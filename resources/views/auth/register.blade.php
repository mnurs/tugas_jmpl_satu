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
        <form method="post" action="{{ url('register') }}">
            {{ csrf_field() }}
          <div class="row">
            <i class="fas fa-user"></i>
            <input type="text" placeholder="FullName" name="name" required  oninvalid="this.setCustomValidity('Enter FullName Here')" oninput="setCustomValidity('')"> 
          </div>
          <div class="row">
            <i class="fas fa-envelope"></i>
            <input type="email" placeholder="Email" name="email" required  oninvalid="this.setCustomValidity('Enter Email Here')" oninput="setCustomValidity('')">
          </div>
          <div class="row">
            <i class="fas fa-lock"></i>
            <input type="password" id="password" placeholder="Password" name="password" required   oninvalid="this.setCustomValidity('Enter Password Here')" oninput="setCustomValidity('')">
          </div>
          <div class="row">
            <i class="fas fa-lock"></i>
            <input type="password" id="new_password" placeholder="Retype Password" name="password_confirmation" required oninvalid="this.setCustomValidity('Enter Retype Password  Here')" oninput="setCustomValidity('')">
          </div> 
          <div class="row button">
            <input type="submit" value="Register">
          </div>
          <div class="row google">
            <input type="button" value="Google" onclick="register()">
          </div>
          <div class="signup-link"><a href="{{ url('login') }}">I already have a membership</a></div>
          <div class="message-link">@if(isset($msg)){{$msg}}@endif</a></div>
        </form>
      </div>
    </div>

  </body>
  <script>
    var myPass = document.getElementById("password"); 
    var myNewPass = document.getElementById("new_password"); 
     
    // When the user clicks outside of the password field, hide the message box
    myNewPass.onblur = function() {
        if (myPass.value != myNewPass.value) {
            alert("Passwords are not the same");
        } 
    }

    function register(){
        window.location.href = "{{ '/auth/redirect'}}";
    }
 
</script>
</html>
