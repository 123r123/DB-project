<?php
session_start();
$_SESSION['Authenticated']=false;
$dbservername='localhost';
$dbname='acdb';
$dbusername='root';
$dbpassword='';

try{
  
  if (!isset($_POST['acc']) || !isset($_POST['pwd']))
  {
    //header("Content-Disposition:attachment;filename=test.pdf");
    header("Location: ../../index.php");   
  }

  if (empty($_POST['acc']) || empty($_POST['pwd']))
    throw new Exception('Please input user name and password.');

  $account=$_POST['acc'];
  $pwd=$_POST['pwd'];

  $conn = new PDO('mysql:host=localhost;dbname=acdb', $dbusername, $dbpassword);
# set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $stmt=$conn->prepare("select * from users where account=:acc");
  $stmt->execute(array('acc' => $account));
  if ($stmt->rowCount()==1){
    $row = $stmt->fetch();
    $fuck = hash('sha256',$row['salt'].$pwd);
    if ($row['password']==hash('sha256',$row['salt'].$pwd)){
    //if ($row['password']==$pwd){
      $_SESSION['Authenticated']=true;
      $_SESSION['account']=$row['account'];
      $u=$row['username']; 
      
      echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
        <script>
        alert("hello, "+"$u");
        window.location.replace("../../nav.php");
        </script>
        </body>
        </html>
      EOT;
      
      exit();
    }
    else{
     
      throw new Exception('Login failed.');
    }
      
  }
  else
    throw new Exception('account not exist');
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
    window.location.replace("../../index.php");
    </script>
    </body>
    </html>
  EOT;
   
}
?>
