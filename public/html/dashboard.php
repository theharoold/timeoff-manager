<?php 

$isLoggedIn = isset($_SESSION["isLoggedIn"]);
if (!$isLoggedIn) {
    header("Location: " . getFullServerPath() . "/");
    exit();
}
$_SESSION["active-page"] = "dashboard";

function roundUpUnlessZero($number) {
    $rounded_number = ceil($number);
    
    if ($rounded_number == $number) {
        return floor($number);
    }
    
    return $rounded_number;
}

$eventDAO = new EventDAO();
$requestDAO = new RequestDAO();

$events = $eventDAO->getAllEvents();

if ($_SESSION["user"]["is_manager"] == 1) {
    $requests = $requestDAO->getAllApprovedRequests("");
} else {
    $requests = $requestDAO->getAllApprovedRequests($_SESSION["user"]["id"]);
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | STOM | Simple Time-Off Manager</title>
    <?php
    include("includes.html");
    ?>
</head>
<body>
    <?php 
        include("header.php")
    ?>

    <main>
        <div class="main-div">
            <h1>Dashboard</h1>

            <?php
            
            // default values
            $current_month = date('n');
            $current_year = date('Y');

            $requestDAO = new RequestDAO();
            $daysInMonth = $requestDAO->numberOfDaysInAMonth($current_year, $current_month);

            ?>

            <?php 

            if (!isset($_SESSION['calendar'])) {
                // Set the initial value to the current month
                $_SESSION['calendar'] = (new DateTime())->format('Y-m');
            } 

            if (isset($_GET["month"])) {
                if ($_GET["month"] == "add") {
                    $d = DateTime::createFromFormat("Y-m", $_SESSION["calendar"]);
                    $d->modify("+1 month");
                    $_SESSION["calendar"] = $d->format("Y-m"); 
                } else if ($_GET["month"] == "sub") {
                    $d = DateTime::createFromFormat("Y-m", $_SESSION["calendar"]);
                    $d->modify("-1 month");
                    $_SESSION["calendar"] = $d->format("Y-m"); 
                }
            }

            function addDaysToDate($day) {
                $calendar = $_SESSION['calendar'];
                $date = DateTime::createFromFormat('Y-m-d', $calendar . "-01");
                
                $day = $day - 1;
                $date->modify("+$day days");
                return $date;
            }

            $displayed_month = new DateTime($_SESSION['calendar']);

            ?> 
            <div class="dashboard-separator">
                <div class="calendar-div">
                    <div class="calendar-nav">
                        <h2 class="calendar-title"> <?= $displayed_month->format("F, Y"); ?> </h2>
                        <div class="month-control">
                            <h2>
                                <a class="month-control-link" href="<?= getFullServerPath() . "/?month=sub" ?>">&lt;</a>
                            </h2>
                            <h2>
                                <a class="month-control-link" href="<?= getFullServerPath() . "/?month=add" ?>">&gt;</a>
                            </h2>
                        </div>
                    </div>
                    
                    <?php 
                    $days = $requestDAO->numberOfDaysInAMonth($displayed_month->format("Y"), $displayed_month->format("m"));
                    $counter = 1;
                    for ($row = 1; $row <= roundUpUnlessZero($days / 7); $row++) {
                        ?>
                        <div class="calendar-row">
                            <?php
                            for ($i = 1; $counter <= $days && $i <= 7; $i++, $counter++) {
                                $data_class = "";
                                $curr_date = addDaysToDate($counter);
                                $format_date = $curr_date->format("Y-m-d");

                                $data_class = $eventDAO->dateIsEventClass($format_date, $events);
                                
                                $active_events = $eventDAO->activeEvents($format_date, $events);


                                $active_requests = array();
                                
                                if ($requests != false) {
                                    foreach ($requests as $request) {
                                        $dates = $requestDAO->arrayOfDates($request["start_date"], $request["end_date"]);
                                        $is_in_dates = $requestDAO->isInDates($format_date, $dates);
                                        $data_class = ($is_in_dates) ? "error-message" : $data_class;
                                        if ($is_in_dates) {
                                            $active_requests[] = $request;
                                        }
                                    }
                                }

                                ?>
                                <div class="calendar-data <?= $data_class ?>" title="<?php 
                                    $final_msg = "";
                                    foreach ($active_events as $event) {
                                        $msg = "Event: " . $event["name"] . " | " . "Description: " . $event["description"];
                                        $final_msg = $final_msg . (($final_msg != "") ? "<br>" : "") . $msg;
                                        echo($msg);
                                    }
                                    foreach ($active_requests as $rq) {
                                        $msg = "Employee: " . $rq["fname"] . " " . $rq["lname"] . " | Description: " . $rq["description"];
                                        $final_msg = $final_msg . (($final_msg != "") ? "<br>" : "") . $msg;
                                        echo($msg);
                                    }
                                    
                                ?>">
                                <a href="<?= getFullServerPath() . "/?msg=" . $final_msg ?>">
                                    <sup>
                                        <?= $counter . " " . addDaysToDate($counter)->format('D'); ?>
                                    </sup>
                                </a> 
                                    
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <?php 
                    }

                    ?>
                </div>
                <div class="approval-graphic-div">
                    <h2 class="calendar-title">Date Details:</h2>
                    <p class="<?= (isset($_GET["msg"])) ? "info-message" : "" ?>">
                        <?= (isset($_GET["msg"])) ? $_GET["msg"] : "" ?>
                    </p>
                </div>

                
            </div>

            <hr style="margin-top: 50px; margin-bottom: 50px;">

                <div class="surveys">
                    <h2> Surveys </h2>
                    <?php 
                    
                    $surveyDAO = new SurveyDAO();
                    $surveys = $surveyDAO->getAllUnansweredSurveys($_SESSION["user"]["id"]);

                    if ($surveys != false) {
                        ?> 
                        
                        <?= (isset($_SESSION["create-survey-response-message"])) ? "<p class='message-div " . $_SESSION["create-survey-response-class"] . "'><span class='message-text'>" . $_SESSION['create-survey-response-message'] . "</span></p>" : ""; ?>
                            <div class="table-container">
                            <table class="requests-table">
                                <thead>
                                    <tr>
                                        <th>Survey Question</th>
                                        <th>Answers</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    foreach ($surveys as $survey) {
                                    ?>
                                        <tr>
                                            <td><?= $survey["question"] ?></td>
                                            <?php 
                                            
                                            $answers = explode(",", $survey["answers"]);
                                            for ($i = 0; $i < count($answers); $i++) {
                                                $answers[$i] = trim($answers[$i]);
                                            }
                                            
                                            ?>
                                            <td>
                                                <div class="approve-div">
                                                    <?php 
                                                    
                                                    foreach ($answers as $answer) {
                                                        ?>
                                                        <a class="approve-link info-message" href="<?= getFullServerPath() . "/answer-survey?answer=". $answer ."&id=" . $survey["id"] ?>"> <?= $answer ?> </a><br>
                                                        <?php
                                                    }
                                                    
                                                    ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                            </div>
                        <?php
                    }

                    ?>
                </div>
        </div>
    </main>

    <?php 
        include("footer.php")
    ?>
</body>
</html>