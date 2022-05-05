<?php
session_start();
$_SESSION['Authenticated']=false;
$dbservername='localhost';
$dbname='acdb';
$dbusername='root';
$dbpassword='';



    if (!isset($_POST['longitude']) ||!isset($_POST['latitude']) ||!isset($_POST['shopname']) || !isset($_POST['category']))
    {
        header("Location: ../../signUp.php");
        exit();
    }


    $shopname = $_POST['shopname'];
    $category = $_POST['category'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];


    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    # set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    /* 店名可不可重複
    $stmt=$conn->prepare("select shopname from shops where shopname=:acc");
    $stmt->execute(array('acc' => $shopname));

    if ($stmt->rowCount()==0){
    */
    $stmt = $conn->prepare("select max(SID) from shops");
    $stmt->execute();
    $SID = $stmt->fetch()[0] + 1 ;

       
    $stmt=$conn->prepare("insert into shops(SID, shopname, category, location ) values ($SID, '$shopname' ,'$category' , ST_GeomFromText('POINT($latitude $longitude)'));");
    $stmt->execute();
        
    echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
        <script>
        alert("Resgister successfully.");
        window.location.replace("../../nav.php");
        </script> </body> </html>
    EOT;
        
        exit();
    

    //}else
    //    throw new Exception("Logup failed.");
