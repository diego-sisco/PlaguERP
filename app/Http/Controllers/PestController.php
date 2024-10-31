<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\PestCatalog;
use App\Models\PestCategory;
use Psy\CodeCleaner\IssetPass;
use Symfony\Contracts\Service\Attribute\Required;

class PestController extends Controller
{
    private static $images_path = 'storage/pest_images/';

    private $size = 30;

    public function index(int $page): View
    {
        $pests = PestCatalog::orderBy('id', 'desc')->get();

        $total = ceil($pests->count() / $this->size);
        $min = ($page * $this->size) - $this->size;

        $pests = collect(array_slice($pests->toArray(), $min, $this->size))->map(function ($pest) {
            return new PestCatalog($pest);
        });

        return view('pest.index', compact('pests', 'page', 'total'));
    }
    public function create()
    {
        $pestCategories = PestCategory::all();
        return view('pest.create', compact('pestCategories'));
    }

    public function store(Request $request): RedirectResponse
    {

        $pest = new PestCatalog();
        $pest->fill($request->all());
        if ($request->hasFile('img')) {
            $request->validate([
                'img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);
            $img = $request->file('img');
            $extension = $img->getClientOriginalExtension(); // getting image extension
            $filename = 'Pest' . time() . '.' . $extension;
            $img->move(PestController::$images_path, $filename);
            $img_path = PestController::$images_path . $filename;
            $pest->image = $img_path;
        }
        $pest->save();

        return redirect()->route('pest.index', ['page' => 1]);
    }

    public function show(string $id): View
    {
        $categs = PestCategory::all();
        $pest = PestCatalog::find($id);
        return view('pest.show', compact('pest', 'categs'));
    }

    public function edit(string $id)
    {
        $categs = PestCategory::all();
        $pest = PestCatalog::find($id);
        return view('pest.edit', compact('pest', 'categs'));
    }

    public function update(Request $request, string $id)
    {
        $pest = PestCatalog::find($id);
        $pest->fill($request->all());
        if ($request->hasFile('img')) {
            $img = $request->file('img');
            $extension = $img->getClientOriginalExtension(); // getting image extension
            $filename = 'Pest' . time() . '.' . $extension;
            $img->move(PestController::$images_path, $filename);
            $img_path = PestController::$images_path . $filename;
            $pest->image = $img_path;
        }
        $pest->save();
        return redirect()->route('pest.index', ['page' => 1]);
    }

    public function search(Request $request, int $page)
    {
        if (empty($request->search)) {
            return redirect()->back()->with('error', trans('messages.no_results_found'));
        }

        $searchTerm = '%' . $request->search . '%';

        $categoryIds = PestCategory::where('category', 'LIKE', $searchTerm)
            ->orderBy('id', 'desc')
            ->pluck('id');

        $pests = PestCatalog::where('name', 'LIKE', $searchTerm)
            ->orWhereIn('pest_category_id', $categoryIds)
            ->orderBy('id', 'desc')
            ->get();


        if ($pests->isEmpty()) {
            $error = 'No se encontraron resultados de la búsqueda';
        }

        $total = ceil($pests->count() / $this->size);
        $min = ($page * $this->size) - $this->size;

        $pests = collect(array_slice($pests->toArray(), $min, $this->size))->map(function ($pest) {
            return new PestCatalog($pest);
        });

        return view(
            'pest.index',
            compact(
                'pests',
                'page',
                'total'
            )
        );
    }

    public function destroy(string $id)
    {
        $pest = PestCatalog::find($id);
        if ($pest) {
            $pest->delete();
            return redirect()->route('pest.index', ['page' => 1]);
        }
    }
}
