<?php 
// Set the environment manually for now
define('APP_ENV', 'development');

$dbhn = 'localhost';
$dbun = 'aldelighthouse_bookingdev';
$dbpw = 'mvZ8WAKeRdAhFGv9RhK'; // 4q#K)k-ZZU+#
$dbnm = 'aldelighthouse_bookingdev';

$dbinc =
	array(
		'default' => $dbun,
		$dbun => 
		array(
			'dbhn' => $dbhn,
			'dbun' => $dbun,
			'dbpw' => $dbpw
		)
	)
;

// --- DEV SAFETY GUARDS --- //
if (APP_ENV === 'development') {
    // Prevent real emails from sending
    ini_set('sendmail_path', '/bin/true');

    // Block live payment keys
    if (defined('STRIPE_KEY') && strpos(STRIPE_KEY, 'sk_live_') === 0) {
        throw new RuntimeException('Live Stripe key detected in dev!');
    }

    // Tripwire: make sure we’re on the dev DB
    try {
        $pdo = new PDO(
            "mysql:host={$dbhn};dbname={$dbnm};charset=utf8mb4",
            $dbun,
            $dbpw
        );
        $currentDb = $pdo->query('SELECT DATABASE()')->fetchColumn();
        if ($currentDb !== $dbnm) {
            throw new RuntimeException('Connected to wrong database: ' . $currentDb);
        }
    } catch (Exception $e) {
        die('DB connection check failed: ' . $e->getMessage());
    }
}


?>