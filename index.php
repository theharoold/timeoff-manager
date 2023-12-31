<?php

require('api/flight/Flight.php');
require_once("config/server.php");
require_once("config/db.php");
require_once("model/contactDAO.php");
require_once("model/surveyDAO.php");
require_once("model/userDAO.php");
require_once("model/requestDAO.php");
require_once("model/eventDAO.php");
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
    unset($_SESSION["create-survey-message"]);

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
    unset($_SESSION["update-request-message"]);
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

    $start_date = $formData["start_date"];
    $end_date = $formData["end_date"];
    $start_datetime = DateTime::createFromFormat('Y-m-d', $start_date);
    $end_datetime = DateTime::createFromFormat('Y-m-d', $end_date);
    $current_date = new DateTime();

    if ($start_datetime > $end_datetime || $start_datetime < $current_date) {
        $_SESSION["create-request-message"] = "Invalid dates. Start date cannot be before today. End date cannot be before start date.";
        $_SESSION["create-request-class"] = "error-message";
        require_once("public/html/requests.php");
        exit();
    }

    $index = new IndexController();
    $index->createRequest($formData);

});

Flight::route("GET /update-request", function() {
    if (!isset($_GET["decision"]) || !isset($_GET["id"])) {
        require_once("public/html/requests.php");
        exit();
    }

    $decision = $_GET["decision"];
    $id = $_GET["id"];

    $index = new IndexController();
    $index->updateRequest($id, $decision);
});

Flight::route("POST /create-event", function() {
    $isLoggedIn = isset($_SESSION["isLoggedIn"]);

    if (!$isLoggedIn) {
        require_once("public/html/login.php");
        exit();
    }

    if ($_POST["event_date"] == "" || $_POST["name"] == "") {
        $_SESSION["create-event-message"] = "Event date and name are required.";
        $_SESSION["create-event-class"] = "error-message";
        require_once("public/html/admin.php");
        exit();
    }

    $formData = array();
    $formData["event_date"] = $_POST["event_date"];
    $formData["name"] = $_POST["name"];
    $formData["description"] = ($_POST["description"] == "") ? "/" : $_POST["description"];
    $formData["is_workday"] = (isset($_POST["is_workday"])) ? 1 : 0;

    $index = new IndexController();
    $index->createEvent($formData);
});

Flight::route("GET /about-us", function() {
    $isLoggedIn = isset($_SESSION["isLoggedIn"]);
    if (!$isLoggedIn) {
        require_once("public/html/login.php");
        exit();
    }

    unset($_SESSION["create-contact-us-message"]);

    $_SESSION["active-page"] = "about-us";

    require_once("public/html/about-us.php");
});

Flight::route("POST /contact-us", function() {
    $isLoggedIn = isset($_SESSION["isLoggedIn"]);
    if (!$isLoggedIn) {
        require_once("public/html/login.php");
        exit();
    }

    $employee_id = $_SESSION["user"]["id"];
    $message = $_POST["message"];

    $index = new IndexController();
    $index->newContactUs($employee_id, $message);
});

Flight::route("POST /create-survey", function() {
    $isLoggedIn = isset($_SESSION["isLoggedIn"]);
    if (!$isLoggedIn) {
        require_once("public/html/login.php");
        exit();
    }

    $question = $_POST["question"];
    $answers = $_POST["answers"];

    $index = new IndexController();
    $index->createSurvey($question, $answers);
});

Flight::route("GET /answer-survey", function() {
    $isLoggedIn = isset($_SESSION["isLoggedIn"]);
    if (!$isLoggedIn) {
        require_once("public/html/login.php");
        exit();
    }

    if (!isset($_GET["answer"]) || !isset($_GET["id"])) {
        $_SESSION["create-survey-response-message"] = "Answer or survey not set.";
        $_SESSION["create-survey-response-class"] = "error-message";
        require_once("public/html/dashboard.php");
        exit();
    }

    $index = new IndexController();
    $index->createSurveyResponse($_GET["answer"], $_GET["id"]);
});

Flight::route("GET /search-responses", function() {
    if (!isset($_GET["name"])) {
        echo json_encode([]);
        exit();
    }
    
    $name = strtoupper(trim($_GET["name"]));
    if ($name == "") {
        echo json_encode([]);
        exit();
    }

    $surveyDAO = new SurveyDAO();
    $results = $surveyDAO->searchResponses($name);
    if ($results == false) {
        $results = [];
    }
    echo json_encode($results);
});

Flight::start();

?>