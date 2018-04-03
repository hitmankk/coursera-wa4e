<?php
require_once('pdo.php');
$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';
    session_start();
    unset($_SESSION['name']);
    unset($_SESSION['user_id']);

    if (isset($_POST['cancel'])) {
        header('Location: index.php');
        return;
    }

    if (isset($_POST['email']) && isset($_POST['pass'])) {
       if (strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1) {
           $_SESSION['error'] = 'Email and password are required';
           return;
       } else if (strchr(($_POST['email']), '@') === false) {
           $_SESSION['error'] = 'Email must have an at-sign (@)';
           return;
       } else {
           $check = hash('md5', $salt .$_POST['pass']);
           $sql = "SELECT user_id, name from users where email = :em and password = :pw";
           $stmt = $pdo->prepare($sql);
           $stmt->execute(array(':em' =>$_POST['email'], ':pw'=>$check));
           $row = $stmt->fetch(PDO::FETCH_ASSOC);
           if($row !== false){
               $_SESSION['name'] = $row['name'];
               $_SESSION['user_id'] = $row['user_id'];
               error_log('Login success ' . htmlentities($_POST['email']));
               header("Location: index.php");
               return;
           }else{
                 $_SESSION['error'] = 'Incorrect password';
                 error_log('Login fail ' . htmlentities($_POST['email']) . ' ' . hash('md5', $salt .$_POST['pass']));
                 return;
             }
          }
    }
?>

<!DOCTYPE html>
<html>
<head> <title>Meng Shi Severance's Resume Registry</title></head>
<body>
    <div class="container">
        <h1>Please Log In</h1>
        <?php
            if (isset($_SESSION['error'])) {
                echo '<p style="color: red;">' . $_SESSION['error'] . '</p>';
                unset($_SESSION['error']);
            }
        ?>
        <form method="POST" action="login.php">
        <label for="email">Email</label>
        <input type="text" name="email" id="email"><br/>
        <label for="id_1723">Password</label>
        <input type="password" name="pass" id="id_1723"><br/>
        <input type="submit" onclick="return doValidate();" value="Log In">
        <input type="submit" name="cancel" value="Cancel">
        </form>
        <p>
        For a password hint, view source and find an account and password hint
        in the HTML comments.

        </p>
        <script>
        function doValidate() {
            console.log('Validating...');
            try {
                addr = document.getElementById('email').value;
                pw = document.getElementById('id_1723').value;
                console.log("Validating addr="+addr+" pw="+pw);
                if (addr == null || addr == "" || pw == null || pw == "") {
                    alert("Both fields must be filled out");
                    return false;
                }
                if ( addr.indexOf('@') == -1 ) {
                    alert("Invalid email address");
                    return false;
                }
                return true;
            } catch(e) {
                return false;
            }
            return false;
        }
        </script>
    </div>
</body>
</html>
