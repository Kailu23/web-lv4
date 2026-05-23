<?php
require_once 'includes/db.php';

$sqlDrama = "SELECT COUNT(*) as total FROM filmovi WHERE zanr LIKE '%DRAMA%'";
$sqlCrime = "SELECT COUNT(*) as total FROM filmovi WHERE zanr LIKE '%CRIME%'";
$sqlComedy = "SELECT COUNT(*) as total FROM filmovi WHERE zanr LIKE '%COMEDY%'";
$sqlAction = "SELECT COUNT(*) as total FROM filmovi WHERE zanr LIKE '%ACTION%'";

$drama = $conn->query($sqlDrama)->fetch_assoc()['total'];
$crime = $conn->query($sqlCrime)->fetch_assoc()['total'];
$comedy = $conn->query($sqlComedy)->fetch_assoc()['total'];
$action = $conn->query($sqlAction)->fetch_assoc()['total'];

$total = $drama + $crime + $comedy + $action;

if ($total == 0) {
    $total = 1;
}

$dramaDeg = ($drama / $total) * 360;
$crimeDeg = ($crime / $total) * 360;
$comedyDeg = ($comedy / $total) * 360;
$actionDeg = ($action / $total) * 360;
?>

<!doctype html>
<html lang="hr">

<head>
    <meta charset="utf-8" />
    <title>Grafikon - Netflix žanrovi</title>

    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="stylesheet" href="style/grafikon.css" />
    <link rel="stylesheet" href="style/nav.css" />

</head>

<body>

<header>
    <h1>Netflix sadržaj po žanrovima</h1>
</header>
<?php include 'includes/nav.php'; ?>

<input type="checkbox" id="menu-toggle" />

<nav class="side-nav" aria-labelledby="primarna-navigacija">

    <h2 id="primarna-navigacija" class="visually-hidden">
        Primarna navigacija
    </h2>

    <label for="menu-toggle"
           class="menu-button"
           aria-label="Otvori izbornik">
        ☰
    </label>

    <ul>
        <li><a href="/">Početna</a></li>
        <li><a href="grafikon.php">Grafikon</a></li>
        <li><a href="/slike">Slike</a></li>
        <li><a href="/galerija">Galerija</a></li>
        <li><a href="#">Kontakt</a></li>
    </ul>

</nav>

<main>

    <section>

        <div class="pie"></div>

        <div class="legend">

            <div>
                <span class="box drama"></span>
                Drama (<?= $drama ?>)
            </div>

            <div>
                <span class="box crime"></span>
                Crime (<?= $crime ?>)
            </div>

            <div>
                <span class="box comedy"></span>
                Comedy (<?= $comedy ?>)
            </div>

            <div>
                <span class="box action"></span>
                Action (<?= $action ?>)
            </div>

        </div>

    </section>

</main>

<footer>
    <p>Podatci preuzeti iz MySQL baze</p>
</footer>

</body>
</html>
