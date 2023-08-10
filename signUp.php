<?php
session_start();
# remove all session variables
session_unset();
# destroy the session 
session_destroy();


require "php/shit/head.php";
?>



<script src="js/fuck.js"></script>
<style>
    .form-messages {
        background-color: rgb(255, 232, 232);
        border: 1px solid red;
        color: red;
        display: none;
        font-size: 12px;
        font-weight: bold;
        margin-bottom: 10px;
        padding: 10px 25px;
        max-width: 250px;
    }
		
</style>

<div class="container">
	<div class="row">
		<div class="col-md-4 col-md-offset-4">
			<!-- Start Sign In Form -->
			<div class="fh5co-form animate-box form" data-animate-effect="fadeIn">
				<h2>Sign Up</h2>
				<!-- <div class="form-group">
							<div class="alert alert-success" role="alert">Your info has been saved.</div>
						</div> -->
				<ul style="color: red" id="form-messages"></ul>
				<div class="form-group">
					<label for="name" class="sr-only">Name</label>
					<input name="nname" type="text" class="form-control" id="name" placeholder="Name" autocomplete="on" pattern="^[a-zA-Z]*$" title="letters only!" required>
					<span style="color: red" id="invname" ></span>
				</div>
				<div class="form-group">
					<label for="name" class="sr-only">phonenumber</label>
					<input name="pnum" type="text" class="form-control" id="phonenumber" placeholder="PhoneNumber" autocomplete="on"required>
					<span style="color: red" id="invpnum" ></span>
				</div>
				<div class="form-group">
					<label for="Account" class="sr-only">Account</label>
					<input name="acc" type="text" class="form-control" id="Account" placeholder="Account" autocomplete="on"required>
					<span style="color: red" id="dulAccount"></span>
					<span style="color: red" id="invacc" ></span>

				</div>
				<div class="form-group">
					<label for="password" class="sr-only">Password</label>
					<input name="pwd" type="password" class="form-control" id="password" placeholder="Password" autocomplete="on"required>
					<span style="color: red" id="invpass" ></span>
				</div>
				<div class="form-group">
					<label for="re-password" class="sr-only">Re-type Password</label>
					<input name="ppwd" type="password" class="form-control" id="re-password" placeholder="Re-type Password" autocomplete="on"required>
					<span style="color: red" id="difpwd"></span>
					
				</div>
				<div class="form-group">
					<label for="latitude" class="sr-only">latitude</label>
					<input name="lat" type="text" class="form-control" id="latitude" placeholder="Latitude" autocomplete="on"required>
					<span style="color: red" id="invlat"></span>
				</div>
				<div class="form-group">
					<label for="longitude" class="sr-only">longitude</label>
					<input name="lon" type="text" class="form-control" id="longitude" placeholder="longitude" autocomplete="on"required>
					<span style="color: red" id="invlon"></span>
				</div>

				<div class="form-group">
					<p>Already registered? <a href="index.php">Sign In</a></p>
				</div>
				<div class="form-group">
					<input type="submit" id="fuck" value="Sign Up" class="btn btn-primary">
				</div>

			</div>

			<script>
				const form = {
					accMessage: document.getElementById('dulAccount'),
					nname: document.getElementById('name'),
					pnum: document.getElementById('phonenumber'),
					acc: document.getElementById('Account'),
				  pwd: document.getElementById('password'),
					ppwd: document.getElementById('re-password'),
					lat: document.getElementById('latitude'),
					lon: document.getElementById('longitude'),
					mess:document.getElementById('form-messages')
				};

				$("#name").change( ()=>{
					if(!/^[a-zA-Z ]*$/.test($("#name").val())){
						$("#invname").html('name can only have letters!');
					}			
					else{
						$("#invname").html('');
					}		 
				})

        $("#Account").change(function() {
					$("#dulAccount").html('')
					if(!/^[a-zA-Z0-9]+$/.test( form.acc.value)&&$(this).val()!=''){

						$("#invacc").html('account can only have letters or numbers!');
						return;

					} else {

						$("#invacc").html('');

					}

					const request = new XMLHttpRequest();
									
					request.onload = () => {
						let responseObject = null;

						try {
							responseObject = JSON.parse(request.responseText);
						} catch (e) {
							console.error(request.responseText);
						}

						if (responseObject) {							
							handleAcc(responseObject);
						}

					};
					
					const requestData = `acc=${form.acc.value}`;
					request.open('post', 'php/account/checkAcc.php');
					request.setRequestHeader('content-type', 'application/x-www-form-urlencoded');
					request.send(requestData);

					
				})
				$("#phonenumber").change(function() {					

					if((!/^[0-9]+$/.test($(this).val())||$(this).val().length!=10)&&$(this).val()!='')
						$("#invpnum").html('phone number must be 10 digit numbers!').slideDown();
					else 
						$("#invpnum").html('');
								
				})
				$("#longitude").change(function() {					
						if((!isFloat($(this).val())&&$(this).val()!='')||parseFloat($(this).val())>180||parseFloat($(this).val())<-180){
							$("#invlon").html('illegal longitude!').slideDown();
						}	else {					
							$("#invlon").html('');
						}
					}
				)
				$("#latitude").change(function() {
					
					if((!isFloat($(this).val())&&$(this).val()!='')||parseFloat($(this).val())>90||parseFloat($(this).val())<-90){
							$("#invlat").html('illegal latitude!').slideDown();
						}
						else{
							$("#invlat").html('');
						}
					}
				)
				$("#password").change(function() {
					
					if (!/^[a-zA-Z0-9]+$/.test($(this).val())&&$(this).val()!='') {
							$("#invpass").html('password can only have letters or numbers!').slideDown();
						} else {						
							$("#invpass").html('');
						}
					}
				)
			
				$("#fuck").click(function() {
					const request = new XMLHttpRequest();
	
					request.onload = () => {
						let responseObject = null;
						try {
							responseObject = JSON.parse(request.responseText);
						} catch (e) {
							console.error(request.responseText);
						}

						if (responseObject) {							
							handleResponse(responseObject);
						}

					};
					const requestData = `nname=${form.nname.value}&pnum=${form.pnum.value}&acc=${form.acc.value}&pwd=${form.pwd.value}&ppwd=${form.ppwd.value}&lat=${form.lat.value}&lon=${form.lon.value}`;
					request.open('post', 'php/account/register.php');
					request.setRequestHeader('content-type', 'application/x-www-form-urlencoded');
					request.send(requestData);
				});

        function handleAcc(responseObject){
					if (!responseObject.ok){
						if(responseObject.msg=='Account used.'){
							let k = responseObject.msg + "</br>"
							$("#dulAccount").html(k)
						}
					}
				}
				
				function handleResponse(responseObject) {
					$("#dulAccount").html('')
					$("#difpwd").html('')
					if (!($("#invname").is(':empty')&&$("#invpnum").is(':empty')&&$("#invacc").is(':empty')&&$("#invlon").is(':empty')&&$("#invlat").is(':empty')&&$("#invpass").is(':empty'))){

						const li = document.createElement('li');
						
						form.mess.style.display = 'block';
						return;

					}
					if (responseObject.ok) {

						alert("successfully Created!");
						window.location.replace("index.php");

					} 
					else {

						alert(responseObject.msg)
						while (form.mess.firstChild) {

							form.mess.removeChild(form.mess.firstChild);

						}

						if(responseObject.msg=='Please input all the information.'){
						
							const li       = document.createElement('li');
							li.textContent = responseObject.msg;
							form.mess.appendChild(li);
							form.mess.style.display = 'block';

						} else if (responseObject.msg == 'Account used.') {		

							let k = responseObject.msg + "</br>"
							$("#dulAccount").html(k)

						}				

						if(form.pwd.value!=form.ppwd.value)
							$("#difpwd").html('two password are different!');
						else 
							$("#difpwd").html('');

						if (!($("#invpnum").is(':empty')&&$("#invacc").is(':empty')&&$("#invlon").is(':empty')&&$("#invlat").is(':empty')&&$("#invpass").is(':empty'))) {

							const li = document.createElement('li');
							li.textContent = 'illegal format!';
							form.mess.appendChild(li);
							form.mess.style.display = 'block';

						}				
					}
				}
				
				$(window).keyup(function(e){

					  console.log(e.char);
						$("#Account").change();

				})      
			</script>
			<!-- END Sign In Form -->
		</div>
	</div>
	<div class="row" style="padding-top: 60px; clear: both;">
		<div class="col-md-12 text-center">
			<p><small>&copy; All Rights Reserved. Designed by <a href="https://freehtml5.co">FreeHTML5.co</a></small></p>
		</div>
	</div>
</div>