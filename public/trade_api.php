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

$db->exec('CREATE TABLE IF NOT EXISTS trades (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    proposer_id INTEGER NOT NULL,
    receiver_id INTEGER NOT NULL,
    offer_card INTEGER NOT NULL,
    offer_count INTEGER NOT NULL DEFAULT 1,
    want_card INTEGER NOT NULL,
    want_count INTEGER NOT NULL DEFAULT 1,
    status TEXT NOT NULL DEFAULT "proposed",
    proposed_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    responded_at DATETIME,
    FOREIGN KEY (proposer_id) REFERENCES users(id),
    FOREIGN KEY (receiver_id) REFERENCES users(id)
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
$uname = $user['username'];
$action = $input['action'] ?? '';

switch ($action) {

    // === BROWSE: See all offers and needs across all users ===
    case 'browse':
        $result = ['ok' => true, 'offers' => [], 'needs' => [], 'matches' => []];

        // Get all collections with usernames
        $rows = $db->query('SELECT c.user_id, c.cards, u.username FROM collections c JOIN users u ON c.user_id = u.id');
        $allUsers = [];
        while ($row = $rows->fetchArray(SQLITE3_ASSOC)) {
            $cards = json_decode($row['cards'], true);
            if (!is_array($cards) || count($cards) !== 21) continue;
            $allUsers[] = ['uid' => $row['user_id'], 'username' => $row['username'], 'cards' => $cards];
        }

        // Aggregate: who has duplicates (offers) and who needs what
        for ($i = 0; $i < 21; $i++) {
            $cardNum = $i + 1;
            $offers = []; // users who have duplicates
            $needs = [];  // users who need this card

            foreach ($allUsers as $u) {
                $count = $u['cards'][$i];
                if ($count > 1) {
                    $offers[] = ['username' => $u['username'], 'uid' => $u['uid'], 'available' => $count - 1];
                }
                if ($count === 0) {
                    $needs[] = ['username' => $u['username'], 'uid' => $u['uid']];
                }
            }

            if (!empty($offers)) {
                $result['offers'][] = ['card' => $cardNum, 'users' => $offers];
            }
            if (!empty($needs)) {
                $result['needs'][] = ['card' => $cardNum, 'users' => $needs];
            }
        }

        // Find matches: User A needs card X (has 0), User B has duplicates of X.
        // AND User B needs card Y (has 0), User A has duplicates of Y.
        foreach ($allUsers as $a) {
            foreach ($allUsers as $b) {
                if ($a['uid'] >= $b['uid']) continue; // avoid duplicates and self
                $matchAtoB = []; // A can give to B
                $matchBtoA = []; // B can give to A

                for ($i = 0; $i < 21; $i++) {
                    // A has dups of card i+1, B needs it
                    if ($a['cards'][$i] > 1 && $b['cards'][$i] === 0) {
                        $matchAtoB[] = $i + 1;
                    }
                    // B has dups of card i+1, A needs it
                    if ($b['cards'][$i] > 1 && $a['cards'][$i] === 0) {
                        $matchBtoA[] = $i + 1;
                    }
                }

                if (!empty($matchAtoB) && !empty($matchBtoA)) {
                    $result['matches'][] = [
                        'user_a' => $a['username'],
                        'user_a_uid' => $a['uid'],
                        'user_b' => $b['username'],
                        'user_b_uid' => $b['uid'],
                        'a_gives' => $matchAtoB,
                        'b_gives' => $matchBtoA
                    ];
                }
            }
        }

        echo json_encode($result);
        break;

    // === PROPOSE: Create a trade proposal ===
    case 'propose':
        $receiver_uid = intval($input['receiver_id'] ?? 0);
        $offer_card = intval($input['offer_card'] ?? 0);
        $want_card = intval($input['want_card'] ?? 0);
        $offer_count = intval($input['offer_count'] ?? 1);
        $want_count = intval($input['want_count'] ?? 1);

        if ($receiver_uid < 1 || $offer_card < 1 || $offer_card > 21 || $want_card < 1 || $want_card > 21) {
            echo json_encode(['ok' => false, 'error' => 'Ungültige Parameter']);
            exit;
        }
        if ($receiver_uid === $uid) {
            echo json_encode(['ok' => false, 'error' => 'Kann nicht mit dir selbst tauschen']);
            exit;
        }
        if ($offer_count < 1 || $want_count < 1 || $offer_count > 210 || $want_count > 210) {
            echo json_encode(['ok' => false, 'error' => 'Ungültige Anzahl']);
            exit;
        }

        // Verify proposer actually has the card(s) to offer
        $stmt = $db->prepare('SELECT cards FROM collections WHERE user_id = ?');
        $stmt->bindValue(1, $uid, SQLITE3_INTEGER);
        $myCards = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
        if (!$myCards) {
            echo json_encode(['ok' => false, 'error' => 'Keine Sammlung gefunden']);
            exit;
        }
        $myC = json_decode($myCards['cards'], true);
        if ($myC[$offer_card - 1] < $offer_count) {
            echo json_encode(['ok' => false, 'error' => 'Du hast diese Karte nicht oft genug']);
            exit;
        }

        // Verify receiver exists and has the wanted card(s)
        $stmt2 = $db->prepare('SELECT cards FROM collections WHERE user_id = ?');
        $stmt2->bindValue(1, $receiver_uid, SQLITE3_INTEGER);
        $theirCards = $stmt2->execute()->fetchArray(SQLITE3_ASSOC);
        if (!$theirCards) {
            echo json_encode(['ok' => false, 'error' => 'Empfänger nicht gefunden']);
            exit;
        }
        $theirC = json_decode($theirCards['cards'], true);
        if ($theirC[$want_card - 1] < $want_count) {
            echo json_encode(['ok' => false, 'error' => 'Empfänger hat diese Karte nicht']);
            exit;
        }

        // Check for existing pending trade between same users for same cards
        $check = $db->prepare('SELECT id FROM trades WHERE proposer_id = ? AND receiver_id = ? AND offer_card = ? AND want_card = ? AND status = "proposed"');
        $check->bindValue(1, $uid, SQLITE3_INTEGER);
        $check->bindValue(2, $receiver_uid, SQLITE3_INTEGER);
        $check->bindValue(3, $offer_card, SQLITE3_INTEGER);
        $check->bindValue(4, $want_card, SQLITE3_INTEGER);
        if ($check->execute()->fetchArray()) {
            echo json_encode(['ok' => false, 'error' => 'Tausch bereits vorgeschlagen']);
            exit;
        }

        $ins = $db->prepare('INSERT INTO trades (proposer_id, receiver_id, offer_card, offer_count, want_card, want_count) VALUES (?, ?, ?, ?, ?, ?)');
        $ins->bindValue(1, $uid, SQLITE3_INTEGER);
        $ins->bindValue(2, $receiver_uid, SQLITE3_INTEGER);
        $ins->bindValue(3, $offer_card, SQLITE3_INTEGER);
        $ins->bindValue(4, $offer_count, SQLITE3_INTEGER);
        $ins->bindValue(5, $want_card, SQLITE3_INTEGER);
        $ins->bindValue(6, $want_count, SQLITE3_INTEGER);
        $ins->execute();

        echo json_encode(['ok' => true, 'trade_id' => $db->lastInsertRowID()]);
        break;

    // === MY TRADES: List all trades involving this user ===
    case 'my-trades':
        $trades = ['sent' => [], 'received' => []];

        // Trades I proposed
        $sent = $db->query("SELECT t.id, t.receiver_id, u.username as receiver, t.offer_card, t.offer_count, t.want_card, t.want_count, t.status, t.proposed_at, t.responded_at FROM trades t JOIN users u ON t.receiver_id = u.id WHERE t.proposer_id = $uid ORDER BY t.proposed_at DESC");
        while ($row = $sent->fetchArray(SQLITE3_ASSOC)) {
            $trades['sent'][] = $row;
        }

        // Trades I received
        $recv = $db->query("SELECT t.id, t.proposer_id, u.username as proposer, t.offer_card, t.offer_count, t.want_card, t.want_count, t.status, t.proposed_at, t.responded_at FROM trades t JOIN users u ON t.proposer_id = u.id WHERE t.receiver_id = $uid ORDER BY t.proposed_at DESC");
        while ($row = $recv->fetchArray(SQLITE3_ASSOC)) {
            $trades['received'][] = $row;
        }

        echo json_encode(['ok' => true, 'trades' => $trades]);
        break;

    // === RESPOND: Accept or reject a trade ===
    case 'respond':
        $trade_id = intval($input['trade_id'] ?? 0);
        $response = $input['response'] ?? '';

        if ($trade_id < 1 || !in_array($response, ['accepted', 'rejected'])) {
            echo json_encode(['ok' => false, 'error' => 'Ungültige Parameter']);
            exit;
        }

        // Verify this trade is addressed to current user
        $stmt = $db->prepare('SELECT id, proposer_id, receiver_id, offer_card, offer_count, want_card, want_count, status FROM trades WHERE id = ?');
        $stmt->bindValue(1, $trade_id, SQLITE3_INTEGER);
        $trade = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

        if (!$trade) {
            echo json_encode(['ok' => false, 'error' => 'Tausch nicht gefunden']);
            exit;
        }
        if ($trade['receiver_id'] !== $uid) {
            echo json_encode(['ok' => false, 'error' => 'Nicht berechtigt']);
            exit;
        }
        if ($trade['status'] !== 'proposed') {
            echo json_encode(['ok' => false, 'error' => 'Tausch bereits beantwortet']);
            exit;
        }

        if ($response === 'accepted') {
            // Execute the trade: swap cards between users
            $pid = $trade['proposer_id'];
            $rid = $trade['receiver_id'];
            $oc = $trade['offer_card']; // card proposer gives
            $oc_n = $trade['offer_count'];
            $wc = $trade['want_card'];  // card receiver gives (= proposer wants)
            $wc_n = $trade['want_count'];

            // Get both collections
            $s1 = $db->prepare('SELECT cards FROM collections WHERE user_id = ?');
            $s1->bindValue(1, $pid, SQLITE3_INTEGER);
            $pCards = json_decode($s1->execute()->fetchArray(SQLITE3_ASSOC)['cards'], true);

            $s2 = $db->prepare('SELECT cards FROM collections WHERE user_id = ?');
            $s2->bindValue(1, $rid, SQLITE3_INTEGER);
            $rCards = json_decode($s2->execute()->fetchArray(SQLITE3_ASSOC)['cards'], true);

            // Verify both still have the cards
            if ($pCards[$oc - 1] < $oc_n || $rCards[$wc - 1] < $wc_n) {
                // Mark as failed
                $upd = $db->prepare('UPDATE trades SET status = "failed", responded_at = CURRENT_TIMESTAMP WHERE id = ?');
                $upd->bindValue(1, $trade_id, SQLITE3_INTEGER);
                $upd->execute();
                echo json_encode(['ok' => false, 'error' => 'Karten nicht mehr verfügbar — Tausch fehlgeschlagen']);
                exit;
            }

            // Swap
            $pCards[$oc - 1] -= $oc_n;
            $pCards[$wc - 1] += $wc_n;
            $rCards[$wc - 1] -= $wc_n;
            $rCards[$oc - 1] += $oc_n;

            // Save both
            $u1 = $db->prepare('UPDATE collections SET cards = ?, updated_at = CURRENT_TIMESTAMP WHERE user_id = ?');
            $u1->bindValue(1, json_encode($pCards), SQLITE3_TEXT);
            $u1->bindValue(2, $pid, SQLITE3_INTEGER);
            $u1->execute();

            $u2 = $db->prepare('UPDATE collections SET cards = ?, updated_at = CURRENT_TIMESTAMP WHERE user_id = ?');
            $u2->bindValue(1, json_encode($rCards), SQLITE3_TEXT);
            $u2->bindValue(2, $rid, SQLITE3_INTEGER);
            $u2->execute();
        }

        // Update trade status
        $upd = $db->prepare('UPDATE trades SET status = ?, responded_at = CURRENT_TIMESTAMP WHERE id = ?');
        $upd->bindValue(1, $response === 'accepted' ? 'completed' : 'rejected', SQLITE3_TEXT);
        $upd->bindValue(2, $trade_id, SQLITE3_INTEGER);
        $upd->execute();

        echo json_encode(['ok' => true, 'status' => $response === 'accepted' ? 'completed' : 'rejected']);
        break;

    // === CANCEL: Cancel a proposed trade ===
    case 'cancel':
        $trade_id = intval($input['trade_id'] ?? 0);
        if ($trade_id < 1) {
            echo json_encode(['ok' => false, 'error' => 'Ungültige ID']);
            exit;
        }
        $stmt = $db->prepare('SELECT proposer_id, status FROM trades WHERE id = ?');
        $stmt->bindValue(1, $trade_id, SQLITE3_INTEGER);
        $trade = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
        if (!$trade || $trade['proposer_id'] !== $uid || $trade['status'] !== 'proposed') {
            echo json_encode(['ok' => false, 'error' => 'Nicht möglich']);
            exit;
        }
        $upd = $db->prepare('UPDATE trades SET status = "cancelled", responded_at = CURRENT_TIMESTAMP WHERE id = ?');
        $upd->bindValue(1, $trade_id, SQLITE3_INTEGER);
        $upd->execute();
        echo json_encode(['ok' => true]);
        break;

    default:
        echo json_encode(['ok' => false, 'error' => 'Ungültige Aktion']);
}
?>