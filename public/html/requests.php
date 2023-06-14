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
    <title>Requests | STOM | Simple Time-Off Manager</title>
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
            <h1> Create A Request </h1>
            <?= (isset($_SESSION["create-request-message"])) ? "<p class='message-div " . $_SESSION["create-request-class"] . "'><span class='message-text'>" . $_SESSION['create-request-message'] . "</span></p>" : ""; ?>
            
            <form class="profile-form" action="<?= getFullServerPath() . "/requests" ?>" method="POST">
                <div>
                    <label for="start_date">Start Date:</label><br>
                    <input class="" type="date" name="start_date" /><br>
                    <label for="end_date">End Date:</label><br>
                    <input class="" type="date" name="end_date" /><br>
                    <label for="description">Description:</label><br>
                    <textarea class="description-textarea" name="description" maxlength="250" rows="4" cols="50"></textarea><br>
                    <button class="" type="submit">Create Request</button><br>
                </div>
            </form>
            <hr>
            <h2> My Requests </h2>
            
        </div>
    </main>

    <?php 
        include("footer.php")
    ?>
</body>
</html>