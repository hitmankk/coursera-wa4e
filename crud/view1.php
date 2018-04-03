revise grades after you submit them.

Automobiles CRUD Database
In this assignment you will expand a web based application to Create, Read, Update, and Delete (C.R.U.D.) data in a MySQL database. All interactions will follow the POST-Redirect and Flash Message patterns where appropriate. All generated HTML must be properly protected using htmlentities().


Code: Source code of index.php (click to view)

×
Source code of index.php
<?php
//PHP MODULE 5 - CRUD
session_start();
require_once "../testing/bootstrap.php";
require_once "pdo.php";

$account = "umsi@umich.edu";
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Kramer Herzog</title>
    <meta charset="utf-8">
<style>
table, th, td {border: 1px solid black;border-collapse:separate;border-spacing: .13em}
td, th {text-align: center; margin: 5px 5px 5px 5px; padding: 5px;}
td {text-align: left;}
</style>
</head>
<body>
  <div class="container">
  <h2>Welcome to the Automobiles Database</h2>
  <br><p></p>
<?php
//print_r ($_SESSION);
if ( ! isset($_SESSION['name']) ){// no name set!
          echo '<a href="login.php">Please log in</a>';
          return;
}

if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}
if ( isset($_SESSION['success']) ) {
    echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
    unset($_SESSION['success']);
}


//$stop = NULL;
$account = "umsi@umich.edu";

$stmt = $pdo->query("SELECT * FROM autos");
$row = $stmt->fetch(PDO::FETCH_ASSOC);
//echo ("LINE47: db".$row['autos_id']);
if ($row['autos_id'] == NULL) {
  echo ("No rows found<p></p>");
  echo ('<p><a href="add.php">Add New Entry</a></p>');
  echo ('<a href="logout.php">Log Out</a>');

}
  else{
            echo('<table border="1">'."\n");
            echo('<th>Make</th><th>Model</th><th>Year</th><th>Mileage</th><th>Action</th>');
            $stmt = $pdo->query("SELECT make, model, year, mileage, autos_id FROM autos");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

            echo "<tr><td>";
            echo(htmlentities($row['make']));
            echo("</td><td>");
            echo(htmlentities($row['model']));
            echo("</td><td>");
            echo(htmlentities($row['year']));
            echo("</td><td>");
            echo(htmlentities($row['mileage']));
            echo("</td><td>");
            echo('<a href="edit.php?autos_id='.$row['autos_id'].'">Edit</a>'." / ");//GET parameter
            echo('<a href="delete.php?autos_id='.$row['autos_id'].'">Delete</a>');  //GET paramater
            echo("\n<form>\n");
            echo("</td></tr>\n");

           }
           echo '</table><br><p><a href="add.php">Add New Entry</a></p>';
           echo '<p><a href="logout.php">Logout</a></p>';
           }

?>

</div>
</body>
</html>
