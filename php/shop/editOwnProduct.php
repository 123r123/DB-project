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

    $stmt=$conn->prepare("update products set quantity = $quantity ,price = $price where SID = $SID and productName = '$productName'");
    $stmt->execute();
    echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
        <script>
        alert("edit success!" )
        window.location.replace("navShop.php")
        </script> </body> </html>
    EOT;

    exit();
  }
  catch(Exception $e){ 
    $msg = $e->getMessage();
    echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
        <script>
        alert("$msg" )
        
        </script> </body> </html>
    EOT;
  }