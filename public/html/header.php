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
                <div><a href="#" class="header-link-a active-link">Dashboard</a></div>
                <div><a href="#" class="header-link-a inactive-link">Requests</a></div>
                <div><a href="#" class="header-link-a inactive-link">Profile</a></div>
                <?php
                    $isManager = isset($_SESSION["isManager"]);
                    if ($isManager) {
                        ?> 
                        <div><a href="#" class="header-link-a inactive-link">Admin</a></div>
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