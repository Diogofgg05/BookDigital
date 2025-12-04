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
    exit;
} else {
    $NIF = filter_input(INPUT_GET, "NIF", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $consulta = $conexao->query("SELECT * FROM UTILIZADORES WHERE NIF='$NIF'");
    $linha = $consulta->fetch(PDO::FETCH_ASSOC);
    
    if(!$linha) {
        echo "Utilizador não encontrado!";
        exit;
    }
    
    // Verificar se existe imagem e processar caminho
    $temImagem = false;
    $caminhoImagem = '';
    $imagemExiste = false;
    
    if (!empty($linha["FOTO_PERFIL"]) && $linha["FOTO_PERFIL"] != '[ ]' && $linha["FOTO_PERFIL"] != '') {
        $temImagem = true;
        $caminhoNaBase = $linha["FOTO_PERFIL"];
        
        if (strpos($caminhoNaBase, '/uploads/') === 0) {
            $caminhoImagem = '/Api' . $caminhoNaBase;
        } else {
            $caminhoImagem = $caminhoNaBase;
        }
        
        $caminhoAbsoluto = $_SERVER['DOCUMENT_ROOT'] . $caminhoImagem;
        $imagemExiste = file_exists($caminhoAbsoluto);
    }
}

// Incluir header e navbar APÓS toda a lógica PHP
include('../../../layout/header.html');
include('../../../layout/navbar.php');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Utilizador • Sistema de Biblioteca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --primary-light: #dbeafe;
            --secondary: #64748b;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --light: #f8fafc;
            --dark: #1e293b;
            --border: #e2e8f0;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            --radius-sm: 6px;
            --radius-md: 8px;
            --radius-lg: 12px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        .edit-user-container {
            background: #f1f5f9;
            min-height: calc(100vh - 80px);
            padding: 24px;
        }
        
        /* CONTAINER +10% */
        .compact-container {
            max-width: 1000px;
            margin: 0 auto;
        }
        
        /* HEADER +10% */
        .page-header-compact {
            background: white;
            border-radius: var(--radius-lg);
            padding: 18px 22px;
            margin-bottom: 18px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
        }
        
        .header-compact-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
        }
        
        .header-title-group {
            flex: 1;
            min-width: 220px;
        }
        
        .page-title-compact {
            font-size: 20px;
            font-weight: 700;
            color: var(--dark);
            margin: 0;
            line-height: 1.3;
        }
        
        .page-subtitle-compact {
            font-size: 12px;
            color: var(--secondary);
            margin-top: 4px;
            opacity: 0.8;
        }
        
        /* FORM CARD +10% */
        .form-card-compact {
            background: white;
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }
        
        /* PROGRESS BAR +10% */
        .progress-container-compact {
            padding: 14px 22px;
            background: var(--light);
            border-bottom: 1px solid var(--border);
        }
        
        .progress-bar-compact {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            max-width: 660px;
            margin: 0 auto;
        }
        
        .progress-line-compact {
            position: absolute;
            top: 14px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--border);
            z-index: 1;
        }
        
        .progress-fill-compact {
            position: absolute;
            top: 14px;
            left: 0;
            height: 2px;
            background: var(--primary);
            transition: width 0.3s ease;
            z-index: 2;
            width: 0%;
        }
        
        .progress-step-compact {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 3;
            flex: 1;
            cursor: pointer;
            padding: 0 6px;
        }
        
        .step-circle-compact {
            width: 28px;
            height: 28px;
            background: white;
            border: 2px solid var(--border);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: var(--secondary);
            transition: all 0.2s ease;
            margin-bottom: 6px;
            font-size: 11px;
            position: relative;
        }
        
        .step-circle-compact.active {
            border-color: var(--primary);
            background: var(--primary);
            color: white;
            box-shadow: 0 0 0 3px var(--primary-light);
            transform: scale(1.1);
        }
        
        .step-circle-compact.completed {
            border-color: var(--success);
            background: var(--success);
            color: white;
        }
        
        .step-label-compact {
            font-size: 10px;
            color: var(--secondary);
            font-weight: 500;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            line-height: 1.2;
            max-width: 60px;
            word-wrap: break-word;
        }
        
        /* FORM STEPS +10% */
        .step-form-compact {
            display: none;
        }
        
        .step-form-compact.active {
            display: block;
            animation: fadeIn 0.2s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        /* FORM CONTENT +10% */
        .form-content-compact {
            padding: 22px;
        }
        
        /* SECTION HEADER +10% */
        .section-header-compact {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 18px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border);
        }
        
        .section-icon-compact {
            width: 20px;
            height: 20px;
            background: var(--primary-light);
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 10px;
        }
        
        .section-title-compact {
            font-size: 14px;
            font-weight: 600;
            color: var(--dark);
            margin: 0;
        }
        
        /* FORM GRID +10% */
        .form-grid-compact {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 14px;
            margin-bottom: 18px;
        }
        
        /* FORM CONTROLS +10% */
        .form-group-compact {
            margin-bottom: 14px;
        }
        
        .form-label-compact {
            display: block;
            font-size: 11px;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .required-compact::after {
            content: "*";
            color: var(--danger);
            margin-left: 3px;
            font-size: 12px;
        }
        
        .form-control-compact {
            width: 100%;
            padding: 7px 12px;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 13px;
            background: white;
            color: var(--dark);
            transition: all 0.15s ease;
            font-family: inherit;
            height: 36px;
        }
        
        .form-control-compact:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-light);
        }
        
        textarea.form-control-compact {
            min-height: 80px;
            resize: vertical;
            line-height: 1.5;
            height: auto;
            padding: 9px 12px;
        }
        
        select.form-control-compact {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='9' height='9' fill='%2364748b' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-position: right 10px center;
            background-repeat: no-repeat;
            background-size: 8px;
            padding-right: 28px;
        }
        
        .input-with-icon-compact {
            position: relative;
        }
        
        .input-with-icon-compact .form-control-compact {
            padding-left: 32px;
        }
        
        .input-icon-compact {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--secondary);
            font-size: 12px;
            opacity: 0.7;
        }
        
        .form-hint-compact {
            font-size: 11px;
            color: var(--secondary);
            margin-top: 4px;
            line-height: 1.3;
        }
        
        /* PASSWORD STRENGTH */
        .password-strength-compact {
            height: 3px;
            background: var(--border);
            border-radius: 1.5px;
            margin-top: 6px;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .password-strength-bar-compact {
            height: 100%;
            width: 0%;
            transition: all 0.3s ease;
        }
        
        .strength-weak { background: var(--danger); width: 25%; }
        .strength-fair { background: var(--warning); width: 50%; }
        .strength-good { background: var(--primary); width: 75%; }
        .strength-strong { background: var(--success); width: 100%; }
        
        .password-feedback-compact {
            font-size: 11px;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .text-success { color: var(--success); }
        .text-warning { color: var(--warning); }
        .text-error { color: var(--danger); }
        .text-info { color: var(--primary); }
        
        /* IMAGE UPLOAD +10% */
        .image-upload-compact {
            border: 1px dashed var(--border);
            border-radius: var(--radius-md);
            padding: 14px;
            text-align: center;
            background: var(--light);
            transition: all 0.2s ease;
            margin-bottom: 18px;
        }
        
        .image-upload-compact:hover {
            border-color: var(--primary);
            background: white;
        }
        
        .current-image-compact {
            max-width: 120px;
            max-height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid var(--border);
            margin: 0 auto 10px;
        }
        
        .image-placeholder-compact {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #cbd5e1;
            font-size: 24px;
            margin: 0 auto 10px;
            border: 2px solid var(--border);
        }
        
        .image-actions-compact {
            display: flex;
            gap: 8px;
            justify-content: center;
            margin-top: 10px;
        }
        
        /* ALERTS +10% */
        .alert-compact {
            padding: 12px 14px;
            border-radius: var(--radius-sm);
            font-size: 12px;
            margin-bottom: 14px;
            border-left: 3px solid;
            background: var(--light);
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }
        
        .alert-danger-compact {
            border-left-color: var(--danger);
            background: #fef2f2;
        }
        
        .alert-success-compact {
            border-left-color: var(--success);
            background: #f0fdf4;
        }
        
        .alert-info-compact {
            border-left-color: var(--primary);
            background: var(--primary-light);
        }
        
        /* BUTTONS +10% */
        .form-actions-compact {
            display: flex;
            gap: 10px;
            justify-content: space-between;
            padding: 18px 22px;
            border-top: 1px solid var(--border);
            background: var(--light);
        }
        
        .btn-compact {
            padding: 8px 14px;
            border: none;
            border-radius: var(--radius-sm);
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            transition: all 0.2s ease;
            font-family: inherit;
            letter-spacing: 0.02em;
        }
        
        .btn-sm-compact {
            padding: 6px 10px;
            font-size: 11px;
        }
        
        .btn-secondary-compact {
            background: white;
            border: 1px solid var(--border);
            color: var(--secondary);
        }
        
        .btn-secondary-compact:hover {
            background: #f8fafc;
            border-color: var(--primary);
            color: var(--primary);
        }
        
        .btn-primary-compact {
            background: var(--primary);
            color: white;
        }
        
        .btn-primary-compact:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }
        
        .btn-outline-compact {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--secondary);
        }
        
        .btn-outline-compact:hover {
            background: var(--light);
            border-color: var(--primary);
            color: var(--primary);
        }
        
        /* REVIEW SECTION +10% */
        .review-grid-compact {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
            gap: 12px;
            margin-bottom: 18px;
        }
        
        .review-card-compact {
            background: var(--light);
            border-radius: var(--radius-sm);
            padding: 12px;
            border: 1px solid var(--border);
        }
        
        .review-header-compact {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 1px solid var(--border);
        }
        
        .review-title-compact {
            font-size: 12px;
            font-weight: 600;
            color: var(--dark);
            margin: 0;
        }
        
        .review-item-compact {
            margin-bottom: 6px;
            display: flex;
            font-size: 11px;
        }
        
        .review-label-compact {
            color: var(--secondary);
            font-weight: 500;
            min-width: 70px;
            flex-shrink: 0;
        }
        
        .review-value-compact {
            color: var(--dark);
            font-weight: 500;
            word-break: break-word;
        }
        
        /* PASSWORD TOGGLE */
        .password-toggle-compact {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--secondary);
            cursor: pointer;
            font-size: 12px;
            padding: 4px;
            transition: color 0.2s ease;
        }
        
        .password-toggle-compact:hover {
            color: var(--primary);
        }
        
        /* VALIDATION STATES */
        .is-invalid-compact {
            border-color: var(--danger) !important;
            background: #fef2f2;
        }
        
        .is-valid-compact {
            border-color: var(--success) !important;
            background: #f0fff4;
        }
        
        /* UTILITY CLASSES */
        .text-sm {
            font-size: 12px;
        }
        
        .text-xs {
            font-size: 11px;
        }
        
        .text-muted {
            color: var(--secondary);
        }
        
        .mt-2 {
            margin-top: 10px;
        }
        
        .mb-3 {
            margin-bottom: 14px;
        }
        
        .w-full {
            width: 100%;
        }
        
        /* MOBILE OPTIMIZATIONS */
        @media (max-width: 768px) {
            .edit-user-container {
                padding: 16px;
            }
            
            .page-header-compact {
                padding: 14px 18px;
            }
            
            .form-content-compact {
                padding: 18px;
            }
            
            .form-grid-compact {
                grid-template-columns: 1fr;
                gap: 12px;
            }
            
            .progress-step-compact {
                padding: 0 4px;
            }
            
            .step-circle-compact {
                width: 26px;
                height: 26px;
                font-size: 10px;
            }
            
            .step-label-compact {
                font-size: 9px;
                max-width: 50px;
            }
            
            .progress-line-compact,
            .progress-fill-compact {
                top: 13px;
            }
            
            .form-actions-compact {
                flex-direction: column;
                gap: 8px;
                padding: 14px 18px;
            }
            
            .btn-compact {
                width: 100%;
                justify-content: center;
            }
            
            .review-grid-compact {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 480px) {
            .page-title-compact {
                font-size: 18px;
            }
            
            .form-content-compact {
                padding: 16px;
            }
            
            .section-title-compact {
                font-size: 13px;
            }
            
            .step-label-compact {
                font-size: 8px;
                max-width: 45px;
            }
        }
    </style>
</head>
<body>
    <div class="edit-user-container">
        <div class="compact-container">
            <!-- HEADER +10% -->
            <div class="page-header-compact">
                <div class="header-compact-content">
                    <div class="header-title-group">
                        <h1 class="page-title-compact">Editar Utilizador</h1>
                        <p class="page-subtitle-compact">Atualize as informações do utilizador</p>
                    </div>
                    
                    <!-- Mensagens de status -->
                    <?php if(isset($_GET["mensagem_erro"])): ?>
                        <div class="alert-compact alert-danger-compact w-full">
                            <i class="bi bi-exclamation-triangle"></i>
                            <div><?php echo htmlspecialchars($_GET["mensagem_erro"]); ?></div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(isset($_GET["mensagem_sucesso"])): ?>
                        <div class="alert-compact alert-success-compact w-full">
                            <i class="bi bi-check-circle"></i>
                            <div><?php echo htmlspecialchars($_GET["mensagem_sucesso"]); ?></div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- FORM CARD +10% -->
            <div class="form-card-compact">
                <!-- PROGRESS BAR +10% -->
                <div class="progress-container-compact">
                    <div class="progress-bar-compact">
                        <div class="progress-line-compact"></div>
                        <div class="progress-fill-compact" id="progressFill"></div>
                        
                        <div class="progress-step-compact" data-step="1">
                            <div class="step-circle-compact active" id="stepCircle1">
                                <i class="bi bi-person-vcard"></i>
                            </div>
                            <div class="step-label-compact">Dados Pessoais</div>
                        </div>
                        <div class="progress-step-compact" data-step="2">
                            <div class="step-circle-compact" id="stepCircle2">
                                <i class="bi bi-telephone"></i>
                            </div>
                            <div class="step-label-compact">Contactos</div>
                        </div>
                        <div class="progress-step-compact" data-step="3">
                            <div class="step-circle-compact" id="stepCircle3">
                                <i class="bi bi-image"></i>
                            </div>
                            <div class="step-label-compact">Foto</div>
                        </div>
                        <div class="progress-step-compact" data-step="4">
                            <div class="step-circle-compact" id="stepCircle4">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                            <div class="step-label-compact">Segurança</div>
                        </div>
                        <div class="progress-step-compact" data-step="5">
                            <div class="step-circle-compact" id="stepCircle5">
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <div class="step-label-compact">Revisão</div>
                        </div>
                    </div>
                </div>
                
                <!-- FORM -->
                <form action="/Api/Users/editar.php" method="post" id="userForm" enctype="multipart/form-data">
                    <input type="hidden" name="txtNIFAtualizar" value="<?php echo htmlspecialchars($NIF); ?>">
                    <input type="hidden" name="foto_atual" id="fotoAtual" value="<?php echo htmlspecialchars($linha["FOTO_PERFIL"] ?? ''); ?>">
                    <input type="hidden" name="remover_foto" id="removerFoto" value="0">
                    
                    <!-- STEP 1: Dados Pessoais -->
                    <div class="step-form-compact active" id="step1">
                        <div class="form-content-compact">
                            <div class="section-header-compact">
                                <div class="section-icon-compact">
                                    <i class="bi bi-person-vcard"></i>
                                </div>
                                <h3 class="section-title-compact">Dados Pessoais</h3>
                            </div>
                            
                            <div class="form-grid-compact">
                                <!-- NIF -->
                                <div class="form-group-compact">
                                    <label class="form-label-compact required-compact">
                                        <i class="bi bi-credit-card"></i> NIF
                                    </label>
                                    <div class="input-with-icon-compact">
                                        <i class="bi bi-123 input-icon-compact"></i>
                                        <input type="text" class="form-control-compact" name="txtNIF" id="txtNIF"
                                               value="<?php echo htmlspecialchars($linha["NIF"] ?? ''); ?>" required
                                               placeholder="123456789" maxlength="9">
                                    </div>
                                    <div class="form-hint-compact">9 dígitos (sem espaços)</div>
                                </div>
                                
                                <!-- NOME -->
                                <div class="form-group-compact">
                                    <label class="form-label-compact required-compact">
                                        <i class="bi bi-person"></i> Nome
                                    </label>
                                    <div class="input-with-icon-compact">
                                        <i class="bi bi-type input-icon-compact"></i>
                                        <input type="text" class="form-control-compact" name="txtNOME" id="txtNOME"
                                               value="<?php echo htmlspecialchars($linha["NOME"] ?? ''); ?>" required
                                               placeholder="Primeiro nome">
                                    </div>
                                </div>
                                
                                <!-- SOBRENOME -->
                                <div class="form-group-compact">
                                    <label class="form-label-compact required-compact">
                                        <i class="bi bi-person-badge"></i> Sobrenome
                                    </label>
                                    <input type="text" class="form-control-compact" name="txtSOBRENOME" id="txtSOBRENOME"
                                           value="<?php echo htmlspecialchars($linha["SOBRENOME"] ?? ''); ?>" required
                                           placeholder="Apelido">
                                </div>
                                
                                <!-- DATA NASCIMENTO -->
                                <div class="form-group-compact">
                                    <label class="form-label-compact">
                                        <i class="bi bi-calendar"></i> Data Nascimento
                                    </label>
                                    <input type="date" class="form-control-compact" name="txtDATA_NASCIMENTO" id="txtDATA_NASCIMENTO"
                                           value="<?php echo htmlspecialchars($linha["DATA_NASCIMENTO"] ?? ''); ?>">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-actions-compact">
                            <a href="/views/usuarios/visualizar.php" class="btn-compact btn-secondary-compact">
                                <i class="bi bi-arrow-left"></i> Voltar
                            </a>
                            <button type="button" class="btn-compact btn-primary-compact next-step" data-next="2">
                                Seguinte <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- STEP 2: Contactos -->
                    <div class="step-form-compact" id="step2">
                        <div class="form-content-compact">
                            <div class="section-header-compact">
                                <div class="section-icon-compact">
                                    <i class="bi bi-telephone"></i>
                                </div>
                                <h3 class="section-title-compact">Contactos</h3>
                            </div>
                            
                            <div class="form-grid-compact">
                                <!-- EMAIL -->
                                <div class="form-group-compact">
                                    <label class="form-label-compact required-compact">
                                        <i class="bi bi-envelope"></i> Email
                                    </label>
                                    <div class="input-with-icon-compact">
                                        <i class="bi bi-at input-icon-compact"></i>
                                        <input type="email" class="form-control-compact" name="txtEMAIL" id="txtEMAIL"
                                               value="<?php echo htmlspecialchars($linha["EMAIL"] ?? ''); ?>" required
                                               placeholder="exemplo@email.com">
                                    </div>
                                </div>
                                
                                <!-- TELEFONE -->
                                <div class="form-group-compact">
                                    <label class="form-label-compact required-compact">
                                        <i class="bi bi-phone"></i> Telefone
                                    </label>
                                    <div class="input-with-icon-compact">
                                        <i class="bi bi-telephone-outbound input-icon-compact"></i>
                                        <input type="tel" class="form-control-compact" name="txtTELEFONE" id="txtTELEFONE"
                                               value="<?php echo htmlspecialchars($linha["TELEFONE"] ?? ''); ?>" required
                                               placeholder="912345678" maxlength="9">
                                    </div>
                                    <div class="form-hint-compact">9 dígitos (sem espaços)</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-actions-compact">
                            <button type="button" class="btn-compact btn-secondary-compact prev-step" data-prev="1">
                                <i class="bi bi-arrow-left"></i> Anterior
                            </button>
                            <button type="button" class="btn-compact btn-primary-compact next-step" data-next="3">
                                Seguinte <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- STEP 3: Foto de Perfil -->
                    <div class="step-form-compact" id="step3">
                        <div class="form-content-compact">
                            <div class="section-header-compact">
                                <div class="section-icon-compact">
                                    <i class="bi bi-image"></i>
                                </div>
                                <h3 class="section-title-compact">Foto de Perfil</h3>
                            </div>
                            
                            <div class="alert-compact alert-info-compact mb-3">
                                <i class="bi bi-info-circle"></i>
                                <div class="text-sm">
                                    <strong>Formatos:</strong> JPG, PNG, GIF • <strong>Máx.:</strong> 5MB
                                </div>
                            </div>

                            <!-- Foto Atual -->
                            <div class="image-upload-compact" id="imageUploadSection">
                                <?php if($temImagem && $imagemExiste): ?>
                                    <img src="<?php echo htmlspecialchars($caminhoImagem); ?>" 
                                         alt="Foto atual" 
                                         class="current-image-compact" id="currentImage"
                                         onerror="document.getElementById('imagePlaceholder').style.display='flex'; this.style.display='none';">
                                    <div class="image-placeholder-compact" id="imagePlaceholder" style="display: none;">
                                        <i class="bi bi-person-circle"></i>
                                    </div>
                                <?php else: ?>
                                    <div class="image-placeholder-compact" id="imagePlaceholder">
                                        <i class="bi bi-person-circle"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="image-actions-compact">
                                    <?php if($temImagem && $imagemExiste): ?>
                                        <button type="button" class="btn-compact btn-outline-compact btn-sm-compact" id="removeImageBtn">
                                            <i class="bi bi-trash"></i> Remover
                                        </button>
                                    <?php endif; ?>
                                    <label for="fotoPerfil" class="btn-compact btn-outline-compact btn-sm-compact">
                                        <i class="bi bi-upload"></i> Nova
                                        <input type="file" class="file-input" id="fotoPerfil" name="fotoPerfil" 
                                               accept="image/jpeg,image/png,image/gif" style="display: none;">
                                    </label>
                                </div>
                                
                                <p class="text-xs text-muted mt-2">Clique ou arraste uma imagem</p>
                                
                                <!-- Preview da nova imagem -->
                                <img id="imagePreview" class="current-image-compact" alt="Pré-visualização" style="display: none;">
                            </div>
                        </div>
                        
                        <div class="form-actions-compact">
                            <button type="button" class="btn-compact btn-secondary-compact prev-step" data-prev="2">
                                <i class="bi bi-arrow-left"></i> Anterior
                            </button>
                            <button type="button" class="btn-compact btn-primary-compact next-step" data-next="4">
                                Seguinte <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- STEP 4: Segurança -->
                    <div class="step-form-compact" id="step4">
                        <div class="form-content-compact">
                            <div class="section-header-compact">
                                <div class="section-icon-compact">
                                    <i class="bi bi-shield-lock"></i>
                                </div>
                                <h3 class="section-title-compact">Segurança (Opcional)</h3>
                            </div>
                            
                            <div class="alert-compact alert-info-compact mb-3">
                                <i class="bi bi-info-circle"></i>
                                <div class="text-sm">Preencha apenas se desejar alterar a senha</div>
                            </div>
                            
                            <!-- NOVA SENHA -->
                            <div class="form-group-compact">
                                <label class="form-label-compact">
                                    <i class="bi bi-key"></i> Nova Senha
                                </label>
                                <div class="input-with-icon-compact">
                                    <i class="bi bi-lock input-icon-compact"></i>
                                    <input type="password" class="form-control-compact" name="txtSENHA" id="txtSENHA"
                                           placeholder="Deixe em branco para manter"
                                           minlength="6">
                                    <button type="button" class="password-toggle-compact" data-target="txtSENHA">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <div class="password-strength-compact">
                                    <div class="password-strength-bar-compact" id="passwordStrengthBar"></div>
                                </div>
                                <div class="password-feedback-compact" id="passwordFeedback"></div>
                                <div class="form-hint-compact">Mínimo 6 caracteres</div>
                            </div>

                            <!-- CONFIRMAR SENHA -->
                            <div class="form-group-compact">
                                <label class="form-label-compact">
                                    <i class="bi bi-key-fill"></i> Confirmar Senha
                                </label>
                                <div class="input-with-icon-compact">
                                    <i class="bi bi-shield-lock input-icon-compact"></i>
                                    <input type="password" class="form-control-compact" name="txtCONFIRMAR_SENHA" id="txtCONFIRMAR_SENHA"
                                           placeholder="Confirme a nova senha"
                                           minlength="6">
                                    <button type="button" class="password-toggle-compact" data-target="txtCONFIRMAR_SENHA">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <div class="password-feedback-compact" id="confirmPasswordFeedback"></div>
                            </div>
                        </div>
                        
                        <div class="form-actions-compact">
                            <button type="button" class="btn-compact btn-secondary-compact prev-step" data-prev="3">
                                <i class="bi bi-arrow-left"></i> Anterior
                            </button>
                            <button type="button" class="btn-compact btn-primary-compact next-step" data-next="5">
                                Seguinte <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- STEP 5: Confirmação -->
                    <div class="step-form-compact" id="step5">
                        <div class="form-content-compact">
                            <div class="section-header-compact">
                                <div class="section-icon-compact">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                                <h3 class="section-title-compact">Confirmação</h3>
                            </div>
                            
                            <div class="alert-compact alert-success-compact mb-3">
                                <i class="bi bi-info-circle"></i>
                                <div class="text-sm">Verifique todas as informações antes de confirmar</div>
                            </div>

                            <div class="review-grid-compact">
                                <div class="review-card-compact">
                                    <div class="review-header-compact">
                                        <i class="bi bi-person-vcard" style="font-size: 11px;"></i>
                                        <h4 class="review-title-compact">Dados Pessoais</h4>
                                    </div>
                                    <div class="review-item-compact">
                                        <span class="review-label-compact">NIF:</span>
                                        <span class="review-value-compact" id="reviewNIF"><?php echo htmlspecialchars($linha["NIF"] ?? ''); ?></span>
                                    </div>
                                    <div class="review-item-compact">
                                        <span class="review-label-compact">Nome:</span>
                                        <span class="review-value-compact" id="reviewNOME"><?php echo htmlspecialchars($linha["NOME"] ?? ''); ?></span>
                                    </div>
                                    <div class="review-item-compact">
                                        <span class="review-label-compact">Sobrenome:</span>
                                        <span class="review-value-compact" id="reviewSOBRENOME"><?php echo htmlspecialchars($linha["SOBRENOME"] ?? ''); ?></span>
                                    </div>
                                    <div class="review-item-compact">
                                        <span class="review-label-compact">Nascimento:</span>
                                        <span class="review-value-compact" id="reviewDATA_NASCIMENTO"><?php echo htmlspecialchars($linha["DATA_NASCIMENTO"] ?? 'Não informado'); ?></span>
                                    </div>
                                </div>
                                
                                <div class="review-card-compact">
                                    <div class="review-header-compact">
                                        <i class="bi bi-telephone" style="font-size: 11px;"></i>
                                        <h4 class="review-title-compact">Contactos</h4>
                                    </div>
                                    <div class="review-item-compact">
                                        <span class="review-label-compact">Email:</span>
                                        <span class="review-value-compact" id="reviewEMAIL"><?php echo htmlspecialchars($linha["EMAIL"] ?? ''); ?></span>
                                    </div>
                                    <div class="review-item-compact">
                                        <span class="review-label-compact">Telefone:</span>
                                        <span class="review-value-compact" id="reviewTELEFONE"><?php echo htmlspecialchars($linha["TELEFONE"] ?? ''); ?></span>
                                    </div>
                                </div>
                                
                                <div class="review-card-compact">
                                    <div class="review-header-compact">
                                        <i class="bi bi-image" style="font-size: 11px;"></i>
                                        <h4 class="review-title-compact">Foto</h4>
                                    </div>
                                    <div class="review-item-compact">
                                        <span class="review-label-compact">Status:</span>
                                        <span class="review-value-compact" id="reviewFotoStatus">
                                            <?php 
                                                if($temImagem && $imagemExiste) {
                                                    echo 'Mantida';
                                                } else {
                                                    echo 'Nenhuma';
                                                }
                                            ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="review-card-compact">
                                    <div class="review-header-compact">
                                        <i class="bi bi-shield-lock" style="font-size: 11px;"></i>
                                        <h4 class="review-title-compact">Segurança</h4>
                                    </div>
                                    <div class="review-item-compact">
                                        <span class="review-label-compact">Senha:</span>
                                        <span class="review-value-compact" id="reviewSenhaStatus">Não alterada</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-actions-compact">
                            <button type="button" class="btn-compact btn-secondary-compact prev-step" data-prev="4">
                                <i class="bi bi-arrow-left"></i> Anterior
                            </button>
                            <button type="submit" class="btn-compact btn-primary-compact" id="submitBtn">
                                <i class="bi bi-check-lg"></i> Confirmar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ========== MULTI-STEP FORM ==========
            const progressSteps = document.querySelectorAll('.progress-step-compact');
            const stepCircles = document.querySelectorAll('.step-circle-compact');
            const stepForms = document.querySelectorAll('.step-form-compact');
            const progressFill = document.getElementById('progressFill');
            let currentStep = 1;
            const totalSteps = 5;
            
            // Variáveis para controle de imagem
            let imageChanged = false;
            let imageRemoved = false;
            let newImageFile = null;

            // Atualizar progress bar
            function updateProgressBar() {
                const progressPercentage = ((currentStep - 1) / (totalSteps - 1)) * 100;
                progressFill.style.width = progressPercentage + '%';

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
                        form.style.display = 'block';
                    } else {
                        form.classList.remove('active');
                        form.style.display = 'none';
                    }
                });
            }

            function goToStep(step) {
                currentStep = step;
                updateProgressBar();
                
                // Scroll suave mínimo
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }

            // Next step buttons
            document.querySelectorAll('.next-step').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const nextStep = parseInt(this.getAttribute('data-next'));
                    if (validateStep(currentStep)) {
                        updateReviewSection();
                        goToStep(nextStep);
                    }
                });
            });

            // Previous step buttons
            document.querySelectorAll('.prev-step').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
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
                        nifInput.classList.remove('is-invalid-compact');
                        nomeInput.classList.remove('is-invalid-compact');
                        sobrenomeInput.classList.remove('is-invalid-compact');
                        
                        // NIF validation (9 digits)
                        if (!nifInput.value.trim() || !/^\d{9}$/.test(nifInput.value)) {
                            nifInput.classList.add('is-invalid-compact');
                            nifInput.focus();
                            isValid = false;
                        }
                        
                        if (!nomeInput.value.trim()) {
                            nomeInput.classList.add('is-invalid-compact');
                            if (isValid) nomeInput.focus();
                            isValid = false;
                        }
                        
                        if (!sobrenomeInput.value.trim()) {
                            sobrenomeInput.classList.add('is-invalid-compact');
                            if (isValid) sobrenomeInput.focus();
                            isValid = false;
                        }
                        break;
                        
                    case 2:
                        const emailInput = document.getElementById('txtEMAIL');
                        const telefoneInput = document.getElementById('txtTELEFONE');
                        
                        // Reset validation
                        emailInput.classList.remove('is-invalid-compact');
                        telefoneInput.classList.remove('is-invalid-compact');
                        
                        // Email validation
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailInput.value.trim() || !emailRegex.test(emailInput.value)) {
                            emailInput.classList.add('is-invalid-compact');
                            emailInput.focus();
                            isValid = false;
                        }
                        
                        // Telefone validation (9 digits)
                        if (!telefoneInput.value.trim() || !/^\d{9}$/.test(telefoneInput.value)) {
                            telefoneInput.classList.add('is-invalid-compact');
                            if (isValid) telefoneInput.focus();
                            isValid = false;
                        }
                        break;

                    case 3:
                        // Validação de imagem (opcional)
                        const fileInput = document.getElementById('fotoPerfil');
                        if (fileInput && fileInput.files.length > 0) {
                            const file = fileInput.files[0];
                            const maxSize = 5 * 1024 * 1024; // 5MB
                            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                            
                            if (file.size > maxSize) {
                                alert('❌ Imagem muito grande. Máx: 5MB.');
                                fileInput.value = '';
                                isValid = false;
                            }
                            
                            if (!allowedTypes.includes(file.type)) {
                                alert('❌ Formato não permitido. Use JPG, PNG ou GIF.');
                                fileInput.value = '';
                                isValid = false;
                            }
                        }
                        break;
                        
                    case 4:
                        // Validação de senha
                        const senhaInput = document.getElementById('txtSENHA');
                        const confirmarSenhaInput = document.getElementById('txtCONFIRMAR_SENHA');
                        
                        // Reset validation
                        senhaInput.classList.remove('is-invalid-compact');
                        confirmarSenhaInput.classList.remove('is-invalid-compact');
                        
                        // Se preencheu uma senha, deve preencher a confirmação
                        if (senhaInput.value.trim()) {
                            if (senhaInput.value.length < 6) {
                                senhaInput.classList.add('is-invalid-compact');
                                senhaInput.focus();
                                isValid = false;
                            }
                            
                            if (!confirmarSenhaInput.value.trim()) {
                                confirmarSenhaInput.classList.add('is-invalid-compact');
                                if (isValid) confirmarSenhaInput.focus();
                                isValid = false;
                            }
                            
                            if (senhaInput.value !== confirmarSenhaInput.value) {
                                confirmarSenhaInput.classList.add('is-invalid-compact');
                                if (isValid) confirmarSenhaInput.focus();
                                isValid = false;
                            }
                        }
                        break;
                }
                
                return isValid;
            }

            // ========== IMAGE UPLOAD ==========
            const fileInput = document.getElementById('fotoPerfil');
            const imagePreview = document.getElementById('imagePreview');
            const removeImageBtn = document.getElementById('removeImageBtn');
            const currentImage = document.getElementById('currentImage');
            const imagePlaceholder = document.getElementById('imagePlaceholder');
            const removerFotoInput = document.getElementById('removerFoto');
            
            // Handle file input change
            if (fileInput) {
                fileInput.addEventListener('change', function(e) {
                    if (this.files && this.files[0]) {
                        handleImageSelect(this.files[0]);
                    }
                });
            }
            
            function handleImageSelect(file) {
                // Reset remove image state
                imageRemoved = false;
                removerFotoInput.value = '0';
                
                // Validate file
                const maxSize = 5 * 1024 * 1024; // 5MB
                const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                
                if (!allowedTypes.includes(file.type)) {
                    alert('❌ Formato não permitido. Use JPG, PNG ou GIF.');
                    fileInput.value = '';
                    return;
                }
                
                if (file.size > maxSize) {
                    alert('❌ Imagem muito grande. Máx: 5MB.');
                    fileInput.value = '';
                    return;
                }
                
                // Show preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                    
                    // Hide current image/placeholder
                    if (currentImage) currentImage.style.display = 'none';
                    if (imagePlaceholder) imagePlaceholder.style.display = 'none';
                    
                    // Update flags
                    imageChanged = true;
                    newImageFile = file;
                };
                reader.readAsDataURL(file);
            }
            
            // Remove image button
            if (removeImageBtn) {
                removeImageBtn.addEventListener('click', function() {
                    if (confirm('Remover foto atual?')) {
                        // Hide current image
                        if (currentImage) currentImage.style.display = 'none';
                        if (imagePlaceholder) imagePlaceholder.style.display = 'flex';
                        
                        // Clear file input and preview
                        if (fileInput) fileInput.value = '';
                        imagePreview.style.display = 'none';
                        
                        // Update flags
                        imageRemoved = true;
                        imageChanged = false;
                        newImageFile = null;
                        removerFotoInput.value = '1';
                        
                        // Update review section
                        updateReviewSection();
                    }
                });
            }
            
            // ========== PASSWORD STRENGTH ==========
            const senhaInput = document.getElementById('txtSENHA');
            const confirmarSenhaInput = document.getElementById('txtCONFIRMAR_SENHA');
            const passwordStrengthBar = document.getElementById('passwordStrengthBar');
            const passwordFeedback = document.getElementById('passwordFeedback');
            const confirmPasswordFeedback = document.getElementById('confirmPasswordFeedback');
            
            function checkPasswordStrength(password) {
                let strength = 0;
                let feedback = '';
                
                if (password.length === 0) {
                    passwordStrengthBar.className = 'password-strength-bar-compact';
                    passwordFeedback.innerHTML = '';
                    return;
                }
                
                if (password.length >= 6) strength++;
                if (password.length >= 8) strength++;
                if (/[A-Z]/.test(password)) strength++;
                if (/[0-9]/.test(password)) strength++;
                if (/[^A-Za-z0-9]/.test(password)) strength++;
                
                passwordStrengthBar.className = 'password-strength-bar-compact';
                if (strength <= 1) {
                    passwordStrengthBar.classList.add('strength-weak');
                    feedback = '<i class="bi bi-exclamation-triangle"></i> Senha fraca';
                    passwordFeedback.className = 'password-feedback-compact text-error';
                } else if (strength <= 2) {
                    passwordStrengthBar.classList.add('strength-fair');
                    feedback = '<i class="bi bi-info-circle"></i> Senha razoável';
                    passwordFeedback.className = 'password-feedback-compact text-warning';
                } else if (strength <= 3) {
                    passwordStrengthBar.classList.add('strength-good');
                    feedback = '<i class="bi bi-check-circle"></i> Senha boa';
                    passwordFeedback.className = 'password-feedback-compact text-info';
                } else {
                    passwordStrengthBar.classList.add('strength-strong');
                    feedback = '<i class="bi bi-shield-check"></i> Senha forte';
                    passwordFeedback.className = 'password-feedback-compact text-success';
                }
                
                passwordFeedback.innerHTML = feedback;
                return strength;
            }
            
            function validatePasswords() {
                const password = senhaInput.value;
                const confirmPassword = confirmarSenhaInput.value;
                
                // Reset validation
                senhaInput.classList.remove('is-invalid-compact', 'is-valid-compact');
                confirmarSenhaInput.classList.remove('is-invalid-compact', 'is-valid-compact');
                confirmPasswordFeedback.innerHTML = '';
                
                if (password.length > 0) {
                    if (password.length < 6) {
                        senhaInput.classList.add('is-invalid-compact');
                    } else {
                        senhaInput.classList.add('is-valid-compact');
                    }
                    
                    if (confirmPassword.length > 0) {
                        if (password !== confirmPassword) {
                            confirmarSenhaInput.classList.add('is-invalid-compact');
                            confirmPasswordFeedback.innerHTML = '<i class="bi bi-x-circle"></i> As senhas não coincidem';
                            confirmPasswordFeedback.className = 'password-feedback-compact text-error';
                        } else {
                            confirmarSenhaInput.classList.add('is-valid-compact');
                            confirmPasswordFeedback.innerHTML = '<i class="bi bi-check-circle"></i> Senhas coincidem';
                            confirmPasswordFeedback.className = 'password-feedback-compact text-success';
                        }
                    }
                }
                
                updateReviewSection();
            }
            
            // Password toggle visibility
            document.querySelectorAll('.password-toggle-compact').forEach(button => {
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
            
            // Password event listeners
            if (senhaInput) {
                senhaInput.addEventListener('input', function() {
                    checkPasswordStrength(this.value);
                    validatePasswords();
                });
            }
            
            if (confirmarSenhaInput) {
                confirmarSenhaInput.addEventListener('input', validatePasswords);
            }
            
            // ========== UPDATE REVIEW SECTION ==========
            function updateReviewSection() {
                // Get form values
                const nif = document.getElementById('txtNIF')?.value || 'Não informado';
                const nome = document.getElementById('txtNOME')?.value || 'Não informado';
                const sobrenome = document.getElementById('txtSOBRENOME')?.value || 'Não informado';
                const email = document.getElementById('txtEMAIL')?.value || 'Não informado';
                const telefone = document.getElementById('txtTELEFONE')?.value || 'Não informado';
                const dataNascimento = document.getElementById('txtDATA_NASCIMENTO')?.value || 'Não informado';
                const senha = document.getElementById('txtSENHA')?.value || '';
                
                // Update review elements
                document.getElementById('reviewNIF').textContent = nif;
                document.getElementById('reviewNOME').textContent = nome;
                document.getElementById('reviewSOBRENOME').textContent = sobrenome;
                document.getElementById('reviewEMAIL').textContent = email;
                document.getElementById('reviewTELEFONE').textContent = telefone;
                document.getElementById('reviewDATA_NASCIMENTO').textContent = dataNascimento || 'Não informado';
                
                // Update foto status
                const reviewFotoStatus = document.getElementById('reviewFotoStatus');
                if (imageRemoved) {
                    reviewFotoStatus.textContent = 'Removida';
                    reviewFotoStatus.style.color = 'var(--danger)';
                } else if (imageChanged) {
                    reviewFotoStatus.textContent = 'Nova';
                    reviewFotoStatus.style.color = 'var(--success)';
                } else if (<?php echo $temImagem && $imagemExiste ? 'true' : 'false'; ?>) {
                    reviewFotoStatus.textContent = 'Mantida';
                    reviewFotoStatus.style.color = 'var(--primary)';
                } else {
                    reviewFotoStatus.textContent = 'Nenhuma';
                    reviewFotoStatus.style.color = 'var(--secondary)';
                }
                
                // Update senha status
                const reviewSenhaStatus = document.getElementById('reviewSenhaStatus');
                if (senha.length > 0) {
                    reviewSenhaStatus.textContent = 'Alterada';
                    reviewSenhaStatus.style.color = 'var(--success)';
                } else {
                    reviewSenhaStatus.textContent = 'Não alterada';
                    reviewSenhaStatus.style.color = 'var(--secondary)';
                }
            }

            // Form submission
            const form = document.getElementById('userForm');
            form.addEventListener('submit', function(e) {
                // Validate all steps
                if (!validateStep(1) || !validateStep(2) || !validateStep(4)) {
                    e.preventDefault();
                    alert('⚠️ Corrija os erros no formulário antes de submeter.');
                    goToStep(1);
                } else {
                    const submitBtn = document.getElementById('submitBtn');
                    if (submitBtn) {
                        submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Salvando...';
                        submitBtn.disabled = true;
                    }
                }
            });

            // Real-time validation
            form.querySelectorAll('input, select, textarea').forEach(input => {
                if (input.type === 'file') return;
                
                input.addEventListener('blur', function() {
                    if (this.hasAttribute('required') && !this.disabled) {
                        if (!this.value.trim()) {
                            this.classList.add('is-invalid-compact');
                        } else {
                            this.classList.remove('is-invalid-compact');
                        }
                    }
                });

                input.addEventListener('input', function() {
                    if (this.value.trim()) {
                        this.classList.remove('is-invalid-compact');
                    }
                });
            });

            // Initialize
            updateProgressBar();
            setTimeout(updateReviewSection, 100);
            
            // Auto focus no primeiro campo
            setTimeout(() => {
                const firstInput = document.querySelector('input[name="txtNIF"]');
                if (firstInput) {
                    firstInput.focus();
                }
            }, 300);
        });
    </script>
</body>
</html>

<?php include('../../../layout/footer.html'); ?>