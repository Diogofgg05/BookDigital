<?php
# Impede que usuários acessem a página se não estiverem logados
include('../../seguranca/seguranca.php');
session_start();
if(administrador_logado() == false) {header("location: /index.php"); exit;}

require_once("../../conexao/conexao.php");

$teste_NIF = campo_e_valido("txtNIF", "NIF");
$teste_NOME = campo_e_valido("txtNOME", "nome");
$teste_SOBRENOME = campo_e_valido("txtSOBRENOME", "sobrenome");
$teste_EMAIL = campo_e_valido("txtEMAIL", "E-mail");
$teste_TELEFONE = campo_e_valido("txtTELEFONE", "telefone");
$teste_DATA_NASCIMENTO = campo_e_valido("txtDATA_NASCIMENTO", "Data de Nascimento");
$teste_SENHA = campo_e_valido("txtSENHA", "Senha"); // NOVO

if ($teste_NIF[0] == false) { exit; }
if ($teste_NOME[0] == false) { exit; }
if ($teste_SOBRENOME[0] == false) { exit; }
if ($teste_EMAIL[0] == false) { exit; }
if ($teste_TELEFONE[0] == false) { exit; }
if ($teste_DATA_NASCIMENTO[0] == false) { exit; }
if ($teste_SENHA[0] == false) { exit; } // NOVO

$txtNOME = $teste_NOME[1];
$txtSOBRENOME = $teste_SOBRENOME[1];
$txtNIF = $teste_NIF[1];
$txtEMAIL = $teste_EMAIL[1];
$txtTELEFONE = $teste_TELEFONE[1];
$txtDATA_NASCIMENTO = $teste_DATA_NASCIMENTO[1];
$txtSENHA = $teste_SENHA[1];

# 🔒 Gera hash seguro da senha (bcrypt por padrão)
$PASSHASH = password_hash($txtSENHA, PASSWORD_DEFAULT);

try {
    $comando = $conexao->prepare(
        "INSERT INTO UTILIZADORES
        (NIF, NOME, SOBRENOME, EMAIL, TELEFONE, DATA_NASCIMENTO, PASSHASH)
        VALUES (:txtNIF, :txtNOME, :txtSOBRENOME, :txtEMAIL, :txtTELEFONE, :txtDATA_NASCIMENTO, :PASSHASH)"
    );

    $comando->execute(array(
        ':txtNIF' => $txtNIF,
        ':txtNOME' => $txtNOME,
        ':txtSOBRENOME' => $txtSOBRENOME,
        ':txtEMAIL' => $txtEMAIL,
        ':txtTELEFONE' => $txtTELEFONE,
        ':txtDATA_NASCIMENTO' => $txtDATA_NASCIMENTO,
        ':PASSHASH' => $PASSHASH
    ));

    if($comando->rowCount() > 0)
    {
        header('location:/views/usuarios/visualizar.php');
        exit;
    }
    else
    {
        echo "Erro ao gravar os dados";
    }

} catch (PDOException $e) {
    echo("Erro ao gravar informação no banco de dados. \n\n".$e->getMessage());
}

$conexao = null;
