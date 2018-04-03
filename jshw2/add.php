<?php require_once "pdo.php"; ?>
<?php
session_start();
// Demand a GET parameter
if ( ! isset($_SESSION['name']) || strlen($_SESSION['name']) < 1  ) {
    die('ACCESS DENIED');
}

if ( isset($_POST['cancel'])) {
    header("Location: index.php");
    return;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Gagandeep Singh Profile View</title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
<?php
	if( (isset($_POST['first_name']) && strlen($_POST['first_name']) < 1) || (isset($_POST['last_name']) && strlen($_POST['last_name']) < 1) || (isset($_POST['email']) && strlen($_POST['email']) < 1) || (isset($_POST['headline']) && strlen($_POST['headline']) < 1) || (isset($_POST['summary']) && strlen($_POST['summary']) < 1) || (isset($_POST['year']) && strlen($_POST['year']) < 1)) {
			$_SESSION['error'] = "All fields are required";
		    header("Location: add.php");
		    return;
	}
	elseif (isset($_POST['email']) && strpos($_POST['email'], '@') <1) {
		$_SESSION['error'] = "Email must have an at-sign(@)";
        header("Location:add.php");
        return;
    }
	elseif(isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email'])) {
		$stmt = $pdo->prepare('INSERT INTO profile (user_id, first_name, last_name, email, headline, summary) VALUES (:userId, :firstName, :lastName, :email, :headline, :summary)');
	    $stmt->bindValue(':userId', $_POST['user_id']);
	    $stmt->bindValue(':firstName', $_POST['first_name']);
	    $stmt->bindValue(':lastName', $_POST['last_name']);
	    $stmt->bindValue(':email', $_POST['email']);
	    $stmt->bindValue(':headline', $_POST['headline']);
	    $stmt->bindValue(':summary', $_POST['summary']);
	    $result = $stmt->execute();
	    $profile_id = $pdo->lastInsertId();

	    $rank = 1;
	    for($i=1;$i<=9;$i++) {
	    	if(!isset($_POST['year'.$i])) continue;
	    	if(!isset($_POST['desc'.$i])) continue;
	    	$year = $_POST['year'.$i];
	    	$desc = $_POST['desc'.$i];
	    	if ( strlen($year) == 0 || strlen($desc) == 0 ) {
				$_SESSION['error'] = "All fields are required";
			    header("Location:add.php");
		    	return;
			}
			if ( ! is_numeric($year) ) {
				$_SESSION['error'] = "Position year must be numeric";
			    header("Location:add.php");
		    	return;
			}

	    	$stmt = $pdo->prepare('INSERT INTO Position (profile_id, rank, year, description) VALUES (:pid, :rank, :year, :desc)');
	    	$stmt->execute(array(
	    			':pid' => $profile_id,
	    			':rank' => $rank,
	    			':year' => $year,
	    			':desc' => $desc)
	    		);
	    	$rank++;
	    }
	    $_SESSION['success'] = "Profile added";
		header( 'Location: index.php' ) ;
		return;
	}

	if ( isset($_SESSION['name']) ) {
	    echo "<h1>Adding Profile for  ";
	    echo htmlentities($_SESSION['name']);
	    echo "</h1>\n";
	}
	// Note triple not equals and think how badly double
	// not equals would work here...
	if ( isset($_SESSION['error']) ) {
		echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
		unset($_SESSION['error']);
	}
?>
	<form method="post">
		<input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">
		<p>First Name:
		<input type="text" name="first_name" id="firstName" size="60"/></p>
		<p>Last Name:
		<input type="text" name="last_name" id="lastName" size="60"/></p>
		<p>Email:
		<input type="text" name="email" id="email" size="30"/></p>
		<p>Headline:<br/>
		<input type="text" name="headline" id="headline" size="90"/></p>
		<p>Summary:<br/>
		<textarea name="summary" id="summary" rows="8" cols="90"></textarea>
		<p>
		Position: <input type="button" id="addPos" value="+">
		<div id="position_fields">
		</div>
		</p>
		<p>
		<input type="submit" name="Add" value="Add" onclick="return doValidate()">
		<a href="index.php">Cancel</a></p>
		</p>
	</form>
</div>
<script type="text/javascript">
    function doValidate() {
        try {
            var firstName = document.getElementById('firstName').value;
            var lastName = document.getElementById('lastName').value;
            var email = document.getElementById('email').value;
            var headline = document.getElementById('headline').value;
            var summary = document.getElementById('summary').value;
            if ((firstName == null || firstName == "") || (lastName == null || lastName == "") || (email == null || email == "") || (headline == null || headline == "") || (summary == null || summary == "")) {
                alert("All fields are required");
                return false;
            }
            if(email.indexOf('@') < 1) {
                alert("Email must have an at-sign(@)");
                return false;
            }
            return true;
        } catch(e) {
            return false;
        }
        return false;
    }

    countPos = 0;
    $(document).ready(function() {
    	$('#addPos').click(function(event) {
    		event.preventDefault();
    		if(countPos >= 9) {
    			alert("Maximum of nine positon entries exceeded");
    			return;
    		}
    		countPos++;
    		$('#position_fields').append(
    			'<div id="position'+countPos+'"> \
    			<p>Year: <input type="text" name="year' + countPos + '" value="" /> \
    			<input type="button" value="-" \
    				onclick="$(\'#position'+countPos+'\').remove();return false;"></p> \
    			<textarea name="desc'+countPos+'" rows="9" cols="90"></textarea> \
    			</div>');
    	});
    });
</script>
