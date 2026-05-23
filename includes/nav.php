<?php
// includes/nav.php
// Reusable navigacija - koristi se na svim stranicama
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/auth.php';
?>
<input type="checkbox" id="menu-toggle" />
<nav class="side-nav" aria-labelledby="primarna-navigacija">
    <h2 id="primarna-navigacija" class="visually-hidden">Primarna navigacija</h2>
    <label for="menu-toggle" class="menu-button" aria-label="Otvori izbornik">☰</label>
    <ul>
        <li><a href="/index.php">Početna</a></li>
        <li><a href="/films.php">Filmovi</a></li>
        <li><a href="/grafikon.php">Grafikon</a></li>
        <li><a href="/gallery.php">Ocjeni slike</a></li>
        <?php if (je_prijavljen()): ?>
            <li><a href="/my_videoteka.php">Moja videoteka</a></li>
            <?php if (je_admin()): ?>
                <li><a href="/admin.php">Admin</a></li>
            <?php endif; ?>
            <li><a href="/logout.php">Odjava (<?= htmlspecialchars($_SESSION['username']) ?>)</a></li>
        <?php else: ?>
            <li><a href="/login.php">Prijava</a></li>
            <li><a href="/register.php">Registracija</a></li>
        <?php endif; ?>
    </ul>
</nav>
