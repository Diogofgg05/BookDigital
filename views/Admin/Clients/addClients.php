<?php
include('../../../seguranca/seguranca.php');
session_start();
if(administrador_logado() == false) {
    header("location: /index.php"); 
    exit;
}
include('../../../layout/header.html');
include('../../../layout/navbar.php');
?>
<!DOCTYPE html>
<html lang="pt-pt">
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
            padding: 11px; /* AUMENTADO 10% */
        }
        
        .library-container {
            max-width: 990px; /* AUMENTADO 10% */
            margin: 0 auto;
            padding: 11px; /* AUMENTADO 10% */
        }
        
        .library-title {
            font-size: 1.65rem; /* AUMENTADO 10% */
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.33rem; /* AUMENTADO 10% */
        }
        
        .library-subtitle {
            color: #7f8c8d;
            font-size: 0.99rem; /* AUMENTADO 10% */
            margin-bottom: 1.1rem; /* AUMENTADO 10% */
        }
        
        .form-wrapper {
            background: var(--white);
            border-radius: 8.8px; /* AUMENTADO 10% */
            box-shadow: 0 2.2px 6.6px rgba(0,0,0,0.088); /* AUMENTADO 10% */
            border: 1px solid var(--border-color);
            overflow: hidden;
        }
        
        .form-container {
            display: flex;
            min-height: 440px; /* AUMENTADO 10% */
        }
        
        .form-sidebar {
            flex: 0 0 220px; /* AUMENTADO 10% */
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1.32rem; /* AUMENTADO 10% */
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .photo-preview {
            width: 99px; /* AUMENTADO 10% */
            height: 99px; /* AUMENTADO 10% */
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            border: 2.2px solid rgba(255, 255, 255, 0.33); /* AUMENTADO 10% */
            margin: 0 auto 0.88rem; /* AUMENTADO 10% */
            overflow: hidden;
            position: relative;
            cursor: pointer;
        }
        
        .photo-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none;
        }
        
        .photo-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.98rem; /* AUMENTADO 10% */
            color: rgba(255, 255, 255, 0.8);
        }
        
        .photo-upload-btn {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 0.44rem 0.88rem; /* AUMENTADO 10% */
            border-radius: 4.4px; /* AUMENTADO 10% */
            cursor: pointer;
            font-size: 0.825rem; /* AUMENTADO 10% */
            display: inline-flex;
            align-items: center;
            gap: 0.33rem; /* AUMENTADO 10% */
            margin: 0.33rem auto; /* AUMENTADO 10% */
        }
        
        .photo-requirements {
            font-size: 0.715rem; /* AUMENTADO 10% */
            color: rgba(255, 255, 255, 0.7);
            text-align: center;
            margin-top: 0.33rem; /* AUMENTADO 10% */
        }
        
        .form-content {
            flex: 1;
            padding: 1.65rem; /* AUMENTADO 10% */
        }
        
        .form-header {
            margin-bottom: 1.1rem; /* AUMENTADO 10% */
            padding-bottom: 0.88rem; /* AUMENTADO 10% */
            border-bottom: 1px solid var(--border-color);
        }
        
        .form-title {
            font-size: 1.32rem; /* AUMENTADO 10% */
            font-weight: 600;
            color: var(--primary-color);
            margin: 0;
        }
        
        .form-subtitle {
            font-size: 0.88rem; /* AUMENTADO 10% */
            color: #7f8c8d;
            margin-top: 0.22rem; /* AUMENTADO 10% */
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.88rem; /* AUMENTADO 10% */
            margin-bottom: 0.55rem; /* AUMENTADO 10% */
        }
        
        .form-group {
            margin-bottom: 0;
        }
        
        .form-full-width {
            grid-column: 1 / -1;
        }
        
        .form-label {
            font-size: 0.825rem; /* AUMENTADO 10% */
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 0.22rem; /* AUMENTADO 10% */
            display: flex;
            align-items: center;
            gap: 0.33rem; /* AUMENTADO 10% */
        }
        
        .form-control {
            width: 100%;
            padding: 0.55rem 0.77rem; /* AUMENTADO 10% */
            font-size: 0.88rem; /* AUMENTADO 10% */
            border: 1px solid var(--border-color);
            border-radius: 4.4px; /* AUMENTADO 10% */
            transition: all 0.2s ease;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 2.2px rgba(52, 152, 219, 0.165); /* AUMENTADO 10% */
        }
        
        .input-with-icon {
            position: relative;
        }
        
        .input-with-icon .form-control {
            padding-left: 1.98rem; /* AUMENTADO 10% */
        }
        
        .input-icon {
            position: absolute;
            left: 0.55rem; /* AUMENTADO 10% */
            top: 50%;
            transform: translateY(-50%);
            color: #7f8c8d;
            font-size: 0.825rem; /* AUMENTADO 10% */
        }
        
        .form-actions {
            margin-top: 1.1rem; /* AUMENTADO 10% */
            display: flex;
            gap: 0.44rem; /* AUMENTADO 10% */
            justify-content: flex-end;
            padding-top: 1.1rem; /* AUMENTADO 10% */
            border-top: 1px solid var(--border-color);
        }
        
        .btn-minimal {
            padding: 0.55rem 1.1rem; /* AUMENTADO 10% */
            border: 1px solid var(--border-color);
            background: var(--light-bg);
            color: #7f8c8d;
            border-radius: 4.4px; /* AUMENTADO 10% */
            font-size: 0.88rem; /* AUMENTADO 10% */
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.33rem; /* AUMENTADO 10% */
            min-width: 99px; /* AUMENTADO 10% */
            justify-content: center;
            text-decoration: none;
        }
        
        .btn-minimal-submit {
            background: var(--primary-color);
            color: var(--white);
            border-color: var(--primary-color);
        }
        
        .password-strength {
            height: 2.2px; /* AUMENTADO 10% */
            background: var(--border-color);
            border-radius: 1.1px; /* AUMENTADO 10% */
            margin-top: 0.22rem; /* AUMENTADO 10% */
        }
        
        .hidden-input {
            display: none;
        }
        
        @media (max-width: 768px) {
            .library-container {
                padding: 5.5px; /* AUMENTADO 10% */
                max-width: 95%;
            }
            
            .form-container {
                flex-direction: column;
                min-height: auto;
            }
            
            .form-sidebar {
                flex: none;
                padding: 1.1rem; /* AUMENTADO 10% */
            }
            
            .form-content {
                padding: 1.1rem; /* AUMENTADO 10% */
            }
            
            .form-grid {
                grid-template-columns: 1fr;
                gap: 0.66rem; /* AUMENTADO 10% */
            }
            
            .photo-preview {
                width: 88px; /* AUMENTADO 10% */
                height: 88px; /* AUMENTADO 10% */
            }
            
            .library-title {
                font-size: 1.43rem; /* AUMENTADO 10% */
            }
        }
    </style>
</head>
<body>
    <div class="library-container">
        <div class="library-header">
            <h1 class="library-title">Registar Utilizador</h1>
            <p class="library-subtitle">Adicione um novo utilizador ao sistema da biblioteca</p>
        </div>
        
        <div class="form-wrapper">
            <div class="form-container">
                <div class="form-sidebar">
                    <div class="photo-upload-container">
                        <div class="photo-preview" id="photoPreview">
                            <div class="photo-placeholder">
                                <i class="bi bi-person"></i>
                            </div>
                            <img id="photoImage" src="" alt="Foto do utilizador">
                        </div>
                        
                        <!-- ADICIONADO: Input de arquivo oculto -->
                        <input type="file" id="photoInput" name="foto_utilizador" accept="image/jpeg,image/png,image/jpg" class="hidden-input">
                        
                     
                        
        
                    </div>
                </div>
                
                <div class="form-content">
                    <div class="form-header">
                        <h1 class="form-title">Dados do Utilizador</h1>
                        <p class="form-subtitle">Informe os dados pessoais do novo utilizador</p>
                    </div>
                    
                    <form action="/Api/Users/putUsers.php" method="post" id="userForm" enctype="multipart/form-data">
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="bi bi-person"></i> Nome *
                                </label>
                                <input type="text" class="form-control" name="txtNOME" placeholder="Primeiro nome" required maxlength="50">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="bi bi-person"></i> Apelido *
                                </label>
                                <input type="text" class="form-control" name="txtSOBRENOME" placeholder="Apelido" required maxlength="50">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="bi bi-credit-card"></i> NIF *
                                </label>
                                <input type="text" class="form-control" name="txtNIF" placeholder="123456789" required maxlength="9">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="bi bi-calendar"></i> Data Nasc. *
                                </label>
                                <input type="date" class="form-control" name="txtDATA_NASCIMENTO" required max="<?php echo date('Y-m-d'); ?>">
                            </div>
                            
                            <div class="form-group form-full-width">
                                <label class="form-label">
                                    <i class="bi bi-house"></i> Morada
                                </label>
                                <input type="text" class="form-control" name="txtMORADA" placeholder="Rua, número, andar" maxlength="200">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="bi bi-envelope"></i> Email *
                                </label>
                                <input type="email" class="form-control" name="txtEMAIL" placeholder="utilizador@exemplo.pt" required maxlength="100">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="bi bi-phone"></i> Telemóvel *
                                </label>
                                <input type="tel" class="form-control" name="txtTELEFONE" placeholder="912345678" required maxlength="9">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="bi bi-shield-lock"></i> Password *
                                </label>
                                <input type="password" class="form-control" name="txtSENHA" id="txtSENHA" placeholder="Mínimo 6 caracteres" required minlength="6">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="bi bi-shield-check"></i> Confirmar *
                                </label>
                                <input type="password" class="form-control" name="txtCONFIRMAR_SENHA" id="txtCONFIRMAR_SENHA" placeholder="Repita a password" required minlength="6">
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <a href="javascript:history.go(-1)" class="btn-minimal">
                                <i class="bi bi-arrow-left"></i> Voltar
                            </a>
                            <button type="submit" class="btn-minimal btn-minimal-submit" id="submitBtn">
                                <i class="bi bi-check-lg"></i> Registar
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
            // Upload de foto
            const photoInput = document.getElementById('photoInput');
            const photoPreview = document.getElementById('photoPreview');
            const photoImage = document.getElementById('photoImage');
            const uploadBtn = document.getElementById('uploadBtn');
            
            // Adiciona evento de clique ao botão de upload
            if (uploadBtn) {
                uploadBtn.addEventListener('click', () => photoInput.click());
            }
            
            // Adiciona evento de clique à pré-visualização da foto
            if (photoPreview) {
                photoPreview.addEventListener('click', () => photoInput.click());
            }
            
            // Adiciona evento de mudança ao input de arquivo
            if (photoInput) {
                photoInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (!file) return;
                    
                    const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                    const maxSize = 2 * 1024 * 1024;
                    
                    if (!validTypes.includes(file.type)) {
                        alert('Formato inválido. Use JPG ou PNG.');
                        this.value = ''; // Limpa o input
                        return;
                    }
                    
                    if (file.size > maxSize) {
                        alert('Ficheiro muito grande. Máx: 2MB');
                        this.value = ''; // Limpa o input
                        return;
                    }
                    
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        photoImage.src = e.target.result;
                        photoImage.style.display = 'block';
                        const placeholder = photoPreview.querySelector('.photo-placeholder');
                        if (placeholder) {
                            placeholder.style.display = 'none';
                        }
                    };
                    reader.readAsDataURL(file);
                });
            }
            
            // Validação de password
            const senhaInput = document.getElementById('txtSENHA');
            const confirmarSenhaInput = document.getElementById('txtCONFIRMAR_SENHA');
            
            function validatePasswords() {
                const password = senhaInput.value;
                const confirmPassword = confirmarSenhaInput.value;
                
                if (password && confirmPassword && password !== confirmPassword) {
                    confirmarSenhaInput.style.borderColor = 'var(--error-color)';
                    return false;
                } else {
                    confirmarSenhaInput.style.borderColor = '';
                    return true;
                }
            }
            
            if (senhaInput && confirmarSenhaInput) {
                senhaInput.addEventListener('input', validatePasswords);
                confirmarSenhaInput.addEventListener('input', validatePasswords);
            }
            
            // Validação do formulário
            const form = document.getElementById('userForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    if (!validatePasswords()) {
                        e.preventDefault();
                        alert('As passwords não coincidem.');
                    }
                    // Adicione aqui outras validações se necessário
                });
            }
        });
    </script>
</body>
</html>
<?php include('../../../layout/footer.html'); ?>