<!DOCTYPE html>
<!-- Created By CodingLab - www.codinglabweb.com -->
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <title>Login Form | CodingLab</title> -->
    <link rel="stylesheet" href="{{asset('css/register.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
  </head>
  <body>
    <div class="container">
      <div class="wrapper">
        <div class="title"><center>Verify Your <br> Email Address</center></div>
        <form action="{{url('verifikasi')}}" id="resend-form"  method="POST" >
            @csrf
          <div class="row">
            <p>Before proceeding, please check your email for a verification link.If you did not receive the email,</p>
          </div>  
          <div class="row"> 
          </div>  
          <div class="row button"> 
            <input type="hidden" name="id" value="{{request()->route('id')}}">
            <input type="submit" value="Verifikasi"  onclick="event.preventDefault(); document.getElementById('resend-form').submit();"> 
          </div> 
          <div class="signup-link"><a href="{{ url('login') }}">I already have a membership</a></div>
        </form>
      </div>
    </div>

  </body>
</html>
