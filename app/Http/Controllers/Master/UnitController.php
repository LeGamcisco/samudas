<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Exception;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("master.unit.index");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $data = $request->validate([
                'name' => 'required',
            ]);
            Unit::create($data);
            return response()->json(["message"=>"Data saved successfully"]);
        }catch(Exception $e){
            return response()->json(["message"=>$e->getMessage()],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Unit $unit)    
    {
        try{
            return response()->json(["success"=>true,"data" => $unit]);
        }catch(Exception $e){
            return response()->json(["message"=>$e->getMessage()],500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Unit $unit)
    {
        try{
            $data = $request->validate([
                'name' => 'required',
            ]);
            $unit->update($data);
            return response()->json(["message"=>"Data updated successfully"]);
        }catch(Exception $e){
            return response()->json(["message"=>$e->getMessage()],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unit $unit)
    {
        try{
            $unit->delete();
            return response()->json(["success"=>true, "message"=>"Data deleted successfully"]);
        }catch(Exception $e){
            return response()->json(["message"=>$e->getMessage()],500);
        }
    }

    public function datatable(Request $request){
        try{
            $data = Unit::query();
            return datatables()->of($data)->toJson();
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
