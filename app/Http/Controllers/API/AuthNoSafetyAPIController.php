<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use DB;

class AuthNoSafetyAPIController extends AppBaseController
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
    
  	 public function getLoginData(Request $request)
    {    
        $user = DB::select("SELECT * FROM users WHERE email ='".$request->email."' AND password='".$request->password."'");
        if(empty($user)){
             return [
                "status" => false,
                "message" => "Username or Password salah"
             ];
        }else{
              return [
                "status" => true,
                "data" => $user,
                "message" => "Data berhasil didapat"
             ];
        }
    }
}