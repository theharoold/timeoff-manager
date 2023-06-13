<?php

class IndexController {
    public function index($isLoggedIn) {
        if ($isLoggedIn) {
            require("public/html/dashboard.php");
        } else {
            require("public/html/login.php");
        }
    }

    public function login($email, $password) {
        $userDAO = new UserDAO();
        $results = $userDAO->getUserByEmailPassword($email, $password);

        if (!$results) {
            // Invalid login credentials
            $_SESSION["invalidCredentials"] = true;
            $_SESSION["invalidMessage"] = "Invalid login credentials.";
            require_once("public/html/login.php");

        } else {
            unset($_SESSION["invalidMessage"]);
            unset($_SESSION["invalidCredentials"]);
            $_SESSION["user"] = $results;
            if ($results["is_manager"] == 1) {
                $_SESSION["isManager"] = true;
            }
            $_SESSION["isLoggedIn"] = true;
            require_once("public/html/dashboard.php");

        }


    }

    public function logout() {
        session_unset();
        require_once("public/html/login.php");
    }

    public function profile($isLoggedIn) {
        if ($isLoggedIn) {
            require("public/html/profile.php");
        } else {
            require("public/html/login.php");
        }
    }
}

?>