<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In | STOM | Simple Time-Off Manager</title>
    <?php
    require("public/html/includes.html");
    ?>
</head>
<body>
    <?php 
        include("public/html/header.php")
    ?>

    <main>
        <div class="main-div">
            <h1>
                Log in
            </h1>
            <p>
                Please log in to proceed.
            </p>
            <p>
                <?= isset($_SESSION["invalidMessage"]) ? "<span style='color:red;padding-top: 20px;'>" . $_SESSION["invalidMessage"] . "</span>" : "" ?>
            </p>
            <br>
            <form class="login-form" method="POST" action="<?= getFullServerPath() . "/login"; ?>">
                <label for="email" class="<?= isset($_SESSION["invalidCredentials"]) ? "invalid-credentials-text" : "" ?>">Email:</label><br>
                <input name="email" type="email" id="email" class="<?= isset($_SESSION["invalidCredentials"]) ? "invalid-credentials-input" : "" ?>"/><br>
                <label for="password" class="<?= isset($_SESSION["invalidCredentials"]) ? "invalid-credentials-text" : "" ?>">Password:</label><br>
                <input name="password" type="password" id="password" class="<?= isset($_SESSION["invalidCredentials"]) ? "invalid-credentials-input" : "" ?>"/><br>
                <button class="login-button" type="submit">Log in</button><br>
            </form>
        </div>
    </main>

    <?php 
        include("public/html/footer.php")
    ?>
</body>
</html>