<?php
// api/filmovi.php - JSON API za dohvat filmova s filterima
header('Content-Type: application/json');
require_once '../includes/db.php';

$naziv     = trim($_GET['naziv'] ?? '');
$zanr      = trim($_GET['zanr'] ?? '');
$zemlja    = trim($_GET['zemlja'] ?? '');
$min_ocjena = (float)($_GET['min_ocjena'] ?? 0);
$sort      = $_GET['sort'] ?? '';

$where = ["1=1"];
$params = [];
$types = '';

if ($naziv !== '') {
    $where[] = "naslov LIKE ?";
    $params[] = "%$naziv%";
    $types .= 's';
}
if ($zanr !== '') {
    $where[] = "zanr LIKE ?";
    $params[] = "%$zanr%";
    $types .= 's';
}
if ($zemlja !== '') {
    $where[] = "zemlja = ?";
    $params[] = $zemlja;
    $types .= 's';
}
if ($min_ocjena > 0) {
    $where[] = "ocjena >= ?";
    $params[] = $min_ocjena;
    $types .= 'd';
}

$order = match($sort) {
    'naslov_asc'   => 'naslov ASC',
    'naslov_desc'  => 'naslov DESC',
    'ocjena_desc'  => 'ocjena DESC',
    'ocjena_asc'   => 'ocjena ASC',
    'godina_desc'  => 'godina DESC',
    'godina_asc'   => 'godina ASC',
    default        => 'id ASC'
};

$sql = "SELECT * FROM filmovi WHERE " . implode(' AND ', $where) . " ORDER BY $order";
$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$filmovi = [];
while ($row = $result->fetch_assoc()) {
    $filmovi[] = $row;
}

echo json_encode($filmovi, JSON_UNESCAPED_UNICODE);