<?php
	session_start();
	require_once('conn.php');

	//if(!isset($_GET['name'])){ die("Name parameter missing");}
	//$names=$_GET['name'];
	//$errormsg=false;
	//$list=false;
	if ( ! isset($_SESSION['name']) ) {
    die("ACCESS DENIED");
}
	if (!isset($_GET['profile_id'])) {
    die("ACCESS DENIED");
}
	if(isset($_POST['cancel'])){
	 header("Location: index.php");
    return;
	}
	if(isset($_POST['save'])){
	if(strlen($_POST['first_name'])<1 || strlen($_POST['last_name'])<1|| strlen($_POST['email'])<1 || strlen($_POST['headline'])<1 || strlen($_POST['summary'])<1){
		$_SESSION['error']= "All fields are required" ;
		header('Location:edit.php');
		return;
	}
	else if (strpos($_POST['email'],"@")===FALSE) {
		$_SESSION['error'] = "Email address must contain @";
			header("Location: edit.php");
			return;
		}

	else{

		  $stmt = $pdo->prepare('UPDATE profile set email=:mk, first_name=:md, last_name=:yr, headline=:mi, summary=:sm, user_id=:uid where profile_id=:pf');
		 $stmt->execute(array(
        ':mk' => $_POST['email'],
		':md' => $_POST['first_name'],
        ':yr' => $_POST['last_name'],
        ':mi' => $_POST['headline'],
		':sm' => $_POST['summary'],
		':pf' => $_GET['profile_id'],
		':uid' => $_SESSION['user_id']));
	$_SESSION['success'] = "Profile updated";
	header("Location: index.php");
	return;
	//$_SESSION['error']='Record inserted';

	}
	}
?>
<script>
function doValidate() {
console.log('Validating...');
try {
var fn = document.getElementById('fn').value;
console.log('Validating...');
var ln = document.getElementById('ln').value;
console.log('Validating...');
var ema = document.getElementById('ema').value;
var hd = document.getElementById('hd').value;
var sum = document.getElementById('sum').value;
var n = ema.indexOf("@");

if(fn==null || fn=="" || ln==null || ln=="" || ema==null || ema=="" || hd==null || hd=="" || sum==null || sum==""){
	alert("All fields are required");
	return false;
}
if (n==-1) {
alert("Email address must contain @");
return false;
}

return true;
} catch(e) {
return false;
}
return false;
}
</script>
<html>
<head>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

<script
  src="https://code.jquery.com/jquery-3.2.1.js"
  integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
  crossorigin="anonymous">
</script>

<title>Elias Patricio Vieira Lima</title>
</head>
<body>
<div class="container">
<h1>Tracking Autos for <?php echo $_SESSION['name'].'</h1>';
if(isset($_SESSION['error'])){
echo '<p style="color:red;">'.htmlentities($_SESSION['error'])."</p>";
unset($_SESSION['error']);
}
$stmt = $pdo->prepare("SELECT * FROM profile where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for profile_id';
    header( 'Location: index.php' ) ;
    return;
}
$fn = htmlentities($row['first_name']);
$ln = htmlentities($row['last_name']);
$em = htmlentities($row['email']);
$hd = htmlentities($row['headline']);
$sam = htmlentities($row['summary']);
$profile_id = $row['profile_id'];

$stmt = $pdo->prepare("SELECT * FROM position where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row2 = $stmt->fetchAll(PDO::FETCH_ASSOC);

$qtd = count($row2);

for($i=0; $i < count($row2); $i++)
{
    $html .= '<div id="position'.$row2[$i]['rank'];
    $html .= '<p>Year: <input type="text" name="year'.$row2[$i]['rank'].'"value="'.$row2[$i]['year'].'" />';
    $html .= '<input type="button" value="-"';
    $html .= 'onclick="$(\'#position'.$row2[$i]['rank'].'\').remove();return false;"></p>';
    $html .= '<textarea name="desc'.$row2[$i]['rank'].'" rows="8" cols="80">'.$row2[$i]['description'].'</textarea>';
    $html .= '</div>';

}

// echo('<pre>');
// die(print_r($row2, true));

?>
<form method="post">
<p>First Name:
<input type="text" name="first_name" id='fn' value=<?=$fn?> size="60"/></p>
<p>Last Name:
<input type="text" name="last_name" id='ln' value=<?=$ln?> size="60"/></p>
<p>Email:
<input type="text" name="email" value="<?=$em?>" size="30" id='ema'/></p>
<p>Headline:<br/>
<input type="text" name="headline" value="<?=$hd?>" size="80" id='hd'/></p>
<p>Summary:<br/>
<textarea name="summary" rows="8" cols="80"  id='sum'> <?=$sam?> </textarea>
<p>
Position: <input type="submit" id="addPos" value="+">
<div id="position_fields">
<?php echo($html);?>
</div>
<input type="submit" name='save' onclick="return doValidate();" value="Save">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>
<p>

For a password hint, view source and find a password hint
in the HTML comments.

</p>
</div>
</body>
