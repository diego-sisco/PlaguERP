<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Customer;
use App\Models\ProductCatalog;
use App\Models\RotationPlan;
use App\Models\RotationPlanProduct;
use App\Models\Service;
use Illuminate\Http\Request;
use View;

class RotationPlanController extends Controller
{
    private $size = 50;

    public function index(string $contractId)
    {
        $contract = Contract::find($contractId);
        $rotation_plans = RotationPlan::where('contract_id', $contract->id)->paginate($this->size);
        return view('rotation-plan.index', compact('contract', 'rotation_plans'));
    }

    public function create(string $contractId)
    {
        $contract = Contract::find($contractId);
        //$contracts = Contract::orderBy('id', 'asc')->get();
        $customers = Customer::orderBy('name', 'asc')->get();
        $products = ProductCatalog::orderBy('name', 'asc')->get();
        return view('rotation-plan.create', compact('contract', 'customers', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $products = json_decode($request->input('products'));
        $contract = Contract::find($request->input('contractId'));

        if(empty($products)) {
            $message = 'Debes agregar al menos 1 producto';
            session()->flash('success', $message);
            return back();
        }
        
        $rotation_plan = new RotationPlan();
        $rotation_plan->fill($request->all());
        $rotation_plan->contract_id = $contract->id;
        $rotation_plan->customer_id = $contract->customer_id;
        $rotation_plan->no_review = $contract->rotationPlans->count()+1;
        $rotation_plan->save();

        foreach($products as $product) {
            RotationPlanProduct::create([
                'rotation_plan_id' => $rotation_plan->id,
                'product_id' => $product->id,
                'start_date' => $product->start_date,
                'end_date' => $product->end_date,
                'color' => $product->color, 
            ]);
        }

        return redirect()->route('rotation.index', ['contractId' => $contract->id]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {
        $product_data = [];
        $rotation_plan = RotationPlan::find($id);
        foreach($rotation_plan->products as $index => $item) {
            $product_data[] = [
                'index' => $index,
                'id' => $item->product->id,
                'name' => $item->product->name,
                'biocide_type' => $item->product->biocide->type ?? null,
                'biocide_group' => $item->product->biocide->group ?? null,
                'active_ingredient' => $item->product->active_ingredient ?? null,
                'color' => $item->color ?? null,
                'start_date' => $item->start_date,
                'end_date' => $item->end_date,
                'status' => true
            ];
        }

        return view('rotation-plan.edit', compact('rotation_plan', 'product_data'));
    }

    public function searchProduct(Request $request) {
        $data = [];
        $search = json_decode($request->input('search'));
        $productTerm = '%' . $search . '%';
        $products = ProductCatalog::where('name', 'LIKE', $productTerm)->orWhere('active_ingredient', 'LIKE', $productTerm)->get();
        foreach($products as $product) {
            $data[] = [
                'id' => $product->id,
                'name' => $product->name,
                'biocide_type' => $product->biocide->type ?? null,
                'biocide_group' => $product->biocide->group ?? null,
                'active_ingredient' => $product->active_ingredient ?? null,
                'status' => false
            ];
        }
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $products = json_decode($request->input('products'));
        $productIds = array_map(fn($item) => $item->id, $products);

        if(empty($products)) {
            $message = 'Debes agregar al menos 1 producto';
            session()->flash('success', $message);
            return back();
        }

        $rotation_plan = RotationPlan::find($id);
        $rotation_plan->fill(attributes: $request->all());
        $rotation_plan->save();

        foreach ($products as $product) {
            RotationPlanProduct::updateOrCreate(
                [
                    'rotation_plan_id' => $rotation_plan->id,
                    'product_id' => $product->id,
                ],
                [
                    'start_date' => $product->start_date,
                    'end_date' => $product->end_date,
                    'color' => $product->color,
                ]
            );
        }        

        $products_delete = array_diff($rotation_plan->products->pluck('product_id')->toArray(), $productIds);
        if(!empty($products_delete)) {
            RotationPlanProduct::where('rotation_plan', $rotation_plan->id)->whereIn('product_id', $products_delete)->delete();
        }

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rotation_plan = RotationPlan::find($id);
        if($rotation_plan) {
            RotationPlanProduct::where('rotation_plan_id', $rotation_plan->id)->delete();
            $rotation_plan->delete();
        }
        return back();
    }
}
