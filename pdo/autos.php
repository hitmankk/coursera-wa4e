<?php
require_once "pdo.php";
// Demand a GET parameter
if ( ! isset($_GET['name']) || strlen($_GET['name']) < 1  ) {
    die('Name parameter missing');
}

if ( isset($_POST['logout']) ) {
    header('Location: index.php');
    return;
}
$fault = false;
if ( isset($_POST['make']) && isset($_POST['year'])
     && isset($_POST['mileage'])) {
    if(is_numeric($_POST['year']) === false || is_numeric($_POST['mileage']) === false ){
        $fault = "Mileage and year must be numeric";

    } else if(strlen($_POST['make']) < 1){
         $fault = 'Make is required';

    } else{
         $fault = "yes";
        $sql = "INSERT INTO autos (make, year, mileage)
            VALUES (:make, :year, :mileage)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':make' => $_POST['make'],
            ':year' => $_POST['year'],
            ':mileage' => $_POST['mileage']));
}
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Meng Shi database autos</title>
<body>
<div class="container">
<h1>Tracking autos for <?php echo $_GET['name']?> </h1>
<?php
if($fault === false){

}else if (  $fault === 'yes' ) {
    echo('<p style="color: green;">'.'Record inserted'."</p>\n");
} else {
    echo('<p style="color: red;">'.htmlentities($fault)."</p>\n");
}
?>
<form method="post">
<p>Make:<input type="text" name="make" size="40"></p>
<p>Year:<input type="text" name="year"></p>
<p>Mileage:<input type="text" name="mileage"></p>

<input type="submit" value="Add">
<input type="submit" name="logout" value="Logout">
<h2>Automobiles </h2>
</form>
<?php
if ( $fault === 'yes' && isset($_POST['make']) && isset($_POST['year'])
     && isset($_POST['mileage'])) {

    $sql = "SELECT make, year, mileage FROM autos";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':make' => $_POST['make'],
        ':year' => $_POST['year'],
        ':mileage' => $_POST['mileage']
      ));

    while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
        echo "<ul>";
        echo(htmlentities($row['year']));
        echo(" ");
        echo(htmlentities($row['make']));
        echo(' / ');
        echo(htmlentities($row['mileage']));
        echo("</ul>\n");
    }
}
?>
</div>
</body>
</html>
