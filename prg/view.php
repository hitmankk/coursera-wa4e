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
        ?>
        <h2>Automobiles</h2>
        <?php
        $selection = $pdo->query('SELECT * FROM autos');
        echo '<ul>';
        while ($row = $selection->fetch(PDO::FETCH_ASSOC)) {
            echo '<li>';
            echo htmlentities($row['year']).' '.htmlentities($row['make']).' / '.htmlentities($row['mileage']);
            echo '</li>';
        }
        echo '</ul>';
        ?>
        <a href="add.php">Add New</a> | <a href="logout.php">Logout</a>
    </div>
</body>
</html>
