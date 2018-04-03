<?php

function flashMessages(){
if (isset($_SESSION['error'])) {
    echo '<p style="color: red;">' . $_SESSION['error'] . '</p>';
    unset($_SESSION['error']);
}
if ( isset($_SESSION['success']) ) {
    echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
    unset($_SESSION['success']);
}
}

function validateProfile(){
  if (   strlen($_POST['first_name']) < 1
      || strlen($_POST['last_name']) < 1
      ||strlen($_POST['email']) < 1
      || strlen($_POST['headline']) < 1
      || strlen($_POST['summary']) < 1) {
          return 'All values are required';
  }
  return true;
}

function validatePos(){
  for($i = 0; $i <=9; $i++){
    if(! isset($_POST['year'.$i])) continue;
    if(! isset($_POST['desc'.$i])) continue;
    $year =   $_POST['year'.$i];
    $desc =   $_POST['desc'.$i];
    if(strlen($_POST['year']) < 1
        || strlen($_POST['desc']) < 1){
          return 'All values are required';
        }
    if( !is_numeric($year)){
      return 'Postion year must be numeric';
    }
  }
  return true;
}

function loadPos($pdo, $profile_id){
  $stmt = $pdo->prepare('SELECT * from Position where profile_id = :prof order by rank');
  $stmt->execute(array(
      ":prof" => $profile_id));
  $position = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $position;
}
