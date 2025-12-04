<?php
# Impede que utilizadores acedam à página se não estiverem logados
include('../../../seguranca/seguranca.php');
session_start();
if(administrador_logado() == false) {header("location: /index.php"); exit;}

// Processar exportação Excel - DEVE VIR ANTES DE QUALQUER OUTPUT
if (isset($_GET['export']) && $_GET['export'] == 'excel') {
       require_once('../../../conexao/conexao.php');
    
    // Buscar todos os dados para exportar
    $export_query = $conexao->query("
        SELECT 
            EMPRESTIMO.ID,
            LIVROS.TITULO as 'Livro',
            LIVROS.ISBN,
            UTILIZADORES.NOME as 'Nome',
            UTILIZADORES.SOBRENOME as 'Sobrenome',
            UTILIZADORES.NIF,
            EMPRESTIMO.DATA_EMPRESTADO as 'Data_Emprestimo',
            EMPRESTIMO.TEMPO_EMPRESTIMO as 'Dias_Emprestimo',
            DATE_ADD(EMPRESTIMO.DATA_EMPRESTADO, INTERVAL EMPRESTIMO.TEMPO_EMPRESTIMO DAY) as 'Data_Expiracao',
            EMPRESTIMO.STATUS_LIVRO as 'Status'
        FROM EMPRESTIMO
        INNER JOIN LIVROS ON LIVROS.ISBN = EMPRESTIMO.LIVRO_ISBN
        INNER JOIN UTILIZADORES ON UTILIZADORES.NIF = EMPRESTIMO.NIF_PESSOA
        ORDER BY 
            CASE WHEN EMPRESTIMO.STATUS_LIVRO != 'DEVOLVIDO' THEN 0 ELSE 1 END,
            EMPRESTIMO.DATA_EMPRESTADO DESC
    ");
    $dados_export = $export_query->fetchAll();
    
    // Gerar CSV que pode ser aberto no Excel
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="emprestimos_' . date('Y-m-d') . '.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');
    
    // Criar output stream
    $output = fopen('php://output', 'w');
    
    // Adicionar BOM para UTF-8 (importante para Excel)
    fputs($output, $bom = ( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
    
    // Cabeçalhos das colunas
    fputcsv($output, [
        'ID',
        'Livro', 
        'ISBN',
        'Nome',
        'Sobrenome', 
        'NIF',
        'Data Empréstimo',
        'Dias Empréstimo',
        'Data Expiracao',
        'Status'
    ], ';');
    
    // Dados
    foreach ($dados_export as $linha) {
        fputcsv($output, [
            $linha['ID'],
            $linha['Livro'],
            $linha['ISBN'],
            $linha['Nome'],
            $linha['Sobrenome'],
            $linha['NIF'],
            $linha['Data_Emprestimo'],
            $linha['Dias_Emprestimo'],
            $linha['Data_Expiracao'],
            $linha['Status']
        ], ';');
    }
    
    fclose($output);
    exit;
}

// SÓ DEPOIS INCLUIMOS OS OUTROS FICHEIROS
include('../../../layout/header.html');
include('../../../layout/navbar.php');
include("../../../recursos.php");
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
        /* [MANTENHA TODO O CSS ANTERIOR - não alterei o estilo] */
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #3498db;
            --text-color: #2c3e50;
            --text-light: #7f8c8d;
            --border-color: #ecf0f1;
            --card-shadow: 0 2px4px rgba(0,0,0,0.08);
            --hover-shadow: 0 4px 12px rgba(0,0,0,0.12);
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --background: #f8f9fa;
        }
        
        body {
            background-color: var(--background);
            color: var(--text-color);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        
        .page-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 1.5rem 1rem;
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
        
        .stats-container {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }
        
        .stat-card {
            flex: 1;
            min-width: 200px;
            background: white;
            padding: 1.25rem;
            border-radius: 8px;
            border-left: 4px solid var(--accent-color);
            box-shadow: var(--card-shadow);
            border: 1px solid var(--border-color);
        }
        
        .stat-card.emprestados { border-left-color: var(--accent-color); }
        .stat-card.expirados { border-left-color: var(--danger-color); }
        .stat-card.Expira{ border-left-color: var(--warning-color); }
        .stat-card.devolvidos { border-left-color: var(--success-color); }
        
        .stat-number {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.25rem;
        }
        
        .stat-label {
            font-size: 0.85rem;
            color: var(--text-light);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .table-container {
            background: white;
            border-radius: 8px;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--border-color);
            overflow: hidden;
        }
        
        .table-header {
            background: #fafbfc;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .table-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary-color);
            margin: 0;
        }
        
        .search-container {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }
        
        .search-box {
            position: relative;
            min-width: 300px;
        }
        
        .search-input {
            padding: 0.6rem 1rem 0.6rem 2.5rem;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            background: white;
            width: 100%;
        }
        
        .search-input:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.1);
            outline: none;
        }
        
        .search-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            font-size: 0.9rem;
        }
        
        .btn-export {
            padding: 0.6rem 1.25rem;
            background: var(--success-color);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap;
            text-decoration: none;
        }
        
        .btn-export:hover {
            background: #219653;
            color: white;
        }
        
        .custom-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .custom-table thead {
            background: #f8f9fa;
            border-bottom: 2px solid var(--border-color);
        }
        
        .custom-table th {
            padding: 1rem 1.5rem;
            text-align: left;
            font-weight: 600;
            color: var(--text-color);
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .custom-table td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            font-size: 0.9rem;
            vertical-align: middle;
        }
        
        .custom-table tbody tr {
            transition: background-color 0.2s ease;
        }
        
        .custom-table tbody tr:hover {
            background-color: #fafbfc;
        }
        
        .custom-table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .book-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .book-icon {
            width: 36px;
            height: 36px;
            border-radius: 6px;
            background: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.9rem;
        }
        
        .book-details h6 {
            margin: 0;
            font-weight: 600;
            color: var(--primary-color);
            font-size: 0.9rem;
        }
        
        .book-details p {
            margin: 0;
            font-size: 0.8rem;
            color: var(--text-light);
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--secondary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .user-details h6 {
            margin: 0;
            font-weight: 500;
            color: var(--text-color);
            font-size: 0.9rem;
        }
        
        .user-details p {
            margin: 0;
            font-size: 0.8rem;
            color: var(--text-light);
        }
        
        .status-badge {
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .status-emprestado {
            background: rgba(52, 152, 219, 0.1);
            color: var(--accent-color);
        }
        
        .status-expirado {
            background: rgba(231, 76, 60, 0.1);
            color: var(--danger-color);
        }
        
        .status-Expira{
            background: rgba(243, 156, 18, 0.1);
            color: var(--warning-color);
        }
        
        .status-devolvido {
            background: rgba(39, 174, 96, 0.1);
            color: var(--success-color);
        }
        
        .date-cell {
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: 0.85rem;
            color: var(--text-color);
        }
        
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: flex-end;
        }
        
        .btn-table {
            padding: 0.4rem 0.8rem;
            border: 1px solid var(--border-color);
            background: white;
            color: var(--text-color);
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 500;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.25rem;
            text-decoration: none;
        }
        
        .btn-table:hover {
            background: #f8f9fa;
            border-color: var(--accent-color);
            color: var(--accent-color);
        }
        
        .btn-table.primary {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }
        
        .btn-table.primary:hover {
            background: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--text-light);
        }
        
        .empty-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.4;
        }
        
        .empty-title {
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
            color: var(--text-color);
        }
        
        .empty-text {
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }

        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--border-color);
            background: #fafbfc;
        }

        .pagination-info {
            font-size: 0.85rem;
            color: var(--text-light);
        }

        .pagination {
            margin: 0;
        }

        .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .page-link {
            color: var(--primary-color);
            font-size: 0.85rem;
            padding: 0.5rem 0.75rem;
        }

        .page-link:hover {
            color: var(--secondary-color);
        }

        @media (max-width: 768px) {
            .page-container {
                padding: 1rem;
            }
            
            .stats-container {
                flex-direction: column;
            }
            
            .stat-card {
                min-width: auto;
            }
            
            .table-header {
                flex-direction: column;
                align-items: stretch;
                padding: 1rem;
            }
            
            .search-container {
                width: 100%;
            }
            
            .search-box {
                min-width: auto;
            }
            
            .custom-table th,
            .custom-table td {
                padding: 0.75rem 1rem;
            }
            
            .action-buttons {
                flex-direction: column;
            }

            .pagination-container {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="page-container">
        <!-- Cabeçalho Minimalista -->
        <div class="page-header">
            <h1 class="page-title">Gestão de Empréstimos</h1>
            <p class="page-subtitle">Controle e monitorize todos os empréstimos da biblioteca</p>
        </div>

        <?php
       require_once('../../../conexao/conexao.php');

        // ATUALIZAÇÃO AUTOMÁTICA: Atualizar empréstimos expirados para "NÃO DEVOLVIDO"
        $DATA_ATUAL = date('Y-m-d');
        
        // Buscar todos os empréstimos que não foram devolvidos e cuja data de Expiracao já passou
        $update_expirados = $conexao->prepare(
            "UPDATE EMPRESTIMO 
             SET STATUS_LIVRO = 'NÃO DEVOLVIDO' 
             WHERE STATUS_LIVRO != 'DEVOLVIDO' 
             AND DATE_ADD(DATA_EMPRESTADO, INTERVAL TEMPO_EMPRESTIMO DAY) < :data_atual"
        );
        $update_expirados->execute(['data_atual' => $DATA_ATUAL]);
        $linhas_afetadas = $update_expirados->rowCount();
        
        // Se houver empréstimos atualizados, mostrar mensagem (opcional)
        if ($linhas_afetadas > 0) {
            echo "<div class='alert alert-info alert-dismissible fade show' role='alert'>
                    <i class='bi bi-info-circle'></i> $linhas_afetadas empréstimo(s) foram automaticamente marcados como 'NÃO DEVOLVIDO' por estarem expirados.
                    <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                  </div>";
        }

        // Processar pesquisa automática
        $search_term = isset($_GET['search']) ? trim($_GET['search']) : '';
        
        // BUSCAR TODOS OS DADOS PARA OS CARDS (SEM PAGINAÇÃO)
        $query_cards = "
            SELECT EMPRESTIMO.ID, EMPRESTIMO.STATUS_LIVRO, UTILIZADORES.NOME, UTILIZADORES.SOBRENOME, 
                   UTILIZADORES.NIF, EMPRESTIMO.DATA_EMPRESTADO, EMPRESTIMO.TEMPO_EMPRESTIMO, 
                   LIVROS.TITULO, LIVROS.ISBN
            FROM EMPRESTIMO
            INNER JOIN LIVROS ON LIVROS.ISBN = EMPRESTIMO.LIVRO_ISBN
            INNER JOIN UTILIZADORES ON UTILIZADORES.NIF = EMPRESTIMO.NIF_PESSOA
        ";

        // Adicionar filtro de pesquisa se existir
        if (!empty($search_term)) {
            $where_cards = " WHERE (LIVROS.TITULO LIKE :search_cards 
                       OR UTILIZADORES.NOME LIKE :search_cards 
                       OR UTILIZADORES.SOBRENOME LIKE :search_cards 
                       OR UTILIZADORES.NIF LIKE :search_cards 
                       OR LIVROS.ISBN LIKE :search_cards)";
            $query_cards .= $where_cards;
        }

        $stmt_cards = $conexao->prepare($query_cards);
        if (!empty($search_term)) {
            $stmt_cards->execute(['search_cards' => '%' . $search_term . '%']);
        } else {
            $stmt_cards->execute();
        }
        $todos_emprestimos = $stmt_cards->fetchAll();

        // Organizar TODOS os empréstimos por status para os CARDS
        $emprestados_total = [];
        $devolvidos_total = [];
        $expirados_total = [];
        $vencendo_hoje_total = [];

        if($todos_emprestimos) {
            foreach ($todos_emprestimos as $linha) {
                $DATA = $linha["DATA_EMPRESTADO"];
                $TEMPO_EMPRESTIMO = $linha["TEMPO_EMPRESTIMO"];
                $DATA_Expiracao = date('Y-m-d', strtotime($DATA. " + $TEMPO_EMPRESTIMO days"));
                
                // Lógica para determinar o status
                if ($linha["STATUS_LIVRO"] == "DEVOLVIDO") {
                    $devolvidos_total[] = $linha;
                } else if ($linha["STATUS_LIVRO"] == "NÃO DEVOLVIDO") {
                    $expirados_total[] = $linha;
                } else {
                    // Quando STATUS_LIVRO não é "DEVOLVIDO" nem "NÃO DEVOLVIDO", significa que está pendente/emprestado
                    // Agora verificamos se está expirado ou Expirahoje
                    if ($DATA_Expiracao < $DATA_ATUAL) {
                        $expirados_total[] = $linha;
                    } else if ($DATA_Expiracao == $DATA_ATUAL) {
                        $vencendo_hoje_total[] = $linha;
                    } else {
                        $emprestados_total[] = $linha;
                    }
                }
            }
        }

        // Configuração de paginação para a TABELA
        $limite = 10; // Número de registos por página
        $pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
        $offset = ($pagina - 1) * $limite;

        // Construir query base para TABELA (com ordenação: ativos primeiro)
        $query_base = "
            SELECT EMPRESTIMO.ID, EMPRESTIMO.STATUS_LIVRO, UTILIZADORES.NOME, UTILIZADORES.SOBRENOME, 
                   UTILIZADORES.NIF, EMPRESTIMO.DATA_EMPRESTADO, EMPRESTIMO.TEMPO_EMPRESTIMO, 
                   LIVROS.TITULO, LIVROS.ISBN
            FROM EMPRESTIMO
            INNER JOIN LIVROS ON LIVROS.ISBN = EMPRESTIMO.LIVRO_ISBN
            INNER JOIN UTILIZADORES ON UTILIZADORES.NIF = EMPRESTIMO.NIF_PESSOA
        ";

        $query_contagem = "
            SELECT COUNT(*) as total
            FROM EMPRESTIMO
            INNER JOIN LIVROS ON LIVROS.ISBN = EMPRESTIMO.LIVRO_ISBN
            INNER JOIN UTILIZADORES ON UTILIZADORES.NIF = EMPRESTIMO.NIF_PESSOA
        ";

        // Adicionar filtro de pesquisa se existir
        if (!empty($search_term)) {
            $where = " WHERE (LIVROS.TITULO LIKE :search 
                       OR UTILIZADORES.NOME LIKE :search 
                       OR UTILIZADORES.SOBRENOME LIKE :search 
                       OR UTILIZADORES.NIF LIKE :search 
                       OR LIVROS.ISBN LIKE :search)";
            
            $query_base .= $where;
            $query_contagem .= $where;
        }

        // ORDENAÇÃO: Empréstimos ativos primeiro, depois por data de empréstimo
        $query_base .= " ORDER BY 
            CASE 
                WHEN EMPRESTIMO.STATUS_LIVRO != 'DEVOLVIDO' THEN 0 
                ELSE 1 
            END,
            EMPRESTIMO.DATA_EMPRESTADO DESC 
            LIMIT :limite OFFSET :offset";

        // Executar query de contagem para paginação
        $stmt_contagem = $conexao->prepare($query_contagem);
        if (!empty($search_term)) {
            $stmt_contagem->execute(['search' => '%' . $search_term . '%']);
        } else {
            $stmt_contagem->execute();
        }
        $total_registos = $stmt_contagem->fetch()['total'];
        $total_paginas = ceil($total_registos / $limite);

        // Executar query principal para TABELA
        $stmt = $conexao->prepare($query_base);
        if (!empty($search_term)) {
            $stmt->bindValue(':search', '%' . $search_term . '%', PDO::PARAM_STR);
        }
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetchAll();
        ?>

        <!-- Estatísticas (usam TODOS os dados) -->
        <div class="stats-container">
            <div class="stat-card emprestados">
                <div class="stat-number"><?php echo count($emprestados_total); ?></div>
                <div class="stat-label">
                    <i class="bi bi-clock" style="color: var(--accent-color);"></i>
                    Emprestados
                </div>
            </div>
            <div class="stat-card expirados">
                <div class="stat-number"><?php echo count($expirados_total); ?></div>
                <div class="stat-label">
                    <i class="bi bi-exclamation-triangle" style="color: var(--danger-color);"></i>
                    Expirados
                </div>
            </div>
            <div class="stat-card vencendo">
                <div class="stat-number"><?php echo count($vencendo_hoje_total); ?></div>
                <div class="stat-label">
                    <i class="bi bi-calendar-check" style="color: var(--warning-color);"></i>
                    Expira Hoje
                </div>
            </div>
            <div class="stat-card devolvidos">
                <div class="stat-number"><?php echo count($devolvidos_total); ?></div>
                <div class="stat-label">
                    <i class="bi bi-check-circle" style="color: var(--success-color);"></i>
                    Devolvidos
                </div>
            </div>
        </div>

        <!-- Tabela de Empréstimos -->
        <div class="table-container">
            <div class="table-header">
                <h3 class="table-title">Todos os Empréstimos</h3>
                
                <div class="search-container">
                    <form id="search-form" method="get" class="d-flex gap-2 align-items-center">
                        <div class="search-box">
                            <i class="bi bi-search search-icon"></i>
                            <input type="text" 
                                   name="search" 
                                   class="search-input" 
                                   placeholder="Pesquisar por livro, utilizador, NIF ou ISBN..." 
                                   value="<?php echo htmlspecialchars($search_term); ?>"
                                   id="searchInput">
                        </div>
                        <a href="?export=excel<?php echo !empty($search_term) ? '&search=' . urlencode($search_term) : ''; ?>" class="btn-export">
                            <i class="bi bi-file-earmark-excel"></i>
                            Exportar Excel
                        </a>
                    </form>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Livro</th>
                            <th>Utilizador</th>
                            <th>Data Empréstimo</th>
                            <th>Expiracao</th>
                            <th>Status</th>
                            <th style="width: 120px;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($resultado)): ?>
                            <?php foreach($resultado as $linha): 
                                $DATA = $linha["DATA_EMPRESTADO"];
                                $TEMPO_EMPRESTIMO = $linha["TEMPO_EMPRESTIMO"];
                                $DATA_Expiracao = date('Y-m-d', strtotime($DATA. " + $TEMPO_EMPRESTIMO days"));
                                
                                // Determinar status
                                if ($linha["STATUS_LIVRO"] == "DEVOLVIDO") {
                                    $status_class = 'status-devolvido';
                                    $status_text = 'Devolvido';
                                    $status_icon = 'bi-check-circle';
                                } else if ($linha["STATUS_LIVRO"] == "NÃO DEVOLVIDO") {
                                    $status_class = 'status-expirado';
                                    $status_text = 'Não Devolvido';
                                    $status_icon = 'bi-exclamation-triangle';
                                } else {
                                    // Quando STATUS_LIVRO não é "DEVOLVIDO" nem "NÃO DEVOLVIDO", está pendente/emprestado
                                    // Verificamos se está expirado ou Expirahoje
                                    if ($DATA_Expiracao < $DATA_ATUAL) {
                                        $status_class = 'status-expirado';
                                        $status_text = 'Expirado';
                                        $status_icon = 'bi-exclamation-triangle';
                                    } else if ($DATA_Expiracao == $DATA_ATUAL) {
                                        $status_class = 'status-vencendo';
                                        $status_text = 'Expira Hoje';
                                        $status_icon = 'bi-calendar-check';
                                    } else {
                                        $status_class = 'status-emprestado';
                                        $status_text = 'Emprestado';
                                        $status_icon = 'bi-clock';
                                    }
                                }
                                
                                $iniciais = substr($linha["NOME"], 0, 1) . substr($linha["SOBRENOME"], 0, 1);
                            ?>
                                <tr>
                                    <td>
                                        <div class="book-info">
                                            <div class="book-icon">
                                                <i class="bi bi-book"></i>
                                            </div>
                                            <div class="book-details">
                                                <h6><?php echo htmlspecialchars($linha["TITULO"]); ?></h6>
                                                <p>ISBN: <?php echo $linha["ISBN"]; ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="user-info">
                                            <div class="user-avatar">
                                                <?php echo strtoupper($iniciais); ?>
                                            </div>
                                            <div class="user-details">
                                                <h6><?php echo htmlspecialchars($linha["NOME"] . " " . $linha["SOBRENOME"]); ?></h6>
                                                <p>NIF: <?php echo $linha["NIF"]; ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="date-cell">
                                        <?php echo date('d/m/Y', strtotime($linha["DATA_EMPRESTADO"])); ?>
                                    </td>
                                    <td class="date-cell">
                                        <?php echo date('d/m/Y', strtotime($DATA_Expiracao)); ?>
                                    </td>
                                    <td>
                                        <span class="status-badge <?php echo $status_class; ?>">
                                            <i class="bi <?php echo $status_icon; ?>"></i>
                                            <?php echo $status_text; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <?php if($linha["STATUS_LIVRO"] != "DEVOLVIDO"): ?>
                                                <form action="/Api/emprestimos/MarcarDevolvido.php" method="post">
                                                    <input type="hidden" name="txtIDEMPRESTIMO" value="<?php echo $linha["ID"]; ?>">
                                                    <button type="submit" class="btn-table primary" title="Marcar como Devolvido">
                                                        <i class="bi bi-check-circle"></i>
                                                        Devolver
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <i class="bi bi-book empty-icon"></i>
                                        <div class="empty-title">
                                            <?php echo empty($search_term) ? 'Nenhum empréstimo encontrado' : 'Nenhum resultado para "' . htmlspecialchars($search_term) . '"'; ?>
                                        </div>
                                        <div class="empty-text">
                                            <?php echo empty($search_term) ? 'Não foram encontrados empréstimos na base de dados.' : 'Tente pesquisar com outros termos.'; ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            <?php if ($total_paginas > 1): ?>
            <div class="pagination-container">
                <div class="pagination-info">
                    Mostrando <?php echo min($limite, count($resultado)); ?> de <?php echo $total_registos; ?> registos
                </div>
                <nav>
                    <ul class="pagination">
                        <?php if ($pagina > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?pagina=<?php echo $pagina - 1; ?><?php echo !empty($search_term) ? '&search=' . urlencode($search_term) : ''; ?>">
                                    <i class="bi bi-chevron-left"></i>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                            <?php if ($i == 1 || $i == $total_paginas || ($i >= $pagina - 2 && $i <= $pagina + 2)): ?>
                                <li class="page-item <?php echo $i == $pagina ? 'active' : ''; ?>">
                                    <a class="page-link" href="?pagina=<?php echo $i; ?><?php echo !empty($search_term) ? '&search=' . urlencode($search_term) : ''; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php elseif ($i == $pagina - 3 || $i == $pagina + 3): ?>
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            <?php endif; ?>
                        <?php endfor; ?>

                        <?php if ($pagina < $total_paginas): ?>
                            <li class="page-item">
                                <a class="page-link" href="?pagina=<?php echo $pagina + 1; ?><?php echo !empty($search_term) ? '&search=' . urlencode($search_term) : ''; ?>">
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Pesquisa automática com debounce
        let searchTimeout;
        const searchInput = document.getElementById('searchInput');
        
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    document.getElementById('search-form').submit();
                }, 500);
            });

            // Focar no campo de pesquisa quando a página carrega
            searchInput.focus();
        }

        // Manter o foco no campo de pesquisa após submit
        document.getElementById('search-form').addEventListener('submit', function(e) {
            e.preventDefault();
            // A pesquisa já é feita automaticamente pelo evento input
        });
    </script>
</body>
</html>

<?php include('../../../layout/footer.html'); ?>