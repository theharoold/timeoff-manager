<?php 

class EventDAO {
    public function createEvent($eventData) {
        $db = new DB();
        $conn = $db->createInstance();
        $insert_query = "INSERT INTO events (name, description, event_date, is_workday) VALUES (:name, :description, :event_date, :is_workday)";

        $stmt = $conn->prepare($insert_query);
        $stmt->bindParam(":name", $eventData["name"]);
        $stmt->bindParam(":description", $eventData["description"]);
        $stmt->bindParam(":event_date", $eventData["event_date"]);
        $stmt->bindParam(":is_workday", $eventData["is_workday"]);

        $stmt->execute();

        return $stmt->rowCount();
    }

    public function getAllEvents() {
        $db = new DB();
        $conn = $db->createInstance();
        $select_query = "SELECT * FROM events";

        $stmt = $conn->prepare($select_query);
        $stmt->execute();

        $results = $stmt->fetchAll();
        return $results;
    }

    function dateIsEventClass($date, $events) {
        $is_event = "";
        if ($events == false) {
            return $is_event;
        }
        foreach ($events as $event) {
            if ($date == $event["event_date"]) {
                if ($event["is_workday"] == 1) {
                    $is_event = "success-message";
                } else {
                    $is_event = "warning-message";
                    break;
                }
            }
        }
    
        return $is_event;
    }

    function activeEvents($date, $events) {
        $active_events = array();
        if ($events == false) {
            return $active_events;
        }
        foreach ($events as $event) {
            if ($date == $event["event_date"]) {
                $active_events[] = $event;
            }
        }
    
        return $active_events;
    }


}

?>