<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';

// Dohvati sve žanrove za filter
$zanrovi_result = $conn->query("SELECT DISTINCT zanr FROM filmovi ORDER BY zanr");
$zanrovi = [];
while ($z = $zanrovi_result->fetch_assoc()) {
    $zanrovi[] = $z['zanr'];
}

// Dohvati sve zemlje
$zemlje_result = $conn->query("SELECT DISTINCT zemlja FROM filmovi ORDER BY zemlja");
$zemlje = [];
while ($z = $zemlje_result->fetch_assoc()) {
    $zemlje[] = $z['zemlja'];
}
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Web stranica s popisom filmova i Netflix grafikonima">
    <title>Početna stranica</title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/nav.css">
    <link rel="stylesheet" href="style/lv4.css">
</head>
<body>
<header><h1>Dobrodošli na moju web stranicu</h1></header>
<?php include 'includes/nav.php'; ?>

<main>
    <h1>Popis filmova</h1>

    <?php if (je_prijavljen()): ?>
        <div class="alert alert-success" style="max-width:900px;margin:10px auto;">
            Prijavljeni ste kao <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>.
            Možete dodavati filmove u svoju videoteku.
        </div>
    <?php else: ?>
        <div class="alert alert-info" style="max-width:900px;margin:10px auto;">
            <a href="login.php">Prijavite se</a> da biste mogli dodavati filmove u videoteku.
        </div>
    <?php endif; ?>

    <!-- Filteri -->
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

        <label for="filter-ocjena">Min ocjena:</label>
        <input type="range" id="filter-ocjena" min="0" max="10" step="0.1" value="0" />
        <span id="ocjena-value">0</span>

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
    <h2>Videoteka</h2>
    <aside id="kosarica">
        <ul id="lista-kosarice"></ul>
        <button id="potvrdi">Spremi u videoteku</button>
    </aside>
    <?php endif; ?>

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
            <tbody id="filmovi-tbody">
                <!-- Popunjava AJAX -->
            </tbody>
        </table>
    </div>

    <div class="content-location">
        <aside aria-label="Lokacija">
            <h2>Karta lokacije</h2>
            <iframe src="https://www.google.com/maps?q=Zagreb&output=embed"
                width="300" height="200" style="border:0" allowfullscreen loading="lazy"></iframe>
        </aside>
    </div>

    <article aria-labelledby="vijesti">
        <h2 id="vijesti">Najnovije vijesti</h2>
        <p>Ovdje se nalazi članak s važnim informacijama.</p>
    </article>
</main>

<footer><p>&copy; 2025. Web Programiranje. Sva prava pridržana.</p></footer>

<script>
const jePrijavljen = <?= je_prijavljen() ? 'true' : 'false' ?>;
let sviFilmovi = [];
let kosarica = []; // {id, naslov}

// Dohvati filmove
function ucitajFilmove(params = {}) {
    const qs = new URLSearchParams(params).toString();
    fetch('api/filmovi.php?' + qs)
        .then(r => r.json())
        .then(data => {
            sviFilmovi = data;
            prikaziTablicu(data);
        })
        .catch(err => console.error(err));
}

function prikaziTablicu(filmovi) {
    const tbody = document.getElementById('filmovi-tbody');
    tbody.innerHTML = '';
    filmovi.forEach(film => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><a href="film.php?id=${film.id}">${escHtml(film.naslov)}</a></td>
            <td>${escHtml(film.zanr)}</td>
            <td>${film.godina}</td>
            <td>${film.trajanje_min} min</td>
            <td class="stars">${zvjezdice(film.ocjena)} <small>${film.ocjena}</small></td>
            <td>${escHtml(film.reziser || '')}</td>
            <td>${escHtml(film.zemlja || '')}</td>
            ${jePrijavljen ? `<td><button class="btn-dodaj" data-id="${film.id}" data-naslov="${escHtml(film.naslov)}">+ Videoteka</button></td>` : ''}
        `;
        tbody.appendChild(tr);
    });

    if (jePrijavljen) {
        document.querySelectorAll('.btn-dodaj').forEach(btn => {
            btn.addEventListener('click', () => {
                dodajUKosaricu({ id: btn.dataset.id, naslov: btn.dataset.naslov });
            });
        });
    }
}

function zvjezdice(ocjena) {
    const pune = Math.round(ocjena / 2);
    return '★'.repeat(pune) + '☆'.repeat(5 - pune);
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
    const params = {
        naziv: document.getElementById('filter-naziv').value,
        zanr: document.getElementById('filter-zanr').value,
        zemlja: document.getElementById('filter-zemlja').value,
        min_ocjena: document.getElementById('filter-ocjena').value,
        sort: document.getElementById('filter-sort').value
    };
    ucitajFilmove(params);
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

// Košarica/videoteka
function dodajUKosaricu(film) {
    if (!kosarica.find(f => f.id === film.id)) {
        kosarica.push(film);
        osvjeziKosaricu();
    }
}

function osvjeziKosaricu() {
    const lista = document.getElementById('lista-kosarice');
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
}

const potvrdiBtn = document.getElementById('potvrdi');
if (potvrdiBtn) {
    potvrdiBtn.addEventListener('click', () => {
        if (kosarica.length === 0) { alert('Košarica je prazna!'); return; }
        const ids = kosarica.map(f => f.id);
        fetch('api/videoteka.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ film_ids: ids })
        })
        .then(r => r.json())
        .then(data => {
            alert(data.poruka);
            kosarica = [];
            osvjeziKosaricu();
        });
    });
}

// Inicijalno učitavanje
ucitajFilmove();
</script>
</body>
</html>
