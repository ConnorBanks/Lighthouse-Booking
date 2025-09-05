
<?php

require __DIR__ . '/bootstrap.php'; 

$q = strval($_GET['q']);

$con = database('localhost', 'jack', 'hack528618', 'lighthouse-booking');
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}

$sql="SELECT * FROM bookings WHERE first_name LIKE '%$q%'";
$result = database($con,$sql);

echo "<table>
<tr>
<th>First Name</th>
<th>Surname</th>
<th>Telephone</th>
<th>Party Size</th>
</tr>";

foreach ($result as $row) 
{
    echo "<tr>";
    echo "<td>" . $row['first_name'] . "</td>";
    echo "<td>" . $row['last_name'] . "</td>";
    echo "<td>" . $row['telephone'] . "</td>";
    echo "<td>" . $row['party_size'] . "</td>";
    echo "</tr>";
}

echo "</table>";
mysqli_close($con);
?>
