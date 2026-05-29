<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('X-Content-Type-Options: nosniff');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit; }

// Rate limiting via temp file (simple approach for shared hosting)
$rl_file = sys_get_temp_dir() . '/tb_rl_' . md5($_SERVER['REMOTE_ADDR'] ?? 'unknown');
$rl_now = time();
$rl_data = [];
if (file_exists($rl_file)) {
    $rl_data = json_decode(file_get_contents($rl_file), true) ?: [];
    // Clean entries older than 5 min
    $rl_data = array_filter($rl_data, fn($t) => $rl_now - $t < 300);
}
// Max 10 attempts per 5 min
if (count($rl_data) >= 10) {
    echo json_encode(['ok' => false, 'error' => 'Zu viele Versuche. Warte kurz.']);
    exit;
}

$db_path = __DIR__ . '/../data/toxicbooster.db';
$data_dir = dirname($db_path);
if (!is_dir($data_dir)) mkdir($data_dir, 0755, true);

$db = new SQLite3($db_path);
$db->busyTimeout(5000);
$db->exec('PRAGMA journal_mode=WAL');

$db->exec('CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE NOT NULL,
    password_hash TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)');

$db->exec('CREATE TABLE IF NOT EXISTS sessions (
    token TEXT PRIMARY KEY,
    user_id INTEGER NOT NULL,
    ip TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    expires_at DATETIME,
    FOREIGN KEY (user_id) REFERENCES users(id)
)');

$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? '';

switch ($action) {
    case 'register':
    case 'login':
        // Rate limit tracking
        $rl_data[] = $rl_now;
        file_put_contents($rl_file, json_encode($rl_data));

        $username = trim($input['username'] ?? '');
        $password = $input['password'] ?? '';

        if (strlen($username) < 3 || strlen($username) > 20) {
            echo json_encode(['ok' => false, 'error' => 'Username: 3-20 Zeichen']);
            exit;
        }
        if (!preg_match('/^[a-zA-Z0-9_äöüÄÖÜß]+$/', $username)) {
            echo json_encode(['ok' => false, 'error' => 'Nur Buchstaben, Zahlen und _']);
            exit;
        }
        if (strlen($password) < 4) {
            echo json_encode(['ok' => false, 'error' => 'Passwort: mind. 4 Zeichen']);
            exit;
        }

        $stmt = $db->prepare('SELECT id, password_hash FROM users WHERE username = ?');
        $stmt->bindValue(1, $username, SQLITE3_TEXT);
        $row = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

        if ($action === 'register') {
            if ($row) {
                echo json_encode(['ok' => false, 'error' => 'Username schon vergeben']);
                exit;
            }
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $ins = $db->prepare('INSERT INTO users (username, password_hash) VALUES (?, ?)');
            $ins->bindValue(1, $username, SQLITE3_TEXT);
            $ins->bindValue(2, $hash, SQLITE3_TEXT);
            $ins->execute();
            $uid = $db->lastInsertRowID();
        } else {
            if (!$row || !password_verify($password, $row['password_hash'])) {
                echo json_encode(['ok' => false, 'error' => 'Falsche Zugangsdaten']);
                exit;
            }
            $uid = $row['id'];
        }

        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', time() + 86400 * 30);
        $ip = $_SERVER['REMOTE_ADDR'] ?? null;

        $ins = $db->prepare('INSERT INTO sessions (token, user_id, ip, expires_at) VALUES (?, ?, ?, ?)');
        $ins->bindValue(1, $token, SQLITE3_TEXT);
        $ins->bindValue(2, $uid, SQLITE3_INTEGER);
        $ins->bindValue(3, $ip, SQLITE3_TEXT);
        $ins->bindValue(4, $expires, SQLITE3_TEXT);
        $ins->execute();

        echo json_encode(['ok' => true, 'token' => $token, 'username' => $username, 'user_id' => $uid]);
        break;

    case 'logout':
        $token = $input['token'] ?? '';
        if ($token) {
            $del = $db->prepare('DELETE FROM sessions WHERE token = ?');
            $del->bindValue(1, $token, SQLITE3_TEXT);
            $del->execute();
        }
        echo json_encode(['ok' => true]);
        break;

    case 'verify':
        $token = $input['token'] ?? '';
        $stmt = $db->prepare('SELECT s.user_id, u.username FROM sessions s JOIN users u ON s.user_id = u.id WHERE s.token = ? AND s.expires_at > datetime("now")');
        $stmt->bindValue(1, $token, SQLITE3_TEXT);
        $row = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
        echo $row
            ? json_encode(['ok' => true, 'user_id' => $row['user_id'], 'username' => $row['username']])
            : json_encode(['ok' => false, 'error' => 'Session abgelaufen']);
        break;

    default:
        echo json_encode(['ok' => false, 'error' => 'Ungültige Aktion']);
}
?>