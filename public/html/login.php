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
            <br>
            <form class="login-form">
                <label for="email">Email:</label><br>
                <input name="email" type="email" id="email"/><br>
                <label for="password">Password:</label><br>
                <input name="password" type="password" id="password"/><br>
                <button class="login-button" type="submit">Log in</button><br>
            </form>
        </div>
    </main>

    <?php 
        include("public/html/footer.php")
    ?>
</body>
</html>