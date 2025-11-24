<?php
# Impede que utilizadores acedam à página se não estiverem logados
include('../../../seguranca/seguranca.php');
session_start();
if(administrador_logado() == false) {header("location: /index.php"); exit;}

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
        :root {
            --primary-50: #f8fafc;
            --primary-100: #f1f5f9;
            --primary-200: #e2e8f0;
            --primary-300: #cbd5e1;
            --primary-400: #94a3b8;
            --primary-500: #64748b;
            --primary-600: #475569;
            --primary-700: #334155;
            --primary-800: #1e293b;
            --primary-900: #0f172a;
            
            --success-50: #f0fdf4;
            --success-100: #dcfce7;
            --success-500: #22c55e;
            --success-600: #16a34a;
            
            --warning-50: #fffbeb;
            --warning-100: #fef3c7;
            --warning-500: #f59e0b;
            --warning-600: #d97706;
            
            --danger-50: #fef2f2;
            --danger-100: #fee2e2;
            --danger-500: #ef4444;
            --danger-600: #dc2626;
            
            --surface: #ffffff;
            --surface-elevated: #fafafa;
            --border: #e5e7eb;
            --border-light: #f1f5f9;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --text-tertiary: #9ca3af;
            
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        * {
            box-sizing: border-box;
        }
        
        body {
            background: linear-gradient(135deg, var(--primary-50) 0%, var(--primary-100) 100%);
            color: var(--text-primary);
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
            line-height: 1.6;
            min-height: 100vh;
        }
        
        .page-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }
        
        /* Header Elegante */
        .page-header {
            text-align: center;
            margin-bottom: 3rem;
            position: relative;
        }
        
        .page-header::before {
            content: '';
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            border-radius: 2px;
        }
        
        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary-700), var(--primary-900));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.75rem;
            letter-spacing: -0.025em;
        }
        
        .page-subtitle {
            font-size: 1.1rem;
            color: var(--text-secondary);
            font-weight: 400;
            max-width: 500px;
            margin: 0 auto;
        }
        
        /* Cards de Estatísticas Modernos */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }
        
        .stat-card {
            background: var(--surface);
            padding: 2rem 1.5rem;
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-light);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-400), var(--primary-600));
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-xl);
        }
        
        .stat-card.total::before { background: linear-gradient(90deg, var(--primary-400), var(--primary-600)); }
        .stat-card.disponiveis::before { background: linear-gradient(90deg, var(--success-500), var(--success-600)); }
        .stat-card.emprestados::before { background: linear-gradient(90deg, var(--warning-500), var(--warning-600)); }
        .stat-card.indisponiveis::before { background: linear-gradient(90deg, var(--danger-500), var(--danger-600)); }
        
        .stat-content {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }
        
        .stat-card.total .stat-icon { 
            background: linear-gradient(135deg, var(--primary-100), var(--primary-200));
            color: var(--primary-600);
        }
        .stat-card.disponiveis .stat-icon { 
            background: linear-gradient(135deg, var(--success-100), var(--success-50));
            color: var(--success-600);
        }
        .stat-card.emprestados .stat-icon { 
            background: linear-gradient(135deg, var(--warning-100), var(--warning-50));
            color: var(--warning-600);
        }
        .stat-card.indisponiveis .stat-icon { 
            background: linear-gradient(135deg, var(--danger-100), var(--danger-50));
            color: var(--danger-600);
        }
        
        .stat-text {
            flex: 1;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1;
            margin-bottom: 0.25rem;
        }
        
        .stat-label {
            font-size: 0.9rem;
            color: var(--text-secondary);
            font-weight: 500;
        }
        
        /* Barra de Pesquisa Elegante */
        .search-section {
            max-width: 600px;
            margin: 0 auto 3rem;
        }
        
        .search-container {
            position: relative;
        }
        
        .search-input {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            border: 2px solid var(--border);
            border-radius: 12px;
            font-size: 1rem;
            background: var(--surface);
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
        }
        
        .search-input:focus {
            outline: none;
            border-color: var(--primary-400);
            box-shadow: 0 0 0 3px rgba(100, 116, 139, 0.1);
        }
        
        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-tertiary);
            font-size: 1.1rem;
        }
        
        /* Lista de Livros Ultra Moderna */
        .books-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .book-card {
            background: var(--surface);
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-light);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            position: relative;
        }
        
        .book-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-xl);
        }
        
        .book-card.unavailable {
            opacity: 0.7;
        }
        
        .book-card.unavailable::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(239, 68, 68, 0.02);
            pointer-events: none;
        }
        
        .book-header {
            padding: 1.5rem 1.5rem 1rem;
            border-bottom: 1px solid var(--border-light);
        }
        
        .book-main-info {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .book-cover {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary-500), var(--primary-700));
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
            flex-shrink: 0;
        }
        
        .book-card.unavailable .book-cover {
            background: linear-gradient(135deg, var(--primary-300), var(--primary-400));
        }
        
        .book-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-primary);
            line-height: 1.4;
            margin-bottom: 0.5rem;
        }
        
        .book-card.unavailable .book-title {
            color: var(--text-secondary);
        }
        
        .book-isbn {
            background: var(--primary-50);
            color: var(--primary-600);
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-block;
        }
        
        .book-details {
            padding: 1rem 1.5rem;
        }
        
        .book-meta {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.9rem;
            color: var(--text-secondary);
        }
        
        .meta-item i {
            width: 16px;
            color: var(--text-tertiary);
            font-size: 0.9rem;
        }
        
        .book-footer {
            padding: 1rem 1.5rem 1.5rem;
            border-top: 1px solid var(--border-light);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .stock-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
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
            background: var(--success-50);
            color: var(--success-600);
            border: 1px solid var(--success-100);
        }
        
        .stock-unavailable {
            background: var(--danger-50);
            color: var(--danger-600);
            border: 1px solid var(--danger-100);
        }
        
        .loan-button {
            background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
            color: white;
            border: none;
            padding: 0.75rem 1.25rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            text-decoration: none;
        }
        
        .loan-button:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
            color: white;
            background: linear-gradient(135deg, var(--primary-600), var(--primary-700));
        }
        
        .loan-button:disabled {
            background: var(--primary-200);
            color: var(--text-tertiary);
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        
        /* Estado Vazio */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: var(--surface);
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-light);
        }
        
        .empty-icon {
            font-size: 4rem;
            color: var(--primary-300);
            margin-bottom: 1.5rem;
        }
        
        .empty-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.75rem;
        }
        
        .empty-text {
            font-size: 1rem;
            color: var(--text-secondary);
            max-width: 400px;
            margin: 0 auto;
        }

        /* Modal Moderno e Funcional */
        .loan-modal .modal-content {
            border: none;
            border-radius: 20px;
            box-shadow: var(--shadow-xl);
            overflow: hidden;
        }
        
        .loan-modal .modal-header {
            background: linear-gradient(135deg, var(--primary-50), var(--surface));
            border-bottom: 1px solid var(--border-light);
            padding: 2rem;
        }
        
        .loan-modal .modal-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .loan-modal .modal-body {
            padding: 2rem;
        }

        /* Layout do Modal */
        .modal-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            align-items: start;
        }

        .book-info-panel {
            background: var(--primary-50);
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid var(--border-light);
        }

        .form-panel {
            background: var(--surface);
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid var(--border-light);
        }

        .selected-book {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--border-light);
        }

        .selected-book-cover {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-500), var(--primary-700));
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .selected-book-info h5 {
            margin: 0 0 0.25rem 0;
            font-weight: 600;
            color: var(--text-primary);
        }

        .selected-book-meta {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .isbn-tag {
            background: var(--primary-100);
            color: var(--primary-700);
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-block;
        }

        .availability-badge {
            padding: 0.375rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .summary-card {
            background: var(--surface);
            border-radius: 8px;
            padding: 1.25rem;
            border: 1px solid var(--border-light);
            margin-top: 1.5rem;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--border-light);
        }

        .summary-item:last-child {
            border-bottom: none;
        }

        .summary-label {
            color: var(--text-secondary);
            font-weight: 500;
        }

        .summary-value {
            color: var(--text-primary);
            font-weight: 600;
        }

        .return-date-highlight {
            background: var(--success-50);
            border: 1px solid var(--success-100);
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .return-label {
            color: var(--success-700);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .return-date {
            color: var(--success-700);
            font-weight: 700;
            font-size: 1.1rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-label.required::after {
            content: " *";
            color: var(--danger-500);
        }

        .form-control {
            border: 2px solid var(--border);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            width: 100%;
        }

        .form-control:focus {
            border-color: var(--primary-400);
            box-shadow: 0 0 0 3px rgba(100, 116, 139, 0.1);
            outline: none;
        }

        /* CORREÇÃO: Campo Período de Empréstimo */
        .loan-period-container {
            position: relative; height:45px;
        }

        .loan-period-container select { height: 50px;
            width: 100%;
            
            border: 2px solid var(--border);
            border-radius: 8px;
            font-size: 1rem;
            background-color: white;
            transition: all 0.3s ease;
            appearance: none;
            cursor: pointer;
        }

        .loan-period-container select:focus {
            border-color: var(--primary-400);
            box-shadow: 0 0 0 3px rgba(100, 116, 139, 0.1);
            outline: none;
        }

        .select-arrow {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: var(--text-tertiary);
            font-size: 1rem;
        }

        .input-group-text {
            background: var(--primary-50);
            border: 2px solid var(--border);
            border-right: none;
            color: var(--text-secondary);
        }

        .form-control:focus + .input-group-text {
            border-color: var(--primary-400);
        }

        .modal-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-light);
        }

        .btn-secondary {
            background: var(--primary-100);
            color: var(--primary-700);
            border: 1px solid var(--primary-200);
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-secondary:hover {
            background: var(--primary-200);
            border-color: var(--primary-300);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-600), var(--primary-700));
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .page-container {
                padding: 1rem;
            }
            
            .page-title {
                font-size: 2rem;
            }
            
            .stats-container {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .books-grid {
                grid-template-columns: 1fr;
            }
            
            .book-card {
                margin-bottom: 1rem;
            }
            
            .loan-modal .modal-header,
            .loan-modal .modal-body {
                padding: 1.5rem;
            }

            .modal-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .modal-actions {
                align-items: center;
                flex-direction: column;
            }
        }
        
        /* Animações Suaves */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .book-card {
            animation: fadeIn 0.5s ease-out;
        }
        
        .stat-card {
            animation: fadeIn 0.6s ease-out;
        }
        
        /* Scrollbar Personalizada */
        ::-webkit-scrollbar {
            width: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--primary-100);
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--primary-300);
            border-radius: 3px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-400);
        }
    </style>
</head>
<body>
    <div class="page-container">
        <!-- Header Elegante -->
        <div class="page-header">
            <h1 class="page-title">Gestão de Empréstimos</h1>
            <p class="page-subtitle">Gerencie e acompanhe todos os empréstimos da biblioteca de forma eficiente</p>
        </div>

        <?php
       require_once('../../../conexao/conexao.php');

        try {
            // Buscar todos os livros com UNIDADES_DISPONIVEIS
            $comandoSQL = "SELECT ISBN, TITULO, EDITORA, AUTOR, UNIDADES_DISPONIVEIS FROM LIVROS";
            $select = $conexao->query($comandoSQL);
            $livros = $select->fetchAll();
            $totalLivros = count($livros);

            // Buscar empréstimos ativos para calcular disponibilidade real
            $comandoEmprestimos = "SELECT LIVRO_ISBN, COUNT(*) as total_emprestado 
                                  FROM EMPRESTIMO 
                                  WHERE STATUS_LIVRO != 'DEVOLVIDO' 
                                  GROUP BY LIVRO_ISBN";
            $selectEmprestimos = $conexao->query($comandoEmprestimos);
            $emprestimosAtivos = $selectEmprestimos->fetchAll(PDO::FETCH_KEY_PAIR);

            // Calcular estatísticas
            $livrosDisponiveis = 0;
            $livrosEmprestados = 0;
            $livrosIndisponiveis = 0;
            $totalEmprestimosAtivos = 0;

            // Processar disponibilidade de cada livro
            $livrosComEstoque = [];
            foreach ($livros as $livro) {
                $isbn = $livro['ISBN'];
                $unidadesDisponiveis = $livro['UNIDADES_DISPONIVEIS'] ?? 0;
                $emprestados = $emprestimosAtivos[$isbn] ?? 0;
                
                // Calcular unidades realmente disponíveis
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

        <!-- Cards de Estatísticas Modernos -->
        <div class="stats-container">
            <div class="stat-card total">
                <div class="stat-content">
                    <div class="stat-icon">
                        <i class="bi bi-book"></i>
                    </div>
                    <div class="stat-text">
                        <div class="stat-number"><?php echo $totalLivros; ?></div>
                        <div class="stat-label">Total de Livros</div>
                    </div>
                </div>
            </div>
            
            <div class="stat-card disponiveis">
                <div class="stat-content">
                    <div class="stat-icon">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="stat-text">
                        <div class="stat-number"><?php echo $livrosDisponiveis; ?></div>
                        <div class="stat-label">Disponíveis</div>
                    </div>
                </div>
            </div>
            
            <div class="stat-card emprestados">
                <div class="stat-content">
                    <div class="stat-icon">
                        <i class="bi bi-clock"></i>
                    </div>
                    <div class="stat-text">
                        <div class="stat-number"><?php echo $livrosEmprestados; ?></div>
                        <div class="stat-label">Emprestados</div>
                    </div>
                </div>
            </div>
            
            <div class="stat-card indisponiveis">
                <div class="stat-content">
                    <div class="stat-icon">
                        <i class="bi bi-x-circle"></i>
                    </div>
                    <div class="stat-text">
                        <div class="stat-number"><?php echo $livrosIndisponiveis; ?></div>
                        <div class="stat-label">Indisponíveis</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Barra de Pesquisa Elegante -->
        <div class="search-section">
            <div class="search-container">
                <i class="bi bi-search search-icon"></i>
                <input type="text" class="search-input" placeholder="Pesquisar livros por título, autor, editora ou ISBN..." id="searchInput">
            </div>
        </div>

        <!-- Grid de Livros Moderno -->
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
                                            <strong><?php echo $disponiveisReal; ?></strong> de <strong><?php echo $unidadesDisponiveis; ?></strong> unidades disponíveis
                                            <?php if($unidadesEmprestadas > 0): ?>
                                                <span style="color: var(--warning-600);">(<?php echo $unidadesEmprestadas; ?> emprestadas)</span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <strong style="color: var(--danger-600);">Indisponível</strong> - 
                                            <?php echo $unidadesEmprestadas; ?> de <?php echo $unidadesDisponiveis; ?> unidades emprestadas
                                        <?php endif; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="book-footer">
                            <div class="stock-info">
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
            <!-- Estado Vazio Moderno -->
            <div class="empty-state">
                <i class="bi bi-book-x empty-icon"></i>
                <div class="empty-title">Nenhum livro encontrado</div>
                <div class="empty-text">Não há livros disponíveis para empréstimo no momento. Verifique o catálogo da biblioteca.</div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Modal de Empréstimo Funcional -->
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
                        <!-- Painel de Informações do Livro -->
                        <div class="book-info-panel">
                            <div class="selected-book">
                                <div class="selected-book-cover">
                                    <i class="bi bi-book"></i>
                                </div>
                                <div class="selected-book-info">
                                    <h5 id="modalBookTitle">Selecione um livro</h5>
                                    <div class="selected-book-meta">
                                        <span class="isbn-tag" id="modalBookIsbn">ISBN: --</span>
                                        <span class="availability-badge stock-available" id="modalBookAvailability">
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
                                    Previsão de Devolução:
                                </div>
                                <div class="return-date" id="modalReturnDate">
                                    <?php echo date('d/m/Y', strtotime('+14 days')); ?>
                                </div>
                            </div>
                        </div>

                        <!-- Painel do Formulário -->
                        <div class="form-panel">
                            <form action="/Api/emprestimos/cadastrar.php" method="post" id="loanForm">
                                <input type="hidden" name="txtLIVRO_ISBN" id="modalIsbnInput">
                                <input type="hidden" name="txtSTATUS_LIVRO" value="PENDENTE">

                                <div class="form-group">
                                    <label class="form-label required" for="userDocument">
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
                                            <i class="bi bi-search"></i> Buscar
                                        </button>
                                    </div>
                                    <small class="form-text text-muted">Digite o documento do utilizador para verificar disponibilidade</small>
                                </div>

                                <div class="form-group">
                                    <label class="form-label required" for="loanDate">
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
                                    <label class="form-label required" for="loanPeriod">
                                        <i class="bi bi-clock"></i>
                                        Período de Empréstimo
                                    </label>
                                    <!-- CORREÇÃO: Campo Período de Empréstimo corrigido -->
                                    <div class="loan-period-container">
                                        <select class="form-control" id="loanPeriod" name="txtTEMPO_EMPRESTIMO" required>
                                            <option value="">Selecione o período</option>
                                            <option value="7">7 dias</option>
                                            <option value="14" selected>14 dias</option>
                                            <option value="21">21 dias</option>
                                            <option value="30">30 dias</option>
                                        </select>
                                        <div class="select-arrow">▼</div>
                                    </div>
                                </div>

                                <div class="modal-actions">
                                    
                                    <button type="submit" class="btn btn-primary" id="btnConfirmLoan">
                                        <i class="bi bi-check-lg"></i> Confirmar Empréstimo
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
        // Função para abrir modal de empréstimo com dados do livro
        function abrirModalEmprestimo(livro) {
            // Preencher dados do livro no modal
            document.getElementById('modalBookTitle').textContent = livro.TITULO || 'Título não disponível';
            document.getElementById('modalBookIsbn').textContent = 'ISBN: ' + (livro.ISBN || '--');
            document.getElementById('modalIsbnInput').value = livro.ISBN || '';
            
            // Preencher informações do livro
            document.getElementById('summaryBookTitle').textContent = livro.TITULO || '--';
            document.getElementById('summaryBookIsbn').textContent = livro.ISBN || '--';
            document.getElementById('summaryBookAuthor').textContent = livro.AUTOR || '--';
            document.getElementById('summaryBookPublisher').textContent = livro.EDITORA || '--';
            
            // Atualizar informações de stock
            const disponivel = livro.DISPONIVEL;
            const disponiveisReal = livro.DISPONIVEIS_REAL || 0;
            const unidadesDisponiveis = livro.UNIDADES_DISPONIVEIS || 0;
            const unidadesEmprestadas = livro.UNIDADES_EMPRESTADAS || 0;
            
            const availabilityBadge = document.getElementById('modalBookAvailability');
            const stockInfo = document.getElementById('summaryBookStock');
            
            if (disponivel) {
                availabilityBadge.className = 'availability-badge stock-available';
                availabilityBadge.innerHTML = '<i class="bi bi-check-circle-fill"></i> Disponível';
                stockInfo.textContent = `${disponiveisReal} de ${unidadesDisponiveis} unidades disponíveis`;
                document.getElementById('btnConfirmLoan').disabled = false;
            } else {
                availabilityBadge.className = 'availability-badge stock-unavailable';
                availabilityBadge.innerHTML = '<i class="bi bi-x-circle-fill"></i> Indisponível';
                stockInfo.textContent = `Indisponível - ${unidadesEmprestadas} de ${unidadesDisponiveis} unidades emprestadas`;
                document.getElementById('btnConfirmLoan').disabled = true;
            }
            
            // Abrir o modal
            const modal = new bootstrap.Modal(document.getElementById('modalEmprestimo'));
            modal.show();
            
            // Atualizar data de retorno
            atualizarDataRetorno();
        }

        // Atualizar data de retorno baseada no período selecionado
        function atualizarDataRetorno() {
            const loanDate = document.getElementById('loanDate').value;
            const loanPeriod = document.getElementById('loanPeriod').value;
            
            if (loanDate && loanPeriod) {
                const dataEmprestimo = new Date(loanDate);
                const dataRetorno = new Date(dataEmprestimo);
                dataRetorno.setDate(dataRetorno.getDate() + parseInt(loanPeriod));
                
                const options = { day: '2-digit', month: '2-digit', year: 'numeric' };
                const dataFormatada = dataRetorno.toLocaleDateString('pt-PT', options);
                
                document.getElementById('modalReturnDate').textContent = dataFormatada;
            }
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Filtro de pesquisa
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
            
            // Atualizar data de retorno quando mudar data ou período
            document.getElementById('loanDate').addEventListener('change', atualizarDataRetorno);
            document.getElementById('loanPeriod').addEventListener('change', atualizarDataRetorno);
            
            // Buscar utilizador
            document.getElementById('btnSearchUser').addEventListener('click', function() {
                const userDocument = document.getElementById('userDocument').value.trim();
                if (userDocument) {
                    // Simular busca de utilizador
                    alert(`Buscando utilizador com documento: ${userDocument}\n\nEsta funcionalidade integrará com o sistema de utilizadores.`);
                } else {
                    alert('Por favor, digite um CPF ou NIF para buscar.');
                }
            });
            
            // Validação do formulário
            document.getElementById('loanForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const userDocument = document.getElementById('userDocument').value.trim();
                const loanDate = document.getElementById('loanDate').value;
                const loanPeriod = document.getElementById('loanPeriod').value;
                
                if (!userDocument || !loanDate || !loanPeriod) {
                    alert('Por favor, preencha todos os campos obrigatórios.');
                    return;
                }
                
                // Simular envio do formulário
                if (confirm('Confirmar empréstimo?\n\nLivro: ' + document.getElementById('modalBookTitle').textContent + 
                           '\nUtilizador: ' + userDocument + 
                           '\nPeríodo: ' + loanPeriod + ' dias' +
                           '\nDevolução: ' + document.getElementById('modalReturnDate').textContent)) {
                    this.submit();
                }
            });

            // CORREÇÃO: Botão Cancelar funcional
            document.querySelector('.btn-secondary[data-bs-dismiss="modal"]').addEventListener('click', function() {
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalEmprestimo'));
                modal.hide();
            });

            // CORREÇÃO: Botão Fechar (X) funcional
            document.querySelector('.btn-close').addEventListener('click', function() {
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalEmprestimo'));
                modal.hide();
            });
        });

        // Efeitos de hover suaves
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.book-card, .stat-card');
            
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
                });
            });
        });
    </script>
</body>
</html>

<?php include('../../layout/footer.html'); ?>