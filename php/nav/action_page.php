<?php include("../shit/dbConnect.php");?>
<?php


session_start();


if(empty($_POST['shopname']) && $_POST['distance']=='null' && empty($_POST['min_price']) 
    && empty($_POST['max_price']) && empty($_POST['meal']) && empty($_POST['category']) )
{
    echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
        <script>
        window.location.replace("../../nav.php")
        alert("請輸入任一資訊" )
        </script> </body> </html>
    EOT;
}

else{
    if(!empty($_POST['shopname'])){
        
        $shopname = $_POST['shopname'];
        $s=$conn->prepare("select * from shops where shopname like '%$shopname%'");
        $s->execute();
        
    }elseif($_POST['distance']!='null'){
        $distance = $_POST['distance'];
        if($distance=='near'){$dis=100;$lolim=0;}
        elseif($distance=='medium'){$dis=10000;$lolim=100;}
        elseif($distance=='far'){$dis=10000000;$lolim=10000;}
        $s=$conn->prepare("select * from shops where ST_Distance_Sphere(POINT(:lon ,:lat),location) < $dis and ST_Distance_Sphere(POINT(:lon ,:lat),location) >$lolim ");
        $s->execute(array('lon' => $_SESSION['curUser']['longitude'],'lat' => $_SESSION['curUser']['latitude']  ));
        
    }elseif(!empty($_POST['min_price']) || !empty($_POST['max_price'])){
        $min_price = $_POST['min_price'];
        $max_price = $_POST['max_price'];
        if(empty($_POST['min_price'])){
            $s=$conn->prepare("SELECT SID FROM shops 
            NATURAL JOIN `products` WHERE price<=$max_price 
            GROUP BY SID ");
            $s->execute();
        }elseif(empty($_POST['max_price'])){
            $s=$conn->prepare("SELECT SID FROM shops 
            NATURAL JOIN `products` WHERE price>=$min_price 
            GROUP BY SID ");
            $s->execute();
        }else{
            $s=$conn->prepare("SELECT SID FROM shops 
            NATURAL JOIN `products` WHERE price<=$max_price and price>=$min_price
            GROUP BY SID ");
            $s->execute();
        }
        
        
        
    }elseif(!empty($_POST['meal'])){
        $meal = $_POST['meal'];
        $s=$conn->prepare(" SELECT SID FROM products WHERE productName LIKE '%$meal%' GROUP BY SID");
        $s->execute();
       
    }elseif(!empty($_POST['category'])){
        $category = $_POST['category'];
        $s=$conn->prepare("select * from shops where category like '%$category%'");
        $s->execute();
        
    }
    
    $Shops=array();
    foreach($s->fetchAll() as $value){
        array_push($Shops, $value['SID']);
    }
    $_SESSION['Shops']=$Shops;
    print_r($_SESSION['Shops']);

    // connect with login.php line 66
    $_SESSION['begin']=false;
    header("location:../../nav.php");

}

?>



