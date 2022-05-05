<?php
  $username = '';
  if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    if ($username === 'admin' && $password === 'admin') {}
      echo '登入成功';
  }
?>
<form action='/index.php' method='POST'>
  username: <input name='username' />
  password: <input name='password' />
  <input type='submit' />
</form>