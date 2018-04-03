<?php
require_once 'pdo.php';
session_start();
if (isset($_POST['cancel'])):
    header('location: index.php');
    return;
elseif (isset($_POST['email'], $_POST['pass'])):
    $email = $_POST['email'];
    $password = $_POST['pass'];
    if (empty($email) || empty($password)):
        $_SESSION['error_msg'] = 'All fields are required';
        header('location: login.php');
        return;
    elseif (!strpos($email, '@')):
        $_SESSION['error_msg'] = 'Email must contain \'@\' symbol';
        header('location: login.php');
        return;
    else:
        $salt = 'XyZzy12*_';
        $hashedPassword = hash('md5', $salt.$password);
        $query = 'SELECT * FROM users WHERE email = :eml AND password = :pss';
        $stmt = $pdo->prepare($query);
        $stmt->execute(array(
           ':eml'    => $email,
           ':pss'    => $hashedPassword,
        ));
        $result = $stmt->fetch();
        if ($result !== false):
            $_SESSION['name'] = $result['name'];
            $_SESSION['user_id'] = $result['user_id'];
            header('location: index.php');
            return;
        else:
            $_SESSION['error_msg'] = 'Incorrect Password';
            header('location: login.php');
            return;
        endif;
    endif;
endif;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Meng Shi</title>

</head>

<body>

<div class="container">

    <h1>Please Log In</h1>

    <!-- Flashing error message When login will fail -->
    <?php if (isset($_SESSION['error_msg'])): ?>
        <p style="color: red"><?=$_SESSION['error_msg']?></p>
        <?php unset($_SESSION['error_msg']);?>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <label for="email">Email</label>
        <input type="text" name="email" id="email"><br/>
        <label for="id_1723">Password</label>
        <input type="password" name="pass" id="id_1723"><br/>
        <input type="submit" value="Log In" onclick="return doValidate();">
        <input type="submit" name="cancel" value="Cancel">
    </form>

</div>

<script>
    function doValidate() {
        console.log('Validating...');
        try {
        var email = document.getElementById('email').value;
        var pass = document.getElementById('id_1723').value;
        console.log('Validating address = ' + email + ' password = ' + pass);
        if (email == null || email === '' || pass == null || pass === '') {
            alert('Both fields must be filled out');
            return false;
        } else if (email.indexOf('@') === -1) {
            alert('Invalid email address');
            return false;
        }
        return true;
        } catch (e) {
            return false;
        }
    }
</script>
</body>
