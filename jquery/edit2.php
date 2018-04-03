<?php
	require_once "pdo.php";
	require_once "util.php";
	session_start();

	if ( ! isset($_SESSION['user_id'])){
		die("ACCESS DENIED");
		return;
	}

	if( isset($_POST['Cancel'])){
			header('Location: index.php');
			return;
		}

	if ( ! isset($_REQUEST['profile_id']) ) {
	  $_SESSION['error'] = "Missing profile_id";
	  header('Location: index.php');
	  return;
	}

	$stmt = $pdo->prepare('SELECT * FROM Profile
		WHERE profile_id = :prof AND user_id = :uid');
	$stmt->execute(array(':prof' => $_REQUEST['profile_id'],
		':uid' => $_SESSION['user_id']));
	$profile = $stmt->fetch(PDO::FETCH_ASSOC);
	if ($profile === false){
		$_SESSION['error'] = "Could not load profile";
		header('Location: index.php');
		return;
	}

	if ( isset($_POST['first_name']) && isset($_POST['last_name'])
     && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])){

		$msg = validateProfile();
		if (is_string($msg)) {
			# code...
			$_SESSION['error'] = $msg;
			header("Location: edit.php?profile_id=".$_REQUEST["profile_id"]);
			return;
		}

		//validate position entries if present
		$msg = validatePos();
		if( is_string($msg)){
			$_SESSION['error'] = $msg;
			header("Location: edit.php?profile_id=".$_REQUEST["profile_id"]);
			return;
		}

		$stmt = $pdo->prepare('UPDATE Profile SET
			first_name = :fn, last_name = :ln,
			email = :em, headline = :he, summary = :su
	    	WHERE profile_id = :pid AND user_id=:uid');
		$stmt->execute(array(
			':pid' => $_REQUEST['profile_id'],
			':uid' => $_SESSION['user_id'],
	        ':fn' => $_POST['first_name'],
	        ':ln' => $_POST['last_name'],
	    	':em' => $_POST['email'],
	    	':he' => $_POST['headline'],
	    	':su' => $_POST['summary'])
			);

		$stmt = $pdo->prepare('DELETE FROM position
				WHERE profile_id=:pid');
		$stmt ->execute(array(':pid' => $_REQUEST['profile_id']));

		//Insert position entries
		$rank = 1;
		for ($i=1; $i < 9; $i++) {
			# code...
			if (! isset($_POST['year'.$i])) continue;
			if (! isset($_POST['desc'.$i])) continue;
			$year = $_POST['year'.$i];
			$desc = $_POST['desc'.$i];

			$stmt = $pdo->prepare('INSERT INTO Position
				(profile_id, rank, year, description)
				VALUES (:pid, :rank, :year, :desc)');
			$stmt->execute(array(
				':pid' => $_REQUEST['profile_id'],
				':rank' => $rank,
				':year' => $year,
				':desc' => $desc)
			);
			$rank++;

			$_SESSION['success'] = "Profile updated";
			header("Location: index.php");
			return;
		}

		if (isset($_POST['Value'])) {
			# code...
			$_SESSION['success'] = "Profile updated";
			header("Location: index.php");
			return;
		}
		#$_SESSION['success'] = "Profile updated";
		#header("Location: index.php");
		#return;
	}

	$positions = loadPos($pdo, $_REQUEST['profile_id']);



?>
<!DOCTYPE html>
<html>
	<head>
		<title>Edgar Solis Edit Page</title>
		<?php require_once "bootstrap.php"; ?>
		<?php require_once "head.php"; ?>
	</head>
	<body>
		<div class="container">
			<?php
					echo '<h1>Editing profile for ';
					echo htmlentities($_SESSION['name']);
					echo '</h1>';
			?>

			<?php
				flashMessages();
			?>

			<form method="post" action="edit.php">
			<input type="hidden" name="profile_id" value="<?= htmlentities($_GET['profile_id']); ?>"/>
				<p>First Name:
					<input type="text" name="first_name" size="60" value="<?= htmlentities($profile['first_name']); ?>"/></p>
				<p>Last Name:
					<input type="text" name="last_name" size="60" value="<?= htmlentities($profile['last_name']); ?>"/></p>
				<p>Email:
					<input type="text" name="email" size="30" value="<?= htmlentities($profile['email']); ?>"/></p>
				<p>Headline:
					<input type="text" name="headline" size="80" value="<?= htmlentities($profile['headline']); ?>"/></p>
				<p>Summary:<br>
					<textarea name="summary" rows="8" cols="80" ><?= htmlentities($profile['summary']);?></textarea></p>
				<?php
					$pos = 0;
					echo('<p>Position: <input type="submit" id="addPos" value="+">'."\n");
					echo('<div id="position_fields">'."\n");
					foreach ($positions as $position) {
					 	# code...
					 	$pos++;
					 	echo('<div id="position'.$pos.'">'."\n");
					 	echo('<p>Year: <input type="text" name="year'.$pos.'"');
					 	echo(' value="'.$position['year'].'" />'."\n");
					 	echo('<input type="button" value="-" ');
					 	echo('onClick="$(\'#position'.$pos.'\).remove();return false;">'."\n");
					 	echo("</p>\n");
					 	echo('<textarea name="desc"'.$pos.'" rows="8" cols="80">'."\n");
					 	echo(htmlentities($position['description'])."\n");
					 	echo("\n</textarea>\n</div>\n");
					 }
					 echo("</div></p>\n");
				?>
				<p><input type="submit" value="Save"></p>
				<p><input type="submit" value="Cancel"></p>
			</form>
			<script src="js/jquery.1.10.2.js"></script>
			<script src="js/jquery-ui-.1.11.4.js"></script>
			<script type="text/javascript">
				countPos = <?= $pos ?>;
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
