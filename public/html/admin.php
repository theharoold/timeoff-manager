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
            <?= (isset($_SESSION["create-account-message"])) ? "<p class='message-div " . $_SESSION["create-account-class"] . "'><span class='message-text'>" . $_SESSION['create-account-message'] . "</span></p>" : ""; ?>
            <form class="profile-form" action="<?= getFullServerPath() . "/create-account" ?>" method="POST">
                <div>
                    <label for="email">Email:</label><br>
                    <input type="email" name="email" /><br>
                    <label for="password">Password:</label><br>
                    <input type="password" name="password" /><br>
                    <label for="fname">First Name:</label><br>
                    <input type="text" name="fname" /><br>
                </div>
                <div>
                    <label for="lname">Last Name:</label><br>
                    <input type="text" name="lname" /><br>
                    <label for="job_title">Job Title:</label><br>
                    <input type="text" name="job_title" /><br>
                    <label for="is_manager">Is A Manager:</label><br>
                    <input type="checkbox" name="is_manager" /><br>
                    <button type="submit">Create New Account</button><br>
                </div>

            </form>
        </div>
    </main>

    <?php 
        include("footer.php")
    ?>
</body>
</html>