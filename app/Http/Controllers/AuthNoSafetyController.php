<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class AuthNoSafetyController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getLogin()
    {
        return view('auth_not_safety.login');
    }

    public function setLogin(Request $request)
    {    
        $user = DB::select("SELECT * FROM users WHERE email ='".$request->email."' AND password='".$request->password."'");
        if(empty($user)){
             return view('auth_not_safety.login')->with('msg', 'Username atau Password salah');
        }else{
              return view('home');
        }
    }
    public function register()
    {
        return view('auth_not_safety.register');
    }

    public function registerPost(Request $request)
    { 
        $user = DB::select("SELECT * FROM users WHERE email ='". $request->email. "'");
        if(empty($user)){
             DB::statement("INSERT INTO users (name, email , password) VALUES ('".$request->name."', '".$request->email."', '".$request->password."');");

             return view('auth_not_safety.register')->with('msg', 'Register Berhasil');
        }else{
            return view('auth.register')->with('msg', 'Your Email Has Been Registered');
        }
       
    } 
}
