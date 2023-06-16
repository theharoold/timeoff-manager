<?php 

$isLoggedIn = isset($_SESSION["isLoggedIn"]);
if (!$isLoggedIn) {
    header("Location: " . getFullServerPath() . "/");
    exit();
}

$_SESSION["active-page"] = "admin";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | STOM | Simple Time-Off Manager</title>
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
            <h1>Create Employee Account</h1>
            <?= (isset($_SESSION["create-account-message"])) ? "<p class='message-div " . $_SESSION["create-account-class"] . "'><span class='message-text'>" . $_SESSION['create-account-message'] . "</span></p>" : ""; ?>
            <form class="profile-form" action="<?= getFullServerPath() . "/create-account" ?>" method="POST">
                <div>
                    <label for="email">Email:</label><br>
                    <input type="email" name="email" /><br>
                    <label for="password">Password:</label><br>
                    <input type="password" name="password" /><br>
                    <label for="fname">First Name:</label><br>
                    <input type="text" name="fname" /><br>
                </div>
                <div>
                    <label for="lname">Last Name:</label><br>
                    <input type="text" name="lname" /><br>
                    <label for="job_title">Job Title:</label><br>
                    <input type="text" name="job_title" /><br>
                    <label for="is_manager">Is A Manager:</label><br>
                    <input type="checkbox" name="is_manager" /><br>
                    <button type="submit">Create New Account</button><br>
                </div>

            </form>
            <hr>
            <h2> Create an Event </h2>
            <?= (isset($_SESSION["create-event-message"])) ? "<p class='message-div " . $_SESSION["create-event-class"] . "'><span class='message-text'>" . $_SESSION['create-event-message'] . "</span></p>" : ""; ?>
            <form class="profile-form" action="<?= getFullServerPath() . "/create-event" ?>" method="POST">
                <div>
                    <label for="name">Event Name:</label>
                    <input type="text" name="name" maxlength="40"/>
                    <label for="description">Description:</label>
                    <input type="text" name="description" maxlength="80"/>
                    <label for="event_date">Event Date:</label>
                    <input type="date" name="event_date"/>
                    <label for="is_workday">Is it a Workday:</label>
                    <input type="checkbox" name="is_workday"/>
                    <button type="submit">Create Event</button>
                </div>                    
            </form>

            <hr> 

            <h2> Submitted Contact Forms </h2>
            <?php 
            
            $contactDAO = new ContactDAO();
            $contact_forms = $contactDAO->getAllContactForms();

            if ($contact_forms != false) {
                foreach($contact_forms as $form) {
                    ?>
                    <div class="contact-form-content-div"> 
                        <?= "[" . $form["create_time"] . "] " . $form["fname"] . " " . $form["lname"] . " says: " . $form["message"] ?>
                    </div>
                    <?php
                }
            } else {
                echo("<p>No contact forms have been submitted yet.");
            }
            
            ?>

            <hr style="margin-top: 50px; margin-bottom: 50px;">

            <h2> Create A Survey </h2>
            <?= (isset($_SESSION["create-survey-message"])) ? "<p class='message-div " . $_SESSION["create-survey-class"] . "'><span class='message-text'>" . $_SESSION['create-survey-message'] . "</span></p>" : ""; ?>
            <form class="profile-form" action="<?= getFullServerPath() . "/create-survey" ?>" method="POST">
                <div> 
                    <label for="question">Survey Question: </label><br>
                    <input type="text" name="question" />
                    <label for="answers">Allowed Survey Answers (separate them with a comma): </label><br>
                    <input type="text" name="answers" />
                    <button type="submit">Submit</button>
                </div>
            </form>

            <hr style="margin-top: 50px; margin-bottom: 50px;">
            
            <h2> Survey results </h2>
            <div>

            <?php 
            
            $surveyDAO = new SurveyDAO();
            $allSurveys = $surveyDAO->getAllSurveys();

            if ($allSurveys == false) {
                echo("<p> There are currently no active surveys. Why not make one?</p>");
            } else {
                foreach ($allSurveys as $survey) {
                    ?>
                    <ul>
                        <p>Survey question: <?= $survey["question"] ?> </p>
                    
                        <?php 
                        
                        $answers = explode(",", $survey["answers"]);
                        for ($i = 0; $i < count($answers); $i++) {
                            echo("<li>" . $answers[$i] . ": " . ($surveyDAO->getAnswerCount($survey["id"], $answers[$i]))["COUNT(*)"] . "</li>");
                        }


                        
                        ?>
                    
                    </ul>
                    <?php
                }
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