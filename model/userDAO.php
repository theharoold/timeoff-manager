<?php

class UserDao {
    public function getUserByEmailPassword($email, $password) {
        $hash = hash("sha256", $password);
        
        $db = new DB();
        $conn = $db->createInstance();

        $select_query = "SELECT * FROM employees WHERE email = :email AND password = :password";

        $stmt = $conn->prepare($select_query);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $hash);
        $stmt->execute();

        $results = $stmt->fetch();

        return $results;
    }
}

?>