<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\Status;

class BranchController extends Controller {
    private $states_route = 'datas/json/Mexico_states.json';
    private $cities_route = 'datas/json/Mexico_cities.json';
    public function index()
    {
        $branches = Branch::all();
        $message = null;
        $states = json_decode(file_get_contents(public_path($this->states_route)), true); 
        $cities = json_decode(file_get_contents(public_path($this->cities_route)), true);
        return view('branch.index', compact('branches', 'message', 'states', 'cities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $branches = Branch::all();
        $message = null;
        $states = json_decode(file_get_contents(public_path($this->states_route)), true); 
        $cities = json_decode(file_get_contents(public_path($this->cities_route)), true);
        return view('branch.create', compact('branches', 'message', 'states', 'cities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $branch = new Branch();
        $branch->fill($request->all());
        $branch->status_id = 2;
        $branch->save();
        return redirect()->route('branch.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, int $section)
    {
        $branch = Branch::find($id);

        return view('branch.show', compact('branch', 'section'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, int $section)
    {
        $branch = Branch::find($id);
        $states = json_decode(file_get_contents(public_path($this->states_route)), true); 
        $cities = json_decode(file_get_contents(public_path($this->cities_route)), true);
        return view('branch.edit', compact('branch', 'section', 'states', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $branch = Branch::find($id);
        $branch->fill($request->all());
        $branch->save();
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $branch = Branch::findOrFail($id);
        $branch->status_id = 3;
        $branch->save();
        return back();
    }
}
