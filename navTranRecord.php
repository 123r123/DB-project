

<?php

session_start();
require "php/shit/head2.php";

if(!$_SESSION['Authenticated']){
  header('Location: index.php');
} 

require "php/shit/dbConnect.php";
$action = "";
$fuck = "all" ;

if (isset($_POST['ac'])) {
  $fuck = $_POST['ac'] == "" ? "all" : $_POST['ac'] ;
  if ($_POST['ac'] != "all") {
    $action = " where TransactionType = '" . $_POST['ac'] . "'";
  } 
}
?>



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
  <li><a href="navMyOrder.php">My Order</a></li>
  
  <li class="active"><a href="navTranRecord.php">Transaction Record</a></li>
</ul>

<div class="tab-content">   
  
  <div id="menu1" class="tab-pane fade in active">
  
    <div class="row">
      <hr>
    <label class="control-label col-sm-1" for="distance">action</label>
          <div class="col-sm-5">  
            <select name="distance" class="form-control" id="action">
              <option value = "all"     >all</option>
              <option value = "receive" >receive</option>
              <option value = "payment" >payment </option>
              <option value = "recharge">recharge</option>
            </select>
          </div>
      <div class="  col-xs-8">
        <table class="table" style=" margin-top: 15px;">

          <thead>
            <tr>
              <th scope="col">Record ID</th>
              <th scope="col">Action</th>
              <th scope="col">Time</th>
              <th scope="col">Trader</th>
              <th scope="col">Amount change</th>

            </tr>
          </thead>

          <tbody>

<?php
foreach ($conn -> query("select * from transactions" . $action) as $id => $tra) {
  

  $UID    = $tra['UID']; 

  $stmt   = $tra['isShop'] == true ? $conn->prepare("select shopName from shops where SID = $UID;") : $conn->prepare("select account from users where UID = $UID");
  $stmt -> execute();

  $trader = $stmt->fetch()[0];
  $sign   = $tra['TransactionType'] == 'payment' ? '-' : '+';
?> 

          <tr>
            <td><?=$id+1?></td> 
            <td><?=$tra['TransactionType']?></td> 
            <td><?=$tra['timeTransaction']?></td>
            <td><?=$trader?></td>
            <td><?=$sign?><?=$tra['TransactionAmount']?></td> 
          </tr> 

<?php
}
?>

          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</div>

<form action="navTranRecord.php" id="pp"  method="post" >
<input type="hidden" id="ac" name="ac" value="3487">
</form>

<script>

$('#action option[value=<?=$fuck?>]').prop('selected', true);

$('li.dropdown').mouseover(function () {

  if ($(document).width() > 767)
    $(this).addClass('open');

}).mouseout(function () {
  if ($(document).width() > 767)
    $(this).removeClass('open');
});

$(document).ready(function() {

  $("#action").change(function(){
    var value =  $("#action option:selected")
    
    $('#ac').attr('value', value.text());
    $( "#pp" ).submit();
    
  });

});
</script>


<?php require "php/shit/foot.php"; ?>