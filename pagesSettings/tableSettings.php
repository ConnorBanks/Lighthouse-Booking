<?php
    if (isset($_POST) && count($_POST) > 0)
    {
        foreach ($_POST as $key => $value) 
        {
            $sql = "UPDATE settings SET fieldValue='".$value."' WHERE fieldName='".$key."'";
            //echo '<p>'.$sql.'</p>';
            database($sql);
        }

        echo '<p style="color:green;">Settings Successfully Updated</p>';
    }
?>
<h2 class="margin-bottom--normal">Default Table Settings</h2>
<div>
    <?php
    $query = "SELECT * FROM settings WHERE data_group = 1";
    $results = database($query);

    foreach ($results as $row) 
    {
        $tableSettings[$row['fieldName']] = $row['fieldValue'];
    }
    ?>
    <p class="margin-bottom--large">Below shows default settings for length of use in minutes of tables for specific party sizes, if nothing is put in the specify party sizes (i.e 1-4, 5-8, 9-50) it will automatically place them at the default length.</p>
    <form action="settings.php?settings_tab=2" method="POST">
        <div class="grid">
            <div class="col col-1-2">
                <h3 class="text--underline">Downstairs</h3>
                <label for="DownstairsDefault">Downstairs Default:</label>
                <input type="number" id="DownstairsDefault" name="DownstairsDefault" value="<?php echo $tableSettings['DownstairsDefault'];?>" min="0" max="180" step="5" required></input>
                <label for="Downstairs_1_4">Party Size 1-4:</label>
                <input type="number" id="Downstairs_1_4" name="Downstairs_1_4" value="<?php echo $tableSettings['Downstairs_1_4'];?>" min="0" max="180" step="5" required></input>
                <label for="Downstairs_5_8">Party Size 5-8:</label>
                <input type="number" id="Downstairs_5_8" name="Downstairs_5_8" value="<?php echo $tableSettings['Downstairs_5_8'];?>" min="0" max="180" step="5" required></input>
                <label for="Downstairs_9_50">Downstairs 9-50:</label>
                <input type="number" id="Downstairs_9_50" name="Downstairs_9_50" value="<?php echo $tableSettings['Downstairs_9_50'];?>" min="0" max="180" step="5" required></input>
            </div>

            <div class="col col-1-2">
                <h3 class="text--underline">Upstairs</h3>
                <label for="UpstairsDefault">Upstairs Default:</label>
                <input type="number" id="UpstairsDefault" name="UpstairsDefault" value="<?php echo $tableSettings['UpstairsDefault'];?>" min="0" max="180" step="5" required></input>
                <label for="Upstairs_1_4">Party Size 1-4:</label>
                <input type="number" id="Upstairs_1_4" name="Upstairs_1_4" value="<?php echo $tableSettings['Upstairs_1_4'];?>" min="0" max="180" step="5" required></input>
                <label for="Upstairs_5_8">Party Size 5-8:</label>
                <input type="number" id="Upstairs_5_8" name="Upstairs_5_8" value="<?php echo $tableSettings['Upstairs_5_8'];?>" min="0" max="180" step="5" required></input>
                <label for="Upstairs_9_50">Upstairs 9-50:</label>
                <input type="number" id="Upstairs_9_50" name="Upstairs_9_50" value="<?php echo $tableSettings['Upstairs_9_50'];?>" min="0" max="180" step="5" required></input>
            </div>
        </div>
        <input type="submit" value="Confirm Settings" class="text--white"></input>
    </form>
</div>
