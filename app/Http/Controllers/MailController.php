<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\RegisterMail;
use Mail;

class MailController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index()
    {
        $mailData = [
            'title' => 'Mail from ItSolutionStuff.com',
            'body' => 'This is for testing email using smtp.',
            'id' => 1
        ];
         
        Mail::to('ipulsamudin@gmail.com')->send(new RegisterMail($mailData));
           
        dd("Email is sent successfully.");
    }
}
