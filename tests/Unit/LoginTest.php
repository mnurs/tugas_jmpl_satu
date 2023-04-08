<?php

namespace Tests\Unit;

use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */ 
    public function test_tidak_memasukan_email()
    {
        $response = $this->post(url('login'), []);
     
        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
    }


    public function test_tidak_memasukkan_password()
    {
        $response = $this->post(url('login'), [
            'email'=>'ipulsamudin@gmail.com'
        ]);
     
        $response->assertStatus(302);
        $response->assertSessionHasErrors('password');
    }

    public function test_email_belum_terdaftar()
    { 
        $response = $this->post(url('login'), [
            'email'=>'ipulsamudin12@gmail.com',
            'password'=>'123456'
        ]);
        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
        $response->assertSee("Email Not found"); 
    }

    public function test_password_yang_dimasukkan_salah()
    { 
        $response = $this->post(url('login'), [
            'email'=>'ipulsamudin@gmail.com',
            'password'=>'12345611'
        ]);
        $response->assertStatus(200);
        $response->assertViewIs('auth.login'); 
        $response->assertSee("Upps Wrong Password"); 
    }

    public function test_akun_belum_diverifikasi()
    { 
        $response = $this->post(url('login'), [
            'email'=>'ipul@gmail.com',
            'password'=>'111111'
        ]);
        $response->assertStatus(200);
        $response->assertViewIs('auth.login'); 
        $response->assertSee("Please verify your email"); 
    }

    public function test_berhasil_login()
    { 
        $response = $this->post(url('login'), [
            'email'=>'ipulsamudin@gmail.com',
            'password'=>'123456'
        ]); 
        $response->assertRedirect('home');
    }

    public function test_salah_memasukkan_akun_lebih_dari_3x()
    { 

        for ($i=0; $i < 4; $i++) { 
            $response = $this->post(url('login'), [
                'email'=>'ipulsamudin@gmail.com',
                'password'=>'12345611'
            ]);    
        } 
        $responses = $this->get(url('login'), [
            'email'=>'ipulsamudin@gmail.com',
            'password'=>'123456' 
        ]);         
        $responses->assertSee('https://www.google.com/recaptcha/api.js?'); 
    }
    public function test_tidak_input_captcha()
    { 

        for ($i=0; $i < 4; $i++) { 
            $response = $this->post(url('login'), [
                'email'=>'ipulsamudin@gmail.com',
                'password'=>'12345611'
            ]);    
        }
        $responses = $this->post(url('login'), [
            'email'=>'ipulsamudin@gmail.com',
            'password'=>'123456' 
        ]);        
        $response->assertSessionHasErrors('g-recaptcha-response');   
    }

    protected function ignoreCaptcha($name = 'g-recaptcha-response')
    {
        \NoCaptcha::shouldReceive('display')->once()->andReturn('<input type="checkbox" value="yes" name="' . $name . '">');
        \NoCaptcha::shouldReceive('verify')->once()->andReturn(true);
    }

    public function test_input_captcha()
    { 
        for ($i=0; $i < 4; $i++) { 
            $response = $this->post(url('login'), [
                'email'=>'ipulsamudin@gmail.com',
                'password'=>'12345611'
            ]);    
        }  
        // provide hidden input for your 'required' validation
        \NoCaptcha::shouldReceive('verifyResponse') 
            ->andReturn(true);
            // provide hidden input for your 'required' validation
        \NoCaptcha::shouldReceive('display')
            ->andReturn('<input type="hidden" name="g-recaptcha-response" value="1" />');
        \NoCaptcha::shouldReceive('renderJs') 
            ->andReturn(true);
        // POST request, with request body including g-recaptcha-response
        $responses = $this->json('POST', '/login', [
            'g-recaptcha-response' => '1',
            'email'=>'ipulsamudin@gmail.com',
            'password'=>'123456'
        ]);
        $responses->assertRedirect('home');
    }

    public function test_login_dengan_google()
    {
        $abstractUser = \Mockery::mock('Laravel\Socialite\Two\User');
        
        $abstractUser
            ->shouldReceive('getId')
            ->andReturn(rand())
            ->shouldReceive('getName')
            ->andReturn('test user')
            ->shouldReceive('getEmail')
            ->andReturn('ipulsamudin@gmail.com')
            ->shouldReceive('getAvatar')
            ->andReturn('https://en.gravatar.com/userimage');

        $provider = \Mockery::mock('Laravel\Socialite\Contracts\Provider');
        $provider->shouldReceive('user')->andReturn($abstractUser);

        \Socialite::shouldReceive('driver')->with('google')
            ->andReturn($provider);

        $response = $this->get(url('/auth/callback'));  
        $response->assertRedirect('home');
    }
}
