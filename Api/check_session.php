<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['user_type'])) {
    header('location: ../../index.php');
    exit;
}

$isAdmin = ($_SESSION['user_type'] === 'admin');
$isUser = ($_SESSION['user_type'] === 'user');
?>