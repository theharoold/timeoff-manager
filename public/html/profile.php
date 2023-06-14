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
    <title>Profile | STOM | Simple Time-Off Manager</title>
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
            <h1> User Profile </h1>
            <?= (isset($_SESSION["update-employee-message"])) ? "<p class='message-div " . $_SESSION["update-message-class"] . "'><span class='message-text'>" . $_SESSION['update-employee-message'] . "</span></p>" : ""; ?>
            
            <form class="profile-form" action="<?= getFullServerPath() . "/profile" ?>" method="POST">
                <div>
                    <label>Email:</label><br>
                    <input class="" type="email" name="email" placeholder="<?= $_SESSION["user"]["email"] ?>"/><br>
                    <label>First Name:</label><br>
                    <input class="" type="text" name="fname" placeholder="<?= $_SESSION["user"]["fname"] ?>"/><br>
                    <label>Last Name:</label><br>
                    <input class="" type="text" name="lname" placeholder="<?= $_SESSION["user"]["lname"] ?>"/><br>
                    <label>Phone:</label><br>
                    <input class="" type="text" name="phone" placeholder="<?= $_SESSION["user"]["phone"] ?>"/><br>
                    <label>Job Title:</label><br>
                    <input class="" type="text" name="job_title" placeholder="<?= $_SESSION["user"]["job_title"] ?>"/><br>
                </div>
                <div>
                    <label>Address:</label><br>
                    <input class="" type="text" name="address" placeholder="<?= $_SESSION["user_address"]["address"] ?>"/><br>
                    <label>City:</label><br>
                    <input class="" type="text" name="zip_code" placeholder="<?= $_SESSION["user_address"]["zip_code"] ?>"/><br>
                    <label>Zip Code:</label><br>
                    <input class="" type="text" name="city" placeholder="<?= $_SESSION["user_address"]["city"] ?>"/><br>
                    <label>Country:</label><br>
                    <input class="" type="text" name="country" placeholder="<?= $_SESSION["user_address"]["country"] ?>"/><br>
                    <label>Theme:</label><br>
                    <div class="theme-div">
                        <p class="theme-control-left" onclick="changeTheme('dark');">Dark</p>
                        <p class="theme-control-right" onclick="changeTheme('light');">Light</p>
                    </div>
                    <button class="" type="submit">Save Changes</button><br>
                </div>
            </form>
            <hr>
            <h2> Change Password </h2>
            <?= (isset($_SESSION["change-password-message"])) ? "<p class='message-div " . $_SESSION["change-password-class"] . "'><span class='message-text'>" . $_SESSION['change-password-message'] . "</span></p>" : ""; ?>
            <form class="profile-form" action="<?= getFullServerPath() . "/change-password" ?>" method="POST">
                <div>
                    <label>Old Password:</label>
                    <input type="password" name="old_password" />
                    <label>New Password:</label>
                    <input type="password" name="new_password" />
                    <label>Repeat New Password:</label>
                    <input type="password" name="repeat_new_password" />
                    <button type="submit">Change Password</button>
                </div>

            </form>
        </div>
    </main>

    <?php 
        include("footer.php")
    ?>
</body>
</html>