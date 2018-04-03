<?php
	require_once "pdo.php";
	require_once "util.php";
	session_start();

	$fn = "";
	$ln = "";
	$em = "";
	$he = "";
	$su = "";

	//if the user is not logged in redirect back to index.php
	if ( ! isset($_SESSION['user_id']) ) {
    	die('ACCESS DENIED');
    	return;
	}

	if(isset($_POST['first_name']) && isset($_POST['last_name'])
     && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])){
		$msg = validateProfile();
		if(is_string($msg)){
			$_SESSION['error'] = $msg;
			header("Location: add.php");
			return;
		}

		$msg = validatePos();
		if(is_string($msg)){
			$_SESSION['error'] = $msg;
			header("Location: add.php");
			return;
		}

		$stmt = $pdo->prepare('INSERT INTO Profile
	    	(user_id, first_name, last_name, email, headline, summary)
	    	VALUES ( :uid, :fn, :ln, :em, :he,:su)');
	    $stmt->execute(array(
	        ':uid' => $_SESSION['user_id'],
	        ':fn' => $_POST['first_name'],
	        ':ln' => $_POST['last_name'],
	    	':em' => $_POST['email'],
	    	':he' => $_POST['headline'],
	    	':su' => $_POST['summary'])
		);
		$profile_id = $pdo->lastInsertId();

		//insert position entries
		$rank = 1;
		for($i=1; $i<=9; $i++){
			if( ! isset($_POST['year'.$i])) continue;
			if( ! isset($_POST['desc'.$i])) continue;
			$year = $_POST['year'.$i];
			$desc = $_POST['desc'.$i];

			$stmt = $pdo->prepare('INSERT INTO Position
				(profile_id, rank, year, description)
				VALUES (:pid, :rank, :year, :desc)');
			$stmt ->execute(array(
				':pid' => $profile_id,
				':rank' => $rank,
				':year' => $year,
				':desc' => $desc)
			);
			$rank++;

			/*$_SESSION['success'] = "Profile added ";
			header("Location: index.php");
			return;*/
		}

		if (isset($_POST['Value'])) {
			# code...
			$_SESSION['success'] = "Profile added";
			header("Location: index.php");
			return;
		}
	}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Edgar Solis Add Page</title>
		<?php require_once "bootstrap.php"; ?>
		<?php require_once "head.php"; ?>
	</head>
	<body>
		<div class="container">
			<?php
					echo '<h1>Adding Profile for ';
					echo htmlentities($_SESSION['name']);
					echo '</h1>';
			?>
			<?php flashMessages(); ?>

			<form method="post">
				<p>First Name:
					<input type="text" name="first_name" size="60" value="<?= htmlentities($fn) ?>"/></p>
				<p>Last Name:
					<input type="text" name="last_name" size="60" value="<?= htmlentities($ln) ?>"/></p>
				<p>Email:
					<input type="text" name="email" size="30" value="<?= htmlentities($em) ?>"/></p>
				<p>Headline:
					<input type="text" name="headline" size="80" value="<?= htmlentities($he) ?>"/></p>
				<p>Summary:<br>
					<textarea name="summary" rows="8" cols="80"><?= htmlentities($su)?></textarea></p>
				<p>
				Position: <input type="submit" id="addPos" value="+">
				<div id="position_fields">
				</div>
				</p>
				<p><input type="submit" value="Add"></p>
				<p><input type="submit" value="Cancel"></p>
			</form>
			<script type="text/javascript">
				countPos = 0;
				$(document).ready(function(){
					window.console && console.log('Document ready called');
					$('#addPos').click(function(event){
						event.preventDefault();
						if (countPos >= 9) {
							alert("Maximum of nine position entries exceeded");
							return;
						}
						countPos++;
						window.console && console.log("Adding position "+countPos);
						$('#position_fields').append(
							'<div id="position'+countPos+'"> \
							<p>Year: <input type="text" name="year'+countPos+'" value="" />\
							<input type="button" value="-"\
								onClick="$(\'#position'+countPos+'\').remove();return false;"></p>\
							<textarea name="desc'+countPos+'" rows="8" cols="80"></textarea>\
							</div>');
						});
					});
			</script>
		</div>

	</body>
</html>
