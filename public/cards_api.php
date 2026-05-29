<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('X-Content-Type-Options: nosniff');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit; }

$db_path = __DIR__ . '/../data/toxicbooster.db';
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

$db->exec('CREATE TABLE IF NOT EXISTS collections (
    user_id INTEGER PRIMARY KEY,
    cards TEXT NOT NULL,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
)');

$input = json_decode(file_get_contents('php://input'), true);
$token = $input['token'] ?? '';

// Verify session
$stmt = $db->prepare('SELECT s.user_id, u.username FROM sessions s JOIN users u ON s.user_id = u.id WHERE s.token = ? AND s.expires_at > datetime("now")');
$stmt->bindValue(1, $token, SQLITE3_TEXT);
$user = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

if (!$user) {
    echo json_encode(['ok' => false, 'error' => 'Nicht eingeloggt']);
    exit;
}

$uid = $user['user_id'];
$action = $input['action'] ?? '';

switch ($action) {
    case 'load':
        $stmt = $db->prepare('SELECT cards FROM collections WHERE user_id = ?');
        $stmt->bindValue(1, $uid, SQLITE3_INTEGER);
        $row = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
        if ($row) {
            $decoded = json_decode($row['cards']);
            echo json_encode(['ok' => true, 'cards' => $decoded, 'username' => $user['username']]);
        } else {
            $default = array_fill(0, 21, 0);
            $ins = $db->prepare('INSERT INTO collections (user_id, cards) VALUES (?, ?)');
            $ins->bindValue(1, $uid, SQLITE3_INTEGER);
            $ins->bindValue(2, json_encode($default), SQLITE3_TEXT);
            $ins->execute();
            echo json_encode(['ok' => true, 'cards' => $default, 'username' => $user['username']]);
        }
        break;

    case 'save':
        $cards = $input['cards'] ?? null;
        if (!is_array($cards) || count($cards) !== 21) {
            echo json_encode(['ok' => false, 'error' => 'Ungültige Daten']);
            exit;
        }
        foreach ($cards as $c) {
            if (!is_int($c) || $c < 0 || $c > 210) {
                echo json_encode(['ok' => false, 'error' => 'Ungültiger Wert']);
                exit;
            }
        }
        $stmt = $db->prepare('INSERT OR REPLACE INTO collections (user_id, cards, updated_at) VALUES (?, ?, CURRENT_TIMESTAMP)');
        $stmt->bindValue(1, $uid, SQLITE3_INTEGER);
        $stmt->bindValue(2, json_encode($cards), SQLITE3_TEXT);
        $stmt->execute();
        echo json_encode(['ok' => true]);
        break;

    default:
        echo json_encode(['ok' => false, 'error' => 'Ungültige Aktion']);
}
?>