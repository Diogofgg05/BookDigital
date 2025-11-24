<?php
# Impede que usuários acessem a página se não estiverem logados
include('../../../seguranca/seguranca.php');
session_start();
if(administrador_logado() == false) {
    header("location: /index.php"); 
    exit;
}

require_once("../../../conexao/conexao.php");

if(!filter_input(INPUT_GET, "ISBN", FILTER_SANITIZE_FULL_SPECIAL_CHARS)) {
    echo "ISBN é inválido!";
} else {
    $ISBN = filter_input(INPUT_GET, "ISBN", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $consulta = $conexao->query("SELECT * FROM LIVROS WHERE ISBN='$ISBN'");
    $linha = $consulta->fetch(PDO::FETCH_ASSOC);
}

include('../../../layout/header.html');
include('../../../layout/navbar.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Livro - Digiteca</title>
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <style>
    :root {
        --primary-color: #4a5568;
        --secondary-color: #718096;
        --accent-color: #2d3748;
        --light-bg: #f7fafc;
        --card-bg: #ffffff;
        --border-color: #e2e8f0;
        --text-primary: #2d3748;
        --text-secondary: #4a5568;
        --success-color: #38a169;
        --warning-color: #d69e2e;
        --error-color: #e53e3e;
        --info-color: #3182ce;
        --progress-width: 0%;
    }
    
    body {
        background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        padding: 8px;
        color: var(--text-primary);
        margin: 0;
        padding-top: 30px;
        min-height: 100vh;
    }
    
    .page-container {
        max-width: 98%;
        margin: 0 auto;
        padding-top: 20px;
    }
    
    .header-card {
        background: var(--card-bg);
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border-color);
        margin-bottom: 20px;
        margin-top: 15px;
        overflow: hidden;
        border-left: 4px solid var(--primary-color);
    }
    
    .header-content {
        padding: 1.5rem !important;
    }
    
    .header-title {
        font-size: 1.4rem;
        margin-bottom: 0.3rem;
        color: var(--text-primary);
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .header-subtitle {
        color: var(--secondary-color);
        font-size: 0.9rem;
        margin: 0;
    }
    
    .form-card {
        background-color: var(--card-bg);
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        padding: 20px;
        border: 1px solid var(--border-color);
        position: relative;
        overflow: hidden;
        margin-top: 20px;
    }
    
    .form-container {
        position: relative;
        z-index: 1;
    }
    
    .floating-icon {
        position: absolute;
        font-size: 3rem;
        opacity: 0.03;
        z-index: 0;
        color: var(--primary-color);
        transition: all 0.3s ease;
    }
    
    .icon-book-1 {
        top: 5%;
        left: 3%;
    }
    
    .icon-book-2 {
        top: 60%;
        right: 3%;
    }
    
    .icon-book-3 {
        bottom: 5%;
        left: 40%;
    }

    /* PROGRESS BAR HORIZONTAL */
    .progress-container {
        margin-bottom: 40px;
        position: relative;
    }

    .progress-bar-horizontal {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin-bottom: 20px;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
    }

    .progress-bar-horizontal::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--border-color);
        transform: translateY(-50%);
        z-index: 1;
        border-radius: 2px;
    }

    .progress-bar-horizontal::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        height: 4px;
        background: var(--primary-color);
        transform: translateY(-50%);
        transition: width 0.4s ease;
        z-index: 2;
        border-radius: 2px;
        width: var(--progress-width, 0%);
    }

    .progress-step-horizontal {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 3;
    }

    .step-circle-horizontal {
        width: 50px;
        height: 50px;
        background: var(--card-bg);
        border: 3px solid var(--border-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: var(--secondary-color);
        position: relative;
        transition: all 0.3s ease;
        cursor: pointer;
        margin-bottom: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .step-circle-horizontal.active {
        border-color: var(--primary-color);
        background: var(--primary-color);
        color: white;
        transform: scale(1.1);
        box-shadow: 0 4px 8px rgba(74, 85, 104, 0.3);
    }

    .step-circle-horizontal.completed {
        border-color: var(--success-color);
        background: var(--success-color);
        color: white;
        transform: scale(1.05);
    }

    .step-label-horizontal {
        font-size: 0.85rem;
        color: var(--secondary-color);
        font-weight: 600;
        text-align: center;
        white-space: nowrap;
        margin-top: 5px;
    }

    .step-circle-horizontal.active .step-label-horizontal {
        color: var(--primary-color);
        font-weight: 700;
    }

    .step-circle-horizontal.completed .step-label-horizontal {
        color: var(--success-color);
    }

    .step-icon {
        font-size: 1.2rem;
    }

    /* Step Forms */
    .step-form {
        display: none;
        animation: fadeIn 0.5s ease;
    }

    .step-form.active {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .form-section {
        margin-bottom: 20px;
        padding: 20px;
        border-radius: 8px;
        background: linear-gradient(135deg, #f7fafc 0%, #ffffff 100%);
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
    }
    
    .form-section:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }
    
    .section-title {
        color: var(--primary-color);
        font-size: 1.1rem;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--border-color);
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .section-title i {
        color: var(--primary-color);
    }
    
    .form-label {
        font-weight: 600;
        margin-bottom: 6px;
        color: var(--text-secondary);
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .required-field::after {
        content: " *";
        color: var(--error-color);
    }
    
    .form-control {
        border-radius: 6px;
        border: 1px solid var(--border-color);
        padding: 8px 12px;
        font-size: 0.9rem;
        height: 40px;
        transition: all 0.3s ease;
        background: var(--card-bg);
        width: 100%;
    }
    
    .input-with-icon {
        position: relative;
    }
    
    .input-with-icon .form-control {
        padding-left: 40px;
    }
    
    .input-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--secondary-color);
        font-size: 1rem;
    }
    
    textarea.form-control {
        height: auto;
        min-height: 80px;
        resize: vertical;
    }
    
    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(74, 85, 104, 0.1);
        outline: none;
        transform: translateY(-1px);
    }
    
    .form-text-custom {
        font-size: 0.8rem;
        color: var(--secondary-color);
        margin-top: 4px;
        line-height: 1.4;
    }
    
    .btn-group-custom {
        display: flex;
        justify-content: space-between;
        margin-top: 25px;
        padding-top: 20px;
        border-top: 1px solid var(--border-color);
        gap: 12px;
    }
    
    .btn-custom {
        padding: 10px 24px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        height: 44px;
        border: 1px solid;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        text-decoration: none;
        cursor: pointer;
        min-width: 140px;
        justify-content: center;
    }
    
    .btn-primary-custom {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
        border-color: var(--primary-color);
        color: white;
    }
    
    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(74, 85, 104, 0.3);
    }
    
    .btn-secondary-custom {
        background-color: var(--card-bg);
        border-color: var(--border-color);
        color: var(--text-secondary);
    }
    
    .btn-secondary-custom:hover {
        background-color: var(--light-bg);
        border-color: var(--secondary-color);
        transform: translateY(-1px);
    }
    
    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none !important;
    }
    
    .alert-custom {
        padding: 12px 16px;
        border-radius: 6px;
        font-size: 0.9rem;
        margin-bottom: 20px;
        border: 1px solid;
        margin-top: 15px;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .alert-danger {
        background-color: #fed7d7;
        border-color: #feb2b2;
        color: #c53030;
    }
    
    .animate-form {
        animation: fadeInUp 0.6s ease-out;
    }
    
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
    
    /* Layout vertical otimizado */
    .form-group {
        margin-bottom: 16px;
    }
    
    /* Estados de validação */
    .is-valid {
        border-color: var(--success-color) !important;
        background: #f0fff4 !important;
    }
    
    .is-invalid {
        border-color: var(--error-color) !important;
        background: #fff5f5 !important;
    }
    
    /* Review section vertical */
    .review-section {
        margin-bottom: 20px;
    }
    
    .review-section h4 {
        color: var(--primary-color);
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .review-section p {
        margin-bottom: 8px;
        padding-left: 10px;
    }
    
    /* Responsividade */
    @media (max-width: 768px) {
        .btn-group-custom {
            flex-direction: column;
        }
        
        .btn-custom {
            width: 100%;
            justify-content: center;
        }
        
        .floating-icon {
            display: none;
        }
        
        .page-container {
            max-width: 100%;
        }
        
        body {
            padding: 5px;
            padding-top: 20px;
        }
        
        .page-container {
            padding-top: 15px;
        }
        
        .form-card {
            margin-top: 15px;
            padding: 15px;
        }
        
        .form-section {
            padding: 15px;
        }

        .step-circle-horizontal {
            width: 40px;
            height: 40px;
            font-size: 0.9rem;
        }

        .step-label-horizontal {
            font-size: 0.75rem;
        }

        .step-icon {
            font-size: 1rem;
        }

        .progress-bar-horizontal {
            max-width: 100%;
            padding: 0 10px;
        }
    }
    
    @media (min-width: 1200px) {
        .page-container {
            max-width: 900px;
        }
        
        body {
            padding-top: 40px;
        }
        
        .page-container {
            padding-top: 25px;
        }
    }
</style>
</head>
<body>
    <div class="page-container">
      

        <!-- Mensagens de Erro -->
        <?php if(isset($_GET["mensagem_erro"])): ?>
            <div class="alert-custom alert-danger animate-form">
                <i class="bi bi-exclamation-triangle"></i> 
                Erro ao tentar executar atualização: <?php echo htmlspecialchars($_GET["mensagem_erro"]); ?>
            </div>
        <?php endif; ?>

        <!-- Formulário Multi-Step -->
        <div class="form-card animate-form">
            <div class="form-container">
                <!-- Ícones flutuantes -->
                <i class="bi bi-book floating-icon icon-book-1"></i>
                <i class="bi bi-journal floating-icon icon-book-2"></i>
                <i class="bi bi-journal-text floating-icon icon-book-3"></i>
                
                <!-- PROGRESS BAR HORIZONTAL -->
                <div class="progress-container">
                    <div class="progress-bar-horizontal">
                        <div class="progress-step-horizontal" data-step="1">
                            <div class="step-circle-horizontal" id="stepCircle1">
                                <i class="bi bi-journal-text step-icon"></i>
                            </div>
                            <div class="step-label-horizontal">Informações<br>Básicas</div>
                        </div>
                        <div class="progress-step-horizontal" data-step="2">
                            <div class="step-circle-horizontal" id="stepCircle2">
                                <i class="bi bi-person step-icon"></i>
                            </div>
                            <div class="step-label-horizontal">Autor e<br>Editora</div>
                        </div>
                        <div class="progress-step-horizontal" data-step="3">
                            <div class="step-circle-horizontal" id="stepCircle3">
                                <i class="bi bi-grid step-icon"></i>
                            </div>
                            <div class="step-label-horizontal">Categorias e<br>Estoque</div>
                        </div>
                        <div class="progress-step-horizontal" data-step="4">
                            <div class="step-circle-horizontal" id="stepCircle4">
                                <i class="bi bi-check-circle step-icon"></i>
                            </div>
                            <div class="step-label-horizontal">Confirmação</div>
                        </div>
                    </div>
                </div>

                <form action="/Api/livros/editar.php" method="post" id="bookForm">
                    <input type="hidden" name="ISBN" value="<?php echo htmlspecialchars($ISBN); ?>">
                    
                    <!-- STEP 1: Informações Básicas -->
                    <div class="step-form active" id="step1">
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="bi bi-journal-text"></i> Informações Básicas
                            </h3>
                            
                            <!-- Título -->
                            <div class="form-group">
                                <label class="form-label required-field">
                                    <i class="bi bi-bookmark"></i> Título
                                </label>
                                <div class="input-with-icon">
                                    <i class="bi bi-type input-icon"></i>
                                    <input type="text" class="form-control" name="tituloDoLivro" id="tituloDoLivro"
                                           value="<?php echo htmlspecialchars($linha["TITULO"] ?? ''); ?>" required>
                                </div>
                            </div>
                            
                            <!-- Descrição -->
                            <div class="form-group">
                                <label class="form-label required-field">
                                    <i class="bi bi-text-paragraph"></i> Descrição
                                </label>
                                <textarea class="form-control" name="descricaoDoLivro" id="descricaoDoLivro" 
                                          rows="4" required><?php echo htmlspecialchars($linha["DESCRICAO"] ?? ''); ?></textarea>
                            </div>
                            
                            <!-- Ano de Publicação -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="bi bi-calendar-heart"></i> Ano de Publicação
                                </label>
                                <div class="input-with-icon">
                                    <i class="bi bi-calendar-date input-icon"></i>
                                    <input type="date" class="form-control" name="anoDePublicacao" id="anoDePublicacao"
                                           value="<?php echo htmlspecialchars($linha["ANO_PUBLICACAO"] ?? ''); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="btn-group-custom">
                            <a href="/views/livros/visualizar.php" class="btn-custom btn-secondary-custom">
                                <i class="bi bi-arrow-left"></i> Voltar
                            </a>
                            <button type="button" class="btn-custom btn-primary-custom next-step" data-next="2">
                                Seguinte <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- STEP 2: Autor e Editora -->
                    <div class="step-form" id="step2">
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="bi bi-person"></i> Autor e Editora
                            </h3>
                            
                            <!-- Autor Principal -->
                            <div class="form-group">
                                <label class="form-label required-field">
                                    <i class="bi bi-person-circle"></i> Autor Principal
                                </label>
                                <div class="input-with-icon">
                                    <i class="bi bi-person input-icon"></i>
                                    <input type="text" class="form-control" name="autorPrincipal" id="autorPrincipal"
                                           value="<?php echo htmlspecialchars($linha["AUTOR"] ?? ''); ?>" required
                                           placeholder="Autor Principal">
                                </div>
                            </div>
                            
                            <!-- Editora -->
                            <div class="form-group">
                                <label class="form-label required-field">
                                    <i class="bi bi-building"></i> Editora
                                </label>
                                <div class="input-with-icon">
                                    <i class="bi bi-building input-icon"></i>
                                    <input type="text" class="form-control" name="nomeDaEditora" id="nomeDaEditora"
                                           value="<?php echo htmlspecialchars($linha["EDITORA"] ?? ''); ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="btn-group-custom">
                            <button type="button" class="btn-custom btn-secondary-custom prev-step" data-prev="1">
                                <i class="bi bi-arrow-left"></i> Anterior
                            </button>
                            <button type="button" class="btn-custom btn-primary-custom next-step" data-next="3">
                                Seguinte <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- STEP 3: Categorias e Estoque -->
                    <div class="step-form" id="step3">
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="bi bi-grid"></i> Categorias e Estoque
                            </h3>
                            
                            <!-- Gênero -->
                            <div class="form-group">
                                <label class="form-label required-field">
                                    <i class="bi bi-tags"></i> Gênero
                                </label>
                                <div class="input-with-icon">
                                    <i class="bi bi-tag input-icon"></i>
                                    <input type="text" class="form-control" name="generoPrincipal" id="generoPrincipal"
                                           value="<?php echo htmlspecialchars($linha["GENERO"] ?? ''); ?>" required>
                                </div>
                            </div>

                            <!-- ISBN -->
                            <div class="form-group">
                                <label class="form-label required-field">
                                    <i class="bi bi-upc-scan"></i> Código ISBN
                                </label>
                                <div class="input-with-icon">
                                    <i class="bi bi-barcode input-icon"></i>
                                    <input type="text" class="form-control" name="codigoISBN" id="codigoISBN"
                                           value="<?php echo htmlspecialchars($linha["ISBN"] ?? ''); ?>" required
                                           placeholder="Código ISBN">
                                </div>
                                <small class="form-text-custom">Código de identificação único do livro</small>
                            </div>

                            <!-- Unidades Disponíveis -->
                            <div class="form-group">
                                <label class="form-label required-field">
                                    <i class="bi bi-box"></i> Unidades Disponíveis
                                </label>
                                <div class="input-with-icon">
                                    <i class="bi bi-box-arrow-in-down input-icon"></i>
                                    <input type="number" class="form-control" name="unidadesDisponiveis" id="unidadesDisponiveis"
                                           value="<?php echo htmlspecialchars($linha["UNIDADES_DISPONIVEIS"] ?? ''); ?>" required
                                           min="0">
                                </div>
                            </div>
                        </div>

                        <div class="btn-group-custom">
                            <button type="button" class="btn-custom btn-secondary-custom prev-step" data-prev="2">
                                <i class="bi bi-arrow-left"></i> Anterior
                            </button>
                            <button type="button" class="btn-custom btn-primary-custom next-step" data-next="4">
                                Seguinte <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- STEP 4: Confirmação -->
                    <div class="step-form" id="step4">
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="bi bi-check-circle"></i> Confirmação
                            </h3>
                            
                            <div class="alert-custom" style="background: #f0fff4; border-color: #9ae6b4; color: #276749;">
                                <i class="bi bi-info-circle"></i>
                                <div>
                                    <strong>Verifique os dados antes de confirmar:</strong><br>
                                    Confirme que todas as informações estão corretas antes de salvar as alterações.
                                </div>
                            </div>

                            <div class="review-section">
                                <h4><i class="bi bi-journal-text"></i> Informações Básicas</h4>
                                <p><strong>Título:</strong> <span id="reviewTitulo"><?php echo htmlspecialchars($linha["TITULO"] ?? ''); ?></span></p>
                                <p><strong>Descrição:</strong> <span id="reviewDescricao"><?php echo htmlspecialchars($linha["DESCRICAO"] ?? ''); ?></span></p>
                                <p><strong>Ano Publicação:</strong> <span id="reviewAnoPublicacao"><?php echo htmlspecialchars($linha["ANO_PUBLICACAO"] ?? ''); ?></span></p>
                            </div>
                            
                            <div class="review-section">
                                <h4><i class="bi bi-person"></i> Autor e Editora</h4>
                                <p><strong>Autor:</strong> <span id="reviewAutor"><?php echo htmlspecialchars($linha["AUTOR"] ?? ''); ?></span></p>
                                <p><strong>Editora:</strong> <span id="reviewEditora"><?php echo htmlspecialchars($linha["EDITORA"] ?? ''); ?></span></p>
                            </div>
                            
                            <div class="review-section">
                                <h4><i class="bi bi-grid"></i> Categorias e Estoque</h4>
                                <p><strong>Gênero:</strong> <span id="reviewGenero"><?php echo htmlspecialchars($linha["GENERO"] ?? ''); ?></span></p>
                                <p><strong>ISBN:</strong> <span id="reviewISBN"><?php echo htmlspecialchars($linha["ISBN"] ?? ''); ?></span></p>
                                <p><strong>Unidades Disponíveis:</strong> <span id="reviewUnidades"><?php echo htmlspecialchars($linha["UNIDADES_DISPONIVEIS"] ?? ''); ?></span></p>
                            </div>
                        </div>

                        <div class="btn-group-custom">
                            <button type="button" class="btn-custom btn-secondary-custom prev-step" data-prev="3">
                                <i class="bi bi-arrow-left"></i> Anterior
                            </button>
                            <button type="submit" class="btn-custom btn-primary-custom" id="submitBtn">
                                <i class="bi bi-check-lg"></i> Confirmar Alterações
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('bookForm');
            const progressSteps = document.querySelectorAll('.progress-step-horizontal');
            const stepCircles = document.querySelectorAll('.step-circle-horizontal');
            const stepForms = document.querySelectorAll('.step-form');
            let currentStep = 1;

            // ========== MULTI-STEP NAVIGATION ==========
            function updateProgressBar() {
                // Update progress line - HORIZONTAL
                const progressPercentage = ((currentStep - 1) / (progressSteps.length - 1)) * 100;
                document.documentElement.style.setProperty('--progress-width', progressPercentage + '%');

                // Update steps
                stepCircles.forEach((circle, index) => {
                    if (index + 1 < currentStep) {
                        circle.classList.add('completed');
                        circle.classList.remove('active');
                    } else if (index + 1 === currentStep) {
                        circle.classList.add('active');
                        circle.classList.remove('completed');
                    } else {
                        circle.classList.remove('active', 'completed');
                    }
                });

                // Update forms
                stepForms.forEach((form, index) => {
                    if (index + 1 === currentStep) {
                        form.classList.add('active');
                    } else {
                        form.classList.remove('active');
                    }
                });
            }

            function goToStep(step) {
                currentStep = step;
                updateProgressBar();
                
                // Scroll to top of form
                document.querySelector('.form-card').scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'start' 
                });
            }

            // Next step buttons
            document.querySelectorAll('.next-step').forEach(button => {
                button.addEventListener('click', function() {
                    const nextStep = parseInt(this.getAttribute('data-next'));
                    if (validateStep(currentStep)) {
                        updateReviewSection();
                        goToStep(nextStep);
                    }
                });
            });

            // Previous step buttons
            document.querySelectorAll('.prev-step').forEach(button => {
                button.addEventListener('click', function() {
                    const prevStep = parseInt(this.getAttribute('data-prev'));
                    goToStep(prevStep);
                });
            });

            // Click on progress steps
            progressSteps.forEach(step => {
                step.addEventListener('click', function() {
                    const stepNumber = parseInt(this.getAttribute('data-step'));
                    if (stepNumber <= currentStep) {
                        goToStep(stepNumber);
                    }
                });
            });

            // ========== STEP VALIDATION ==========
            function validateStep(step) {
                let isValid = true;
                
                switch(step) {
                    case 1:
                        const tituloInput = document.getElementById('tituloDoLivro');
                        const descricaoInput = document.getElementById('descricaoDoLivro');
                        
                        // Reset validation
                        tituloInput.classList.remove('is-invalid');
                        descricaoInput.classList.remove('is-invalid');
                        
                        if (!tituloInput.value.trim()) {
                            tituloInput.classList.add('is-invalid');
                            isValid = false;
                        }
                        if (!descricaoInput.value.trim()) {
                            descricaoInput.classList.add('is-invalid');
                            isValid = false;
                        }
                        break;
                        
                    case 2:
                        const autorInput = document.getElementById('autorPrincipal');
                        const editoraInput = document.getElementById('nomeDaEditora');
                        
                        // Reset validation
                        autorInput.classList.remove('is-invalid');
                        editoraInput.classList.remove('is-invalid');
                        
                        if (!autorInput.value.trim()) {
                            autorInput.classList.add('is-invalid');
                            isValid = false;
                        }
                        if (!editoraInput.value.trim()) {
                            editoraInput.classList.add('is-invalid');
                            isValid = false;
                        }
                        break;

                    case 3:
                        const generoInput = document.getElementById('generoPrincipal');
                        const isbnInput = document.getElementById('codigoISBN');
                        const unidadesInput = document.getElementById('unidadesDisponiveis');
                        
                        // Reset validation
                        generoInput.classList.remove('is-invalid');
                        isbnInput.classList.remove('is-invalid');
                        unidadesInput.classList.remove('is-invalid');
                        
                        if (!generoInput.value.trim()) {
                            generoInput.classList.add('is-invalid');
                            isValid = false;
                        }
                        if (!isbnInput.value.trim()) {
                            isbnInput.classList.add('is-invalid');
                            isValid = false;
                        }
                        if (!unidadesInput.value.trim() || unidadesInput.value < 0) {
                            unidadesInput.classList.add('is-invalid');
                            isValid = false;
                        }
                        break;
                }
                
                if (!isValid) {
                    // Scroll to first error
                    const firstError = document.querySelector('.is-invalid');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        firstError.focus();
                    }
                }
                
                return isValid;
            }

            // ========== UPDATE REVIEW SECTION ==========
            function updateReviewSection() {
                document.getElementById('reviewTitulo').textContent = document.getElementById('tituloDoLivro').value;
                document.getElementById('reviewDescricao').textContent = document.getElementById('descricaoDoLivro').value;
                document.getElementById('reviewAnoPublicacao').textContent = document.getElementById('anoDePublicacao').value;
                document.getElementById('reviewAutor').textContent = document.getElementById('autorPrincipal').value;
                document.getElementById('reviewEditora').textContent = document.getElementById('nomeDaEditora').value;
                document.getElementById('reviewGenero').textContent = document.getElementById('generoPrincipal').value;
                document.getElementById('reviewISBN').textContent = document.getElementById('codigoISBN').value;
                document.getElementById('reviewUnidades').textContent = document.getElementById('unidadesDisponiveis').value;
            }

            // Validate all steps before submission
            form.addEventListener('submit', function(e) {
                if (!validateStep(1) || !validateStep(2) || !validateStep(3)) {
                    e.preventDefault();
                    alert('Por favor, corrija os erros no formulário antes de submeter.');
                    goToStep(1);
                }
            });

            // Real-time validation
            form.querySelectorAll('input').forEach(input => {
                input.addEventListener('blur', function() {
                    if (this.hasAttribute('required')) {
                        if (!this.value.trim()) {
                            this.classList.add('is-invalid');
                        }
                    }
                });

                input.addEventListener('input', function() {
                    if (this.value.trim()) {
                        this.classList.remove('is-invalid');
                    }
                });
            });

            // Initialize progress bar
            updateProgressBar();
            
            // Foco automático no primeiro campo
            setTimeout(() => {
                const firstInput = document.querySelector('input[name="tituloDoLivro"]');
                if (firstInput) {
                    firstInput.focus();
                    firstInput.select();
                }
            }, 500);
        });
    </script>
</body>
</html>

<?php include('../../../layout/footer.html'); ?>