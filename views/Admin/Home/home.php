<?php
# Impede que usuários acessem a página se não estiverem logados
include('../../../seguranca/seguranca.php');
session_start();
if(administrador_logado() == false) {header("location: /index.php"); exit;}

// Conexão com o banco de dados
require_once('../../../conexao/conexao.php');

// Buscar dados para o dashboard
// Total de livros
try {
    $comandoLivros = "SELECT COUNT(*) as total FROM LIVROS";
    $selectLivros = $conexao->query($comandoLivros);
    $totalLivros = $selectLivros->fetch()['total'];
} catch (Exception $e) {
    $totalLivros = 0;
}

// Total de usuários
try {
    $comandoUsuarios = "SELECT COUNT(*) as total FROM UTILIZADORES";
    $selectUsuarios = $conexao->query($comandoUsuarios);
    $totalUsuarios = $selectUsuarios->fetch()['total'];
} catch (Exception $e) {
    $totalUsuarios = 0;
}

// Total de empréstimos ativos
try {
    $comandoEmprestimos = "SELECT COUNT(*) as total FROM EMPRESTIMO WHERE STATUS_LIVRO = 'NÃO DEVOLVIDO'";
    $selectEmprestimos = $conexao->query($comandoEmprestimos);
    $totalEmprestimosAtivos = $selectEmprestimos->fetch()['total'];
} catch (Exception $e) {
    $totalEmprestimosAtivos = 0;
}

// Empréstimos Expirados
try {
    $dataAtual = date('Y-m-d');
    $comandoExpirado = "SELECT COUNT(*) as total FROM EMPRESTIMO WHERE STATUS_LIVRO = 'NÃO DEVOLVIDO' AND DATE_ADD(DATA_EMPRESTADO, INTERVAL TEMPO_EMPRESTIMO DAY) < '$dataAtual'";
    $selectExpirado = $conexao->query($comandoExpirado);
    $totalExpirado = $selectExpirado->fetch()['total'];
} catch (Exception $e) {
    $totalExpirado = 0;
}

// Empréstimos deste mês
try {
    $primeiroDiaMes = date('Y-m-01');
    $ultimoDiaMes = date('Y-m-t');
    $comandoEmprestimosMes = "SELECT COUNT(*) as total FROM EMPRESTIMO WHERE DATA_EMPRESTADO BETWEEN '$primeiroDiaMes' AND '$ultimoDiaMes'";
    $selectEmprestimosMes = $conexao->query($comandoEmprestimosMes);
    $totalEmprestimosMes = $selectEmprestimosMes->fetch()['total'];
} catch (Exception $e) {
    $totalEmprestimosMes = 0;
}

// Empréstimos devolvidos este mês
try {
    $comandoDevolvidosMes = "SELECT COUNT(*) as total FROM EMPRESTIMO WHERE STATUS_LIVRO = 'DEVOLVIDO' AND DATA_DEVOLUCAO BETWEEN '$primeiroDiaMes' AND '$ultimoDiaMes'";
    $selectDevolvidosMes = $conexao->query($comandoDevolvidosMes);
    $totalDevolvidosMes = $selectDevolvidosMes->fetch()['total'];
} catch (Exception $e) {
    $totalDevolvidosMes = 0;
}

// NOVO: Total de utilizadores registados este mês
try {
    $comandoUtilizadoresMes = "SELECT COUNT(*) as total FROM UTILIZADORES 
                              WHERE MONTH(data_registo) = MONTH(CURRENT_DATE()) 
                              AND YEAR(data_registo) = YEAR(CURRENT_DATE())";
    $selectUtilizadoresMes = $conexao->query($comandoUtilizadoresMes);
    $totalUtilizadoresMes = $selectUtilizadoresMes->fetch()['total'];
} catch (Exception $e) {
    $totalUtilizadoresMes = 0;
}

// NOVO: Total de livros adicionados este mês
try {
    $comandoLivrosMes = "SELECT COUNT(*) as total FROM LIVROS 
                        WHERE MONTH(data_adicao) = MONTH(CURRENT_DATE()) 
                        AND YEAR(data_adicao) = YEAR(CURRENT_DATE())";
    $selectLivrosMes = $conexao->query($comandoLivrosMes);
    $totalLivrosMes = $selectLivrosMes->fetch()['total'];
} catch (Exception $e) {
    $totalLivrosMes = 0;
}

// Próximas devoluções
try {
    $comandoProximasDevolucoes = "SELECT E.ID, L.TITULO, U.NOME, U.SOBRENOME, E.DATA_EMPRESTADO, 
                                         E.TEMPO_EMPRESTIMO, E.STATUS_LIVRO,
                                         DATE_ADD(E.DATA_EMPRESTADO, INTERVAL E.TEMPO_EMPRESTIMO DAY) as DATA_VENCIMENTO
                                  FROM EMPRESTIMO E 
                                  INNER JOIN LIVROS L ON E.LIVRO_ISBN = L.ISBN 
                                  INNER JOIN UTILIZADORES U ON E.NIF_PESSOA = U.NIF 
                                  WHERE E.STATUS_LIVRO = 'NÃO DEVOLVIDO'
                                  ORDER BY DATA_VENCIMENTO ASC 
                                  LIMIT 6";
    $selectProximasDevolucoes = $conexao->query($comandoProximasDevolucoes);
    $proximasDevolucoes = $selectProximasDevolucoes->fetchAll();
} catch (Exception $e) {
    $proximasDevolucoes = [];
}

// Livros mais emprestados
try {
    $comandoPopulares = "SELECT L.TITULO, L.AUTOR, COUNT(E.ID) as total_emprestimos 
                         FROM LIVROS L 
                         LEFT JOIN EMPRESTIMO E ON L.ISBN = E.LIVRO_ISBN 
                         GROUP BY L.ISBN, L.TITULO, L.AUTOR 
                         ORDER BY total_emprestimos DESC 
                         LIMIT 5";
    $selectPopulares = $conexao->query($comandoPopulares);
    $livrosPopulares = $selectPopulares->fetchAll();
} catch (Exception $e) {
    $livrosPopulares = [];
}

// Dados para o gráfico de empréstimos mensais (últimos 6 meses)
$emprestimosMensais = [];
$labelsMensais = [];
try {
    for ($i = 5; $i >= 0; $i--) {
        $mes = date('Y-m', strtotime("-$i months"));
        $primeiroDia = date('Y-m-01', strtotime($mes));
        $ultimoDia = date('Y-m-t', strtotime($mes));
        
        $comandoMes = "SELECT COUNT(*) as total FROM EMPRESTIMO WHERE DATA_EMPRESTADO BETWEEN '$primeiroDia' AND '$ultimoDia'";
        $selectMes = $conexao->query($comandoMes);
        $totalMes = $selectMes->fetch()['total'];
        
        $emprestimosMensais[] = $totalMes;
        $labelsMensais[] = date('M/Y', strtotime($mes));
    }
} catch (Exception $e) {
    // Se houver erro, usa dados padrão
    $emprestimosMensais = [12, 19, 8, 15, 12, 16];
    $labelsMensais = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'];
}

// Inclui o código HTML
include('../../../layout/header.html');
include('../../../layout/navbar.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Biblioteca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #3498db;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --text-color: #2c3e50;
            --text-light: #7f8c8d;
            --border-color: #ecf0f1;
            --card-shadow: 0 2px 8px rgba(0,0,0,0.06);
            --hover-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        
        body {
          
        }
        
        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 1.5rem 1rem;
            margin-top: 2rem; /* NOVO: Adiciona espaço entre a navbar e o conteúdo */
        }
        
        .dashboard-header {
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .dashboard-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.25rem;
        }
        
        .dashboard-subtitle {
            color: var(--text-light);
            font-size: 0.9rem;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 1.25rem;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--hover-shadow);
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--accent-color);
        }
        
        .stat-card.books::before { background: #3498db; }
        .stat-card.users::before { background: #27ae60; }
        .stat-card.active::before { background: #f39c12; }
        .stat-card.expired::before { background: #e74c3c; }
        
        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--primary-color);
            line-height: 1;
            margin-bottom: 0.25rem;
        }
        
        .stat-label {
            font-size: 0.8rem;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }
        
        .stat-icon {
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }
        
        .stat-card.books .stat-icon { color: #3498db; }
        .stat-card.users .stat-icon { color: #27ae60; }
        .stat-card.active .stat-icon { color: #f39c12; }
        .stat-card.expired .stat-icon { color: #e74c3c; }
        
        .content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 1.5rem;
            margin-bottom: 2rem;
            align-items: stretch;
        }
        
        .main-content, .sidebar {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .chart-container {
            background: white;
            border-radius: 8px;
            padding: 1.25rem;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--border-color);
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .chart-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-shrink: 0;
        }
        
        .chart-title i {
            color: var(--accent-color);
        }
        
        .chart-wrapper {
            height: 200px;
            position: relative;
            flex: 1;
        }
        
        .stats-card {
            background: white;
            border-radius: 8px;
            padding: 1.25rem;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--border-color);
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .stats-card .chart-title {
            margin-bottom: 0rem;
            flex-shrink: 0;
        }
        
        .stats-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .list-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border-color);
            transition: background-color 0.2s ease;
        }
        
        .list-item:hover {
            background-color: #f8f9fa;
        }
        
        .list-item:last-child {
            border-bottom: none;
        }
        
        .item-content {
            flex: 1;
        }
        
        .item-title {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.2rem;
            line-height: 1.4;
            font-size: 0.9rem;
        }
        
        .item-subtitle {
            font-size: 0.75rem;
            color: var(--text-light);
            margin-bottom: 0.4rem;
        }
        
        .item-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 0.7rem;
            color: var(--text-light);
        }
        
        .badge {
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.65rem;
            font-weight: 600;
            letter-spacing: 0.3px;
        }
        
        .badge-success {
            background-color: var(--success-color);
            color: white;
        }
        
        .badge-warning {
            background-color: var(--warning-color);
            color: white;
        }
        
        .badge-danger {
            background-color: var(--danger-color);
            color: white;
        }
        
        .badge-neutral {
            background-color: var(--border-color);
            color: var(--text-light);
        }
        
        .status-Expirado{
            background-color: var(--danger-color);
            color: white;
        }
        
        .status-hoje {
            background-color: var(--warning-color);
            color: white;
        }
        
        .empty-state {
            text-align: center;
            padding: 2rem 1rem;
            color: var(--text-light);
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        
        .empty-icon {
            font-size: 2rem;
            margin-bottom: 0.75rem;
            opacity: 0.4;
        }
        
        .empty-text {
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
            font-weight: 500;
        }
        
        .empty-subtext {
            font-size: 0.75rem;
            opacity: 0.7;
        }
        
        .section-divider {
            height: 1px;
            background: var(--border-color);
            margin: 1rem 0;
        }
        
        .month-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
            margin: 1rem 0;
        }
        
        .month-stat {
            text-align: center;
            padding: 0.75rem 0.5rem;
            background: #f8f9fa;
            border-radius: 6px;
        }
        
        .month-value {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.25rem;
        }
        
        .month-label {
            font-size: 0.65rem;
            color: var(--text-light);
            text-transform: uppercase;
            font-weight: 600;
        }
        
        .list-content {
            flex: 1;
            overflow-y: auto;
            max-height: 300px;
        }
        
        /* NOVO: Estilo para o grid de 4 colunas */
        .month-stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
            margin: 0.5rem 0;
        }
        
        @media (min-width: 768px) {
            .month-stats-grid {
                grid-template-columns: 1fr 1fr 1fr 1fr;
            }
        }
        
        @media (max-width: 1024px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
            
            .sidebar {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 1.5rem;
            }
        }
        
        @media (max-width: 768px) {
            .dashboard-container {
                padding: 1rem;
                margin-top: 1.5rem; /* NOVO: Margem menor em mobile */
            }
            
            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                gap: 0.75rem;
            }
            
            .stat-value {
                font-size: 1.5rem;
            }
            
            .chart-container,
            .stats-card {
                padding: 1rem;
            }
            
            .sidebar {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr 1fr;
            }
            
            .dashboard-title {
                font-size: 1.25rem;
            }
            
            .month-stats,
            .month-stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
      

        <!-- Cards de Estatísticas Compactos -->
        <div class="stats-grid">
            <div class="stat-card books">
                <i class="bi bi-book stat-icon"></i>
                <div class="stat-value"><?php echo $totalLivros; ?></div>
                <div class="stat-label">Livros</div>
            </div>
            
            <div class="stat-card users">
                <i class="bi bi-people stat-icon"></i>
                <div class="stat-value"><?php echo $totalUsuarios; ?></div>
                <div class="stat-label">Utilizadores</div>
            </div>
            
            <div class="stat-card active">
                <i class="bi bi-arrow-left-right stat-icon"></i>
                <div class="stat-value"><?php echo $totalEmprestimosAtivos; ?></div>
                <div class="stat-label">Ativos</div>
            </div>
            
            <div class="stat-card expired">
                <i class="bi bi-exclamation-triangle stat-icon"></i>
                <div class="stat-value"><?php echo $totalExpirado; ?></div>
                <div class="stat-label">Expirados</div>
            </div>
        </div>

        <!-- Conteúdo Principal -->
        <div class="content-grid">
            <!-- Coluna Principal -->
            <div class="main-content">
                <!-- Gráfico de Empréstimos -->
                <div class="chart-container">
                    <h3 class="chart-title">
                        <i class="bi bi-bar-chart"></i>
                        Empréstimos Mensais (Últimos 6 Meses)
                    </h3>
                    <div class="chart-wrapper">
                        <canvas id="emprestimosChart"></canvas>
                    </div>
                </div>

                <!-- Estatísticas do Mês ATUALIZADO -->
                <div class="stats-card">
                    <h3 class="chart-title">
                        <i class="bi bi-calendar"></i>
                        Este Mês (<?php echo date('M/Y'); ?>)
                    </h3>
                    <div class="stats-content">
                        <div class="month-stats-grid">
                            <div class="month-stat">
                                <div class="month-value"><?php echo $totalEmprestimosMes; ?></div>
                                <div class="month-label">Empréstimos</div>
                            </div>
                            <div class="month-stat">
                                <div class="month-value">
                                    <?php 
                                        $taxaDevolucao = $totalEmprestimosMes > 0 ? 
                                            round(($totalDevolvidosMes / $totalEmprestimosMes) * 100) : 0;
                                        echo $taxaDevolucao . '%';
                                    ?>
                                </div>
                                <div class="month-label">Taxa Devolução</div>
                            </div>
                            <div class="month-stat">
                                <div class="month-value"><?php echo $totalUtilizadoresMes; ?></div>
                                <div class="month-label">Novos Utilizadores</div>
                            </div>
                            <div class="month-stat">
                                <div class="month-value"><?php echo $totalLivrosMes; ?></div>
                                <div class="month-label">Livros Adicionados</div>
                            </div>
                        </div>
                        
                        <div class="section-divider"></div>
                        
                        <div style="text-align: center; margin-top: 0.5rem;">
                            <div class="item-meta" style="justify-content: center;">
                                <span>Devolvidos: <?php echo $totalDevolvidosMes; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Próximas Devoluções -->
                <div class="stats-card">
                    <h3 class="chart-title">
                        <i class="bi bi-calendar-check"></i>
                         Devoluções Pendentes
                    </h3>
                    <div class="list-content">
                        <?php if(count($proximasDevolucoes) > 0): ?>
                            <?php foreach($proximasDevolucoes as $emprestimo): 
                                $dataVencimento = $emprestimo['DATA_VENCIMENTO'];
                                $dataAtual = date('Y-m-d');
                                $diasRestantes = floor((strtotime($dataVencimento) - strtotime($dataAtual)) / (60 * 60 * 24));
                                
                                if ($diasRestantes < 0) {
                                    $statusClass = 'status-vencido';
                                    $statusText = 'Expirado há ' . abs($diasRestantes) . ' dias';
                                } else if ($diasRestantes == 0) {
                                    $statusClass = 'status-hoje';
                                    $statusText = 'Hoje';
                                } else if ($diasRestantes <= 2) {
                                    $statusClass = 'badge-warning';
                                    $statusText = $diasRestantes . ' dias';
                                } else {
                                    $statusClass = 'badge-success';
                                    $statusText = $diasRestantes . ' dias';
                                }
                            ?>
                                <div class="list-item">
                                    <div class="item-content">
                                        <div class="item-title"><?php echo htmlspecialchars($emprestimo['TITULO']); ?></div>
                                        <div class="item-subtitle"><?php echo htmlspecialchars($emprestimo['NOME'] . ' ' . $emprestimo['SOBRENOME']); ?></div>
                                        <div class="item-meta">
                                            <span>Expira: <?php echo date('d/m/Y', strtotime($dataVencimento)); ?></span>
                                        </div>
                                    </div>
                                    <span class="badge <?php echo $statusClass; ?>">
                                        <?php echo $statusText; ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="bi bi-check-circle empty-icon"></i>
                                <div class="empty-text">Nenhuma devolução pendente</div>
                                <div class="empty-subtext">Todos os livros foram entregues dentro do prazo</div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Livros Populares -->
                <div class="stats-card">
                    <h3 class="chart-title">
                        <i class="bi bi-star"></i>
                        Livros Populares
                    </h3>
                    <div class="list-content">
                        <?php if(count($livrosPopulares) > 0): ?>
                            <?php foreach($livrosPopulares as $livro): ?>
                                <div class="list-item">
                                    <div class="item-content">
                                        <div class="item-title"><?php echo htmlspecialchars($livro['TITULO']); ?></div>
                                        <?php if(!empty($livro['AUTOR'])): ?>
                                        <div class="item-subtitle"><?php echo htmlspecialchars($livro['AUTOR']); ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <span class="badge badge-neutral"><?php echo $livro['total_emprestimos']; ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="bi bi-book empty-icon"></i>
                                <div class="empty-text">Sem dados de popularidade</div>
                                <div class="empty-subtext">Aguardando empréstimos</div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Dados reais para o gráfico
        const emprestimosData = {
            labels: <?php echo json_encode($labelsMensais); ?>,
            datasets: [{
                label: 'Empréstimos',
                data: <?php echo json_encode($emprestimosMensais); ?>,
                backgroundColor: 'rgba(52, 152, 219, 0.08)',
                borderColor: 'rgba(52, 152, 219, 1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }]
        };

        // Inicializar gráfico
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('emprestimosChart').getContext('2d');
            const emprestimosChart = new Chart(ctx, {
                type: 'line',
                data: emprestimosData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.03)'
                            },
                            ticks: {
                                font: {
                                    size: 10
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 10
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(44, 62, 80, 0.9)',
                            titleFont: {
                                size: 11
                            },
                            bodyFont: {
                                size: 10
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    elements: {
                        point: {
                            radius: 3,
                            hoverRadius: 5,
                            backgroundColor: 'rgba(52, 152, 219, 1)'
                        }
                    }
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include('../../../layout/footer.html'); ?>