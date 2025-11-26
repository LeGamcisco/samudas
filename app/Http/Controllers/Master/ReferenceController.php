<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Reference;
use App\Models\Sensor;
use Exception;
use Illuminate\Http\Request;

class ReferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sensors = Sensor::where("is_has_reference",1)->get();
        return view("master.reference.index", compact("sensors"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $data = $request->validate([
                "sensor_id" => "required",
                "range_start" => "required",
                "range_end" => "required",
                "formula" => "required",
            ]);
            Reference::create($data);
            return response()->json(["message"=>"Data created successfully"]);
        }catch(Exception $e){
            return response()->json(["message"=>$e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reference $reference)
    {
        try{
            $data = $request->validate([
                "sensor_id" => "required",
                "range_start" => "required",
                "range_end" => "required",
                "formula" => "required",
            ]);
            $reference->update($data);
            return response()->json(["success" => true,"message"=>"Reference updated successfully"]);
        }catch(Exception $e){
            return response()->json(["success" => false,"message"=>$e->getMessage()],500);
        }
    }


    public function show(Reference $reference){
        return response()->json(["success" => true,"data" => $reference->load(["sensor:id,stack_id,name","sensor.stack:id,name"])]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reference $reference)
    {
        try{
            $reference->delete();
            return redirect()->back()->with("success","Reference deleted successfully");
        }catch(Exception $e){
            return redirect()->back()->with("error",$e->getMessage())->withInput();
        }
    }

    public function datatable(Request $request){
        try{
            $where = "1=1";
            $data = Reference::with(["sensor:id,stack_id,name","sensor.stack:id,name"])
             ->whereRaw($where)->select("references.*");
            return datatables()->of($data)->toJson();
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);

        }
    }
}
