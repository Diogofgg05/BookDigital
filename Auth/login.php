<?php
// Iniciar sessão
session_start();

// Verificar se há mensagem de erro
$erro = '';
if (isset($_SESSION['login_erro'])) {
    $erro = $_SESSION['login_erro'];
    unset($_SESSION['login_erro']);
}

// Limpar dados da sessão (se necessário)
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Student Portal</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #1a365d;
            --secondary-color: #2d3748;
            --accent-color: #e53e3e;
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
            background: linear-gradient(rgba(26, 54, 93, 0.8), rgba(26, 54, 93, 0.8)), 
                        url('C:/Users/diogo/Documents/GitHub/BookDigital/assets/images/Background_image_auth.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            display: flex;
            max-width: 1000px;
            width: 100%;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            overflow: hidden;
            min-height: 600px;
            backdrop-filter: blur(10px);
        }
        
        .login-left {
            flex: 1;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: rgba(255, 255, 255, 0.95);
        }
        
        .login-right {
            flex: 1;
            background: linear-gradient(135deg, rgba(26, 54, 93, 0.9) 0%, rgba(45, 55, 72, 0.9) 100%);
            color: white;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
        }
        
        .login-right::before {
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
        
        .login-right > * {
            position: relative;
            z-index: 2;
        }
        
        .login-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        
        .login-subtitle {
            color: #718096;
            margin-bottom: 30px;
            font-size: 1.1rem;
        }
        
        .welcome-title {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: white;
        }
        
        .welcome-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            line-height: 1.6;
            color: #e2e8f0;
            max-width: 300px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            color: var(--secondary-color);
            font-weight: 600;
            font-size: 0.95rem;
        }
        
        .form-control {
            width: 100%;
            padding: 15px;
            border: 2px solid var(--light-gray);
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            background: white;
            box-shadow: 0 0 0 3px rgba(26, 54, 93, 0.1);
        }
        
        .forgot-password {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-block;
            margin-top: 8px;
            transition: color 0.3s ease;
        }
        
        .forgot-password:hover {
            text-decoration: underline;
            color: var(--secondary-color);
        }
        
        .btn-login {
            width: 100%;
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 15px;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }
        
        .btn-login:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(26, 54, 93, 0.3);
        }
        
        .signup-section {
            text-align: center;
            margin-top: 30px;
            color: #718096;
            font-size: 0.95rem;
        }
        
        .signup-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            margin-left: 5px;
            transition: color 0.3s ease;
        }
        
        .signup-link:hover {
            text-decoration: underline;
            color: var(--secondary-color);
        }
        
        .error-message {
            background: #fed7d7;
            color: #c53030;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #feb2b2;
            font-weight: 500;
        }
        
        .security-alert {
            background: rgba(26, 54, 93, 0.08);
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        .student-icon {
            font-size: 4rem;
            margin-bottom: 30px;
            opacity: 0.9;
            color: #e2e8f0;
        }
        
        .university-badge {
            position: absolute;
            top: 20px;
            left: 20px;
            color: white;
            font-size: 0.8rem;
            opacity: 0.8;
        }
        
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                max-width: 450px;
                margin: 20px;
            }
            
            .login-left, .login-right {
                padding: 40px 30px;
            }
            
            .login-right {
                order: -1;
                padding: 40px 30px;
            }
            
            .login-title {
                font-size: 2rem;
            }
            
            .welcome-title {
                font-size: 1.8rem;
            }
            
            body {
                background-attachment: scroll;
                padding: 10px;
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
        
        /* Efeito de glassmorphism para o container */
        .login-container {
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Left Side - Login Form -->
        <div class="login-left">
            <h1 class="login-title">Login</h1>
            <p class="login-subtitle">Enter your account details.</p>
            
            <div class="security-alert">
                <i class="fas fa-shield-alt me-2"></i>
                Acesso permitido somente para utilizadores autorizados
            </div>
            
            <?php if (!empty($erro)): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?php echo htmlspecialchars($erro); ?>
            </div>
            <?php endif; ?>
            
            <form action="/Api/auth/loginDB.php" method="post" id="loginForm">
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="text" name="txtEmailLogin" class="form-control" placeholder="Enter your email" required autofocus>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="txtSenhaLogin" class="form-control" placeholder="Enter your password" required>
                    <a href="#" class="forgot-password">
                        <i class="fas fa-key me-1"></i>Forgot Password?
                    </a>
                </div>
                
                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i>Login
                </button>
            </form>
            
            <div class="signup-section">
                Don't have an account? 
                <a href="../layout/signup.php" class="signup-link">Sign up</a>
            </div>
        </div>
        
        <!-- Right Side - Welcome Message -->
        <div class="login-right">
            <div class="student-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <h2 class="welcome-title">Welcome to student portal</h2>
            <p class="welcome-subtitle">Login to access your student account and manage your academic information.</p>
         
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');
            const inputs = document.querySelectorAll('.form-control');
            
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
            
            // Validação do formulário
            loginForm.addEventListener('submit', function(e) {
                const username = document.querySelector('input[name="txtEmailLogin"]');
                const password = document.querySelector('input[name="txtSenhaLogin"]');
                
                if (!username.value || !password.value) {
                    e.preventDefault();
                    
                    if (!username.value) {
                        username.classList.add('shake');
                        setTimeout(() => username.classList.remove('shake'), 500);
                    }
                    if (!password.value) {
                        password.classList.add('shake');
                        setTimeout(() => password.classList.remove('shake'), 500);
                    }
                }
            });
        });
    </script>
</body>
</html>