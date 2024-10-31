<?php

namespace App\Http\Controllers;

use App\Models\Lot;
use App\Models\ProductCatalog;
use App\Models\Warehouse;
use App\Models\WarehouseMovement;
use App\Models\WarehouseMovementProduct;

use Illuminate\Http\Request;
use Carbon\Carbon;

class LotController extends Controller
{
    public function index()
    {
        $lots = Lot::all();
        $products = ProductCatalog::all();
        $warehouses = Warehouse::where('receive_material', true)->where('active', true)->get();
        
        return view('lot.index', compact('lots', 'products','warehouses'));
    }

    public function store(Request $request)
    {
        // Validar los datos del formulario
        /*$request->validate([
            'product_id' => 'nullable|exists:product_catalog,id',
            'lot_number' => 'required|string|max:255',
            'expiration_date' => 'required|date',
            'amount' => 'required|string|max:255',
        ]);*/


        // Crear un nuevo lote
        $lot = new Lot($request->except('_token'));
        $lot->save();

        /*$currentDate = Carbon::now()->format('Y-m-d');
        $currentTime = Carbon::now()->format('H:i:s');

        $newmovement = new WarehouseMovement();
        $newmovement->date = $currentDate;
        $newmovement->time = $currentTime;
        $newmovement->movement_type_id = 2;
        $newmovement->source_warehouse_id = $request->warehouse_input_id;
        $newmovement->user_id = $request->user_id;
        $newmovement->save();

        $newproductmovement = new WarehouseMovementProduct();
        $newproductmovement->warehouse_movement_id = $newmovement->id;
        $newproductmovement->product_id = $request->product_id;
        $newproductmovement->lot_id = $lot->id;
        $newproductmovement->amount = $request->amount;
        $newproductmovement->save();

        $lots = Lot::all();
        $products = ProductCatalog::where('presentation_id', '!=', 1)->get();
        $warehouses = Warehouse::where('receive_material', 1)
            ->where('active', 1)
            ->get();*/
        return back();
    }

    public function edit($id)
    {
        $lot = Lot::findOrFail($id);
        $products = ProductCatalog::all();
        $warehouses = Warehouse::where('receive_material', true)->where('active', true)->get();

        return view('lot.edit', compact('lot','products', 'warehouses'));
    }
    
    public function update(Request $request, $id)
    {
        $lot = Lot::findOrFail($id);
        $lot->fill($request->all());
        $lot->save();
        return back();
    }

    public function show($id)
    {
        $lot = Lot::findOrFail($id);
        return view('lot.show', compact('lot'));
    }

    public function destroy($id)
    {
        $lot = Lot::findOrFail($id);
        $lot->delete();

        return back();
    }
}
