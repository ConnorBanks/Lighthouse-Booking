<?php
    if (isset($_POST) && count($_POST) > 0)
    {
        database("TRUNCATE TABLE `slots_default_covers`");

        foreach ($_POST as $key => $value) 
        {
            $key = date('H:i',strtotime($key));
            $sql = "INSERT INTO slots_default_covers SET slot_covers='".$value."', slot_time='".$key."'";
            //echo '<p>'.$sql.'</p>';
            database($sql);
        }

        echo '<p style="color:green;">Settings Successfully Updated</p>';
    }
?>
<h2 class="margin-bottom--normal">Time Slot Covers</h2>
<div>
    <p class="margin-bottom--large">Below shows the default covers for each time slot available to the customer. The admin can override this setting if trying to make a booking which succeeds the limit.</p>

    <?php
        $slots = array();
        $results = database("SELECT * FROM slots_default_covers");
        foreach ($results as $result) 
        {
            $slots[$result['slot_time']] = $result['slot_covers'];
        }
    ?>
    <form action="settings.php?settings_tab=4" method="POST">
        <div class="grid">
            <div class="col col--vertical-top col-1-2">
                <h3 class="text--underline">Lunch</h3>
                <label for="12_00">12:00pm</label>
                <input type="number" id="12_00" name="12:00:00" value="<?php echo $slots['12:00:00']; ?>" min="0" max="50" />
                <label for="12_15">12:15pm</label>
                <input type="number" id="12_15" name="12:15:00" value="<?php echo $slots['12:15:00']; ?>" min="0" max="50" />
                <label for="12_30">12:30pm</label>
                <input type="number" id="12_30" name="12:30:00" value="<?php echo $slots['12:30:00']; ?>" min="0" max="50" />
                <label for="12_45">12:45pm</label>
                <input type="number" id="12_45" name="12:45:00" value="<?php echo $slots['12:45:00']; ?>" min="0" max="50" />
                <label for="13_00">13:00pm</label>
                <input type="number" id="13_00" name="13:00:00" value="<?php echo $slots['13:00:00']; ?>" min="0" max="50" />
                <label for="13_15">13:15pm</label>
                <input type="number" id="13_15" name="13:15:00" value="<?php echo $slots['13:15:00']; ?>" min="0" max="50" />
                <label for="13_30">13:30pm</label>
                <input type="number" id="13_30" name="13:30:00" value="<?php echo $slots['13:30:00']; ?>" min="0" max="50" />
                <label for="13_45">13:45pm</label>
                <input type="number" id="13_45" name="13:45:00" value="<?php echo $slots['13:45:00']; ?>" min="0" max="50" />
                <label for="14_00">14:00pm</label>
                <input type="number" id="14_00" name="14:00:00" value="<?php echo $slots['14:00:00']; ?>" min="0" max="50" />
                <label for="14_15">14:15pm</label>
                <input type="number" id="14_15" name="14:15:00" value="<?php echo $slots['14:15:00']; ?>" min="0" max="50" />
                <label for="14_30">14:30pm</label>
                <input type="number" id="14_30" name="14:30:00" value="<?php echo $slots['14:30:00']; ?>" min="0" max="50" />
                <label for="14_45">14:45pm</label>
                <input type="number" id="14_45" name="14:45:00" value="<?php echo $slots['14:45:00']; ?>" min="0" max="50" />
                <label for="15_00">15:00pm</label>
                <input type="number" id="15_00" name="15:00:00" value="<?php echo $slots['15:00:00']; ?>" min="0" max="50" />
            </div>
            <div class="col col--vertical-top col-1-2">
                <h3 class="text--underline">Dinner</h3>
                <label for="17_00">17:00pm</label>
                <input type="number" id="17_00" name="17:00:00" value="<?php echo $slots['17:00:00']; ?>" min="0" max="50" />
                <label for="17_15">17:15pm</label>
                <input type="number" id="17_15" name="17:15:00" value="<?php echo $slots['17:15:00']; ?>" min="0" max="50" />
                <label for="17_30">17:30pm</label>
                <input type="number" id="17_30" name="17:30:00" value="<?php echo $slots['17:30:00']; ?>" min="0" max="50" />
                <label for="17_45">17:45pm</label>
                <input type="number" id="17_45" name="17:45:00" value="<?php echo $slots['17:45:00']; ?>" min="0" max="50" />
                <label for="18_00">18:00pm</label>
                <input type="number" id="18_00" name="18:00:00" value="<?php echo $slots['18:00:00']; ?>" min="0" max="50" />
                <label for="18_15">18:15pm</label>
                <input type="number" id="18_15" name="18:15:00" value="<?php echo $slots['18:15:00']; ?>" min="0" max="50" />
                <label for="18_30">18:30pm</label>
                <input type="number" id="18_30" name="18:30:00" value="<?php echo $slots['18:30:00']; ?>" min="0" max="50" />
                <label for="18_45">18:45pm</label>
                <input type="number" id="18_45" name="18:45:00" value="<?php echo $slots['18:45:00']; ?>" min="0" max="50" />
                <label for="19_00">19:00pm</label>
                <input type="number" id="19_00" name="19:00:00" value="<?php echo $slots['19:00:00']; ?>" min="0" max="50" />
                <label for="19_15">19:15pm</label>
                <input type="number" id="19_15" name="19:15:00" value="<?php echo $slots['19:15:00']; ?>" min="0" max="50" />
                <label for="19_30">19:30pm</label>
                <input type="number" id="19_30" name="19:30:00" value="<?php echo $slots['19:30:00']; ?>" min="0" max="50" />
                <label for="19_45">19:45pm</label>
                <input type="number" id="19_45" name="19:45:00" value="<?php echo $slots['19:45:00']; ?>" min="0" max="50" />
                <label for="20_00">20:00pm</label>
                <input type="number" id="20_00" name="20:00:00" value="<?php echo $slots['20:00:00']; ?>" min="0" max="50" />
                <label for="20_15">20:15pm</label>
                <input type="number" id="20_15" name="20:15:00" value="<?php echo $slots['20:15:00']; ?>" min="0" max="50" />
                <label for="20_30">20:30pm</label>
                <input type="number" id="20_30" name="20:30:00" value="<?php echo $slots['20:30:00']; ?>" min="0" max="50" />
                <label for="20_45">20:45pm</label>
                <input type="number" id="20_45" name="20:45:00" value="<?php echo $slots['20:45:00']; ?>" min="0" max="50" />
                <label for="21_00">21:00pm</label>
                <input type="number" id="21_00" name="21:00:00" value="<?php echo $slots['21:00:00']; ?>" min="0" max="50" />
                <label for="21_15">21:15pm</label>
                <input type="number" id="21_15" name="21:15:00" value="<?php echo $slots['21:15:00']; ?>" min="0" max="50" />
                <label for="21_30">21:30pm</label>
                <input type="number" id="21_30" name="21:30:00" value="<?php echo $slots['21:30:00']; ?>" min="0" max="50" />
                <label for="21_45">21:45pm</label>
                <input type="number" id="21_45" name="21:45:00" value="<?php echo $slots['21:45:00']; ?>" min="0" max="50" />
            </div>
        </div>
        <input type="submit" value="Confirm Settings" class="text--white" />
    </form>
</div>
