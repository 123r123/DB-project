<?php
session_start();

// function httpPost($url, $data){
//   $curl = curl_init($url);
//   curl_setopt($curl, CURLOPT_POST, true);
//   curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
//   curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//   $response = curl_exec($curl);
//   return $response;
// }

require "../shit/dbConnect.php";

  $ok = true;
  $msg = "success!";

  if (!isset($_POST['cart']))
  {
      exit();
  }
 
    $cart = json_decode($_POST['cart'], true);

    $SID = $cart[-1] ;
    $UID = $_SESSION['curUser']['UID'];
    $orderStatus = "NotFinished";

    $timeZone         = new DateTimeZone('Asia/Taipei');
    $currentDate      = new DateTime('now',$timeZone);
    $timeOrderCreated = $currentDate->format('Y-m-d H:i:s');

    $orderDistance = $cart[-4];

    $amount = $cart[-2];

    $orderType = $cart[-3] == 1 ? 'delivery' : 'pickUp';

    unset($cart[-1]);
    unset($cart[-2]);
    unset($cart[-3]);
    unset($cart[-4]);
   
   
    $conn->beginTransaction();

    try{
      try{
        if (empty($cart)) {
          throw new Exception("You had ordered nothing!");
        }

        $OID = 0;

        $s=$conn->prepare("select count(*) from orders");
        $s->execute();
    
        if($s->fetch()[0] != 0){
          $s=$conn->prepare("select max(OID) from orders");
          $s->execute();
          $OID = $s -> fetch()[0] + 1;
        }  

        foreach($cart as $PID => $quantity){
          
          $stmt=$conn->prepare("select * from products where PID = $PID");
          $stmt->execute();

          if($stmt->rowCount() == 0){
            throw new Exception("There is product doesn't exist anymore in your order.");

          } else if ($stmt->fetch()['quantity'] < $quantity){
            $productName = $stmt->fetch()['productName'];
            throw new Exception("$productName not enough.");

          } else {
            $stmt=$conn->prepare("insert into order_product values ($OID, $PID, $quantity);");
            $stmt->execute();

            $stmt=$conn->prepare("update products set quantity = quantity - $quantity where PID = $PID;");
            $stmt->execute();            
          }
        }

        $stmt = $conn->prepare("insert into orders values ($OID, $SID, $UID, '$orderStatus', '$timeOrderCreated', NULL, $orderDistance, $amount, '$orderType');");
        $stmt->execute();

        $stmt = $conn->prepare("select UID from shops where SID = $SID;");
        $stmt->execute();
        $SUID = $stmt->fetch()[0];

        ###create transaction

        $stmt=$conn->prepare("select balance from users where UID = $UID;");
        $stmt->execute();

        if($amount > $stmt->fetch()[0]){
          throw new Exception("lack in balance");
        }        

      } catch (PDOException $e) {
        throw new Exception($e->getMessage());
      }
     

      $conn->commit(); 
    }
    catch(Exception $e){
      
      $conn->rollBack();
      $ok = false;
      $msg = $e->getMessage();
    }
?>

<?php
    echo json_encode(
        array(
            'ok'       => $ok,
            'msg'      => $msg
        )
    );
?>        