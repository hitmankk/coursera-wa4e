<?php require_once "pdo.php"; ?>
<?php
session_start();
$stmt = $pdo->prepare("SELECT * FROM profile where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_REQUEST['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$firstName = htmlentities($row['first_name']);
$lastName = htmlentities($row['last_name']);
$email = htmlentities($row['email']);
$headline = htmlentities($row['headline']);
$summary = htmlentities($row['summary']);
?>
<!DOCTYPE html>
<html>
<head>
<title>Meng Shi's Profile View</title>
</head>
<body>
<div class="container">
<h1>Profile information</h1>
<p>First Name:
<?php echo $firstName?></p>
<p>Last Name:
<?php echo $lastName?></p>
<p>Email:
<?php echo $email?></a></p>
<p>Headline:<br/>
<?php echo $headline?></p>
<p>Summary:<br/>
<?php echo $summary?><p>
</p>
<a href="index.php">Done</a>
</div>
<script data-cfasync="false" src="/cdn-cgi/scripts/d07b1474/cloudflare-static/email-decode.min.js"></script></body>
</html>
