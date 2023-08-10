<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<p>
  <input type="checkbox" id="cbox1" value="first_checkbox">
  <label for="cbox1">This is the first checkbox</label>
</p>
<p>
  <input type="checkbox" id="cbox2" value="second_checkbox" checked="checked">
  <label for="cbox2">This is the second checkbox, which is checked</label>
</p>
<h1 id="ff">ff</h1>
<script>
    
    if($("#cbox1").attr("checked")=="checked"){
        $("h1").text("hello");
    }
</script>