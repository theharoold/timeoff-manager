<?php

class UserDao {
    public $employeeFieldList = ["id", "email", "password", "fname", "lname", "phone", "is_manager", "job_title", "is_reset_password"];
    public $addressFieldList = ["employee_id", "address", "zip_code", "city", "country"];

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

    public function getUserById($id) {
        $db = new DB();
        $conn = $db->createInstance();

        $select_query = "SELECT * FROM employees WHERE id = :id";

        $stmt = $conn->prepare($select_query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        $results = $stmt->fetch();

        return $results;
    }

    public function getAddressById($id) {
        $db = new DB();
        $conn = $db->createInstance();

        $select_query = "SELECT * FROM addresses WHERE employee_id = :id";

        $stmt = $conn->prepare($select_query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        $results = $stmt->fetch();

        return $results;
    }

    public function updateEmployee($updateFields, $formData) {
        $db = new DB();
        $conn = $db->createInstance();

        // Build the update statement
        $updateQuery = "UPDATE employees SET " . implode(", ", $updateFields) . " WHERE id = :id";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bindParam(':id', $_SESSION["user"]["id"]);
        foreach ($formData as $field => $value) {
            if ($value !== '') {
                $stmt->bindParam(":$field", $value);
            }
        }

        // Execute the update statement
        $stmt->execute();

        return $stmt->rowCount();
    }

    public function updateAddress($updateFields, $formData) {
        $db = new DB();
        $conn = $db->createInstance();

        // Build the update statement
        $updateQuery = "UPDATE addresses SET " . implode(", ", $updateFields) . " WHERE employee_id = :id";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bindParam(':id', $_SESSION["user"]["id"]);
        foreach ($formData as $field => $value) {
            if ($value !== '') {
                $stmt->bindParam(":$field", $value);
            }
        }

        // Execute the update statement
        $stmt->execute();

        return $stmt->rowCount();
    }

    public function contains($fieldName, $fieldList) {
        for ($i = 0; $i < count($fieldList); $i++) {
            if ($fieldName == $fieldList[$i]) {
                return true;
            }
        }
        return false;
    }

    public function changePassword($id, $newPassword) {
        $hash = hash("sha256", $newPassword);
        $update_query = "UPDATE employees SET password = :password WHERE id = :id";

        $db = new DB();
        $conn = $db->createInstance();

        $stmt = $conn->prepare($update_query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":password", $hash);

        $stmt->execute();

        return $stmt->rowCount();
        
    }

    public function createAccount($accountData) {
        try {
            $insert_query = "INSERT INTO employees (email, password, fname, lname, job_title, is_manager, create_time) VALUES (:email, :password, :fname, :lname, :job_title, :is_manager, NOW())";
            $insert_address_query = "INSERT INTO addresses (employee_id) VALUES (:id)";

            $db = new DB();
            $conn = $db->createInstance();

            $stmt = $conn->prepare($insert_query);
            $stmt->bindParam(":email", $accountData["email"]);
            $stmt->bindParam(":password", $accountData["password"]);
            $stmt->bindParam(":fname", $accountData["fname"]);
            $stmt->bindParam(":lname", $accountData["lname"]);
            $stmt->bindParam(":job_title", $accountData["job_title"]);
            $stmt->bindParam(":is_manager", $accountData["is_manager"]);

            $stmt->execute();
            
            $lastId = $conn->lastInsertId();
            $addr_stmt = $conn->prepare($insert_address_query);
            $addr_stmt->bindParam(":id", $lastId);
            $addr_stmt->execute();

            return $stmt->rowCount();
        } catch (Exception $e) {
            return 0;
        }
    }

    function checkPasswordStrength($input) {
        $uppercaseRegex = '/[A-Z]/';
        $lowercaseRegex = '/[a-z]/';
        $numberRegex = '/[0-9]/';
        $specialCharRegex = '/[^A-Za-z0-9]/'; // Matches any non-alphanumeric character
    
        return (
            preg_match($uppercaseRegex, $input) &&
            preg_match($lowercaseRegex, $input) &&
            preg_match($numberRegex, $input) &&
            preg_match($specialCharRegex, $input)
        );
    }
    
}

?>