<?php 
if (isset($_REQUEST['table_location'])) 
    {$table_location = $_SESSION['table_location'] = $_REQUEST['table_location'];}
elseif (isset($_SESSION['table_location'])) 
    {$table_location = $_SESSION['table_location'];}
else 
    {$table_location = 'downstairs';}
?>

<h2>Table Numbers</h2>
<div>
    <?php if (isset($_GET['addtable']) OR isset($_GET['edittable'])) { 
        if (isset($_GET['edittable']))
        {
            $table_id = $_GET['edittable'];
            $result = database("SELECT * FROM tables_single WHERE table_id='$table_id' LIMIT 1");
            extract($result);
        }
        else
        {
            $result = database("SELECT * FROM tables_single");
            $sortorder = (count($result) + 1);
        }
        ?>
        <h3><b><?php echo (isset($_GET['edittable'])?'Edit':'Add'); ?> Table</b></h3>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <input type="hidden" name="<?php echo (isset($_GET['edittable'])?'edit':'add'); ?>tablenow" value="<?php echo (isset($_GET['edittable'])?$table_id:1); ?>">
        <input type="hidden" name="sortorder" value="<?php echo $sortorder; ?>" />
            <div class="grid">
                <div class="col col-1-2">
                    <label for="TableNumber">Table number</label>
                    <input type="number" id="TableNumber" name="TableNumber" min="1" max="99" value="<?php echo ($table_number>''?$table_number:''); ?>" required>
                </div>
                <div class="col col-1-2">
                    <label for="TableLocation">Table Location</label>
                    <select name="TableLocation">
                        <option value="downstairs" <?php echo ($location=='downstairs'?' selected="selected"':''); ?>>Downstairs</option>
                        <option value="upstairs" <?php echo ($location=='upstairs'?' selected="selected"':''); ?>>Upstairs</option>
                    </select>
                </div>
            </div>
            <div class="grid">
                <div class="col col-1-2">
                    <label for="MinimumCovers">Minimum Covers</label>
                    <input type="number" id="MinimumCovers" name="MinimumCovers" min="1" max="99" value="<?php echo ($minimum_covers>''?$minimum_covers:''); ?>" required>
                </div>
                <div class="col col-1-2">
                    <label for="MaximumCovers">Maximum Covers</label>
                    <input type="number" id="MaximumCovers" name="MaximumCovers" min="1" max="99" value="<?php echo ($maximum_covers>''?$maximum_covers:''); ?>" required>
                </div>
            </div>
            <div class="grid">
                <div class="col col-1-2">
                </div>
                <div class="col col-1-2">
                    <input type="submit" value="Submit" class="text--white"></input>
                </div>
            </div>            
        </form>    
        <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="padding-top--small padding-bottom--small" style="color: black">Cancel</a>
        <?php 
        if (isset($_GET['edittable'])) 
        { 
            $combi = count(database("DELETE FROM tables_combinations WHERE tableid='$table_id'"));
            if ($combi == 0)
            {
                ?>
                <br /><br />
                <a href="<?php echo $_SERVER['PHP_SELF']; ?>?deletetable=<?php echo $table_id; ?>" class="padding-top--small padding-bottom--small" style="color: red">Delete</a>
                <?php 
            }
        } 
        ?>
    <?php } elseif (isset($_POST['addtablenow'])) { 
        extract($_POST);

        $sql = "INSERT INTO tables_single SET 
            sortorder='$sortorder',
            table_number='$TableNumber',
            location='$TableLocation',
            minimum_covers='$MinimumCovers',
            maximum_covers='$MaximumCovers',
            type='individual'";
        //echo '<p>'.$sql.'</p>';
        $id = database($sql);
        if ($id > 0)
        {
            ?>
            <p style="color: green">Table Added Successfully</p>
            <ul>
                <li><a style="color: black" href="<?php echo $_SERVER['PHP_SELF']; ?>?addtable"><button type="button" class="text--white padding-top--small padding-bottom--small">Add Table</button></a></li>
                <li><a style="color: black" href="<?php echo $_SERVER['PHP_SELF']; ?>"><button type="button" class="text--white padding-top--small padding-bottom--small">Return to List</button></a></li>
            </ul>
            <?php
        }
        else
        {
            ?>
            <p style="color: red">Table Added Unsuccessfully, please try again</p><br />
            <a style="color: black" href="<?php echo $_SERVER['PHP_SELF']; ?>"><button type="button" class="text--white padding-top--small padding-bottom--small">Return to List</button></a>
            <?php
        }
    ?>
    <?php } elseif (isset($_POST['edittablenow'])) { 
        extract($_POST);
        
        $sql = "UPDATE tables_single SET 
            table_number='$TableNumber',
            location='$TableLocation',
            minimum_covers='$MinimumCovers',
            maximum_covers='$MaximumCovers'
        WHERE table_id='$edittablenow'";
        //echo '<p>'.$sql.'</p>';
        database($sql);
    ?>
        <p style="color: black">Table Updated</p>
        <a style="color: black" href="<?php echo $_SERVER['PHP_SELF']; ?>"><button type="button" class="text--white padding-top--small padding-bottom--small">Return to List</button></a>
    
    <?php } elseif (isset($_GET['deletetable'])) { 
        $table_id = $_GET['deletetable'];
        if ($table_id > 0)
        {
            database("DELETE FROM tables_single WHERE table_id='$table_id'");
            ?>
            <p style="color: red">Table Deleted</p>
            <a style="color: black" href="<?php echo $_SERVER['PHP_SELF']; ?>"><button type="button" class="text--white padding-top--small padding-bottom--small">Return to List</button></a>
            <?php
        }
    ?>

    <?php } else { ?>

    <div class="grid">
        <div class="col col-1-4 vertical-align--middle">
            <a href="<?php echo $_SERVER['PHP_SELF']; ?>?table_location=downstairs"><button id="" type="button" class="text--white padding-top--small padding-bottom--small" <?php if ($table_location <> 'downstairs') {echo 'style="background-color:#5c5c5c"';} ?>>Downstairs</button></a>
        </div>
        <div class="col col-1-4 vertical-align--middle">
            <a href="<?php echo $_SERVER['PHP_SELF']; ?>?table_location=upstairs"><button id="" type="button" class="text--white padding-top--small padding-bottom--small" <?php if ($table_location <> 'upstairs') {echo 'style="background-color:#5c5c5c"';} ?>>Upstairs</button></a>
        </div>
        <div class="col col-1-4 vertical-align--middle"></div>
        <div class="col col-1-4 vertical-align--middle">
            <a href="<?php echo $_SERVER['PHP_SELF']; ?>?addtable"><button id="btn-add-table" type="button" class="text--white padding-top--small padding-bottom--small">Add Table</button></a>
        </div>
    </div>
    <h2><?php echo ucfirst($table_location); ?> tables:</h2>
    <?php
    echo "
    <table class='margin-bottom--normal sortable_sortorder'>
        <thead>
            <tr>
                <th>SO</th>
                <th>Table Number</th>
                <th>Type</th>
                <th>Min. Covers</th>
                <th>Max. Covers</th>
                <th>Edit</th>
            </tr>
        </thead>
    <tbody>"
    ;
    $query = "SELECT * FROM tables_single WHERE location='".$table_location."' ORDER BY sortorder,table_number";
    $results = database($query);
    foreach ($results as $row) 
    {
        echo "<tr id='identifyerID-".$row['table_id']."'>";
        echo "<td>" . $row['sortorder'] . "</td>";
        echo "<td>" . $row['table_number'] . "</td>";

        $type = $row['type'];
        if ( $type === 'linked') {
        echo "<td><span class='icon icon--small icon--link'></span></td>";
        } else {
        echo "<td></td>";
        }

        echo "<td>" . $row['minimum_covers'] . "</td>";
        echo "<td>" . $row['maximum_covers'] . "</td>";
        echo "<td><a class='icon icon--small icon--edit' href='".$_SERVER['PHP_SELF']."?edittable=" . $row['table_id'] . "'></a></td>";
        echo "</tr>";
    }
    echo "</tbody></table>";
    echo '<input class="sortable_sortorder_url" name="sortable_sortorder_url" value="ajax.php?request=updateSortorder&amp;update=tables_single&amp;identifyer=table_id" type="hidden">';    
    ?>
    <?php } ?>
</div>