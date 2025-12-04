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
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utilizadores - Sistema de Biblioteca</title>
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
            --error-color: #e74c3c;
        }
        
        body {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-color);
            padding: 11px;
        }
        
        .library-container {
            max-width: 1320px;
            margin: 0 auto;
            padding: 11px;
        }
        
        .library-header {
            margin-bottom: 1.65rem;
        }
        
        .library-title {
            font-size: 1.65rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.33rem;
        }
        
        .library-subtitle {
            color: #7f8c8d;
            font-size: 0.99rem;
            margin-bottom: 1.1rem;
        }
        
        .form-wrapper {
            background: var(--white);
            border-radius: 8.8px;
            box-shadow: 0 2.2px 6.6px rgba(0,0,0,0.088);
            border: 1px solid var(--border-color);
            overflow: hidden;
            margin-bottom: 1.65rem;
        }
        
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 0.88rem;
            margin-bottom: 1.65rem;
        }
        
        .stat-card {
            background: var(--white);
            border-radius: 8.8px;
            padding: 1.32rem;
            box-shadow: 0 2.2px 6.6px rgba(0,0,0,0.088);
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
            text-align: center;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4.4px 13.2px rgba(0,0,0,0.132);
        }
        
        .stat-number {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.44rem;
            line-height: 1;
        }
        
        .stat-label {
            font-size: 0.88rem;
            color: #7f8c8d;
            font-weight: 500;
        }
        
        .search-container {
            position: relative;
        }
        
        .search-input {
            width: 100%;
            padding: 0.55rem 1.32rem 0.55rem 2.64rem;
            font-size: 0.88rem;
            border: 1px solid var(--border-color);
            border-radius: 4.4px;
            background: var(--white);
            transition: all 0.2s ease;
        }
        
        .search-input:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 2.2px rgba(52, 152, 219, 0.165);
        }
        
        .search-icon {
            position: absolute;
            left: 0.88rem;
            top: 50%;
            transform: translateY(-50%);
            color: #7f8c8d;
            font-size: 0.88rem;
        }
        
        .view-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.1rem;
            padding: 0.88rem;
            background: var(--white);
            border-radius: 8.8px;
            border: 1px solid var(--border-color);
        }
        
        .view-buttons {
            display: flex;
            gap: 0.44rem;
        }
        
        .btn-view {
            padding: 0.44rem 0.88rem;
            border: 1px solid var(--border-color);
            background: var(--light-bg);
            color: #7f8c8d;
            border-radius: 4.4px;
            font-size: 0.825rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.33rem;
            transition: all 0.2s ease;
        }
        
        .btn-view.active {
            background: var(--primary-color);
            color: var(--white);
            border-color: var(--primary-color);
        }
        
        .view-stats {
            font-size: 0.825rem;
            color: #7f8c8d;
            font-weight: 500;
        }
        
        /* Cards View */
        .cards-view {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1.1rem;
        }
        
        .user-card {
            background: var(--white);
            border-radius: 8.8px;
            box-shadow: 0 2.2px 6.6px rgba(0,0,0,0.088);
            border: 1px solid var(--border-color);
            overflow: hidden;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .user-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4.4px 13.2px rgba(0,0,0,0.132);
            border-color: var(--accent-color);
        }
        
        .user-header {
            padding: 1.32rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 0.88rem;
        }
        
        .user-avatar {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-weight: 600;
            font-size: 1.1rem;
            flex-shrink: 0;
        }
        
        .user-info h4 {
            margin: 0;
            font-size: 1rem;
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .user-info p {
            margin: 0.22rem 0 0 0;
            font-size: 0.825rem;
            color: #7f8c8d;
        }
        
        .user-details {
            padding: 1.32rem;
        }
        
        .detail-item {
            display: flex;
            align-items: center;
            gap: 0.66rem;
            margin-bottom: 0.77rem;
            font-size: 0.825rem;
            color: var(--text-color);
        }
        
        .detail-item i {
            color: var(--accent-color);
            width: 16px;
            font-size: 0.825rem;
        }
        
        .card-actions {
            padding: 0.88rem 1.32rem;
            border-top: 1px solid var(--border-color);
            display: flex;
            gap: 0.44rem;
            background: var(--light-bg);
        }
        
        /* Botões minimalistas */
        .btn-minimal {
            flex: 1;
            padding: 0.44rem 0.66rem;
            border: 1px solid var(--border-color);
            background: var(--white);
            color: #7f8c8d;
            border-radius: 4.4px;
            font-size: 0.8rem;
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.33rem;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        
        .btn-minimal:hover {
            background: var(--light-bg);
            color: var(--primary-color);
            border-color: var(--accent-color);
        }
        
        .btn-minimal-edit:hover {
            color: var(--success-color);
            border-color: var(--success-color);
        }
        
        .btn-minimal-delete:hover {
            color: var(--error-color);
            border-color: var(--error-color);
        }
        
        /* List View */
        .list-view {
            background: var(--white);
            border-radius: 8.8px;
            box-shadow: 0 2.2px 6.6px rgba(0,0,0,0.088);
            border: 1px solid var(--border-color);
            overflow: hidden;
        }
        
        .list-header {
            background: var(--light-bg);
            padding: 0.88rem 1.32rem;
            border-bottom: 1px solid var(--border-color);
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr 120px;
            gap: 0.88rem;
            font-size: 0.825rem;
            font-weight: 600;
            color: var(--text-color);
            align-items: center;
        }
        
        .list-header div {
            text-align: center;
            padding: 0.44rem;
        }
        
        .list-header div:first-child {
            text-align: left;
        }
        
        .list-item {
            padding: 0.88rem 1.32rem;
            border-bottom: 1px solid var(--border-color);
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr 120px;
            gap: 0.88rem;
            align-items: center;
            transition: background-color 0.2s ease;
            cursor: pointer;
        }
        
        .list-item:hover {
            background-color: var(--light-bg);
        }
        
        .list-item:last-child {
            border-bottom: none;
        }
        
        .list-user {
            display: flex;
            align-items: center;
            gap: 0.88rem;
            text-align: left;
        }
        
        .list-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: 0.825rem;
            font-weight: 600;
            flex-shrink: 0;
        }
        
        .list-user-info h5 {
            margin: 0;
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .list-user-info p {
            margin: 0.11rem 0 0 0;
            font-size: 0.77rem;
            color: #7f8c8d;
        }
        
        .list-detail {
            font-size: 0.825rem;
            color: var(--text-color);
            text-align: center;
            padding: 0.44rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .list-actions {
            display: flex;
            gap: 0.44rem;
            justify-content: center;
            align-items: center;
            height: 100%;
        }
        
        /* Estado vazio */
        .empty-state {
            text-align: center;
            padding: 3.3rem 1.1rem;
            color: #7f8c8d;
            grid-column: 1 / -1;
        }
        
        .empty-icon {
            font-size: 2.64rem;
            margin-bottom: 0.88rem;
            opacity: 0.4;
        }
        
        .empty-text {
            font-size: 0.88rem;
            margin-bottom: 0.55rem;
        }
        
        /* Botão flutuante */
        .fab-button {
            position: fixed;
            bottom: 1.65rem;
            right: 1.65rem;
            width: 66px;
            height: 66px;
            background: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: 1.32rem;
            box-shadow: 0 4.4px 13.2px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
            z-index: 1000;
            text-decoration: none;
        }
        
        .fab-button:hover {
            transform: scale(1.05);
            background: var(--accent-color);
            color: var(--white);
            box-shadow: 0 6.6px 17.6px rgba(0,0,0,0.2);
        }
        
        /* Modal */
        .user-modal .modal-content {
            background: var(--white);
            border: none;
            border-radius: 8.8px;
            box-shadow: 0 4.4px 22px rgba(0,0,0,0.1);
            overflow: hidden;
            border: 1px solid var(--border-color);
        }
        
        .user-modal .modal-header {
            border: none;
            padding: 0;
            position: relative;
        }
        
        .user-modal .modal-body {
            padding: 0;
            display: flex;
            min-height: 350px;
        }
        
        .modal-cover-section {
            flex: 0 0 250px;
            background: var(--light-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.65rem;
            border-right: 1px solid var(--border-color);
        }
        
        .modal-info-section {
            flex: 1;
            padding: 1.65rem;
            display: flex;
            flex-direction: column;
        }
        
        .modal-user-avatar {
            width: 132px;
            height: 132px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 4.4px 13.2px rgba(0,0,0,0.1);
            border: 2.2px solid var(--accent-color);
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: 2.2rem;
            font-weight: 600;
        }
        
        .modal-title {
            font-size: 1.32rem;
            color: var(--primary-color);
            margin-bottom: 0.22rem;
            line-height: 1.3;
            font-weight: 600;
        }
        
        .modal-subtitle {
            font-size: 0.88rem;
            color: #7f8c8d;
            margin-bottom: 1.32rem;
        }
        
        .user-details-minimal {
            display: flex;
            flex-direction: column;
            gap: 0.66rem;
            margin-bottom: 1.32rem;
        }
        
        .detail-row {
            display: flex;
            align-items: center;
            gap: 0.88rem;
            padding: 0.44rem 0;
            border-bottom: 1px solid var(--border-color);
        }
        
        .detail-label {
            font-size: 0.8rem;
            color: #7f8c8d;
            font-weight: 500;
            min-width: 110px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .detail-value {
            font-size: 0.825rem;
            color: var(--text-color);
            flex: 1;
        }
        
        .user-stats-minimal {
            margin-top: auto;
        }
        
        .user-stats-minimal h6 {
            color: var(--accent-color);
            font-size: 0.825rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.66rem;
            font-weight: 600;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.66rem;
        }
        
        .stat-item {
            text-align: center;
            padding: 0.66rem;
            background: var(--light-bg);
            border-radius: 4.4px;
            border: 1px solid var(--border-color);
        }
        
        .stat-number {
            font-size: 1rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.22rem;
        }
        
        .modal-actions-minimal {
            display: flex;
            gap: 0.66rem;
            margin-top: 1.32rem;
            padding-top: 1.32rem;
            border-top: 1px solid var(--border-color);
        }
        
        .btn-modal-minimal {
            padding: 0.44rem 0.88rem;
            border-radius: 4.4px;
            font-weight: 500;
            font-size: 0.8rem;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.33rem;
            border: 1px solid var(--border-color);
            background: var(--light-bg);
            color: #7f8c8d;
        }
        
        .btn-modal-minimal:hover {
            background: var(--white);
            color: var(--primary-color);
            border-color: var(--accent-color);
        }
        
        .btn-close-custom {
            position: absolute;
            top: 0.88rem;
            right: 0.88rem;
            z-index: 10;
            background: var(--white);
            border-radius: 4.4px;
            width: 33px;
            height: 33px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--border-color);
            font-size: 0.88rem;
            color: #7f8c8d;
            transition: all 0.2s ease;
        }
        
        .btn-close-custom:hover {
            background: var(--light-bg);
            color: var(--primary-color);
        }
        
        @media (max-width: 768px) {
            .library-container {
                padding: 5.5px;
                max-width: 95%;
            }
            
            .stats-container {
                grid-template-columns: 1fr;
            }
            
            .cards-view {
                grid-template-columns: 1fr;
            }
            
            .list-header, .list-item {
                grid-template-columns: 1fr;
                gap: 0.66rem;
                text-align: left;
            }
            
            .list-header div, .list-detail {
                text-align: left;
                justify-content: flex-start;
            }
            
            .list-actions {
                justify-content: flex-start;
                margin-top: 0.44rem;
            }
            
            .user-modal .modal-body {
                flex-direction: column;
                min-height: auto;
            }
            
            .modal-cover-section {
                flex: none;
                border-right: none;
                border-bottom: 1px solid var(--border-color);
                padding: 1.32rem;
            }
            
            .modal-user-avatar {
                width: 110px;
                height: 110px;
                font-size: 1.65rem;
            }
            
            .modal-info-section {
                padding: 1.32rem;
            }
            
            .modal-title {
                font-size: 1.1rem;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 480px) {
            .view-controls {
                flex-direction: column;
                gap: 0.66rem;
                align-items: flex-start;
            }
            
            .detail-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.22rem;
            }
            
            .detail-label {
                min-width: auto;
            }
            
            .modal-actions-minimal {
                flex-direction: column;
            }
            
            .fab-button {
                bottom: 1.1rem;
                right: 1.1rem;
                width: 55px;
                height: 55px;
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body>
    <div class="library-container">
        <div class="library-header">
            <h1 class="library-title">Gestão de Utilizadores</h1>
            <p class="library-subtitle">Gerencie todos os utilizadores do sistema da biblioteca</p>
        </div>
        
        <?php
        require_once("../../../conexao/conexao.php");
        $select = $conexao->query("SELECT * FROM UTILIZADORES ORDER BY NOME, SOBRENOME");
        $resultado = $select->fetchAll();
        $totalUsers = count($resultado);
        ?>
        
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-number"><?php echo $totalUsers; ?></div>
                <div class="stat-label">Total de Utilizadores</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $totalUsers; ?></div>
                <div class="stat-label">Utilizadores Ativos</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">0</div>
                <div class="stat-label">Com Empréstimos</div>
            </div>
        </div>
        
        <div class="form-wrapper">
            <div class="search-container">
                <i class="bi bi-search search-icon"></i>
                <input type="text" class="search-input" id="searchInput" 
                       placeholder="Pesquisar utilizadores por nome, NIF ou email...">
            </div>
        </div>

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
            
            <div class="view-stats" id="statsInfo">
                <?php echo $totalUsers; ?> Utilizador(es)
            </div>
        </div>

        <div class="view-content" id="cardsView">
            <div class="cards-view" id="cardsContainer">
                <?php if($resultado && count($resultado) > 0): ?>
                    <?php foreach ($resultado as $linha):
                        $NIF = $linha["NIF"];
                        $iniciais = substr($linha["NOME"], 0, 1) . substr($linha["SOBRENOME"], 0, 1);
                        $idade = calcularIdade($linha["DATA_NASCIMENTO"]);
                    ?>
                    <div class="user-card" data-user-id="<?php echo $NIF; ?>" 
                         data-search-text="<?php echo htmlspecialchars(strtolower($linha["NOME"] . ' ' . $linha["SOBRENOME"] . ' ' . $linha["NIF"] . ' ' . ($linha["EMAIL"] ?? ''))); ?>">
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
                            <a href="/views/Admin/Clients/editClients.php?NIF=<?php echo $NIF; ?>" class="btn-minimal btn-minimal-edit" onclick="event.stopPropagation()">
                                <i class="bi bi-pencil"></i>
                                Editar
                            </a>
                            <a href="/views/Admin/Clients/delClients.php?NIF=<?php echo $NIF; ?>" class="btn-minimal btn-minimal-delete" 
                               onclick="event.stopPropagation(); return confirm('Tem certeza que deseja excluir este utilizador?')">
                                <i class="bi bi-trash"></i>
                                Excluir
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="bi bi-people empty-icon"></i>
                        <div class="empty-text">Nenhum utilizador encontrado</div>
                        <a href="/views/Admin/Clients/addClients.php" class="btn-minimal" style="margin-top: 0.55rem;">
                            <i class="bi bi-person-plus"></i>
                            Registar utilizador
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="view-content" id="listView" style="display: none;">
            <div class="list-view" id="listContainer">
                <div class="list-header">
                    <div>Utilizador</div>
                    <div>NIF</div>
                    <div>Idade</div>
                    <div>Nascimento</div>
                    <div>Ações</div>
                </div>
                
                <?php if($resultado && count($resultado) > 0): ?>
                    <?php foreach ($resultado as $linha):
                        $NIF = $linha["NIF"];
                        $iniciais = substr($linha["NOME"], 0, 1) . substr($linha["SOBRENOME"], 0, 1);
                        $idade = calcularIdade($linha["DATA_NASCIMENTO"]);
                    ?>
                    <div class="list-item" data-user-id="<?php echo $NIF; ?>"
                         data-search-text="<?php echo htmlspecialchars(strtolower($linha["NOME"] . ' ' . $linha["SOBRENOME"] . ' ' . $linha["NIF"] . ' ' . ($linha["EMAIL"] ?? ''))); ?>">
                        <div class="list-user">
                            <div class="list-avatar">
                                <?php echo strtoupper($iniciais); ?>
                            </div>
                            <div class="list-user-info">
                                <h5><?php echo htmlspecialchars($linha["NOME"] . " " . $linha["SOBRENOME"]); ?></h5>
                                <p><?php echo htmlspecialchars($linha["EMAIL"] ?? ''); ?></p>
                            </div>
                        </div>
                        <div class="list-detail"><?php echo htmlspecialchars($linha["NIF"]); ?></div>
                        <div class="list-detail"><?php echo $idade; ?> anos</div>
                        <div class="list-detail"><?php echo date('d/m/Y', strtotime($linha["DATA_NASCIMENTO"])); ?></div>
                        <div class="list-actions">
                            <a href="/views/Admin/Clients/editClients.php?NIF=<?php echo $NIF; ?>" class="btn-minimal" onclick="event.stopPropagation()">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="/views/Admin/Clients/delClients.php?NIF=<?php echo $NIF; ?>" class="btn-minimal" 
                               onclick="event.stopPropagation(); return confirm('Tem certeza que deseja excluir este utilizador?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state" style="padding: 1.65rem;">
                        <i class="bi bi-people empty-icon"></i>
                        <div class="empty-text">Nenhum utilizador encontrado</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <a href="/views/Admin/Clients/addClients.php" class="fab-button">
        <i class="bi bi-plus-lg"></i>
    </a>

    <div class="modal fade user-modal" id="userModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <button type="button" class="btn-close-custom" data-bs-dismiss="modal">
                    <i class="bi bi-x"></i>
                </button>
                <div class="modal-body">
                    <div class="modal-cover-section">
                        <div class="modal-user-avatar" id="modalAvatar"></div>
                    </div>
                    <div class="modal-info-section">
                        <h4 class="modal-title" id="modalName"></h4>
                        <p class="modal-subtitle" id="modalAge"></p>
                        
                        <div class="user-details-minimal">
                            <div class="detail-row">
                                <span class="detail-label">NIF</span>
                                <span class="detail-value" id="modalNIF"></span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Data Nascimento</span>
                                <span class="detail-value" id="modalBirth"></span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Email</span>
                                <span class="detail-value" id="modalEmail"></span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Telefone</span>
                                <span class="detail-value" id="modalPhone"></span>
                            </div>
                        </div>
                        
                        <div class="user-stats-minimal">
                            <h6>Estatísticas</h6>
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
                        
                        <div class="modal-actions-minimal">
                            <a href="#" class="btn-modal-minimal" id="modalEditLink">
                                <i class="bi bi-pencil"></i> Editar
                            </a>
                            <a href="#" class="btn-modal-minimal" id="modalDeleteLink">
                                <i class="bi bi-trash"></i> Excluir
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Controle de visualização
        document.querySelectorAll('.btn-view').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelectorAll('.btn-view').forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                const viewType = this.getAttribute('data-view');
                document.getElementById('cardsView').style.display = viewType === 'cards' ? 'block' : 'none';
                document.getElementById('listView').style.display = viewType === 'list' ? 'block' : 'none';
                
                // Aplicar filtro atual quando mudar de visualização
                applySearchFilter();
            });
        });
        
        // Elementos do DOM
        const searchInput = document.getElementById('searchInput');
        const statsInfo = document.getElementById('statsInfo');
        
        // Filtragem automática - Corrigido
        searchInput.addEventListener('input', function() {
            applySearchFilter();
        });
        
        function applySearchFilter() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            const userCards = document.querySelectorAll('.user-card');
            const listItems = document.querySelectorAll('.list-item');
            let visibleCount = 0;
            
            // Filtrar cards
            userCards.forEach(card => {
                const searchText = card.getAttribute('data-search-text') || '';
                
                if (searchText.includes(searchTerm)) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Filtrar lista
            listItems.forEach(item => {
                const searchText = item.getAttribute('data-search-text') || '';
                
                if (searchText.includes(searchTerm)) {
                    item.style.display = 'grid';
                } else {
                    item.style.display = 'none';
                }
            });
            
            // Atualizar estatísticas
            if (searchTerm === '') {
                statsInfo.textContent = '<?php echo $totalUsers; ?> Utilizador(es)';
            } else {
                statsInfo.textContent = `${visibleCount} Utilizador(es) encontrado(s)`;
            }
            
            // Mostrar mensagem de "nenhum resultado" se necessário
            showNoResultsMessage(visibleCount);
        }
        
        function showNoResultsMessage(visibleCount) {
            const searchTerm = searchInput.value.toLowerCase().trim();
            const cardsContainer = document.getElementById('cardsContainer');
            const listContainer = document.getElementById('listContainer');
            
            if (visibleCount === 0 && searchTerm !== '') {
                // Verificar se já existe uma mensagem de "nenhum resultado"
                let noResultsMessage = cardsContainer.querySelector('.no-results-message');
                
                if (!noResultsMessage) {
                    noResultsMessage = document.createElement('div');
                    noResultsMessage.className = 'empty-state no-results-message';
                    noResultsMessage.innerHTML = `
                        <i class="bi bi-search empty-icon"></i>
                        <div class="empty-text">Nenhum utilizador encontrado para "${searchTerm}"</div>
                    `;
                    noResultsMessage.style.gridColumn = '1 / -1';
                    cardsContainer.appendChild(noResultsMessage);
                    
                    // Também adicionar à lista
                    const listNoResults = noResultsMessage.cloneNode(true);
                    listNoResults.className = 'empty-state no-results-message';
                    listContainer.appendChild(listNoResults);
                }
            } else {
                // Remover mensagens de "nenhum resultado"
                const noResultsMessages = document.querySelectorAll('.no-results-message');
                noResultsMessages.forEach(msg => msg.remove());
            }
        }
        
        // Abrir modal ao clicar no card/lista
        document.addEventListener('click', function(e) {
            const card = e.target.closest('.user-card');
            const listItem = e.target.closest('.list-item');
            
            if (card || listItem) {
                const userId = (card || listItem).getAttribute('data-user-id');
                
                // Encontrar usuário nos dados PHP (simplificado - em produção, faria uma requisição AJAX)
                const userData = <?php echo json_encode($resultado); ?>;
                const user = userData.find(u => u.NIF === userId);
                
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
            
            // Informações detalhadas
            document.getElementById('modalNIF').textContent = user.NIF || 'Não informado';
            document.getElementById('modalBirth').textContent = user.DATA_NASCIMENTO ? 
                formatDate(user.DATA_NASCIMENTO) : 'Não informado';
            document.getElementById('modalEmail').textContent = user.EMAIL || 'Não informado';
            document.getElementById('modalPhone').textContent = user.TELEFONE || 'Não informado';
            
            // Estatísticas (exemplo)
            document.getElementById('modalLoans').textContent = Math.floor(Math.random() * 20);
            document.getElementById('modalReturns').textContent = Math.floor(Math.random() * 15);
            document.getElementById('modalPending').textContent = Math.floor(Math.random() * 5);
            
            // Configurar links de ações
            document.getElementById('modalEditLink').href = `/views/Admin/Clients/editClients.php?NIF=${user.NIF}`;
            document.getElementById('modalDeleteLink').href = `/views/Admin/Clients/delClients.php?NIF=${user.NIF}`;
            
            // Configurar evento de confirmação para exclusão
            const deleteLink = document.getElementById('modalDeleteLink');
            const originalClick = deleteLink.onclick;
            deleteLink.onclick = function(e) {
                if (!confirm('Tem certeza que deseja excluir este utilizador?')) {
                    e.preventDefault();
                }
            };
            
            const modal = new bootstrap.Modal(document.getElementById('userModal'));
            modal.show();
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
        
        // Função para formatar data
        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('pt-PT');
        }
        
        // Inicializar filtro
        applySearchFilter();
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

include('../../../layout/footer.html'); 
?>