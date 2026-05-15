<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: gallery.php'); exit; }

// Dohvati sliku
$stmt = $conn->prepare("SELECT * FROM slike WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$slika = $stmt->get_result()->fetch_assoc();
if (!$slika) { header('Location: gallery.php'); exit; }

$poruka = '';

// Ocjenjivanje putem forme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && je_prijavljen()) {
    provjeri_csrf();
    $ocjena = (int)($_POST['ocjena'] ?? 0);
    $komentar = trim($_POST['komentar'] ?? '');
    $uid = $_SESSION['korisnik_id'];

    if ($ocjena >= 1 && $ocjena <= 5) {
        $stmt2 = $conn->prepare("
            INSERT INTO ocjene (id_korisnik, id_slika, ocjena, komentar)
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE ocjena = VALUES(ocjena), komentar = VALUES(komentar), vrijeme_ocjene = NOW()
        ");
        $stmt2->bind_param("iiis", $uid, $id, $ocjena, $komentar);
        $stmt2->execute();
        $poruka = "Ocjena je spremljena!";
    }
}

// Prosječna ocjena
$avg_stmt = $conn->prepare("SELECT AVG(ocjena) as avg, COUNT(*) as cnt FROM ocjene WHERE id_slika = ?");
$avg_stmt->bind_param("i", $id);
$avg_stmt->execute();
$avg_data = $avg_stmt->get_result()->fetch_assoc();

// Vlastita ocjena
$moja_ocjena = 0;
$moj_komentar = '';
if (je_prijavljen()) {
    $uid = $_SESSION['korisnik_id'];
    $stmt3 = $conn->prepare("SELECT ocjena, komentar FROM ocjene WHERE id_korisnik = ? AND id_slika = ?");
    $stmt3->bind_param("ii", $uid, $id);
    $stmt3->execute();
    $moja = $stmt3->get_result()->fetch_assoc();
    if ($moja) {
        $moja_ocjena = $moja['ocjena'];
        $moj_komentar = $moja['komentar'];
    }
}

// Svi komentari
$komentari_stmt = $conn->prepare("
    SELECT o.ocjena, o.komentar, o.vrijeme_ocjene, k.username
    FROM ocjene o
    JOIN korisnici k ON o.id_korisnik = k.id
    WHERE o.id_slika = ? AND o.komentar != ''
    ORDER BY o.vrijeme_ocjene DESC
");
$komentari_stmt->bind_param("i", $id);
$komentari_stmt->execute();
$komentari = $komentari_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($slika['opis'] ?? $slika['naziv_datoteke']) ?></title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/nav.css">
    <link rel="stylesheet" href="style/style_slike.css">
    <link rel="stylesheet" href="style/lv4.css">
</head>
<body>
<header><h1>Fotografija</h1></header>
<?php include 'includes/nav.php'; ?>

<main>
    <div class="photo-detalji">
        <?php if ($poruka): ?>
            <div class="alert alert-success"><?= htmlspecialchars($poruka) ?></div>
        <?php endif; ?>

        <img src="<?= htmlspecialchars($slika['putanja']) ?>"
             alt="<?= htmlspecialchars($slika['opis'] ?? $slika['naziv_datoteke']) ?>"
             style="max-width:100%;border-radius:10px;">

        <h2><?= htmlspecialchars($slika['opis'] ?? $slika['naziv_datoteke']) ?></h2>

        <!-- Prosječna ocjena IMDb stil -->
        <div class="imdb-ocjena">
            <?php
            $avg = round($avg_data['avg'] ?? 0);
            echo str_repeat('★', $avg) . str_repeat('☆', 5 - $avg);
            $prosjecna = number_format($avg_data['avg'] ?? 0, 1);
            echo " <strong>$prosjecna / 5</strong> ({$avg_data['cnt']} ocjena)";
            ?>
        </div>

        <!-- Forma za ocjenjivanje -->
        <?php if (je_prijavljen()): ?>
        <div class="ocjena-forma-detalji">
            <h3>Vaša ocjena</h3>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <div class="zvjezdice-forma" id="zvjezdice">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <span class="zvjezdica-velika <?= $i <= $moja_ocjena ? 'aktivna' : '' ?>"
                              data-ocjena="<?= $i ?>">★</span>
                    <?php endfor; ?>
                    <input type="hidden" name="ocjena" id="ocjena-input" value="<?= $moja_ocjena ?>">
                </div>
                <label for="komentar">Komentar (opcionalno)</label>
                <textarea id="komentar" name="komentar" rows="3" style="width:100%;padding:8px;"><?= htmlspecialchars($moj_komentar) ?></textarea>
                <button type="submit" style="margin-top:10px;">Spremi ocjenu</button>
            </form>
        </div>
        <?php else: ?>
            <p><a href="login.php">Prijavite se</a> za ocjenjivanje.</p>
        <?php endif; ?>

        <!-- Komentari -->
        <?php if (!empty($komentari)): ?>
        <div class="komentari">
            <h3>Komentari</h3>
            <?php foreach ($komentari as $k): ?>
            <div class="komentar">
                <strong><?= htmlspecialchars($k['username']) ?></strong>
                <span class="komentar-ocjena">
                    <?php echo str_repeat('★', $k['ocjena']) . str_repeat('☆', 5 - $k['ocjena']); ?>
                </span>
                <span class="komentar-datum"><?= date('d.m.Y.', strtotime($k['vrijeme_ocjene'])) ?></span>
                <p><?= htmlspecialchars($k['komentar']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <p><a href="gallery.php">← Natrag na galeriju</a></p>
    </div>
</main>
<footer><p>&copy; 2025. Web Programiranje. Sva prava pridržana.</p></footer>
<script>
const zvjezdice = document.querySelectorAll('.zvjezdica-velika');
const input = document.getElementById('ocjena-input');
if (zvjezdice.length) {
    zvjezdice.forEach(z => {
        z.addEventListener('mouseover', () => {
            const ocjena = parseInt(z.dataset.ocjena);
            zvjezdice.forEach(s => s.classList.toggle('hover', parseInt(s.dataset.ocjena) <= ocjena));
        });
        z.addEventListener('mouseout', () => zvjezdice.forEach(s => s.classList.remove('hover')));
        z.addEventListener('click', () => {
            const ocjena = parseInt(z.dataset.ocjena);
            input.value = ocjena;
            zvjezdice.forEach(s => s.classList.toggle('aktivna', parseInt(s.dataset.ocjena) <= ocjena));
        });
    });
}
</script>
</body>
</html>