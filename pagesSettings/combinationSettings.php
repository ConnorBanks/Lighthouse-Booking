<?php $bookingnumber_max = 40; ?>
<?php 
if (isset($_REQUEST['bookingnumber'])) 
    {$bookingnumber = $_SESSION['bookingnumber'] = $_REQUEST['bookingnumber'];}
elseif (isset($_SESSION['bookingnumber'])) 
    {$bookingnumber = $_SESSION['bookingnumber'];}
else 
    {$bookingnumber = '1';}

if (isset($_REQUEST['table_location'])) 
    {$table_location = $_SESSION['table_location'] = $_REQUEST['table_location'];}
elseif (isset($_SESSION['table_location'])) 
    {$table_location = $_SESSION['table_location'];}
else 
    {$table_location = 'downstairs';}
?>

<h2>Combination Tables</h2>
<div>
    <?php if (isset($_GET['addcombination']) OR isset($_GET['editcombination'])) { 
        if (isset($_GET['editcombination']))
        {
            $combinationid = $_GET['editcombination'];
            $result = database("SELECT * FROM combinations WHERE combinationid='$combinationid' LIMIT 1");
            extract($result);
        }
        else
        {
            $result = database("SELECT * FROM combinations WHERE bookingnumber='$bookingnumber' AND location='$table_location'");
            $sortorder = (count($result) + 1);

        }
        ?>
        <h3><b><?php echo (isset($_GET['editcombination'])?'Edit':'Add'); ?> Combination</b></h3>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <input type="hidden" name="<?php echo (isset($_GET['editcombination'])?'edit':'add'); ?>combinationnow" value="<?php echo (isset($_GET['editcombination'])?$combinationid:1); ?>">
        <input type="hidden" name="bookingnumber" value="<?php echo $bookingnumber; ?>" />
        <input type="hidden" name="table_location" value="<?php echo $table_location; ?>" />
        <input type="hidden" name="sortorder" value="<?php echo $sortorder; ?>" />
            <?php if (isset($_GET['editcombination'])) { ?>
                <label for="combinationname">Combination Name</label>
                <input type="text" id="combinationname" name="combinationname" value="<?php echo ($combinationname>''?$combinationname:''); ?>" required>
            <?php } ?>
            <h3><?php echo ucfirst($table_location); ?></h3>
            <div class="grid">
                <?php 
                    $results = database("SELECT * FROM tables_single WHERE location='$table_location'");
                    foreach ($results as $result) 
                    {
                        $result_table_linked = count(database("SELECT * FROM tables_combinations WHERE combinationid='$combinationid' AND tableid='".$result['table_id']."'"));
                        ?>
                        <div class="col col-1-4 tks-ch-bxs" style="vertical-align: top;">
                            <label><input type="checkbox" name="tables_linked[]" value="<?php echo $result['table_id']; ?>" <?php echo ($result_table_linked>0?'checked="checked"':''); ?>> Table <?php echo $result['table_number'].' (covers: '.$result['minimum_covers'].' -> '.$result['maximum_covers'].')'; ?><div class="tks-ch-bxs-overlay"></div></label>
                            
                        </div>
                        <?php
                    }
                ?>
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
        <?php if (isset($_GET['editcombination'])) { ?>
            <br /><br />
            <a href="<?php echo $_SERVER['PHP_SELF']; ?>?deletecombination=<?php echo $combinationid; ?>" class="padding-top--small padding-bottom--small" style="color: red">Delete</a>
        <?php } ?>
    <?php } elseif (isset($_POST['addcombinationnow'])) { 
        extract($_POST);

        $combinationname_array = array();
        foreach ($tables_linked as $key => $tableid)
        {
            $result = database("SELECT * FROM tables_single WHERE table_id='$tableid' LIMIT 1");
            $combinationname_array[] = $result['table_number'];
        }
        $last_table_number = $combinationname_array[count($combinationname_array)-1];
        if (count($tables_linked) > 2) {unset($combinationname_array[count($combinationname_array)-1]);}
        $combinationname = 'Table'.(count($tables_linked)>1?'s':'').' '.
            (count($tables_linked) > 2?implode(', ', $combinationname_array).' & '.$last_table_number:
                (count($tables_linked) > 1?implode(' & ', $combinationname_array):
                    $combinationname_array[0]
                )
            );

        $sql = "INSERT INTO combinations SET 
            bookingnumber='$bookingnumber',
            sortorder='$sortorder',
            location='$table_location',
            combinationname='$combinationname'";
        //echo '<p>'.$sql.'</p>';
        $id = database($sql);
        if ($id > 0 && count($tables_linked) > 0)
        {
            foreach ($tables_linked as $key => $tableid) 
            {
                $sortorder = ($key + 1);
                $sql = "INSERT INTO tables_combinations SET 
                    sortorder='$sortorder',
                    combinationid='$id',
                    tableid='$tableid'";
                //echo '<p>'.$sql.'</p>';
                database($sql);
            }
            ?>
            <p style="color: green">Combination Added Successfully</p>
            <ul>
                <li><a style="color: black" href="<?php echo $_SERVER['PHP_SELF']; ?>?addcombination"><button type="button" class="text--white padding-top--small padding-bottom--small">Add Combination</button></a></li>
                <li><a style="color: black" href="<?php echo $_SERVER['PHP_SELF']; ?>"><button type="button" class="text--white padding-top--small padding-bottom--small">Return to List</button></a></li>
                <li><a style="color: black" href="<?php echo $_SERVER['PHP_SELF']; ?>?viewcombination=<?php echo $id; ?>"><button type="button" class="text--white padding-top--small padding-bottom--small">View Tables</button></a></li>
            </ul>
            <?php
        }
        else
        {
            ?>
            <p style="color: red">Combination Added Unsuccessfully, please try again</p><br />
            <a style="color: black" href="<?php echo $_SERVER['PHP_SELF']; ?>"><button type="button" class="text--white padding-top--small padding-bottom--small">Return to List</button></a>
            <?php
        }
    ?>
    <?php } elseif (isset($_POST['editcombinationnow'])) { 
        extract($_POST);
        
        $sql = "UPDATE combinations SET 
            combinationname='$combinationname'
        WHERE combinationid='$editcombinationnow'";
        //echo '<p>'.$sql.'</p>';
        database($sql);

        database("DELETE FROM tables_combinations WHERE combinationid='$editcombinationnow'");

        foreach ($tables_linked as $key => $tableid) 
        {
            $sortorder = ($key + 1);
            $sql = "INSERT INTO tables_combinations SET 
                sortorder='$sortorder',
                combinationid='$editcombinationnow',
                tableid='$tableid'";
            //echo '<p>'.$sql.'</p>';
            database($sql);
        }    
    ?>
        <p style="color: black">Combination Updated</p>
        <a style="color: black" href="<?php echo $_SERVER['PHP_SELF']; ?>"><button type="button" class="text--white padding-top--small padding-bottom--small">Return to List</button></a>
    
    <?php } elseif (isset($_GET['deletecombination'])) { 
        $combinationid = $_GET['deletecombination'];
        if ($combinationid > 0)
        {
            database("DELETE FROM combinations WHERE combinationid='$combinationid'");
            database("DELETE FROM tables_combinations WHERE combinationid='$combinationid'");
            ?>
            <p style="color: red">Combination Deleted</p>
            <a style="color: black" href="<?php echo $_SERVER['PHP_SELF']; ?>"><button type="button" class="text--white padding-top--small padding-bottom--small">Return to List</button></a>
            <?php
        }
    ?>

    <?php } elseif (isset($_GET['viewcombination'])) { 
        $combinationid = $_GET['viewcombination'];
        $result = database("SELECT * FROM combinations WHERE combinationid='$combinationid' LIMIT 1");

        echo '<a style="color: black" href="'.$_SERVER['PHP_SELF'].'"><button type="button" class="text--white padding-top--small padding-bottom--small" style="width:10%">Back</button></a>';
        echo '<h3>'.$result['combinationname'].' tables:<h3>';
    echo "
    <table class='margin-bottom--normal sortable_sortorder'>
        <thead>
            <tr>
                <th>SO</th>
                <th>Table Number</th>
                <th>Min. Covers</th>
                <th>Max. Covers</th>
            </tr>
        </thead>
    <tbody>"
    ;

    $query = "SELECT tables_single.*, tables_combinations.*, tables_combinations.sortorder AS sortorder FROM tables_combinations, tables_single WHERE tables_combinations.combinationid='$combinationid' AND tables_single.table_id=tables_combinations.tableid ORDER BY tables_combinations.sortorder";
    $results = database($query);
    foreach ($results as $row) 
    {
        echo "<tr id='identifyerID-".$row['table_combi_id']."'>";
        echo "<td>" . $row['sortorder'] . "</td>";
        echo "<td>" . $row['table_number'] . "</td>";
        echo "<td>" . $row['minimum_covers'] . "</td>";
        echo "<td>" . $row['maximum_covers'] . "</td>";
        echo "</tr>";
    }

    echo "</tbody></table>";
    echo '<input class="sortable_sortorder_url" name="sortable_sortorder_url" value="ajax.php?request=updateSortorder&amp;update=tables_combinations&amp;identifyer=table_combi_id" type="hidden">';
    ?>

    <?php } else { ?>

    <div class="grid">
        <div class="col col-1-4 vertical-align--middle">
            <h3>Booking Number:</h3>
        </div>
        <div class="col col-1-4 vertical-align--middle">        
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <select onChange="this.form.submit();" name="bookingnumber">
                <?php
                    for ($i=1; $i <= $bookingnumber_max; $i++) 
                    { 
                        echo '<option value="'.$i.'"'.($bookingnumber==$i?' selected="selected"':'').'>'.$i.' guest'.($i>1?'s':'').'</option>';
                    }
                ?>
                </select>
            </form>
        </div>
        <div class="col col-1-4 vertical-align--middle">
            <a href="<?php echo $_SERVER['PHP_SELF']; ?>?table_location=downstairs"><button id="" type="button" class="text--white padding-top--small padding-bottom--small" <?php if ($table_location <> 'downstairs') {echo 'style="background-color:#5c5c5c"';} ?>>Downstairs</button></a>
        </div>
        <div class="col col-1-4 vertical-align--middle">
            <a href="<?php echo $_SERVER['PHP_SELF']; ?>?table_location=upstairs"><button id="" type="button" class="text--white padding-top--small padding-bottom--small" <?php if ($table_location <> 'upstairs') {echo 'style="background-color:#5c5c5c"';} ?>>Upstairs</button></a>
        </div>
    </div>
    <div class="grid">
        <div class="col col-1-2 vertical-align--middle" style="width: 75%;">
            <h2>Combinations for bookings of <?php echo $bookingnumber.' guest'.($bookingnumber>1?'s':'').' '.$table_location; ?></h2>
        </div>
        <div class="col col-1-4 vertical-align--middle">
            <a href="<?php echo $_SERVER['PHP_SELF']; ?>?bookingnumber=<?php echo $bookingnumber; ?>&amp;table_location=<?php echo $table_location; ?>&amp;addcombination"><button id="btn-add-table" type="button" class="text--white padding-top--small padding-bottom--small">Add Combination</button></a>
        </div>    
    </div>
    <?php
    $query = "SELECT * FROM combinations WHERE bookingnumber='$bookingnumber' AND location='$table_location' ORDER BY sortorder,combinationname";
    $results = database($query);
    if (count($results) == 0)
    {
        echo '<h3>No combinations for '.$bookingnumber.' guest'.($bookingnumber>1?'s':'').' '.$table_location.'</h3>';
    }
    else
    {
        echo "
        <table class='margin-bottom--normal sortable_sortorder'>
            <thead>
                <tr>
                    <th>SO</th>
                    <th>Combination</th>
                    <th>View Tables</th>
                    <th>Edit</th>
                </tr>
            </thead>
        <tbody>"
        ;

        foreach ($results as $row) 
        {
            echo "<tr id='identifyerID-".$row['combinationid']."'>";
            echo "<td>" . $row['sortorder'] . "</td>";
            echo "<td>" . $row['combinationname'] . "</td>";
            echo "<td><a style='color:black' href='".$_SERVER['PHP_SELF']."?viewcombination=" . $row['combinationid'] . "'>View</a></td>";
            echo "<td><a class='icon icon--small icon--edit' href='".$_SERVER['PHP_SELF']."?editcombination=" . $row['combinationid'] . "'></a></td>";
            echo "</tr>";
        }

        echo "</tbody></table>";
        echo '<input class="sortable_sortorder_url" name="sortable_sortorder_url" value="ajax.php?request=updateSortorder&amp;update=combinations&amp;identifyer=combinationid" type="hidden">';
    }
    ?>
    <?php } ?>
</div>