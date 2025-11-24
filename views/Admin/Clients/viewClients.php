<?php
# Impede que usuários acessem a página se não estiverem logados
include('../../../seguranca/seguranca.php');
session_start();
if(administrador_logado() == false) {header("location: /index.php"); exit;}

include('../../../layout/header.html');
include('../../../layout/navbar.php');
include("../../../recursos.php");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuários - Sistema de Biblioteca</title>
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
        }
        
        body {
            background-color: #f8f9fa;
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
            text-align: center;
        }
        
        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .page-subtitle {
            color: var(--text-light);
            font-size: 0.9rem;
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
        
        .view-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .view-buttons {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn-view {
            padding: 0.4rem 0.8rem;
            border: 1px solid var(--border-color);
            background: white;
            color: var(--text-color);
            border-radius: 4px;
            font-size: 0.8rem;
            transition: all 0.2s ease;
        }
        
        .btn-view.active {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }
        
        .stats {
            font-size: 0.8rem;
            color: var(--text-light);
        }
        
        /* Cards View */
        .cards-view {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1rem;
        }
        
        .user-card {
            background: white;
            border-radius: 8px;
            padding: 1.25rem;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .user-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--hover-shadow);
        }
        
        .user-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .user-info h4 {
            margin: 0;
            font-size: 1rem;
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .user-info p {
            margin: 0;
            font-size: 0.8rem;
            color: var(--text-light);
        }
        
        .user-details {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        
        .detail-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
            color: var(--text-color);
        }
        
        .detail-item i {
            color: var(--accent-color);
            width: 14px;
            font-size: 0.7rem;
        }
        
        .card-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn-action {
            flex: 1;
            padding: 0.4rem 0.6rem;
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
        
        /* List View */
        .list-view {
            background: white;
            border-radius: 8px;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--border-color);
            overflow: hidden;
        }
        
        .list-header {
            background: #fafbfc;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--border-color);
            display: grid;
            grid-template-columns: 2fr 1fr 1fr auto;
            gap: 1rem;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-color);
        }
        
        .list-item {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            display: grid;
            grid-template-columns: 2fr 1fr 1fr auto;
            gap: 1rem;
            align-items: center;
            transition: background-color 0.2s ease;
            cursor: pointer;
        }
        
        .list-item:hover {
            background-color: #fafbfc;
        }
        
        .list-item:last-child {
            border-bottom: none;
        }
        
        .list-user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .list-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .list-user-info h5 {
            margin: 0;
            font-size: 0.9rem;
            font-weight: 600;
        }
        
        .list-user-info p {
            margin: 0;
            font-size: 0.75rem;
            color: var(--text-light);
        }
        
        .list-detail {
            font-size: 0.8rem;
            color: var(--text-color);
        }
        
        .list-actions {
            display: flex;
            gap: 0.25rem;
        }
        
        .btn-list-action {
            padding: 0.3rem 0.5rem;
            border: 1px solid var(--border-color);
            background: white;
            color: var(--text-color);
            border-radius: 3px;
            font-size: 0.7rem;
            transition: all 0.2s ease;
        }
        
        .btn-list-action:hover {
            background: #f8f9fa;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 2rem 1rem;
            color: var(--text-light);
        }
        
        .empty-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            opacity: 0.4;
        }
        
        .empty-text {
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }
        
        /* MODAL MELHORADO */
        .user-modal .modal-content {
            border: none;
            border-radius: 12px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .user-modal .modal-header {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-bottom: 1px solid #e5e7eb;
            padding: 1.5rem 2rem;
            border-radius: 12px 12px 0 0;
            position: relative;
        }

        .user-modal .modal-header-content {
            display: flex;
            align-items: center;
            gap: 1rem;
            width: 100%;
        }

        .user-modal .modal-avatar {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .user-modal .modal-user-info {
            flex: 1;
        }

        .user-modal .modal-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary-color);
            margin: 0 0 0.25rem 0;
        }

        .user-modal .modal-subtitle {
            font-size: 0.9rem;
            color: var(--text-light);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .user-modal .btn-close-custom {
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

        .user-modal .btn-close-custom:hover {
            background: white;
            color: var(--text-color);
            transform: scale(1.1);
        }

        .user-modal .modal-body {
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

        .stats-section {
            background: linear-gradient(135deg, #f8fafc, #e5e7eb);
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 1.5rem;
            margin-top: 1rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 1rem;
        }

        .stat-item {
            text-align: center;
            padding: 0.75rem;
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.75rem;
            color: var(--text-light);
            font-weight: 500;
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

        /* Responsividade */
        @media (max-width: 768px) {
            .page-container {
                padding: 1rem;
            }
            
            .cards-view {
                grid-template-columns: 1fr;
            }
            
            .list-header, .list-item {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }
            
            .list-actions {
                justify-content: center;
                margin-top: 0.5rem;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .user-modal .modal-header-content {
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
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .user-modal .modal-body {
                padding: 1.5rem 1rem;
            }
            
            .user-modal .modal-header {
                padding: 1.25rem 1.5rem;
            }
            
            .user-modal .btn-close-custom {
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
            <h1 class="page-title">Utilizadores</h1>
            <p class="page-subtitle">Gerencie os utilizadores do sistema</p>
        </div>

        <!-- Barra de Pesquisa -->
        <div class="search-container">
            <div class="search-box">
                <i class="bi bi-search search-icon"></i>
                <input type="text" class="form-control search-input" id="searchInput" 
                       placeholder="Pesquisar utilizadores por nome, sobrenome ou NIF...">
            </div>
        </div>

        <!-- Controles de Visualização -->
        <div class="view-controls">
            <div class="view-buttons">
                <button class="btn-view active" data-view="cards">
                    <i class="bi bi-grid"></i>
                    Cards
                </button>
                <button class="btn-view" data-view="list">
                    <i class="bi bi-list"></i>
                    Lista
                </button>
            </div>
            
            <div class="stats" id="statsInfo">
                Carregando...
            </div>
        </div>

        <?php
        require_once("../../../conexao/conexao.php");

        // Buscar todos os utilizadores com TODOS os dados
        $select = $conexao->query("SELECT * FROM UTILIZADORES ORDER BY NOME, SOBRENOME");
        $resultado = $select->fetchAll();
        $totalUsers = count($resultado);
        ?>

        <!-- Visualização em Cards -->
        <div class="view-content" id="cardsView">
            <div class="cards-view" id="cardsContainer">
                <?php if($resultado && count($resultado) > 0): ?>
                    <?php foreach ($resultado as $linha):
                        $NIF = $linha["NIF"];
                        $iniciais = substr($linha["NOME"], 0, 1) . substr($linha["SOBRENOME"], 0, 1);
                        $idade = calcularIdade($linha["DATA_NASCIMENTO"]);
                    ?>
                    <div class="user-card" data-user-id="<?php echo $NIF; ?>">
                        <div class="user-header">
                            <div class="user-avatar">
                                <?php echo strtoupper($iniciais); ?>
                            </div>
                            <div class="user-info">
                                <h4><?php echo htmlspecialchars($linha["NOME"] . " " . $linha["SOBRENOME"]); ?></h4>
                                <p><?php echo $idade; ?> anos</p>
                            </div>
                        </div>
                        <div class="user-details">
                            <div class="detail-item">
                                <i class="bi bi-credit-card"></i>
                                <span><?php echo htmlspecialchars($linha["NIF"]); ?></span>
                            </div>
                            <div class="detail-item">
                                <i class="bi bi-calendar"></i>
                                <span><?php echo date('d/m/Y', strtotime($linha["DATA_NASCIMENTO"])); ?></span>
                            </div>
                            <?php if(!empty($linha["EMAIL"])): ?>
                            <div class="detail-item">
                                <i class="bi bi-envelope"></i>
                                <span><?php echo htmlspecialchars($linha["EMAIL"]); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-actions">
                            <button class="btn-action" onclick="event.stopPropagation(); editarUtilizador('<?php echo $NIF; ?>')">
                                <i class="bi bi-pencil"></i>
                                Editar
                            </button>
                            <button class="btn-action" onclick="event.stopPropagation(); excluirUtilizador('<?php echo $NIF; ?>')">
                                <i class="bi bi-trash"></i>
                                Excluir
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state w-100">
                        <i class="bi bi-people empty-icon"></i>
                        <div class="empty-text">Nenhum utilizador encontrado</div>
                        <a href="/views/Admin/Clients/addClients.php" class="btn btn-sm btn-outline-primary mt-2">
                            <i class="bi bi-person-plus me-1"></i>
                            Registar utilizador
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Visualização em Lista -->
        <div class="view-content" id="listView" style="display: none;">
            <div class="list-view" id="listContainer">
                <div class="list-header">
                    <div>Utilizador</div>
                    <div>NIF</div>
                    <div>Nascimento</div>
                    <div>Ações</div>
                </div>
                
                <?php if($resultado && count($resultado) > 0): ?>
                    <?php foreach ($resultado as $linha):
                        $NIF = $linha["NIF"];
                        $iniciais = substr($linha["NOME"], 0, 1) . substr($linha["SOBRENOME"], 0, 1);
                        $idade = calcularIdade($linha["DATA_NASCIMENTO"]);
                    ?>
                    <div class="list-item" data-user-id="<?php echo $NIF; ?>">
                        <div class="list-user">
                            <div class="list-avatar">
                                <?php echo strtoupper($iniciais); ?>
                            </div>
                            <div class="list-user-info">
                                <h5><?php echo htmlspecialchars($linha["NOME"] . " " . $linha["SOBRENOME"]); ?></h5>
                                <p><?php echo $idade; ?> anos</p>
                            </div>
                        </div>
                        <div class="list-detail"><?php echo htmlspecialchars($linha["NIF"]); ?></div>
                        <div class="list-detail"><?php echo date('d/m/Y', strtotime($linha["DATA_NASCIMENTO"])); ?></div>
                        <div class="list-actions">
                            <button class="btn-list-action" onclick="event.stopPropagation(); editarUtilizador('<?php echo $NIF; ?>')">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn-list-action" onclick="event.stopPropagation(); excluirUtilizador('<?php echo $NIF; ?>')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="bi bi-people empty-icon"></i>
                        <div class="empty-text">Nenhum utilizador encontrado</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal de Detalhes do Utilizador - MELHORADO -->
    <div class="modal fade user-modal" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Cabeçalho com gradiente -->
                <div class="modal-header">
                    <div class="modal-header-content">
                        <div class="modal-avatar" id="modalAvatar"></div>
                        <div class="modal-user-info">
                            <h5 class="modal-title" id="modalName"></h5>
                            <p class="modal-subtitle">
                                <i class="bi bi-person"></i>
                                <span id="modalAge"></span>
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
                        <!-- Informações Pessoais -->
                        <div class="info-card">
                            <div class="info-header">
                                <i class="bi bi-person-badge"></i>
                                <h6>Informações Pessoais</h6>
                            </div>
                            <div class="info-content">
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="bi bi-credit-card"></i>
                                        NIF
                                    </div>
                                    <div class="info-value" id="modalNIF"></div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="bi bi-calendar-event"></i>
                                        Data de Nascimento
                                    </div>
                                    <div class="info-value" id="modalBirth"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Informações de Contacto -->
                        <div class="info-card">
                            <div class="info-header">
                                <i class="bi bi-telephone"></i>
                                <h6>Contacto</h6>
                            </div>
                            <div class="info-content">
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="bi bi-envelope"></i>
                                        E-mail
                                    </div>
                                    <div class="info-value" id="modalEmail"></div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="bi bi-phone"></i>
                                        Telefone
                                    </div>
                                    <div class="info-value" id="modalPhone"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Estatísticas (opcional - pode ser preenchido com dados reais se disponíveis) -->
                    <div class="stats-section">
                        <div class="info-header">
                            <i class="bi bi-graph-up"></i>
                            <h6>Estatísticas</h6>
                        </div>
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-number" id="modalLoans">0</div>
                                <div class="stat-label">Empréstimos</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number" id="modalReturns">0</div>
                                <div class="stat-label">Devoluções</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number" id="modalPending">0</div>
                                <div class="stat-label">Pendentes</div>
                            </div>
                         
                        </div>
                    </div>

                    <!-- Ações -->
                    <div class="modal-actions">
                        <button type="button" class="btn-modal btn-modal-danger" id="modalDeleteBtn">
                            <i class="bi bi-trash"></i>
                            Excluir Utilizador
                        </button>
                        <div class="action-buttons">
                            <button type="button" class="btn-modal btn-modal-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x"></i>
                                Fechar
                            </button>
                            <button type="button" class="btn-modal btn-modal-primary" id="modalEditBtn">
                                <i class="bi bi-pencil"></i>
                                Editar Utilizador
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Dados dos utilizadores
        const users = <?php echo json_encode($resultado); ?>;
        
        // Atualizar estatísticas
        document.getElementById('statsInfo').textContent = `${users.length} Utilizador(es)`;
        
        // Controle de visualização
        document.querySelectorAll('.btn-view').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelectorAll('.btn-view').forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                const viewType = this.getAttribute('data-view');
                document.getElementById('cardsView').style.display = viewType === 'cards' ? 'block' : 'none';
                document.getElementById('listView').style.display = viewType === 'list' ? 'block' : 'none';
            });
        });
        
        // Filtragem automática
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const userCards = document.querySelectorAll('.user-card');
            const listItems = document.querySelectorAll('.list-item');
            let visibleCount = 0;
            
            // Filtrar cards
            userCards.forEach(card => {
                const userId = card.getAttribute('data-user-id');
                const user = users.find(u => u.NIF === userId);
                
                if (user) {
                    const searchText = `${user.NOME} ${user.SOBRENOME} ${user.NIF} ${user.EMAIL || ''}`.toLowerCase();
                    
                    if (searchText.includes(searchTerm)) {
                        card.style.display = 'block';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                }
            });
            
            // Filtrar lista
            listItems.forEach(item => {
                const userId = item.getAttribute('data-user-id');
                const user = users.find(u => u.NIF === userId);
                
                if (user) {
                    const searchText = `${user.NOME} ${user.SOBRENOME} ${user.NIF} ${user.EMAIL || ''}`.toLowerCase();
                    
                    if (searchText.includes(searchTerm)) {
                        item.style.display = 'grid';
                    } else {
                        item.style.display = 'none';
                    }
                }
            });
            
            // Atualizar estatísticas
            document.getElementById('statsInfo').textContent = `${visibleCount} utilizador(es)${searchTerm ? ' encontrados' : ''}`;
        });
        
        // Abrir modal ao clicar no card/lista
        document.addEventListener('click', function(e) {
            const card = e.target.closest('.user-card');
            const listItem = e.target.closest('.list-item');
            
            if (card || listItem) {
                const userId = (card || listItem).getAttribute('data-user-id');
                const user = users.find(u => u.NIF === userId);
                
                if (user) {
                    abrirModalUtilizador(user);
                }
            }
        });
        
        // Função para abrir modal com todos os dados
        function abrirModalUtilizador(user) {
            const iniciais = (user.NOME.charAt(0) + user.SOBRENOME.charAt(0)).toUpperCase();
            const idade = calcularIdade(user.DATA_NASCIMENTO);
            
            // Informações básicas
            document.getElementById('modalAvatar').textContent = iniciais;
            document.getElementById('modalName').textContent = `${user.NOME} ${user.SOBRENOME}`;
            document.getElementById('modalAge').textContent = user.DATA_NASCIMENTO ? `${idade} anos` : 'Idade não informada';
            
            // Informações pessoais
            document.getElementById('modalNIF').textContent = user.NIF || 'Não informado';
            document.getElementById('modalNIF').className = user.NIF ? 'info-value' : 'info-value empty';
            
            document.getElementById('modalBirth').textContent = user.DATA_NASCIMENTO ? 
                new Date(user.DATA_NASCIMENTO).toLocaleDateString('pt-BR') : 'Não informado';
            document.getElementById('modalBirth').className = user.DATA_NASCIMENTO ? 'info-value' : 'info-value empty';
            
            // Informações de contacto
            document.getElementById('modalEmail').textContent = user.EMAIL || 'Não informado';
            document.getElementById('modalEmail').className = user.EMAIL ? 'info-value' : 'info-value empty';
            
            document.getElementById('modalPhone').textContent = user.TELEFONE || 'Não informado';
            document.getElementById('modalPhone').className = user.TELEFONE ? 'info-value' : 'info-value empty';
            
            // Estatísticas (exemplo - pode ser conectado a dados reais)
            // Aqui você pode fazer uma requisição AJAX para buscar estatísticas reais do usuário
            document.getElementById('modalLoans').textContent = Math.floor(Math.random() * 20);
            document.getElementById('modalReturns').textContent = Math.floor(Math.random() * 15);
            document.getElementById('modalPending').textContent = Math.floor(Math.random() * 5);
            
            
            // Configurar botões de ação
            document.getElementById('modalEditBtn').onclick = function() {
                editarUtilizador(user.NIF);
            };
            
            document.getElementById('modalDeleteBtn').onclick = function() {
                excluirUtilizador(user.NIF);
            };
            
            const modal = new bootstrap.Modal(document.getElementById('userModal'));
            modal.show();
        }
        
        // Funções de ação
        function editarUtilizador(NIF) {
            window.location.href = `/views/Admin/Clients/editClients.php?NIF=${NIF}`;
        }
        
        function excluirUtilizador(NIF) {
            if (confirm('Tem certeza que deseja excluir este utilizador?')) {
                window.location.href = `/views/Admin/Clients/delClients.php?NIF=${NIF}`;
            }
        }
        
        // Função para calcular idade
        function calcularIdade(dataNascimento) {
            if (!dataNascimento) return 0;
            
            const hoje = new Date();
            const nascimento = new Date(dataNascimento);
            let idade = hoje.getFullYear() - nascimento.getFullYear();
            const mes = hoje.getMonth() - nascimento.getMonth();
            
            if (mes < 0 || (mes === 0 && hoje.getDate() < nascimento.getDate())) {
                idade--;
            }
            
            return idade;
        }
    </script>
</body>
</html>

<?php 
// Função para calcular idade
function calcularIdade($dataNascimento) {
    if(empty($dataNascimento)) return 'N/A';
    
    $hoje = new DateTime();
    $nascimento = new DateTime($dataNascimento);
    $idade = $hoje->diff($nascimento);
    return $idade->y;
}

include('../../layout/footer.html'); 
?>