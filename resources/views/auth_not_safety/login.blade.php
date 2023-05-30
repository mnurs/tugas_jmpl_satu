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
        <div class="title"><span>Login Form</span></div>
        <form method="post" action="{{ url('login_not_safety') }}">
            {{ csrf_field() }}
          <div class="row">
            <i class="fas fa-envelope"></i>
            <input type="email" placeholder="Email" name="email">
          </div>
          <div class="row">
            <i class="fas fa-lock"></i>
            <input type="password" id="password" placeholder="Password" name="password">
          </div> 
          <div class="pass"><a href="#">Forgot password?</a></div>
          <div class="row button">
            <input type="submit" value="Login">
          </div> 
          <div class="signup-link">Not a member? <a href="{{ url('register_not_safety') }}">Signup now</a></div>
          <div class="message-link">
                @if(isset($msg)){{$msg}}@endif 
               @error('email')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
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

  </body>
  <script> 
    function register(){
        window.location.href = "{{ '/auth/redirect'}}";
    } 
</script>
</html>
