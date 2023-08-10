<?php include("../shit/dbConnect.php");?>
<?php
session_start();
$_SESSION['Authenticated']=false;
$_SESSION['username'] = "";
//echo 'alert("ffd")';//

$ok = true;
$msg = "";
$us  = "";
$id = false;
try{
  
  if (!isset($_POST['acc']) || !isset($_POST['pwd']))
  {
    //header("Content-Disposition:attachment;filename=test.pdf");
    header("Location: ../../index.php");   
  }

  if (empty($_POST['acc']) || empty($_POST['pwd']))
    throw new Exception('Please input account and password.');

  $account=$_POST['acc'];
  $pwd=$_POST['pwd'];


  $stmt=$conn->prepare("select * from users where account=:Acc");
  $stmt->execute(array('Acc' => $account));
  if ($stmt->rowCount()==1){
    $row = $stmt->fetch();
    $fuck = hash('sha256',$row['salt'].$pwd);
    if ($row['password']==hash('sha256',$row['salt'].$pwd)){
      try{

        $_SESSION['Authenticated'] = true;
      
        $_SESSION['curUser']['account'] = $row['account'];
        $_SESSION['curUser']['UID'] = $row['UID'];
        $_SESSION['curUser']['username'] = $row['username'];
        $_SESSION['curUser']['phoneNum'] = $row['phoneNum'];
        $_SESSION['curUser']['password'] = $pwd;

        $stmt=$conn->prepare("select ST_AsText(location) from users where account=:acc");
        $stmt->execute(array('acc' => $account));

        $geoloca = $stmt->fetch()["ST_AsText(location)"];
        $g = substr($geoloca, 6, strlen($geoloca)-6-1);
        $loca = explode(" ",$g);

        $_SESSION['curUser']['longitude'] = $loca[0];
        $_SESSION['curUser']['latitude'] = $loca[1];
        $_SESSION['curUser']['balance'] = $row['balance'];
        $_SESSION['curUser']['identity'] = $row['identity'];
        $us = $row['username']; 
          
        if($row['identity']){

          $stmt=$conn->prepare("select * from shops where UID=:uid");
          $stmt->execute(array('uid' => $row['UID']));
          $row = $stmt->fetch();
          $_SESSION['curUser']['SID'] =  $row['SID'];
          $_SESSION['curUser']['shop']['shopName']  = $row['shopname'];
          $_SESSION['curUser']['shop']['category']  = $row['category'];

          $stmt=$conn->prepare("select ST_AsText(location) from shops where UID=:uid");
          $stmt->execute(array('uid' => $row['UID']));
          $row = $stmt->fetch();

          $geoloc = $row["ST_AsText(location)"];
          $gg = substr($geoloc, 6, strlen($geoloc)-6-1);
          $local = explode(" ",$gg);

          $_SESSION['curUser']['shop']['latitude']  = $local[1];
          $_SESSION['curUser']['shop']['longitude'] = $local[0];
          $id = true ;

        }
      } 
      catch(PDOException $e){
        throw new Exception($e->getMessage());
      }
      //陳 判斷是否為剛進入 connect with search "action_page line 69"
      $_SESSION['begin']=true;
    }
    else{    
      throw new Exception('wrong password.');
    }      
  }
  else
    throw new Exception('account not exist.');
}

catch(Exception $e){
  $ok = false ;
  $msg = $e->getMessage();
  session_unset(); 
  session_destroy(); 
}
echo json_encode(
  array(
      'ok'       => $ok,
      'msg'      => $msg,
      'us'       => $us
  )
);



?>
