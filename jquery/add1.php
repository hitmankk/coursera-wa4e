<?php
require_once "conn.php";
session_start();

// echo('<pre>');
// die(print_r($_POST, true));

if (isset($_SESSION['error']))
{
	echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
	unset($_SESSION['error']);
}

if(!$_SESSION['user_id'])
{
	die('ACESS DENIED');
	return;
}

if (isset($_POST['cancel']))
{
	header("Location: index.php");
	return;
}

if(isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary']))
{
	if($_POST['first_name'] == '')
	{
		$_SESSION['error'] = "All Fields are required";
		header("Location: add.php");
		return;
	}

	if($_POST['last_name'] == '')
	{
		$_SESSION['error'] = "All Fields are required";
		header("Location: add.php");
		return;
	}

	if($_POST['email'] == '')
	{
		$_SESSION['error'] = "All Fields are required";
		header("Location: add.php");
		return;
	}

	if($_POST['headline'] == '')
	{
		$_SESSION['error'] = "All Fields are required";
		header("Location: add.php");
		return;
	}

	if($_POST['summary'] == '')
	{
		$_SESSION['error'] = "All Fields are required";
		header("Location: add.php");
		return;
	}

	if(strpos($_POST['email'], '@') === false)
	{
		$_SESSION['error'] = "Email address must contain @";
		header("Location: add.php");
		return;
	}

	$stmt = $pdo->prepare('INSERT INTO Profile
        (user_id, first_name, last_name, email, headline, summary)
        VALUES ( :uid, :fn, :ln, :em, :he, :su)');
    $stmt->execute(array(
        ':uid' => $_SESSION['user_id'],
        ':fn' => $_POST['first_name'],
        ':ln' => $_POST['last_name'],
        ':em' => $_POST['email'],
        ':he' => $_POST['headline'],
        ':su' => $_POST['summary'])
    );

	$profile_id = $pdo->lastInsertId();
	$rank = 1;

	//Year1
	if($_POST['year1'])
	{
	    $year = $_POST['year1'];
	    $desc = $_POST['desc1'];

	    if (!is_numeric($year)) {
	        $_SESSION['error'] = "Position year must be numeric";
	        header("Location: add.php");
	        return;
	    }

	    $stmt = $pdo->prepare('INSERT INTO Position (profile_id, rank, year, description) VALUES ( :pid, :rank, :year, :desc)');
	    $stmt->execute(array(
	        ':pid' => $profile_id,
	        ':rank' => $rank,
	        ':year' => $year,
	        ':desc' => $desc)
	        );
	    $rank++;
	}

	//Year2
	if($_POST['year2'])
	{
	    $year = $_POST['year2'];
	    $desc = $_POST['desc2'];

	    if (!is_numeric($year)) {
	        $_SESSION['error'] = "Position year must be numeric";
	        header("Location: add.php");
	        return;
	    }

	    $stmt = $pdo->prepare('INSERT INTO Position (profile_id, rank, year, description) VALUES ( :pid, :rank, :year, :desc)');
	    $stmt->execute(array(
	        ':pid' => $profile_id,
	        ':rank' => $rank,
	        ':year' => $year,
	        ':desc' => $desc)
	        );
	    $rank++;
	}

	//Year3
	if($_POST['year3'])
	{
	    $year = $_POST['year3'];
	    $desc = $_POST['desc3'];

	    if (!is_numeric($year)) {
	        $_SESSION['error'] = "Position year must be numeric";
	        header("Location: add.php");
	        return;
	    }

	    $stmt = $pdo->prepare('INSERT INTO Position (profile_id, rank, year, description) VALUES ( :pid, :rank, :year, :desc)');
	    $stmt->execute(array(
	        ':pid' => $profile_id,
	        ':rank' => $rank,
	        ':year' => $year,
	        ':desc' => $desc)
	        );
	    $rank++;
	}

	//Year4
	if($_POST['year4'])
	{
	    $year = $_POST['year4'];
	    $desc = $_POST['desc4'];

	    if (!is_numeric($year)) {
	        $_SESSION['error'] = "Position year must be numeric";
	        header("Location: add.php");
	        return;
	    }

	    $stmt = $pdo->prepare('INSERT INTO Position (profile_id, rank, year, description) VALUES ( :pid, :rank, :year, :desc)');
	    $stmt->execute(array(
	        ':pid' => $profile_id,
	        ':rank' => $rank,
	        ':year' => $year,
	        ':desc' => $desc)
	        );
	    $rank++;
	}

	//Year5
	if($_POST['year5'])
	{
	    $year = $_POST['year5'];
	    $desc = $_POST['desc5'];

	    if (!is_numeric($year)) {
	        $_SESSION['error'] = "Position year must be numeric";
	        header("Location: add.php");
	        return;
	    }

	    $stmt = $pdo->prepare('INSERT INTO Position (profile_id, rank, year, description) VALUES ( :pid, :rank, :year, :desc)');
	    $stmt->execute(array(
	        ':pid' => $profile_id,
	        ':rank' => $rank,
	        ':year' => $year,
	        ':desc' => $desc)
	        );
	    $rank++;
	}

	//Year6
	if($_POST['year6'])
	{
	    $year = $_POST['year6'];
	    $desc = $_POST['desc6'];

	    if (!is_numeric($year)) {
	        $_SESSION['error'] = "Position year must be numeric";
	        header("Location: add.php");
	        return;
	    }

	    $stmt = $pdo->prepare('INSERT INTO Position (profile_id, rank, year, description) VALUES ( :pid, :rank, :year, :desc)');
	    $stmt->execute(array(
	        ':pid' => $profile_id,
	        ':rank' => $rank,
	        ':year' => $year,
	        ':desc' => $desc)
	        );
	    $rank++;
	}

	//Year7
	if($_POST['year7'])
	{
	    $year = $_POST['year7'];
	    $desc = $_POST['desc7'];

	    if (!is_numeric($year)) {
	        $_SESSION['error'] = "Position year must be numeric";
	        header("Location: add.php");
	        return;
	    }

	    $stmt = $pdo->prepare('INSERT INTO Position (profile_id, rank, year, description) VALUES ( :pid, :rank, :year, :desc)');
	    $stmt->execute(array(
	        ':pid' => $profile_id,
	        ':rank' => $rank,
	        ':year' => $year,
	        ':desc' => $desc)
	        );
	    $rank++;
	}

	//Year8
	if($_POST['year8'])
	{
	    $year = $_POST['year8'];
	    $desc = $_POST['desc8'];

	    if (!is_numeric($year)) {
	        $_SESSION['error'] = "Position year must be numeric";
	        header("Location: add.php");
	        return;
	    }

	    $stmt = $pdo->prepare('INSERT INTO Position (profile_id, rank, year, description) VALUES ( :pid, :rank, :year, :desc)');
	    $stmt->execute(array(
	        ':pid' => $profile_id,
	        ':rank' => $rank,
	        ':year' => $year,
	        ':desc' => $desc)
	        );
	    $rank++;
	}

	//Year9
	if($_POST['year9'])
	{
	    $year = $_POST['year9'];
	    $desc = $_POST['desc9'];

	    if (!is_numeric($year)) {
	        $_SESSION['error'] = "Position year must be numeric";
	        header("Location: add.php");
	        return;
	    }

	    $stmt = $pdo->prepare('INSERT INTO Position (profile_id, rank, year, description) VALUES ( :pid, :rank, :year, :desc)');
	    $stmt->execute(array(
	        ':pid' => $profile_id,
	        ':rank' => $rank,
	        ':year' => $year,
	        ':desc' => $desc)
	        );
	    $rank++;
	}

	//Year4
	if($_POST['year4'])
	{
	    $year = $_POST['year4'];
	    $desc = $_POST['desc4'];

	    $stmt = $pdo->prepare('INSERT INTO Position (profile_id, rank, year, description) VALUES ( :pid, :rank, :year, :desc)');
	    $stmt->execute(array(
	        ':pid' => $profile_id,
	        ':rank' => $rank,
	        ':year' => $year,
	        ':desc' => $desc)
	        );
	    $rank++;
	}

	$_SESSION['success'] = "Profile added";

	if (isset($_SESSION['success']))
	{
		echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
		header("Location: index.php");
		return;
	}
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Elias Patricio Vieira Lima</title>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

<script
  src="https://code.jquery.com/jquery-3.2.1.js"
  integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
  crossorigin="anonymous">
</script>

</head>
<body>
<div class="container">
<h1>Adding Profile for UMSI</h1>
<form method="post">
<p>First Name:
<input type="text" name="first_name" size="60"/></p>
<p>Last Name:
<input type="text" name="last_name" size="60"/></p>
<p>Email:
<input type="text" name="email" size="30"/></p>
<p>Headline:<br/>
<input type="text" name="headline" size="80"/></p>
<p>Summary:<br/>
<textarea name="summary" rows="8" cols="80"></textarea>
<p>
Position: <input type="submit" id="addPos" value="+">
<div id="position_fields">
</div>
</p>
<input type="submit" value="Add">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>
</div>
</body>
<script>
countPos = 0;
$(document).ready(function(){
    $('#addPos').click(function(event){
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
</html>
