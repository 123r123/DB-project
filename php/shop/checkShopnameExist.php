<?php include("../shit/dbConnect.php");?>
<?php
session_start();



$ok = true;
$msg = "";
    if (!isset($_POST['shopname'])||empty($_POST['shopname']))
    {
        exit();
    }
        
    $shopname = $_REQUEST['shopname'];
   
    try{
        
        //* 店名可不可重複
        $stmt=$conn->prepare("select shopname from shops where shopname=:acc");
        $stmt->execute(array('acc' => $shopname));

        if ($stmt->rowCount()!=0){

            $ok = false;
            $msg = "shop name existed.";


        }
      
    }
    catch(PDOException $e){
        $_SESSION['ok'] = false;
        $k = $e->getMessage();
        echo "$k";
    }
    echo json_encode(
        array(
            'ok'       => $ok,
            'msg'      => $msg
        )
    );
?>        