<?php
# Impede que utilizadores acedam à página se não estiverem logados
include('../../../seguranca/seguranca.php');
session_start();
if(administrador_logado() == false) {header("location: /index.php"); exit;}

include('../../../layout/header.html');
include('../../../layout/navbar.php');
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empréstimos - Sistema de Biblioteca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #3498db;
            --text-color: #2c3e50;
            --border-color: #ecf0f1;
            --white: #ffffff;
            --light-bg: #f8f9fa;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --error-color: #e74c3c;
        }
        
        body {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-color);
        }
        
        .library-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .library-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .library-subtitle {
            color: #7f8c8d;
            font-size: 0.95rem;
            margin-bottom: 2rem;
        }
        
        /* Cards de Estatísticas - Estilo Minimalista */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: var(--white);
            border-radius: 8px;
            padding: 1.25rem;
            border: 1px solid var(--border-color);
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: transform 0.2s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }
        
        .stat-card.total .stat-icon { 
            background: rgba(44, 62, 80, 0.08);
            color: var(--primary-color);
        }
        .stat-card.disponiveis .stat-icon { 
            background: rgba(39, 174, 96, 0.08);
            color: var(--success-color);
        }
        .stat-card.emprestados .stat-icon { 
            background: rgba(243, 156, 18, 0.08);
            color: var(--warning-color);
        }
        .stat-card.indisponiveis .stat-icon { 
            background: rgba(231, 76, 60, 0.08);
            color: var(--error-color);
        }
        
        .stat-text {
            flex: 1;
        }
        
        .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-color);
            line-height: 1;
            margin-bottom: 0.25rem;
        }
        
        .stat-label {
            font-size: 0.85rem;
            color: #7f8c8d;
            font-weight: 500;
        }
        
        /* Barra de Pesquisa Minimalista */
        .search-container {
            position: relative;
            margin-bottom: 2rem;
        }
        
        .search-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 3rem;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-size: 0.95rem;
            background: var(--white);
            transition: all 0.2s ease;
        }
        
        .search-input:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.15);
        }
        
        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #7f8c8d;
            font-size: 1rem;
        }
        
        /* Grid de Livros Minimalista */
        .books-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.25rem;
            margin-bottom: 2rem;
        }
        
        .book-card {
            background: var(--white);
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            border: 1px solid var(--border-color);
            transition: all 0.2s ease;
            overflow: hidden;
        }
        
        .book-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
        }
        
        .book-card.unavailable {
            opacity: 0.8;
        }
        
        .book-header {
            padding: 1.25rem 1.25rem 0.75rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .book-main-info {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .book-cover {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.1rem;
            flex-shrink: 0;
        }
        
        .book-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-color);
            line-height: 1.4;
            margin-bottom: 0.5rem;
        }
        
        .book-isbn {
            background: var(--light-bg);
            color: var(--primary-color);
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-block;
        }
        
        .book-details {
            padding: 1rem 1.25rem;
        }
        
        .book-meta {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            margin-bottom: 1.25rem;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
            color: #7f8c8d;
        }
        
        .meta-item i {
            width: 16px;
            color: var(--primary-color);
            font-size: 0.85rem;
        }
        
        .book-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .stock-badge {
            padding: 0.375rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
        }
        
        .stock-available {
            background: rgba(39, 174, 96, 0.08);
            color: var(--success-color);
            border: 1px solid rgba(39, 174, 96, 0.2);
        }
        
        .stock-unavailable {
            background: rgba(231, 76, 60, 0.08);
            color: var(--error-color);
            border: 1px solid rgba(231, 76, 60, 0.2);
        }
        
        .loan-button {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0.625rem 1.25rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            text-decoration: none;
        }
        
        .loan-button:hover {
            background: var(--secondary-color);
            transform: translateY(-1px);
        }
        
        .loan-button:disabled {
            background: #bdc3c7;
            color: #7f8c8d;
            cursor: not-allowed;
            transform: none;
        }
        
        /* Estado Vazio Minimalista */
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            background: var(--white);
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            border: 1px solid var(--border-color);
        }
        
        .empty-icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
            opacity: 0.5;
        }
        
        .empty-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 0.5rem;
        }
        
        .empty-text {
            font-size: 0.95rem;
            color: #7f8c8d;
            max-width: 400px;
            margin: 0 auto;
        }
        
        /* Modal Minimalista */
        .loan-modal .modal-content {
            border: none;
            border-radius: 8px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
            overflow: hidden;
        }
        
        .loan-modal .modal-header {
            background: var(--white);
            border-bottom: 1px solid var(--border-color);
            padding: 1.5rem;
        }
        
        .loan-modal .modal-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-color);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .loan-modal .modal-body {
            padding: 1.5rem;
        }
        
        /* Layout do Modal Minimalista */
        .modal-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            align-items: start;
        }
        
        .book-info-panel {
            background: var(--light-bg);
            border-radius: 6px;
            padding: 1.25rem;
            border: 1px solid var(--border-color);
        }
        
        .form-panel {
            background: var(--white);
            border-radius: 6px;
            padding: 1.25rem;
            border: 1px solid var(--border-color);
        }
        
        .selected-book {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .selected-book-cover {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
            flex-shrink: 0;
        }
        
        .selected-book-info h5 {
            margin: 0 0 0.25rem 0;
            font-weight: 600;
            color: var(--text-color);
            font-size: 1rem;
        }
        
        .isbn-tag {
            background: var(--light-bg);
            color: var(--primary-color);
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-block;
        }
        
        .section-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .summary-card {
            background: var(--white);
            border-radius: 6px;
            padding: 1rem;
            border: 1px solid var(--border-color);
            margin-top: 1.5rem;
        }
        
        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--border-color);
        }
        
        .summary-item:last-child {
            border-bottom: none;
        }
        
        .summary-label {
            color: #7f8c8d;
            font-weight: 500;
            font-size: 0.85rem;
        }
        
        .summary-value {
            color: var(--text-color);
            font-weight: 600;
            font-size: 0.85rem;
        }
        
        .return-date-highlight {
            background: rgba(39, 174, 96, 0.08);
            border: 1px solid rgba(39, 174, 96, 0.2);
            border-radius: 6px;
            padding: 1rem;
            margin-top: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .return-label {
            color: var(--success-color);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
        }
        
        .return-date {
            color: var(--success-color);
            font-weight: 700;
            font-size: 0.95rem;
        }
        
        .form-group {
    margin-bottom: 1.75rem;         /* Aumentado de 1.5rem para 1.75rem */
}

.form-label {
    font-weight: 600;
    color: var(--text-color);
    margin-bottom: 0.625rem;       /* Aumentado de 0.5rem para 0.625rem */
    display: block;
    font-size: 0.9rem;             /* Aumentado de 0.85rem para 0.9rem */
}

.form-control {
    border: 1px solid var(--border-color);
    border-radius: 6px;
    padding: 0.875rem;             /* Aumentado de 0.75rem para 0.875rem */
    font-size: 1rem;               /* Aumentado de 0.95rem para 1rem */
    transition: all 0.2s ease;
    width: 100%;
    height: 48px;                  /* Adicionado height para consistência */
}
        
        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.15);
            outline: none;
        }
        
        .input-group .btn {
            height: auto;
            padding: 0.75rem 1rem;
        }
        
        .modal-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color);
        }
        
        .btn-secondary {
            background: var(--light-bg);
            color: var(--text-color);
            border: 1px solid var(--border-color);
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.2s ease;
            cursor: pointer;
            font-size: 0.85rem;
        }
        
        .btn-secondary:hover {
            background: #e9ecef;
        }
        
        .btn-primary {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.2s ease;
            cursor: pointer;
            font-size: 0.85rem;
        }
        
        .btn-primary:hover {
            background: var(--secondary-color);
        }
        
        /* Responsividade */
        @media (max-width: 768px) {
            .library-container {
                padding: 1rem;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .books-grid {
                grid-template-columns: 1fr;
            }
            
            .modal-grid {
                grid-template-columns: 1fr;
                gap: 1.25rem;
            }
            
            .modal-actions {
                flex-direction: column;
            }
            
            .btn-secondary,
            .btn-primary {
                width: 100%;
            }
        }
        
        @media (max-width: 480px) {
            .book-footer {
                flex-direction: column;
                gap: 1rem;
            }
            
            .loan-button {
                width: 100%;
                justify-content: center;
            }
        }
        
        /* Animações Suaves */
        .book-card,
        .stat-card {
            animation: fadeIn 0.3s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="library-container">
        <!-- Header Minimalista -->
        <div class="library-header">
            <h1 class="library-title">Gestão de Empréstimos</h1>
            <p class="library-subtitle">Gerencie e acompanhe empréstimos da biblioteca</p>
        </div>

        <?php
       require_once('../../../conexao/conexao.php');

        try {
            $comandoSQL = "SELECT ISBN, TITULO, EDITORA, AUTOR, UNIDADES_DISPONIVEIS FROM LIVROS";
            $select = $conexao->query($comandoSQL);
            $livros = $select->fetchAll();
            $totalLivros = count($livros);

            $comandoEmprestimos = "SELECT LIVRO_ISBN, COUNT(*) as total_emprestado 
                                  FROM EMPRESTIMO 
                                  WHERE STATUS_LIVRO != 'DEVOLVIDO' 
                                  GROUP BY LIVRO_ISBN";
            $selectEmprestimos = $conexao->query($comandoEmprestimos);
            $emprestimosAtivos = $selectEmprestimos->fetchAll(PDO::FETCH_KEY_PAIR);

            $livrosDisponiveis = 0;
            $livrosEmprestados = 0;
            $livrosIndisponiveis = 0;
            $totalEmprestimosAtivos = 0;

            $livrosComEstoque = [];
            foreach ($livros as $livro) {
                $isbn = $livro['ISBN'];
                $unidadesDisponiveis = $livro['UNIDADES_DISPONIVEIS'] ?? 0;
                $emprestados = $emprestimosAtivos[$isbn] ?? 0;
                
                $disponiveisReal = $unidadesDisponiveis - $emprestados;
                
                $livro['UNIDADES_DISPONIVEIS'] = $unidadesDisponiveis;
                $livro['UNIDADES_EMPRESTADAS'] = $emprestados;
                $livro['DISPONIVEIS_REAL'] = $disponiveisReal;
                $livro['DISPONIVEL'] = $disponiveisReal > 0;
                
                $livrosComEstoque[] = $livro;
                
                if ($disponiveisReal > 0) {
                    $livrosDisponiveis++;
                } else {
                    $livrosIndisponiveis++;
                }
                $totalEmprestimosAtivos += $emprestados;
            }
            
            $livrosEmprestados = $totalEmprestimosAtivos;

        } catch (Exception $e) {
            echo "<div class='alert alert-danger'>Erro ao carregar dados: " . $e->getMessage() . "</div>";
            $livrosComEstoque = [];
            $totalLivros = $livrosDisponiveis = $livrosEmprestados = $livrosIndisponiveis = 0;
        }
        ?>

        <!-- Cards de Estatísticas Minimalistas -->
        <div class="stats-grid">
            <div class="stat-card total">
                <div class="stat-icon">
                    <i class="bi bi-book"></i>
                </div>
                <div class="stat-text">
                    <div class="stat-number"><?php echo $totalLivros; ?></div>
                    <div class="stat-label">Total de Livros</div>
                </div>
            </div>
            
            <div class="stat-card disponiveis">
                <div class="stat-icon">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stat-text">
                    <div class="stat-number"><?php echo $livrosDisponiveis; ?></div>
                    <div class="stat-label">Disponíveis</div>
                </div>
            </div>
            
            <div class="stat-card emprestados">
                <div class="stat-icon">
                    <i class="bi bi-clock"></i>
                </div>
                <div class="stat-text">
                    <div class="stat-number"><?php echo $livrosEmprestados; ?></div>
                    <div class="stat-label">Emprestados</div>
                </div>
            </div>
            
            <div class="stat-card indisponiveis">
                <div class="stat-icon">
                    <i class="bi bi-x-circle"></i>
                </div>
                <div class="stat-text">
                    <div class="stat-number"><?php echo $livrosIndisponiveis; ?></div>
                    <div class="stat-label">Indisponíveis</div>
                </div>
            </div>
        </div>

        <!-- Barra de Pesquisa Minimalista -->
        <div class="search-container">
            <i class="bi bi-search search-icon"></i>
            <input type="text" class="search-input" placeholder="Pesquisar livros por título, autor, editora ou ISBN..." id="searchInput">
        </div>

        <!-- Grid de Livros Minimalista -->
        <?php if($livrosComEstoque): ?>
            <div class="books-grid" id="booksGrid">
                <?php foreach ($livrosComEstoque as $livro): 
                    $disponivel = $livro['DISPONIVEL'];
                    $disponiveisReal = $livro['DISPONIVEIS_REAL'];
                    $unidadesDisponiveis = $livro['UNIDADES_DISPONIVEIS'];
                    $unidadesEmprestadas = $livro['UNIDADES_EMPRESTADAS'];
                ?>
                    <div class="book-card <?php echo !$disponivel ? 'unavailable' : ''; ?>">
                        <div class="book-header">
                            <div class="book-main-info">
                                <div class="book-cover">
                                    <i class="bi bi-book"></i>
                                </div>
                                <div>
                                    <h3 class="book-title"><?php echo htmlspecialchars($livro["TITULO"]); ?></h3>
                                    <span class="book-isbn"><?php echo htmlspecialchars($livro["ISBN"]); ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="book-details">
                            <div class="book-meta">
                                <?php if(isset($livro["AUTOR"]) && !empty($livro["AUTOR"])): ?>
                                <div class="meta-item">
                                    <i class="bi bi-person"></i>
                                    <span><?php echo htmlspecialchars($livro["AUTOR"]); ?></span>
                                </div>
                                <?php endif; ?>
                                
                                <div class="meta-item">
                                    <i class="bi bi-building"></i>
                                    <span><?php echo htmlspecialchars($livro["EDITORA"]); ?></span>
                                </div>
                                
                                <div class="meta-item">
                                    <i class="bi bi-box"></i>
                                    <span>
                                        <?php if($disponivel): ?>
                                            <strong><?php echo $disponiveisReal; ?></strong> de <?php echo $unidadesDisponiveis; ?> unidades
                                            <?php if($unidadesEmprestadas > 0): ?>
                                                <span style="color: var(--warning-color); font-size: 0.8rem;">
                                                    (<?php echo $unidadesEmprestadas; ?> emprestadas)
                                                </span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <strong style="color: var(--error-color);">Indisponível</strong>
                                            (<?php echo $unidadesEmprestadas; ?>/<?php echo $unidadesDisponiveis; ?> emprestadas)
                                        <?php endif; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="book-footer">
                            <div>
                                <span class="stock-badge <?php echo $disponivel ? 'stock-available' : 'stock-unavailable'; ?>">
                                    <i class="bi <?php echo $disponivel ? 'bi-check-circle-fill' : 'bi-x-circle-fill'; ?>"></i>
                                    <?php echo $disponivel ? 'Disponível' : 'Indisponível'; ?>
                                </span>
                            </div>
                            
                            <?php if($disponivel): ?>
                                <button class="loan-button" 
                                        onclick="abrirModalEmprestimo(<?php echo htmlspecialchars(json_encode($livro), ENT_QUOTES, 'UTF-8'); ?>)">
                                    <i class="bi bi-journal-plus"></i>
                                    Emprestar
                                </button>
                            <?php else: ?>
                                <button class="loan-button" disabled>
                                    <i class="bi bi-lock"></i>
                                    Indisponível
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="bi bi-book-x empty-icon"></i>
                <div class="empty-title">Nenhum livro encontrado</div>
                <div class="empty-text">Não há livros disponíveis para empréstimo no momento.</div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Modal de Empréstimo Minimalista -->
    <div class="modal fade loan-modal" id="modalEmprestimo" tabindex="-1" aria-labelledby="modalEmprestimoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEmprestimoLabel">
                        <i class="bi bi-journal-plus"></i>
                        Novo Empréstimo
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="modal-grid">
                        <div class="book-info-panel">
                            <div class="selected-book">
                                <div class="selected-book-cover">
                                    <i class="bi bi-book"></i>
                                </div>
                                <div class="selected-book-info">
                                    <h5 id="modalBookTitle">Selecione um livro</h5>
                                    <div>
                                        <span class="isbn-tag" id="modalBookIsbn">ISBN: --</span>
                                        <span class="stock-badge stock-available" id="modalBookAvailability" style="margin-left: 0.5rem;">
                                            <i class="bi bi-check-circle-fill"></i> Disponível
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="section-title">
                                <i class="bi bi-info-circle"></i>
                                Informações do Livro
                            </div>

                            <div class="summary-card">
                                <div class="summary-item">
                                    <span class="summary-label">Título:</span>
                                    <span class="summary-value" id="summaryBookTitle">--</span>
                                </div>
                                <div class="summary-item">
                                    <span class="summary-label">ISBN:</span>
                                    <span class="summary-value" id="summaryBookIsbn">--</span>
                                </div>
                                <div class="summary-item">
                                    <span class="summary-label">Autor:</span>
                                    <span class="summary-value" id="summaryBookAuthor">--</span>
                                </div>
                                <div class="summary-item">
                                    <span class="summary-label">Editora:</span>
                                    <span class="summary-value" id="summaryBookPublisher">--</span>
                                </div>
                                <div class="summary-item">
                                    <span class="summary-label">Disponibilidade:</span>
                                    <span class="summary-value" id="summaryBookStock">--</span>
                                </div>
                            </div>

                            <div class="return-date-highlight">
                                <div class="return-label">
                                    <i class="bi bi-calendar-check"></i>
                                    Devolução:
                                </div>
                                <div class="return-date" id="modalReturnDate">
                                    <?php echo date('d/m/Y', strtotime('+14 days')); ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-panel">
                            <form action="/Api/emprestimos/cadastrar.php" method="post" id="loanForm">
                                <input type="hidden" name="txtLIVRO_ISBN" id="modalIsbnInput">
                                <input type="hidden" name="txtSTATUS_LIVRO" value="PENDENTE">

                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="bi bi-person-badge"></i>
                                        CPF/NIF do Utilizador
                                    </label>
                                    <div class="input-group">
                                        <input type="text" 
                                               class="form-control" 
                                               id="userDocument" 
                                               name="txtNIF_PESSOA"
                                               placeholder="Digite o CPF ou NIF"
                                               required>
                                        <button type="button" class="btn btn-outline-secondary" id="btnSearchUser">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="bi bi-calendar"></i>
                                        Data do Empréstimo
                                    </label>
                                    <input type="date" 
                                           class="form-control" 
                                           id="loanDate" 
                                           name="txtDATA_EMPRESTADO"
                                           value="<?php echo date('Y-m-d'); ?>"
                                           required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="bi bi-clock"></i>
                                        Período de Empréstimo
                                    </label>
                                    <select class="form-control" id="loanPeriod" name="txtTEMPO_EMPRESTIMO" required>
                                        <option value="">Selecione o período</option>
                                        <option value="7">7 dias</option>
                                        <option value="14" selected>14 dias</option>
                                        <option value="21">21 dias</option>
                                        <option value="30">30 dias</option>
                                    </select>
                                </div>

                                <div class="modal-actions">
                                    <button type="button" class="btn-secondary" data-bs-dismiss="modal">
                                        Cancelar
                                    </button>
                                    <button type="submit" class="btn-primary" id="btnConfirmLoan">
                                        Confirmar Empréstimo
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function abrirModalEmprestimo(livro) {
            document.getElementById('modalBookTitle').textContent = livro.TITULO || 'Título não disponível';
            document.getElementById('modalBookIsbn').textContent = 'ISBN: ' + (livro.ISBN || '--');
            document.getElementById('modalIsbnInput').value = livro.ISBN || '';
            
            document.getElementById('summaryBookTitle').textContent = livro.TITULO || '--';
            document.getElementById('summaryBookIsbn').textContent = livro.ISBN || '--';
            document.getElementById('summaryBookAuthor').textContent = livro.AUTOR || '--';
            document.getElementById('summaryBookPublisher').textContent = livro.EDITORA || '--';
            
            const disponivel = livro.DISPONIVEL;
            const disponiveisReal = livro.DISPONIVEIS_REAL || 0;
            const unidadesDisponiveis = livro.UNIDADES_DISPONIVEIS || 0;
            const unidadesEmprestadas = livro.UNIDADES_EMPRESTADAS || 0;
            
            const availabilityBadge = document.getElementById('modalBookAvailability');
            const stockInfo = document.getElementById('summaryBookStock');
            
            if (disponivel) {
                availabilityBadge.className = 'stock-badge stock-available';
                availabilityBadge.innerHTML = '<i class="bi bi-check-circle-fill"></i> Disponível';
                stockInfo.textContent = `${disponiveisReal} de ${unidadesDisponiveis} unidades`;
                document.getElementById('btnConfirmLoan').disabled = false;
            } else {
                availabilityBadge.className = 'stock-badge stock-unavailable';
                availabilityBadge.innerHTML = '<i class="bi bi-x-circle-fill"></i> Indisponível';
                stockInfo.textContent = `Indisponível (${unidadesEmprestadas}/${unidadesDisponiveis} emprestadas)`;
                document.getElementById('btnConfirmLoan').disabled = true;
            }
            
            const modal = new bootstrap.Modal(document.getElementById('modalEmprestimo'));
            modal.show();
            atualizarDataRetorno();
        }

        function atualizarDataRetorno() {
            const loanDate = document.getElementById('loanDate').value;
            const loanPeriod = document.getElementById('loanPeriod').value;
            
            if (loanDate && loanPeriod) {
                const dataEmprestimo = new Date(loanDate);
                const dataRetorno = new Date(dataEmprestimo);
                dataRetorno.setDate(dataRetorno.getDate() + parseInt(loanPeriod));
                
                const dataFormatada = dataRetorno.toLocaleDateString('pt-PT');
                document.getElementById('modalReturnDate').textContent = dataFormatada;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const bookCards = document.querySelectorAll('.book-card');
            
            if (searchInput && bookCards.length > 0) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase().trim();
                    
                    bookCards.forEach(card => {
                        const title = card.querySelector('.book-title').textContent.toLowerCase();
                        const isbn = card.querySelector('.book-isbn').textContent.toLowerCase();
                        const autor = card.querySelector('.meta-item:nth-child(1) span')?.textContent.toLowerCase() || '';
                        const editora = card.querySelector('.meta-item:nth-child(2) span')?.textContent.toLowerCase() || '';
                        
                        const matches = title.includes(searchTerm) || 
                                      isbn.includes(searchTerm) || 
                                      autor.includes(searchTerm) || 
                                      editora.includes(searchTerm);
                        
                        card.style.display = matches ? 'block' : 'none';
                    });
                });
            }
            
            document.getElementById('loanDate').addEventListener('change', atualizarDataRetorno);
            document.getElementById('loanPeriod').addEventListener('change', atualizarDataRetorno);
            
            document.getElementById('btnSearchUser').addEventListener('click', function() {
                const userDocument = document.getElementById('userDocument').value.trim();
                if (userDocument) {
                    alert(`Buscando utilizador com documento: ${userDocument}`);
                } else {
                    alert('Por favor, digite um CPF ou NIF para buscar.');
                }
            });
            
            document.getElementById('loanForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const userDocument = document.getElementById('userDocument').value.trim();
                const loanDate = document.getElementById('loanDate').value;
                const loanPeriod = document.getElementById('loanPeriod').value;
                
                if (!userDocument || !loanDate || !loanPeriod) {
                    alert('Por favor, preencha todos os campos obrigatórios.');
                    return;
                }
                
                if (confirm('Confirmar empréstimo?')) {
                    this.submit();
                }
            });
        });
    </script>
</body>
</html>

<?php include('../../layout/footer.html'); ?>