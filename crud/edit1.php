<?php
require_once "pdo.php";
session_start();
if ( isset($_POST['make']) && isset($_POST['model']) && isset($_POST['year']) && isset($_POST['mileage']) ) {
     $sql = "UPDATE autos SET make = :make, model = :model, year = :year WHERE autos_id = :autos_id";
     $stmt = $pdo->prepare($sql);
     $stmt->execute(array(
        ':make' => $_POST['make'],
        ':model' => $_POST['model'],
        ':year' => $_POST['year'],
        ':mileage' => $_POST['mileage']));
    $_SESSION['rsuccess'] = 'Record updated';
    header( 'Location: view1.php' ) ;
    return;
}
$stmt = $pdo->prepare("SELECT * FROM autos where autos_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['autos_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
     $_SESSION['error'] = 'Bad value for autos_id';
     header( 'Location: index.php' ) ;
     return;
}
$ma = htmlentities($row['make']);
$mo = htmlentities($row['model']);
$y = htmlentities($row['year']);
$mi = htmlentities($row['mileage']);
$autos_id = $row['autso_id'];
?>
<p>Edit autos</p>
<form method="post">
<p>Make:
<input type="text" name="make" value="<?= $ma ?>"></p>
<p>Model:
<input type="text" name="model" value="<?= $mo ?>"></p>
<p>Year:
<input type="text" name="year" value="<?= $y ?>"></p>
<p>Mileage:
<input type="text" name="mileage" value="<?= $mi ?>"></p>
<input type="hidden" name="autos_id" value="<?= $autos_id ?>">
<p><input type="submit" value="Update"/>
<a href="index.php">Cancel</a></p>
</form>
