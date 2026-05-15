<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';

$greske = [];

if (je_prijavljen()) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    provjeri_csrf();

    $username = trim($_POST['username'] ?? '');
    $lozinka  = $_POST['lozinka'] ?? '';

    if (empty($username) || empty($lozinka)) {
        $greske[] = "Unesite korisničko ime i lozinku.";
    } else {
        $stmt = $conn->prepare("SELECT id, username, lozinka, uloga FROM korisnici WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $korisnik = $result->fetch_assoc();

        if ($korisnik && password_verify($lozinka, $korisnik['lozinka'])) {
            $_SESSION['korisnik_id'] = $korisnik['id'];
            $_SESSION['username']    = $korisnik['username'];
            $_SESSION['uloga']       = $korisnik['uloga'];
            header('Location: index.php');
            exit;
        } else {
            $greske[] = "Pogrešno korisničko ime ili lozinka.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prijava</title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/nav.css">
    <link rel="stylesheet" href="style/lv4.css">
</head>
<body>
<header><h1>Prijava</h1></header>
<?php include 'includes/nav.php'; ?>
<main>
    <div class="auth-box">
        <h2>Prijava</h2>

        <?php foreach ($greske as $g): ?>
            <div class="alert alert-error"><?= htmlspecialchars($g) ?></div>
        <?php endforeach; ?>

        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

            <label for="username">Korisničko ime</label>
            <input type="text" id="username" name="username" required
                   value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">

            <label for="lozinka">Lozinka</label>
            <input type="password" id="lozinka" name="lozinka" required>

            <button type="submit">Prijavi se</button>
        </form>
        <p style="margin-top:15px;">Nemaš račun? <a href="register.php">Registriraj se</a></p>
    </div>
</main>
<footer><p>&copy; 2025. Web Programiranje. Sva prava pridržana.</p></footer>
</body>
</html>