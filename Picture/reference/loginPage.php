<?php
session_start();
# remove all session variables
session_unset(); 
# destroy the session 
session_destroy();
$_SESSION['Authenticated']=false;
?>

<?php require 'header.php';?>

<?php require 'upperBAr.php';?>

<div class="container-fluid p-3 bg-info text-white ">
  
    
  <div class="container rounded p-5 my-5 bg-dark text-white">
    <div class="row">

      <div class="col-md-6 p-5 mx-3">
        <p class="h1">Log In</p>
        <form action="login.php" method="post" >
          <div class="mb-3 mt-3">
            <label for="email" class="form-label ">Email:</label>
            <input type="email" class="form-control "  id="email" placeholder="Enter email or username" name="email" >
          </div>
          <div class="mb-3">
            <label for="pwd" class="form-label text-left">Password:</label>
            <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="pwd">
          </div>
          <div class="form-check mb-3">
            <label class="form-check-label">
              <input class="form-check-input" type="checkbox" name="remember"> Remember me
            </label>
          </div>
          <button type="submit" class="btn btn-primary px-5 my-3" >login </button>
        </form>
      </div>
      
        
      <div class="col-md-5 px-1 mx-1">
        <div class="container rounded p-5 my-4 bg-warning text-black">
          <p class="h1">Creat An Account</p>
          <form action="register.php" method="post" >
            <div class="mb-3 mt-3">
              <label for="email" class="form-label ">Email:</label>
              <input type="email" class="form-control "  id="email" placeholder="your email" name="email" >
            </div>
            <div class="mb-3">
              <label for="un" class="form-label text-left">username:</label>
              <input type="text" class="form-control" id="un" placeholder="your username" name="uname">
            </div>
            <div class="mb-3">
              <label for="pwd" class="form-label text-left">Password:</label>
              <input type="password" class="form-control" id="pwd" placeholder="your password" name="pwd">
            </div>
            <div class="mb-3">
              <label for="ppwd" class="form-label text-left">Password again:</label>
              <input type="password" class="form-control" id="ppwd" placeholder="password again" name="ppwd">
            </div>
            <div class="text-center">
            <button type="submit" class="btn btn-primary px-5 my-3 text-center" >creat account </button>
            </div>
          </form>
        </div>
      </div>

    </div>

  </div>
</div>
<div class="container-fluid p-5 bg-success text-white text-center">
  <footer>DA GG</footer>
  
</div>

<?php require 'tail.php';?>