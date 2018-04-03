<?php require_once "pdo.php"; ?>
<!DOCTYPE html>
<html>
<head>
<title>Gagandeep Singh Profile View</title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
<?php
echo "<h1>Profile information</h1>";
/* Execute a prepared statement by passing an array of values */
$sql = 'SELECT first_name, last_name, email, headline, summary FROM profile where profile_id = :profile_id';
$sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$sth->execute(array(
            ':profile_id' => $_REQUEST['profile_id'])
            );
$row = $sth->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT);
echo('<p>First Name: '.htmlentities($row[0]).'</p>');
echo('<p>Last Name: '.htmlentities($row[1]).'</p>');
echo('<p>Email: '.htmlentities($row[2]).'</p>');
echo('<p>Headline: '.htmlentities($row[3]).'</p>');
echo('<p>Summary: '.htmlentities($row[4]).'</p>');

$sql = 'SELECT year, description FROM position where profile_id = :profile_id';
$sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$sth->execute(array(
            ':profile_id' => $_REQUEST['profile_id'])
            );
echo('<p>Positions</p>');
echo('<ul>');
while ($row = $sth->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT))  {
    echo('<li>'.$row[0].':'.$row[1].'</li>');
}
echo('</ul>');
?>
<br/>
<a href="index.php">Done</a>

</div>
</body>
</html>
