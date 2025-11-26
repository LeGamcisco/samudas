<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use App\Models\Stack;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ConfigurationController extends Controller
{
    public function index(){
        $config = Configuration::first();
        if(empty($config)){
            Artisan::call("db:seed --class=ConfigurationSeeder");
            return redirect()->back()->with("success","Configuration Created. Please Re-Login to see changes");
        }
        $stack = Stack::find($config->rca_stack);
        return view("configuration.index",compact("config","stack"));
    }
    public function store(Request $request){
        try{
            $config = Configuration::find(1);
            $data = $request->validate([
                "name" => "required",
                "server_ip" => "required",
                "server_url" => "required",
                "server_apikey" => "nullable",
            ]);
            if(empty($data['server_apikey'])) unset($data['server_apikey']);
            $config->update($data);
            return redirect()->back()->with("success","Configuration Updated");
        }catch(Exception $e){
            return redirect()->back()->with("error",$e->getMessage())->withInput();
        }
    }
}
