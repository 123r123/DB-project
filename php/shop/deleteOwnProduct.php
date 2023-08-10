<?php include("../shit/dbConnect.php");?>
<?php


session_start();

$productName = $_REQUEST['productName'] ;
$price       = $_REQUEST['price'] ;
$quantity    = $_REQUEST['quantity'] ;
  try{
    $stmt=$conn->prepare("select SID from shops where UID=:user");
    $stmt->execute(array('user' => $_SESSION['curUser']['UID']));
    $SID = $stmt->fetch()[0];

    $stmt=$conn->prepare("delete from products where SID = $SID and productName = '$productName'");
    $stmt->execute();
    echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body> 
        <script>
        alert("remove success!" )
        window.location.replace("navShop.php")
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
        window.location.replace("navShop.php")
        </script> </body> </html>
    EOT;

  }