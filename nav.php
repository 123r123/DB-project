

<?php require "php/shit/head2.php"; ?>
<?php
  session_start();
  $_SESSION['ok'] = true;

  if(!$_SESSION['Authenticated']){
    header('Location: index.php');
  } 
  
  require "php/shit/dbConnect.php";

  $lo = $_SESSION['curUser']['longitude'];
  $la = $_SESSION['curUser']['latitude'];
  
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
    <li class="active"><a href="nav.php">Home</a></li>

    <li class="dropdown">
      <a href="navShop.php" class="dropdown-toggle" data-toggle="dropdown" role="button"
        aria-haspopup="true" aria-expanded="true">My shop<span class="caret"></span></a>
      <ul class="dropdown-menu">
        <li><a href="navShop.php">info.</a></li>
        <li><a href="navShopOrder.php">orders</a></li>
      </ul>
    </li>

    <li><a href="navMyOrder.php">My Order</a></li>
    <li><a href="navTranRecord.php">Transaction Record</a></li>
  </ul>

  <div class="tab-content">
    <div id="home" class="tab-pane fade in active">
      <h3>Profile</h3>
      <div class="row">
        <div class="col-xs-12">
          
          Accouont: <?php echo $_SESSION['curUser']['account']; ?>, user: <?php echo $_SESSION['curUser']['username'];?>, PhoneNumber: <?php echo $_SESSION['curUser']['phoneNum']; ?>, location: <?php echo $_SESSION['curUser']['latitude'],',',$_SESSION['curUser']['longitude']; ?>

          <button type="button " style="margin-left: 5px;" class=" btn btn-info " data-toggle="modal" data-target="#location">edit location</button>
           
          <div class="modal fade" id="location" data-backdrop="static"  tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog  modal-sm">
              <div class="modal-content">
                <form action="php/nav/edit_location.php" id="lonlatedit" class="fh5co-form animate-box" data-animate-effect="fadeIn" method="post" >
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">edit location</h4>
                  </div>
                  
                  <div class="modal-body">
                    <label class="control-label " for="latitude">latitude</label>
                    <input name= lat type="text" class="form-control" id="latitude" title="float from -90~90!" placeholder="enter latitude" value ="<?= $_SESSION['curUser']['latitude'];?>" required="required" pattern="[-+]?[0-9]*\.?[0-9]*">
                    <br>
                    <label class="control-label " for="longitude">longitude</label>
                    <input name= lon type="text" class="form-control" id="longitude" title="float from -180~180!" value ="<?= $_SESSION['curUser']['longitude'];?>" placeholder="enter longitude" required="required" pattern="[-+]?[0-9]*\.?[0-9]*">
                  </div>

                  <div class="modal-footer">
                    <p id="ErrMsg" style="color : red"></p>
                    <button type="submit" id="editLonLanButton" class="btn btn-primary">Edit</button>
                    <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Edit</button> -->
                  </div>
                </form>
              </div>
            </div>
          </div>

          <script>
              
            $("#lonlatedit").submit( (event)=>{
              if(parseFloat($("#latitude").val())>90 || parseFloat($("#longitude").val())>180 || parseFloat($("#longitude").val())<-180 || parseFloat($("#latitude").val())<-90 ){
                event.preventDefault()
                $("#ErrMsg").html('illegal input!!')
              }
            })
            
          </script>
           
          walletbalance: <?php echo $_SESSION['curUser']['balance']; ?>
            <!-- Modal -->
          <button type="button " style="margin-left: 5px;" class=" btn btn-info " data-toggle="modal" data-target="#myModal">Add value</button>

          <div class="modal fade" id="myModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog  modal-sm">
              <div class="modal-content">
              <form action="php/nav/add_value.php" id="addMoneySubmit" class="fh5co-form animate-box" data-animate-effect="fadeIn" method="post" >

                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Add value</h4>
                </div>

                <div class="modal-body">
                  <input name="add_value" type="text" class="form-control" id="advalue" placeholder="enter add value" required="required" pattern="^[0-9]+$" >
                </div>

                <div class="modal-footer">
                  <input type="submit" value="Add" class="btn btn-primary">                 
                </div>

              </form>
              </div>
            </div>
          </div>
        </div>
      </div>

      <script>
      $(document).ready(function() {
        $("#addMoneySubmit").submit( (event)=>{
          event.preventDefault()
          
          $.ajax({
              url: 'php/transaction/createTransaction.php',
              type: 'post',
              data: {
                'ID':<?=$_SESSION['curUser']['UID'];?>,
                'TransactionAmount':parseInt($("#advalue").val()),
                'TransactionType': 'recharge',
                'isShop': 0
              },
              success: function(data) {
                
                 let response = JSON.parse(data)

               if(response.ok) {
                   alert("add value success!")
                  window.location.reload();

                } else {
                  alert("add value failed! errmsg: "+response.msg)
                  window.location.reload();
                }
              }
            });

          })
        })

      </script>
      <!-- 
          搜尋SEARCH
          頁面 跳轉to action_page.php
          用session return data 
      -->
      <h3>Search</h3>
      <div class=" row  col-xs-8">
        <form method="post" class="form-horizontal" action="php/nav/action_page.php">

          <div class="form-group">
            <label class="control-label col-sm-1" for="Shop">Shop</label>
            <div class="col-sm-5">
              <input name="shopname" type="text" class="form-control" placeholder="Enter Shop name">
            </div>
            <label class="control-label col-sm-1" for="distance">distance</label>
            <div class="col-sm-5">  

              <select name="distance" class="form-control" id="sel1">
                <option>null</option>
                <option>near</option>
                <option>medium </option>
                <option>far</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-sm-1" for="Price">Price</label>
            <div class="col-sm-2">
              <input name="min_price" type="text" class="form-control">
            </div>
            <label class="control-label col-sm-1" for="~">~</label>
            <div class="col-sm-2">
              <input name="max_price" type="text" class="form-control">
            </div>
            <label class="control-label col-sm-1" for="Meal">Meal</label>
            <div class="col-sm-5">
              <input name="meal" type="text" list="Meals" class="form-control" id="Meal" placeholder="Enter Meal">
              <datalist id="Meals">
                <option value="Hamburger">
                <option value="coffee">
              </datalist>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-sm-1" for="category"> category</label>
            <div class="col-sm-5">
              <input name="category" type="text" list="categorys" class="form-control" id="category" placeholder="Enter shop category">
              <datalist id="categorys">
                <option value="fast food">
              </datalist>
            </div>

            <button type="submit" style="margin-left: 18px;" class="btn btn-primary">Search</button>

          </div>
        </form>
      </div>

      <!--搜尋結果              SECTION search result  -->
      <div class="row">
        <div class="  col-xs-8">
          <table class="table" style=" margin-top: 15px;">
          
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">shop name</th>
                <th scope="col">shop category</th>
                <th scope="col">Distance</th>
              </tr>
            </thead>

            <tbody>

<?php 
  $Shopsname=array();
  if($_SESSION['begin']){   

  } else {
    foreach($_SESSION['Shops'] as $keys => $value){                                     // NOTE result  -->
      $s=$conn->prepare("select * from shops where SID=$value");
      $s->execute();
      $shop = $s->fetch();
      $id = $keys+1;
      $shopname = $shop['shopname'];
      array_push($Shopsname,$shopname);
      $category = $shop['category'];
      $s=$conn->prepare("select ST_Distance_Sphere(POINT(:lon,:lat),location) from shops where SID=$value");
      $s->execute(array('lon' => $_SESSION['curUser']['longitude'],'lat' => $_SESSION['curUser']['latitude']  ));
      $dis = $s -> fetch()[0];
      
      if($dis<100){$distance='near';}
      elseif($dis<10000){$distance = 'medium';}
      else{$distance = 'far';}
  
?>

              <tr>
              <th scope="row"><?php echo $id ?></th>

              <td><?php echo $shopname ?></td>
              <td><?php echo $category ?></td>
              <td> <?php echo $distance ?> </td>
              <td> <button type="button" class="btn btn-info " data-toggle="modal" data-target="#<?=$shopname;?>" >Open menu</button></td>

              </tr>
             
              <?php
                      }
                  }
              ?>
            </tbody>
          </table>
          <!-- Modal -->
<?php 
  if($_SESSION['begin']){   
                                                       
  } else {
  foreach($_SESSION['Shops'] as $keys => $value){
    $s=$conn->prepare("select * from shops where SID=$value");
    $s->execute();
    $shop     = $s->fetch();
    $shopname = $shop['shopname'];
    $SID      = $value;

    $s=$conn->prepare("select ST_Distance_Sphere(POINT(:lon,:lat),location) from shops where SID=$SID");
    $s->execute(array('lon' => $_SESSION['curUser']['longitude'],'lat' => $_SESSION['curUser']['latitude']  ));
    $dis = $s -> fetch()[0];
?>

          <script>
             
            var cart<?=$SID;?> = new Map()
             
          </script>
          <div class="modal fade" id="<?=$shopname;?>" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
                              <th scope="col">Quantity</th>
                              <th scope="col" id="orderQ<?=$SID?>">Order</th>
                            </tr>
                          </thead>
                  
                          <tbody>

<?php
  $s=$conn->prepare("select * from products where SID=$value");
  $s->execute();
  
  foreach($s -> fetchAll() as $keys => $product){
      
    $id = $keys+1;
    $PID = $product['PID'];

    $sql="select * FROM productimage WHERE PID=$PID";   //ggmow : fetch img from sql
    $result = $conn->query($sql);                                                                                    
    $productName = $product['productName'];
    $price = $product['price'];
    $quantity	 = $product['quantity'];                                                          
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

                                <td class="row"> 
                                  <button type="button" id= "minus<?=$PID;?>" class="btn btn-default col-md-3 minus<?=$SID;?>">-</button>
                                  <div id="quantity<?=$PID;?>" class="col-md-3">0</div>
                                  <button type="button" id= "plus<?=$PID;?>" class="btn btn-default col-md-3 plus<?=$SID;?>">+</button>
                                </td>
                              </tr>

                              <script>
                                  
                                $("#minus<?=$PID;?>").click( ()=>{

                                  let k           = parseInt($("#quantity<?=$PID;?>").html());  
                                  let curSubtotal = parseInt($("#Subtotal<?=$SID;?>").html());  
                                  
                                  if(k == 0){
                                    if (cart<?=$SID;?>.has(<?=$PID;?>) ){
                                      cart<?=$SID;?>.delete(<?=$PID;?>)
                                    }
                                    return
                                  }   

                                  cart<?=$SID;?>.set(<?=$PID;?>,cart<?=$SID;?>.get(<?=$PID;?>) - 1)
                                  let newSubtotal = curSubtotal - parseInt($("#price<?=$PID;?>").html())
                                  let v = k-1;
                                  
                                  $("#quantity<?=$PID;?>").html(v.toString())
                                  $("#Subtotal<?=$SID;?>").html(newSubtotal.toString())

                                })

                                $("#plus<?=$PID;?>").click( ()=>{

                                  let k           = parseInt($("#quantity<?=$PID;?>").html());  
                                  let curSubtotal = parseInt($("#Subtotal<?=$SID;?>").html());  
                                  
                                  if(k == <?=$quantity;?>) return 

                                  if (!cart<?=$SID;?>.has(<?=$PID;?>)) {
                                    cart<?=$SID;?>.set(<?=$PID;?>,1)

                                  } else {
                                    cart<?=$SID;?>.set(<?=$PID;?>,cart<?=$SID;?>.get(<?=$PID;?>) + 1)
                                  }

                                  let newSubtotal = curSubtotal + parseInt($("#price<?=$PID;?>").html())
                                  let v = k+1;
                                  
                                  $("#quantity<?=$PID;?>").html(v.toString())
                                  $("#Subtotal<?=$SID;?>").html(newSubtotal.toString())             
                                                                 
                                })
                                  
                              </script> 

<?php
  }
?>

                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>

                  <div style="float:left ; margin-left:20px;">


                    <div style="display:inline" class="custom-control custom-radio custom-control-inline">
                      <input type="radio" class="custom-control-input" name="fuckB"id="Delivery<?=$SID;?>"  class="defaultDeli" checked>
                      <label class="custom-control-label"  for="Delivery<?=$SID;?>" >Delivery</label>
                    </div>

                    <!-- Default inline 2-->
                    <div  style="display:inline"class="custom-control custom-radio custom-control-inline">
                      <input type="radio" class="custom-control-input"name="fuckB" id="Pickup<?=$SID;?>" >
                      <label class="custom-control-label" for="Pickup<?=$SID;?>">Pick-up</label>
                    </div>
                  
                  </div>

                  <div class="modal-footer">
                      <!-- Default inline 1-->
                      
                    <p style="display:inline">
                      <div style="display:inline">Subtotal   $</div><div style="display:inline" id="Subtotal<?=$SID;?>">0</div>                                        
                    </p>

                    <p>
                      <div style="display:inline; font-size:smaller;" class="words fucker1<?=$SID;?>"></div><div style="display:inline;font-size:smaller;" class="fucker2<?=$SID;?>"></div>                                        
                    </p>

                    <p>
                      <div style="display:inline;"class="fucker3<?=$SID;?>"></div><div style="display:inline" class="fucker4<?=$SID;?>" id="totalPrice<?=$SID;?>"></div>                                        
                    </p>
                    <!-- Default inline 1-->
                    <button type="button" value="0" style="float:left" id="back<?=$SID;?>"class="btn btn-default nowave" >back</button>
                    <button type="button" value="0" class="btn btn-default nowave" id="CalPriceBtn<?=$SID;?>">calculate the price</button>
                    <script>

                      $(".defaultDeli").prop('checked', true)
                      $("#back<?=$SID;?>").hide()

                      $("#back<?=$SID;?>").on('click', () => {

                        $(".plus<?=$SID;?>").show()
                        $(".minus<?=$SID;?>").show()
                        $("#orderQ<?=$SID?>").html("order")
                        $("#back<?=$SID;?>").hide()

                        $(".fucker1<?=$SID;?>").html("")  
                        $(".fucker2<?=$SID;?>").html("")  
                        $(".fucker3<?=$SID;?>").html("")
                        $(".fucker4<?=$SID;?>").html("")

                        $("#CalPriceBtn<?=$SID;?>").html("calculate the price")
                      })


                      $("#CalPriceBtn<?=$SID;?>").on('click', () => {

                        let total = parseInt($("#Subtotal<?=$SID;?>").html())
                        let orderType = 1

                        if ($("#CalPriceBtn<?=$SID;?>").html() != 'order'){

                          $(".plus<?=$SID;?>").hide()
                          $(".minus<?=$SID;?>").hide()
                          $("#orderQ<?=$SID?>").html("order quantity")
                          $("#CalPriceBtn<?=$SID;?>").html('order')
                          $("#back<?=$SID;?>").show()
                          
                          if ($("#Delivery<?=$SID;?>").is(':checked')) {
                            $(".fucker1<?=$SID;?>").html("Delivery fee   $")
                            
                            let fee = Math.round(Math.max(<?=$dis;?> * 10,10))
                            $(".fucker2<?=$SID;?>").html(fee.toString())
                            total = total + fee
                            
                          } else {
                            let orderType = 0
                          }

                          $(".fucker3<?=$SID;?>").html("Total price   $")
                          $(".fucker4<?=$SID;?>").html(total.toString())

                        } else {
                          if (cart<?=$SID;?>.size === 0) {
                            alert("You had ordered nothing!")
                            $("#back<?=$SID;?>").click()
                            return
                          }

                          let total = parseInt($("#totalPrice<?=$SID;?>").html())

                          cart<?=$SID;?>.set(-1,<?=$SID;?>)  // [-1] is SID   
                          cart<?=$SID;?>.set(-2,total)       // [-2] is total price
                          cart<?=$SID;?>.set(-3,orderType)   // [-3] is 0: take 1: delivery
                          cart<?=$SID;?>.set(-4,<?=$dis;?>)  // [-4] is order distance

                          const json = JSON.stringify(Object.fromEntries(cart<?=$SID;?>));

                          $.ajax({
                            url: 'php/order/createOrder.php',
                            type: 'post',
                            data: {cart:json},
                            success: function(data) {

                              let response = JSON.parse(data)
                              if(response.ok) {

                                $.ajax({
                                  url: 'php/transaction/createTransaction.php',
                                  type: 'post',
                                  data: {
                                    'ID':<?=$_SESSION['curUser']['UID'];?>,
                                    'TransactionAmount':total,
                                    'TransactionType': 'payment',
                                    'isShop': 0
                                  },
                                  success: function(data) {
                                    let response = JSON.parse(data)

                                    if(!response.ok) {
                                      alert("transaction failed! errmsg: "+response.msg)
                                      window.location.reload();
                                    } 
                                  }
                                });
                                $.ajax({
                                  url: 'php/transaction/createTransaction.php',
                                  type: 'post',
                                  data: {
                                    'ID':<?=$SID;?>,
                                    'TransactionAmount':total,
                                    'TransactionType': 'receive',
                                    'isShop': 1
                                  },
                                  success: function(data) {
                                    let response = JSON.parse(data)

                                    if(!response.ok) {
                                      alert("transaction failed! errmsg: "+response.msg)
                                      window.location.reload();
                                    } 
                                  }
                                });
                                alert("order created!")
                                window.location.replace("navMyOrder.php")

                              } else {
                                alert(response.msg)
                              }
                            }
                          });
                        }
                      })

                    </script>

                  </div>
                </div>
              </div>
          </div>
<?php
    }
  }
?>
        </div>

      </div>
    </div>  
    <!-- !SECTION    -->
  </div>
</div>

<script>

    
                                   
  $('li.dropdown').mouseover(function () {

    if ($(document).width() > 767)
      $(this).addClass('open')

  }).mouseout(function () {

    if ($(document).width() > 767)
        $(this).removeClass('open')

  });

</script>

<?php require "php/shit/foot.php"; ?>