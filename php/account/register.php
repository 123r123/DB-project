
<?php
    $dbservername='localhost';
    $dbname='dbhw';
    $dbusername='root';
    $dbpassword='';
    $conn = new PDO("mysql:host=$dbservername; dbname=$dbname", $dbusername, $dbpassword);
    # set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
session_start();
$_SESSION['Authenticated']=false;

$ok = true;
$msg = "ddw";

try {
    if (!isset($_POST['lon']) || !isset($_POST['lat']) || !isset($_POST['pnum']) || !isset($_POST['nname']) || !isset($_POST['pwd']) || !isset($_POST['ppwd'])|| !isset($_POST['acc']))
    {
        header("Location: ../../signUp.php");
        exit();
    }



    if (empty($_POST['lon']) || empty($_POST['lat']) || empty($_POST['pnum']) || empty($_POST['nname']) || empty($_POST['pwd']) || empty($_POST['ppwd'])|| empty($_POST['acc']))
    throw new Exception('Please input all the information.');

    $nickName    = $_POST['nname']  ;
    $pwd         = $_POST['pwd']    ;
    $ppwd        = $_REQUEST['ppwd'];
    $account     = $_REQUEST['acc'] ;
    $latitude    = $_REQUEST['lat'] ;
    $longitude   = $_REQUEST['lon'];
    $phonenumber = $_REQUEST['pnum'] ;


    if($pwd!=$ppwd){
        throw new Exception('two passwords are different!');
    }

    $stmt=$conn->prepare("select username from users where account=:acc");
    $stmt->execute(array('acc' => $account));
    if ($stmt->rowCount()==0){
        try{
        $s=$conn->prepare("select max(UID) from users");
        $s->execute();
        $k = $s ->fetch();
         
        $UID = (string)((int)$k[0] + 1);
       

        $salt=strval(rand(1000,9999));
        $hashvalue=hash('sha256', $salt.$pwd);
      
        $stmt=$conn->prepare("insert into users values ('$UID', '$nickName' ,'$phonenumber' ,'$account' ,'$hashvalue' , '$salt', ST_GeomFromText('POINT($longitude $latitude)') ,100,0);");
       
        $stmt->execute();
      
        }catch(PDOException $e){
            throw new Exception($e->getMessage());
        }

        $_SESSION['Authenticated']=true;
              
    }
    else
        throw new Exception("Account used.");
}

catch(Exception $e){
    $ok = false;
    $msg=$e->getMessage();
    session_unset(); 
    session_destroy();   
}

echo json_encode(
    array(
        'ok'       => $ok,
        'msg'      => $msg
    )
);