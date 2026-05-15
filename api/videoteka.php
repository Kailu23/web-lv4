<?php
// api/videoteka.php - Dodavanje filmova u osobnu videoteku
session_start();
header('Content-Type: application/json');
require_once '../includes/db.php';
require_once '../includes/auth.php';

if (!je_prijavljen()) {
    echo json_encode(['greska' => 'Niste prijavljeni.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['greska' => 'Neispravan zahtjev.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$film_ids = $input['film_ids'] ?? [];
$korisnik_id = $_SESSION['korisnik_id'];

if (empty($film_ids)) {
    echo json_encode(['poruka' => 'Nema filmova za dodati.']);
    exit;
}

$dodano = 0;
$upozorenja = [];

foreach ($film_ids as $film_id) {
    $film_id = (int)$film_id;

    // Provjeri je li film u bazi
    $stmt = $conn->prepare("SELECT id, naslov, ocjena FROM filmovi WHERE id = ?");
    $stmt->bind_param("i", $film_id);
    $stmt->execute();
    $film = $stmt->get_result()->fetch_assoc();
    if (!$film) continue;

    // Upozorenje za nisku ocjenu
    if ((float)$film['ocjena'] < 5.0) {
        $upozorenja[] = "Film '{$film['naslov']}' ima nisku ocjenu ({$film['ocjena']}).";
    }

    // Pokušaj insert, ignoriraj duplikat
    $stmt2 = $conn->prepare("INSERT IGNORE INTO zeljeni_filmovi (korisnik_id, film_id) VALUES (?, ?)");
    $stmt2->bind_param("ii", $korisnik_id, $film_id);
    if ($stmt2->execute() && $stmt2->affected_rows > 0) {
        $dodano++;
    }
}

$poruka = "Dodano $dodano film(ova) u vašu videoteku!";
if (!empty($upozorenja)) {
    $poruka .= "\n⚠️ " . implode("\n⚠️ ", $upozorenja);
}

echo json_encode(['poruka' => $poruka, 'dodano' => $dodano], JSON_UNESCAPED_UNICODE);