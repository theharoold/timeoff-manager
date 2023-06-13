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

Flight::start();

?>