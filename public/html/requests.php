<?php 

$isLoggedIn = isset($_SESSION["isLoggedIn"]);
if (!$isLoggedIn) {
    header("Location: " . getFullServerPath() . "/");
    exit();
}

$_SESSION["active-page"] = "requests";
$requestDAO = new RequestDAO();
$requests = $requestDAO->getRequestsById($_SESSION["user"]["id"]);
        
if ($_SESSION["user"]["is_manager"] == 1) {
    $pendingRequests = $requestDAO->getAllPendingRequests();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requests | STOM | Simple Time-Off Manager</title>
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
            <h1> Create A Request </h1>
            <?= (isset($_SESSION["create-request-message"])) ? "<p class='message-div " . $_SESSION["create-request-class"] . "'><span class='message-text'>" . $_SESSION['create-request-message'] . "</span></p>" : ""; ?>
            
            <form class="profile-form" action="<?= getFullServerPath() . "/requests" ?>" method="POST">
                <div>
                    <label for="start_date">Start Date:</label><br>
                    <input class="" type="date" name="start_date" /><br>
                    <label for="end_date">End Date:</label><br>
                    <input class="" type="date" name="end_date" /><br>
                    <label for="description">Description:</label><br>
                    <textarea class="description-textarea" name="description" maxlength="250" rows="4" cols="50"></textarea><br>
                    <button class="" type="submit">Create Request</button><br>
                </div>
            </form>
            <hr>
            <h2> My Requests </h2>
            <div class="table-container">
                <?php 
                    if ($requests != false) {
                        ?> 
                        
                        <table class="requests-table"> 
                            <thead> 
                                <tr>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                        <?php
                        foreach($requests as $request) {
                            ?>
                            <tr class="<?= ($request["status"] == "APPROVED") ? "request-approved" : ""; ?><?= ($request["status"] == "DENIED") ? "request-denied" : "" ?>">
                                <td> <?= $request["start_date"] ?> </td>
                                <td> <?= $request["end_date"] ?> </td>
                                <td> <?= $request["description"] ?> </td>
                                <td> <?= $request["status"] ?> </td>
                            </tr>
                            <?php
                        } ?>
                            </tbody>
                        </table>
                        
                        <?php
                    } else {
                        echo("<p>You have no requests at this moment.</p>");
                    }

                ?>
            </div>
            
            <?php 
            
            if ($_SESSION["user"]["is_manager"] == 1) {
            ?>
                <hr>
                <h2>
                    Pending Requests
                </h2>
                <p>
                    Approve or Deny Requests
                </p>
                <div class="table-container">
                    <?php 
                        if ($pendingRequests != false) {
                        ?>
                            <?= (isset($_SESSION["update-request-message"])) ? "<p class='message-div " . $_SESSION["update-request-class"] . "'><span class='message-text'>" . $_SESSION['update-request-message'] . "</span></p>" : ""; ?>
                            
                            <table class="requests-table">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Description</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Application Time</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    foreach ($pendingRequests as $pr) {
                                    ?>
                                        <tr>
                                            <td><?= $pr["fname"] . " " . $pr["lname"] ?></td>
                                            <td><?= $pr["description"] ?></td>
                                            <td><?= $pr["start_date"] ?></td>
                                            <td><?= $pr["end_date"] ?></td>
                                            <td><?= $pr["create_time"] ?></td>
                                            <td>
                                                <div class="approve-div">
                                                    <a class="approve-link success-message" href="<?= getFullServerPath() . "/update-request?decision=APPROVED&id=" . $pr["id"] ?>">Approve</a><br>
                                                    <a class="approve-link error-message" href="<?= getFullServerPath() . "/update-request?decision=DENIED&id=" . $pr["id"] ?>">Deny</a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                            
                        <?php
                        } else {
                            echo("<p>No pending requests.</p>");
                        }
                    ?>
                </div>
            <?php
            }
            
            ?>
        </div>
    </main>

    <?php 
        include("footer.php")
    ?>
</body>
</html>