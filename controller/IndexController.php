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
        $hash = hash("sha256", $password);
        
        $db = new DB();
        $conn = $db->createInstance();

        $select_query = "SELECT * FROM employees WHERE email = :email AND password = :password";

        $stmt = $conn->prepare($select_query);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $hash);
        $stmt->execute();

        $results = $stmt->fetch();
        if (count($results) == 0) {
            // Invalid login credentials
            $_SESSION["invalidCredentials"] = true;
            require_once("public/html/login.php");

        } else {
            unset($_SESSION["invalidCredentials"]);
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
}

?>