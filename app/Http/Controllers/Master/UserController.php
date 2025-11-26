<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("master.user.index");
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $data = $request->validate([
                "name" => "required",
                "email" => "required|unique:users,email",
                "password" => "required|confirmed|min:5",
            ]);

            $user = User::create($data);

            return response()->json(["message" => "Data saved successfully","data" => $user], 200);
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(user $user)
    {
        return response()->json(["success"=>true,"data" => $user]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        try{
            $data = $request->validate([
                "name" => "required",
                "email" => "required|unique:users,email,$user->id,id",
                "password" => "nullable:password|min:5",
            ]);

            if(isset($data["password"])){
                $data["password"] = Hash::make($data["password"]);
            }else{
                unset($data["password"]);
            }

            $user->update($data);

            return response()->json(["message" => "Data updated successfully","data" => $user], 200);
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(user $user)
    {
        try{
            if(Auth::user()->id == $user->id){
                return redirect()->back()->with("error","You can't delete yourself");
            }
            if(Auth::user()->id > $user->id){
                return redirect()->back()->with("error","You can't delete higher user");
            }

            $user->delete();

            return redirect()->back()->with("success","Data deleted successfully");
        }catch(Exception $e){
            return redirect()->back()->with("error",$e->getMessage())->withInput();
        }
    }

    public function datatable(Request $request){
        try{
            $data = User::query();
            return datatables()->of($data)->toJson();
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
