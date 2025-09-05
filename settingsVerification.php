<?php require 'headerAdmin.php'; ?>

    <?php require 'navigationAdmin.php'; ?>

    <div class="background--white max-width--1000 margin-auto--left margin-auto--right padding-top--normal padding-bottom--normal padding-left--normal padding-right--normal">

        <form action="login_setting.php" method="post">
            <label for="SettingPassword">Login to access settings</label>
            <input type="password" id="SettingPassword" name="SettingPassword">
            <input class="text--white" type="submit" value="Login"></input>
        </form>

    </div>

<?php require 'footer.php'; ?>
