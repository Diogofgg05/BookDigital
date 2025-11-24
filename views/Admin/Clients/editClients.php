<?php
# Impede que usuários acessem a página se não estiverem logados
include('../../../seguranca/seguranca.php');
session_start();
if(administrador_logado() == false) {
    header("location: /index.php"); 
    exit;
}

require_once("../../../conexao/conexao.php");

if(!filter_input(INPUT_GET, "NIF", FILTER_SANITIZE_FULL_SPECIAL_CHARS)) {
    echo "NIF é inválido!";
} else {
    $NIF = filter_input(INPUT_GET, "NIF", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $consulta = $conexao->query("SELECT * FROM UTILIZADORES WHERE NIF='$NIF'");
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
    <title>Editar Utilizador - Digiteca</title>
    
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
    
    .icon-user-1 {
        top: 5%;
        left: 3%;
    }
    
    .icon-user-2 {
        top: 60%;
        right: 3%;
    }
    
    .icon-user-3 {
        bottom: 5%;
        left: 40%;
    }

    /* PROGRESS BAR HORIZONTAL - AGORA SIM! */
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
    
    .password-toggle {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--secondary-color);
        cursor: pointer;
        font-size: 1rem;
        transition: color 0.3s ease;
    }
    
    .password-toggle:hover {
        color: var(--primary-color);
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
    
    .password-strength {
        height: 4px;
        background: var(--border-color);
        border-radius: 2px;
        margin-top: 8px;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .password-strength-bar {
        height: 100%;
        width: 0%;
        transition: all 0.3s ease;
    }
    
    .strength-weak { background: var(--error-color); width: 25%; }
    .strength-fair { background: var(--warning-color); width: 50%; }
    .strength-good { background: var(--info-color); width: 75%; }
    .strength-strong { background: var(--success-color); width: 100%; }
    
    .password-feedback {
        font-size: 0.8rem;
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .text-success { color: var(--success-color); }
    .text-warning { color: var(--warning-color); }
    .text-error { color: var(--error-color); }
    .text-info { color: var(--info-color); }
    
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
            max-width: 700px;
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
        <!-- Header -->
       

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
                <i class="bi bi-person floating-icon icon-user-1"></i>
                <i class="bi bi-person-badge floating-icon icon-user-2"></i>
                <i class="bi bi-people floating-icon icon-user-3"></i>
                
                <!-- PROGRESS BAR HORIZONTAL - FINALMENTE! -->
                <div class="progress-container">
                    <div class="progress-bar-horizontal">
                        <div class="progress-step-horizontal" data-step="1">
                            <div class="step-circle-horizontal" id="stepCircle1">
                                <i class="bi bi-person-vcard step-icon"></i>
                            </div>
                            <div class="step-label-horizontal">Informações<br>Pessoais</div>
                        </div>
                        <div class="progress-step-horizontal" data-step="2">
                            <div class="step-circle-horizontal" id="stepCircle2">
                                <i class="bi bi-telephone step-icon"></i>
                            </div>
                            <div class="step-label-horizontal">Contactos</div>
                        </div>
                        <div class="progress-step-horizontal" data-step="3">
                            <div class="step-circle-horizontal" id="stepCircle3">
                                <i class="bi bi-shield-lock step-icon"></i>
                            </div>
                            <div class="step-label-horizontal">Segurança</div>
                        </div>
                        <div class="progress-step-horizontal" data-step="4">
                            <div class="step-circle-horizontal" id="stepCircle4">
                                <i class="bi bi-check-circle step-icon"></i>
                            </div>
                            <div class="step-label-horizontal">Confirmação</div>
                        </div>
                    </div>
                </div>

                <form action="/DB/usuarios/editar.php" method="post" id="userForm">
                    <input type="hidden" name="txtNIFAtualizar" value="<?php echo htmlspecialchars($NIF); ?>">
                    
                    <!-- STEP 1: Informações Pessoais -->
                    <div class="step-form active" id="step1">
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="bi bi-person-vcard"></i> Informações Pessoais
                            </h3>
                            
                            <!-- NIF -->
                            <div class="form-group">
                                <label class="form-label required-field">
                                    <i class="bi bi-credit-card"></i> NIF
                                </label>
                                <div class="input-with-icon">
                                    <i class="bi bi-credit-card input-icon"></i>
                                    <input type="text" class="form-control" name="txtNIF" id="txtNIF"
                                           value="<?php echo htmlspecialchars($linha["NIF"] ?? ''); ?>" 
                                           required maxlength="11" placeholder="123 456 789">
                                </div>
                                <small class="form-text-custom">9 dígitos (formato: 123 456 789)</small>
                            </div>
                            
                            <!-- NOME -->
                            <div class="form-group">
                                <label class="form-label required-field">
                                    <i class="bi bi-person"></i> NOME
                                </label>
                                <div class="input-with-icon">
                                    <i class="bi bi-type input-icon"></i>
                                    <input type="text" class="form-control" name="txtNOME" id="txtNOME"
                                           value="<?php echo htmlspecialchars($linha["NOME"] ?? ''); ?>" required>
                                </div>
                            </div>
                            
                            <!-- SOBRENOME -->
                            <div class="form-group">
                                <label class="form-label required-field">
                                    <i class="bi bi-person"></i> SOBRENOME
                                </label>
                                <input type="text" class="form-control" name="txtSOBRENOME" id="txtSOBRENOME"
                                       value="<?php echo htmlspecialchars($linha["SOBRENOME"] ?? ''); ?>" required>
                            </div>
                        </div>

                        <div class="btn-group-custom">
                            <a href="/views/usuarios/visualizar.php" class="btn-custom btn-secondary-custom">
                                <i class="bi bi-arrow-left"></i> Voltar
                            </a>
                            <button type="button" class="btn-custom btn-primary-custom next-step" data-next="2">
                                Seguinte <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- STEP 2: Contactos -->
                    <div class="step-form" id="step2">
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="bi bi-telephone"></i> Contactos
                            </h3>
                            
                            <!-- EMAIL -->
                            <div class="form-group">
                                <label class="form-label required-field">
                                    <i class="bi bi-envelope"></i> EMAIL
                                </label>
                                <div class="input-with-icon">
                                    <i class="bi bi-at input-icon"></i>
                                    <input type="email" class="form-control" name="txtEMAIL" id="txtEMAIL"
                                           value="<?php echo htmlspecialchars($linha["EMAIL"] ?? ''); ?>" required>
                                </div>
                            </div>
                            
                            <!-- TELEFONE -->
                            <div class="form-group">
                                <label class="form-label required-field">
                                    <i class="bi bi-phone"></i> TELEFONE
                                </label>
                                <div class="input-with-icon">
                                    <i class="bi bi-telephone input-icon"></i>
                                    <input type="tel" class="form-control" name="txtTELEFONE" id="txtTELEFONE"
                                           value="<?php echo htmlspecialchars($linha["TELEFONE"] ?? ''); ?>" 
                                           required maxlength="11" placeholder="912 345 678">
                                </div>
                                <small class="form-text-custom">9 dígitos (formato: 912 345 678)</small>
                            </div>

                            <!-- DATA_NASCIMENTO -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="bi bi-calendar-heart"></i> DATA DE NASCIMENTO
                                </label>
                                <div class="input-with-icon">
                                    <i class="bi bi-calendar-date input-icon"></i>
                                    <input type="date" class="form-control" name="txtDATA_NASCIMENTO" id="txtDATA_NASCIMENTO"
                                           value="<?php echo htmlspecialchars($linha["DATA_NASCIMENTO"] ?? ''); ?>">
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

                    <!-- STEP 3: Segurança -->
                    <div class="step-form" id="step3">
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="bi bi-shield-lock"></i> Segurança (Opcional)
                            </h3>
                            
                            <!-- NOVA SENHA -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="bi bi-key"></i> NOVA SENHA
                                </label>
                                <div class="input-with-icon">
                                    <i class="bi bi-lock input-icon"></i>
                                    <input type="password" class="form-control" name="txtSENHA" id="txtSENHA"
                                           placeholder="Deixe em branco para manter a atual"
                                           minlength="6">
                                    <button type="button" class="password-toggle" data-target="txtSENHA">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <div class="password-strength">
                                    <div class="password-strength-bar" id="passwordStrengthBar"></div>
                                </div>
                                <div class="password-feedback" id="passwordFeedback"></div>
                                <small class="form-text-custom">Mínimo 6 caracteres. Deixe em branco para não alterar.</small>
                            </div>

                            <!-- CONFIRMAR SENHA -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="bi bi-key-fill"></i> CONFIRMAR SENHA
                                </label>
                                <div class="input-with-icon">
                                    <i class="bi bi-shield-lock input-icon"></i>
                                    <input type="password" class="form-control" name="txtCONFIRMAR_SENHA" id="txtCONFIRMAR_SENHA"
                                           placeholder="Confirme a nova senha"
                                           minlength="6">
                                    <button type="button" class="password-toggle" data-target="txtCONFIRMAR_SENHA">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <div class="password-feedback" id="confirmPasswordFeedback"></div>
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
                                <h4><i class="bi bi-person"></i> Informações Pessoais</h4>
                                <p><strong>NIF:</strong> <span id="reviewNIF"><?php echo htmlspecialchars($linha["NIF"] ?? ''); ?></span></p>
                                <p><strong>Nome:</strong> <span id="reviewNOME"><?php echo htmlspecialchars($linha["NOME"] ?? ''); ?></span></p>
                                <p><strong>Sobrenome:</strong> <span id="reviewSOBRENOME"><?php echo htmlspecialchars($linha["SOBRENOME"] ?? ''); ?></span></p>
                            </div>
                            
                            <div class="review-section">
                                <h4><i class="bi bi-telephone"></i> Contactos</h4>
                                <p><strong>Email:</strong> <span id="reviewEMAIL"><?php echo htmlspecialchars($linha["EMAIL"] ?? ''); ?></span></p>
                                <p><strong>Telefone:</strong> <span id="reviewTELEFONE"><?php echo htmlspecialchars($linha["TELEFONE"] ?? ''); ?></span></p>
                                <p><strong>Data Nascimento:</strong> <span id="reviewDATA_NASCIMENTO"><?php echo htmlspecialchars($linha["DATA_NASCIMENTO"] ?? ''); ?></span></p>
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
            const form = document.getElementById('userForm');
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
                        const nifInput = document.getElementById('txtNIF');
                        const nomeInput = document.getElementById('txtNOME');
                        const sobrenomeInput = document.getElementById('txtSOBRENOME');
                        
                        // Reset validation
                        nifInput.classList.remove('is-invalid');
                        nomeInput.classList.remove('is-invalid');
                        sobrenomeInput.classList.remove('is-invalid');
                        
                        if (!nifInput.value.trim() || nifInput.value.replace(/\s/g, '').length !== 9) {
                            nifInput.classList.add('is-invalid');
                            isValid = false;
                        }
                        if (!nomeInput.value.trim()) {
                            nomeInput.classList.add('is-invalid');
                            isValid = false;
                        }
                        if (!sobrenomeInput.value.trim()) {
                            sobrenomeInput.classList.add('is-invalid');
                            isValid = false;
                        }
                        break;
                        
                    case 2:
                        const emailInput = document.getElementById('txtEMAIL');
                        const telefoneInput = document.getElementById('txtTELEFONE');
                        
                        // Reset validation
                        emailInput.classList.remove('is-invalid');
                        telefoneInput.classList.remove('is-invalid');
                        
                        if (!emailInput.value.trim() || !isValidEmail(emailInput.value)) {
                            emailInput.classList.add('is-invalid');
                            isValid = false;
                        }
                        if (!telefoneInput.value.trim() || telefoneInput.value.replace(/\s/g, '').length !== 9) {
                            telefoneInput.classList.add('is-invalid');
                            isValid = false;
                        }
                        break;

                    case 3:
                        // Step 3 is optional, so always valid
                        isValid = true;
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

            function isValidEmail(email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }

            // ========== UPDATE REVIEW SECTION ==========
            function updateReviewSection() {
                document.getElementById('reviewNIF').textContent = document.getElementById('txtNIF').value;
                document.getElementById('reviewNOME').textContent = document.getElementById('txtNOME').value;
                document.getElementById('reviewSOBRENOME').textContent = document.getElementById('txtSOBRENOME').value;
                document.getElementById('reviewEMAIL').textContent = document.getElementById('txtEMAIL').value;
                document.getElementById('reviewTELEFONE').textContent = document.getElementById('txtTELEFONE').value;
                document.getElementById('reviewDATA_NASCIMENTO').textContent = document.getElementById('txtDATA_NASCIMENTO').value;
            }

            // ========== ORIGINAL FUNCTIONALITY ==========
            const senhaInput = document.getElementById('txtSENHA');
            const confirmarSenhaInput = document.getElementById('txtCONFIRMAR_SENHA');
            const passwordStrengthBar = document.getElementById('passwordStrengthBar');
            const passwordFeedback = document.getElementById('passwordFeedback');
            const confirmPasswordFeedback = document.getElementById('confirmPasswordFeedback');

            // Formatação de NIF e Telefone com espaços
            function formatarNumeroComEspacos(input, maxDigits) {
                input.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    value = value.substring(0, maxDigits);
                    
                    if (value.length > 6) {
                        value = value.replace(/(\d{3})(\d{3})(\d{3})/, '$1 $2 $3');
                    } else if (value.length > 3) {
                        value = value.replace(/(\d{3})(\d{3})/, '$1 $2');
                    }
                    
                    e.target.value = value;
                    
                    const digitsOnly = value.replace(/\s/g, '');
                    if (digitsOnly.length === maxDigits) {
                        this.classList.add('is-valid');
                        this.classList.remove('is-invalid');
                    } else if (digitsOnly.length > 0) {
                        this.classList.add('is-invalid');
                        this.classList.remove('is-valid');
                    } else {
                        this.classList.remove('is-valid', 'is-invalid');
                    }
                });
            }

            // Aplicar formatação
            const nifInput = document.getElementById('txtNIF');
            const telefoneInput = document.getElementById('txtTELEFONE');
            if (nifInput) formatarNumeroComEspacos(nifInput, 9);
            if (telefoneInput) formatarNumeroComEspacos(telefoneInput, 9);

            // Limpar espaços antes do envio
            form.addEventListener('submit', function(e) {
                if (nifInput) {
                    const cleanNIF = nifInput.value.replace(/\s/g, '');
                    nifInput.value = cleanNIF;
                }
                if (telefoneInput) {
                    const cleanTelefone = telefoneInput.value.replace(/\s/g, '');
                    telefoneInput.value = cleanTelefone;
                }
                
                // Validate all steps before submission
                if (!validateStep(1) || !validateStep(2)) {
                    e.preventDefault();
                    alert('Por favor, corrija os erros no formulário antes de submeter.');
                    goToStep(1);
                }
            });

            // Toggle password visibility
            document.querySelectorAll('.password-toggle').forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const targetInput = document.getElementById(targetId);
                    const icon = this.querySelector('i');
                    
                    if (targetInput.type === 'password') {
                        targetInput.type = 'text';
                        icon.classList.remove('bi-eye');
                        icon.classList.add('bi-eye-slash');
                    } else {
                        targetInput.type = 'password';
                        icon.classList.remove('bi-eye-slash');
                        icon.classList.add('bi-eye');
                    }
                });
            });

            // Password strength checker
            function checkPasswordStrength(password) {
                let strength = 0;
                let feedback = '';

                if (password.length >= 6) strength++;
                if (password.length >= 8) strength++;
                if (/[A-Z]/.test(password)) strength++;
                if (/[0-9]/.test(password)) strength++;
                if (/[^A-Za-z0-9]/.test(password)) strength++;

                passwordStrengthBar.className = 'password-strength-bar';
                if (password.length === 0) {
                    feedback = '';
                } else if (strength <= 2) {
                    passwordStrengthBar.classList.add('strength-weak');
                    feedback = '<i class="bi bi-exclamation-triangle"></i> Senha fraca';
                    passwordFeedback.className = 'password-feedback text-error';
                } else if (strength <= 3) {
                    passwordStrengthBar.classList.add('strength-fair');
                    feedback = '<i class="bi bi-info-circle"></i> Senha razoável';
                    passwordFeedback.className = 'password-feedback text-warning';
                } else if (strength <= 4) {
                    passwordStrengthBar.classList.add('strength-good');
                    feedback = '<i class="bi bi-check-circle"></i> Senha boa';
                    passwordFeedback.className = 'password-feedback text-info';
                } else {
                    passwordStrengthBar.classList.add('strength-strong');
                    feedback = '<i class="bi bi-shield-check"></i> Senha forte';
                    passwordFeedback.className = 'password-feedback text-success';
                }

                passwordFeedback.innerHTML = feedback;
                return strength;
            }

            // Password validation
            function validatePasswords() {
                const password = senhaInput.value;
                const confirmPassword = confirmarSenhaInput.value;
                let isValid = true;

                if (password.length > 0) {
                    if (password.length < 6) {
                        senhaInput.classList.add('is-invalid');
                        senhaInput.classList.remove('is-valid');
                        isValid = false;
                    } else {
                        senhaInput.classList.add('is-valid');
                        senhaInput.classList.remove('is-invalid');
                    }

                    if (confirmPassword.length > 0) {
                        if (password !== confirmPassword) {
                            confirmarSenhaInput.classList.add('is-invalid');
                            confirmarSenhaInput.classList.remove('is-valid');
                            confirmPasswordFeedback.innerHTML = '<i class="bi bi-x-circle"></i> As senhas não coincidem';
                            confirmPasswordFeedback.className = 'password-feedback text-error';
                            isValid = false;
                        } else {
                            confirmarSenhaInput.classList.add('is-valid');
                            confirmarSenhaInput.classList.remove('is-invalid');
                            confirmPasswordFeedback.innerHTML = '<i class="bi bi-check-circle"></i> Senhas coincidem';
                            confirmPasswordFeedback.className = 'password-feedback text-success';
                        }
                    } else {
                        confirmarSenhaInput.classList.remove('is-valid', 'is-invalid');
                        confirmPasswordFeedback.innerHTML = '';
                    }
                } else {
                    senhaInput.classList.remove('is-valid', 'is-invalid');
                    confirmarSenhaInput.classList.remove('is-valid', 'is-invalid');
                    confirmPasswordFeedback.innerHTML = '';
                }

                return isValid;
            }

            senhaInput.addEventListener('input', function() {
                checkPasswordStrength(this.value);
                validatePasswords();
            });

            confirmarSenhaInput.addEventListener('input', validatePasswords);

            // Real-time validation
            form.querySelectorAll('input').forEach(input => {
                input.addEventListener('blur', function() {
                    if (this.hasAttribute('required')) {
                        if (this.id === 'txtNIF' || this.id === 'txtTELEFONE') {
                            const cleanValue = this.value.replace(/\s/g, '');
                            if (cleanValue.length !== 9) {
                                this.classList.add('is-invalid');
                            }
                        } else if (!this.value.trim()) {
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
                const firstInput = document.querySelector('input[name="txtNIF"]');
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