<?php

namespace App\Http\Controllers;

use App\Models\Filenames;
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

    private $size = 50;

    public function getImage(string $url)
    {
        if (!Storage::disk('public')->exists($url)) {
            abort(404);
        }

        $file = Storage::disk('public')->get($url);
        $type = Storage::disk('public')->mimeType($url);

        return response($file, 200)->header('Content-Type', $type);
    }

    public function index(): View
    {
        $products = ProductCatalog::orderBy('id', 'desc')->paginate($this->size);
        return view('product.index', compact('products'));
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
        $product = new ProductCatalog($request->all());

        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:6000',
            ]);

            $file = $request->file('image');
            $filename = $product->name . '.' . $file->getClientOriginalExtension();
            $url = $this->images_path . $filename;
            Storage::disk('public')->put($url, file_get_contents($file));
            $product->image_path = $url;
        }

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

        return redirect()->route('product.index');
    }

    public function storeFile(Request $request, string $id) {
        $url = null;

        $product = ProductCatalog::find($id);
        $filename = Filenames::find($request->input('filename_id'));

        if ($request->hasFile('file')) {
            $request->validate([
                'file' => 'required|file|mimes:jpeg,png,jpg,pdf|max:10000',
            ]);

            $file = $request->file('file');
            $dir = $product->name . $product->id . '/';
            $dir_filename = $filename->name . '.' . $file->getClientOriginalExtension();
            $url = $this->files_path . $dir . $dir_filename;
            Storage::disk('public')->put($url, file_get_contents($file));
        }

        ProductFile::updateOrCreate([
            'product_id' => $product->id,
            'filename_id' => $filename->id,
        ], [
            'path' => $url,
            'expirated_at' => $request->input('expirated_at')
        ]);

        return back();
    }

    public function show(string $id, string $section): View
    {
        $product = ProductCatalog::find($id);
        $filenames = Filenames::where('type', 'product')->get();

        return view('product.show', compact('product', 'filenames', 'section'));
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
        $filenames = Filenames::where('type', 'product')->get();
        $pest_categories = PestCategory::orderBy('category', 'asc')->get();
        $inputs = ProductInput::where('product_id', $id)->get();

        return view(
            'product.edit',
            compact('product', 'line_business', 'application_methods', 'purposes', 'biocides', 'presentations', 'toxics', 'metrics', 'pest_categories', 'inputs', 'filenames', 'section')
        );
    }

    public function update(Request $request, string $id, int $section)
    {
        $appMethodSelected = json_decode($request->input('appMethodSelected'), true);
        $pestSelected = json_decode($request->input('pestSelected'), true);

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
            $product->image_path = $url;
        }

        $product->save();

        if (!empty($appMethodSelected)) {
            $appMethodProducts = Dosage::where('prod_id', $id)->pluck('methd_id')->toArray();
            $appMethodsToDelete = array_diff($appMethodProducts, $appMethodSelected);
            Dosage::where('prod_id', $id)->whereIn('methd_id', $appMethodsToDelete)->delete();

            foreach ($appMethodSelected as $appMethodId) {
                Dosage::updateOrCreate(
                    ['prod_id' => $id, 'methd_id' => $appMethodId],
                    ['prod_id' => $id, 'methd_id' => $appMethodId]
                );
            }
        }


        if ($pestSelected) {
            $productPests = ProductPest::where('product_id', $id)->pluck('pest_id')->toArray();
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
        return back();
    }

    public function downloadFile(string $id, string $file)
	{
		try {
			$product_file = ProductFile::where('product_id', $id)->where('filename_id', $file)->first();

			if (!$product_file) {
				abort(404);
			}

			if (Storage::disk('public')->exists($product_file->path)) {
				return Storage::disk('public')->download($product_file->path);
			}
			return response()->json(['error' => 'File not found.'], 404);
		} catch (\Exception $e) {
			return response()->json(['error' => 'An error occurred while downloading the file.'], 500);
		}
	}

    public function destroyFile(string $id, string $file)
    {
        try {
            $product_file = ProductFile::where('product_id', $id)->where('filename_id', $file)->first();
    
            if (!$product_file) {
                return response()->json(['error' => 'File not found.'], 404);
            }
    
            if (Storage::disk('public')->exists($product_file->path)) {
                Storage::disk('public')->delete($product_file->path);
            }

            $product_file->delete();
    
            return back();
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the file.'], 500);
        }
    }    

    public function input(Request $request, string $id)
    {
        $inputId = $request->input('input_id');
        ProductInput::updateOrCreate([
            'id' => $inputId,
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

    public function search(Request $request)
    {
        if (empty($request->search)) {
            return redirect()->back()->with('error', trans('messages.no_results_found'));
        }

        $searchTerm = '%' . $request->search . '%';

        $presentationIds = Presentation::where('name', 'LIKE', $searchTerm)->get()->pluck('id');

        $products = ProductCatalog::whereIn('presentation_id', $presentationIds)->orWhere('name', 'LIKE', $searchTerm)
            ->orderBy('id', 'desc')
            ->paginate($this->size);
        return view('product.index', compact('products'));
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
