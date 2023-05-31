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
        <div class="title"><span>Register Form</span></div>
        <form name="register" method="post" action="{{ url('register_not_safety') }}">
            {{ csrf_field() }}
          <div class="row">
            <i class="fas fa-user"></i>
            <input type="text" placeholder="FullName" name="name"> 
          </div>
          <div class="row">
            <i class="fas fa-envelope"></i>
            <input type="text" placeholder="Email" name="email">
          </div>
          <div class="row">
            <i class="fas fa-lock"></i>
            <input type="password" id="password" placeholder="Password"  name="password">
          </div>
          <div class="row">
            <i class="fas fa-lock"></i>
            <input type="password" id="new_password" placeholder="Retype Password" name="password_confirmation">
          </div> 
          <div class="row button">
            <input type="submit" value="Register">
          </div> 
          <div class="signup-link"><a href="{{ url('login_not_safety') }}">I already have a membership</a></div>
          <div class="message-link">
            @if(isset($msg)){{$msg}}@endif
             @error('name')
            <span class="text-danger" role="alert">
                <strong>{{ $message }}</strong><br>
            </span>
            @enderror 
             @error('email')
            <span class="text-danger" role="alert">
                <strong>{{ $message }}</strong><br>
            </span>
            @enderror 
             @error('password')
            <span class="text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror  
          </div>
        </form>
      </div>
    </div>

  <script> 
      function register(){
          window.location.href = "{{ '/auth/redirect'}}";
      } 
  </script>
  </body> 
</html>
