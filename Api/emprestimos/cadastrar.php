<?php
# Impede que utilizadores acedam à página se não estiverem logados
include('../../seguranca/seguranca.php');
session_start();
if(administrador_logado() == false) {header("location: /index.php"); exit;}

require_once(__DIR__ . "/../../conexao/conexao.php");

$teste_LIVRO_ISBN = campo_e_valido("txtLIVRO_ISBN", "Livro");
$teste_NIF_PESSOA = campo_e_valido("txtNIF_PESSOA", "Pessoa");
$teste_DATA_EMPRESTADO = campo_e_valido("txtDATA_EMPRESTADO", "Data emprestimo");
$teste_TEMPO_EMPRESTIMO = campo_e_valido("txtTEMPO_EMPRESTIMO", "Tempo do emprestimo");

if ($teste_LIVRO_ISBN[0] == false) { exit; }
if ($teste_NIF_PESSOA[0] == false) { exit; }
if ($teste_DATA_EMPRESTADO[0] == false) { exit; }
if ($teste_TEMPO_EMPRESTIMO[0] == false) { exit; }

$txtLIVRO_ISBN = $teste_LIVRO_ISBN[1];
$txtNIF_PESSOA = $teste_NIF_PESSOA[1];
$txtDATA_EMPRESTADO = $teste_DATA_EMPRESTADO[1];
$txtTEMPO_EMPRESTIMO = $teste_TEMPO_EMPRESTIMO[1];

$txtSTATUSLIVRO = "A DEVOLVER";

try {
    // VERIFICAR SE O LIVRO EXISTE
    $verificaLivro = $conexao->prepare("SELECT COUNT(*) FROM LIVROS WHERE ISBN = ?");
    $verificaLivro->execute([$txtLIVRO_ISBN]);
    $livroExiste = $verificaLivro->fetchColumn();
    
    if ($livroExiste == 0) {
        echo "Erro: O livro com ISBN $txtLIVRO_ISBN não existe na base de dados.";
        exit;
    }
    
    // VERIFICAR SE O UTILIZADOR EXISTE
    $verificaUtilizador = $conexao->prepare("SELECT COUNT(*) FROM UTILIZADORES WHERE NIF = ?");
    $verificaUtilizador->execute([$txtNIF_PESSOA]);
    $utilizadorExiste = $verificaUtilizador->fetchColumn();
    
    if ($utilizadorExiste == 0) {
        echo "Erro: O utilizador com NIF $txtNIF_PESSOA não existe na base de dados.";
        exit;
    }

    // INSERIR EMPRÉSTIMO
    $comando = $conexao->prepare(
        "INSERT INTO EMPRESTIMO
        (
            LIVRO_ISBN,
            NIF_PESSOA,
            DATA_EMPRESTADO,
            TEMPO_EMPRESTIMO,
            STATUS_LIVRO
        )
        VALUES
        (
            :txtLIVRO_ISBN,
            :txtNIF_PESSOA,
            :txtDATA_EMPRESTADO,
            :txtTEMPO_EMPRESTIMO,
            :txtSTATUSLIVRO
        )"
    );

    $comando->execute(array(
        ':txtLIVRO_ISBN' => $txtLIVRO_ISBN,
        ':txtNIF_PESSOA' => $txtNIF_PESSOA,
        ':txtDATA_EMPRESTADO' => $txtDATA_EMPRESTADO,
        ':txtTEMPO_EMPRESTIMO' => $txtTEMPO_EMPRESTIMO,
        ':txtSTATUSLIVRO' => $txtSTATUSLIVRO
    ));

    if($comando->rowCount() > 0)
    {
        header('location:/views/emprestimos/visualizar.php');
        exit;
    }
    else
    {
        echo "Erro ao gravar os dados";
    }

} catch (PDOException $e) {
    echo "Erro ao gravar informação no banco de dados: " . $e->getMessage();
}

$conexao = null;
?>