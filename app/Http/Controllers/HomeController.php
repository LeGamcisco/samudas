<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use App\Models\Sensor;
use App\Models\Stack;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class HomeController extends Controller
{
    public function index($stackId = null){
        if($stackId == null) $stackId = Stack::orderBy("id")->first()->id ?? null;
        if(!$stackId) return redirect()->route("master.stack.index");
        $config = Configuration::find(1);
        if($config->is_rca == 1 && $config->rca_stack == $stackId){
            $sensors  = Sensor::with(["unit:id,name"])->selectRaw("id,unit_id,name")->whereRaw("stack_id='$stackId' and extra_parameter in (1,2) and is_show = 1")->orderBy("id")->get();
        }else{
            $sensors  = Sensor::with(["unit:id,name"])->selectRaw("id,unit_id,name")->whereRaw("stack_id='$stackId' and is_show = 1")->orderBy("id")->get();
        }
        $stacks = Stack::get(["id","name"]);
        $stack = Stack::find($stackId);
    return view("home.index", compact("sensors","stacks","stack","stackId","config"));
    }
    public function values($stackId = null){
        if(!$stackId) return response()->json(["message"=>"Stack not found"]);
        $stack = Stack::find($stackId);
        $config = Configuration::find(1);
        if($config->is_rca == 1 && $config->rca_stack == $stack->id){
            $sensors  = Sensor::whereRaw("stack_id='$stackId' and is_show = 1")
                ->with(["unit:id,name","value_rca:id,sensor_id,measured,corrected,raw,updated_at"])
                ->get(["id","name","unit_id"]);
        }else{
            $sensors  = Sensor::whereRaw("stack_id='$stackId' and is_show = 1")
                ->with(["unit:id,name","value:id,sensor_id,measured,raw,updated_at"])
                ->get(["id","name","unit_id"]);
        }
        return response()->json(["success"=>true,"data" => $sensors]);
    }

    /*
     * Change to RCA Mode 
     *
     * @return void
     */
    public function store(Request $request){
        try{
            $data = $request->validate([
                "stack_id" => "required",
            ]);
            $config = Configuration::find(1);
            $isRca = $config->is_rca;
            if(!$config){
                $config = new Configuration();
                $config->id = 1;
            }
            if($config->is_rca == 0 && ($config->rca_stack != null)){
                $stack = Stack::find($config->rca_stack);
                return redirect()->back()->with("error","RCA Mode Already Enabled on Stack ".($stack->name ?? "-"))->withInput();
            }
            $config->is_rca = $isRca == 0 ? 1 : 0;
            $config->rca_stack = $config->is_rca == 1 ? $data["stack_id"] : null;
            $config->save();
            return redirect()->back()->with("success","RCA Mode ".($config->is_rca == 1 ? "Enabled" : "Disabled"));
        }catch(Exception $e){
            return redirect()->back()->with("error",$e->getMessage())->withInput();
        }
    }
}
