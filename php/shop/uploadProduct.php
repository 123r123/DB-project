<?php

require "../shit/dbConnect.php";
session_start();
$_SESSION['ok'] = true;
$msg = "";
try {
   
 
  $productName = $_REQUEST['pname'] ;
  $price       = $_REQUEST['price'] ;
  $quantity    = $_REQUEST['quantity'] ;
  //$imagePath   = $_REQUEST['myFile'] ;
  //開啟圖片檔
  $file = fopen($_FILES["upfile"]["tmp_name"], "rb");
  // 讀入圖片檔資料
  $fileContents = fread($file, filesize($_FILES["upfile"]["tmp_name"])); 
  //關閉圖片檔
  fclose($file);
  //讀取出來的圖片資料必須使用base64_encode()函數加以編碼：圖片檔案資料編碼
  $fileContents = base64_encode($fileContents);


 // exit();

  try{
    
    $PICID = "0";
    $s=$conn->prepare("select count(*) from productimage");
    $s->execute();

    if($s->fetch()[0]!=0){
      $s=$conn->prepare("select max(PICID) from productimage");
      $s->execute();
      $PICID = (string)((int)$s -> fetch()[0] + 1);
    }  


    $imgType=$_FILES["upfile"]["type"];
    




    $stmt=$conn->prepare("select SID from shops where UID=:user");
    $stmt->execute(array('user' => $_SESSION['curUser']['UID']));
    $SID = $stmt->fetch()[0];

    $stmt=$conn->prepare("select * from products where productName = '$productName' and SID = $SID");
    $stmt->execute();
    if ($stmt->rowCount()!=0){
      throw new Exception('Product existed.');
    }
    else{

      $PID = "0";
      $dummyPID = "1";
      $s=$conn->prepare("select count(*) from products");
      $s->execute();
  
      if ($s->fetch()[0]!=0) {
        $s=$conn->prepare("select max(PID) from products");
        $s->execute();
        $PID = (string)((int)$s ->fetch()[0]);
        $dummyPID = $PID+1;

        $stmt=$conn->prepare("delete from products where price = -1");
        $stmt->execute();
        
      }
      

      $stmt=$conn->prepare("insert into products values ($PID, $SID ,'$productName' ,$price ,$quantity);");       
      $stmt->execute();

      $stmt=$conn->prepare("insert into products values ($dummyPID, -1 ,'dummy' , -1 ,-1);");       
      $stmt->execute();

      $stmt=$conn->prepare("insert INTO productimage values ($PICID,$PID,'$fileContents','$imgType');");
      $stmt->execute();
    }
    echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
        <script>
        alert("add product success!" )
        window.location.replace("../../navShop.php")
        </script> </body> </html>
    EOT;
    
    exit();
  }
  catch(PDOException $e){
    $_SESSION['ok'] =false;
    $msg = $e->getMessage();
    $_SESSION['errMsg'] = $e->getMessage();
    echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
        <script>
        alert("$msg")
        window.location.replace("../../navShop.php")
        </script> </body> </html>
    EOT;
  }
}
catch(Exception $e){
  $_SESSION['ok'] =false;
  $msg = $e->getMessage();
  $_SESSION['errMsg'] = $e->getMessage();
    echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
        <script>
       alert("$msg" )
        window.location.replace("../../navShop.php")
        </script> </body> </html>
    EOT;
}