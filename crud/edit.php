<?php
session_start();
require_once "pdo.php";//database connect

if ( ! isset($_SESSION['name']) ) {
    die("ACCESS DENIED");
}else{
  $email = $_SESSION['name'];
}

if (isset($_POST["autos_id"]) && isset($_POST["make"])
  && isset($_POST["model"]) && isset($_POST["year"])
  && isset($_POST["mileage"]) ) {

  $sql = "UPDATE autos SET
         make = :make,
         model = :model,
         year = :year,
         mileage = :mileage
         WHERE autos_id = :autos_id";

      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(
           ':make' => $_POST['make'],
           ':model' => $_POST['model'],
           ':year' => $_POST['year'],
           ':mileage' => $_POST['mileage'],
           ':autos_id' => $_POST['autos_id']) );

           if ($_POST['make'] == "" || $_POST['model'] == ""
              || $_POST['year'] == "" || $_POST['mileage'] == "" ) {
                $_SESSION['error'] = "All fields are required";
                header("Location: edit.php?autos_id=".$_REQUEST['autos_id']);
                return;}

           $_SESSION['success'] = "Record updated";
           header('Location: index.php');
           return;
  }

  // Guardian: Make sure that user_id is present
if ( ! isset($_GET['autos_id']) ) {
    $_SESSION['error'] = "Missing autos_id";
    header('Location: index.php');
    return;
  }

  $stmt = $pdo->prepare("SELECT * FROM autos where autos_id = :xyz");
  $stmt->execute(array(":xyz" => $_GET['autos_id']));
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if ( $row === false ) {
      $_SESSION['error'] = 'Bad value for autos_id';
      header( 'Location: index.php' ) ;
      return;  }

?>

  <!DOCTYPE html>
  <html>
  <head>
  <title>Kramer Herzog</title>
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
  <!-- Optional theme -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
  </head>
  <body>
  <div class="container">
  <h1>Tracking Autos for <?php echo $email;?> </h1>

<?php

// Flash pattern
if ( isset($_SESSION['error']) ) {
      echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
      unset($_SESSION['error']);
  }


if ( isset($_SESSION['success']) ) {
echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
unset($_SESSION['success']);
}

$autos_id = $row['autos_id'];
$make = htmlentities($row['make']);
$model = htmlentities($row['model']);
$year = htmlentities($row['year']);
$mileage = htmlentities($row['mileage']);

?>

<p>Edit User</p>
<form method="post">
<p>Make:
<input type="text" name="make" value="<?= $make ?>"></p>
<p>Model:
<input type="text" name="model" value="<?= $model ?>"></p>
<p>Year:
<input type="text" name="year" value="<?= $year ?>"></p>
<p>Mileage:
<input type="text" name="mileage" value="<?= $mileage ?>"></p>
<input type="hidden" name="autos_id" value="<?=$autos_id ?>">
<p><input type="submit" value="Save"/>
<a href="index.php">Cancel</a></p>
</form>
</html>
