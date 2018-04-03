<?php
session_start();
unset($_SESSION['success']);
require_once "pdo.php";
require_once "util.php";
// if ( ! isset($_SESSION['id'])) {
//     die('Not logged in');
// }
 ?>
<html>
<head>
<title>Meng Shi Profile Database</title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
  <div class="container">
  <h1>Profile information</h1>
  <?php
  $stmt = $pdo->prepare("SELECT * FROM profile WHERE profile_id=:xyz");
  $stmt->execute(array(":xyz" => $_GET['profile_id']));
  $row = $stmt -> fetch(PDO::FETCH_ASSOC);
  if ( $row === false ) {
      $_SESSION['error'] = 'Missing profile_id';
      header( 'Location: index.php' );
      return;
  } else {
    $fn = htmlentities($row['first_name']);
    $ln = htmlentities($row['last_name']);
    $e = htmlentities($row['email']);
    $h = htmlentities($row['headline']);
    $s = htmlentities($row['summary']);
    $profile_id = $row['profile_id'];
    echo "<p>First Name: ".$fn."</p>";
    echo "<p>Last Name: ".$ln."</p>";
    echo "<p>Email: ".$e."</p>";
    echo "<p>Headline:<br/>".$h."</p>";
    echo "<p>Summary:<br/>".$s."</p>";
    $educations = loadEdu($pdo, $profile_id);
    if (count($educations)>0){
      echo "<p>Educations</p>";
      echo "<ul>\n";
      foreach ($educations as $education){
        $year = htmlentities($education['year']);
        $name = htmlentities($education['name']);
        echo "<li>".$year.": ".$name."</li>";
      }
      echo "</ul>\n";
    }
    $positions = loadPos($pdo, $profile_id);
    if (count($positions)>0){
      echo "<p>Positions</p>";
      echo "<ul>\n";
      foreach ($positions as $position){
        $year = htmlentities($position['year']);
        $desc = htmlentities($position['description']);
        echo "<li>".$year.": ".$desc."</li>";
      }
      echo "</ul>\n";
    }
  }
   ?>
  <a href="index.php">Done</a>
  </div>
</body>
</html>
