<?php include("php/shit/dbConnect.php");?>
<?php require "php/shit/head2.php"; ?>
<?php
  session_start();
  $_SESSION['ok'] = true;

  if(!$_SESSION['Authenticated']){
    header('Location: index.php');
  } 
  
  if(isset($_SESSION['upload'])){
    echo $_SESSION['upload'];
    unset($_SESSION['upload']);
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

    <li class="dropdown active">     
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
    <div id="menu1" class="tab-pane fade in active">
      <h3> Start a business </h3>

      <script>

        $(document).ready(function() {
          $("#shopResForm").submit( function(event) {
            event.preventDefault();

            var shopname = $("#exshopname").val();
            var category = $("#excategory").val();
            var latitude = $("#exlatitude").val();
            var longitude = $("#exlongitude").val();

            let lon = parseFloat(longitude)
            let lat = parseFloat(latitude)

            if(Math.abs(lat)>90||Math.abs(lon)>180){
              $("#shopResErrMsg").html('illegal location inout!');
              return;
            }

            $("#shopResErrMsg").load("php/shop/shopRegister.php", {
              shopname :shopname,
              category :category,
              latitude :latitude,
              longitude :longitude
            });
            //checkResShop();
          });   
        });
          
      </script>

      <form action="php/shop/shopRegister.php" class="fh5co-form animate-box" data-animate-effect="fadeIn" method="post"  target="nm_iframe" id="shopResForm">
        <div class="form-group " id="shopInformation">

          <div class="row">
            <div class="col-xs-2">
              <label for="ex5">shop name</label>
              <input name="shopname" class="form-control" id="exshopname" placeholder="macdonald" type="text" required="required" value="">
            </div>
            <div class="col-xs-2">
              <label for="ex5">shop category</label>
              <input name="category" class="form-control" id="excategory" placeholder="fast food" type="text" required="required" >
            </div>
            <div class="col-xs-2">
              <label for="ex6">latitude</label>
              <input name="latitude" class="form-control" id="exlatitude" placeholder="121.00028167648875" type="text" pattern="[-+]?[0-9]*\.?[0-9]*" required="required" title="float from -90~90!">
            </div>
            <div class="col-xs-2">
              <label for="ex8">longitude</label>
              <input name="longitude" class="form-control" id="exlongitude" placeholder="24.78472733371133" type="text" pattern="[-+]?[0-9]*\.?[0-9]*" required="required" title="float from -180~180!" >
            </div>
          </div>
        </div>

        <p id = "shopResErrMsg" style="color:red">
        </p>
        
        <div class=" row" style=" margin-top: 25px;">
          <div class=" col-xs-3">
          <button type="submit" style=" margin-top: 15px;" value="register" class="btn btn-primary" id="shopResSubmit">register</button>
              <!-- <button type="button" class="btn btn-primary">register</button> -->
          </div>
        </div>
      </form>
      
      <script>
                        
          $(document).ready(function() {
            $(".nav-tabs a").click(function() {
              $(this).tab('show');
            });
          });

          if(<?php echo $_SESSION['curUser']['identity']?>){       
            $('#shopResSubmit').prop('disabled', true)   
              
            $('#shopInformation .form-control').each(function(){
                $(this).attr('readonly','readonly');
            });

            $('#exshopname').attr('value', '<?php if($_SESSION['curUser']['identity'])echo $_SESSION['curUser']['shop']['shopName']?>');
            $('#excategory').attr('value', '<?php if($_SESSION['curUser']['identity'])echo $_SESSION['curUser']['shop']['category']?>');
            $('#exlatitude').attr('value', '<?php if($_SESSION['curUser']['identity'])echo $_SESSION['curUser']['shop']['latitude']?>');
            $('#exlongitude').attr('value','<?php if($_SESSION['curUser']['identity'])echo $_SESSION['curUser']['shop']['longitude']?>');
            $('#menu1 h3:first-child').html('Your business')
          }

          $(window).keyup(function(e){
              if($("#exshopname").is(":focus")){

                  if(<?php echo $_SESSION['curUser']['identity']?>) return

                  const request = new XMLHttpRequest();

                  request.onload = () => {
                    let responseObject = null;

                    try {
                      responseObject = JSON.parse(request.responseText);

                    } catch (e) {
                      console.error(request.responseText);
                    }

                    if (responseObject) {
                      handleResponse(responseObject);
                    }
                  };

                  const requestData = `shopname=${$("#exshopname").val()}`;
                  request.open('post', 'php/shop/checkShopnameExist.php');
                  request.setRequestHeader('content-type', 'application/x-www-form-urlencoded');
                  request.send(requestData);
              }
          })
          
          function handleResponse(responseObject){
            $("#shopResErrMsg").html('')

            if(!responseObject.ok){
              $("#shopResErrMsg").html(responseObject.msg)
            }
          }
          
      </script>

      <hr>

<?php 

  if(!$_SESSION['curUser']['identity']){
    exit();
  }

?>

      <h3>ADD</h3>
      <form class="form-group " method="post" action="php/shop/uploadProduct.php" Enctype="multipart/form-data" id= "addProForm">
       
        <div class="row">
          <div class="col-xs-6">
            <label for="ex3">meal name</label>
            <input class="form-control" id="pname" type="text" name="pname"  required>
          </div>
        </div>

        <div class="row" style=" margin-top: 15px;">
          <div class="col-xs-3">
            <label for="ex7">price</label>
            <input class="form-control" id="price" name="price" pattern="^[0-9]+$" type="text" required>
          </div>
          <div class="col-xs-3">
            <label for="ex4">quantity</label>
            <input class="form-control" id="quantity"  name="quantity" pattern="^[0-9]+$" type="text" required>
          </div>
        </div>

        <p id="addproErrMsg" style="color :red ">
        </p>

        <div class="row" style=" margin-top: 25px;">
          <div class=" col-xs-3">
            <label for="ex12">上傳圖片</label>
            <input id="myFile" type="file" name="upfile" multiple class="file-loading" required>
          </div>
          <div class=" col-xs-3">
            <button id="fuck" style=" margin-top: 15px;" type="submit" class="btn btn-primary">Add</button>
          </div>
        </div>

      </form>

      <div class="row">
        <div class="  col-xs-8">
          <table class="table" style=" margin-top: 15px;">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Picture</th>
                <th scope="col">meal name</th>
                <th scope="col">price</th>
                <th scope="col">Quantity</th>
                <th scope="col">Edit</th>
                <th scope="col">Delete</th>
              </tr>
            </thead>
            <tbody>
            <iframe name="dummyframe" id="dummyframe" style="display: none;"><?php require "php/shop/displayOwnProduct.php"; ?></iframe>
            <?php require "php/shop/displayOwnProduct.php"; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script>

    $('li.dropdown').mouseover(function () {

      if ($(document).width() > 767)
        $(this).addClass('open');

    }).mouseout(function () {

        if ($(document).width() > 767)
          $(this).removeClass('open');
    });

</script>


<!-- Option 1: Bootstrap Bundle with Popper -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script> -->


<!-- Option 2: Separate Popper and Bootstrap JS -->
<!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
<?php require "php/shit/foot.php"; ?>