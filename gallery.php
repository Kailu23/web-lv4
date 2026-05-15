<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';

// Dohvati sve slike s prosječnom ocjenom
$sql = "
    SELECT s.*,
           COALESCE(AVG(o.ocjena), 0) AS prosjecna_ocjena,
           COUNT(o.id) AS broj_ocjena
    FROM slike s
    LEFT JOIN ocjene o ON s.id = o.id_slika
    GROUP BY s.id
    ORDER BY s.id DESC
";
$result = $conn->query($sql);
$slike = $result->fetch_all(MYSQLI_ASSOC);

// Dohvati vlastite ocjene ako je korisnik prijavljen
$moje_ocjene = [];
if (je_prijavljen()) {
    $uid = $_SESSION['korisnik_id'];
    $stmt = $conn->prepare("SELECT id_slika, ocjena FROM ocjene WHERE id_korisnik = ?");
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($r = $res->fetch_assoc()) {
        $moje_ocjene[$r['id_slika']] = $r['ocjena'];
    }
}
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ocjenjivanje fotografija</title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/nav.css">
    <link rel="stylesheet" href="style/style_slike.css">
    <link rel="stylesheet" href="style/lv4.css">
</head>
<body>
<header><h1>Ocjenjivanje fotografija</h1></header>
<?php include 'includes/nav.php'; ?>

<main>
    <?php if (!je_prijavljen()): ?>
        <div class="alert alert-info" style="max-width:900px;margin:15px auto;">
            <a href="login.php">Prijavite se</a> da biste mogli ocjenjivati fotografije.
        </div>
    <?php endif; ?>

    <?php if (empty($slike)): ?>
        <div class="alert alert-info" style="max-width:900px;margin:15px auto;">
            Nema dostupnih fotografija.
            <?php if (je_admin()): ?>
                <a href="admin.php">Dodajte slike u adminu.</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="galerija">
            <?php foreach ($slike as $slika): ?>
            <div class="galerija_slika">
                <a href="photo.php?id=<?= $slika['id'] ?>">
                    <img src="<?= htmlspecialchars($slika['putanja']) ?>"
                         alt="<?= htmlspecialchars($slika['opis'] ?? $slika['naziv_datoteke']) ?>"
                         loading="lazy">
                </a>
                <p class="slika-naziv"><?= htmlspecialchars($slika['opis'] ?? $slika['naziv_datoteke']) ?></p>

                <!-- Prosječna ocjena -->
                <div class="ocjena-prikaz">
                    <?php
                    $avg = round($slika['prosjecna_ocjena']);
                    echo str_repeat('★', $avg) . str_repeat('☆', 5 - $avg);
                    echo " <small>{$slika['prosjecna_ocjena']}/5 ({$slika['broj_ocjena']} ocjena)</small>";
                    ?>
                </div>

                <!-- Ocjenjivanje -->
                <?php if (je_prijavljen()): ?>
                <div class="ocjena-forma" data-slika-id="<?= $slika['id'] ?>">
                    <span>Vaša ocjena:</span>
                    <?php $moja = $moje_ocjene[$slika['id']] ?? 0; ?>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <span class="zvjezdica <?= $i <= $moja ? 'aktivna' : '' ?>"
                              data-ocjena="<?= $i ?>"
                              title="<?= $i ?> zvjezdica">★</span>
                    <?php endfor; ?>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<footer><p>Fotografije - Ocjenjivanje | &copy; 2025. Web Programiranje.</p></footer>

<?php if (je_prijavljen()): ?>
<script>
document.querySelectorAll('.ocjena-forma').forEach(forma => {
    const zvjezdice = forma.querySelectorAll('.zvjezdica');
    const slikaId = forma.dataset.slikaId;

    zvjezdice.forEach(z => {
        z.addEventListener('mouseover', () => {
            const ocjena = parseInt(z.dataset.ocjena);
            zvjezdice.forEach(s => s.classList.toggle('hover', parseInt(s.dataset.ocjena) <= ocjena));
        });
        z.addEventListener('mouseout', () => {
            zvjezdice.forEach(s => s.classList.remove('hover'));
        });
        z.addEventListener('click', () => {
            const ocjena = parseInt(z.dataset.ocjena);
            spremiOcjenu(slikaId, ocjena, zvjezdice);
        });
    });
});

function spremiOcjenu(slikaId, ocjena, zvjezdice) {
    const fd = new FormData();
    fd.append('slika_id', slikaId);
    fd.append('ocjena', ocjena);

    fetch('api/ocjena.php', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(data => {
            if (data.uspjeh) {
                zvjezdice.forEach(s => {
                    s.classList.toggle('aktivna', parseInt(s.dataset.ocjena) <= ocjena);
                });
                // Osvježi prosječnu ocjenu
                const parent = zvjezdice[0].closest('.galerija_slika');
                const prikaz = parent.querySelector('.ocjena-prikaz');
                if (data.prosjecna !== undefined) {
                    const avg = Math.round(data.prosjecna);
                    prikaz.innerHTML = '★'.repeat(avg) + '☆'.repeat(5 - avg)
                        + ` <small>${parseFloat(data.prosjecna).toFixed(1)}/5 (${data.broj_ocjena} ocjena)</small>`;
                }
            }
        });
}
</script>
<?php endif; ?>
</body>
</html>