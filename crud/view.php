<?php
    require_once('pdo.php');
    session_start();
    if (!isset($_SESSION['name'])) {
        die('Not logged in');
    }
?>

<!DOCTYPE html>
<html>
<head><title>Meng Shi Autos Database</title></head>
<body>
    <div class='container'>
        <h1>Tracking Autos for <?php echo htmlentities($_SESSION['name']) ?></h1>
        <?php
            if (isset($_SESSION['success'])) {
                echo '<p style="color: green;">Record inserted</p>';
                unset($_SESSION['success']);
            }
            if (isset($_SESSION['dsuccess'])) {
                echo '<p style="color: green;">Successful delete</p>';
                unset($_SESSION['success']);
            }
            if (isset($_SESSION['rsuccess'])) {
                echo '<p style="color: green;">Successful update</p>';
                unset($_SESSION['success']);
            }
        ?>
        <?php
        $selection = $pdo->query('SELECT * FROM autos');
        if($selection->rowCount() == 0){
          echo "<p>no rows found</p>";

        }else {
          echo '<table><thead><tr><th>Make</th><th>Model</th><th>Year</th><th>Mileage</th></tr></thead>';
          echo '<tbody>';

        while ($row = $selection->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr><td>". htmlentities($row['make']);
            echo "</td><td>". htmlentities($row['model']);
            echo "</td><td>". htmlentities($row['year']);
            echo "</td><td>". htmlentities($row['mileage']);
            echo "</td><td>". '<a href = "edit.php?autos_id ='.$row['autos_id'].'">Edit</a> / ';
            echo '<a href = "delete.php?autos_id ='.$row['autos_id'].'">Delete</a>';
            echo "</td><tr>";
          }
        }
        echo '</tbody>';
        echo '</table>';
        ?>
        <a href="add.php">Add New</a> | <a href="logout.php">Logout</a>
    </div>
</body>
</html>
