<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';

$greske = [];
$uspjeh = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    provjeri_csrf();

    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $lozinka  = $_POST['lozinka'] ?? '';
    $potvrda  = $_POST['potvrda'] ?? '';

    // Validacija
    if (strlen($username) < 3 || strlen($username) > 50) {
        $greske[] = "Korisničko ime mora imati između 3 i 50 znakova.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $greske[] = "Email adresa nije ispravna.";
    }
    if (strlen($lozinka) < 6) {
        $greske[] = "Lozinka mora imati najmanje 6 znakova.";
    }
    if ($lozinka !== $potvrda) {
        $greske[] = "Lozinke se ne podudaraju.";
    }

    if (empty($greske)) {
        // Provjera duplikata
        $stmt = $conn->prepare("SELECT id FROM korisnici WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $greske[] = "Korisničko ime ili email već postoji.";
        } else {
            $hash = password_hash($lozinka, PASSWORD_DEFAULT);
            $stmt2 = $conn->prepare("INSERT INTO korisnici (username, email, lozinka) VALUES (?, ?, ?)");
            $stmt2->bind_param("sss", $username, $email, $hash);
            if ($stmt2->execute()) {
                $uspjeh = "Registracija uspješna! <a href='login.php'>Prijavi se</a>";
            } else {
                $greske[] = "Greška pri registraciji. Pokušaj ponovo.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registracija</title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/nav.css">
    <link rel="stylesheet" href="style/lv4.css">
</head>
<body>
<header><h1>Registracija</h1></header>
<?php include 'includes/nav.php'; ?>
<main>
    <div class="auth-box">
        <h2>Novi korisnik</h2>

        <?php foreach ($greske as $g): ?>
            <div class="alert alert-error"><?= htmlspecialchars($g) ?></div>
        <?php endforeach; ?>
        <?php if ($uspjeh): ?>
            <div class="alert alert-success"><?= $uspjeh ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

            <label for="username">Korisničko ime</label>
            <input type="text" id="username" name="username" required
                   value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required
                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">

            <label for="lozinka">Lozinka</label>
            <input type="password" id="lozinka" name="lozinka" required>

            <label for="potvrda">Potvrdi lozinku</label>
            <input type="password" id="potvrda" name="potvrda" required>

            <button type="submit">Registriraj se</button>
        </form>
        <p style="margin-top:15px;">Već imaš račun? <a href="login.php">Prijavi se</a></p>
    </div>
</main>
<footer><p>&copy; 2025. Web Programiranje. Sva prava pridržana.</p></footer>
</body>
</html>