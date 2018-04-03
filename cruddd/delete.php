<?php
    require_once('pdo.php');
    session_start();

    if(isset($_POST['delete']) && isset($_POST['autos_id'])){
      $sql = 'DELETE FROM autos where autos_id = :zip';
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(':zip' => $_POST['autos_id']));
      $_SESSION['dsuccess'] = 'Record delete';
      header('Location: view.php');
      return;
    }

    $stmt = $pdo->prepare("SELECT make, autos_id FROM autos where autos_id = :xyz");
    $stmt->execute(array(":xyz" =>$_GET['autos_id']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

?>


<p>Confirm: Deleting <?php htmlentities($row['make']) ?></p>
<form method = "post">
    <input type = "hidden" name = "autos_id" value = "<?php $row['autos_id']?>">
    <input type = "submit" value = "Delete" name = "delete">
    <a href = "index.php">Cancel</a>
</form>
