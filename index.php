<?php

require('api/flight/Flight.php');
require_once("config/server.php");
require_once("config/db.php");
require_once("model/userDAO.php");
require_once("model/requestDAO.php");
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

Flight::route("GET /admin", function() {
    if (!isset($_SESSION["isLoggedIn"])) {
        require_once("public/html/login.php");
        exit();
    }
    if ($_SESSION["user"]["is_manager"] != 1) {
        require_once("public/html/login.php");
        exit();
    }

    unset($_SESSION["create-account-message"]);

    $_SESSION["active-page"] = "admin";
    require_once("public/html/admin.php");
});

Flight::route("POST /create-account", function() {
    if (!isset($_SESSION["isLoggedIn"])) {
        require_once("public/html/login.php");
        exit();
    }
    if ($_SESSION["user"]["is_manager"] != 1) {
        require_once("public/html/dashboard.php");
        exit();
    }

    $_SESSION["active-page"] = "admin";

    if ($_POST["email"] == "" || $_POST["password"] == "" || $_POST["fname"] == "" || $_POST["lname"] == "" || $_POST["job_title"] == "") {
        $_SESSION["create-account-message"] = "All fields are required.";
        $_SESSION["create-account-class"] = "error-message";
        require_once("public/html/admin.php");
        exit();
    }

    
    $formData = array();
    $formData["email"] = $_POST["email"];
    $formData["password"] = hash("sha256", $_POST["password"]);
    $formData["fname"] = $_POST["fname"];
    $formData["lname"] = $_POST["lname"];
    $formData["job_title"] = $_POST["job_title"];
    $formData["is_manager"] = isset($_POST["is_manager"]) ? 1 : 0;
    $formData["plaintext"] = $_POST["password"];

    $index = new IndexController();
    $index->createAccount($formData);
});

Flight::route("GET /requests", function() {
    unset($_SESSION["create-request-message"]);
    $isLoggedIn = isset($_SESSION["isLoggedIn"]);
    $index = new IndexController();

    $index->requests($isLoggedIn);
});

Flight::route("POST /requests", function() {
    $isLoggedIn = isset($_SESSION["isLoggedIn"]);

    if (!$isLoggedIn) {
        require_once("public/html/login.php");
        exit();
    }

    if ($_POST["start_date"] == "" || $_POST["end_date"] == "") {
        $_SESSION["create-request-message"] = "Start and end date fields are required.";
        $_SESSION["create-request-class"] = "error-message";
        require_once("public/html/requests.php");
        exit();
    }

    $formData = array();
    $formData["start_date"] = $_POST["start_date"];
    $formData["end_date"] = $_POST["end_date"];
    $formData["description"] = ($_POST["description"] == "") ? "/" : $_POST["description"];

    $index = new IndexController();
    $index->createRequest($formData);

});

Flight::start();

?>