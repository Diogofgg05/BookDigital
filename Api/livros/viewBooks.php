<?php
# Impede que usuários acessem a página se não estiverem logados
include('../../../seguranca/seguranca.php');

if(administrador_logado() == false) {header("location: /index.php"); exit;}

require_once('../../../conexao/conexao.php');

function obterLivros($conexao) {
    $comandoSQL = "SELECT * FROM LIVROS";
    $select = $conexao->query($comandoSQL);
    return $select->fetchAll();
}

function formatarDataPublicacao($ano) {
    if (empty($ano) || $ano == '0000' || $ano == '0') {
        return '23-01-2020'; // Data padrão
    }
    
    // Se já estiver no formato correto, retorna como está
    if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $ano)) {
        return $ano;
    }
    
    // Se for apenas o ano, formata para 23-01-ANO
    if (is_numeric($ano) && strlen($ano) == 4) {
        return "23-01-" . $ano;
    }
    
    // Se for outro formato, tenta converter
    $timestamp = strtotime($ano);
    if ($timestamp !== false) {
        return date('d-m-Y', $timestamp);
    }
    
    // Fallback para data padrão
    return '23-01-2020';
}
?>