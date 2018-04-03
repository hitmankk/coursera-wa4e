<?php
require_once "pdo.php";
require_once "util.php";
session_start();
if ( ! isset($_SESSION['user_id'])) {
    die('ACCESS DENIED');
}
if ( isset($_POST['cancel']) ) {
    header('Location: index.php');
    return;
}
if ( isset($_POST['save'])) {
    // Data validation should go here (see edit.php)
    $msg = validateProfile();
    if (is_string($msg)){
      $_SESSION['error'] = $msg;
      header("Location: add.php");
      return;
    }
    $msg = validatePos();
    if (is_string($msg)){
      $_SESSION['error'] = $msg;
      header("Location: add.php");
      return;
    }
    $msg = validateEdu();
    if (is_string($msg)){
      $_SESSION['error'] = $msg;
      header("Location: add.php");
      return;
    }
    $sql = "UPDATE Profile SET first_name = :first,
              last_name = :last, user_id = :id, email = :em, headline = :hd, summary = :sm
              WHERE profile_id = :profile_id AND user_id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
      ':first' => $_POST['first_name'],
      ':last' => $_POST['last_name'],
      ':id' => $_SESSION['user_id'],
      ':em' => $_POST['email'],
      ':hd' => $_POST['headline'],
      ':profile_id' => $_GET['profile_id'],
      ':sm' => $_POST['summary'])
    );
    // Clear out the old position entries
    $stmt = $pdo->prepare('DELETE FROM Position
        WHERE profile_id=:pid');
    $stmt->execute(array( ':pid' => $_REQUEST['profile_id']));
    // Insert the position entries
    $rank = 1;
    for($i=1; $i<=9; $i++) {
        if ( ! isset($_POST['year'.$i]) ) continue;
        if ( ! isset($_POST['desc'.$i]) ) continue;
        $year = $_POST['year'.$i];
        $desc = $_POST['desc'.$i];
        $stmt = $pdo->prepare('INSERT INTO Position
            (profile_id, rank, year, description)
        VALUES ( :pid, :rank, :year, :desc)');
        $stmt->execute(array(
            ':pid' => $_REQUEST['profile_id'],
            ':rank' => $rank,
            ':year' => $year,
            ':desc' => $desc)
        );
        $rank++;
    }
    // Clear out the old education entries
    $stmt = $pdo->prepare('DELETE FROM Education
        WHERE profile_id=:pid');
    $stmt->execute(array( ':pid' => $_REQUEST['profile_id']));
    // Insert the position entries
    $rank = 1;
    for($i=1; $i<=9; $i++) {
        if ( ! isset($_POST['edu_year'.$i]) ) continue;
        if ( ! isset($_POST['edu_school'.$i]) ) continue;
        $year = $_POST['edu_year'.$i];
        $school = $_POST['edu_school'.$i];
        $institution_id = false;
        $stmt = $pdo->prepare('SELECT institution_id FROM Institution WHERE name = :name');
        $stmt -> execute(array(':name' => $school));
        $row = $stmt -> fetch(PDO::FETCH_ASSOC);
        if ($row !== false) $institution_id=$row['institution_id'];
        if ($institution_id===false){
          $stmt = $pdo->prepare('INSERT INTO Institution (name) VALUES (:name)');
          $stmt->execute(array(':name' => $school));
          $institution_id = $pdo -> lastInsertId();
        }
        $stmt = $pdo->prepare('INSERT INTO Education
            (profile_id, rank, year, institution_id)
        VALUES ( :pid, :rank, :year, :iid)');
        $stmt->execute(array(
            ':pid' => $_REQUEST['profile_id'],
            ':rank' => $rank,
            ':year' => $year,
            ':iid' => $institution_id)
        );
        $rank++;
    }
    $_SESSION['success'] = 'Record updated';
    header( 'Location: index.php' ) ;
    return;
}
$positions = loadPos($pdo, $_REQUEST['profile_id']);
$schools = loadEdu($pdo, $_REQUEST['profile_id']);
// Guardian should go here (see edit.php)
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
$e = htmlentities($row['email']);
$h = htmlentities($row['headline']);
$s = htmlentities($row['summary']);
$profile_id = $row['profile_id'];
?>
<html>
<head>
<title>Meng Shi profile Database</title>
<?php
require_once "bootstrap.php";
?>
<?php require_once "head.php"; ?>
</head>
<body>
<div class="container">
<h1>Editing Profile for UMSI</h1>
<?php
flashMessages();
?>
<form method="post">
<p>First Name:
<input type="text" name="first_name" size="60" value="<?= $fn ?>"/></p>
<p>Last Name:
<input type="text" name="last_name" size="60" value="<?= $ln ?>"/></p>
<p>Email:
<input type="text" name="email" size="30" value="<?= $e ?>"/></p>
<p>Headline:<br/>
<input type="text" name="headline" size="80" value="<?= $h ?>"/></p>
<p>Summary:<br/>
<textarea name="summary" rows="8" cols="80"><?= $s ?></textarea></p>

<?php
  $countEdu = 0;
  echo('<p>Education: <input type="submit" id="addEdu" value="+">'."\n");
  echo('<div id="edu_fields">'."\n");
  if(count($schools)>0){
    foreach($schools as $school){
      $countEdu++;
      echo('<div id="edu'.$countEdu.'">');
      echo '<p>Year: <input type="text" name="edu_year' . $countEdu . '" value="' . $school['year'].'" />
      <input type="button" value="-" onclick="$(\'#edu'. $countEdu . '\').remove();return false;"></p> \
        <p>School: <input type="text" size="80" name = "edu_school' .$countEdu . '" class="school" value="'.htmlentities($school['name']).'"/>';
      echo "\n</div>\n";
    }
  }
  echo("</div></p>\n");
  $countPos = 0;
  echo('<p>Position: <input type="submit" id="addPos" value="+">'."\n");
  echo('<div id="position_fields">'."\n");
  if(count($positions)>0){
    foreach($positions as $position){
      $countPos++;
      echo('<div class="position" id="position'.$countPos.'">');
      echo '<p>Year: <input type="text" name="year' . $countPos . '" value="' . $position['year'].'" />
      <input type="button" value="-" onclick="$(\'#position'. $countPos . '\').remove();return false;"></p>';
      echo '<textarea name="desc"'.$countPos.'" rows="8" cols="80">'."\n".htmlentities($position['description']);
      echo "\n</textarea>\n</div>\n";
    }
  }
  echo("</div></p>\n");
 ?>

<p>
<input type="submit" value="Save" name="save">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>

<script>
  countPos = <?= $countPos ?>;
  countEdu = <?= $countEdu ?>;
  $(document).ready(function(){
    window.console && console.log('Document ready called');
    $('#addPos').click(function(event){
      event.preventDefault();
      if(countPos >= 9){
        alert("Maximum of nine position entries exceeded");
        return;
      }
      countPos++;
      window.console && console.log('Adding position' + countPos);
      $('#position_fields').append(
        '<div id="position'+countPos+'"> \
        <p>Year: <input type="text" name="year' + countPos + '" value="" /> \
        <input type="button" value="-" \
          onclick="$(\'#position'+countPos+'\').remove();return false;"></p> \
          <textarea name="desc'+countPos+'" rows="8" cols="80"></textarea>\
          </div>');
    });
    $('#addEdu').click(function(event){
      event.preventDefault();
      if(countEdu >= 9){
        alert("Maximum of nine position entries exceeded");
        return;
      }
      countEdu++;
      window.console && console.log('Adding education' + countEdu);
      var source = $("#edu-template").html();
      $('#edu_fields').append(source.replace(/@COUNT@/g,countEdu));
      $('.school').autocomplete({
        source: "school.php"
      });
    });
    $('.school').autocomplete({
      source: "school.php"
    });
  });
</script>
<script id="edu-template" type="text">
  <div id="edu@COUNT@">
  <p>Year: <input type="text" name="edu_year@COUNT@" value="" />
  <input type="button" value="-" onclick="$('#edu@COUNT@').remove(); return false;"><br>
  <p>School: <input type="text" size="80" name="edu_school@COUNT@" class="school" value=""/>
  </p>
  </div>
</script>
</div>
</body>
