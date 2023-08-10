<?php

include("../shit/dbConnect.php");


$msg = "";

try {
   
 
    $account   = $_REQUEST['acc'] ;


 
    
    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", 
    $dbusername, $dbpassword);
    # set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, 
    PDO::ERRMODE_EXCEPTION);
    $stmt=$conn->prepare("select username from users where account=:acc");
    $stmt->execute(array('acc' => $account));
    if ($stmt->rowCount()!=0){
        throw new Exception("Account used.");
    }
        
}

catch(Exception $e){

    $msg=$e->getMessage();

}
echo json_encode(
    array(
 
        'msg'      => $msg
    )
);