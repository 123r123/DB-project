<?php include("../shit/dbConnect.php");?>
<?php
session_start();

$account=$_SESSION['curUser']['account'];



  if (!isset($_POST['lon']) || !isset($_POST['lat']))
  {
    //header("Content-Disposition:attachment;filename=test.pdf");
    header("Location: ../../nav.php");   
  }

  $latitude=$_POST['lat'];
  $longitude=$_POST['lon'];

  $stmt=$conn->prepare("update users set location=ST_GeomFromText('POINT($longitude $latitude)') where account='$account'");
  $_SESSION['curUser']['longitude'] = $longitude ;
  $_SESSION['curUser']['latitude'] = $latitude ;

  $stmt->execute();
  echo <<<EOT
  <!DOCTYPE html>
  <html>
  <script>
  window.location.replace("../../nav.php");
  </script> </html>
  EOT;


?>