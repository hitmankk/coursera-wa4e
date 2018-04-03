<?php
session_start();
require_once "pdo.php";
if ( ! isset($_SESSION['user_id'])) {
    die('ACCESS DENIED');
}
if ( isset($_POST['delete']) && isset($_POST['profile_id']) ) {
    $sql = "DELETE FROM Profile WHERE profile_id = :zip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_POST['profile_id']));
    $_SESSION['success'] = 'Record deleted';
    header( 'Location: index.php' ) ;
    return;
}
if ( ! isset($_GET['profile_id']) ) {
  $_SESSION['error'] = "Missing profile_id";
  header('Location: index.php');
  return;
}
$stmt = $pdo->prepare("SELECT profile_id FROM Profile where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for profile_id';
    header( 'Location: index.php' ) ;
    return;
}
$stmt = $pdo->prepare("SELECT * FROM profile where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for profile_id';
    header( 'Location: index.php' ) ;
    return;
}
$fn = htmlentities($row['first_name']);
$ln = htmlentities($row['last_name']);
?>
<html>
<head>
<title>Meng Shi profile Database</title>
<?php
require_once "bootstrap.php";
?>
<?php require_once "head.php"; ?>
</head>
<body>
<div class="container">
  <h1>Deleteing Profile</h1>
  <form method="post" action="delete.php">
  <?php
  echo "<p>First Name: ".$fn."</p>";
  echo "<p>Last Name: ".$ln."</p>";
   ?>
  <input type="hidden" name="profile_id" value="<?= $row['profile_id'] ?>"/>
  <input type="submit" name="delete" value="Delete">
  <input type="submit" name="cancel" value="Cancel">
  </p>
  </form>
</div>
</body>
</html>
