<?php
    require_once('pdo.php');
    session_start();
    if (isset($_POST['cancel'])) {
        header('Location: index.php');
        return;
    }

    if (!isset($_SESSION['user_id'])) {
        die('Access denied');
        return;
    }

    if (   isset($_POST['first_name'])
        && isset($_POST['last_name'])
        && isset($_POST['email'])
        && isset($_POST['headline'])
        && isset($_POST['summary'])) {
        if (   strlen($_POST['first_name']) < 1
            || strlen($_POST['last_name']) < 1
            ||strlen($_POST['email']) < 1
            || strlen($_POST['headline']) < 1
            || strlen($_POST['summary']) < 1) {
                $_SESSION['error'] = 'All values are required';

        }  else if (strchr(($_POST['email']), '@') === false) {
            $_SESSION['error'] = 'Email must have an at-sign (@)';

        }else {
          $stmt = $pdo->prepare('INSERT INTO profile
              (user_id, first_name, last_name, email, headline, summary)
              VALUES ( :uid, :fn, :l, :em, :he, :su)');
          $stmt->execute(array(
              ':uid' => $_SESSION['user_id'],
              ':fn' => $_POST['first_name'],
              ':l' => $_POST['last_name'],
              ':em' => $_POST['email'],
              ':he' => $_POST['headline'],
              ':su' => $_POST['summary'])
          );
          $_SESSION['success'] = "Profile added";
        }
        if (isset($_SESSION['success'])) {
            header('Location: index.php');
        } else {
            header('Location: add.php');
        }
        return;
    }
?>

<!DOCTYPE html>
<html>
<head><title>Meng Shi Autos Database</title></head>
<body>
    <div class='container'>
        <h1>Tracking Autos for <?php echo htmlentities($_SESSION['name']) ?></h1>
        <?php
            if (isset($_SESSION['error'])) {
                echo '<p style="color: red;">' . $_SESSION['error'] . '</p>';
                unset($_SESSION['error']);
            }
        ?>
        <form method="POST">
              <p>First Name:
              <input type="text" name="first_name" size="60"/></p>
              <p>Last Name:
              <input type="text" name="last_name" size="60"/></p>
              <p>Email:
              <input type="text" name="email" size="30"/></p>
              <p>Headline:<br/>
              <input type="text" name="headline" size="80"/></p>
              <p>Summary:<br/>
              <textarea name="summary" rows="8" cols="80"></textarea>
              <p>
              <input type="submit" value="Add">
              <input type="submit" name="cancel" value="Cancel">
        </form>
    </div>
</body>
</html>
