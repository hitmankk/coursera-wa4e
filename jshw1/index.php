<?php
require_once 'pdo.php';
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Meng Shi</title>


</head>

<body>

<div class="container">

    <h1>Meng Shi's Resume Registry</h1>

    <!-- Flashing success message when profile is added successfully -->
    <?php if (isset($_SESSION['success_msg'])): ?>
        <p style="color: green"><?=$_SESSION['success_msg']?></p>
        <?php unset($_SESSION['success_msg']);?>
    <?php endif; ?>

    <?php if (isset($_SESSION['user_id'])): ?>
        <p><a href="logout.php">Logout</a></p>
    <?php else: ?>
        <p><a href="login.php">Please log in</a></p>
    <?php endif; ?>

    <?php
    $query = 'SELECT * FROM profile';
    $stmt = $pdo->query($query);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($result) > 0): ?>
        <table border="1">
            <tr>
                <th>Name</th>
                <th>Headline</th>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <th>Action</th>
                <?php endif; ?>
            </tr>
            <?php foreach ($result as $profile): ?>
                <tr>
                    <td><a href="view.php?profile_id=<?=$profile['profile_id']?>"><?=$profile['first_name'] . ' ' . $profile['last_name']?></a></td>
                    <td><?=$profile['headline']?></td>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <td><a href="edit.php?profile_id=<?=$profile['profile_id']?>">Edit</a> <a href="delete.php?profile_id=<?=$profile['profile_id']?>">Delete</a></td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif;?>

    <?php if (isset($_SESSION['user_id'])): ?>
        <p><a href="add.php">Add New Entry</a></p>
    <?php endif; ?>


</div>
</body>
