<?php

require('api/flight/Flight.php');
require_once("config/server.php");
require_once("config/db.php");
require_once("controller/IndexController.php");

session_start();

// Redirects to either the dashboard (if user is logged in)
// or to the login page (if not logged in)
Flight::route("GET /", function() {
    $index = new IndexController();

    $isLoggedIn = isset($_SESSION["isLoggedIn"]);
    $index->index($isLoggedIn);
});

Flight::route("POST /login", function() {
    if (!isset($_POST["email"])) {
        $_SESSION["invalidCredentials"] = true;
        require_once("public/html/login.php");
    }

    if (!isset($_POST["password"])) {
        $_SESSION["invalidCredentials"] = true;
        require_once("public/html/login.php");
    }

    $email = $_POST["email"];
    $password = $_POST["password"];

    $index = new IndexController();

    $index->login($email, $password);
});

Flight::route("GET /logout", function() {
    $index = new IndexController();
    $index->logout();
});

Flight::start();

?>