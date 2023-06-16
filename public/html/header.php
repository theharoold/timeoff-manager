<header>
    <div class="header-div">
        <div class="header-small-container">
            <div class="header-logo">
                <div class="header-logo-img">
                    <a class="header-logo-link" href="<?= getFullServerPath(); ?>">&#8987;</a>
                </div>
                <div class="header-logo-text">
                    <a class="header-logo-link" href="<?= getFullServerPath() . "/"; ?>">STOM</a>
                </div>
            </div>
            <?php
                $isLoggedIn = isset($_SESSION["isLoggedIn"]);
                if ($isLoggedIn) { 
            ?>
            <div class="header-links-toggle" onclick="toggleNavbar()">
                &Congruent;
            </div>
            <?php } ?>
        </div>
        <div class="header-links" id="header-links">
            <?php
                $isLoggedIn = isset($_SESSION["isLoggedIn"]);
                if ($isLoggedIn) { 
            ?>
                <div><a href="<?= getFullServerPath() . "/" ?>" class="header-link-a <?= $_SESSION["active-page"] == "dashboard" ? "" : "in" ?>active-link">Dashboard</a></div>
                <div><a href="<?= getFullServerPath() . "/requests" ?>" class="header-link-a <?= $_SESSION["active-page"] == "requests" ? "" : "in" ?>active-link">Requests</a></div>
                <div><a href="<?= getFullServerPath() . "/profile" ?>" class="header-link-a <?= $_SESSION["active-page"] == "profile" ? "" : "in" ?>active-link">Profile</a></div>
                <div><a href="<?= getFullServerPath() . "/about-us" ?>" class="header-link-a <?= $_SESSION["active-page"] == "about-us" ? "" : "in" ?>active-link">About Us</a></div>
                
                <?php
                    $isManager = isset($_SESSION["isManager"]);
                    if ($isManager) {
                        ?> 
                        <div><a href="<?= getFullServerPath() . "/admin" ?>" class="header-link-a <?= $_SESSION["active-page"] == "admin" ? "" : "in" ?>active-link">Admin</a></div>
                        <?php
                    }
                }
            ?>
            <?php
            if ($isLoggedIn) { ?>
                <button class="logout-button"><a class="logout-button-link" href="<?= getFullServerPath() . "/logout" ?>"> Log out </a></button>
            <?php
            }
            ?>


        </div>
    </div>
    <hr>
</header>