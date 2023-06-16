<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | STOM | Simple Time-Off Manager</title>
    <?php
    require("public/html/includes.html");
    ?>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body>
    <?php 
        include("public/html/header.php")
    ?>

    <main>
        <div class="main-div">
            <h1>
                About Us
            </h1>
            

            <div style="width: 100%;" class="w3-content w3-display-container">
                <div style="overflow-x: auto;">
                    <img class="mySlides" src="public/images/veljko.jpg" style="margin: auto; height: 300px;">
                    <img class="mySlides" src="public/images/aleksa.jpg" style="margin: auto; height: 300px;">
                    <img class="mySlides" src="public/images/savo.jpg" style="margin: auto; height: 300px;">
                    <img class="mySlides" src="public/images/ftn-zgrada.jpg" style="margin: auto; height: 300px;">
                </div>

                <button class="w3-button w3-black w3-display-left" onclick="plusDivs(-1)">&#10094;</button>
                <button class="w3-button w3-black w3-display-right" onclick="plusDivs(1)">&#10095;</button>
            </div>

            <h2>
                Who are we?
            </h2>
            <p>
                We're three senior year students of Information Technologies attending Faculty of Technical Sciences in Čačak, which is a part of University of Kragujevac.
                <br>
                Our names are Aleksa, Veljko and Savo, although some call us "The Three Musketeers of IT". 
                <br>
            </p>

            <h2>
                About this project
            </h2>

            <p>
                The main goal of this project was to create a fully functional time-off management system for HR departments in different industries.
                <br>
                (Although, achieving a high grade in our Internet Programming class isn't much below on the priority list!)
            </p>

            <p>
                Feel free to contact us using the form below, and we'll reply as soon as possible.
            </p>
            <?= (isset($_SESSION["create-contact-us-message"])) ? "<p class='message-div " . $_SESSION["create-contact-us-class"] . "'><span class='message-text'>" . $_SESSION['create-contact-us-message'] . "</span></p>" : ""; ?>
            
            <form class="contact-form" action="<?= getFullServerPath() . "/contact-us" ?>" method="POST">
                <label for="message">Type your message here: </label><br>
                <textarea class="description-textarea" name="message" maxlength="500" rows="5" cols="50"></textarea>
                <button type="submit">Submit</button>
            </form>

            <script>
            var slideIndex = 1;
            showDivs(slideIndex);

            function plusDivs(n) {
            showDivs(slideIndex += n);
            }

            function showDivs(n) {
            var i;
            var x = document.getElementsByClassName("mySlides");
            if (n > x.length) {slideIndex = 1}
            if (n < 1) {slideIndex = x.length}
            for (i = 0; i < x.length; i++) {
                x[i].style.display = "none";  
            }
            x[slideIndex-1].style.display = "block";  
            }
            </script>
        </div>
    </main>

    <?php 
        include("public/html/footer.php")
    ?>
</body>
</html>