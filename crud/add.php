<?php
    require_once('pdo.php');
    session_start();

    if (isset($_POST['cancel'])) {
        header('Location: view.php');
        return;
    }

    if (!isset($_SESSION['name'])) {
        die('Access denied');
    }

    if (isset($_POST['make'])&& isset($_POST['model']) && isset($_POST['year']) && isset($_POST['mileage'])) {
        if (strlen($_POST['make']) < 1 || strlen($_POST['model']) < 1) {
            $_SESSION['error'] = 'Make is required';
        } else if (!is_numeric($_POST['mileage']) || !is_numeric($_POST['year'])) {
            $_SESSION['error'] = 'Mileage and year must be numeric';
        } else {
            $stmt = $pdo->prepare('INSERT INTO autos (make, model, year, mileage)
                VALUES (:make,:model, :year, :mileage)');
            $stmt->execute(array(
                ':make' => $_POST['make'],
                ':model' => $_POST['model'],
                ':year' => $_POST['year'],
                ':mileage' => $_POST['mileage'])
            );
            $_SESSION['success'] = true;
        }
        if (isset($_SESSION['success'])) {
            header('Location: view.php');
        } else {
            header('Location: add.php');
        }
        return;
    }
?>

<!DOCTYPE html>
<html>
<head><title>Meng Shi Autos Database</title></head>
<body>
    <div class='container'>
        <h1>Tracking Autos for <?php echo htmlentities($_SESSION['name']) ?></h1>
        <?php
            if (isset($_SESSION['error'])) {
                echo '<p style="color: red;">' . $_SESSION['error'] . '</p>';
                unset($_SESSION['error']);
            }
        ?>
        <form method="POST">
            <label for="edt_make"><span style="font-weight:normal;">Make:</span></label>
            <input type="text" name="make" id="edt_make"><br>
            <label for="edt_model"><span style="font-weight:normal;">Model:</span></label>
            <input type="text" name="model" id="edt_model"><br>
            <label for="edt_year"><span style="font-weight:normal;">Year:</span></label>
            <input type="text" name="year" id="edt_year"><br>
            <label for="edt_mileage"><span style="font-weight:normal;">Mileage:</span></label>
            <input type="text" name="mileage" id="edt_mileage"><br>
            <input type="submit" value="Add">
            <input type="submit" name="cancel" value="Cancel">
        </form>
    </div>
</body>
</html>
