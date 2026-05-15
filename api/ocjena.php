<?php
// api/ocjena.php - AJAX endpoint za ocjenjivanje slika
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

$slika_id = (int)($_POST['slika_id'] ?? 0);
$ocjena   = (int)($_POST['ocjena'] ?? 0);
$uid      = $_SESSION['korisnik_id'];

if ($slika_id <= 0 || $ocjena < 1 || $ocjena > 5) {
    echo json_encode(['greska' => 'Neispravni podaci.']);
    exit;
}

// Provjeri postoji li slika
$stmt = $conn->prepare("SELECT id FROM slike WHERE id = ?");
$stmt->bind_param("i", $slika_id);
$stmt->execute();
if (!$stmt->get_result()->fetch_assoc()) {
    echo json_encode(['greska' => 'Slika nije pronađena.']);
    exit;
}

// Upsert ocjenu
$stmt2 = $conn->prepare("
    INSERT INTO ocjene (id_korisnik, id_slika, ocjena)
    VALUES (?, ?, ?)
    ON DUPLICATE KEY UPDATE ocjena = VALUES(ocjena), vrijeme_ocjene = NOW()
");
$stmt2->bind_param("iii", $uid, $slika_id, $ocjena);
$stmt2->execute();

// Vrati novu prosječnu ocjenu
$stmt3 = $conn->prepare("SELECT AVG(ocjena) as avg, COUNT(*) as cnt FROM ocjene WHERE id_slika = ?");
$stmt3->bind_param("i", $slika_id);
$stmt3->execute();
$data = $stmt3->get_result()->fetch_assoc();

echo json_encode([
    'uspjeh'      => true,
    'prosjecna'   => round($data['avg'], 1),
    'broj_ocjena' => (int)$data['cnt']
]);