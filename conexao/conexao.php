<?php
$host = "127.0.0.1";
$user = "root";
$pass = "";
$banco = "bookdigital"; // Nome da sua base de dados

try
{
    $conexao = new PDO("mysql:host=$host;dbname=$banco", $user, $pass);
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Conexão bem-sucedida!";
}
catch (PDOException $e)
{
    echo "Erro durante a conexão com o banco de dados.\n\n".$e->getMessage();
}
?>
