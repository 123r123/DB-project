
<?php
session_start();
$_SESSION['Authenticated']=false;
$dbservername='localhost';
$dbname='acdb';
$dbusername='jonhou1203';
$dbpassword='pass9704';
//echo 'alert("ffd")';//
try{
  
  if (!isset($_POST['email']) || !isset($_POST['pwd']))
  {
    //header("Content-Disposition:attachment;filename=test.pdf");
    header("Location: loginPage.php");   
  }

  if (empty($_POST['email']) || empty($_POST['pwd']))
    throw new Exception('Please input user name and password.');

  $uaccount=$_POST['email'];
  $pwd=$_POST['pwd'];
  $conn = new PDO('mysql:host=localhost;dbname=acdb', $dbusername, $dbpassword);
# set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $stmt=$conn->prepare("select username, password, salt from users where username=:username or email=:username");
  $stmt->execute(array('username' => $uaccount));
  if ($stmt->rowCount()==1){
    $row = $stmt->fetch();
    if ($row['password']==hash('sha256',$row['salt'].$_POST['pwd'])){
    //if ($row['password']==$pwd){
      $_SESSION['Authenticated']=true;
      $_SESSION['Username']=$row[1];
      $u=$row[0]; 
      
      echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
        <script>
        alert("hello, "+"$u"+" ,you got an ugly face");
        window.location.replace("userHomePage.php");
        </script>
        </body>
        </html>
      EOT;
      
      exit();
    }
    else
      throw new Exception('Login failed.');
  }
  else
    throw new Exception('email not logup yet.');
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
