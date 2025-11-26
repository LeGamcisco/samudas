<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Sensor;
use App\Models\Stack;
use App\Models\Unit;
use Exception;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stacks = Stack::get(["id","name"]);
        $units = Unit::get(["id","name"]);
        return view("master.sensor.index",compact("stacks","units"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $data = $request->validate([
                "code" => "required",
                "name" => "required",
                "stack_id" => "required",
                "parameter_id" => "required",
                "unit_id" => "required",
                "is_show" => "required",
                "analyzer_ip" => "required",
                "port" => "required",
                "extra_parameter" => "required",
                "is_has_reference" => "required",
                "o2_correction" => "required_if:extra_parameter,2",
                "formula" => "required",
            ]);
            Sensor::create($data);
            return response()->json(["message"=>"Data created successfully"]);
        }catch(Exception $e){
            return response()->json(["message"=>$e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Sensor $sensor)
    {
        $sensor->load(['stack:id,name','unit:id,name']);
        return response()->json(["success"=>true,"data" => $sensor]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sensor $sensor)
    {
        try{
            $data = $request->validate([
                "code" => "required",
                "name" => "required",
                "stack_id" => "required",
                "parameter_id" => "required",
                "unit_id" => "required",
                "is_show" => "required",
                "analyzer_ip" => "required",
                "port" => "required",
                "extra_parameter" => "required",
                "is_has_reference" => "required",
                "o2_correction" => "required_if:extra_parameter,2",
                "formula" => "required",
            ]);
            $sensor->update($data);
            return response()->json(["success" => true,"message"=>"Sensor updated successfully"]);
        }catch(Exception $e){
            return response()->json(["success" => false,"message"=>$e->getMessage()],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sensor $sensor)
    {
        try{
            $sensor->delete();
            return redirect()->back()->with("success","Sensor deleted successfully");
        }catch(Exception $e){
            return redirect()->back()->with("error",$e->getMessage())->withInput();
        }
    }

    public function datatable(Request $request){
        try{
            $where = "1=1";
            $stackId = $request->stackId;
            if($stackId) $where.=" and stack_id = '{$stackId}'";
            $data = Sensor::with(["stack:id,name","unit:id,name"])
             ->whereRaw($where)->select("sensors.*");
            return datatables()->of($data)->toJson();
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);

        }
    }
}
