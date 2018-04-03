<?php
require_once 'pdo.php';
session_start();
if (isset($_POST['cancel'])):
    header('Location: index.php');
    return;
elseif (isset($_GET['profile_id'])):
    $query = 'SELECT * FROM profile WHERE profile_id = :pid';
    $stmt = $pdo->prepare($query);
    $stmt->execute(array(':pid' => $_GET['profile_id']));
    $result = $stmt->fetch();
    if (isset($_POST['delete'])):
        $query = "DELETE FROM `profile` WHERE profile_id = '$_GET[profile_id]'";
        $stmt = $pdo->query($query);
        $_SESSION['success_msg'] = 'Profile deleted';
        header('Location: index.php');
        return;
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
    <h1>Deleteing Profile</h1>
    <form method="post" action="delete.php?profile_id=<?=$_GET['profile_id']?>">
        <p>First Name:
            <?=$result['first_name']?>
        </p>
        <p>Last Name:
            <?=$result['last_name']?>
        </p>
        <input type="hidden" name="profile_id" value="354"/>
        <input type="submit" name="delete" value="Delete">
        <input type="submit" name="cancel" value="Cancel">
    </form>
</div>
</body>
</html>
