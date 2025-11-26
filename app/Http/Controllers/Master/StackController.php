<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Stack;
use Exception;
use Illuminate\Http\Request;

class StackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("master.stack.index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $data = $request->validate([
                "name" => "required",
                "oxygen_reference" => "required",
                "is_show" => "required",
            ]);
            Stack::create($data);
            return redirect()->back()->with("success","Stack created successfully");
        }catch(Exception $e){
            return redirect()->back()->with("error",$e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Stack $stack)
    {
        return response()->json(["success"=>true,"data" => $stack]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Stack $stack)
    {
        try{
            $data = $request->validate([
                "name" => "required",
                "oxygen_reference" => "required",
                "is_show" => "required",
            ]);
            $stack->update($data);
            return redirect()->back()->with("success","Stack updated successfully");
        }catch(Exception $e){
            return redirect()->back()->with("error",$e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stack $stack)
    {
        try{
            $stack->delete();
            return redirect()->back()->with("success","Stack deleted successfully");
        }catch(Exception $e){
            return redirect()->back()->with("error",$e->getMessage())->withInput();
        }
    }

    public function datatable(Request $request){
        try{
            $data = Stack::query();
            return datatables()->of($data)->toJson();
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
