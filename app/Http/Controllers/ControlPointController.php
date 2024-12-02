<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

use App\Models\ApplicationMethod;
use App\Models\ControlPoint;
use App\Models\ControlPointAppMethods;
use App\Models\ControlPointProduct;
use App\Models\Purpose;
use App\Models\LineBusiness;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\ControlPointQuestion;
use App\Models\ProductCatalog;

class ControlPointController extends Controller
{
    private $size = 50;

    public function index()
    {
        $points = ControlPoint::orderBy('id', 'desc')->paginate($this->size);
        return view('control_point.index', compact('points'));
    }

    public function create()
    {
        $products = ProductCatalog::where('presentation_id', '!=', 1)->get();
        $devices = ProductCatalog::where('presentation_id', 1)->get();
        return view('control_point.create', compact('products', 'devices'));
    }

    public function store(Request $request): RedirectResponse
    {
        $point = new ControlPoint($request->all());
        $point->save();

        $products = json_decode($request->input('selectedProducts'), true);
     
        if (!empty($products)) {
            foreach ($products as $productId) {
                ControlPointProduct::insert([
                    'control_point_id' => $point->id,
                    'product_id' => $productId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return redirect()->route('point.index');
    }

    public function edit(string $id, string $section)
    {
        $point = ControlPoint::find($id);
        $questions = Question::all();
        $products = ProductCatalog::where('presentation_id', '!=', 1)->get();
        $devices = ProductCatalog::where('presentation_id', 1)->get();

        return view('control_point.edit', compact('point', 'questions', 'products', 'devices', 'section'));
    }

    public function show(string $id, string $section): View
    {
        $point = ControlPoint::find($id);
        $products = ProductCatalog::where('presentation_id', 1)->get();
        $products_included = ProductCatalog::where('presentation_id', '!=', 1)->get();
        $lineBs = LineBusiness::all();
        $porps = Purpose::all();
        $controlPoint_questions = ControlPointQuestion::where('control_point_id', $id)->get();
        $question_options = QuestionOption::all();
        $quests = Question::all();
        return view('control_point.show', compact('quests', 'question_options', 'controlPoint_questions', 'porps', 'lineBs', 'point', 'products', 'products_included', 'section'));
    }

    public function update(Request $request, string $id)
    {
        $point = ControlPoint::findOrFail($id);
        $point->fill($request->all());
        $point->save();

        $products = json_decode($request->input('selectedProducts'), true);
        if (!empty($products)) {
            $pointProducts = ControlPointProduct::where('control_point_id', $id)->pluck('product_id')->toArray();
            $productsToDelete = array_diff($pointProducts, $products);

            ControlPointProduct::where('control_point_id', $id)->whereIn('product_id', $productsToDelete)->delete();
            foreach ($products as $productId) {
                ControlPointProduct::updateOrCreate(
                    ['control_point_id' => $id, 'product_id' => $productId],
                    ['control_point_id' => $id, 'product_id' => $productId]
                );
            }
        }

        return back();
    }

    public function destroy(string $id)
    {
        $point = ControlPoint::find($id);
        if ($point) {
            $point->delete();
        }
        return back();
    }

    public function search(Request $request)
    {
        if(empty($request->search)) {
			return redirect()->back()->with('error', trans('messages.no_results_found'));
		}

		$searchTerm = '%' . $request->search . '%';

		$points = ControlPoint::where('name', 'LIKE', $searchTerm)
			->orderBy('id', 'desc')
            ->paginate($this->size);

		if ($points->isEmpty()) {
			session()->flash('error', trans('messages.no_results_found'));
            $points = ControlPoint::orderBy('id', 'desc')->paginate($this->size);
		}

        return view('control_point.index', compact('points'));
    }
}
