<?php 

class contactDAO {
    public function newContactUs($id, $message) {
        $db = new DB();
        $conn = $db->createInstance();
        $insert_query = "INSERT INTO contact_forms (employee_id, message, create_time) VALUES (:id, :message, NOW())";

        $stmt = $conn->prepare($insert_query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":message", $message);

        $stmt->execute();

        return $stmt->rowCount();
    }

    public function getAllContactForms() {
        $db = new DB();
        $conn = $db->createInstance();
        $select_query = "SELECT e.fname, e.lname, c.message, c.create_time FROM contact_forms c JOIN employees e ON (c.employee_id = e.id)";

        $stmt = $conn->prepare($select_query);

        $stmt->execute();

        return $stmt->fetchAll();
    }
}

?>