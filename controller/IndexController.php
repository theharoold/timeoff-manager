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

            $address_info = $userDAO->getAddressById($results["id"]);
            if (!$address_info) {
                $address_info = [
                    "address" => "", 
                    "zip_code" => "",
                    "city" => "",
                    "country" => "",
                ];
            }

            $_SESSION["user_address"] = $address_info;

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
        unset($_SESSION["update-employee-message"]);
        unset($_SESSION["change-password-message"]);
        if ($isLoggedIn) {
            $userDAO = new UserDAO();
            $_SESSION["user"] = $userDAO->getUserById($_SESSION["user"]["id"]);
            $_SESSION["user_address"] = $userDAO->getAddressById($_SESSION["user"]["id"]);
            require("public/html/profile.php");
        } else {
            require("public/html/login.php");
        }
    }

    public function updateProfile($isLoggedIn, $formData) {
        if ($isLoggedIn) {
            $userDAO = new UserDAO();

            // Prepare an array to store the fields and their respective values
            $updateEmployeeFields = array();
            $updateAddressFields = array();
            $formEmployeeData = array();
            $formAddressData = array();

            // Iterate over the form data
            foreach ($formData as $field => $value) {
                // Check if the field value is not empty
                if ($value !== '' && $userDAO->contains($field, $userDAO->employeeFieldList)) {
                    // Add the field and its value to the updateFields array
                    $updateEmployeeFields[] = "`$field` = :$field";
                    $formEmployeeData[$field] = $value;
                }
                if ($value !== '' && $userDAO->contains($field, $userDAO->addressFieldList)) {
                    // Add the field and its value to the updateFields array
                    $updateAddressFields[] = "`$field` = :$field";
                    $formAddressData[$field] = $value;
                }
            }

            $resultEmp = (count($updateEmployeeFields) > 0) ? $userDAO->updateEmployee($updateEmployeeFields, $formEmployeeData) : 0;
            $resultAddr = (count($updateAddressFields) > 0 ) ? $userDAO->updateAddress($updateAddressFields, $formAddressData) : 0;

            if ($resultEmp > 0 || $resultAddr > 0) {
                $_SESSION["update-employee-message"] = "Profile successfully updated.";
                $_SESSION["user"] = $userDAO->getUserById($_SESSION["user"]["id"]);
                $_SESSION["user_address"] = $userDAO->getAddressById($_SESSION["user"]["id"]);
                $_SESSION["update-message-class"] = "success-message";
            } else {
                $_SESSION["update-message-class"] = "error-message";
                $_SESSION["update-employee-message"] = "Profile update failed. Please try again later.";
            }
            

            require("public/html/profile.php");
        } else {
            require("public/html/login.php");
        }
    }

    public function changePassword($id, $newPassword) {
        $userDAO = new UserDAO();
        $result = $userDAO->changePassword($id, $newPassword);
        
        if ($result > 0) {
            $_SESSION["change-password-message"] = "Password successfully changed.";
            $_SESSION["change-password-class"] = "success-message";
            $_SESSION["user"] = $userDAO->getUserById($_SESSION["user"]["id"]);
        } else {
            $_SESSION["change-password-class"] = "error-message";
            $_SESSION["change-password-message"] = "Password change failed. Please try again later.";
        }
            

        require("public/html/profile.php");
    }

    public function createAccount($accountData) {
        $userDAO = new UserDAO();
        $result = $userDAO->createAccount($accountData);

        if ($result > 0) {
            $_SESSION["create-account-message"] = "Account successfully created.<br>Login details:<br>Email: " . $accountData["email"] . "<br>" . "Password: " . $accountData["plaintext"];
            $_SESSION["create-account-class"] = "success-message";
        } else {
            $_SESSION["create-account-message"] = "Unable to create account. Please try again later.";
            $_SESSION["create-account-class"] = "error-message";
        }

        require_once("public/html/admin.php");
    }
}

?>