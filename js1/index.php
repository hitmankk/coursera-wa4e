<?php
session_start();
require_once "pdo.php";
$account = "umsi@umich.edu";
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Meng Shi</title>
    <meta charset="utf-8">
<style>
table, th, td {border: 1px solid black;border-collapse:separate;border-spacing: .12em}
td, th {text-align: center; margin: 5px 5px 5px 5px; padding: 5px;}
td {text-align: left;}
</style>
</head>
<body>
  <div class="container">
  <h1>Meng Shi Severance's Resume Registry</h1>
  <br><p></p>
<?php
if ( isset($_SESSION['success']) ) {
    echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
    unset($_SESSION['success']);
}
if ( ! isset($_SESSION['name']) ){
    echo '<a href="login.php">Please log in</a>';
    $account = "umsi@umich.edu";
    $stmt = $pdo->query("SELECT * FROM profile");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    echo('<table border="1">'."\n");
    echo('<th>Name</th><th>Headline</th>');
    $stmt = $pdo->query("SELECT first_name, last_name, headline, profile_id FROM profile");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        echo "<tr><td>";
        $name = htmlentities($row["first_name"]).' '.htmlentities($row["last_name"]);
        echo('<a href="view.php?profile_id='.$row['profile_id'].'">'.$name.'</a>');
        echo("</td><td>");
        echo(htmlentities($row['headline']));
        echo("</td><td>");
        echo("\n<form>\n");
        echo("</td></tr>\n");
    }
    echo '</table>';
    echo "<p>
    <b>Note:</b> Your implementation should retain data across multiple
    logout/login sessions.  This sample implementation clears all its
    data periodically - which you should not do in your implementation.
    </p>";

}else{
    echo '<a href="logout.php">Logout</a>';
    $stmt = $pdo->query("SELECT * FROM profile");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo('<table border="1">'."\n");
    echo('<th>Name</th><th>Headline</th>');
    $stmt = $pdo->query("SELECT first_name, last_name, headline, profile_id FROM profile");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        echo "<tr><td>";
        $name = htmlentities($row["first_name"]).' '.htmlentities($row["last_name"]);
        echo('<a href="view.php?profile_id='.$row['profile_id'].'">'.$name.'</a>');
        echo("</td><td>");
        echo(htmlentities($row['headline']));
        echo("</td><td>");
        echo('<a href="edit.php?profile_id='.$row['profile_id'].'">Edit</a>'." / ");//GET parameter
        echo('<a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a>');  //GET paramater
        echo("\n<form>\n");
        echo("</td></tr>\n");
        }
    echo '</table><br><p><a href="add.php">Add New Entry</a></p>';

}
?>
</div>
</body>
</html>
