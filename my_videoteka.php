<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';
zahtijeva_prijavu();

$korisnik_id = $_SESSION['korisnik_id'];
$poruka = '';

// Ukloni film iz videoteke
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ukloni_id'])) {
    provjeri_csrf();
    $film_id = (int)$_POST['ukloni_id'];
    $stmt = $conn->prepare("DELETE FROM zeljeni_filmovi WHERE korisnik_id = ? AND film_id = ?");
    $stmt->bind_param("ii", $korisnik_id, $film_id);
    $stmt->execute();
    $poruka = "Film je uklonjen iz videoteke.";
}

// Dohvati filmove u videoteci
$stmt = $conn->prepare("
    SELECT f.* FROM filmovi f
    INNER JOIN zeljeni_filmovi zf ON f.id = zf.film_id
    WHERE zf.korisnik_id = ?
    ORDER BY zf.added_at DESC
");
$stmt->bind_param("i", $korisnik_id);
$stmt->execute();
$filmovi = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moja videoteka</title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/nav.css">
    <link rel="stylesheet" href="style/lv4.css">
</head>
<body>
<header><h1>Moja videoteka</h1></header>
<?php include 'includes/nav.php'; ?>

<main>
    <h2>Videoteka korisnika: <?= htmlspecialchars($_SESSION['username']) ?></h2>

    <?php if ($poruka): ?>
        <div class="alert alert-success"><?= htmlspecialchars($poruka) ?></div>
    <?php endif; ?>

    <?php if (empty($filmovi)): ?>
        <div class="alert alert-info">Vaša videoteka je prazna. <a href="index.php">Dodajte filmove</a>.</div>
    <?php else: ?>
        <div class="content">
            <table aria-label="Moja videoteka">
                <thead>
                    <tr>
                        <th>Naslov</th>
                        <th>Žanr</th>
                        <th>Godina</th>
                        <th>Ocjena</th>
                        <th>Akcija</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($filmovi as $film): ?>
                    <tr>
                        <td><a href="film.php?id=<?= $film['id'] ?>"><?= htmlspecialchars($film['naslov']) ?></a></td>
                        <td><?= htmlspecialchars($film['zanr']) ?></td>
                        <td><?= $film['godina'] ?></td>
                        <td>
                            <?php
                            $pune = round($film['ocjena'] / 2);
                            echo str_repeat('★', $pune) . str_repeat('☆', 5 - $pune);
                            echo " ({$film['ocjena']})";
                            ?>
                        </td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                <input type="hidden" name="ukloni_id" value="<?= $film['id'] ?>">
                                <button type="submit" class="btn-ukloni"
                                    onclick="return confirm('Ukloniti film iz videoteke?')">Ukloni</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <p style="margin-top:10px;text-align:center;">Ukupno filmova: <strong><?= count($filmovi) ?></strong></p>
    <?php endif; ?>
</main>
<footer><p>&copy; 2025. Web Programiranje. Sva prava pridržana.</p></footer>
</body>
</html>