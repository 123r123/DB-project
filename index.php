<?php
session_start();
# remove all session variables
session_unset(); 
# destroy the session 
session_destroy();
$_SESSION['Authenticated']=false;
require "php/shit/head.php"
?>


    <div class="container">

        <div class="row">
            <div class="col-md-4 col-md-offset-4">


                <!-- Start Sign In Form -->
                <form action="php/back/login.php" class="fh5co-form animate-box" data-animate-effect="fadeIn" method="POST">
                    <h2>Sign In</h2>
                    <div class="form-group">
                        <label for="Account" class="sr-only">Account</label>
                        <input name="acc" type="text" class="form-control" id="Account" placeholder="Account" autocomplete="on">
                    </div>
                    <div class="form-group">
                        <label for="password" class="sr-only">Password</label>
                        <input name="pwd" type="password" class="form-control" id="password" placeholder="Password" autocomplete="on">
                    </div>

                    <div class="form-group">
                        <p>Not registered? <a href="signUp.php">Sign Up</a> </p>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Sign In" class="btn btn-primary">
                    </div>
                </form>
                <!-- END Sign In Form -->

            </div>
        </div>
        <div class="row" style="padding-top: 60px; clear: both;">
            <div class="col-md-12 text-center">
                <p><small>&copy; All Rights Reserved. Designed by <a href="https://freehtml5.co">FreeHTML5.co</a></small></p>
            </div>
        </div>
    </div>




	<?php  require "php/shit/foot.php";?>