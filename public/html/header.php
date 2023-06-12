<header>
    <div class="header-div">
        <div class="header-logo">
            <div class="header-logo-img">
                &#8987;
            </div>
            <div class="header-logo-text">
                STOM
            </div>
        </div>
        <div class="header-links">
            <?php
                $isLoggedIn = isset($_SESSION["isLoggedIn"]);
                if ($isLoggedIn) { 
            ?>
                <div><a href="#" class="header-link-a active-link">Dashboard</a></div>
                <div><a href="#" class="header-link-a inactive-link">Requests</a></div>
                <div><a href="#" class="header-link-a inactive-link">Profile</a></div>
                <?php
                    $isAdmin = isset($_SESSION["isAdmin"]);
                    if ($isAdmin) {
                        ?> 
                        <div><a href="#" class="header-link-a inactive-link">Admin</a></div>
                        <?php
                    }
                }
            ?>


        </div>
    </div>
    <hr>
</header>