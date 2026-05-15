<?php
// films.php - Puna stranica za pregled filmova s filterima i dodavanjem u videoteku
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';

$zanrovi_result = $conn->query("SELECT DISTINCT zanr FROM filmovi ORDER BY zanr");
$zanrovi = [];
while ($z = $zanrovi_result->fetch_assoc()) $zanrovi[] = $z['zanr'];

$zemlje_result = $conn->query("SELECT DISTINCT zemlja FROM filmovi ORDER BY zemlja");
$zemlje = [];
while ($z = $zemlje_result->fetch_assoc()) $zemlje[] = $z['zemlja'];

$godine_result = $conn->query("SELECT MIN(godina) as min_g, MAX(godina) as max_g FROM filmovi");
$god = $godine_result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filmovi</title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/nav.css">
    <link rel="stylesheet" href="style/lv4.css">
</head>
<body>
<header><h1>Filmovi</h1></header>
<?php include 'includes/nav.php'; ?>

<main>
    <h2 style="text-align:center;margin-top:20px;">Pregled svih filmova</h2>

    <?php if (!je_prijavljen()): ?>
        <div class="alert alert-info" style="max-width:900px;margin:10px auto;">
            <a href="login.php">Prijavite se</a> za dodavanje filmova u videoteku.
        </div>
    <?php endif; ?>

    <div id="filteri">
        <input type="text" id="filter-naziv" placeholder="Pretraži naslov" />

        <select id="filter-zanr">
            <option value="">Svi žanrovi</option>
            <?php foreach ($zanrovi as $z): ?>
                <option value="<?= htmlspecialchars($z) ?>"><?= htmlspecialchars($z) ?></option>
            <?php endforeach; ?>
        </select>

        <select id="filter-zemlja">
            <option value="">Sve zemlje</option>
            <?php foreach ($zemlje as $z): ?>
                <option value="<?= htmlspecialchars($z) ?>"><?= htmlspecialchars($z) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="filter-ocjena">Min ocjena: <span id="ocjena-value">0</span></label>
        <input type="range" id="filter-ocjena" min="0" max="10" step="0.1" value="0" />

        <select id="filter-sort">
            <option value="">Sortiraj...</option>
            <option value="naslov_asc">Naslov A-Z</option>
            <option value="naslov_desc">Naslov Z-A</option>
            <option value="ocjena_desc">Ocjena ↓</option>
            <option value="ocjena_asc">Ocjena ↑</option>
            <option value="godina_desc">Godina ↓</option>
            <option value="godina_asc">Godina ↑</option>
        </select>

        <button id="filtriraj-btn">Filtriraj</button>
        <button id="reset-btn">Reset</button>
    </div>

    <?php if (je_prijavljen()): ?>
    <div style="max-width:900px;margin:0 auto;">
        <h3>Videoteka (<span id="kosarica-broj">0</span>)</h3>
        <aside id="kosarica">
            <ul id="lista-kosarice"></ul>
            <button id="potvrdi">💾 Spremi u videoteku</button>
            <a href="my_videoteka.php" style="margin-left:10px;">Pogledaj videoteku →</a>
        </aside>
    </div>
    <?php endif; ?>

    <div id="rezultati-info" style="text-align:center;color:#666;margin:10px;"></div>

    <div class="content">
        <table aria-label="Popis filmova" id="filmovi-tablica">
            <thead>
                <tr>
                    <th>Naslov</th>
                    <th>Žanr</th>
                    <th>Godina</th>
                    <th>Trajanje</th>
                    <th>Ocjena</th>
                    <th>Redatelj</th>
                    <th>Zemlja</th>
                    <?php if (je_prijavljen()): ?>
                    <th>Akcija</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody id="filmovi-tbody"></tbody>
        </table>
    </div>
</main>
<footer><p>&copy; 2025. Web Programiranje. Sva prava pridržana.</p></footer>

<script>
const jePrijavljen = <?= je_prijavljen() ? 'true' : 'false' ?>;
let kosarica = [];

function ucitajFilmove(params = {}) {
    const qs = new URLSearchParams(params).toString();
    fetch('api/filmovi.php?' + qs)
        .then(r => r.json())
        .then(data => {
            prikaziTablicu(data);
            document.getElementById('rezultati-info').textContent = `Pronađeno ${data.length} filmova`;
        });
}

function prikaziTablicu(filmovi) {
    const tbody = document.getElementById('filmovi-tbody');
    tbody.innerHTML = '';
    filmovi.forEach(film => {
        const pune = Math.round(film.ocjena / 2);
        const zvjezdice = '★'.repeat(pune) + '☆'.repeat(5 - pune);
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><a href="film.php?id=${film.id}">${escHtml(film.naslov)}</a></td>
            <td>${escHtml(film.zanr)}</td>
            <td>${film.godina}</td>
            <td>${film.trajanje_min} min</td>
            <td class="stars">${zvjezdice} <small>${film.ocjena}</small></td>
            <td>${escHtml(film.reziser || '')}</td>
            <td>${escHtml(film.zemlja || '')}</td>
            ${jePrijavljen ? `<td><button class="btn-dodaj" data-id="${film.id}" data-naslov="${escHtml(film.naslov)}">+ Dodaj</button></td>` : ''}
        `;
        tbody.appendChild(tr);
    });
    if (jePrijavljen) {
        document.querySelectorAll('.btn-dodaj').forEach(btn => {
            btn.addEventListener('click', () => dodajUKosaricu({ id: btn.dataset.id, naslov: btn.dataset.naslov }));
        });
    }
}

function escHtml(str) {
    const d = document.createElement('div');
    d.appendChild(document.createTextNode(str));
    return d.innerHTML;
}

document.getElementById('filter-ocjena').addEventListener('input', function() {
    document.getElementById('ocjena-value').textContent = this.value;
});

document.getElementById('filtriraj-btn').addEventListener('click', () => {
    ucitajFilmove({
        naziv: document.getElementById('filter-naziv').value,
        zanr: document.getElementById('filter-zanr').value,
        zemlja: document.getElementById('filter-zemlja').value,
        min_ocjena: document.getElementById('filter-ocjena').value,
        sort: document.getElementById('filter-sort').value
    });
});

document.getElementById('reset-btn').addEventListener('click', () => {
    document.getElementById('filter-naziv').value = '';
    document.getElementById('filter-zanr').value = '';
    document.getElementById('filter-zemlja').value = '';
    document.getElementById('filter-ocjena').value = 0;
    document.getElementById('ocjena-value').textContent = '0';
    document.getElementById('filter-sort').value = '';
    ucitajFilmove();
});

function dodajUKosaricu(film) {
    if (!kosarica.find(f => f.id === film.id)) {
        kosarica.push(film);
        osvjeziKosaricu();
    }
}

function osvjeziKosaricu() {
    const lista = document.getElementById('lista-kosarice');
    const broj = document.getElementById('kosarica-broj');
    if (!lista) return;
    lista.innerHTML = '';
    kosarica.forEach((film, i) => {
        const li = document.createElement('li');
        li.textContent = film.naslov;
        const btn = document.createElement('button');
        btn.textContent = 'X';
        btn.onclick = () => { kosarica.splice(i, 1); osvjeziKosaricu(); };
        li.appendChild(btn);
        lista.appendChild(li);
    });
    if (broj) broj.textContent = kosarica.length;
}

const potvrdiBtn = document.getElementById('potvrdi');
if (potvrdiBtn) {
    potvrdiBtn.addEventListener('click', () => {
        if (kosarica.length === 0) { alert('Košarica je prazna!'); return; }
        fetch('api/videoteka.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ film_ids: kosarica.map(f => f.id) })
        })
        .then(r => r.json())
        .then(data => {
            alert(data.poruka);
            kosarica = [];
            osvjeziKosaricu();
        });
    });
}

ucitajFilmove();
</script>
</body>
</html>