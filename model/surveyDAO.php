<?php 

class surveyDAO {
    public function createSurvey($question, $answers) {
        $db = new DB();
        $conn = $db->createInstance();
        $insert_query = "INSERT INTO surveys (question, answers) VALUES (:question, :answers)";

        $stmt = $conn->prepare($insert_query);
        $stmt->bindParam(":question", $question);
        $stmt->bindParam(":answers", $answers);

        $stmt->execute();

        return $stmt->rowCount();
    }

    public function getAllUnansweredSurveys($id) {
        $select_query = "SELECT s.id, s.question, s.answers FROM surveys s LEFT JOIN survey_responses sa ON (s.id = sa.survey_id) AND (sa.employee_id = :id) WHERE sa.survey_id is NULL";
    
        $db = new DB();
        $conn = $db->createInstance();

        $stmt = $conn->prepare($select_query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAnswerCount($survey, $answer) {
        $select_query = "SELECT COUNT(*) FROM survey_responses WHERE survey_id = :survey AND answer = :answer";
    
        $db = new DB();
        $conn = $db->createInstance();

        $stmt = $conn->prepare($select_query);
        $stmt->bindParam(":survey", $survey);
        $stmt->bindParam(":answer", $answer);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function createSurveyResponse($answer, $id) {
        $employee_id = $_SESSION["user"]["id"];

        $insert_query = "INSERT INTO survey_responses (employee_id, survey_id, answer) VALUES (:employee_id, :survey_id, :answer)";

        $db = new DB();
        $conn = $db->createInstance();

        $stmt = $conn->prepare($insert_query);
        $stmt->bindParam(":survey_id", $id);
        $stmt->bindParam(":employee_id", $employee_id);
        $stmt->bindParam(":answer", $answer);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function getAllSurveys() {
        $select_query = "SELECT * FROM surveys";
    
        $db = new DB();
        $conn = $db->createInstance();

        $stmt = $conn->prepare($select_query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}

?>