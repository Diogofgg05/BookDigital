<?php
# Impede que usuários acessem a página se não estiverem logados
include('../../../seguranca/seguranca.php');
session_start();
if(administrador_logado() == false) { header("location: /index.php"); exit; }

include('../../../layout/header.html');
include('../../../layout/navbar.php');

// Buscar dados do usuário logado
require_once("../../../conexao/conexao.php");

// Verificar se é admin ou user para buscar na tabela correta
if(isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin') {
    $userEmail = $_SESSION['user_email'];
    $select = $conexao->prepare("SELECT * FROM ADMINISTRADORES WHERE EMAIL = ?");
} else {
    $userEmail = $_SESSION['user_email'];
    $select = $conexao->prepare("SELECT * FROM UTILIZADORES WHERE EMAIL = ?");
}

$select->execute([$userEmail]);
$usuario = $select->fetch();

// Função para calcular idade
function calcularIdade($dataNascimento) {
    if(empty($dataNascimento)) return 'N/A';
    
    $hoje = new DateTime();
    $nascimento = new DateTime($dataNascimento);
    $idade = $hoje->diff($nascimento);
    return $idade->y;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil - Sistema de Biblioteca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --neutral-light: #f8f9fa;
            --neutral-medium: #e9ecef;
            --neutral-dark: #6c757d;
            --neutral-text: #212529;
            --neutral-border: #dee2e6;
            --accent-subtle: #495057;
            --card-shadow: 0 1px 3px rgba(0,0,0,0.05);
            --hover-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        
        body {
            background-color: var(--neutral-light);
            min-height: 100vh;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: var(--neutral-text);
        }
        
        .profile-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        
        .profile-card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--neutral-border);
            overflow: hidden;
        }
        
        .profile-header {
            background: white;
            padding: 2rem;
            text-align: center;
            border-bottom: 1px solid var(--neutral-border);
        }
        
        .profile-avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: var(--neutral-medium);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            border: 3px solid white;
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--neutral-text);
            box-shadow: var(--card-shadow);
        }
        
        .profile-name {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
            color: var(--neutral-text);
        }
        
        .profile-role {
            font-size: 0.85rem;
            color: var(--neutral-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .profile-badge {
            background: var(--neutral-medium);
            color: var(--neutral-text);
            padding: 0.2rem 0.6rem;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 500;
        }
        
        .profile-content {
            padding: 1.5rem;
        }
        
        .info-section {
            margin-bottom: 1.5rem;
        }
        
        .section-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--neutral-dark);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }
        
        .info-item {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            padding: 0.75rem;
            background: var(--neutral-light);
            border-radius: 8px;
            border: 1px solid var(--neutral-border);
        }
        
        .info-label {
            font-size: 0.75rem;
            color: var(--neutral-dark);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .info-label i {
            width: 14px;
            font-size: 0.8rem;
        }
        
        .info-value {
            font-size: 0.9rem;
            color: var(--neutral-text);
            font-weight: 500;
            padding-left: 1.25rem;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }
        
        .stat-item {
            text-align: center;
            padding: 0.75rem;
            background: white;
            border-radius: 8px;
            border: 1px solid var(--neutral-border);
            transition: all 0.2s ease;
        }
        
        .stat-item:hover {
            transform: translateY(-1px);
            box-shadow: var(--hover-shadow);
        }
        
        .stat-number {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--neutral-text);
            margin-bottom: 0.25rem;
        }
        
        .stat-label {
            font-size: 0.7rem;
            color: var(--neutral-dark);
            font-weight: 500;
        }
        
        .profile-actions {
            display: flex;
            gap: 0.75rem;
            justify-content: center;
            padding-top: 1.5rem;
            border-top: 1px solid var(--neutral-border);
        }
        
        .btn-profile {
            padding: 0.6rem 1.25rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
            border: 1px solid var(--neutral-border);
            cursor: pointer;
            background: white;
            color: var(--neutral-text);
        }
        
        .btn-profile:hover {
            background: var(--neutral-light);
            transform: translateY(-1px);
            box-shadow: var(--hover-shadow);
        }
        
        .btn-password {
            background: var(--neutral-text);
            color: white;
            border-color: var(--neutral-text);
        }
        
        .btn-password:hover {
            background: var(--accent-subtle);
            border-color: var(--accent-subtle);
            color: white;
        }
        
        /* Modal Styles */
        .password-modal .modal-content {
            border-radius: 12px;
            border: 1px solid var(--neutral-border);
            box-shadow: var(--hover-shadow);
        }
        
        .password-modal .modal-header {
            border-bottom: 1px solid var(--neutral-border);
            background: var(--neutral-light);
        }
        
        .password-modal .modal-title {
            font-weight: 600;
            color: var(--neutral-text);
        }
        
        .password-strength {
            height: 4px;
            border-radius: 2px;
            margin-top: 0.25rem;
            background: var(--neutral-medium);
            transition: all 0.3s ease;
        }
        
        .password-strength.weak {
            background: #dc3545;
            width: 33%;
        }
        
        .password-strength.medium {
            background: #ffc107;
            width: 66%;
        }
        
        .password-strength.strong {
            background: #198754;
            width: 100%;
        }
        
        .password-feedback {
            font-size: 0.8rem;
            margin-top: 0.25rem;
            min-height: 1rem;
        }
        
        .password-requirements {
            font-size: 0.75rem;
            color: var(--neutral-dark);
            margin-top: 0.5rem;
        }
        
        .password-requirements ul {
            padding-left: 1rem;
            margin-bottom: 0;
        }
        
        .password-requirements li {
            margin-bottom: 0.25rem;
        }
        
        .password-requirements .requirement-met {
            color: #198754;
        }
        
        .password-requirements .requirement-unmet {
            color: var(--neutral-dark);
        }
        
        /* Responsividade */
        @media (max-width: 768px) {
            .profile-container {
                margin: 1rem auto;
            }
            
            .profile-header {
                padding: 1.5rem;
            }
            
            .profile-content {
                padding: 1.25rem;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .profile-actions {
                flex-direction: column;
            }
            
            .btn-profile {
                justify-content: center;
            }
        }
        
        @media (max-width: 480px) {
            .profile-avatar {
                width: 60px;
                height: 60px;
                font-size: 1.1rem;
            }
            
            .profile-name {
                font-size: 1.1rem;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <?php if($usuario): ?>
        <!-- Cartão de Perfil Compacto -->
        <div class="profile-card">
            <!-- Cabeçalho -->
            <div class="profile-header">
                <div class="profile-avatar">
                    <?php 
                    $iniciais = '';
                    if(isset($usuario['NOME']) && isset($usuario['SOBRENOME'])) {
                        $iniciais = substr($usuario['NOME'], 0, 1) . substr($usuario['SOBRENOME'], 0, 1);
                    } else if(isset($usuario['NOME'])) {
                        $iniciais = substr($usuario['NOME'], 0, 2);
                    } else {
                        $iniciais = 'US';
                    }
                    echo strtoupper($iniciais); 
                    ?>
                </div>
                <div class="profile-name">
                    <?php 
                    if(isset($usuario['NOME']) && isset($usuario['SOBRENOME'])) {
                        echo htmlspecialchars($usuario['NOME'] . ' ' . $usuario['SOBRENOME']);
                    } else if(isset($usuario['NOME'])) {
                        echo htmlspecialchars($usuario['NOME']);
                    } else {
                        echo 'Utilizador';
                    }
                    ?>
                </div>
                <div class="profile-role">
                    <i class="bi bi-person-badge"></i>
                    <span>
                        <?php 
                        if(isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin') {
                            echo 'Administrador';
                        } else {
                            echo 'Utilizador';
                        }
                        ?>
                    </span>
                    <span class="profile-badge">Ativo</span>
                </div>
            </div>

            <!-- Conteúdo -->
            <div class="profile-content">
                <!-- Informações Pessoais -->
                <div class="info-section">
                    <div class="section-title">
                        <i class="bi bi-person"></i>
                        Informações Pessoais
                    </div>
                    <div class="info-grid">
                        <?php if(isset($usuario['NIF'])): ?>
                        <div class="info-item">
                            <div class="info-label">
                                <i class="bi bi-credit-card"></i>
                                NIF
                            </div>
                            <div class="info-value"><?php echo htmlspecialchars($usuario['NIF']); ?></div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if(isset($usuario['DATA_NASCIMENTO'])): ?>
                        <div class="info-item">
                            <div class="info-label">
                                <i class="bi bi-calendar-event"></i>
                                Data de Nascimento
                            </div>
                            <div class="info-value">
                                <?php 
                                echo date('d/m/Y', strtotime($usuario['DATA_NASCIMENTO'])); 
                                echo ' (' . calcularIdade($usuario['DATA_NASCIMENTO']) . ' anos)';
                                ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if(isset($usuario['GENERO'])): ?>
                        <div class="info-item">
                            <div class="info-label">
                                <i class="bi bi-gender-ambiguous"></i>
                                Gênero
                            </div>
                            <div class="info-value"><?php echo htmlspecialchars($usuario['GENERO']); ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Informações de Contacto -->
                <div class="info-section">
                    <div class="section-title">
                        <i class="bi bi-telephone"></i>
                        Contacto
                    </div>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="bi bi-envelope"></i>
                                E-mail
                            </div>
                            <div class="info-value"><?php echo htmlspecialchars($usuario['EMAIL']); ?></div>
                        </div>
                        
                        <?php if(isset($usuario['TELEFONE'])): ?>
                        <div class="info-item">
                            <div class="info-label">
                                <i class="bi bi-phone"></i>
                                Telefone
                            </div>
                            <div class="info-value">
                                <?php echo htmlspecialchars($usuario['TELEFONE'] ?: 'Não informado'); ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if(isset($usuario['MORADA'])): ?>
                        <div class="info-item">
                            <div class="info-label">
                                <i class="bi bi-geo-alt"></i>
                                Morada
                            </div>
                            <div class="info-value">
                                <?php echo htmlspecialchars($usuario['MORADA'] ?: 'Não informada'); ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Estatísticas -->
                <div class="info-section">
                    <div class="section-title">
                        <i class="bi bi-graph-up"></i>
                        Estatísticas
                    </div>
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-number" id="emprestimosAtivos">0</div>
                            <div class="stat-label">Empréstimos Ativos</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number" id="historicoEmprestimos">0</div>
                            <div class="stat-label">Histórico</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number" id="livrosLidos">0</div>
                            <div class="stat-label">Livros Lidos</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number" id="diasMembro">1</div>
                            <div class="stat-label">Dias Membro</div>
                        </div>
                    </div>
                </div>

                <!-- Ações -->
                <div class="profile-actions">
                    <button type="button" class="btn-profile" onclick="editarPerfil()">
                        <i class="bi bi-pencil"></i>
                        Editar Perfil
                    </button>
                    <button type="button" class="btn-profile btn-password" data-bs-toggle="modal" data-bs-target="#passwordModal">
                        <i class="bi bi-shield-lock"></i>
                        Alterar Password
                    </button>
                </div>
            </div>
        </div>
        <?php else: ?>
        <!-- Estado de erro -->
        <div class="alert alert-danger text-center">
            <i class="bi bi-exclamation-triangle me-2"></i>
            Não foi possível carregar os dados do seu perfil.
        </div>
        <?php endif; ?>
    </div>

    <!-- Modal para Alteração de Password -->
    <div class="modal fade password-modal" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="passwordModalLabel">
                        <i class="bi bi-shield-lock me-2"></i>
                        Alterar Password
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="passwordForm">
                        <div class="mb-3">
                            <label for="currentPassword" class="form-label">Password Atual</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="currentPassword" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="currentPassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">Nova Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="newPassword" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="newPassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <div class="password-strength" id="passwordStrength"></div>
                            <div class="password-feedback" id="passwordFeedback"></div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Confirmar Nova Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="confirmPassword" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="confirmPassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <div class="password-feedback" id="confirmFeedback"></div>
                        </div>
                        
                        <div class="password-requirements">
                            <small>A password deve conter:</small>
                            <ul>
                                <li id="req-length" class="requirement-unmet">Pelo menos 8 caracteres</li>
                                <li id="req-uppercase" class="requirement-unmet">Pelo menos uma letra maiúscula</li>
                                <li id="req-lowercase" class="requirement-unmet">Pelo menos uma letra minúscula</li>
                                <li id="req-number" class="requirement-unmet">Pelo menos um número</li>
                                <li id="req-special" class="requirement-unmet">Pelo menos um caractere especial</li>
                            </ul>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-dark" id="updatePasswordBtn" disabled>Atualizar Password</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Simular estatísticas
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('emprestimosAtivos').textContent = Math.floor(Math.random() * 5);
            document.getElementById('historicoEmprestimos').textContent = Math.floor(Math.random() * 20);
            document.getElementById('livrosLidos').textContent = Math.floor(Math.random() * 15);
            document.getElementById('diasMembro').textContent = Math.floor(Math.random() * 365) + 1;
            
            // Inicializar funcionalidades do modal de password
            initPasswordModal();
        });

        function editarPerfil() {
            alert('Funcionalidade de edição de perfil será implementada em breve!');
        }

        function initPasswordModal() {
            const newPasswordInput = document.getElementById('newPassword');
            const confirmPasswordInput = document.getElementById('confirmPassword');
            const currentPasswordInput = document.getElementById('currentPassword');
            const passwordStrength = document.getElementById('passwordStrength');
            const passwordFeedback = document.getElementById('passwordFeedback');
            const confirmFeedback = document.getElementById('confirmFeedback');
            const updatePasswordBtn = document.getElementById('updatePasswordBtn');
            const toggleButtons = document.querySelectorAll('.toggle-password');
            
            // Toggle para mostrar/ocultar password
            toggleButtons.forEach(button => {
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
            
            // Validar força da password
            newPasswordInput.addEventListener('input', function() {
                const password = this.value;
                const strength = checkPasswordStrength(password);
                updatePasswordStrength(strength);
                updateRequirements(password);
                validateForm();
            });
            
            // Validar confirmação de password
            confirmPasswordInput.addEventListener('input', function() {
                validatePasswordConfirmation();
                validateForm();
            });
            
            // Validar password atual
            currentPasswordInput.addEventListener('input', validateForm);
            
            // Botão de atualização
            updatePasswordBtn.addEventListener('click', updatePassword);
        }
        
        function checkPasswordStrength(password) {
            let score = 0;
            
            // Comprimento
            if (password.length >= 8) score++;
            if (password.length >= 12) score++;
            
            // Caracteres diversos
            if (/[A-Z]/.test(password)) score++;
            if (/[a-z]/.test(password)) score++;
            if (/[0-9]/.test(password)) score++;
            if (/[^A-Za-z0-9]/.test(password)) score++;
            
            return score;
        }
        
        function updatePasswordStrength(strength) {
            const passwordStrength = document.getElementById('passwordStrength');
            const passwordFeedback = document.getElementById('passwordFeedback');
            
            passwordStrength.className = 'password-strength';
            
            if (strength === 0) {
                passwordFeedback.textContent = '';
                return;
            }
            
            if (strength <= 2) {
                passwordStrength.classList.add('weak');
                passwordFeedback.textContent = 'Password fraca';
                passwordFeedback.style.color = '#dc3545';
            } else if (strength <= 4) {
                passwordStrength.classList.add('medium');
                passwordFeedback.textContent = 'Password média';
                passwordFeedback.style.color = '#ffc107';
            } else {
                passwordStrength.classList.add('strong');
                passwordFeedback.textContent = 'Password forte';
                passwordFeedback.style.color = '#198754';
            }
        }
        
        function updateRequirements(password) {
            const requirements = {
                length: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /[0-9]/.test(password),
                special: /[^A-Za-z0-9]/.test(password)
            };
            
            Object.keys(requirements).forEach(key => {
                const element = document.getElementById(`req-${key}`);
                if (requirements[key]) {
                    element.classList.remove('requirement-unmet');
                    element.classList.add('requirement-met');
                } else {
                    element.classList.remove('requirement-met');
                    element.classList.add('requirement-unmet');
                }
            });
        }
        
        function validatePasswordConfirmation() {
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const confirmFeedback = document.getElementById('confirmFeedback');
            
            if (!confirmPassword) {
                confirmFeedback.textContent = '';
                return false;
            }
            
            if (newPassword === confirmPassword) {
                confirmFeedback.textContent = 'Passwords coincidem';
                confirmFeedback.style.color = '#198754';
                return true;
            } else {
                confirmFeedback.textContent = 'Passwords não coincidem';
                confirmFeedback.style.color = '#dc3545';
                return false;
            }
        }
        
        function validateForm() {
            const currentPassword = document.getElementById('currentPassword').value;
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const updatePasswordBtn = document.getElementById('updatePasswordBtn');
            
            const isCurrentPasswordValid = currentPassword.length > 0;
            const isNewPasswordValid = newPassword.length >= 8;
            const isConfirmationValid = validatePasswordConfirmation();
            
            updatePasswordBtn.disabled = !(isCurrentPasswordValid && isNewPasswordValid && isConfirmationValid);
        }
        
        function updatePassword() {
            const currentPassword = document.getElementById('currentPassword').value;
            const newPassword = document.getElementById('newPassword').value;
            
            // Simular envio para o servidor
            console.log('Enviando dados para atualização:', {
                currentPassword,
                newPassword
            });
            
            // Simular resposta do servidor
            setTimeout(() => {
                // Fechar modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('passwordModal'));
                modal.hide();
                
                // Mostrar mensagem de sucesso
                alert('Password atualizada com sucesso!');
                
                // Limpar formulário
                document.getElementById('passwordForm').reset();
                document.getElementById('passwordStrength').className = 'password-strength';
                document.getElementById('passwordFeedback').textContent = '';
                document.getElementById('confirmFeedback').textContent = '';
                
                // Resetar requisitos
                document.querySelectorAll('.password-requirements li').forEach(li => {
                    li.classList.remove('requirement-met');
                    li.classList.add('requirement-unmet');
                });
                
            }, 1000);
        }
    </script>
</body>
</html>

<?php include('../../../layout/footer.html'); ?>