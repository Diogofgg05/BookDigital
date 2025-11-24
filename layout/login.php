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
    <title>Login - Digiteca</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #7f8c8d;
            --accent-color: #e74c3c;
            --background-color: #f8f9fa;
            --card-shadow: 0 10px 30px rgba(0,0,0,0.1);
            --success-color: #27ae60;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(rgba(44, 62, 80, 0.8), rgba(44, 62, 80, 0.8)), 
                        url('../assets/images/Background_image_auth.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            width: 100%;
            max-width: 420px;
            margin: 0 auto;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            border: none;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            backdrop-filter: blur(10px);
        }
        
        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        }
        
        .card-header {
            background: var(--primary-color);
            color: white;
            text-align: center;
            padding: 2rem 1rem;
            border-bottom: none;
        }
        
        .logo-container {
            margin-bottom: 1rem;
        }
        
        .logo {
            max-width: 280px;
            height: auto;
            filter: brightness(0) invert(1);
        }
        
        .alert-custom {
            background: rgba(231, 76, 60, 0.1);
            border: 1px solid var(--accent-color);
            color: var(--accent-color);
            border-radius: 10px;
            font-weight: 500;
            margin-bottom: 1.5rem;
        }
        
        .alert-success-custom {
            background: rgba(39, 174, 96, 0.1);
            border: 1px solid var(--success-color);
            color: var(--success-color);
            border-radius: 10px;
            font-weight: 500;
            margin-bottom: 1.5rem;
        }
        
        .form-container {
            padding: 2.5rem;
        }
        
        .form-label-group {
            position: relative;
            margin-bottom: 1.5rem;
        }
        
        .form-control {
            height: 50px;
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
        }
        
        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(127, 140, 141, 0.25);
            background: white;
        }
        
        .form-label {
            position: absolute;
            top: 50%;
            left: 1rem;
            transform: translateY(-50%);
            background: white;
            padding: 0 0.5rem;
            color: #6c757d;
            transition: all 0.3s ease;
            pointer-events: none;
        }
        
        .form-control:focus + .form-label,
        .form-control:not(:placeholder-shown) + .form-label {
            top: 0;
            font-size: 0.8rem;
            color: var(--secondary-color);
            font-weight: 500;
            background: rgba(255, 255, 255, 0.95);
        }
        
        .btn-login {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            height: 50px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            margin-top: 1rem;
            color: white;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(44, 62, 80, 0.4);
            color: white;
        }
        
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .form-check-input:checked {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .forgot-password {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .forgot-password:hover {
            color: var(--primary-color);
            text-decoration: underline;
        }
        
        .copyright {
            margin-top: 2rem;
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .shake {
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        /* Responsividade */
        @media (max-width: 576px) {
            .login-container {
                max-width: 100%;
            }
            
            .form-container {
                padding: 2rem 1.5rem;
            }
            
            .form-options {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
            
            body {
                background-attachment: scroll;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="card-header">
               <div class="logo-container">
    <img src="../assets/images/digiteca_.png" alt="Digiteca" class="logo" style="filter: none !important;">
</div>
                <div class="alert alert-custom" role="alert">
                    <i class="fas fa-shield-alt me-2"></i>
                    Acesso permitido somente para utilizadores autorizados
                </div>
                
                <?php if (!empty($erro)): ?>
                <div class="alert alert-custom mt-2" role="alert" id="error-alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?php echo htmlspecialchars($erro); ?>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="form-container">
                <form class="form-signin" action="Api/login/loginDB.php" method="post" id="loginForm">
                    <div class="form-label-group">
                        <input type="email" name="txtEmailLogin" class="form-control" placeholder=" " required autofocus>
                        <label class="form-label">
                            <i class="fas fa-envelope me-2"></i>Email
                        </label>
                    </div>

                    <div class="form-label-group">
                        <input type="password" name="txtSenhaLogin" class="form-control" placeholder=" " required>
                        <label class="form-label">
                            <i class="fas fa-lock me-2"></i>Senha
                        </label>
                    </div>
                    
                    <div class="form-options">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="lembrar-me">
                            <label class="form-check-label" for="lembrar-me">
                                Lembrar-me
                            </label>
                        </div>
                        <a href="#" class="forgot-password">
                            <i class="fas fa-key me-1"></i>Esqueci minha senha
                        </a>
                    </div>

                    <button class="btn btn-login btn-primary w-100" type="submit">
                        <i class="fas fa-sign-in-alt me-2"></i>Entrar
                    </button>
                    
                    <div class="copyright text-center">
                        <p class="mb-0">Copyright &copy; DIGITECA <?php echo date('Y')?></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.form-control');
            const loginForm = document.getElementById('loginForm');
            const errorAlert = document.getElementById('error-alert');
            
            // Efeito de foco nos campos
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });
                
                input.addEventListener('blur', function() {
                    if (!this.value) {
                        this.parentElement.classList.remove('focused');
                    }
                });
                
                if (input.value) {
                    input.parentElement.classList.add('focused');
                }
            });
            
            // Efeito de shake se houver erro
            if (errorAlert) {
                loginForm.classList.add('shake');
                setTimeout(() => {
                    loginForm.classList.remove('shake');
                }, 500);
            }
            
            // Validação básica do formulário
            loginForm.addEventListener('submit', function(e) {
                const email = document.querySelector('input[name="txtEmailLogin"]');
                const password = document.querySelector('input[name="txtSenhaLogin"]');
                
                if (!email.value || !password.value) {
                    e.preventDefault();
                    if (!email.value) {
                        email.parentElement.classList.add('shake');
                        setTimeout(() => {
                            email.parentElement.classList.remove('shake');
                        }, 500);
                    }
                    if (!password.value) {
                        password.parentElement.classList.add('shake');
                        setTimeout(() => {
                            password.parentElement.classList.remove('shake');
                        }, 500);
                    }
                }
            });
        });
    </script>
</body>
</html>