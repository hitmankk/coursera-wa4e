<?php
    require_once('pdo.php');
    require_once('bootstrap.php');
    session_start();


    if (!isset($_SESSION['user_id'])) {
        die('Access denied');
        return;
    }

    if (   isset($_POST['first_name'])
        && isset($_POST['last_name'])
        && isset($_POST['email'])
        && isset($_POST['headline'])
        && isset($_POST['summary'])) {
        if (   strlen($_POST['first_name']) < 1
            || strlen($_POST['last_name']) < 1
            ||strlen($_POST['email']) < 1
            || strlen($_POST['headline']) < 1
            || strlen($_POST['summary']) < 1) {
                $_SESSION['error'] = 'All values are required';

        }  else if (strchr(($_POST['email']), '@') === false) {
            $_SESSION['error'] = 'Email must have an at-sign (@)';

        }else{
          for($i = 0; $i <=9; $i++){
            if(! isset($_POST['year'.$i])) continue;
            if(! isset($_POST['desc'.$i])) continue;
            $year =   $_POST['year'.$i];
            $desc =   $_POST['desc'.$i];
            if(strlen($_POST['year']) < 1
                || strlen($_POST['desc']) < 1){
                  $_SESSION['error'] = 'All values are required';
                }
            if( !is_numeric($year)){
              $_SESSION['error'] = 'Postion year must be numeric';
            }
        }
        if(!isset($_SESSION['error'])){
          $stmt = $pdo->prepare('INSERT INTO profile
              (user_id, first_name, last_name, email, headline, summary)
              VALUES ( :uid, :fn, :l, :em, :he, :su)');
          $stmt->execute(array(
              ':uid' => $_SESSION['user_id'],
              ':fn' => $_POST['first_name'],
              ':l' => $_POST['last_name'],
              ':em' => $_POST['email'],
              ':he' => $_POST['headline'],
              ':su' => $_POST['summary'])
          );
          $rank = 1;
          for($i = 0; $i <=9; $i++){
            if(! isset($_POST['year'.$i])) continue;
            if(! isset($_POST['desc'.$i])) continue;
            $year =   $_POST['year'.$i];
            $desc =   $_POST['desc'.$i];
            $stmt = $pdo->prepare('INSERT INTO Position
              (profile_id, rank, year, description)
              VALUES (:pid, :rank, :year, :desc)');
              $stmt->execute(array(
                  ':pid' => $_SESSION['profile_id'],
                  ':rank' => $_POST['rank'],
                  ':year' => $_POST['year'],
                  ':desc' => $_POST['desc'])
              );
              $rank++;
            }
          $_SESSION['success'] = "Profile added";
        }
        if (isset($_SESSION['success'])) {
            header('Location: index.php');
        } else {
            header('Location: add.php');
        }
        return;
    }
  }
?>

<!DOCTYPE html>
<html>
<head><title>Meng Shi 'Severance Resuma add</title></head>
<body>
    <div class='container'>
        <h1>Add Resume for <?php echo htmlentities($_SESSION['name']) ?></h1>
        <?php
            if (isset($_SESSION['error'])) {
                echo '<p style="color: red;">' . $_SESSION['error'] . '</p>';
                unset($_SESSION['error']);
            }
        ?>
        <form method="POST">
              <p>First Name:<br/>
              <input type="text" name="first_name" size="60"/></p>
              <p>Last Name:<br/>
              <input type="text" name="last_name" size="60"/></p>
              <p>Email:<br/>
              <input type="text" name="email" size="30"/></p>
              <p>Headline:<br/>
              <input type="text" name="headline" size="80"/></p>
              <p>Summary:<br/>
              <textarea name="summary" rows="8" cols="80"></textarea>
              <p>
                Position:<input type = "submit" id = "addPos"value = "+">
                <div id = "positon_field">
                </div>
              </p>
              <p>
                <input type="submit" value="Add">
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
</body>
</html>
