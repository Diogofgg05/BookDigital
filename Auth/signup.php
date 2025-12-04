<?php
// Iniciar sessão
session_start();

// Verificar se há mensagem de erro
$erro = '';
if (isset($_SESSION['register_erro'])) {
    $erro = $_SESSION['register_erro'];
    unset($_SESSION['register_erro']);
}

// Verificar se há mensagem de sucesso
$sucesso = '';
if (isset($_SESSION['register_sucesso'])) {
    $sucesso = $_SESSION['register_sucesso'];
    unset($_SESSION['register_sucesso']);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Student Portal</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #1a365d;
            --secondary-color: #2d3748;
            --accent-color: #e53e3e;
            --success-color: #38a169;
            --background-color: #f7fafc;
            --light-gray: #e2e8f0;
            --text-color: #2d3748;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #1a365d 0%, #2d3748 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            width: 100%;
            padding: 0;
        }
        
        .register-container {
            display: flex;
            width: 100%;
            height: 100vh;
            background: white;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            overflow: hidden;
        }
        
        .register-left {
            flex: 0.8;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: rgba(255, 255, 255, 0.95);
            overflow-y: auto;
        }

        .register-right {
            flex: 1.2;
            background: linear-gradient(135deg, rgba(26, 54, 93, 0.9) 0%, rgba(45, 55, 72, 0.9) 100%);
            color: white;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
        }
        
        .register-right::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('C:/Users/diogo/Documents/GitHub/BookDigital/assets/images/Background_image_auth.jpg');
            background-size: cover;
            background-position: center;
            opacity: 0.2;
            z-index: 1;
        }
        
        .register-right > * {
            position: relative;
            z-index: 2;
        }
        
        .register-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 8px;
        }
        
        .register-subtitle {
            color: #718096;
            margin-bottom: 25px;
            font-size: 1rem;
        }
        
        .welcome-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 12px;
            color: white;
        }
        
        .welcome-subtitle {
            font-size: 1rem;
            opacity: 0.9;
            line-height: 1.5;
            color: #e2e8f0;
            max-width: 250px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-row {
            display: flex;
            gap: 12px;
        }
        
        .form-row .form-group {
            flex: 1;
        }
        
        .form-label {
            display: block;
            margin-bottom: 6px;
            color: var(--secondary-color);
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .form-control {
            width: 100%;
            padding: 12px;
            border: 2px solid var(--light-gray);
            border-radius: 8px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            background: white;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            background: white;
            box-shadow: 0 0 0 3px rgba(26, 54, 93, 0.1);
        }
        
        .btn-register {
            width: 100%;
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }
        
        .btn-register:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(26, 54, 93, 0.3);
        }
        
        .login-section {
            text-align: center;
            margin-top: 20px;
            color: #718096;
            font-size: 0.9rem;
        }
        
        .login-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            margin-left: 5px;
            transition: color 0.3s ease;
        }
        
        .login-link:hover {
            text-decoration: underline;
            color: var(--secondary-color);
        }
        
        .error-message {
            background: #fed7d7;
            color: #c53030;
            padding: 10px 12px;
            border-radius: 6px;
            margin-bottom: 15px;
            border: 1px solid #feb2b2;
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        .success-message {
            background: #c6f6d5;
            color: #276749;
            padding: 10px 12px;
            border-radius: 6px;
            margin-bottom: 15px;
            border: 1px solid #9ae6b4;
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        .security-alert {
            background: rgba(26, 54, 93, 0.08);
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
            padding: 10px 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-weight: 500;
            font-size: 0.85rem;
        }
        
        .student-icon {
            font-size: 3rem;
            margin-bottom: 20px;
            opacity: 0.9;
            color: #e2e8f0;
        }
        
        @media (max-width: 768px) {
            .register-container {
                flex-direction: column;
                height: 100vh;
            }
            
            .register-left, .register-right {
                padding: 30px 25px;
                flex: none;
            }
            
            .register-right {
                order: -1;
                padding: 30px 25px;
            }
            
            .register-title {
                font-size: 1.8rem;
            }
            
            .welcome-title {
                font-size: 1.6rem;
            }
            
            .form-row {
                flex-direction: column;
                gap: 0;
            }
        }
        
        .shake {
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <!-- Left Side - Register Form -->
        <div class="register-left">
            <h1 class="register-title">Create Account</h1>
            <p class="register-subtitle">Fill in your details to create an account.</p>
            
            <div class="security-alert">
                <i class="fas fa-shield-alt me-2"></i>
                Your information is secure and encrypted
            </div>
            
            <?php if (!empty($erro)): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?php echo htmlspecialchars($erro); ?>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($sucesso)): ?>
            <div class="success-message">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo htmlspecialchars($sucesso); ?>
            </div>
            <?php endif; ?>
            
            <form action="/Api/auth/signup.php" method="post" id="registerForm">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">First Name</label>
                        <input type="text" name="txtNOME" class="form-control" placeholder="First name" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="txtSOBRENOME" class="form-control" placeholder="Last name" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">NIF</label>
                    <input type="text" name="txtNIF" class="form-control" placeholder="Your NIF" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="txtEMAIL" class="form-control" placeholder="your@email.com" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Phone</label>
                    <input type="tel" name="txtTELEFONE" class="form-control" placeholder="Phone number" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Date of Birth</label>
                    <input type="date" name="txtDATA_NASCIMENTO" class="form-control" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" name="txtSENHA" class="form-control" placeholder="Password" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="txtCONFIRMAR_SENHA" class="form-control" placeholder="Confirm password" required>
                    </div>
                </div>
                
                <button type="submit" class="btn-register">
                    <i class="fas fa-user-plus me-2"></i>Create Account
                </button>
            </form>
            
            <div class="login-section">
                Already have an account? 
                <a href="../layout/login.php" class="login-link">Sign in</a>
            </div>
        </div>
        
        <!-- Right Side - Welcome Message -->
        <div class="register-right">
            <div class="student-icon">
                <i class="fas fa-user-graduate"></i>
            </div>
            <h2 class="welcome-title">Join Our Community</h2>
            <p class="welcome-subtitle">Create your account to access all student features and resources.</p>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const registerForm = document.getElementById('registerForm');
            const inputs = document.querySelectorAll('.form-control');
            const password = document.querySelector('input[name="txtSENHA"]');
            const confirmPassword = document.querySelector('input[name="txtCONFIRMAR_SENHA"]');
            
            // Efeito de foco nos campos
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.style.borderColor = 'var(--primary-color)';
                    this.style.boxShadow = '0 0 0 3px rgba(26, 54, 93, 0.1)';
                });
                
                input.addEventListener('blur', function() {
                    if (!this.value) {
                        this.style.borderColor = 'var(--light-gray)';
                        this.style.boxShadow = 'none';
                    }
                });
            });
            
            // Validação de senha em tempo real
            confirmPassword.addEventListener('input', function() {
                if (password.value !== confirmPassword.value) {
                    confirmPassword.style.borderColor = 'var(--accent-color)';
                } else {
                    confirmPassword.style.borderColor = 'var(--success-color)';
                }
            });
            
            // Validação do formulário
            registerForm.addEventListener('submit', function(e) {
                let hasError = false;
                
                // Verificar se todos os campos estão preenchidos
                inputs.forEach(input => {
                    if (!input.value) {
                        input.classList.add('shake');
                        setTimeout(() => input.classList.remove('shake'), 500);
                        hasError = true;
                    }
                });
                
                // Verificar se as senhas coincidem
                if (password.value !== confirmPassword.value) {
                    e.preventDefault();
                    password.classList.add('shake');
                    confirmPassword.classList.add('shake');
                    setTimeout(() => {
                        password.classList.remove('shake');
                        confirmPassword.classList.remove('shake');
                    }, 500);
                    
                    // Mostrar alerta
                    alert('Passwords do not match!');
                    hasError = true;
                }
                
                if (hasError) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>