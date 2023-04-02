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
        <div class="title"><center>Successful Account Verification</center></div>
        <form action="{{url('login')}}" id="resend-form"  method="GET" > 
          <div class="row">
            <p><center>You have successfully verified your account, please login</center></p>
          </div>  
          <div class="row"> 
          </div>  
          <div class="row button">  
            <input type="submit" value="Login"  onclick="event.preventDefault(); document.getElementById('resend-form').submit();"> 
          </div>  
        </form>
      </div>
    </div>

  </body>
</html>
