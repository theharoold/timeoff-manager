<?php 

class RequestDAO {
    public function createRequest($requestData) {
        try {
            $insert_query = "INSERT INTO requests (employee_id, start_date, end_date, description, create_time) VALUES (:employee_id, STR_TO_DATE(:start_date, '%Y-%m-%d'), STR_TO_DATE(:end_date, '%Y-%m-%d'), :description, NOW())";
            $insert_status_query = "INSERT INTO request_statuses (request_id) VALUES (:request_id)";

            $db = new DB();
            $conn = $db->createInstance();

            $stmt = $conn->prepare($insert_query);
            $stmt->bindParam(":employee_id", $_SESSION["user"]["id"]);
            $stmt->bindParam(":start_date", $requestData["start_date"]);
            $stmt->bindParam(":end_date", $requestData["end_date"]);
            $stmt->bindParam(":description", $requestData["description"]);

            $stmt->execute();
            
            $lastId = $conn->lastInsertId();
            $status_stmt = $conn->prepare($insert_status_query);
            $status_stmt->bindParam(":request_id", $lastId);
            $status_stmt->execute();

            return $stmt->rowCount();
        } catch (Exception $e) {
            return 0;
        }
    }
}

?>