<?php
// includes/auth.php

function je_prijavljen() {
    return isset($_SESSION['korisnik_id']);
}

function je_admin() {
    return isset($_SESSION['uloga']) && $_SESSION['uloga'] === 'admin';
}

function zahtijeva_prijavu() {
    if (!je_prijavljen()) {
        header('Location: login.php');
        exit;
    }
}

function zahtijeva_admina() {
    if (!je_admin()) {
        header('Location: index.php');
        exit;
    }
}

function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function provjeri_csrf() {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Neispravan CSRF token.");
    }
}