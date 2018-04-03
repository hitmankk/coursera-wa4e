<?php
require_once "pdo.php";

// p' OR '1' = '1
if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to game.php
    header("Location: index.php");
    return;
}

$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';  // Pw is php123
$failure = false;
$contains = '@';
if ( isset($_POST['who']) && isset($_POST['pass'])  ) {
    echo("<p>Handling POST data...</p>\n");
    $contains = strstr($_POST['who'], '@');
    if ( strlen($_POST['who']) < 1 || strlen($_POST['pass']) < 1 ) {
        $failure = "Email and password are required";
    } else if($contains === false){
        $failure = 'Email must have an at-sign (@)';
    } else {
      $check = hash('md5', $salt.$_POST['pass']);
      if ( $check == $stored_hash ) {
           error_log("Login success ".$_POST['who']);
           header("Location: autos.php?name=".urlencode($_POST['who']));
           return;
        } else {
           $failure = "Incorrect password";
           error_log("Login fail ".$_POST['who']." $check");
        }
    }
  }
?>
<!DOCTYPE html>
<html>
<head>
<title>Meng Shi database autos</title>
<body>
<div class="container">
<h1>Please Log In</h1>
<?php

if ( $failure !== false ) {
    // Look closely at the use of single and double quotes
    echo('<p style="color: red;">'.htmlentities($failure)."</p>\n");
}
?>
<form method="post">
  <label for="email">Email</label>
  <input type="text" name="who" id="email"><br/>
  <label for="id_1724">Password</label>
  <input type="text" name="pass" id="id_1724"><br/>
  <input type="submit" value="Log In">
  <input type="submit" name="cancel" value="Cancel">
  </form>
<a href="<?php echo($_SERVER['PHP_SELF']);?>">Refresh</a></p>
</form>
</div>
</body>
</html>
