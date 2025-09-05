<?php
/**
 * bootstrap.php — lightweight JSON logger + global error handlers
 * Drop this file into your `bookings/` directory and include it at the
 * very top of every entry script (admin.php, bookingSubmitProcess.php, AJAX endpoints, etc):
 *
 *   require __DIR__ . '/bootstrap.php';
 */

declare(strict_types=1);

// -------- CONFIG --------
$LOG_DIR = __DIR__ . '/_logs';        // Consider moving outside webroot
$APP_NAME = 'lighthouse_bookings';
$ENV = getenv('APP_ENV') ?: 'dev';   // prod|stage|dev

if (!is_dir($LOG_DIR)) { @mkdir($LOG_DIR, 0750, true); }
@chmod($LOG_DIR, 0750);

// -------- REQUEST ID --------
if (!function_exists('lb_request_id')) {
    function lb_request_id(): string {
        static $id = null;
        if ($id) return $id;
        try {
            $id = bin2hex(random_bytes(8)); // 16 hex chars
        } catch (Throwable $e) {
            $id = substr(str_replace(['.', ' '], '', microtime(true) . uniqid('', true)), -16);
        }
        if (!headers_sent()) {
            header('X-Request-Id: ' . $id);
        }
        return $id;
    }
}

// -------- REDACTION --------
if (!function_exists('lb_redact')) {
    function lb_redact(array $data): array {
        $out = [];
        foreach ($data as $k => $v) {
            if (is_array($v)) { $out[$k] = lb_redact($v); continue; }
            if (!is_string($v)) { $out[$k] = $v; continue; }
            $val = $v;
            if (stripos($k, 'password') !== false) { $val = '[REDACTED]'; }
            // Mask emails: keep first char and domain
            if (preg_match('/^[^@\s]@|.+@.+\..+$/', $val) || strpos($val, '@') !== false) {
                $val = preg_replace('/(^.).+(@.+$)/', '$1***$2', $val);
            }
            // Mask phone-like strings
            if (preg_match('/\+?\d[\d\s\-]{6,}/', $val)) {
                $val = '[REDACTED_PHONE]';
            }
            $out[$k] = $val;
        }
        return $out;
    }
}

// -------- LOGGER --------
if (!function_exists('lb_log')) {
    function lb_log(string $level, string $message, array $context = []): void {
        global $LOG_DIR, $APP_NAME, $ENV;
        $file = sprintf('%s/%s-%s.log', $LOG_DIR, $APP_NAME, date('Y-m-d')); // daily rotate
        $record = [
            'ts'     => date('c'),
            'level'  => strtoupper($level),
            'app'    => $APP_NAME,
            'env'    => $ENV,
            'req_id' => lb_request_id(),
            'ip'     => $_SERVER['REMOTE_ADDR'] ?? null,
            'uri'    => $_SERVER['REQUEST_URI'] ?? null,
            'user'   => $_SESSION['username'] ?? null,
            'msg'    => $message,
            'ctx'    => lb_redact($context),
        ];
        // Append as JSON line
        @file_put_contents($file, json_encode($record, JSON_UNESCAPED_SLASHES) . PHP_EOL, FILE_APPEND | LOCK_EX);
        @chmod($file, 0640);
    }
}

// -------- GLOBAL HANDLERS --------
if (!function_exists('lb_set_handlers')) {
    function lb_set_handlers(): void {
        // Convert PHP notices/warnings to exceptions for unified handling
        set_error_handler(function($severity, $message, $file, $line) {
            if (!(error_reporting() & $severity)) {
                return false; // respect @-operator and current error_reporting
            }
            throw new ErrorException($message, 0, $severity, $file, $line);
        });

        set_exception_handler(function($e) {
            $isJson = (stripos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false) ||
                      (stripos($_SERVER['CONTENT_TYPE'] ?? '', 'application/json') !== false) ||
                      (isset($_GET['ajax']) || isset($_POST['ajax']));
            $code = 500;

            lb_log('error', 'Unhandled exception', [
                'type' => get_class($e),
                'msg'  => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace'=> (getenv('APP_ENV') === 'dev') ? $e->getTrace() : null,
            ]);

            http_response_code($code);
            if ($isJson) {
                if (!headers_sent()) header('Content-Type: application/json');
                echo json_encode(['ok' => false, 'error' => 'server_error', 'req_id' => lb_request_id()]);
            } else {
                echo 'An error occurred. Ref: ' . htmlspecialchars(lb_request_id(), ENT_QUOTES, 'UTF-8');
            }
        });

        // Base PHP error ini
        ini_set('log_errors', '1');
        ini_set('display_errors', getenv('APP_ENV') === 'dev' ? '1' : '0'); // never display in prod
        // Optionally direct PHP internal errors:
        // ini_set('error_log', __DIR__ . '/_logs/php-error.log');
    }
}

lb_set_handlers();
lb_request_id(); // generate early

lb_log('debug', 'bootstrap.php loaded', [
    'script' => $_SERVER['SCRIPT_NAME'] ?? null
]);

// -------- EXAMPLES --------
// lb_log('info', 'Bootstrap loaded');
// lb_log('info', 'Booking created', ['booking_id' => 123, 'covers' => 4]);
?>
