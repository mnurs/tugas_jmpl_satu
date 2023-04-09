<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;

class RegisterTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_tidak_memasukkan_full_name()
    {
        $nama = rand(0, 1000);
        $response = $this->post(url('register'), [ 
            'email'=> $nama.'@gmail.com',
            'password'=>'123456',
            'password_confirmation'=>'123456'
        ]);   
        $response->assertStatus(302);
        $response->assertSessionHasErrors('name'); 
    }

    public function test_tidak_memasukkan_email()
    {
        $nama = rand(0, 1000);
        $response = $this->post(url('register'), [ 
            'name'=> ''.$nama, 
            'password'=>'123456',
            'password_confirmation'=>'123456'
        ]);   
        $response->assertStatus(302);
        $response->assertSessionHasErrors('email'); 
    }

    public function test_tidak_memasukkan_password()
    {
        $nama = rand(0, 1000);
        $response = $this->post(url('register'), [ 
            'name'=> ''.$nama,
            'email'=> $nama.'@gmail.com', 
            'password_confirmation'=>'123456'
        ]);   
        $response->assertStatus(302);
        $response->assertSessionHasErrors('password'); 
    }

    public function test_tidak_memasukkan_retype_password()
    {
        $nama = rand(0, 1000);
        $response = $this->post(url('register'), [ 
            'name'=> ''.$nama,
            'email'=> $nama.'@gmail.com', 
            'password'=>'123456' 
        ]);   
        $response->assertStatus(302);
        $response->assertSessionHasErrors('password_confirmation'); 
    }

    public function test_tidak_memasukkan_retype_password_tidak_sama()
    {
        $nama = rand(0, 1000);
        $response = $this->post(url('register'), [ 
            'name'=> ''.$nama,
            'email'=> $nama.'@gmail.com', 
            'password'=>'123456' , 
            'password_confirmation'=>'12345611'
        ]);   
        $response->assertStatus(302);
        $response->assertSessionHasErrors('password'); 
    }

    public function test_email_yang_diinput_telah_terdaftar()
    { 
        $response = $this->post(url('register'), [
            'name'=> 'ipulsamudin',
            'email'=> 'ipulsamudin@gmail.com', 
            'password'=>'123456' , 
            'password_confirmation'=>'123456'
        ]);
        $response->assertStatus(200);
        $response->assertViewIs('auth.register');
        $response->assertSee("Your Email Has Been Registered"); 
    } 

    public function test_email_yang_diinput_belum_diverifikasi()
    { 
        $response = $this->post(url('register'), [
            'name'=> 'ipul',
            'email'=> 'ipul@gmail.com', 
            'password'=>'123456' , 
            'password_confirmation'=>'123456'
        ]);
        $response->assertStatus(200);
        $response->assertViewIs('auth.register');
        $response->assertSee("Please verify your email"); 
    }

    public function test_berhasil_register_manual()
    {
        $nama = rand(0, 1000);
        $response = $this->post(url('register'), [
            'name'=> ''.$nama,
            'email'=> $nama.'@gmail.com',
            'password'=>'123456',
            'password_confirmation'=>'123456'
        ]);
        $user = User::where('name', $nama)->first();  
        $response->assertRedirect('info_verify/'.$user->id);
        User::where('name', $nama)->delete(); 
    }

    public function test_resend_email_verifikasi()
    { 
        $user = User::where('email', 'ipulsamudin@gmail.com')->first(); 
        $response = $this->post(url('resend_verify'), [ 
            'id'=>$user->id
        ]); 
        $response->assertRedirect('info_verify/'.$user->id);
    }

    public function test_verifikasi_email()
    {
        $nama = rand(0, 1000);
        $response = $this->post(url('register'), [
            'name'=> ''.$nama,
            'email'=> $nama.'@gmail.com',
            'password'=>'123456',
            'password_confirmation'=>'123456'
        ]);
        $user = User::where('email', $nama.'@gmail.com')->first(); 
        $response = $this->post(url('verifikasi'), [ 
            'id'=>$user->id
        ]);  
        $response->assertViewIs('google2fa.register');
        User::where('email', $nama.'@gmail.com')->delete(); 
    }

    public function test_email_telah_diverifikasi()
    { 
        $user = User::where('email', 'ipulsamudin@gmail.com')->first(); 
        $response = $this->post(url('verifikasi'), [ 
            'id'=>$user->id
        ]);  
        $response->assertViewIs('auth.done_verify'); 
    }

    public function test_register_dengan_google()
    {
        $abstractUser = \Mockery::mock('Laravel\Socialite\Two\User');
        
        $nama = rand(0, 1000);
        $abstractUser
            ->shouldReceive('getId')
            ->andReturn(rand())
            ->shouldReceive('getName')
            ->andReturn('test user')
            ->shouldReceive('getEmail')
            ->andReturn($nama . '@gmail.com')
            ->shouldReceive('getAvatar')
            ->andReturn('https://en.gravatar.com/userimage');

        $provider = \Mockery::mock('Laravel\Socialite\Contracts\Provider');
        $provider->shouldReceive('user')->andReturn($abstractUser);

        \Socialite::shouldReceive('driver')->with('google')
            ->andReturn($provider);

        $response = $this->get(url('/auth/callback'));  
        $response->assertViewIs('google2fa.register');
        User::where('email', $nama.'@gmail.com')->delete(); 
    }
}
