<?php
include('../../seguranca/seguranca.php');
session_start();

if (administrador_logado() == false) {
    header("location: /index.php");
    exit;
}

require_once(__DIR__ . "/../../conexao/conexao.php");

try {
    $NIF = filter_input(INPUT_POST, "NIF", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (!$NIF) {
        echo "NIF inválido.";
        exit;
    }

    $comando = $conexao->prepare("DELETE FROM UTILIZADORES WHERE NIF = :NIF");
    $comando->bindParam(":NIF", $NIF, PDO::PARAM_STR);
    $comando->execute();

    if ($comando->rowCount() > 0) {
        header("location: /views/usuarios/visualizar.php");
        exit;
    } else {
        echo "Sem ação — utilizador não encontrado.";
        exit;
    }

} catch (PDOException $e) {
    $mensagem_erro = urlencode($e->getMessage());
    header("location: /views/usuarios/excluir.php?NIF=$NIF&mensagem_erro=$mensagem_erro");
    exit;
}

$conexao = null;
