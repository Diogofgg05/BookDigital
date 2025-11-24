<?php
session_start();
include('../../seguranca/seguranca.php');
require_once("../../conexao/conexao.php");

$teste_SenhaLogin = campo_e_valido("txtSenhaLogin", "Senha");
$teste_EmailLogin = campo_e_valido("txtEmailLogin", "Email");

if ($teste_SenhaLogin[0] == false) { exit; }
if ($teste_EmailLogin[0] == false) { exit; }

$txtSenhaLogin = $teste_SenhaLogin[1];
$txtEmailLogin = $teste_EmailLogin[1];

try {
    // Primeiro tenta como ADMINISTRADOR
    $comandoSQL = "SELECT * FROM ADMINISTRADOR WHERE LOGIN = \"$txtEmailLogin\" AND SENHA = \"$txtSenhaLogin\"";
    $select = $conexao->query($comandoSQL);
    $resultado = $select->fetchAll();

    if($resultado) {
        $_SESSION["txtLOGIN"] = false;
        $_SESSION["txtSENHA"] = false;
        $_SESSION["user_type"] = "admin"; // ← ADICIONE ESTA LINHA
        $_SESSION["user_email"] = $txtEmailLogin;
        header('location:../../views/Admin/Home/home.php');
        exit;
    } 
    
    // Se não for ADMINISTRADOR, tenta como UTILIZADOR
$comandoSQL = "SELECT * FROM UTILIZADORES WHERE EMAIL = :email LIMIT 1";
$stmt = $conexao->prepare($comandoSQL);
$stmt->bindParam(':email', $txtEmailLogin);
$stmt->execute();

$utilizador = $stmt->fetch(PDO::FETCH_ASSOC);

if ($utilizador && password_verify($txtSenhaLogin, $utilizador['PASSHASH'])) {

    $_SESSION["txtLOGIN"] = false;
    $_SESSION["txtSENHA"] = false;
    $_SESSION["user_type"] = "user";
    $_SESSION["user_email"] = $txtEmailLogin;

    header('location:../../views/Client/Home/home.php');
    exit;
}

 else {
        header('location:../../index.php');
    }

} catch (PDOException $e) {
    echo("Erro ao gravar informação no banco de dados. \n\n".$e->getMessage());
}

$conexao = null;