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
    if (isset($_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['headline'], $_POST['summary'])):
        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];
        $email = $_POST['email'];
        $headLine = $_POST['headline'];
        $summary = $_POST['summary'];
        if (empty($firstName) || empty($lastName) || empty($email) || empty($headLine) || empty($summary)):
            $_SESSION['error_msg'] = 'All fields are required';
            header('location: edit.php?profile_id='.$_GET['profile_id']);
            return;
        elseif (!strpos($email, '@')):
            $_SESSION['error_msg'] = 'Email must contain \'@\' symbol';
            header('location: edit.php?profile_id='.$_GET['profile_id']);
            return;
        else:
            $query = "UPDATE profile SET user_id = :uid, first_name = :fn, last_name = :ln, email = :eml, headline = :hl, summary = :smy WHERE profile_id = '$_GET[profile_id]'";
            $stmt = $pdo->prepare($query);
            $stmt->execute(array(
                ':uid' => $_SESSION['user_id'],
                ':fn' => $firstName,
                ':ln' => $lastName,
                ':eml' => $email,
                ':hl' => $headLine,
                ':smy' => $summary,
            ));
            $_SESSION['success_msg'] = 'Profile edited';
            header('Location: index.php');
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

    <h1>Editing Profile for <?=$result['first_name']?></h1>

    <!-- Flashing error message When login will fail -->
    <?php if (isset($_SESSION['error_msg'])): ?>
        <p style="color: red"><?=$_SESSION['error_msg']?></p>
        <?php unset($_SESSION['error_msg']);?>
    <?php endif; ?>

    <form method="post">
        <p>
            First Name:
            <input type="text" name="first_name" size="60" value="<?=$result['first_name']?>"/>
        </p>
        <p>
            Last Name:
            <input type="text" name="last_name" size="60" value="<?=$result['last_name']?>"/>
        </p>
        <p>
            Email:
            <input type="text" name="email" size="30" value="<?=$result['email']?>"/></p>
        <p>
            Headline:<br/>
            <input type="text" name="headline" size="80" value="<?=$result['headline']?>"/></p>
        <p>
            Summary:<br/>
            <textarea name="summary" rows="8" cols="80"><?=$result['summary']?></textarea>
        <p>
            <input type="hidden" name="profile_id" value="<?=$result['profile_id']?>"/>
            <input type="submit" value="Save">
            <input type="submit" name="cancel" value="Cancel">
        </p>
    </form>
</div>

</body>
</html>
