<?php
function iniciarSessao() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function usuarioLogado() {
    iniciarSessao();
    return isset($_SESSION['usuario_id']);
}

function exigirLogin() {
    if (!usuarioLogado()) {
        header('Location: /login.php');
        exit;
    }
}

function logout() {
    iniciarSessao();
    session_destroy();
    header('Location: /login.php');
    exit;
}
