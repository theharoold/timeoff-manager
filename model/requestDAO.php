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

    public function getRequestsById($id) {
        $select_query = "SELECT r.start_date, r.end_date, r.description, rs.status FROM requests r JOIN request_statuses rs ON (r.id = rs.request_id)  WHERE r.employee_id = :id";
        
        $db = new DB();
        $conn = $db->createInstance();

        $stmt = $conn->prepare($select_query);
        $stmt->bindParam(":id", $id);

        $stmt->execute();

        $results = $stmt->fetchAll();
        return $results;
    
    }

    public function getAllPendingRequests() {
        $select_query = "SELECT r.id, e.fname, e.lname, r.description, r.start_date, r.end_date, r.create_time FROM requests r JOIN employees e ON (r.employee_id = e.id) JOIN request_statuses rs ON (r.id = rs.request_id) WHERE rs.status = 'PROCESSING'";
        
        $db = new DB();
        $conn = $db->createInstance();

        $stmt = $conn->prepare($select_query);

        $stmt->execute();

        $results = $stmt->fetchAll();
        return $results;
    }

    public function getAllApprovedRequests($id) {
        $select_query = "SELECT r.id, e.fname, e.lname, r.description, r.start_date, r.end_date, r.create_time FROM requests r JOIN employees e ON (r.employee_id = e.id) JOIN request_statuses rs ON (r.id = rs.request_id) WHERE rs.status = 'APPROVED'" . (($id == "") ? "" : " AND e.id = :id");
        
        $db = new DB();
        $conn = $db->createInstance();

        $stmt = $conn->prepare($select_query);
        if ($id != "") {
            $stmt->bindParam(":id", $id);
        }

        $stmt->execute();

        $results = $stmt->fetchAll();
        return $results;
    }

    public function updateRequest($id, $decision) {
        $update_query = "UPDATE request_statuses SET status = :status, manager_id = :manager_id, updated_on = NOW() WHERE request_id = :id";
        $db = new DB();
        $conn = $db->createInstance();

        $stmt = $conn->prepare($update_query);
        $stmt->bindParam(":status", $decision);
        $stmt->bindParam(":manager_id", $_SESSION["user"]["id"]);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        $results = $stmt->rowCount();
        return $results;
    }

    public function arrayOfDates($start_date, $end_date) {
        $start_date_obj = DateTime::createFromFormat('Y-m-d', $start_date);
        $end_date_obj = DateTime::createFromFormat('Y-m-d', $end_date);

        $current_date = $start_date_obj;
        $date_objects = [];

        while ($current_date <= $end_date_obj) {
            $date_object = $current_date->format('Y-m-d');

            $date_objects[] = $date_object;

            $current_date->add(new DateInterval('P1D'));
        }

        return $date_objects;
    }

    public function isInDates($date, $dates) {
        foreach ($dates as $obj) {
            if ($date == $obj) {
                return true;
            }
        }

        return false;
    }

    public function numberOfDaysInAMonth($year, $month) {
        return cal_days_in_month(CAL_GREGORIAN, $month, $year);
    }

    public function formatMonthYear($input_month, $input_year) {
        $date = DateTime::createFromFormat('!m Y', $input_month . ' ' . $input_year);
        $output = $date->format('F, Y');
        return $output;
    }
}

?>