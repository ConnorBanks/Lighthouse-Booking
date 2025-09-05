<?php require 'header.php'; ?>

    <div class="background--grey">

        <div class="margin-bottom--normal text-align--center padding--normal">
            <a href="/control_panel.php">
                <img src="images/lighthouse-restaurant-logo.png" alt="lighthouse-restaurant-logo" class="padding--normal">
            </a>
        </div>
    </div>

    <div class="background--white max-width--600 margin-auto--left margin-auto--right padding-top--normal padding-bottom--huge padding-left--normal padding-right--normal" style="max-width: 100%;width: 300px;">

        <h2 class="text-align--center margin-bottom--normal"><?php echo date('dS F Y'); ?></h2>
        <?php if (isset($_GET['loginerror'])) { ?>
            <p style="color: red;">Incorrect Password! Try again</p>
        <?php } ?>
        <form action="login.php" method="post" class="padding-top--normal form--borders">
            <label for="Username">Username</label>
            <input type="text" id="Username" name="Username" autofocus>
            <label for="Password">Password</label>
            <input type="password" id="Password" name="Password">
            <input type="submit" value="Login"></input>
        </form>

        <p>
            <a href="MAILTO: info@lighthouserestaurant.co.uk" class="text--black text--italic text--underline">Forgotten password?</a>
        </p>
    </div>

<?php require 'footer.php'; ?>
