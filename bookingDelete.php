<?php require 'header.php'; ?>

<?php

$id = $_GET["id"];
$ln = $_GET["lastName"]; ?>

<div class="container background-image--cover" style="background-image:url('images/wine.jpg')">

	<div class="modal background--grey">
<?php
if(isset($_GET['id']) && isset($_GET['lastName'])){
	echo '<a style="float: right;" href="bookingsClient.php">Back</a>';
	echo '<p>Are you sure you wish to cancel this booking?</p>';
	echo '<a href="'.$_SERVER['PHP_SELF'].'?deletebookingnow&id='.$id.'&lastName='.$ln.'"><button class="text--white padding-top--small padding-bottom--small">Yes, I wish to cancel</button></a>';
	echo '<a href="bookingsClient.php"><button class="text--white padding-top--small padding-bottom--small" style="background:#aaa;">No, please keep my booking</button></a>';
}

if (isset($_GET['deletebookingnow'])) {
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    $ln = isset($_GET['lastName']) ? trim($_GET['lastName']) : '';

    try {
        $pdo = new PDO(
            "mysql:host={$dbhn};dbname={$dbnm};charset=utf8mb4",
            $dbun,
            $dbpw,
            [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
              PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC ]
        );
    } catch (Throwable $e) {
        error_log('DB connect fail: ' . $e->getMessage());
        header("Location: bookingsClient.php?status=dberror");
        exit;
    }

    // 0) Does a booking match?
    $exists = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE booking_id = :id AND last_name = :ln");
    $exists->execute([':id' => $id, ':ln' => $ln]);
    if ((int)$exists->fetchColumn() !== 1) {
        header("Location: bookingsClient.php?status=notfound");
        exit;
    }

    try {
        $pdo->beginTransaction();

        // 1) Copy the row to deletedBookings (column-for-column)
        $ins = $pdo->prepare("
            INSERT INTO deletedBookings
            SELECT * FROM bookings
            WHERE booking_id = :id AND last_name = :ln
            LIMIT 1
        ");
        $ins->execute([':id' => $id, ':ln' => $ln]);

        // 2) Delete the original
        $del = $pdo->prepare("DELETE FROM bookings WHERE booking_id = :id AND last_name = :ln LIMIT 1");
        $del->execute([':id' => $id, ':ln' => $ln]);

        // Safety: ensure we actually deleted one row
        if ($del->rowCount() !== 1) {
            throw new RuntimeException('Delete did not affect 1 row; rolling back.');
        }

        $pdo->commit();
        header("Location: bookingsClient.php?status=cancelled");
        exit;

    } catch (Throwable $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        // Log the exact SQL error for debugging
        error_log('Cancel/archive error: ' . $e->getMessage());
        header("Location: bookingsClient.php?status=archive_failed");
        exit;
    }
}
?>
	</div>
</div>
<?php require 'footer.php'; ?>
