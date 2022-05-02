<?php
session_start();
# remove all session variables
session_unset();
# destroy the session 
session_destroy();
$_SESSION['Authenticated'] = false;
require "php/shit/head.php";
?>


<div class="container">

	<div class="row">
		<div class="col-md-4 col-md-offset-4">


			<!-- Start Sign In Form -->
			<form action="php/back/register.php" class="fh5co-form animate-box" data-animate-effect="fadeIn" method="post">
				<h2>Sign Up</h2>
				<!-- <div class="form-group">
							<div class="alert alert-success" role="alert">Your info has been saved.</div>
						</div> -->
				<div class="form-group">
					<label for="name" class="sr-only">Name</label>
					<input name="nname" type="text" class="form-control" id="name" placeholder="Name" autocomplete="off">
				</div>
				<div class="form-group">
					<label for="name" class="sr-only">phonenumber</label>
					<input name="pnum" type="text" class="form-control" id="phonenumber" placeholder="PhoneNumber" autocomplete="off">
				</div>
				<div class="form-group">
					<label for="Account" class="sr-only">Account</label>
					<input name="acc" type="text" class="form-control" id="Account" placeholder="Account" autocomplete="off">
				</div>
				<div class="form-group">
					<label for="password" class="sr-only">Password</label>
					<input name="pwd" type="password" class="form-control" id="password" placeholder="Password" autocomplete="off">
				</div>
				<div class="form-group">
					<label for="re-password" class="sr-only">Re-type Password</label>
					<input name="ppwd" type="password" class="form-control" id="re-password" placeholder="Re-type Password" autocomplete="off">
				</div>
				<div class="form-group">
					<label for="latitude" class="sr-only">latitude</label>
					<input name="lat" type="text" class="form-control" id="latitude" placeholder="Latitude" autocomplete="off">
				</div>
				<div class="form-group">
					<label for="longitude" class="sr-only">longitude</label>
					<input name="lon" type="text" class="form-control" id="longitude" placeholder="longitude" autocomplete="off">
				</div>

				<div class="form-group">
					<p>Already registered? <a href="index.php">Sign In</a></p>
				</div>
				<div class="form-group">
					<input type="submit" value="Sign Up" class="btn btn-primary">
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