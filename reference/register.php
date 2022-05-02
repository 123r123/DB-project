<?php
session_start();
$_SESSION['Authenticated']=false;
$dbservername='localhost';
$dbname='acdb';
$dbusername='jonhou1203';
$dbpassword='pass9704';
try {
    if (!isset($_POST['uname']) || !isset($_POST['pwd']) || !isset($_POST['ppwd'])|| !isset($_POST['email']))
    {
    header("Location: loginPage.php");
    exit();
    }
    if (empty($_POST['uname']) || empty($_POST['pwd'])|| empty($_POST['ppwd'])|| empty($_POST['email']))
    throw new Exception('Please input all the information.');

    $uemail=$_POST['email'];
    $pwd=$_POST['pwd'];
    $ppwd=$_REQUEST['ppwd'];
    $uname=$_REQUEST['uname'];
    if($pwd!=$ppwd){
        throw new Exception('two passwords different.');
    }
    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", 
    $dbusername, $dbpassword);
    # set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, 
    PDO::ERRMODE_EXCEPTION);
    $stmt=$conn->prepare("select username from users where email=:username");
    $stmt->execute(array('username' => $uemail));
    if ($stmt->rowCount()==0){
        $salt=strval(rand(1000,9999));
        $hashvalue=hash('sha256', $salt.$pwd);
    
        $stmt=$conn->prepare("insert into users values (:username,:email ,:password, :salt)");
        $stmt->execute(array('username' => $uname,'email'=>$uemail,'password' => $hashvalue, 'salt' => $salt));
        $_SESSION['Authenticated']=true;
        $_SESSION['Username']=$uemail;
        echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
        <script>
        alert("Create an account successfully.");
        window.location.replace("loginPage.php");
        </script> </body> </html>
        EOT;
        
        exit();
    }
    else
        throw new Exception("Logup failed.");
}

catch(Exception $e){
    $msg=$e->getMessage();
    session_unset(); 
    session_destroy(); 
    echo <<<EOT
    <!DOCTYPE html>
    <html>
    <body>
    <script>
    alert("$msg");
    window.location.replace("loginPage.php");
    </script>
    </body>
    </html>
    EOT;
   
}
?>
