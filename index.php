<?php

require('api/flight/Flight.php');
require_once("config/server.php");
require_once("config/db.php");
require_once("model/userDAO.php");
require_once("controller/IndexController.php");

session_start();

// Redirects to either the dashboard (if user is logged in)
// or to the login page (if not logged in)
Flight::route("GET /", function() {
    $_SESSION["active-page"] = "dashboard";
    $index = new IndexController();

    $isLoggedIn = isset($_SESSION["isLoggedIn"]);
    $index->index($isLoggedIn);
});

Flight::route("POST /login", function() {
    $_SESSION["active-page"] = "dashboard";
    if (!isset($_POST["email"]) || $_POST["email"] == "") {
        $_SESSION["invalidCredentials"] = true;
        $_SESSION["invalidMessage"] = "Both email and password fields are required.";
        require_once("public/html/login.php");
    }

    if (!isset($_POST["password"]) || $_POST["password"] == "") {
        $_SESSION["invalidCredentials"] = true;
        $_SESSION["invalidMessage"] = "Both email and password fields are required.";
        require_once("public/html/login.php");
    }

    $email = $_POST["email"];
    $password = $_POST["password"];

    $index = new IndexController();

    $index->login($email, $password);
});

Flight::route("GET /logout", function() {
    $_SESSION["active-page"] = "dashboard";
    $index = new IndexController();
    $index->logout();
});

Flight::route("GET /profile", function() {
    $_SESSION["active-page"] = "profile";
    $index = new IndexController();
    $isLoggedIn = isset($_SESSION["isLoggedIn"]);
    $index->profile($isLoggedIn);
});

Flight::route("POST /profile", function() {
    $_SESSION["active-page"] = "profile";
    $index = new IndexController();
    $formData = $_POST;
    $isLoggedIn = isset($_SESSION["isLoggedIn"]);
    $index->updateProfile($isLoggedIn, $formData);
});

Flight::route("POST /change-password", function() {
    $index = new IndexController();
    $isLoggedIn = isset($_SESSION["isLoggedIn"]);
    if (!$isLoggedIn) {
        require_once("public/html/login.php");
        exit();
    }

    $old_password = $_POST["old_password"];
    $new_password = $_POST["new_password"];
    $repeat_new_password = $_POST["repeat_new_password"];

    if ($_POST["old_password"] == "" || $_POST["new_password"] == "" || $_POST["repeat_new_password"] == "") {
        $_SESSION["change-password-message"] = "All fields are required.";
        $_SESSION["change-password-class"] = "error-message";
        require_once("public/html/profile.php");
        exit();
    }

    if ($new_password != $repeat_new_password) {
        $_SESSION["change-password-message"] = "Passwords do not match.";
        $_SESSION["change-password-class"] = "error-message";
        require_once("public/html/profile.php");
        exit();
    }

    if (hash("sha256", $old_password) != $_SESSION["user"]["password"]) {
        $_SESSION["change-password-message"] = "Invalid password.";
        $_SESSION["change-password-class"] = "error-message";
        require_once("public/html/profile.php");
        exit();
    }

    $index->changePassword($_SESSION["user"]["id"], $new_password);
});

Flight::start();

?>