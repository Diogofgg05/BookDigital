<?php
# Impede que usuários acessem a página se não estiverem logados
include('../../../seguranca/seguranca.php');
session_start();
if(administrador_logado() == false) {header("location: /index.php"); exit;}

include('../../../layout/header.html');
include('../../../layout/navbar.php');

// Inclui o arquivo com as funções de dados
include('../../../Api/livros/viewBooks.php');

// Obtém os dados dos livros
$resultado = obterLivros($conexao);
$totalLivros = count($resultado);
$livrosDisponiveis = $totalLivros;
$livrosEmCirculacao = 0;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livros - Sistema de Biblioteca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #3498db;
            --text-color: #2c3e50;
            --text-light: #7f8c8d;
            --border-color: #ecf0f1;
            --card-shadow: 0 2px 4px rgba(0,0,0,0.08);
            --hover-shadow: 0 4px 12px rgba(0,0,0,0.12);
            --success-color: #27ae60;
            --warning-color: #f39c12;
        }
        
        body {
            background-color: #f8f9fa;
            color: var(--text-color);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        
        .page-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }
        
        .page-header {
            margin-bottom: 2rem;
        }
        
        .page-title {
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .page-subtitle {
            color: var(--text-light);
            font-size: 0.95rem;
        }
        
        .search-container {
            background: white;
            border-radius: 8px;
            padding: 1rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 1.5rem;
            border: 1px solid var(--border-color);
        }
        
        .search-box {
            position: relative;
            max-width: 500px;
            margin: 0 auto;
        }
        
        .search-input {
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            background: #fafbfc;
        }
        
        .search-input:focus {
            border-color: var(--accent-color);
            background: white;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.1);
        }
        
        .search-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
        }
        
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: var(--card-shadow);
            text-align: center;
            border: 1px solid var(--border-color);
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
        }
        
        .stat-number {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: var(--text-light);
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        /* Grid de Livros - CORRIGIDO para tamanho fixo */
        .books-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
        }
        
        .book-card {
            background: white;
            border-radius: 8px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            border: 1px solid var(--border-color);
            overflow: hidden;
            cursor: pointer;
            height: fit-content; /* CORREÇÃO: Altura conforme conteúdo */
        }
        
        .book-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--hover-shadow);
        }
        
        .book-cover {
            height: 160px; /* ALTURA FIXA */
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        /* ESTILO PARA IMAGENS REAIS - CORREÇÃO */
        .book-cover-img {
            height: 160px;
            width: 100%;
            object-fit: cover; /* CORREÇÃO: imagem cobre a área sem distorcer */
            display: block;
        }
        
        .book-cover-placeholder {
            height: 160px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        .book-cover-placeholder::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.1);
        }
        
        .book-icon {
            font-size: 3rem;
            color: white;
            opacity: 0.9;
            z-index: 1;
        }
        
        .book-body {
            padding: 1.25rem;
        }
        
        .book-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.75rem;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 2.8em; /* ALTURA FIXA para título */
        }
        
        .book-details {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            margin-bottom: 1.25rem;
        }
        
        .book-detail {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
            color: var(--text-color);
        }
        
        .book-detail i {
            color: var(--accent-color);
            width: 14px;
            font-size: 0.75rem;
        }
        
        .book-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn-action {
            flex: 1;
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--border-color);
            background: white;
            color: var(--text-color);
            border-radius: 4px;
            font-size: 0.75rem;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.25rem;
        }
        
        .btn-action:hover {
            background: #f8f9fa;
            border-color: var(--accent-color);
        }
        
        .btn-edit:hover {
            background: var(--accent-color);
            color: white;
            border-color: var(--accent-color);
        }
        
        .btn-delete:hover {
            background: #e74c3c;
            color: white;
            border-color: #e74c3c;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--text-light);
        }
        
        .empty-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.4;
        }
        
        .empty-text {
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
        }

        /* MODAL - Mantido igual */
        .book-modal .modal-content {
            border: none;
            border-radius: 12px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .book-modal .modal-header {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-bottom: 1px solid #e5e7eb;
            padding: 1.5rem 2rem;
            border-radius: 12px 12px 0 0;
            position: relative;
        }

        .book-modal .modal-header-content {
            display: flex;
            align-items: center;
            gap: 1rem;
            width: 100%;
        }

        .book-modal .modal-cover {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .book-modal .modal-book-info {
            flex: 1;
        }

        .book-modal .modal-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary-color);
            margin: 0 0 0.25rem 0;
        }

        .book-modal .modal-subtitle {
            font-size: 0.9rem;
            color: var(--text-light);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .book-modal .btn-close-custom {
            background: rgba(255,255,255,0.8);
            border: none;
            border-radius: 6px;
            color: var(--text-light);
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            top: 1.5rem;
            right: 2rem;
            transition: all 0.2s ease;
        }

        .book-modal .btn-close-custom:hover {
            background: white;
            color: var(--text-color);
            transform: scale(1.1);
        }

        .book-modal .modal-body {
            padding: 2rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .info-card {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 1.25rem;
            transition: all 0.2s ease;
        }

        .info-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .info-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .info-header i {
            color: var(--accent-color);
            font-size: 1.1rem;
        }

        .info-header h6 {
            margin: 0;
            font-weight: 600;
            color: var(--primary-color);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-content {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .info-label {
            font-size: 0.75rem;
            color: var(--text-light);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-label i {
            width: 14px;
            font-size: 0.8rem;
            color: #6b7280;
        }

        .info-value {
            font-size: 0.9rem;
            color: var(--text-color);
            font-weight: 500;
            padding-left: 1.5rem;
        }

        .info-value.empty {
            color: var(--text-light);
            font-style: italic;
        }

        .description-section {
            background: linear-gradient(135deg, #f8fafc, #e5e7eb);
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 1.5rem;
            margin-top: 1rem;
        }

        .description-content {
            font-size: 0.9rem;
            line-height: 1.5;
            color: var(--text-color);
            max-height: 120px;
            overflow-y: auto;
        }

        .description-content.empty {
            color: var(--text-light);
            font-style: italic;
        }

        .modal-actions {
            display: flex;
            gap: 1rem;
            justify-content: space-between;
            align-items: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
        }

        .action-buttons {
            display: flex;
            gap: 0.75rem;
        }

        .btn-modal {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
        }

        .btn-modal-danger {
            background: rgba(220, 38, 38, 0.1);
            color: #dc2626;
            border: 1px solid rgba(220, 38, 38, 0.2);
        }

        .btn-modal-danger:hover {
            background: #dc2626;
            color: white;
        }

        .btn-modal-secondary {
            background: white;
            color: var(--text-color);
            border: 1px solid #e5e7eb;
        }

        .btn-modal-secondary:hover {
            background: #f8f9fa;
            border-color: var(--accent-color);
        }

        .btn-modal-primary {
            background: var(--primary-color);
            color: white;
            border: none;
        }

        .btn-modal-primary:hover {
            background: var(--secondary-color);
            transform: translateY(-1px);
        }

        /* Status Badges */
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .status-available {
            background: #d1fae5;
            color: #065f46;
        }

        .status-borrowed {
            background: #fef3c7;
            color: #92400e;
        }

        .status-reserved {
            background: #e0e7ff;
            color: #3730a3;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .page-container {
                padding: 1rem;
            }
            
            .books-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .stats-container {
                grid-template-columns: 1fr;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .book-modal .modal-header-content {
                flex-direction: column;
                text-align: center;
                gap: 0.75rem;
            }
            
            .modal-actions {
                flex-direction: column;
                gap: 0.75rem;
            }
            
            .action-buttons {
                width: 100%;
                justify-content: space-between;
            }
        }

        @media (max-width: 576px) {
            .book-modal .modal-body {
                padding: 1.5rem 1rem;
            }
            
            .book-modal .modal-header {
                padding: 1.25rem 1.5rem;
            }
            
            .book-modal .btn-close-custom {
                top: 1.25rem;
                right: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="page-container">
        <!-- Cabeçalho Minimalista -->
        <div class="page-header">
            <h1 class="page-title">Acervo de Livros</h1>
            <p class="page-subtitle">Gerencie e visualize todos os livros da biblioteca</p>
        </div>

        <!-- Barra de Pesquisa -->
        <div class="search-container">
            <div class="search-box">
                <i class="bi bi-search search-icon"></i>
                <input type="text" class="form-control search-input" id="searchInput" 
                       placeholder="Pesquisar livros por título, autor, ISBN, editora ou categoria...">
            </div>
        </div>

        <?php if($resultado) { ?>
            <!-- Grid de Livros -->
            <div class="books-grid" id="booksGrid">
                <?php foreach ($resultado as $index => $linha) {
                    $ISBN = $linha["ISBN"];
                    $ISBN_LINK_EXCL = "/views/Admin/Books/delBooks.php?ISBN=$ISBN";
                    $ISBN_LINK_EDIT = "/views/Admin/Books/editBooks.php?ISBN=$ISBN";
                    
                    // Formatar a data de publicação
                    $dataPublicacao = formatarDataPublicacao($linha["ANO_PUBLICACAO"]);
                    
                    // Cores para as capas
                    $colors = [
                        'linear-gradient(135deg, #2c3e50 0%, #34495e 100%)',
                        'linear-gradient(135deg, #3498db 0%, #2980b9 100%)',
                        'linear-gradient(135deg, #27ae60 0%, #229954 100%)',
                        'linear-gradient(135deg, #8e44ad 0%, #7d3c98 100%)',
                        'linear-gradient(135deg, #e74c3c 0%, #c0392b 100%)',
                        'linear-gradient(135deg, #f39c12 0%, #d35400 100%)'
                    ];
                    $coverColor = $colors[$index % count($colors)];
                    
                    // Verificar se tem imagem
                    $temImagem = !empty($linha["IMG_LIVROS"]);
                ?>
                <div class="book-card" data-book-isbn="<?php echo $ISBN; ?>">
                    <?php if($temImagem) { ?>
                        <img src="<?php echo htmlspecialchars($linha["IMG_LIVROS"]); ?>" 
                             alt="Capa do livro <?php echo htmlspecialchars($linha["TITULO"]); ?>" 
                             class="book-cover-img"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="book-cover-placeholder" style="display: none; background: <?php echo $coverColor; ?>">
                            <i class="bi bi-book book-icon"></i>
                        </div>
                    <?php } else { ?>
                        <div class="book-cover-placeholder" style="background: <?php echo $coverColor; ?>">
                            <i class="bi bi-book book-icon"></i>
                        </div>
                    <?php } ?>
                    
                    <div class="book-body">
                        <h3 class="book-title"><?php echo htmlspecialchars($linha["TITULO"]); ?></h3>
                        
                        <div class="book-details">
                            <div class="book-detail">
                                <i class="bi bi-upc-scan"></i>
                                <span><?php echo htmlspecialchars($linha["ISBN"]); ?></span>
                            </div>
                            <div class="book-detail">
                                <i class="bi bi-building"></i>
                                <span><?php echo htmlspecialchars($linha["EDITORA"]); ?></span>
                            </div>
                            <?php if(isset($linha["AUTOR"]) && !empty($linha["AUTOR"])) { ?>
                            <div class="book-detail">
                                <i class="bi bi-person"></i>
                                <span><?php echo htmlspecialchars($linha["AUTOR"]); ?></span>
                            </div>
                            <?php } ?>
                            <?php if(isset($linha["GENERO"]) && !empty($linha["GENERO"])) { ?>
                            <div class="book-detail">
                                <i class="bi bi-tags"></i>
                                <span><?php echo htmlspecialchars($linha["GENERO"]); ?></span>
                            </div>
                            <?php } ?>
                            <div class="book-detail">
                                <i class="bi bi-calendar"></i>
                                <span><?php echo htmlspecialchars($dataPublicacao); ?></span>
                            </div>
                        </div>
                        
                        <div class="book-actions">
                            <a href="<?php echo $ISBN_LINK_EDIT; ?>" class="btn-action btn-edit" onclick="event.stopPropagation()">
                                <i class="bi bi-pencil"></i> Editar
                            </a>
                            <a href="<?php echo $ISBN_LINK_EXCL; ?>" class="btn-action btn-delete" 
                               onclick="event.stopPropagation(); return confirm('Tem certeza que deseja excluir este livro?')">
                                <i class="bi bi-trash"></i> Excluir
                            </a>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        <?php } else { ?>
            <!-- Estado vazio -->
            <div class="empty-state">
                <i class="bi bi-book-x empty-icon"></i>
                <h4 class="empty-text">Nenhum livro cadastrado</h4>
                <p class="text-muted mb-3">Comece adicionando o primeiro livro ao acervo</p>
                <a href="/views/livros/cadastrar.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Registar Primeiro Livro
                </a>
            </div>
        <?php } ?>
    </div>

    <!-- Modal para exibir informações completas do livro -->
    <div class="modal fade book-modal" id="bookModal" tabindex="-1" aria-labelledby="bookModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Cabeçalho com gradiente -->
                <div class="modal-header">
                    <div class="modal-header-content">
                        <div class="modal-cover">
                            <i class="bi bi-book"></i>
                        </div>
                        <div class="modal-book-info">
                            <h5 class="modal-title" id="modalTitle"></h5>
                            <p class="modal-subtitle">
                                <i class="bi bi-person"></i>
                                <span id="modalAuthor"></span>
                            </p>
                        </div>
                    </div>
                    <button type="button" class="btn-close-custom" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <!-- Corpo do Modal -->
                <div class="modal-body">
                    <!-- Grid de Informações -->
                    <div class="info-grid">
                        <!-- Informações Básicas -->
                        <div class="info-card">
                            <div class="info-header">
                                <i class="bi bi-info-circle"></i>
                                <h6>Informações Básicas</h6>
                            </div>
                            <div class="info-content">
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="bi bi-upc-scan"></i>
                                        ISBN
                                    </div>
                                    <div class="info-value" id="modalIsbn"></div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="bi bi-building"></i>
                                        Editora
                                    </div>
                                    <div class="info-value" id="modalEditora"></div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="bi bi-calendar"></i>
                                        Data de Publicação
                                    </div>
                                    <div class="info-value" id="modalDataPublicacao"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Detalhes Adicionais -->
                        <div class="info-card">
                            <div class="info-header">
                                <i class="bi bi-tags"></i>
                                <h6>Detalhes</h6>
                            </div>
                            <div class="info-content">
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="bi bi-translate"></i>
                                        Idioma
                                    </div>
                                    <div class="info-value" id="modalIdioma"></div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="bi bi-bookmark"></i>
                                        Categoria
                                    </div>
                                    <div class="info-value" id="modalCategoria"></div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="bi bi-circle-fill"></i>
                                        Status
                                    </div>
                                    <div class="info-value">
                                        <span class="status-badge" id="modalStatus"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Descrição -->
                    <div class="description-section">
                        <div class="info-header">
                            <i class="bi bi-text-paragraph"></i>
                            <h6>Descrição</h6>
                        </div>
                        <div class="description-content" id="modalDescricao">
                            <div class="text-muted" style="font-style: italic;">
                                Nenhuma descrição disponível.
                            </div>
                        </div>
                    </div>

                    <!-- Ações -->
                    <div class="modal-actions">
                        <button type="button" class="btn-modal btn-modal-danger" id="modalDeleteBtn">
                            <i class="bi bi-trash"></i>
                            Excluir Livro
                        </button>
                        <div class="action-buttons">
                            <button type="button" class="btn-modal btn-modal-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x"></i>
                                Fechar
                            </button>
                            <a href="#" class="btn-modal btn-modal-primary" id="modalEditBtn">
                                <i class="bi bi-pencil"></i>
                                Editar Livro
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Dados dos livros
        const livros = <?php echo json_encode($resultado); ?>;

        // Função para formatar data
        function formatarDataPublicacao(ano) {
            if (!ano || ano === '0000' || ano === '0') {
                return '23-01-2020'; // Data padrão
            }
            
            // Se já estiver no formato correto, retorna como está
            if (typeof ano === 'string' && /^\d{2}-\d{2}-\d{4}$/.test(ano)) {
                return ano;
            }
            
            // Se for apenas o ano, formata para 23-01-ANO
            if (!isNaN(ano) && String(ano).length === 4) {
                return "23-01-" + ano;
            }
            
            // Se for outro formato, tenta converter
            const timestamp = Date.parse(ano);
            if (!isNaN(timestamp)) {
                const date = new Date(timestamp);
                const dia = String(date.getDate()).padStart(2, '0');
                const mes = String(date.getMonth() + 1).padStart(2, '0');
                const anoFormatado = date.getFullYear();
                return `${dia}-${mes}-${anoFormatado}`;
            }
            
            // Fallback para data padrão
            return '23-01-2020';
        }

        // Função para abrir o modal com as informações do livro
        function abrirModalLivro(livro) {
            console.log('Abrindo modal para:', livro);
            
            // Preenche os dados no modal
            document.getElementById('modalTitle').textContent = livro.TITULO || 'Sem título';
            document.getElementById('modalAuthor').textContent = livro.AUTOR ? `por ${livro.AUTOR}` : 'Autor não informado';
            document.getElementById('modalIsbn').textContent = livro.ISBN || 'Não informado';
            document.getElementById('modalIsbn').className = livro.ISBN ? 'info-value' : 'info-value empty';
            
            document.getElementById('modalEditora').textContent = livro.EDITORA || 'Não informada';
            document.getElementById('modalEditora').className = livro.EDITORA ? 'info-value' : 'info-value empty';
            
            // Formatar a data de publicação
            const dataPublicacao = formatarDataPublicacao(livro.ANO_PUBLICACAO);
            document.getElementById('modalDataPublicacao').textContent = dataPublicacao;
            document.getElementById('modalDataPublicacao').className = 'info-value';
            
            document.getElementById('modalIdioma').textContent = livro.IDIOMA || 'Não informado';
            document.getElementById('modalIdioma').className = livro.IDIOMA ? 'info-value' : 'info-value empty';
            
            // CORREÇÃO AQUI: Usar GENERO em vez de CATEGORIA
            document.getElementById('modalCategoria').textContent = livro.GENERO || 'Não informada';
            document.getElementById('modalCategoria').className = livro.GENERO ? 'info-value' : 'info-value empty';
            
            // Status
            const statusElement = document.getElementById('modalStatus');
            if (livro.STATUS === 'EMPRESTADO') {
                statusElement.textContent = 'Em Circulação';
                statusElement.className = 'status-badge status-borrowed';
            } else {
                statusElement.textContent = 'Disponível';
                statusElement.className = 'status-badge status-available';
            }
            
            // Descrição
            const descricaoElement = document.getElementById('modalDescricao');
            const descricao = livro.DESCRICAO || livro.SINOPSE;
            if (descricao && descricao.trim() !== '') {
                descricaoElement.textContent = descricao;
                descricaoElement.className = 'description-content';
            } else {
                descricaoElement.innerHTML = '<div class="text-muted" style="font-style: italic;">Nenhuma descrição disponível.</div>';
                descricaoElement.className = 'description-content empty';
            }
            
            // Link para editar
            if (livro.ISBN) {
                document.getElementById('modalEditBtn').href = '/views/Admin/Books/editBooks.php?ISBN=' + livro.ISBN;
            }
            
            // Configurar botão de excluir
            document.getElementById('modalDeleteBtn').onclick = function() {
                if (confirm('Tem certeza que deseja excluir este livro?')) {
                    window.location.href = '/views/livros/excluir.php?ISBN=' + livro.ISBN;
                }
            };
            
            // Abre o modal
            const modal = new bootstrap.Modal(document.getElementById('bookModal'));
            modal.show();
        }

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const bookCards = document.querySelectorAll('.book-card');
            
            if (searchInput && bookCards.length > 0) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    
                    bookCards.forEach(card => {
                        const isbn = card.getAttribute('data-book-isbn');
                        const livro = livros.find(l => l.ISBN === isbn);
                        
                        if (livro) {
                            const title = livro.TITULO?.toLowerCase() || '';
                            const author = livro.AUTOR?.toLowerCase() || '';
                            const editora = livro.EDITORA?.toLowerCase() || '';
                            const categoria = livro.GENERO?.toLowerCase() || '';
                            
                            if (title.includes(searchTerm) || 
                                isbn.includes(searchTerm) || 
                                editora.includes(searchTerm) || 
                                author.includes(searchTerm) ||
                                categoria.includes(searchTerm)) {
                                card.style.display = 'block';
                            } else {
                                card.style.display = 'none';
                            }
                        }
                    });
                });
            }
            
            // Abrir modal ao clicar no card
            bookCards.forEach(card => {
                card.addEventListener('click', function() {
                    const isbn = this.getAttribute('data-book-isbn');
                    const livro = livros.find(l => l.ISBN === isbn);
                    if (livro) {
                        abrirModalLivro(livro);
                    }
                });
            });
        });
    </script>
</body>
</html>

<?php include('../../../layout/footer.html'); ?>