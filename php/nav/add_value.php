<?php
session_start();
$_SESSION['Authenticated']=false;
$account=$_SESSION['account'];
$dbservername='localhost';
$dbname='acdb';
$dbusername='root';
$dbpassword='';

$addvalue=$_POST['add_value'];

$conn = new PDO('mysql:host=localhost;dbname=acdb', $dbusername, $dbpassword);
# set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $stmt=$conn->prepare("select balance from users where account='$account'");
  $stmt->execute();
  $balance=$stmt->fetch()[0];
  $stmt=$conn->prepare("update users set balance=$balance+$addvalue where account='$account'");
  $stmt->execute();
  echo <<<EOT
  <!DOCTYPE html>
  <html>
  <script>
  window.location.replace("../../nav.php");
  </script> </html>
  EOT;
?>