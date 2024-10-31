<?php

namespace App\Http\Controllers;

use App\Models\Metric;
use App\Models\ProductInput;
use App\Models\ProductCatalog;
use App\Models\ProductFile;
use App\Models\Biocide;
use App\Models\LineBusiness;
use App\Models\ApplicationMethod;
use App\Models\Purpose;
use App\Models\ToxicityCategories;
use App\Models\Presentation;

use App\Models\PestCategory;
use App\Models\ProductPest;
use App\Models\Dosage;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    private $images_path = 'products/images/';

    private $files_path = 'products/files/';

    private $size = 30;

    public function getImage(string $url)
    {
        if (!Storage::disk('public')->exists($url)) {
            abort(404);
        }

        $file = Storage::disk('public')->get($url);
        $type = Storage::disk('public')->mimeType($url);

        return response($file, 200)->header('Content-Type', $type);
    }

    public function index(int $page): View
    {
        $products = ProductCatalog::orderBy('id', 'desc')->get();
        $total = ceil($products->count() / $this->size);
        $min = ($page * $this->size) - $this->size;

        $products = collect(array_slice($products->toArray(), $min, $this->size))->map(function ($product) {
            return new ProductCatalog($product);
        });

        return view('product.index', compact('products', 'page', 'total'));
    }

    public function create()
    {
        $line_business = LineBusiness::all();
        $application_methods = ApplicationMethod::all();
        $purposes = Purpose::all();
        $biocides = Biocide::all();
        $presentations = Presentation::all();
        $toxics = ToxicityCategories::all();
        $pest_categories = PestCategory::orderBy('category', 'asc')->get();
        $metrics = Metric::all();

        return view(
            'product.create',
            compact('line_business', 'application_methods', 'purposes', 'biocides', 'presentations', 'toxics', 'pest_categories', 'metrics')
        );
    }

    public function store(Request $request): RedirectResponse
    {
        $url = null;
        $files = ProductFile::create();
        $product = new ProductCatalog($request->all());

        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:6000',
            ]);

            $file = $request->file('image');
            $filename = $product->name . '.' . $file->getClientOriginalExtension();
            $url = $this->images_path . $filename;
            Storage::disk('public')->put($url, file_get_contents($file));
        }

        $product->image_path = $url;
        $product->files_id = $files->id;
        $product->save();

        $selected_pests = json_decode($request->input('pestSelected'));
        $selected_appMethod = json_decode($request->input('appMethodSelected'), true);

        if (!empty($selected_appMethod)) {
            foreach ($selected_appMethod as $appID) {
                Dosage::insert([
                    'prod_id' => $product->id,
                    'methd_id' => $appID,
                ]);
            }
        }

        if (!empty($selected_pests)) {
            foreach ($selected_pests as $pest_id) {
                ProductPest::insert([
                    'product_id' => $product->id,
                    'pest_id' => $pest_id,
                ]);
            }
        }

        return redirect()->route('product.index', ['page' => 1]);
    }

    public function show(string $id, string $section): View
    {
        $product = ProductCatalog::find($id);

        return view('product.show', compact('product', 'lineBs', 'appMethods', 'press', 'porps', 'biogyts', 'biogs', 'biotps', 'toxics', 'section'));
    }

    public function edit(string $id, string $section)
    {
        $product = ProductCatalog::find($id);

        $line_business = LineBusiness::all();
        $application_methods = ApplicationMethod::all();
        $purposes = Purpose::all();
        $biocides = Biocide::all();
        $presentations = Presentation::all();
        $toxics = ToxicityCategories::all();
        $metrics = Metric::all();

        $pest_categories = PestCategory::orderBy('category', 'asc')->get();
        $inputs = ProductInput::where('product_id', $id)->get();

        return view(
            'product.edit',
            compact('product', 'line_business', 'application_methods', 'purposes', 'biocides', 'presentations', 'toxics', 'metrics', 'pest_categories', 'inputs', 'section')
        );
    }

    public function update(Request $request, string $id, int $section)
    {
        $product = ProductCatalog::find($id);
        $product->fill($request->all());

        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:6000',
            ]);

            $file = $request->file('image');
            $filename = $product->name . '.' . $file->getClientOriginalExtension();
            $url = $this->images_path . $filename;
            Storage::disk('public')->put($url, file_get_contents($file));
        }
        $product->image_path = $url;
        $product->save();

        $appMethodSelected = json_decode($request->input('appMethodSelected'), true);
        if (!empty($appMethodSelected)) {
            $appMethodServices = Dosage::where('prod_id', $id)->pluck('methd_id')->toArray();
            $appMethodsToDelete = array_diff($appMethodServices, $appMethodSelected);
            Dosage::where('prod_id', $id)->whereIn('methd_id', $appMethodsToDelete)->delete();

            foreach ($appMethodSelected as $appMethodId) {
                Dosage::updateOrCreate(
                    ['prod_id' => $id, 'methd_id' => $appMethodId],
                    ['prod_id' => $id, 'methd_id' => $appMethodId]
                );
            }
        }



        return back();
        /*
        if ($product) {

            if ($section == 1) {
                // Datos basicos
                if ($request->hasFile('photo')) {
                    $img = $request->file('photo');
                    $extension = $img->getClientOriginalExtension(); // getting image extension
                    $filename = 'P' . time() . '.' . $extension;
                    $img->move($this->images_path, $filename);
                    $img_path = $this->images_path . $filename;
                    $product->photo = $img_path;
                }

                $product->name = $request->name;
                $product->bussiness_name = $request->bussiness_name;
                $product->bar_code = $request->bar_code;
                $product->status = $request->status;
                $product->obsolete = $request->obsolete;
                $product->presentation_id = $request->presentation;
                $product->linebuss_id = $request->lineB;
                $product->description = $request->description;
                $product->execution_indications = $request->indications_execution;
                $product->metric = $request->metric;
               
            }

            if ($section == 2) {
                // Detalles tecnicos
                $product->manufacturer = $request->manufacturer;
                $product->register_number = $request->register_number;
                $product->validity_date = $request->valid_date;
                $product->active_ingredient = $request->activin;
                $product->per_active_ingredient = $request->per_active_ingredient;
                $product->dosage = $request->dosage;
                $product->safety_period = $request->safety_period;
                $product->residual_effect = $request->residual_effect;
                $product->biocidesgyt_id = $request->biotps;
                $product->purpose_id = $request->purpose;
            }

            if ($section == 3) {
                $product->toxicity = $request->toxicity;
                $product->toxicity_categ_id = $request->toxic;
            }

            if ($section == 4) {
                $dataE = $product->economicData;
                if ($dataE) {
                    $dataE->purchase_price = $request->econ_price;
                    $dataE->min_purchase_unit = $request->mult_purchase_unit;
                    $dataE->mult_purchase = $request->mult_purchase;
                    $dataE->supplier_id = $request->supplier_id;
                    $opcion = $request->input('option');

                    if ($opcion === 'show') {
                        $dataE->selling = true;
                    } elseif ($opcion === 'hide') {
                        $dataE->selling = false;
                    } else {
                        $dataE->selling = null;
                    }
                    $dataE->selling_price = $request->selling_price;
                    $dataE->subaccount_purchases = $request->subaccount_purchases;
                    $dataE->subaccount_sales = $request->subaccount_sales;
                    $dataE->save();
                }
            }
            if ($section == 5) {
                $success = null;
                $error = null;
                $warning = null;


                $data = $request->all();
                if (isset($data['files']) && isset($data['prod_id']) && isset($data['file_type'])) {
                    $files = $data['files'];
                    $id = $data['prod_id'];
                    $type = $data['file_type'];
                } else {
                    $error = 'No se encontraron los archivos';
                    return response()->json([
                        'success' => $success,
                        'error' => $error
                    ]);
                }

                $productfile = ProductFile::where('product_id', $id)->first();

                if ($productfile == null) {
                    $error = 'No se encontró el producto';
                    return response()->json([
                        'success' => $success,
                        'error' => $error
                    ]);
                }

                if ($type == "rp_specification") {
                    foreach ($files as $file) {
                        $path = ProductController::setFile($file, $id, "rp_specification");
                        $productfile->rp_specification = $path;
                        $productfile->save();
                    }
                }

                if ($type == "techical_specification") {
                    foreach ($files as $file) {
                        $path = ProductController::setFile($file, $id, "techical_specification");
                        $productfile->techical_specification = $path;
                        $productfile->save();
                    }
                }

                if ($type == "segurity_specification") {
                    foreach ($files as $file) {
                        $path = ProductController::setFile($file, $id, "segurity_specification");
                        $productfile->segurity_specification = $path;
                        $productfile->save();
                    }
                }

                if ($type == "register_specification") {
                    foreach ($files as $file) {
                        $path = ProductController::setFile($file, $id, "register_specification");
                        $productfile->register_specification = $path;
                        $productfile->save();
                    }
                }

                if ($type == "sanitary_register") {
                    foreach ($files as $file) {
                        $path = ProductController::setFile($file, $id, "sanitary_register");
                        $productfile->sanitary_register = $path;
                        $productfile->save();
                    }
                }
            }
            if ($section == 6) {
                $pestSelected = json_decode($request->input('pestSelected'), true);
                $productPests = ProductPest::where('product_id', $id)->pluck('pest_id')->toArray();
                if ($pestSelected != null) {
                    $pestsToDelete = array_diff($productPests, $pestSelected);
                    ProductPest::where('product_id', $id)->whereIn('pest_id', $pestsToDelete)->delete();
                    if (!empty($pestSelected)) {
                        foreach ($pestSelected as $pestId) {
                            ProductPest::updateOrCreate(
                                ['product_id' => $id, 'pest_id' => $pestId],
                                ['product_id' => $id, 'pest_id' => $pestId]
                            );
                        }
                    }
                }
            }
            if ($section == 7) {
                $productsunits = ProductUnit::where('product_id', $id)->get();
                foreach ($productsunits as $pu) {
                    if ($pu->measure_type == 'a' && $request->option_almacenaje) {
                        $pu->update([
                            'measure' => $request->option_almacenaje,
                            'measure_unit' => $request->selectedOption_almacenaje,
                            'unit_number' => $request->unidad_almacenaje
                        ]);
                    } elseif ($pu->measure_type == 'c' && $request->option_compra) {
                        $pu->update([
                            'measure' => $request->option_compra,
                            'measure_unit' => $request->selectedOption_compra,
                            'unit_number' => $request->unidad_compra
                        ]);
                    } elseif ($pu->measure_type == 'v' && $request->option_venta) {
                        $pu->update([
                            'measure' => $request->option_venta,
                            'measure_unit' => $request->selectedOption_venta,
                            'unit_number' => $request->unidad_venta
                        ]);
                    }
                }
            }

            $product->save();
        } else {
            $error = 'No se encontro el producto';
        }

        return redirect()->back();

        if ($request->econom) {
            $dataE = EconomicDataProduct::where('id', $product->economic_data_id)->first();

            if ($dataE) {
                $dataE->purchase_price = $request->econ_price;
                $dataE->min_purchase_unit = $request->mult_purchase_unit;
                $dataE->mult_purchase = $request->mult_purchase;
                $dataE->supplier_id = $request->supplier_id;
                $opcion = $request->input('option');

                if ($opcion === 'show') {
                    $dataE->selling = true;
                } elseif ($opcion === 'hide') {
                    $dataE->selling = false;
                } else {
                    $dataE->selling = null;
                }
                $dataE->selling_price = $request->selling_price;
                $dataE->subaccount_purchases = $request->subaccount_purchases;
                $dataE->subaccount_sales = $request->subaccount_sales;
                $dataE->save();
                $products = ProductCatalog::all();
                return redirect()->route('product.index', compact('products', 'error', 'success', 'warning'));
            }
        } else {
            $product_type = session()->get('product_type');
        }*/
    }

    public function input(Request $request, string $id)
    {
        ProductInput::updateOrCreate([
            'product_id' => $id,
            'application_method_id' => $request->input('application_method_id')
        ], [
            'zone_m2' => $request->input('zone_m2'),
            'cant' => $request->input('cant'),
            'cost' => $request->input('cost')
        ]);

        return back();
    }

    public function destroyInput(string $id)
    {
        ProductInput::find($id)->delete();
        return back();
    }

    public function search(Request $request, string $page)
    {
        if (empty($request->search)) {
            return redirect()->back()->with('error', trans('messages.no_results_found'));
        }

        $searchTerm = '%' . $request->search . '%';

        $presentationIds = Presentation::where('name', 'LIKE', $searchTerm)->get()->pluck('id');

        $products = ProductCatalog::whereIn('presentation_id', $presentationIds)->orWhere('name', 'LIKE', $searchTerm)
            ->orderBy('id', 'desc')
            ->get();

        $total = ceil($products->count() / $this->size);
        $min = ($page * $this->size) - $this->size;

        $products = collect(array_slice($products->toArray(), $min, $this->size))->map(function ($product) {
            return new ProductCatalog($product);
        });

        return view('product.index', compact('products', 'page', 'total'));
    }

    private function setFile($file, $id, $name)
    {
        $file_name = $name . '.' . $file->getClientOriginalExtension();
        $path = $this->files_path . $id;
        $file->storeAs($path, $file_name);
        return $path . '/' . $file_name;
    }

    public function file_download(string $id, string $file)
    {
        $productfile = ProductFile::where('product_id', $id)->first();
        if ($file == "rp_specification") {
            return response()->download(storage_path('app/' . $productfile->rp_specification));
        } else if ($file == "techical_specification") {
            return response()->download(storage_path('app/' . $productfile->techical_specification));
        } else if ($file == "segurity_specification") {
            return response()->download(storage_path('app/' . $productfile->segurity_specification));
        } else if ($file == "register_specification") {
            return response()->download(storage_path('app/' . $productfile->register_specification));
        } else if ($file == "sanitary_register") {
            return response()->download(storage_path('app/' . $productfile->sanitary_register));
        }
    }

    public function file_delete(string $id, string $file, string $section)
    {
        $productfile = ProductFile::where('product_id', $id)->first();
        if ($file == "rp_specification") {
            $productfile->rp_specification = null;
        } else if ($file == "techical_specification") {
            $productfile->techical_specification = null;
        } else if ($file == "segurity_specification") {
            $productfile->segurity_specification = null;
        } else if ($file == "register_specification") {
            $productfile->register_specification = null;
        } else if ($file == "sanitary_register") {
            $productfile->sanitary_register = null;
        }
        $productfile->save();
        return redirect()->route('product.edit', ['id' => $id, 'section' => $section]);
    }

    public function destroy(string $id)
    {
        $product = ProductCatalog::find($id);
        if ($product) {
            $product->delete();
            return redirect()->route('product.index');
        }
    }
}
