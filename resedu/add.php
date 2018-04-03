<?php
session_start();
require_once "pdo.php";
require_once "util.php";
if ( ! isset($_SESSION['user_id'])) {
    die('ACCESS DENIED');
    return;
}
if ( isset($_POST['cancel']) ) {
    header('Location: index.php');
    return;
}
if ( isset($_POST['add']) ) {
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
    $stmt = $pdo->prepare('INSERT INTO Profile
        (first_name, last_name, user_id, email, headline,summary) VALUES ( :first, :last, :id, :em, :hd, :sm)');
    $stmt->execute(array(
        ':first' => $_POST['first_name'],
        ':last' => $_POST['last_name'],
        ':id' => $_SESSION['user_id'],
        ':em' => $_POST['email'],
        ':hd' => $_POST['headline'],
        ':sm' => $_POST['summary'])
    );
    $profile_id = $pdo->lastInsertId();
    $rank = 1;
    for ($i=1; $i<=9; $i++) {
      if (!isset($_POST['year'.$i])) continue;
      if (!isset($_POST['desc'.$i])) continue;
      $year = $_POST['year'.$i];
      $desc = $_POST['desc'.$i];
      $stmt = $pdo->prepare('INSERT INTO Position
          (profile_id, rank, year, description) VALUES ( :pid, :rank, :year, :desc)');
      $stmt->execute(array(
          ':pid' => $profile_id,
          ':rank' => $rank,
          ':year' => $year,
          ':desc' => $desc)
      );
      $rank++;
    }
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
    $_SESSION['success'] = "Profile added";
    header('Location:index.php');
    return;
  }
?>

<html>
<head>
<title>Meng Shi Profile Add</title>
<?php
require_once "bootstrap.php";
?>
<?php require_once "head.php"; ?>
</head>
<body>
  <div class="container">
  <h1>Adding Profile for UMSI</h1>
  <?php
  flashMessages();
  ?>
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
  <textarea name="summary" rows="8" cols="80"></textarea></p>
  <p>
    Education: <input type="submit" id="addEdu" value="+">
    <div id="edu_fields">
    </div>
  </p>
  <p>
    Position: <input type="submit" name="addPos" id="addPos" value="+">
    <div id="position_fields">
    </div>
  </p>
  <p>
  <input type="submit" value="Add" name="add">
  <input type="submit" name="cancel" value="Cancel">
  </p>
  </form>
  <script>
countPos = 0;
countEdu = 0;
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
            <input type="button" value="-" onclick="$(\'#position'+countPos+'\').remove();return false;"><br>\
            <textarea name="desc'+countPos+'" rows="8" cols="80"></textarea>\
            </div>');
    });
    $('#addEdu').click(function(event){
        event.preventDefault();
        if ( countEdu >= 9 ) {
            alert("Maximum of nine education entries exceeded");
            return;
        }
        countEdu++;
        window.console && console.log("Adding education "+countEdu);
        $('#edu_fields').append(
            '<div id="edu'+countEdu+'"> \
            <p>Year: <input type="text" name="edu_year'+countEdu+'" value="" /> \
            <input type="button" value="-" onclick="$(\'#edu'+countEdu+'\').remove();return false;"><br>\
            <p>School: <input type="text" size="80" name="edu_school'+countEdu+'" class="school" value="" />\
            </p></div>'
        );
        $('.school').autocomplete({
            source: "school.php"
        });
    });
});
</script>

  </div>
</body>
</html>
