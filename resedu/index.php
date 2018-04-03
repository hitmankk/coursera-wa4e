<?php
require_once "pdo.php";
require_once "util.php";
session_start();
$stmt = $pdo->query('SELECT * FROM Profile');
$profiles = array();
while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
  $profiles[] = $row;
}
?>
<html>
<head>
<title>Meng Shi Resume Registry</title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
<h1>Meng Shi's Resume Registry</h1>
<?php
flashMessages();
if (!isset($_SESSION["user_id"])) {
  echo '<p><a href="login.php">Please log in</a></p>';
} else {
  echo '<p><a href="logout.php">Logout</a></p>';
}
if (count($profiles) == 0){
  echo '<p>No rows found</p>';
} else{
  echo('<table border="1">'."\n");
  echo "<tr><th>Name</th><th>Headline</th>";
  if (isset($_SESSION['user_id'])){
    echo("<th>Action</th>");
  }
  echo "</tr>\n";
  foreach ($profiles as $profile) {
    echo("<tr><td>");
    echo('<a href="view.php?profile_id='.$profile['profile_id'].'">'.htmlentities($profile['first_name'])." ".htmlentities($profile['last_name']).'</a>');
    echo("</td><td>\n");
    echo(htmlentities($profile['headline']));
    echo("</td>");
    if (isset($_SESSION['user_id'])){
      echo("<td>\n");
      if ($_SESSION['user_id']==$profile['user_id']){
        echo('<a href="edit.php?profile_id='.$profile['profile_id'].'">Edit</a> / ');
        echo('<a href="delete.php?profile_id='.$profile['profile_id'].'">Delete</a>');
      }
      echo("</td>");
    }
    echo "</tr>\n";
  }
  echo ('</table>');
}
if (isset($_SESSION['user_id'])){
  echo '<p><a href="add.php?profile_id='.$profile['profile_id'].'">Add New Entry</a></p>'."\n";
}
?>

</div>
</body>
<html>
