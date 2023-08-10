<?php include("../shit/dbConnect.php");?>
<?php


session_start();

$timeZone = new DateTimeZone('Asia/Taipei');
$currentDate      = new DateTime('now',$timeZone);
$timeOrderEnded = $currentDate->format('Y-m-d H:i:s');

$OID = $_POST['OID'];


  try{

    $stmt=$conn->prepare("update orders set orderStatus='Finished', timeOrderEnded='$timeOrderEnded' where OID=$OID;");
    $stmt->execute();

    echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body> 
        <script>
        window.location.reload()
        </script> </body> </html>
    EOT;
    exit();
  }

  catch(PDOException $e){
    $msg = $e->getMessage();
    echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
        <script>
        alert("$msg")
        window.location.reload()
        </script> </body> </html>
    EOT;
 
  }