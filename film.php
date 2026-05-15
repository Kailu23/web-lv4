<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: index.php'); exit; }

$stmt = $conn->prepare("SELECT * FROM filmovi WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$film = $stmt->get_result()->fetch_assoc();
if (!$film) { header('Location: index.php'); exit; }

$poruka = '';
$upozorenje = '';

// Dodaj u videoteku
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dodaj_videoteka']) && je_prijavljen()) {
    provjeri_csrf();
    $korisnik_id = $_SESSION['korisnik_id'];
    $stmt2 = $conn->prepare("INSERT IGNORE INTO zeljeni_filmovi (korisnik_id, film_id) VALUES (?, ?)");
    $stmt2->bind_param("ii", $korisnik_id, $id);
    $stmt2->execute();
    if ($stmt2->affected_rows > 0) {
        $poruka = "Film je dodan u vašu videoteku!";
        if ((float)$film['ocjena'] < 5.0) {
            $upozorenje = "Ovaj film ima nisku ocjenu ({$film['ocjena']}) – jeste li sigurni da ga želite dodati?";
        }
    } else {
        $poruka = "Film je već u vašoj videoteci.";
    }
}
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($film['naslov']) ?> - Detalji</title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/nav.css">
    <link rel="stylesheet" href="style/lv4.css">
</head>
<body>
<header><h1>Detalji filma</h1></header>
<?php include 'includes/nav.php'; ?>

<main>
    <div class="film-detalji">
        <?php if ($upozorenje): ?>
            <div class="alert alert-warning">⚠️ <?= htmlspecialchars($upozorenje) ?></div>
        <?php endif; ?>
        <?php if ($poruka): ?>
            <div class="alert alert-success"><?= htmlspecialchars($poruka) ?></div>
        <?php endif; ?>

        <h2><?= htmlspecialchars($film['naslov']) ?></h2>
        <table class="detalji-tablica">
            <tr><th>Žanr</th><td><?= htmlspecialchars($film['zanr']) ?></td></tr>
            <tr><th>Godina</th><td><?= $film['godina'] ?></td></tr>
            <tr><th>Trajanje</th><td><?= $film['trajanje_min'] ?> minuta</td></tr>
            <tr><th>Ocjena</th><td>
                <?php
                $pune = round($film['ocjena'] / 2);
                echo str_repeat('★', $pune) . str_repeat('☆', 5 - $pune);
                echo " ({$film['ocjena']}/10)";
                ?>
            </td></tr>
            <tr><th>Redatelj</th><td><?= htmlspecialchars($film['reziser'] ?? '-') ?></td></tr>
            <tr><th>Zemlja</th><td><?= htmlspecialchars($film['zemlja'] ?? '-') ?></td></tr>
            <?php if ($film['opis']): ?>
            <tr><th>Opis</th><td><?= htmlspecialchars($film['opis']) ?></td></tr>
            <?php endif; ?>
        </table>

        <?php if (je_prijavljen()): ?>
        <form method="POST" style="margin-top:20px;">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            <button type="submit" name="dodaj_videoteka">+ Dodaj u videoteku</button>
        </form>
        <?php else: ?>
            <p><a href="login.php">Prijavite se</a> da biste dodali film u videoteku.</p>
        <?php endif; ?>

        <p style="margin-top:20px;"><a href="index.php">← Natrag na popis</a></p>
    </div>
</main>
<footer><p>&copy; 2025. Web Programiranje. Sva prava pridržana.</p></footer>
</body>
</html>