<?php
include('../../seguranca/seguranca.php');
session_start();
if(administrador_logado() == false) {
    header("location: /index.php"); 
    exit;
}

require_once(__DIR__ . "/../../conexao/conexao.php");

// Coletar dados do formulário
$titulo = filter_input(INPUT_POST, "txtTITULO", FILTER_SANITIZE_STRING);
$autor = filter_input(INPUT_POST, "txtAUTOR", FILTER_SANITIZE_STRING);
$descricao = filter_input(INPUT_POST, "txtDESCRICAO", FILTER_SANITIZE_STRING);
$genero = filter_input(INPUT_POST, "txtCATEGORIA", FILTER_SANITIZE_STRING);
$editora = filter_input(INPUT_POST, "txtEDITORA", FILTER_SANITIZE_STRING);
$data_publicacao = filter_input(INPUT_POST, "txtDATA_PUBLICACAO", FILTER_SANITIZE_STRING);
$isbn = filter_input(INPUT_POST, "txtISBN", FILTER_SANITIZE_STRING);
$unidades = filter_input(INPUT_POST, "txtUNIDADES", FILTER_SANITIZE_NUMBER_INT);

// Validar campos obrigatórios
if (empty($titulo) || empty($autor) || empty($genero) || empty($editora) || 
    empty($data_publicacao) || empty($isbn) || $unidades === null) {
    die("Erro: Todos os campos obrigatórios devem ser preenchidos.");
}

// Processar upload da imagem
$caminhoImagem = '';

if(isset($_FILES['imageUpload']) && $_FILES['imageUpload']['error'] === UPLOAD_ERR_OK) {
    $arquivo = $_FILES['imageUpload'];
    
    // Configurações
    $diretorioUpload = '../uploads';
    $extensoesPermitidas = array('jpg', 'jpeg', 'png', 'gif', 'webp');
    $tamanhoMaximo = 5 * 1024 * 1024;
    
    // Criar diretório se não existir
    if(!is_dir($diretorioUpload)) {
        mkdir($diretorioUpload, 0777, true);
    }
    
    // Validar arquivo
    $nomeArquivo = $arquivo['name'];
    $tamanhoArquivo = $arquivo['size'];
    $arquivoTmp = $arquivo['tmp_name'];
    $extensao = strtolower(pathinfo($nomeArquivo, PATHINFO_EXTENSION));
    
    if(!in_array($extensao, $extensoesPermitidas)) {
        die("Erro: Tipo de arquivo não permitido.");
    }
    
    if($tamanhoArquivo > $tamanhoMaximo) {
        die("Erro: Arquivo muito grande. Máximo: 5MB");
    }
    
    // Gerar nome único e mover arquivo - CORRIGIDO
    $novoNome = uniqid('livro_') . '_' . time() . '.' . $extensao;
    $caminhoCompleto = $diretorioUpload . '/' . $novoNome; 
    
    if(move_uploaded_file($arquivoTmp, $caminhoCompleto)) {
        $caminhoImagem = '/uploads/' . $novoNome; 
    } else {
        die("Erro ao fazer upload da imagem");
    }
}

try {
    $comando = $conexao->prepare(
        "INSERT INTO LIVROS (
            TITULO, AUTOR, DESCRICAO, GENERO, EDITORA, 
            ANO_PUBLICACAO, ISBN, UNIDADES_DISPONIVEIS, IMG_LIVROS
        ) VALUES (
            :titulo, :autor, :descricao, :genero, :editora,
            :data_publicacao, :isbn, :unidades, :imagem
        )"
    );

    $comando->execute(array(
        ':titulo' => $titulo,
        ':autor' => $autor,
        ':descricao' => $descricao,
        ':genero' => $genero,
        ':editora' => $editora,
        ':data_publicacao' => $data_publicacao,
        ':isbn' => $isbn,
        ':unidades' => $unidades,
        ':imagem' => $caminhoImagem
    ));

    if($comando->rowCount() > 0) {
        header('location: /views/livros/visualizar.php');
        exit;
    } else {
        echo "Erro: Nenhum dado foi inserido.";
    }

} catch (PDOException $e) {
    echo "Erro ao gravar: " . $e->getMessage();
}

$conexao = null;
?>