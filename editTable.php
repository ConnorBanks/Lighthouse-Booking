<?php require 'headerAdmin.php'; ?>

    <?php require 'navigationAdmin.php'; ?>

    <div class="background--white max-width--1000 margin-auto--left margin-auto--right padding-top--normal padding-bottom--normal padding-left--tiny padding-right--tiny">

    <h2>Edit Table Settings</h2>

<?php

$id = $_GET["ID"];

//$conn = new PDO("mysql:host=localhost;dbname=lighthouse-booking","jack", "hack528618");

$query = "SELECT * FROM tables_single WHERE table_id = '$id'";

$results = database($query);

echo "
<table class='margin-bottom--normal'>
    <thead>
        <tr>
            <th>Table Field</th>
            <th>Table Value</th>
        </tr>
    </thead>
<tbody>"
;

foreach ($results as $result) 
{
    echo "<tr><td>Table ID</td> <td>" . $row['table_id'] . "</td></tr>";
    echo "<tr><td>Table Number</td> <td>" . $row['table_number'] . "</td></tr>";
    echo "<tr><td>Type</td> <td>" . $row['type'] . "</td></tr>";
    echo "<tr><td>Minimum Covers</td> <td>" . $row['minimum_covers'] . "</td></tr>";
    echo "<tr><td>Maximum Covers</td> <td>" . $row['maximum_covers'] . "</td></tr>";
    echo "<tr><td>Location</td> <td>" . $row['location'] . "</td></tr>";
    echo "<tr><td>Available for Merge?</td> <td>" . $row['available_merge'] . "</td></tr>";
}

echo "</tbody></table>";

?>

<?php require 'footer.php'; ?>
