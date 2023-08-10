<?php
session_start();

require "../shit/dbConnect.php";

$ok = true;

$msg = "";

if (!isset($_POST['ID']) ||
    !isset($_POST['TransactionAmount']) ||
    !isset($_POST['TransactionType'])  || // recharge payment receive 
    !isset($_POST['isShop'])
    ) { exit(); }
    
$UID               = $_POST['ID'];
$TransactionAmount = $_POST['TransactionAmount'];
$TransactionType   = $_POST['TransactionType']; 
$isShop   = $_POST['isShop']; 

$timeZone      = new DateTimeZone('Asia/Taipei');
$currentDate   = new DateTime('now',$timeZone);
$timeTransactionCreated = $currentDate->format('Y-m-d H:i:s');

  $conn -> beginTransaction();
  try{
    try{
      if ($isShop) {
        $stmt=$conn->prepare("select UID from shops where SID = $UID;");
        $stmt->execute();

        $UID = $stmt->fetch()[0];
      } else {
        if ($TransactionType == 'payment'){
          $stmt=$conn->prepare("select balance from users where UID = $UID;");
          $stmt->execute();

          if($TransactionAmount > $stmt->fetch()[0]){
            throw new Exception("lack in balance");
          }

          $stmt=$conn->prepare("update users set balance = balance - $TransactionAmount where UID = $UID;");
          $stmt->execute();

          if($_SESSION['curUser']['UID'] == $UID) {
            $_SESSION['curUser']['balance']  -= $TransactionAmount;
          }

        } else {
          $stmt=$conn->prepare("update users set balance = balance + $TransactionAmount where UID = $UID;");
          $stmt->execute();

          if($_SESSION['curUser']['UID'] == $UID) {
            $_SESSION['curUser']['balance']  += $TransactionAmount;
          }
        }
      }
      
      $TID = 0;
      $s=$conn->prepare("select count(*) from transactions");
      $s->execute();
      
      if($s->fetch()[0]!=0){
        $s=$conn->prepare("select max(TID) from transactions");
        $s->execute();
        $TID = $s ->fetch()[0] + 1;
      }  

      $stmt=$conn->prepare("insert into transactions values ($TID, $UID, $TransactionAmount, '$timeTransactionCreated', '$TransactionType', $isShop );  ");
      $stmt->execute();

    } catch (PDOException $e) {
      throw new Exception($e-> getMessage());
    }
    
    $conn->commit();

  } catch (Exception $e) {
    $conn->rollBack();
    $ok = false;
    $msg = $e->getMessage();
  }
 
  echo json_encode(
    array(
        'ok'       => $ok,
        'msg'      => $msg
    )
  );
?>        