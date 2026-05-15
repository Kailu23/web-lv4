<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';
zahtijeva_admina();

$tab = $_GET['tab'] ?? 'filmovi';
$poruka = '';
$greske = [];

// ===================== FILMOVI =====================

if ($tab === 'filmovi') {

    // Brisanje filma
    if (isset($_POST['obrisi_film'])) {
        provjeri_csrf();
        $fid = (int)$_POST['film_id'];
        $stmt = $conn->prepare("DELETE FROM filmovi WHERE id = ?");
        $stmt->bind_param("i", $fid);
        $stmt->execute();
        $poruka = "Film je obrisan.";
    }

    // Dodavanje / uređivanje filma
    if (isset($_POST['spremi_film'])) {
        provjeri_csrf();
        $naslov    = trim($_POST['naslov'] ?? '');
        $zanr      = trim($_POST['zanr'] ?? '');
        $godina    = (int)($_POST['godina'] ?? 0);
        $trajanje  = (int)($_POST['trajanje_min'] ?? 0);
        $ocjena    = (float)($_POST['ocjena'] ?? 0);
        $reziser   = trim($_POST['reziser'] ?? '');
        $zemlja    = trim($_POST['zemlja'] ?? '');
        $opis      = trim($_POST['opis'] ?? '');
        $film_id   = (int)($_POST['film_id'] ?? 0);

        if (empty($naslov)) $greske[] = "Naslov je obavezan.";
        if (empty($zanr))   $greske[] = "Žanr je obavezan.";
        if ($godina < 1888 || $godina > date('Y') + 5) $greske[] = "Neispravna godina ($godina).";
        if ($trajanje < 1 || $trajanje > 600) $greske[] = "Trajanje mora biti između 1 i 600 min.";
        if ($ocjena < 0 || $ocjena > 10) $greske[] = "Ocjena mora biti između 0 i 10.";

        if (empty($greske)) {
            if ($film_id > 0) {
                $stmt = $conn->prepare("UPDATE filmovi SET naslov=?,zanr=?,godina=?,trajanje_min=?,ocjena=?,reziser=?,zemlja=?,opis=? WHERE id=?");
                $stmt->bind_param("ssiidsss i", $naslov, $zanr, $godina, $trajanje, $ocjena, $reziser, $zemlja, $opis, $film_id);
            } else {
                $stmt = $conn->prepare("INSERT INTO filmovi (naslov,zanr,godina,trajanje_min,ocjena,reziser,zemlja,opis) VALUES (?,?,?,?,?,?,?,?)");
                $stmt->bind_param("ssiiidss", $naslov, $zanr, $godina, $trajanje, $ocjena, $reziser, $zemlja, $opis);
            }
            $stmt->execute();
            $poruka = $film_id > 0 ? "Film je ažuriran." : "Film je dodan.";
        }
    }

    // Uvoz iz CSV
    if (isset($_POST['uvozi_csv']) && isset($_FILES['csv_datoteka'])) {
        provjeri_csrf();
        $file = $_FILES['csv_datoteka'];
        if ($file['error'] === 0 && pathinfo($file['name'], PATHINFO_EXTENSION) === 'csv') {
            $handle = fopen($file['tmp_name'], 'r');
            $header = fgetcsv($handle); // preskoči zaglavlje
            $uvezeno = 0;
            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) < 5) continue;
                $naslov   = trim($row[0]);
                $zanr     = trim($row[1]);
                $godina   = (int)trim($row[2]);
                $trajanje = (int)trim($row[3]);
                $ocjena   = (float)trim($row[4]);
                $reziser  = trim($row[5] ?? '');
                $zemlja   = trim($row[6] ?? '');
                $stmt = $conn->prepare("INSERT IGNORE INTO filmovi (naslov,zanr,godina,trajanje_min,ocjena,reziser,zemlja) VALUES (?,?,?,?,?,?,?)");
                $stmt->bind_param("ssiidss", $naslov, $zanr, $godina, $trajanje, $ocjena, $reziser, $zemlja);
                $stmt->execute();
                $uvezeno++;
            }
            fclose($handle);
            $poruka = "Uvezeno $uvezeno filmova iz CSV-a.";
        } else {
            $greske[] = "Neispravan CSV format ili greška pri uploadu.";
        }
    }

    // Dohvati filmove
    $filmovi = $conn->query("SELECT * FROM filmovi ORDER BY id DESC")->fetch_all(MYSQLI_ASSOC);
}

// ===================== SLIKE =====================

if ($tab === 'slike') {

    // Brisanje slike
    if (isset($_POST['obrisi_sliku'])) {
        provjeri_csrf();
        $sid = (int)$_POST['slika_id'];
        // Dohvati putanju za brisanje datoteke
        $s = $conn->prepare("SELECT putanja FROM slike WHERE id = ?");
        $s->bind_param("i", $sid);
        $s->execute();
        $sr = $s->get_result()->fetch_assoc();
        if ($sr && $sr['izvor'] === 'lokalno') {
            $filepath = 'public' . $sr['putanja'];
            if (file_exists($filepath)) unlink($filepath);
        }
        $stmt = $conn->prepare("DELETE FROM slike WHERE id = ?");
        $stmt->bind_param("i", $sid);
        $stmt->execute();
        $poruka = "Slika je obrisana.";
    }

    // Upload slike
    if (isset($_POST['dodaj_sliku']) && isset($_FILES['slika'])) {
        provjeri_csrf();
        $file  = $_FILES['slika'];
        $opis  = trim($_POST['opis'] ?? '');
        $dozvoljeni_tipovi = ['image/jpeg', 'image/png', 'image/jpg'];
        $max_velicina = 5 * 1024 * 1024; // 5MB

        if ($file['error'] !== 0) {
            $greske[] = "Greška pri uploadu slike.";
        } elseif (!in_array($file['type'], $dozvoljeni_tipovi)) {
            $greske[] = "Samo JPEG i PNG format je dozvoljen.";
        } elseif ($file['size'] > $max_velicina) {
            $greske[] = "Slika ne smije biti veća od 5MB.";
        } else {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $naziv = uniqid('slika_', true) . '.' . $ext;
            $dest_dir = 'public/images/gallery/';
            if (!is_dir($dest_dir)) mkdir($dest_dir, 0755, true);
            $dest = $dest_dir . $naziv;
            if (move_uploaded_file($file['tmp_name'], $dest)) {
                $putanja = '/images/gallery/' . $naziv;
                $uid = $_SESSION['korisnik_id'];
                $stmt = $conn->prepare("INSERT INTO slike (naziv_datoteke, opis, putanja, izvor, uploaded_by) VALUES (?,?,?,'lokalno',?)");
                $stmt->bind_param("sssi", $naziv, $opis, $putanja, $uid);
                $stmt->execute();
                $poruka = "Slika je uspješno uploadana.";
            } else {
                $greske[] = "Greška pri spremanju slike.";
            }
        }
    }

    // API slike
    if (isset($_POST['dodaj_api_slike'])) {
        provjeri_csrf();
        $uid = $_SESSION['korisnik_id'];
        $broj = (int)($_POST['broj_api_slika'] ?? 6);
        $broj = min($broj, 24);
        for ($i = 1; $i <= $broj; $i++) {
            $putanja = "https://picsum.photos/900/600?random=$i";
            $naziv = "api_slika_$i.jpg";
            $opis = "Slika $i";
            $stmt = $conn->prepare("INSERT IGNORE INTO slike (naziv_datoteke, opis, putanja, izvor, uploaded_by) VALUES (?,?,?,'api',?)");
            $stmt->bind_param("sssi", $naziv, $opis, $putanja, $uid);
            $stmt->execute();
        }
        $poruka = "Dodano $broj API slika.";
    }

    $slike = $conn->query("SELECT s.*, k.username FROM slike s LEFT JOIN korisnici k ON s.uploaded_by = k.id ORDER BY s.id DESC")->fetch_all(MYSQLI_ASSOC);
}

// Dohvati edit film ako postoji
$edit_film = null;
if ($tab === 'filmovi' && isset($_GET['edit'])) {
    $eid = (int)$_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM filmovi WHERE id = ?");
    $stmt->bind_param("i", $eid);
    $stmt->execute();
    $edit_film = $stmt->get_result()->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin panel</title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/nav.css">
    <link rel="stylesheet" href="style/lv4.css">
</head>
<body>
<header><h1>Admin panel</h1></header>
<?php include 'includes/nav.php'; ?>

<main>
    <!-- Tabovi -->
    <div class="admin-tabovi">
        <a href="admin.php?tab=filmovi" class="<?= $tab === 'filmovi' ? 'aktivan' : '' ?>">🎬 Filmovi</a>
        <a href="admin.php?tab=slike" class="<?= $tab === 'slike' ? 'aktivan' : '' ?>">🖼 Slike</a>
    </div>

    <?php foreach ($greske as $g): ?>
        <div class="alert alert-error"><?= htmlspecialchars($g) ?></div>
    <?php endforeach; ?>
    <?php if ($poruka): ?>
        <div class="alert alert-success"><?= htmlspecialchars($poruka) ?></div>
    <?php endif; ?>

    <!-- ===== FILMOVI TAB ===== -->
    <?php if ($tab === 'filmovi'): ?>

    <div class="admin-grid">
        <!-- Forma za dodavanje/uređivanje -->
        <div class="admin-forma">
            <h3><?= $edit_film ? 'Uredi film' : 'Dodaj film' ?></h3>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <input type="hidden" name="spremi_film" value="1">
                <input type="hidden" name="film_id" value="<?= $edit_film['id'] ?? 0 ?>">

                <label>Naslov *</label>
                <input type="text" name="naslov" required value="<?= htmlspecialchars($edit_film['naslov'] ?? '') ?>">

                <label>Žanr *</label>
                <input type="text" name="zanr" required value="<?= htmlspecialchars($edit_film['zanr'] ?? '') ?>">

                <label>Godina *</label>
                <input type="number" name="godina" required min="1888" max="<?= date('Y') + 5 ?>" value="<?= $edit_film['godina'] ?? date('Y') ?>">

                <label>Trajanje (min) *</label>
                <input type="number" name="trajanje_min" required min="1" max="600" value="<?= $edit_film['trajanje_min'] ?? '' ?>">

                <label>Ocjena (0-10) *</label>
                <input type="number" name="ocjena" required min="0" max="10" step="0.1" value="<?= $edit_film['ocjena'] ?? '' ?>">

                <label>Redatelj</label>
                <input type="text" name="reziser" value="<?= htmlspecialchars($edit_film['reziser'] ?? '') ?>">

                <label>Zemlja</label>
                <input type="text" name="zemlja" value="<?= htmlspecialchars($edit_film['zemlja'] ?? '') ?>">

                <label>Opis</label>
                <textarea name="opis" rows="3"><?= htmlspecialchars($edit_film['opis'] ?? '') ?></textarea>

                <button type="submit"><?= $edit_film ? 'Ažuriraj' : 'Dodaj film' ?></button>
                <?php if ($edit_film): ?>
                    <a href="admin.php?tab=filmovi" class="btn-sekundarni">Otkaži</a>
                <?php endif; ?>
            </form>

            <hr>
            <h3>Uvoz iz CSV</h3>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <input type="hidden" name="uvozi_csv" value="1">
                <input type="file" name="csv_datoteka" accept=".csv" required>
                <small>Format: Naslov,Zanr,Godina,Trajanje_min,Ocjena,Reziser,Zemlja</small>
                <button type="submit">Uvezi CSV</button>
            </form>
        </div>

        <!-- Tablica filmova -->
        <div>
            <h3>Svi filmovi (<?= count($filmovi) ?>)</h3>
            <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th>ID</th><th>Naslov</th><th>Žanr</th><th>Godina</th><th>Ocjena</th><th>Akcije</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($filmovi as $f): ?>
                    <tr>
                        <td><?= $f['id'] ?></td>
                        <td><?= htmlspecialchars($f['naslov']) ?></td>
                        <td><?= htmlspecialchars($f['zanr']) ?></td>
                        <td><?= $f['godina'] ?></td>
                        <td><?= $f['ocjena'] ?></td>
                        <td>
                            <a href="admin.php?tab=filmovi&edit=<?= $f['id'] ?>" class="btn-edit">✏️</a>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('Obrisati film?')">
                                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                <input type="hidden" name="obrisi_film" value="1">
                                <input type="hidden" name="film_id" value="<?= $f['id'] ?>">
                                <button type="submit" class="btn-ukloni">🗑</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>

    <!-- ===== SLIKE TAB ===== -->
    <?php elseif ($tab === 'slike'): ?>

    <div class="admin-grid">
        <div class="admin-forma">
            <h3>Upload slike</h3>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <input type="hidden" name="dodaj_sliku" value="1">
                <label>Opis slike</label>
                <input type="text" name="opis" placeholder="Opis fotografije">
                <label>Datoteka (JPEG/PNG, max 5MB)</label>
                <input type="file" name="slika" accept="image/jpeg,image/png" required>
                <button type="submit">Upload</button>
            </form>

            <hr>
            <h3>Dodaj API slike (Picsum)</h3>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <input type="hidden" name="dodaj_api_slike" value="1">
                <label>Broj slika (max 24)</label>
                <input type="number" name="broj_api_slika" min="1" max="24" value="12">
                <button type="submit">Dodaj API slike</button>
            </form>
        </div>

        <div>
            <h3>Sve slike (<?= count($slike) ?>)</h3>
            <div class="admin-slike-grid">
                <?php foreach ($slike as $s): ?>
                <div class="admin-slika-card">
                    <img src="<?= htmlspecialchars($s['putanja']) ?>" alt="<?= htmlspecialchars($s['opis'] ?? '') ?>" loading="lazy">
                    <p><?= htmlspecialchars($s['opis'] ?? $s['naziv_datoteke']) ?></p>
                    <small><?= $s['izvor'] ?> | <?= htmlspecialchars($s['username'] ?? 'N/A') ?></small>
                    <form method="POST" onsubmit="return confirm('Obrisati sliku?')">
                        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                        <input type="hidden" name="obrisi_sliku" value="1">
                        <input type="hidden" name="slika_id" value="<?= $s['id'] ?>">
                        <button type="submit" class="btn-ukloni">🗑 Obriši</button>
                    </form>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <?php endif; ?>
</main>
<footer><p>&copy; 2025. Web Programiranje. Sva prava pridržana.</p></footer>
</body>
</html>