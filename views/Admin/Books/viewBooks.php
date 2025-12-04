<?php
session_start();

# Impede que usuários acessem a página se não estiverem logados
include('../../../seguranca/seguranca.php');

if(administrador_logado() == false) {
    header("location: /index.php"); 
    exit;
}

// Inclui o arquivo com as funções de dados
include('../../../Api/Books/viewBooks.php');

// Obtém os dados dos livros
$resultado = obterLivros($conexao);
$totalLivros = count($resultado);
$livrosDisponiveis = $totalLivros;
$livrosEmCirculacao = 0;

include('../../../layout/header.html');
include('../../../layout/navbar.php');
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
            --white: #ffffff;
            --light-bg: #f8f9fa;
            --success-color: #27ae60;
            --error-color: #e74c3c;
            --shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
            --shadow-md: 0 2px 4px rgba(0,0,0,0.08);
            --shadow-lg: 0 4px 12px rgba(0,0,0,0.12);
            --radius-sm: 6px;
            --radius-md: 8px;
            --radius-lg: 12px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #f5f7fa;
            color: var(--text-color);
            line-height: 1.5;
            overflow-x: hidden;
        }
        
        /* SIDEBAR MOBILE */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .sidebar-overlay.active {
            display: block;
            opacity: 1;
        }
        
        .mobile-sidebar {
            position: fixed;
            top: 0;
            left: -280px;
            width: 280px;
            height: 100vh;
            background: var(--white);
            z-index: 1050;
            transition: left 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            box-shadow: 2px 0 12px rgba(0, 0, 0, 0.15);
            overflow-y: auto;
            padding-top: 60px;
        }
        
        .mobile-sidebar.active {
            left: 0;
        }
        
        .sidebar-close {
            position: absolute;
            top: 16px;
            right: 16px;
            background: none;
            border: none;
            font-size: 24px;
            color: var(--text-light);
            cursor: pointer;
            padding: 4px;
            z-index: 1051;
            transition: color 0.2s ease;
        }
        
        .sidebar-close:hover {
            color: var(--primary-color);
        }
        
        .sidebar-content {
            padding: 20px;
        }
        
        .sidebar-header {
            padding: 0 0 20px 0;
            margin-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .sidebar-header h5 {
            font-size: 18px;
            font-weight: 600;
            color: var(--primary-color);
            margin: 0;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
        }
        
        .sidebar-menu li {
            margin-bottom: 8px;
        }
        
        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: var(--text-color);
            text-decoration: none;
            border-radius: var(--radius-sm);
            transition: all 0.2s ease;
            font-size: 15px;
            font-weight: 500;
        }
        
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: var(--light-bg);
            color: var(--accent-color);
        }
        
        .sidebar-menu i {
            font-size: 18px;
            width: 24px;
            text-align: center;
        }
        
        /* Botão hamburguer */
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            color: var(--primary-color);
            cursor: pointer;
            padding: 8px;
            margin-right: 12px;
            transition: color 0.2s ease;
        }
        
        .menu-toggle:hover {
            color: var(--accent-color);
        }
        
        /* Esconder sidebar desktop em mobile */
        @media (max-width: 768px) {
            .menu-toggle {
                display: block;
            }
            
            .desktop-sidebar {
                display: none !important;
            }
        }
        
        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 24px;
            transition: all 0.3s ease;
        }
        
        /* HEADER - Minimalista Perfeito */
        .page-header {
            margin-bottom: 32px;
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }
        
        @media (max-width: 768px) {
            .header-content {
                justify-content: flex-start;
            }
        }
        
        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0;
            letter-spacing: -0.025em;
        }
        
        .page-subtitle {
            font-size: 14px;
            color: var(--text-light);
            margin-top: 4px;
        }
        
        /* STATS CARDS - Horizontal Clean */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 32px;
        }
        
        .stat-card {
            background: var(--white);
            border-radius: var(--radius-md);
            padding: 20px;
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
            transition: all 0.2s ease;
        }
        
        .stat-card:hover {
            box-shadow: var(--shadow-md);
            border-color: var(--accent-color);
        }
        
        .stat-number {
            font-size: 32px;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 4px;
            line-height: 1;
        }
        
        .stat-label {
            font-size: 12px;
            color: var(--text-light);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        /* SEARCH & ACTIONS BAR */
        .action-bar {
            display: flex;
            gap: 12px;
            margin-bottom: 32px;
            align-items: center;
        }
        
        @media (max-width: 768px) {
            .action-bar {
                flex-direction: column;
            }
            
            .search-container {
                width: 100%;
            }
        }
        
        .search-container {
            flex: 1;
            position: relative;
        }
        
        .search-input {
            width: 100%;
            padding: 12px 16px 12px 40px;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            font-size: 14px;
            background: var(--white);
            color: var(--text-color);
            transition: all 0.2s ease;
        }
        
        .search-input:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }
        
        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            font-size: 14px;
        }
        
        /* ADD BUTTON - Minimal Premium */
        .btn-add {
            padding: 12px 24px;
            background: var(--primary-color);
            color: var(--white);
            border: none;
            border-radius: var(--radius-md);
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        
        .btn-add:hover {
            background: var(--secondary-color);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }
        
        /* BOOKS GRID - Perfeição Minimalista */
        .books-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 24px;
            margin-bottom: 40px;
        }
        
        .book-card {
            background: var(--white);
            border-radius: var(--radius-md);
            border: 1px solid var(--border-color);
            overflow: hidden;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            cursor: pointer;
        }
        
        .book-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-4px);
            border-color: var(--accent-color);
        }
        
        .book-cover {
            height: 200px;
            background: linear-gradient(135deg, #f8f9fa, #ecf0f1);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .book-cover-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .book-card:hover .book-cover-img {
            transform: scale(1.05);
        }
        
        .book-cover-placeholder {
            color: #bdc3c7;
            font-size: 48px;
        }
        
        .book-info {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        
        .book-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 8px;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .book-author {
            font-size: 14px;
            color: var(--text-light);
            margin-bottom: 16px;
            font-weight: 400;
        }
        
        .book-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 20px;
        }
        
        .book-tag {
            background: var(--light-bg);
            color: var(--text-color);
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .book-actions {
            display: flex;
            gap: 8px;
            margin-top: auto;
            padding-top: 20px;
            border-top: 1px solid var(--border-color);
        }
        
        @media (max-width: 576px) {
            .book-actions {
                flex-direction: column;
            }
        }
        
        /* ACTION BUTTONS - Minimal Perfection */
        .btn-action {
            flex: 1;
            padding: 8px 12px;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            background: var(--light-bg);
            color: var(--text-light);
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        
        .btn-action:hover {
            background: var(--white);
            border-color: var(--accent-color);
        }
        
        .btn-action-edit:hover {
            color: var(--accent-color);
        }
        
        .btn-action-delete:hover {
            color: var(--error-color);
        }
        
        /* EMPTY STATE */
        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 60px 20px;
            background: var(--white);
            border-radius: var(--radius-md);
            border: 1px dashed var(--border-color);
        }
        
        .empty-icon {
            font-size: 48px;
            color: #e0e6ed;
            margin-bottom: 20px;
        }
        
        .empty-state h3 {
            font-size: 20px;
            color: var(--primary-color);
            margin-bottom: 8px;
            font-weight: 600;
        }
        
        .empty-state p {
            color: var(--text-light);
            font-size: 14px;
            margin-bottom: 24px;
        }
        
        /* MODAL - Minimalista Perfeito */
        .book-modal .modal-content {
            border: none;
            border-radius: var(--radius-lg);
            box-shadow: 0 8px 32px rgba(0,0,0,0.08);
            overflow: hidden;
            background: var(--white);
        }
        
        .book-modal .modal-header {
            border-bottom: 1px solid var(--border-color);
            padding: 24px 28px;
            background: var(--white);
        }
        
        .book-modal .modal-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .book-modal .modal-body {
            padding: 0;
        }
        
        .modal-content-wrapper {
            display: flex;
            min-height: 400px;
        }
        
        .modal-cover-section {
            flex: 0 0 300px;
            background: linear-gradient(135deg, #f8f9fa, #ecf0f1);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px;
            border-right: 1px solid var(--border-color);
        }
        
        .modal-cover {
            width: 200px;
            height: 280px;
            object-fit: cover;
            border-radius: var(--radius-sm);
            box-shadow: var(--shadow-lg);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .modal-info-section {
            flex: 1;
            padding: 32px;
            display: flex;
            flex-direction: column;
        }
        
        .modal-book-title {
            font-size: 24px;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 8px;
            line-height: 1.3;
        }
        
        .modal-book-author {
            font-size: 16px;
            color: var(--text-light);
            margin-bottom: 24px;
            font-weight: 400;
        }
        
        .modal-details-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 32px;
        }
        
        .modal-detail {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        
        .modal-detail-label {
            font-size: 11px;
            color: var(--text-light);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .modal-detail-value {
            font-size: 14px;
            color: var(--text-color);
            font-weight: 500;
        }
        
        .modal-description {
            margin-top: auto;
            padding-top: 24px;
            border-top: 1px solid var(--border-color);
        }
        
        .modal-description h6 {
            font-size: 12px;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .modal-description p {
            color: var(--text-color);
            font-size: 14px;
            line-height: 1.6;
            margin: 0;
            max-height: 80px;
            overflow-y: auto;
        }
        
        .modal-footer {
            padding: 20px 28px;
            border-top: 1px solid var(--border-color);
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            background: var(--white);
        }
        
        /* RESPONSIVE - Perfeito */
        @media (max-width: 1200px) {
            .books-grid {
                grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            }
            
            .modal-content-wrapper {
                min-height: auto;
            }
        }
        
        @media (max-width: 992px) {
            .main-container {
                padding: 20px;
            }
            
            .modal-content-wrapper {
                flex-direction: column;
            }
            
            .modal-cover-section {
                flex: none;
                padding: 24px;
                border-right: none;
                border-bottom: 1px solid var(--border-color);
            }
            
            .modal-cover {
                width: 180px;
                height: 252px;
            }
            
            .modal-info-section {
                padding: 24px;
            }
        }
        
        @media (max-width: 768px) {
            .books-grid {
                grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
                gap: 16px;
            }
            
            .modal-details-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }
        }
        
        @media (max-width: 576px) {
            .main-container {
                padding: 16px;
            }
            
            .stats-container {
                grid-template-columns: 1fr;
            }
            
            .books-grid {
                grid-template-columns: 1fr;
            }
            
            .page-title {
                font-size: 24px;
            }
            
            .modal-footer {
                flex-direction: column;
            }
            
            .book-modal .modal-header,
            .modal-footer {
                padding: 20px;
            }
        }
        
        /* ANIMAÇÕES SUTIS */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .book-card {
            animation: fadeInUp 0.4s ease-out;
            animation-fill-mode: both;
        }
        
        .book-card:nth-child(1) { animation-delay: 0.1s; }
        .book-card:nth-child(2) { animation-delay: 0.2s; }
        .book-card:nth-child(3) { animation-delay: 0.3s; }
        .book-card:nth-child(4) { animation-delay: 0.4s; }
        .book-card:nth-child(5) { animation-delay: 0.5s; }
        .book-card:nth-child(6) { animation-delay: 0.6s; }
        .book-card:nth-child(7) { animation-delay: 0.7s; }
        .book-card:nth-child(8) { animation-delay: 0.8s; }
    </style>
</head>
<body>
    <!-- Overlay para fechar sidebar -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <!-- Sidebar Mobile -->
    <div class="mobile-sidebar" id="mobileSidebar">
        <button class="sidebar-close" id="sidebarClose">
            <i class="bi bi-x-lg"></i>
        </button>
        
        <!-- Conteúdo do sidebar -->
        <div class="sidebar-content">
            <div class="sidebar-header">
                <h5>Menu</h5>
            </div>
            <ul class="sidebar-menu">
                <li><a href="/dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                <li><a href="/views/Admin/Books/viewBooks.php" class="active"><i class="bi bi-book"></i> Livros</a></li>
                <li><a href="/views/Admin/Users/viewUsers.php"><i class="bi bi-people"></i> Usuários</a></li>
                <li><a href="/views/Admin/Loans/viewLoans.php"><i class="bi bi-arrow-left-right"></i> Empréstimos</a></li>
                <li><a href="/views/Admin/Reports/reports.php"><i class="bi bi-bar-chart"></i> Relatórios</a></li>
            </ul>
        </div>
    </div>
    
    <div class="main-container">
        <!-- HEADER -->
        <header class="page-header">
            <div class="header-content">
                <div style="display: flex; align-items: center;">
                    <button class="menu-toggle" id="menuToggle">
                        <i class="bi bi-list"></i>
                    </button>
                    <div>
                        <h1 class="page-title">Livros</h1>
                        <p class="page-subtitle">Gerencie o acervo da biblioteca</p>
                    </div>
                </div>
            </div>
            
            <!-- STATS -->
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $totalLivros; ?></div>
                    <div class="stat-label">Total</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number"><?php echo $livrosDisponiveis; ?></div>
                    <div class="stat-label">Disponíveis</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number"><?php echo $livrosEmCirculacao; ?></div>
                    <div class="stat-label">Em Circulação</div>
                </div>
            </div>
            
            <!-- SEARCH & ACTIONS -->
            <div class="action-bar">
                <div class="search-container">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" class="search-input" id="searchInput" 
                           placeholder="Pesquisar por título, autor ou ISBN...">
                </div>
                
            </div>
        </header>
        
        <!-- BOOKS GRID -->
        <?php if($resultado) { ?>
            <div class="books-grid" id="booksGrid">
                <?php foreach ($resultado as $index => $linha) {
                    $ISBN = $linha["ISBN"];
                    $ISBN_LINK_EXCL = "/views/Admin/Books/delBooks.php?ISBN=$ISBN";
                    $ISBN_LINK_EDIT = "/views/Admin/Books/editBooks.php?ISBN=$ISBN";
                    
                    $temImagem = !empty($linha["IMG_LIVROS"]) && $linha["IMG_LIVROS"] != '[ ]';
                    $caminhoImagem = '';
                    $imagemExiste = false;
                    
                    if ($temImagem) {
                        $caminhoNaBase = $linha["IMG_LIVROS"];
                        if (strpos($caminhoNaBase, '/uploads/') === 0) {
                            $caminhoImagem = '/Api' . $caminhoNaBase;
                        } else {
                            $caminhoImagem = $caminhoNaBase;
                        }
                        $caminhoAbsoluto = $_SERVER['DOCUMENT_ROOT'] . $caminhoImagem;
                        $imagemExiste = file_exists($caminhoAbsoluto);
                    }
                ?>
                <div class="book-card" data-book-isbn="<?php echo $ISBN; ?>">
                    <div class="book-cover">
                        <?php if($temImagem && $imagemExiste) { ?>
                            <img src="<?php echo htmlspecialchars($caminhoImagem); ?>" 
                                 alt="Capa do livro <?php echo htmlspecialchars($linha["TITULO"]); ?>" 
                                 class="book-cover-img"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="book-cover-placeholder" style="display: none;">
                                <i class="bi bi-book"></i>
                            </div>
                        <?php } else { ?>
                            <div class="book-cover-placeholder">
                                <i class="bi bi-book"></i>
                            </div>
                        <?php } ?>
                    </div>
                    
                    <div class="book-info">
                        <h3 class="book-title"><?php echo htmlspecialchars($linha["TITULO"]); ?></h3>
                        <p class="book-author">
                            <?php echo isset($linha["AUTOR"]) && !empty($linha["AUTOR"]) && $linha["AUTOR"] != '[ ]' 
                                ? htmlspecialchars($linha["AUTOR"]) 
                                : 'Autor Desconhecido'; ?>
                        </p>
                        
                        <div class="book-meta">
                            <?php if(isset($linha["GENERO"]) && !empty($linha["GENERO"]) && $linha["GENERO"] != '[ ]') { ?>
                                <span class="book-tag">
                                    <?php echo htmlspecialchars($linha["GENERO"]); ?>
                                </span>
                            <?php } ?>
                            
                            <?php if(isset($linha["ANO_PUBLICACAO"]) && !empty($linha["ANO_PUBLICACAO"])) { ?>
                                <span class="book-tag">
                                    <?php echo htmlspecialchars($linha["ANO_PUBLICACAO"]); ?>
                                </span>
                            <?php } ?>
                        </div>
                        
                        <div class="book-actions">
                            <a href="<?php echo $ISBN_LINK_EDIT; ?>" class="btn-action btn-action-edit" onclick="event.stopPropagation()">
                                <i class="bi bi-pencil"></i>
                                Editar
                            </a>
                            <a href="<?php echo $ISBN_LINK_EXCL; ?>" class="btn-action btn-action-delete" 
                               onclick="event.stopPropagation(); return confirm('Tem certeza que deseja excluir este livro?')">
                                <i class="bi bi-trash"></i>
                                Excluir
                            </a>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        <?php } else { ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="bi bi-book"></i>
                </div>
                <h3>Nenhum livro encontrado</h3>
                <p>Comece adicionando um novo livro ao acervo</p>
                <a href="/views/Admin/Books/regBooks.php" class="btn-add" style="max-width: 200px; margin: 0 auto;">
                    <i class="bi bi-plus-lg"></i>
                    Adicionar Livro
                </a>
            </div>
        <?php } ?>
    </div>
    
    <!-- MODAL -->
    <div class="modal fade book-modal" id="bookModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalhes do Livro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="modal-content-wrapper">
                        <div class="modal-cover-section">
                            <img id="modalCover" class="modal-cover" src="" alt="Capa do livro">
                        </div>
                        <div class="modal-info-section">
                            <h4 class="modal-book-title" id="modalTitle"></h4>
                            <p class="modal-book-author" id="modalAuthor"></p>
                            
                            <div class="modal-details-grid">
                                <div class="modal-detail">
                                    <span class="modal-detail-label">ISBN</span>
                                    <span class="modal-detail-value" id="modalIsbn"></span>
                                </div>
                                <div class="modal-detail">
                                    <span class="modal-detail-label">Editora</span>
                                    <span class="modal-detail-value" id="modalPublisher"></span>
                                </div>
                                <div class="modal-detail">
                                    <span class="modal-detail-label">Ano</span>
                                    <span class="modal-detail-value" id="modalYear"></span>
                                </div>
                                <div class="modal-detail">
                                    <span class="modal-detail-label">Gênero</span>
                                    <span class="modal-detail-value" id="modalGenre"></span>
                                </div>
                                <div class="modal-detail">
                                    <span class="modal-detail-label">Idioma</span>
                                    <span class="modal-detail-value" id="modalLanguage"></span>
                                </div>
                                <div class="modal-detail">
                                    <span class="modal-detail-label">Páginas</span>
                                    <span class="modal-detail-value" id="modalPages"></span>
                                </div>
                                <div class="modal-detail">
                                    <span class="modal-detail-label">Unidades</span>
                                    <span class="modal-detail-value" id="modalUnits"></span>
                                </div>
                            </div>
                            
                            <div class="modal-description">
                                <h6>Sinopse</h6>
                                <p id="modalDescription"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn-action btn-action-edit" id="modalEditLink">
                        <i class="bi bi-pencil"></i>
                        Editar
                    </a>
                    <a href="#" class="btn-action btn-action-delete" id="modalDeleteLink">
                        <i class="bi bi-trash"></i>
                        Excluir
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const livros = <?php echo json_encode($resultado); ?>;
        
        // Controle do Sidebar Mobile
        const menuToggle = document.getElementById('menuToggle');
        const mobileSidebar = document.getElementById('mobileSidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const sidebarClose = document.getElementById('sidebarClose');
        
        // Variáveis para controle do swipe
        let touchStartX = 0;
        let touchEndX = 0;
        const SWIPE_THRESHOLD = 50;
        
        // Função para abrir sidebar
        function openSidebar() {
            mobileSidebar.classList.add('active');
            sidebarOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        
        // Função para fechar sidebar
        function closeSidebar() {
            mobileSidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }
        
        // Inicializar controles do sidebar se os elementos existirem
        if (menuToggle && mobileSidebar) {
            // Abrir sidebar com botão hamburguer
            menuToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                openSidebar();
            });
            
            // Fechar sidebar com botão X
            if (sidebarClose) {
                sidebarClose.addEventListener('click', closeSidebar);
            }
            
            // Fechar sidebar com overlay
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', closeSidebar);
            }
            
            // Fechar sidebar ao clicar fora
            document.addEventListener('click', function(e) {
                if (mobileSidebar.classList.contains('active') && 
                    !mobileSidebar.contains(e.target) && 
                    e.target !== menuToggle) {
                    closeSidebar();
                }
            });
            
            // Fechar sidebar com tecla ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && mobileSidebar.classList.contains('active')) {
                    closeSidebar();
                }
            });
            
            // Fechar sidebar ao redimensionar para desktop
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768 && mobileSidebar.classList.contains('active')) {
                    closeSidebar();
                }
            });
            
            // SWIPE PARA ABRIR (a partir da borda da tela)
            document.addEventListener('touchstart', function(e) {
                touchStartX = e.changedTouches[0].screenX;
            }, {passive: true});
            
            document.addEventListener('touchend', function(e) {
                touchEndX = e.changedTouches[0].screenX;
                const swipeDistance = touchEndX - touchStartX;
                
                // Swipe da esquerda para direita (abrir sidebar)
                // Só funciona se começar nos primeiros 30px da tela
                if (touchStartX < 30 && 
                    swipeDistance > SWIPE_THRESHOLD && 
                    !mobileSidebar.classList.contains('active') &&
                    window.innerWidth <= 768) {
                    openSidebar();
                    e.preventDefault();
                }
                
                // Swipe da direita para esquerda (fechar sidebar)
                if (mobileSidebar.classList.contains('active') && 
                    swipeDistance < -SWIPE_THRESHOLD) {
                    closeSidebar();
                    e.preventDefault();
                }
            }, {passive: false});
            
            // SWIPE DENTRO DO SIDEBAR PARA FECHAR
            mobileSidebar.addEventListener('touchstart', function(e) {
                touchStartX = e.changedTouches[0].screenX;
            }, {passive: true});
            
            mobileSidebar.addEventListener('touchend', function(e) {
                touchEndX = e.changedTouches[0].screenX;
                const swipeDistance = touchEndX - touchStartX;
                
                // Swipe para esquerda dentro do sidebar para fechar
                if (swipeDistance < -80) {
                    closeSidebar();
                }
            }, {passive: true});
        }
        
        // Pesquisa em tempo real
        const searchInput = document.getElementById('searchInput');
        const booksGrid = document.getElementById('booksGrid');
        
        if (searchInput && booksGrid) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                const bookCards = booksGrid.querySelectorAll('.book-card');
                
                bookCards.forEach(card => {
                    const isbn = card.getAttribute('data-book-isbn');
                    const livro = livros.find(l => l.ISBN === isbn);
                    
                    if (livro) {
                        const title = livro.TITULO?.toLowerCase() || '';
                        const author = livro.AUTOR?.toLowerCase() || '';
                        const genre = livro.GENERO?.toLowerCase() || '';
                        const shouldShow = searchTerm === '' || 
                                          title.includes(searchTerm) || 
                                          author.includes(searchTerm) ||
                                          genre.includes(searchTerm) ||
                                          isbn.toLowerCase().includes(searchTerm);
                        
                        card.style.display = shouldShow ? 'flex' : 'none';
                    }
                });
            });
        }
        
        // Abrir modal
        document.querySelectorAll('.book-card').forEach(card => {
            card.addEventListener('click', function(e) {
                if (e.target.closest('.book-actions')) return;
                
                const isbn = this.getAttribute('data-book-isbn');
                const livro = livros.find(l => l.ISBN === isbn);
                
                if (livro) {
                    // Preencher modal
                    document.getElementById('modalTitle').textContent = livro.TITULO || 'Título não informado';
                    document.getElementById('modalAuthor').textContent = livro.AUTOR || 'Autor não informado';
                    document.getElementById('modalIsbn').textContent = livro.ISBN || 'Não informado';
                    document.getElementById('modalPublisher').textContent = livro.EDITORA || 'Não informado';
                    document.getElementById('modalYear').textContent = livro.ANO_PUBLICACAO || 'Não informado';
                    document.getElementById('modalGenre').textContent = livro.GENERO || 'Não informado';
                    document.getElementById('modalLanguage').textContent = livro.IDIOMA || 'Não informado';
                    document.getElementById('modalPages').textContent = livro.NUMERO_PAGINAS || 'Não informado';
                    document.getElementById('modalUnits').textContent = livro.UNIDADES || '1';
                    document.getElementById('modalDescription').textContent = livro.DESCRICAO || 'Sinopse não disponível.';
                    
                    // Configurar imagem
                    const modalCover = document.getElementById('modalCover');
                    if (livro.IMG_LIVROS && livro.IMG_LIVROS !== '[ ]') {
                        let caminhoImagem = livro.IMG_LIVROS;
                        if (caminhoImagem.startsWith('/uploads/')) {
                            caminhoImagem = '/Api' + caminhoImagem;
                        }
                        modalCover.src = caminhoImagem;
                        modalCover.style.display = 'block';
                        modalCover.alt = `Capa do livro ${livro.TITULO}`;
                    } else {
                        modalCover.src = '';
                        modalCover.style.display = 'none';
                        modalCover.parentElement.innerHTML = '<div style="width: 200px; height: 280px; background: linear-gradient(135deg, #f8f9fa, #ecf0f1); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #bdc3c7;"><i class="bi bi-book" style="font-size: 48px;"></i></div>';
                    }
                    
                    // Configurar links
                    document.getElementById('modalEditLink').href = `/views/Admin/Books/editBooks.php?ISBN=${livro.ISBN}`;
                    document.getElementById('modalDeleteLink').href = `/views/Admin/Books/delBooks.php?ISBN=${livro.ISBN}`;
                    
                    // Configurar confirmação de exclusão
                    document.getElementById('modalDeleteLink').onclick = function(e) {
                        if (!confirm('Tem certeza que deseja excluir este livro?')) {
                            e.preventDefault();
                            return false;
                        }
                    };
                    
                    // Abrir modal
                    const modal = new bootstrap.Modal(document.getElementById('bookModal'));
                    modal.show();
                }
            });
        });
        
        // Animação suave para os cards
        document.addEventListener('DOMContentLoaded', function() {
            const bookCards = document.querySelectorAll('.book-card');
            bookCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    card.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 50);
            });
        });
    </script>
</body>
</html>

<?php include('../../../layout/footer.html'); ?>