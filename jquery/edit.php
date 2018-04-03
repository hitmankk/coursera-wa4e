<?php
     require_once "pdo.php";
     require_once('bootstrap.php'); ?>
<?php

session_start();
// Demand a GET parameter
if ( ! isset($_SESSION['name']) || strlen($_SESSION['name']) < 1  ) {
    die('ACCESS DENIED');
    return;
}

if ( isset($_POST['cancel'])) {
    header("Location: index.php");
    return;
}
if ( ! isset($_SESSION['profile_id'])) {
  $_SESSION['error'] = 'MISSING PROFILE_ID';
  header("Location: index.php");
  return;
}
	$stmt = $pdo->prepare("SELECT * FROM profile where profile_id = :xyz");
	$stmt->execute(array(":xyz" => $_REQUEST['profile_id']));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	if ( $row === false ) {
	    $_SESSION['error'] = 'Bad value for profile_id';
	    header('Location: index.php');
	    return;
	}

	if( (isset($_POST['first_name']) && strlen($_POST['first_name']) < 1)
       || (isset($_POST['last_name']) && strlen($_POST['last_name']) < 1)
       || (isset($_POST['email']) && strlen($_POST['email']) < 1)
       || (isset($_POST['headline']) && strlen($_POST['headline']) < 1)
       || (isset($_POST['summary']) && strlen($_POST['summary']) < 1)) {
			$_SESSION['error'] = "All fields are required";
		    header("Location:edit.php?profile_id=".$_REQUEST['profile_id']);
		    return;
	}
	elseif (isset($_POST['email']) && strpos($_POST['email'], '@') <1) {
		$_SESSION['error'] = "Email must have an at-sign(@)";
        header("Location:edit.php?profile_id=".$_REQUEST['profile_id']);
        return;
    }
	elseif(isset($_POST['save'])) {
		$stmt = $pdo->prepare('UPDATE profile SET first_name = :firstName, last_name = :lastName, email = :email, headline = :headline, summary = :summary WHERE profile_id = :profileId');
	    $stmt->execute(array(
	        ':firstName' => $_POST['first_name'],
	        ':lastName' => $_POST['last_name'],
	        ':email' => $_POST['email'],
	        ':headline' => $_POST['headline'],
	        ':summary' => $_POST['summary'],
	        ':profileId' => $_REQUEST['profile_id'])
	    );
	    $_SESSION['success'] = "Profile edited";
		header( 'Location: index.php' ) ;
		return;
	}

	if ( isset($_SESSION['name']) ) {
	    echo "<h1>Adding Profile for  ";
	    echo htmlentities($_SESSION['name']);
	    echo "</h1>\n";
	}

	if ( isset($_SESSION['error']) ) {
		echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
		unset($_SESSION['error']);
	}

	$firstName = htmlentities($row['first_name']);
	$lastName = htmlentities($row['last_name']);
	$email = htmlentities($row['email']);
	$headline = htmlentities($row['headline']);
	$summary = htmlentities($row['summary']);
?>

<!DOCTYPE html>
<html>
<head>
<title>Mengshi Profile Edit</title>
</head>
<body>
<div class="container">
	<form method="post">
		<p>First Name:
		<input type="text" name="first_name" id="firstName" size="60" value="<?= $firstName ?>" /></p>
		<p>Last Name:
		<input type="text" name="last_name" id="lastName" size="60"  value="<?= $lastName ?>" /></p>
		<p>Email:
		<input type="text" name="email" id="email" size="30" value="<?= $email ?>" /></p>
		<p>Headline:<br/>
		<input type="text" name="headline" id="headline" size="80" value="<?= $headline ?>" /></p>
		<p>Summary:<br/>
		<textarea name="summary" id="summary" rows="8" cols="80"><?= $summary ?></textarea>
    <p>
      Position:<input type = "submit" id = "addPos"value = "+">
      <div id = "positon_field">
      </div>
    </p>
    <p>
		<p>
		<input type="submit" name="save" value="Save" >
		<input type="submit" name="cancel" value="Cancel">
		</p>
	</form>
  <script>
  countPos = 0;

// http://stackoverflow.com/questions/17650776/add-remove-html-inside-div-using-javascript
$(document).ready(function(){
window.console && console.log('Document ready called');
$('#addPos').click(function(event){
  // http://api.jquery.com/event.preventdefault/
  event.preventDefault();
  if ( countPos >= 9 ) {
      alert("Maximum of nine position entries exceeded");
      return;
  }
  countPos++;
  window.console && console.log("Adding position "+countPos);
  $('#position_fields').append(
      '<div id="position'+countPos+'"> \
      <p>Year: <input type="text" name="year'+countPos+'" value="" /> \
      <input type="button" value="-" \
          onclick="$(\'#position'+countPos+'\').remove();return false;"></p> \
      <textarea name="desc'+countPos+'" rows="8" cols="80"></textarea>\
      </div>');
});
});
</script>
</div>
