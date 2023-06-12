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
        var_dump($hash);

    }
}

?>