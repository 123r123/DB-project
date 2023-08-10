

<?php

session_start();
require "php/shit/head2.php";

if(!$_SESSION['Authenticated']){
  header('Location: index.php');
} 

require "php/shit/dbConnect.php";
$action = "";
$status = "All" ;
$UID =  $_SESSION['curUser']['UID'];

if (isset($_POST['aa'])) {
  $status = $_POST['aa'] == "" ? "All" : $_POST['aa'] ;
}

if ($status === "All") {
  $action="where UID=$UID";
} else {
  $action="where orderStatus='$status' and UID=$UID";
}
?>
<script>
var selectedOrders = new Set();
</script>
<nav class="navbar navbar-inverse">
<div class="container-fluid">
<div class="navbar-header ">
  <a class="navbar-brand " href="#">DJJs</a>    
</div>
<div class="navbar-footer">
  <a class="navbar-brand nav" href="index.php" style="float: right;display: block;">logout</a>
</div>
</div>  
</nav>

<div class="container">

<ul class="nav nav-tabs">
  <li><a href="nav.php">Home</a></li>
  <li class="dropdown">
      <a href="navShop.php" class="dropdown-toggle" data-toggle="dropdown" role="button"
        aria-haspopup="true" aria-expanded="true">My shop<span class="caret"></span></a>
      <ul class="dropdown-menu">
      
        <li><a href="navShop.php">info.</a></li>
        <li><a href="navShopOrder.php">orders</a></li>
      </ul>
    </li>
  <li class="active"><a href="navMyOrder.php">My Order</a></li>
  
  <li ><a href="navTranRecord.php">Transaction Record</a></li>
</ul>

<div class="tab-content">   
  
  <div id="menu1" class="tab-pane fade in active">
  
    <div class="row">
      <hr>
      <label class="control-label col-sm-1" for="distance">Status</label>
      <div class="col-sm-5">  
      <form action="navMyOrder.php" id="form"  method="post" >
        <select name="aa" class="form-control" id="action">
          <option value = "All"     >All</option>
          <option value = "Finished" >Finished</option>
          <option value = "NotFinished" >NotFinished</option>
          <option value = "Cancel">Cancel</option>
        </select>
      </form>
      </div>

      <button type="button" class="btn btn-danger delete" id="cancelS">Cancel selected orders</button>
      
      <script>
        $("#cancelS").hide();
      </script>

      <div class="  col-xs-8">
        <table class="table" style=" margin-top: 15px;">
          <thead>
            <tr>
              <th scope="col"></th>
              <th scope="col" nowrap="nowrap">Order ID</th>
              <th scope="col">Status</th>
              <th scope="col">Start</th>
              <th scope="col">End</th>
              <th scope="col"  nowrap="nowrap">Shop name</th>
              <th scope="col"  nowrap="nowrap">Total Price</th>
              <th scope="col">Order Details</th>
              <th scope="col">Action</th>

            </tr>
          </thead>
          <tbody>
<?php

$s=$conn->prepare("select * from orders " . $action);
$s->execute();

foreach ($s->fetchAll() as $keys => $value) {
  
  $stmt   = $conn->prepare("select shopName from shops where SID=:sid;");
  $stmt -> execute(array('sid'=>$value['SID']));

  $shopname= $stmt->fetch()[0];

?> 
            <tr>
              <td><input class="form-check-input" type="checkbox" value="" id="select<?=$value['OID']?>"></td>
              <td><?=$value['OID']?></td> 
              <td><?=$value['orderStatus']?></td> 
              <td nowrap="nowrap"><?=$value['timeOrderCreated']?></td>
              <td nowrap="nowrap"><?=$value['orderStatus']=="NotFinished"?"":$value['timeOrderEnded']?></td>
              <td><?=$shopname?></td>
              <td><?=$value['orderAmount']?></td>
              <td><button type="button" class="btn btn-info" data-toggle="modal" id = "details<?=$value['OID']?>" data-target="#OID<?=$value['OID'];?>">order details</button></td>
              <td><button type="button" class="btn btn-danger delete" id="cancel<?=$value['OID']?>">Cancel</button></td>
            </tr> 

            <script>
              if("<?php echo $value['orderStatus']?>"!="NotFinished"){
                $("#cancel<?=$value['OID']?>").hide();
                $("#select<?=$value['OID']?>").hide();
              }
            </script>
<?php
}
?>
          </tbody>
          
        </table>

<?php
$s=$conn->prepare("select * from orders ".$action);
$s->execute();
foreach ($s->fetchAll() as $keys => $value) { 
?>

        <div class="modal fade" id="OID<?=$value['OID'];?>" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog">
                <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">menu</h4>
              </div>
              <div class="modal-body">

                <div class="row">
                  <div class="  col-xs-12">
                    <table class="table" style=" margin-top: 15px;">

                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Picture</th>
                          <th scope="col">meal name</th>
                          <th scope="col">price</th>
                          <th scope="col">Order Quantity</th>
                          
                        </tr>
                      </thead>
              
                      <tbody>
<?php
  $ss=$conn->prepare("select * from order_product where OID=:oid");
  $ss->execute(array('oid'=>$value['OID']));
  $subtotal = 0;

  foreach ($ss -> fetchAll() as $order => $row) {
    $id = $order+1;
    $PID = $row['PID'];

    $s=$conn->prepare("select * from products where PID=$PID");
    $s->execute();
    $product = $s->fetch();
    $sql="select * FROM productimage WHERE PID=$PID";   //ggmow : fetch img from sql

    $result = $conn->query($sql);                                                                                    
    $productName = $product['productName'];
    $price = $product['price'];
    $quantity	 = $row['quantity'];
    $subtotal = $subtotal + $price*$quantity;                                                          
?>                           
                      <tr>
                        <th scope="row"><?php echo $id;?></th>

                        <td>
<?php 
    if ($result->rowCount()>0) {
      $row = $result->fetch();
      $img=$row['image'];
      $logodata = $img;
      echo '<img  width="50" height="40" src="data:'.$row['imgType'].';base64,' . $logodata . '" />';  
    }
?>

                        </td>
                        <td>                      <?php echo $productName;?> </td>  
                        <td id="price<?=$PID;?>"> <?php echo $price;?>       </td>
                        <td>                      <?php echo $quantity;?>    </td>
                      </tr>
<?php
  }
?>

                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                  <p style="display:inline">
                    <div style="display:inline">Subtotal   $</div>
                    <div style="display:inline"><?=$subtotal;?></div>                                        
                  </p>
                  <p>
                    <div style="display:inline; font-size:smaller;">Delivery fee</div>
                    <div style="display:inline; font-size:smaller;"><?=$value['orderAmount']-$subtotal;?></div>  <!-- delivery fee can directly compute  -->
                  </p>

                  <p>
                    <div style="display:inline;">Total Price</div>
                    <div style="display:inline" ><?=$value['orderAmount'];?></div>                                        
                  </p>
              </div>
            </div>
          </div>
        </div>

        <script>
          $(document).ready(function() {
            $("#cancel<?=$value['OID']?>").click( function() {           
              $.post("php/order/cancelOrder.php",{OID : <?=$value['OID']?>} ,function(data,status){
                  $("#www").html(data);
                });        
             });
          });
        </script>
<?php
}
?>
      </div>
    </div>
  </div>
</div>
</div>

<p id="www"></p>

<script>

$('#action option[value=<?=$status?>]').prop('selected', true);

$('li.dropdown').mouseover(function () {

  if ($(document).width() > 767)
    $(this).addClass('open');

}).mouseout(function () {
  if ($(document).width() > 767)
    $(this).removeClass('open');
});

$(document).ready(function() {

  $("#action").change(function(){
    $( "#form " ).submit();
  });

});

$(document).ready(function() {
  $('input[type="checkbox"]').click ( function (){
    let id = parseInt($(this).attr('id').substring(6))

    if ($(this).prop("checked")) {
      if (selectedOrders.size === 0) {
        $("#cancelS").show();
      }
      selectedOrders.add(id)

    } else {
      selectedOrders.delete(id)

      if (selectedOrders.size === 0) {
        $("#cancelS").hide();
      }
    }
  });
});

$(document).ready(function() {
  $('#cancelS').click ( function (){
    selectedOrders.forEach( function(OID) {
      $.ajax({
        url: 'php/order/cancelOrder.php',
        type: 'post',
        data: {
          'OID':OID,
          's':1
        },
        success: function(data) {
        }
      });
    });
    alert("cancel success!" )
    window.location.reload()
  });
});
</script>
<?php require "php/shit/foot.php"; ?>