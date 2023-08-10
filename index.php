<?php
session_start();
# remove all session variables
session_unset(); 
# destroy the session 
session_destroy();

require "php/shit/head.php";
?>


    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <!-- Start Sign In Form -->
                <div  class="fh5co-form animate-box form" data-animate-effect="fadeIn" >
                    <h2>Sign In</h2>
                    <div class="form-group">
                        <label for="Account" class="sr-only">Account</label>
                        <input name="acc" type="text" class="form-control" id="Account" placeholder="Account" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="password" class="sr-only">Password</label>
                        <input name="pwd" type="password" class="form-control" id="password" placeholder="Password" autocomplete="off">
                    </div>
                    <span style="color: red" id="message">
                        
                    </span>
                    <div class="form-group">
                        <p>Not registered? <a href="signUp.php">Sign Up</a> </p>
                    </div>
                    
                    <div class="form-group">
                        <input type="submit" id="fuck" value="Sign In" class="btn btn-primary">
                    </div>
                </div>
                <script>

                    const form = {
                        acc:     document.getElementsByName('acc')[0],
                        pwd:     document.getElementsByName('pwd')[0],
                        message: document.getElementById('message')
                    };
                    
                    $("#fuck").click(function(){
                        const request = new XMLHttpRequest();
                    
                        request.onload = () =>{

                            let responseObject = null;
                            try {
                                responseObject = JSON.parse(request.responseText);
                            } catch (e) {
                                console.error(e.message);
                            }

                            if (responseObject) {
                                handleResponse(responseObject);
                            }

                       };
                       const requestData = `acc=${form.acc.value}&pwd=${form.pwd.value}`;
                       request.open('post','php/account/login.php');
                       request.setRequestHeader('content-type', 'application/x-www-form-urlencoded');
                       request.send(requestData);
                    });

                    function handleResponse (responseObject) {
                        if (responseObject.ok) {
                            alert("hello ," + responseObject.us)
                            window.location.replace("nav.php");
                        } else {
                            console.log("haha");

                            while (form.message.firstChild) {
                                form.message.removeChild(form.message.firstChild);
                            }

                            const li = document.createElement('div');
                            li.textContent = responseObject.msg;
                            form.message.appendChild(li);
                            form.message.style.display = "block";

                        }
                    }
                    $(window).keydown(function(e){
                        if(e.keyCode==13){
                            $( "#fuck" ).click();
                        }
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

<?php  require "php/shit/foot.php";?>