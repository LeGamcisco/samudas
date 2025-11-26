<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index(){
        return view("auth.login");
    }
    public function store(Request $request){
        try{
            $data = $request->validate([
                "email" => "required|email",
                "password" => "required"
            ]);
            if(Auth::attempt($data)){
                return redirect()->intended("/");
            }
            $isExist = User::where("email",$data["email"])->exists();
            if(!$isExist){
                return redirect()->back()->with("error","Email not registered")->withInput();
            }
            return redirect()->back()->with("error","Invalid password")->withInput();
        }catch(Exception $e){

        }
    }
    public function logout(){
        auth()->logout();
        return redirect()->route("login");
    }
}
