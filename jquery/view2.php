<?php
	require_once "pdo.php";
	require_once "util.php";
	session_start();

	$positions = loadPos($pdo, $_REQUEST['profile_id']);
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Meng Shi Profile View </title>
		<?php require_once "bootstrap.php"; ?>

	</head>
	<body>
		<div class="container">
			<h1>Profile information</h1>
			<?php
				$stmt = $pdo -> prepare("SELECT first_name, last_name, email, headline, summary FROM Profile
										WHERE user_id = :xyz");
				$stmt -> execute(array(":xyz" => $_SESSION['user_id']));
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
					# code...
					echo ('<p>First Name: ');
					echo (htmlentities($row['first_name']));
					echo "</p>";
					echo ('<p>Last Name: ');
					echo (htmlentities($row['last_name']));
					echo "</p>";
					echo ('<p>Email: ');
					echo (htmlentities($row['email']));
					echo "</p>";
					echo ('<p>Headline: ');
					echo (htmlentities($row['headline']));
					echo "</p>";
					echo ('<p>Summary: ');
					echo (htmlentities($row['summary']));
					echo "</p>";
					if ( ! is_null($positions)) {

						echo('<p>Position'."\n");
						echo('<div id="position_fields">'."\n");
						echo('<ul>');
						foreach ($positions as $position) {
						 	# code...
							echo('<li>');
							echo(htmlentities($position['year'].': '.htmlentities($position['description'])));
							echo('</li>');
						 }
						 echo("</ul></div></p>\n");
					 }

			?>
			<a href="index.php">Done</a>
		</div>
	</body>
</html>
