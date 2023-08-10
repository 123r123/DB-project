<?php include("../shit/dbConnect.php");?>
<?php


session_start();

$timeZone = new DateTimeZone('Asia/Taipei');
$currentDate      = new DateTime('now',$timeZone);
$timeOrderEnded = $currentDate->format('Y-m-d H:i:s');

$OID = $_POST['OID'];


  try{

    $stmt=$conn->prepare("update orders set orderStatus='Cancel', timeOrderEnded='$timeOrderEnded' where OID=$OID;");
    $stmt->execute();
    $stmt=$conn->prepare("select * from order_product where OID=$OID");
    $stmt->execute();

    foreach($stmt->fetchAll() as $id => $val){
      $PID=$val['PID'];
      $addquantity=$val['quantity'];
      $s=$conn->prepare("update products set quantity=quantity+$addquantity where PID=$PID");
      $s->execute();
    }

    $stmt=$conn->prepare("select * from orders where OID=$OID");
    $stmt->execute();

    $order=$stmt->fetch();

    $UID=$order['UID'];
    $SID=$order['SID'];
    $return_money =$order['orderAmount'];

    $s=$conn->prepare("update users set balance=balance+$return_money where UID=$UID");
    $s->execute();
    
    $stmt = $conn->prepare("select UID from shops where SID = $SID;");
    $stmt->execute();
    $SUID = $stmt->fetch()[0];

    $TID = 0;
    $s=$conn->prepare("select count(*) from transactions");
    $s->execute();

    if($s->fetch()[0]!=0){
      $s=$conn->prepare("select max(TID) from transactions");
      $s->execute();
      $TID = $s ->fetch()[0] + 1;
    }  

    //### there's bug here because the setting at navTranRecord.php 
    //### Solution: append to element "SID" or  "determine ID " to "transaction"
    $stmt=$conn->prepare("insert into transactions values ($TID, $SID, $return_money, '$timeOrderEnded', 'payment', 1);  ");
    $stmt->execute();

    $TID++;

    $stmt=$conn->prepare("insert into transactions values ($TID, $UID, $return_money, '$timeOrderEnded', 'receive', 0);  ");
    $stmt->execute();

    if($_SESSION['curUser']['UID'] == $UID) {
      $_SESSION['curUser']['balance']  += $return_money;
    }
    if(isset($_POST['s'])){
      exit();
    }
    
    echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body> 
        <script>
        alert("cancel success!" )
        window.location.replace("navMyorder.php")
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
        window.location.replace("navMyorder.php")
        </script> </body> </html>
    EOT;
 
  }