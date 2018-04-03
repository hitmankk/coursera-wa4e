<?php

$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';
    session_start();

    if (isset($_POST['cancel'])) {
        header('Location: index.php');
        return;
    }

    if (isset($_POST['email']) && isset($_POST['pass'])) {
       if (strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1) {
           $_SESSION['error'] = 'Email and password are required';
       } else if (strchr(($_POST['email']), '@') === false) {
           $_SESSION['error'] = 'Email must have an at-sign (@)';
       } else if (hash('md5', $salt .$_POST['pass']) !== $stored_hash) {
           $_SESSION['error'] = 'Incorrect password';
           error_log('Login fail ' . htmlentities($_POST['email']) . ' ' . hash('md5', $salt .$_POST['pass']));
       } else {
           $_SESSION['name'] = $_POST['email'];
           error_log('Login success ' . htmlentities($_POST['email']));
       }
      if (isset($_SESSION['name'])) {
          header('Location: view.php');
      } else {
         header('Location: login.php');
      }
      return;
    }
?>

<!DOCTYPE html>
<html>
<head> <title>Meng Shi Autos Database Login Page</title></head>
<body>
    <div class="container">
        <h1>Please Log In</h1>
        <?php
            if (isset($_SESSION['error'])) {
                echo '<p style="color: red;">' . $_SESSION['error'] . '</p>';
                unset($_SESSION['error']);
            }
        ?>
        <form method="POST">
            <label for="id_user">Email</label>
            <input type="text" name="email" id="id_user" width="60"><br>
            <label for="id_password">Password</label>
            <input type="password" name="pass" id="id_password" width="60"><br>
            <input type="submit" value="Log In">
            <input type="submit" name="cancel" value="Cancel">
        </form>
    </div>
</body>
</html>
