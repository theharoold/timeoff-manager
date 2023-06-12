<?php

require('api/flight/Flight.php');
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
        Flight::halt(400, "Email is required");
    }

    if (!isset($_POST["password"])) {
        Flight::halt(400, "Password is required");
    }

    $email = $_POST["email"];
    $password = $_POST["password"];

    $index = new IndexController();

    $index->login($email, $password);
});

Flight::start();

?>