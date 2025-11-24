<?php
# Impede que usuários acessem a página se não estiverem logados
include('../../../seguranca/seguranca.php');
session_start();
if(administrador_logado() == false) {header("location: /index.php"); exit;}

include('../../../layout/header.html');
include('../../../layout/navbar.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registar Utilizador - Sistema de Biblioteca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #3498db;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --error-color: #e74c3c;
            --text-color: #2c3e50;
            --text-light: #7f8c8d;
            --border-color: #e0e6ed;
            --background: #f8fafc;
            --surface: #ffffff;
            --sidebar-bg: #2c3e50;
            --card-shadow: 0 2px 8px rgba(0,0,0,0.08);
            --hover-shadow: 0 4px 20px rgba(0,0,0,0.12);
        }

        body {
            background-color: var(--background);
            color: var(--text-color);
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
            min-height: 100vh;
            margin: 0;
        }

        .horizontal-form-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - 80px);
            padding: 2rem 1rem;
        }

        .horizontal-form {
            width: 100%;
            max-width: 1200px; /* Aumentado de 1000px para 1200px */
            background: var(--surface);
            border-radius: 16px; /* Bordas mais arredondadas */
            box-shadow: var(--card-shadow);
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .form-container {
            display: flex;
            min-height: 600px;
        }

        .form-sidebar {
            flex: 0 0 350px; /* Largura aumentada de 300px para 350px */
            background: linear-gradient(135deg, var(--sidebar-bg) 0%, #1a2530 100%); /* Gradiente mais elegante */
            color: white;
            padding: 3rem; /* Mais padding para mais espaço */
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .form-sidebar::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            opacity: 0.3;
        }

        .sidebar-content {
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .sidebar-icon {
            width: 80px; /* Aumentado de 70px */
            height: 80px; /* Aumentado de 70px */
            background: rgba(255, 255, 255, 0.15); /* Mais transparente */
            border-radius: 20px; /* Mais arredondado */
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem; /* Aumentado de 1.75rem */
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3); /* Borda mais visível */
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .sidebar-title {
            font-size: 1.6rem; /* Aumentado de 1.4rem */
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: white;
        }

        .sidebar-subtitle {
            font-size: 1rem; /* Aumentado de 0.9rem */
            opacity: 0.8;
            line-height: 1.5;
            color: rgba(255, 255, 255, 0.9);
        }

        .form-content {
            flex: 1;
            padding: 3rem; /* Mais padding para mais espaço */
            display: flex;
            flex-direction: column;
        }

        .form-header {
            margin-bottom: 2.5rem; /* Mais espaço */
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        .form-title {
            font-size: 1.8rem; /* Aumentado de 1.5rem */
            font-weight: 600;
            color: var(--primary-color);
            margin: 0;
        }

        .form-subtitle {
            color: var(--text-light);
            font-size: 1rem; /* Aumentado de 0.9rem */
            margin-top: 0.5rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr; /* Alterado para 3 colunas em vez de 2 */
            gap: 1.5rem; /* Mais espaço entre os campos */
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 0;
        }

        .form-full-width {
            grid-column: 1 / -1;
        }

        .form-label {
            display: block;
            font-size: 0.85rem; /* Ligeiramente maior */
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-label i {
            color: var(--accent-color);
            font-size: 0.95rem; /* Ligeiramente maior */
            width: 16px;
        }

        .form-control {
            width: 100%;
            padding: 0.85rem 1rem; /* Mais padding para campos mais altos */
            font-size: 0.95rem; /* Ligeiramente maior */
            border: 1px solid var(--border-color);
            border-radius: 8px; /* Mais arredondado */
            background: var(--surface);
            transition: all 0.2s ease;
            color: var(--text-color);
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.15); /* Sombra mais destacada */
        }

        .input-with-icon {
            position: relative;
        }

        .input-with-icon .form-control {
            padding-left: 2.5rem;
        }

        .input-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .password-strength {
            height: 4px;
            background: var(--border-color);
            border-radius: 2px;
            margin-top: 0.5rem;
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
        .strength-good { background: var(--accent-color); width: 75%; }
        .strength-strong { background: var(--success-color); width: 100%; }

        .password-feedback {
            font-size: 0.8rem; /* Ligeiramente maior */
            margin-top: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .text-success { color: var(--success-color); }
        .text-warning { color: var(--warning-color); }
        .text-error { color: var(--error-color); }

        .form-actions {
            margin-top: auto;
            display: flex;
            gap: 0.75rem;
            justify-content: flex-end;
            padding-top: 2rem;
            border-top: 1px solid var(--border-color);
        }

        .btn {
            padding: 0.85rem 1.75rem; /* Mais padding para botões maiores */
            border: none;
            border-radius: 8px; /* Mais arredondado */
            font-size: 0.9rem; /* Ligeiramente maior */
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            min-width: 130px; /* Ligeiramente maior */
            justify-content: center;
            font-family: inherit;
        }

        .btn-cancel {
            background: transparent;
            color: var(--text-color);
            border: 1px solid var(--border-color);
        }

        .btn-cancel:hover {
            background: #f8f9fa;
            border-color: var(--text-light);
            transform: translateY(-2px); /* Mais movimento no hover */
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .btn-submit {
            background: var(--primary-color);
            color: white;
            border: 1px solid var(--primary-color);
        }

        .btn-submit:hover {
            background: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: translateY(-2px); /* Mais movimento no hover */
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }

        /* Estados de validação */
        .form-control:valid {
            border-color: #e0e6ed;
            background: var(--surface);
        }

        .form-control:invalid:not(:placeholder-shown):not(:focus) {
            border-color: #e74c3c;
            background: #fef5f5;
        }

        .is-valid {
            border-color: var(--success-color) !important;
            background: #f8fff8 !important;
        }

        .is-invalid {
            border-color: var(--error-color) !important;
            background: #fef5f5 !important;
        }

        /* Responsividade */
        @media (max-width: 1200px) {
            .form-grid {
                grid-template-columns: 1fr 1fr; /* 2 colunas em ecrãs médios */
            }
        }

        @media (max-width: 968px) {
            .horizontal-form-container {
                padding: 1rem;
                min-height: calc(100vh - 60px);
            }
            
            .form-container {
                flex-direction: column;
                min-height: auto;
            }
            
            .form-sidebar {
                flex: none;
                padding: 2rem;
            }
            
            .form-content {
                padding: 2rem;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
        }

        @media (max-width: 576px) {
            .horizontal-form-container {
                padding: 0.5rem;
            }
            
            .horizontal-form {
                border-radius: 12px; /* Menos arredondado em ecrãs pequenos */
            }
            
            .form-sidebar,
            .form-content {
                padding: 1.5rem;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .btn {
                min-width: auto;
                width: 100%;
            }
            
            .sidebar-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }
            
            .sidebar-title {
                font-size: 1.25rem;
            }
        }

        /* Animações sutis */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-group {
            animation: fadeInUp 0.3s ease-out;
            animation-fill-mode: both;
        }

        .form-group:nth-child(1) { animation-delay: 0.1s; }
        .form-group:nth-child(2) { animation-delay: 0.15s; }
        .form-group:nth-child(3) { animation-delay: 0.2s; }
        .form-group:nth-child(4) { animation-delay: 0.25s; }
        .form-group:nth-child(5) { animation-delay: 0.3s; }
        .form-group:nth-child(6) { animation-delay: 0.35s; }
        .form-group:nth-child(7) { animation-delay: 0.4s; }
        .form-group:nth-child(8) { animation-delay: 0.45s; }

        /* Efeitos de hover minimalistas */
        .horizontal-form {
            transition: box-shadow 0.2s ease;
        }

        .horizontal-form:hover {
            box-shadow: var(--hover-shadow);
        }

        .toggle-password {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-light);
            cursor: pointer;
            font-size: 0.9rem;
        }

        .toggle-password:hover {
            color: var(--accent-color);
        }
    </style>
</head>
<body>
    <!-- A navbar é carregada aqui via PHP include -->
    
    <div class="horizontal-form-container">
        <div class="horizontal-form">
            <div class="form-container">
                <!-- Sidebar -->
                <div class="form-sidebar">
                    <div class="sidebar-content">
                        <div class="sidebar-icon">
                            <i class="bi bi-person-plus"></i>
                        </div>
                        <h3 class="sidebar-title">Novo Utilizador</h3>
                        <p class="sidebar-subtitle">Preencha os dados para registar um novo utilizador no sistema da biblioteca</p>
                    </div>
                </div>

                <!-- Conteúdo do Formulário -->
                <div class="form-content">
                    <div class="form-header">
                        <h1 class="form-title">Dados do Utilizador</h1>
                        <p class="form-subtitle">Informe os dados pessoais do novo utilizador do sistema</p>
                    </div>

                    <form action="/Api/usuarios/cadastrar.php" method="post" id="userForm">
                        <div class="form-grid">
                            <!-- NIF -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="bi bi-person-badge"></i>
                                    NIF
                                </label>
                                <div class="input-with-icon">
                                    <i class="bi bi-credit-card input-icon"></i>
                                    <input type="text" 
                                           class="form-control" 
                                           name="txtNIF" 
                                           placeholder="123 456 789"
                                           required
                                           maxlength="11">
                                </div>
                            </div>

                            <!-- Data de Nascimento -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="bi bi-calendar"></i>
                                    Data de Nascimento
                                </label>
                                <div class="input-with-icon">
                                    <i class="bi bi-calendar-date input-icon"></i>
                                    <input type="date" 
                                           class="form-control" 
                                           name="txtDATA_NASCIMENTO" 
                                           required>
                                </div>
                            </div>

                            <!-- Nome -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="bi bi-person"></i>
                                    Nome
                                </label>
                                <div class="input-with-icon">
                                    <i class="bi bi-type input-icon"></i>
                                    <input type="text" 
                                           class="form-control" 
                                           name="txtNOME" 
                                           placeholder="Primeiro nome"
                                           required>
                                </div>
                            </div>

                            <!-- Sobrenome -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="bi bi-person"></i>
                                    Sobrenome
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       name="txtSOBRENOME" 
                                       placeholder="Último nome"
                                       required>
                            </div>

                            <!-- Email -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="bi bi-envelope"></i>
                                    Email
                                </label>
                                <div class="input-with-icon">
                                    <i class="bi bi-at input-icon"></i>
                                    <input type="email" 
                                           class="form-control" 
                                           name="txtEMAIL" 
                                           placeholder="utilizador@exemplo.pt"
                                           required>
                                </div>
                            </div>

                            <!-- Telemóvel -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="bi bi-phone"></i>
                                    Telemóvel
                                </label>
                                <div class="input-with-icon">
                                    <i class="bi bi-telephone input-icon"></i>
                                    <input type="tel" 
                                           class="form-control" 
                                           name="txtTELEFONE" 
                                           placeholder="912 345 678"
                                           required>
                                </div>
                            </div>

                            <!-- Senha -->
                            <div class="form-group form-full-width">
                                <label class="form-label">
                                    <i class="bi bi-shield-lock"></i>
                                    Senha
                                </label>
                                <div class="input-with-icon">
                                    <i class="bi bi-key input-icon"></i>
                                    <input type="password" 
                                           class="form-control" 
                                           name="txtSENHA" 
                                           id="txtSENHA"
                                           placeholder="Digite uma senha segura"
                                           required
                                           minlength="6">
                                    <button type="button" class="toggle-password" data-target="txtSENHA">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <div class="password-strength">
                                    <div class="password-strength-bar" id="passwordStrengthBar"></div>
                                </div>
                                <div class="password-feedback" id="passwordFeedback"></div>
                            </div>

                            <!-- Confirmar Senha -->
                            <div class="form-group form-full-width">
                                <label class="form-label">
                                    <i class="bi bi-shield-check"></i>
                                    Confirmar Senha
                                </label>
                                <div class="input-with-icon">
                                    <i class="bi bi-key-fill input-icon"></i>
                                    <input type="password" 
                                           class="form-control" 
                                           name="txtCONFIRMAR_SENHA" 
                                           id="txtCONFIRMAR_SENHA"
                                           placeholder="Digite a senha novamente"
                                           required
                                           minlength="6">
                                    <button type="button" class="toggle-password" data-target="txtCONFIRMAR_SENHA">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <div class="password-feedback" id="confirmPasswordFeedback"></div>
                            </div>
                        </div>

                        <!-- Ações -->
                        <div class="form-actions">
                            <button type="button" class="btn btn-cancel" onclick="history.go(-1)">
                                <i class="bi bi-arrow-left"></i>
                                Voltar
                            </button>
                            <button type="submit" class="btn btn-submit" id="submitBtn">
                                <i class="bi bi-check-lg"></i>
                                Registar Utilizador
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elementos do DOM
            const form = document.getElementById('userForm');
            const submitBtn = document.getElementById('submitBtn');
            const senhaInput = document.getElementById('txtSENHA');
            const confirmarSenhaInput = document.getElementById('txtCONFIRMAR_SENHA');
            const passwordStrengthBar = document.getElementById('passwordStrengthBar');
            const passwordFeedback = document.getElementById('passwordFeedback');
            const confirmPasswordFeedback = document.getElementById('confirmPasswordFeedback');

            // Máscara para NIF (9 dígitos portugueses)
            const nifInput = document.querySelector('input[name="txtNIF"]');
            if (nifInput) {
                nifInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    value = value.substring(0, 9);
                    if (value.length > 6) {
                        value = value.replace(/(\d{3})(\d{3})(\d{3})/, '$1 $2 $3');
                    } else if (value.length > 3) {
                        value = value.replace(/(\d{3})(\d{3})/, '$1 $2');
                    }
                    e.target.value = value;
                });

                nifInput.addEventListener('blur', function() {
                    const value = this.value.replace(/\s/g, '');
                    if (value.length === 9 && /^\d+$/.test(value)) {
                        this.classList.add('is-valid');
                        this.classList.remove('is-invalid');
                    } else if (value && value.length !== 9) {
                        this.classList.add('is-invalid');
                        this.classList.remove('is-valid');
                    } else {
                        this.classList.remove('is-valid', 'is-invalid');
                    }
                });
            }

            // Máscara para telemóvel português (9 dígitos)
            const telefoneInput = document.querySelector('input[name="txtTELEFONE"]');
            if (telefoneInput) {
                telefoneInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    value = value.substring(0, 9);
                    if (value.length > 6) {
                        value = value.replace(/(\d{3})(\d{3})(\d{3})/, '$1 $2 $3');
                    } else if (value.length > 3) {
                        value = value.replace(/(\d{3})(\d{3})/, '$1 $2');
                    }
                    e.target.value = value;
                });

                telefoneInput.addEventListener('blur', function() {
                    const value = this.value.replace(/\s/g, '');
                    if (value.length === 9 && /^\d+$/.test(value)) {
                        this.classList.add('is-valid');
                        this.classList.remove('is-invalid');
                    } else if (value && value.length !== 9) {
                        this.classList.add('is-invalid');
                        this.classList.remove('is-valid');
                    } else {
                        this.classList.remove('is-valid', 'is-invalid');
                    }
                });
            }

            // Validação de email
            const emailInput = document.querySelector('input[name="txtEMAIL"]');
            if (emailInput) {
                emailInput.addEventListener('blur', function() {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (this.value && emailRegex.test(this.value)) {
                        this.classList.add('is-valid');
                        this.classList.remove('is-invalid');
                    } else if (this.value) {
                        this.classList.add('is-invalid');
                        this.classList.remove('is-valid');
                    } else {
                        this.classList.remove('is-valid', 'is-invalid');
                    }
                });
            }

            // Toggle password visibility
            document.querySelectorAll('.toggle-password').forEach(button => {
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

                // Update strength bar and feedback
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
                    passwordFeedback.className = 'password-feedback text-success';
                } else {
                    passwordStrengthBar.classList.add('strength-strong');
                    feedback = '<i class="bi bi-shield-check"></i> Senha forte';
                    passwordFeedback.className = 'password-feedback text-success';
                }

                passwordFeedback.innerHTML = feedback;
                return strength;
            }

            // Password validation
            senhaInput.addEventListener('input', function() {
                checkPasswordStrength(this.value);
                validatePasswords();
            });

            confirmarSenhaInput.addEventListener('input', validatePasswords);

            function validatePasswords() {
                const password = senhaInput.value;
                const confirmPassword = confirmarSenhaInput.value;
                let isValid = true;

                // Validate password
                if (password.length > 0) {
                    if (password.length < 6) {
                        senhaInput.classList.add('is-invalid');
                        senhaInput.classList.remove('is-valid');
                        isValid = false;
                    } else {
                        senhaInput.classList.add('is-valid');
                        senhaInput.classList.remove('is-invalid');
                    }
                } else {
                    senhaInput.classList.remove('is-valid', 'is-invalid');
                }

                // Validate password confirmation
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

                // Enable/disable submit button
                submitBtn.disabled = !isValid;
                return isValid;
            }

            // Form submission validation
            form.addEventListener('submit', function(e) {
                if (!validatePasswords()) {
                    e.preventDefault();
                    alert('Por favor, corrija os erros no formulário antes de submeter.');
                    return;
                }

                // Additional validation before submission
                const requiredFields = form.querySelectorAll('[required]');
                let allValid = true;

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        allValid = false;
                    }
                });

                if (!allValid) {
                    e.preventDefault();
                    alert('Por favor, preencha todos os campos obrigatórios.');
                }
            });

            // Foco automático no primeiro campo
            setTimeout(() => {
                const firstInput = document.querySelector('.form-control');
                if (firstInput) firstInput.focus();
            }, 400);
        });
    </script>
</body>
</html>

<?php include('../../layout/footer.html'); ?>