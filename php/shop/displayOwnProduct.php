<?php
  try{
    $s=$conn->prepare("select * from products where SID =:sid");
    $s->execute(array('sid' => $_SESSION['curUser']['SID']));
    $count = 1;
    foreach($s->fetchAll() as $product){

      $PID = $product['PID'];
      $productName = $product['productName'];
      $price = $product['price'];
      $quantity	 = $product['quantity'];

      $sql="select * FROM productimage WHERE PID=$PID";
      $result = $conn->query($sql);
   
      
      //查詢結果     

      echo <<<EOT
      <script>
        $(document).ready(function() {
            $("#edit$productName").click( function() {          
                var quantity= $("#quantity$productName").val();
                var price = $("#price$productName").val();
                var productName = '$productName';
                // if(!quantity ||!price){
                //   alert("empty column is illegal!")
                //   $("#toEdit$productName"").click()
                //   return
                // }
                if(quantity.length==0||price.length==0){
                  $("#shopResErrMsg$productName").html('please fill all information!!')
                  return
                }
                if(!(/^\d+$/.test(quantity)&&/^\d+$/.test(price))){                 
                  $("#shopResErrMsg$productName").html('illegal input!!')
                  return
                }
                $("#shopResErrMsg$productName").load("php/shop/editOwnProduct.php", {
                    quantity :quantity,
                    price :price,
                    productName :productName
                });
               
                
            });   
          });
       $(document).ready(function() {
          $("#del$productName").click( function() {          
              var quantity= $("#quantity$productName").val();
              var price = $("#price$productName").val();
              var productName = '$productName';
              
              
              if(confirm("remove $productName?")){
                $("#shopResErrMsg$productName").load("php/shop/deleteOwnProduct.php", {
                  quantity :quantity,
                  price :price,
                  productName :productName
                });   
              }       
          });   
      });
      
      </script>
        <tr>
            
            <th scope="row">$count</th>
            <td>
      
    EOT; 
      if ($result->rowCount()>0) {
        $row = $result->fetch();
        $img=$row['image'];
        $logodata = $img;
        echo '<img  width="50" height="40" src="data:'.$row['imgType'].';base64,' . $logodata . '" />';  
      }

      echo <<<EOT
            </td>
            <td>$productName</td>

            <td>$price </td>
            <td>$quantity </td>
            <td>
              <button type="button" class="btn btn-info" data-toggle="modal" id = "toEdit$productName" data-target="#$productName-1">
              Edit
              </button>
            </td>
            <!-- Modal -->
            <div class="modal fade" id="$productName-1" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  
                    <div class="modal-content">
                    
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">$productName Edit</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>              
                          <div class="modal-body">
                            <div class="row">
                              <div class="col-xs-6">
                                <label for="ex71">price</label>
                                <input name="price" value="$price" id="price$productName" class="form-control"  type="text" pattern="^[0-9]+$" required>
                              </div>
                              <div class="col-xs-6">
                                <label for="ex41">quantity</label>
                                <input name="quantity" value="$quantity" id="quantity$productName" class="form-control"  type="text" pattern="^[0-9]+$" required>
                              </div>
                            </div>
                          </div> 
                          
                          <div class="modal-footer">
                          <p id="shopResErrMsg$productName" style="color : red"></p>
                            <button type="submit" class="btn btn-primary"    id="edit$productName">Edit</button>
                          </div>                    
                    </div>
                </div>
            </div>
            <td><button type="button" class="btn btn-danger delete" id="del$productName">Delete</button></td>
        </tr>

        EOT;
      $count ++;

    }
  }
  
  catch(PDOException $e){
    $MSG = $e ->getMessage();
    echo <<<EOT
    <script> 
    alert("$MSG");
    </script> 
    EOT; 
  }
?>




                            
                          