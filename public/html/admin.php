<?php 

$isLoggedIn = isset($_SESSION["isLoggedIn"]);
if (!$isLoggedIn) {
    header("Location: " . getFullServerPath() . "/");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | STOM | Simple Time-Off Manager</title>
    <?php
    include("includes.html");
    ?>
</head>
<body>
    <?php 
        include("header.php")
    ?>

    <main>
        <div class="main-div">
            <h1>Create Employee Account</h1>
        </div>
    </main>

    <?php 
        include("footer.php")
    ?>
</body>
</html>