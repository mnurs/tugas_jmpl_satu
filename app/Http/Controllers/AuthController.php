<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use App\Mail\RegisterMail;
use Mail;
use Session;
use Illuminate\Support\Carbon;

class AuthController extends Controller
{ 

    public function getLogin()
    {
        return view('auth.login');
    }

    public function setLogin(Request $request)
    {    
        if(Session::get('captcha')){
            $request->validate([ 
                'g-recaptcha-response' => 'required|captcha' 
            ],
            [
                'g-recaptcha-response.required' => "Please verify I am not a robot" 
            ]);
        } 
        $request->validate([  
            'email'=>'required',
            'password'=>'required'
        ]);
        $user = User::where('email', $request->email)->first(); 
        if (!empty($user)) {
            $userV = User::where('email', $request->email)->whereNull('email_verified_at')->first(); 
            if(empty($userV)){ 
                if (Hash::check($request->password, $user->password)) { 
                    User::where('email', $request->email)->
                    update([
                       'wrong_amount' => 0
                    ]);
                    Session()->forget('captcha'); 
                    \auth()->login($user, true);
                    return redirect('home');
                } else { 
                    if($user->wrong_amount >= 2){
                        Session::put('captcha', true);
                    }
                    $jml = $user->wrong_amount + 1;
                    User::where('email', $request->email)->
                    update([
                       'wrong_amount' => $jml
                    ]);
                    return view('auth.login')->with('msg', 'Upps Wrong Password');
                }
            }else{
               return view('auth.login')->with('msg', 'Please verify your email'); 
            }
            
        } else {
            $jml = Session::get('jmlSalah'); 
            if($jml >= 2){
                Session::put('captcha', true);
            } 
            $ttl = $jml + 1;
            Session::put('jmlSalah', $ttl);
            return view('auth.login')->with('msg', 'Email Not found');
        }
    }
    public function register()
    {
        return view('auth.register');
    }

    public function registerPost(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6'
        ]); 
        $user = User::where('email', $request->email) ->first(); 
        if(empty($user)){
            $user = User::create([
                'name' => $request->name,
                'email' =>$request->email,
                'password' => Hash::make($request->password),
                'email_verified_at' => Carbon::now()
            ]);
            $mailData = [ 
                'id' => $user->id
            ];
             
            // Mail::to($request->email)->send(new RegisterMail($mailData));
            // return redirect('info_verify/'.$user->id);
            return view('auth.register')->with('msg', 'Success Registered');
        }else{
            $userV = User::where('email', $request->email)->whereNull('email_verified_at')->first(); 
            if(empty($userV)){ 
                return view('auth.register')->with('msg', 'Your Email Has Been Registered');
            }else{
               return view('auth.register')->with('msg', 'Please verify your email'); 
            }
        }
        
    } 
    public function infoVerifikasiForm(Request $request)
    {
         return view('auth.info_verify');
    }

    public function resendVerifikasiForm(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        $mailData = [
            'title' => 'Mail from ItSolutionStuff.com',
            'body' => 'This is for testing email using smtp.',
            'id' => $user->id
        ];
         
        // Mail::to($user->email)->send(new RegisterMail($mailData));
         return redirect('info_verify/'.$user->id);
    }



    public function verifikasiForm($id)
    { 
         return view('auth.verify');
    }

    public function verifikasi(Request $request)
    {
        $userV = User::where('id', $request->id)->whereNull('email_verified_at')->first();
        if(empty($userV)){
            return view('auth.done_verify');
        }else{

            $google2fa = app('pragmarx.google2fa');
 
            $google2fa_secret = $google2fa->generateSecretKey();

            $QR_Image = $google2fa->getQRCodeInline(
                config('app.name'),
                $userV->email,
                $google2fa_secret
            ); 

            $user = User::where('id', $request->id)->
                update([
                   'email_verified_at' => date('Y-m-d'),
                   'google2fa_secret'=>$google2fa_secret
                ]); 
            
            return view('google2fa.register', ['QR_Image' => $QR_Image, 'secret' => $google2fa_secret]);
            // return view('auth.success_verify');
        }
    }
    public function getLogout()
    { 
        Session()->forget('jmlSalah'); 
        Session()->forget('captcha'); 
        Session()->forget('username'); 
        Session()->forget('name');  
        Session::flush();
        \Auth::logout();
        return redirect('login');
    }


     //tambahkan script di bawah ini
    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }
  
  
    //tambahkan script di bawah ini 
    public function handleProviderCallback(\Request $request)
    {
        try {
            $user_google    = Socialite::driver('google')->user();
            $user           = User::where('email', $user_google->getEmail())->
                                    whereNotNull('email_verified_at')->
                                    first();

            //jika user ada maka langsung di redirect ke halaman home
            //jika user tidak ada maka simpan ke database
            //$user_google menyimpan data google account seperti email, foto, dsb

            if($user != null){
                \auth()->login($user, true);
                Session()->forget('jmlSalah'); 
                Session()->forget('captcha'); 
                return redirect()->route('home');
            }else{

                $google2fa = app('pragmarx.google2fa');
 
                $google2fa_secret = $google2fa->generateSecretKey();

                $QR_Image = $google2fa->getQRCodeInline(
                    config('app.name'),
                    $user_google->getEmail(),
                    $google2fa_secret
                );  
                
                $create = User::Create([
                    'email'             => $user_google->getEmail(),
                    'name'              => $user_google->getName(),
                    'password'          => 0,
                    'email_verified_at' => date('Y-m-d'),
                    'google2fa_secret'=>$google2fa_secret
                ]);
        
                
                \auth()->login($create, true);
                return view('google2fa.register', ['QR_Image' => $QR_Image, 'secret' => $google2fa_secret]);
                // return redirect()->route('home');
            }

        } catch (\Exception $e) {
            return redirect()->route('login');
        }


    }
}
