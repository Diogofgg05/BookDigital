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
    <title>Registar Livro • Biblioteca</title>
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
            max-width: 1200px;
            background: var(--surface);
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .form-container {
            display: flex;
            min-height: 600px;
        }

        .form-sidebar {
            flex: 0 0 350px;
            background: linear-gradient(135deg, var(--sidebar-bg) 0%, #1a2530 100%);
            color: white;
            padding: 3rem;
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
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .sidebar-title {
            font-size: 1.6rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: white;
        }

        .sidebar-subtitle {
            font-size: 1rem;
            opacity: 0.8;
            line-height: 1.5;
            color: rgba(255, 255, 255, 0.9);
        }

        .form-content {
            flex: 1;
            padding: 3rem;
            display: flex;
            flex-direction: column;
        }

        .form-header {
            margin-bottom: 2.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        .form-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--primary-color);
            margin: 0;
        }

        .form-subtitle {
            color: var(--text-light);
            font-size: 1rem;
            margin-top: 0.5rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
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
            font-size: 0.85rem;
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
            font-size: 0.95rem;
            width: 16px;
        }

        .form-control {
            width: 100%;
            padding: 0.85rem 1rem;
            font-size: 0.95rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: var(--surface);
            transition: all 0.2s ease;
            color: var(--text-color);
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.15);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 120px;
            line-height: 1.5;
            padding: 1rem;
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23718096' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-position: right 1rem center;
            background-repeat: no-repeat;
            background-size: 12px;
            padding-right: 2.5rem;
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

        .form-actions {
            margin-top: auto;
            display: flex;
            gap: 0.75rem;
            justify-content: flex-end;
            padding-top: 2rem;
            border-top: 1px solid var(--border-color);
        }

        .btn {
            padding: 0.85rem 1.75rem;
            border: none;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            min-width: 130px;
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
            transform: translateY(-2px);
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
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }

        /* Upload Section */
        .upload-section {
            margin-bottom: 1.5rem;
        }

        .upload-area-refined {
            border: 2px dashed var(--border-color);
            border-radius: 12px;
            padding: 2rem;
            background: var(--background);
            transition: all 0.3s ease;
            cursor: pointer;
            text-align: center;
            margin-bottom: 1rem;
        }

        .upload-area-refined:hover {
            border-color: var(--accent-color);
            background: #f0f8ff;
        }

        .upload-icon {
            font-size: 2rem;
            color: var(--text-light);
            margin-bottom: 1rem;
            opacity: 0.7;
        }

        .upload-text {
            font-size: 1rem;
            font-weight: 500;
            color: var(--text-color);
            margin-bottom: 0.5rem;
        }

        .upload-subtext {
            font-size: 0.8rem;
            color: var(--text-light);
        }

        .image-preview {
            width: 100%;
            max-width: 180px;
            margin: 0 auto;
            border-radius: 8px;
            overflow: hidden;
            display: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .image-preview img {
            width: 100%;
            height: auto;
            display: block;
        }

        .file-input {
            display: none;
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
                grid-template-columns: 1fr 1fr;
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
                border-radius: 12px;
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
                            <i class="bi bi-book"></i>
                        </div>
                        <h3 class="sidebar-title">Novo Livro</h3>
                        <p class="sidebar-subtitle">Preencha os dados para registar um novo livro no acervo da biblioteca</p>
                    </div>
                </div>

                <!-- Conteúdo do Formulário -->
                <div class="form-content">
                    <div class="form-header">
                        <h1 class="form-title">Dados do Livro</h1>
                        <p class="form-subtitle">Informe os dados do novo livro a ser adicionado ao sistema</p>
                    </div>

                    <form action="/Api/livros/cadastrar.php" method="post" id="livroForm" enctype="multipart/form-data">
                        <div class="form-grid">
                            <!-- ISBN -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="bi bi-upc-scan"></i>
                                    ISBN
                                </label>
                                <div class="input-with-icon">
                                    <i class="bi bi-barcode input-icon"></i>
                                    <input type="text" 
                                           class="form-control" 
                                           name="txtISBN" 
                                           placeholder="978-3-16-148410-0"
                                           required
                                           maxlength="17">
                                </div>
                            </div>

                            <!-- Data de Publicação -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="bi bi-calendar"></i>
                                    Data de Publicação
                                </label>
                                <div class="input-with-icon">
                                    <i class="bi bi-calendar-date input-icon"></i>
                                    <input type="date" 
                                           class="form-control" 
                                           name="txtDATA_PUBLICACAO" 
                                           required>
                                </div>
                            </div>

                            <!-- Título -->
                            <div class="form-group form-full-width">
                                <label class="form-label">
                                    <i class="bi bi-bookmark"></i>
                                    Título do Livro
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       name="txtTITULO" 
                                       placeholder="Introduza o título completo do livro"
                                       required>
                            </div>

                            <!-- Autor -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="bi bi-person"></i>
                                    Autor
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       name="txtAUTOR" 
                                       placeholder="Nome do autor"
                                       required>
                            </div>

                            <!-- Editora -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="bi bi-building"></i>
                                    Editora
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       name="txtEDITORA" 
                                       placeholder="Nome da editora"
                                       required>
                            </div>

                            <!-- Categoria -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="bi bi-tags"></i>
                                    Categoria
                                </label>
                                <select class="form-control" name="txtCATEGORIA" required>
                                    <option value="" disabled selected>Seleccione uma categoria</option>
                                    <option value="Ficção">Ficção</option>
                                    <option value="Não-Ficção">Não-Ficção</option>
                                    <option value="Ciência">Ciência</option>
                                    <option value="Tecnologia">Tecnologia</option>
                                    <option value="História">História</option>
                                    <option value="Biografia">Biografia</option>
                                    <option value="Arte">Arte</option>
                                    <option value="Infantil">Infantil</option>
                                    <option value="Didático">Didático</option>
                                    <option value="Outros">Outros</option>
                                </select>
                            </div>

                            <!-- Unidades Disponíveis -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="bi bi-box"></i>
                                    Unidades
                                </label>
                                <div class="input-with-icon">
                                    <i class="bi bi-123 input-icon"></i>
                                    <input type="number" 
                                           class="form-control" 
                                           name="txtUNIDADES" 
                                           placeholder="0"
                                           min="0"
                                           value="1"
                                           required>
                                </div>
                            </div>

                            <!-- Descrição -->
                            <div class="form-group form-full-width">
                                <label class="form-label">
                                    <i class="bi bi-text-paragraph"></i>
                                    Descrição
                                </label>
                                <textarea class="form-control" 
                                          name="txtDESCRICAO" 
                                          placeholder="Descreva brevemente o livro ou inclua uma sinopse..."
                                          rows="4"
                                          maxlength="500"></textarea>
                            </div>

                            <!-- Upload de Capa -->
                            <div class="form-group form-full-width upload-section">
                                <label class="form-label">
                                    <i class="bi bi-image"></i>
                                    Capa do Livro
                                </label>
                                
                                <div class="upload-area-refined" id="uploadArea">
                                    <div class="image-preview" id="imagePreview">
                                        <img id="previewImage" src="" alt="Capa do livro">
                                    </div>
                                    <div class="upload-content" id="uploadContent">
                                        <div class="upload-icon">
                                            <i class="bi bi-cloud-arrow-up"></i>
                                        </div>
                                        <div class="upload-text">Adicionar Capa</div>
                                        <div class="upload-subtext">Arraste ou clique para fazer upload</div>
                                    </div>
                                    <input type="file" id="imageUpload" name="imageUpload" accept="image/*" class="file-input">
                                </div>
                                
                                <div class="upload-subtext">
                                    Formatos suportados: JPG, PNG • Tamanho máximo: 5MB
                                </div>
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
                                Registar Livro
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
            // Máscara para ISBN
            const isbnInput = document.querySelector('input[name="txtISBN"]');
            if (isbnInput) {
                isbnInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/[^\d-]/g, '');
                    value = value.replace(/-+/g, '-');
                    value = value.substring(0, 17);
                    e.target.value = value;
                });
            }

            // Upload de imagem
            const imageUpload = document.getElementById('imageUpload');
            const uploadArea = document.getElementById('uploadArea');
            const imagePreview = document.getElementById('imagePreview');
            const previewImage = document.getElementById('previewImage');
            const uploadContent = document.getElementById('uploadContent');

            uploadArea.addEventListener('click', function() {
                imageUpload.click();
            });

            imageUpload.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    
                    reader.addEventListener('load', function() {
                        previewImage.src = reader.result;
                        imagePreview.style.display = 'block';
                        uploadContent.style.display = 'none';
                        uploadArea.style.borderStyle = 'solid';
                        uploadArea.style.borderColor = 'var(--success-color)';
                        uploadArea.style.background = 'var(--background)';
                    });
                    
                    reader.readAsDataURL(file);
                }
            });

            // Drag and drop
            uploadArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.style.borderColor = 'var(--accent-color)';
                this.style.background = '#f0f8ff';
            });

            uploadArea.addEventListener('dragleave', function() {
                if (uploadContent.style.display !== 'none') {
                    this.style.borderColor = 'var(--border-color)';
                    this.style.background = 'var(--background)';
                }
            });

            uploadArea.addEventListener('drop', function(e) {
                e.preventDefault();
                if (e.dataTransfer.files.length) {
                    imageUpload.files = e.dataTransfer.files;
                    const event = new Event('change');
                    imageUpload.dispatchEvent(event);
                }
            });

            // Corrigir selects
            const selects = document.querySelectorAll('select');
            selects.forEach(select => {
                select.addEventListener('change', function() {
                    if (this.value !== '') {
                        this.style.color = 'var(--text-color)';
                    }
                });
            });

            // Definir data máxima como hoje para a data de publicação
            const dataInput = document.querySelector('input[name="txtDATA_PUBLICACAO"]');
            if (dataInput) {
                const today = new Date().toISOString().split('T')[0];
                dataInput.max = today;
            }

            // Foco automático
            setTimeout(() => {
                const firstInput = document.querySelector('input[name="txtISBN"]');
                if (firstInput) firstInput.focus();
            }, 500);
        });
    </script>
</body>
</html>

<?php include('../../../layout/footer.html'); ?>