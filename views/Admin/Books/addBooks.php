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
    <title>Registar Livro - Sistema de Biblioteca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #3498db;
            --text-color: #2c3e50;
            --text-light: #7f8c8d;
            --border-color: #ecf0f1;
            --white: #ffffff;
            --light-bg: #f8f9fa;
            --success-color: #27ae60;
            --error-color: #e74c3c;
            --shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
            --shadow-md: 0 2px 4px rgba(0,0,0,0.08);
            --shadow-lg: 0 4px 12px rgba(0,0,0,0.12);
            --radius-sm: 6px;
            --radius-md: 8px;
            --radius-lg: 12px;
        }
        
        body {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-color);
            padding: 11.5px; /* AUMENTADO 15% */
        }
        
        .library-container {
            max-width: 1380px; /* AUMENTADO 15% */
            margin: 0 auto;
            padding: 11.5px; /* AUMENTADO 15% */
        }
        
        .library-title {
            font-size: 1.725rem; /* AUMENTADO 15% */
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.345rem; /* AUMENTADO 15% */
        }
        
        .library-subtitle {
            color: #7f8c8d;
            font-size: 1.035rem; /* AUMENTADO 15% */
            margin-bottom: 1.15rem; /* AUMENTADO 15% */
        }
        
        .form-wrapper {
            background: var(--white);
            border-radius: 9.2px; /* AUMENTADO 15% */
            box-shadow: 0 2.3px 6.9px rgba(0,0,0,0.092); /* AUMENTADO 15% */
            border: 1px solid var(--border-color);
            overflow: hidden;
        }
        
        .form-container {
            display: flex;
            min-height: 552px; /* AUMENTADO 15% */
        }
        
        .form-sidebar {
            flex: 0 0 230px; /* AUMENTADO 15% */
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1.38rem; /* AUMENTADO 15% */
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .book-icon-container {
            width: 160px; /* AUMENTADO 15% */
            height: 220px; /* AUMENTADO 15% */
            border-radius: 5%;
            background: rgba(255, 255, 255, 0.1);
            border: 2.3px solid rgba(255, 255, 255, 0.345); /* AUMENTADO 15% */
            margin: 0 auto 0.92rem; /* AUMENTADO 15% */
            overflow: hidden;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .book-icon {
            font-size: 2.07rem; /* AUMENTADO 15% */
            color: rgba(255, 255, 255, 0.9);
        }
        
        .cover-preview {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none;
        }
        
        .sidebar-info {
            text-align: center;
            color: rgba(255, 255, 255, 0.9);
        }
        
        .sidebar-title {
            font-size: 1.15rem; /* AUMENTADO 15% */
            font-weight: 600;
            margin-bottom: 0.46rem; /* AUMENTADO 15% */
        }
        
        .sidebar-subtitle {
            font-size: 0.805rem; /* AUMENTADO 15% */
            opacity: 0.8;
            line-height: 1.2;
        }
        
        .form-content {
            flex: 1;
            padding: 1.725rem; /* AUMENTADO 15% */
        }
        
        .form-header {
            margin-bottom: 1.15rem; /* AUMENTADO 15% */
            padding-bottom: 0.92rem; /* AUMENTADO 15% */
            border-bottom: 1px solid var(--border-color);
        }
        
        .form-title {
            font-size: 1.38rem; /* AUMENTADO 15% */
            font-weight: 600;
            color: var(--primary-color);
            margin: 0;
        }
        
        .form-subtitle {
            font-size: 0.92rem; /* AUMENTADO 15% */
            color: #7f8c8d;
            margin-top: 0.23rem; /* AUMENTADO 15% */
        }
        
        /* Layout mais compacto e horizontal */
        .compact-form-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.92rem; /* AUMENTADO 15% */
            margin-bottom: 0.92rem; /* AUMENTADO 15% */
        }
        
        .compact-form-group {
            margin-bottom: 0;
        }
        
        .form-full-width {
            grid-column: 1 / -1;
        }
        
        .form-label {
            font-size: 0.8625rem; /* AUMENTADO 15% */
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 0.23rem; /* AUMENTADO 15% */
            display: flex;
            align-items: center;
            gap: 0.345rem; /* AUMENTADO 15% */
        }
        
        .form-control {
            width: 100%;
            padding: 0.575rem 0.805rem; /* AUMENTADO 15% */
            font-size: 0.92rem; /* AUMENTADO 15% */
            border: 1px solid var(--border-color);
            border-radius: 4.6px; /* AUMENTADO 15% */
            transition: all 0.2s ease;
            line-height: 1.2;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 2.3px rgba(52, 152, 219, 0.1725); /* AUMENTADO 15% */
        }
        
        textarea.form-control {
            min-height: 80.5px; /* AUMENTADO 15% */
            resize: vertical;
            line-height: 1.3;
        }
        
        /* Fix para os selects - aumentar o padding e z-index */
        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='13.8' height='13.8' fill='%237f8c8d' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-position: right 0.805rem center; /* AUMENTADO 15% */
            background-repeat: no-repeat;
            background-size: 13.8px; /* AUMENTADO 15% */
            padding-right: 2.53rem; /* AUMENTADO 15% */
            padding-top: 0.575rem; /* AUMENTADO 15% */
            padding-bottom: 0.575rem; /* AUMENTADO 15% */
            height: auto;
            min-height: 41.4px; /* AUMENTADO 15% */
            line-height: 1.2;
        }
        
        /* Garantir que os options sejam visíveis */
        select option {
            padding: 0.575rem 0.805rem; /* AUMENTADO 15% */
            font-size: 0.92rem; /* AUMENTADO 15% */
            line-height: 1.2;
            white-space: normal;
            min-height: 1.2em;
        }
        
        .input-with-icon {
            position: relative;
        }
        
        .input-with-icon .form-control {
            padding-left: 2.07rem; /* AUMENTADO 15% */
        }
        
        .input-icon {
            position: absolute;
            left: 0.575rem; /* AUMENTADO 15% */
            top: 50%;
            transform: translateY(-50%);
            color: #7f8c8d;
            font-size: 0.8625rem; /* AUMENTADO 15% */
        }
        
        .form-actions {
            margin-top: 1.15rem; /* AUMENTADO 15% */
            display: flex;
            gap: 0.46rem; /* AUMENTADO 15% */
            justify-content: flex-end;
            padding-top: 1.15rem; /* AUMENTADO 15% */
            border-top: 1px solid var(--border-color);
        }
        
        .btn-minimal {
            padding: 0.575rem 1.15rem; /* AUMENTADO 15% */
            border: 1px solid var(--border-color);
            background: var(--light-bg);
            color: #7f8c8d;
            border-radius: 4.6px; /* AUMENTADO 15% */
            font-size: 0.92rem; /* AUMENTADO 15% */
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.345rem; /* AUMENTADO 15% */
            min-width: 103.5px; /* AUMENTADO 15% */
            justify-content: center;
            text-decoration: none;
        }
        
        .btn-minimal-submit {
            background: var(--primary-color);
            color: var(--white);
            border-color: var(--primary-color);
        }
        
        .btn-minimal:hover {
            background: var(--white);
            border-color: var(--accent-color);
        }
        
        .btn-minimal-submit:hover {
            background: var(--secondary-color);
        }
        
        .cover-upload-btn {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 0.46rem 0.92rem; /* AUMENTADO 15% */
            border-radius: 4.6px; /* AUMENTADO 15% */
            cursor: pointer;
            font-size: 0.8625rem; /* AUMENTADO 15% */
            display: inline-flex;
            align-items: center;
            gap: 0.345rem; /* AUMENTADO 15% */
            margin: 0.345rem auto; /* AUMENTADO 15% */
            transition: all 0.2s ease;
        }
        
        .cover-upload-btn:hover {
            background: rgba(255, 255, 255, 0.25);
        }
        
        .cover-requirements {
            font-size: 0.7475rem; /* AUMENTADO 15% */
            color: rgba(255, 255, 255, 0.7);
            text-align: center;
            margin-top: 0.345rem; /* AUMENTADO 15% */
        }
        
        .hidden-input {
            display: none;
        }
         
        /* Grid compacto para info do livro */
        .book-info-compact {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.92rem; /* AUMENTADO 15% */
            margin-top: 0.92rem; /* AUMENTADO 15% */
        }
        
        .book-detail {
            background: var(--light-bg);
            border-radius: 4.6px; /* AUMENTADO 15% */
            padding: 0.69rem; /* AUMENTADO 15% */
            border: 1px solid var(--border-color);
            font-size: 0.805rem; /* AUMENTADO 15% */
        }
        
        .detail-label {
            color: #7f8c8d;
            margin-bottom: 0.23rem; /* AUMENTADO 15% */
            display: block;
            font-weight: 600;
        }
        
        .detail-value {
            font-weight: 600;
            color: var(--text-color);
        }
        
       
    </style>
</head>
<body>
    <div class="library-container">
        <div class="library-header">
            <h1 class="library-title">Registar Novo Livro</h1>
            <p class="library-subtitle">Adicione um novo livro ao acervo da biblioteca</p>
        </div>
        
        <div class="form-wrapper">
            <div class="form-container">
                <div class="form-sidebar">
                    <div class="book-icon-container" id="coverPreview">
                        <div class="book-icon">
                            <i class="bi bi-book"></i>
                        </div>
                        <img id="coverImage" src="" alt="Capa do livro" class="cover-preview">
                    </div>
                    
                    <!-- Input de arquivo oculto -->
                    <input type="file" id="coverInput" name="imageUpload" accept="image/*" class="hidden-input">
                    
                    <!-- Botão de upload -->
                    
                    
                    <div class="sidebar-info">
                        <div class="sidebar-title">Informação do Livro</div>
                        <div class="sidebar-subtitle">
                            Preencha todos os campos obrigatórios para registar o livro.
                        </div>
                    </div>
                </div>
                
                <div class="form-content">
                    <div class="form-header">
                        <h1 class="form-title">Dados do Livro</h1>
                        <p class="form-subtitle">Informe os dados detalhados do novo livro</p>
                    </div>
                    
                    <form action="/Api/Books/add.php" method="post" id="livroForm" enctype="multipart/form-data">
                        <div class="compact-form-grid">
                            <!-- ISBN -->
                            <div class="compact-form-group">
                                <label class="form-label">
                                    <i class="bi bi-upc-scan"></i> ISBN *
                                </label>
                                <div class="input-with-icon">
                                    <i class="bi bi-barcode input-icon"></i>
                                    <input type="text" class="form-control" name="txtISBN" 
                                           placeholder="978-3-16-148410-0" required maxlength="17">
                                </div>
                            </div>
                            
                            <!-- Título -->
                            <div class="compact-form-group">
                                <label class="form-label">
                                    <i class="bi bi-bookmark"></i> Título *
                                </label>
                                <input type="text" class="form-control" name="txtTITULO" 
                                       placeholder="Título do livro" required>
                            </div>
                            
                            <!-- Autor -->
                            <div class="compact-form-group">
                                <label class="form-label">
                                    <i class="bi bi-person"></i> Autor *
                                </label>
                                <input type="text" class="form-control" name="txtAUTOR" 
                                       placeholder="Nome do autor" required>
                            </div>
                            
                            <!-- Editora -->
                            <div class="compact-form-group">
                                <label class="form-label">
                                    <i class="bi bi-building"></i> Editora *
                                </label>
                                <input type="text" class="form-control" name="txtEDITORA" 
                                       placeholder="Nome da editora" required>
                            </div>
                            
                            <!-- Data de Publicação -->
                            <div class="compact-form-group">
                                <label class="form-label">
                                    <i class="bi bi-calendar"></i> Data Pub. *
                                </label>
                                <input type="date" class="form-control" name="txtDATA_PUBLICACAO" 
                                       required max="<?php echo date('Y-m-d'); ?>">
                            </div>
                            
                            <!-- Categoria -->
                            <div class="compact-form-group">
                                <label class="form-label">
                                    <i class="bi bi-tags"></i> Categoria *
                                </label>
                                <select class="form-control" name="txtCATEGORIA" required>
                                    <option value="" disabled selected>Selecione...</option>
                                    <option value="Ficção">Ficção</option>
                                    <option value="Não-Ficção">Não-Ficção</option>
                                    <option value="Ciência">Ciência</option>
                                    <option value="Tecnologia">Tecnologia</option>
                                    <option value="História">História</option>
                                    <option value="Biografia">Biografia</option>
                                    <option value="Arte">Arte</option>
                                    <option value="Infantil">Infantil</option>
                                    <option value="Didático">Didático</option>
                                    <option value="Romance">Romance</option>
                                    <option value="Fantasia">Fantasia</option>
                                    <option value="Mistério">Mistério</option>
                                    <option value="Poesia">Poesia</option>
                                    <option value="Drama">Drama</option>
                                    <option value="Outros">Outros</option>
                                </select>
                            </div>
                            
                            <!-- Idioma -->
                            <div class="compact-form-group">
                                <label class="form-label">
                                    <i class="bi bi-translate"></i> Idioma *
                                </label>
                                <select class="form-control" name="txtIDIOMA" required>
                                    <option value="" disabled selected>Selecione...</option>
                                    <option value="Português">Português</option>
                                    <option value="Inglês">Inglês</option>
                                    <option value="Espanhol">Espanhol</option>
                                    <option value="Francês">Francês</option>
                                    <option value="Alemão">Alemão</option>
                                    <option value="Italiano">Italiano</option>
                                    <option value="Outro">Outro</option>
                                </select>
                            </div>
                            
                            <!-- Número de Páginas -->
                            <div class="compact-form-group">
                                <label class="form-label">
                                    <i class="bi bi-file-text"></i> Páginas *
                                </label>
                                <div class="input-with-icon">
                                    <i class="bi bi-123 input-icon"></i>
                                    <input type="number" class="form-control" name="txtPAGINAS" 
                                           placeholder="Ex: 250" min="1" max="5000" required>
                                </div>
                            </div>
                            
                            <!-- Unidades Disponíveis -->
                            <div class="compact-form-group">
                                <label class="form-label">
                                    <i class="bi bi-box"></i> Unidades *
                                </label>
                                <div class="input-with-icon">
                                    <i class="bi bi-box-seam input-icon"></i>
                                    <input type="number" class="form-control" name="txtUNIDADES" 
                                           placeholder="0" min="0" value="1" required>
                                </div>
                            </div>
                            
                            <!-- Descrição -->
                            <div class="compact-form-group form-full-width">
                                <label class="form-label">
                                    <i class="bi bi-chat-text"></i> Descrição / Sinopse
                                </label>
                                <textarea class="form-control" name="txtDESCRICAO" 
                                          placeholder="Insira uma breve descrição ou sinopse do livro..."
                                          rows="2" maxlength="500"></textarea>
                                <div class="cover-requirements" style="margin-top: 0.345rem; color: #7f8c8d;">
                                    Máximo 500 caracteres
                                </div>
                            </div>
                        </div>
                        
                        <div class="book-info-compact">
                            <div class="book-detail">
                                <span class="detail-label">Formato ISBN</span>
                                <span class="detail-value">XXX-X-XX-XXXXXX-X</span>
                            </div>
                            <div class="book-detail">
                                <span class="detail-label">Exemplo ISBN</span>
                                <span class="detail-value">978-972-0-00000-0</span>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <a href="javascript:history.go(-1)" class="btn-minimal">
                                <i class="bi bi-arrow-left"></i> Voltar
                            </a>
                            <button type="submit" class="btn-minimal btn-minimal-submit" id="submitBtn">
                                <i class="bi bi-check-lg"></i> Registar Livro
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
            // Upload de capa
            const coverInput = document.getElementById('coverInput');
            const coverPreview = document.getElementById('coverPreview');
            const coverImage = document.getElementById('coverImage');
            const uploadBtn = document.getElementById('uploadBtn');
            const bookIcon = coverPreview.querySelector('.book-icon');
            
            // Adiciona evento de clique ao botão de upload
            if (uploadBtn) {
                uploadBtn.addEventListener('click', () => coverInput.click());
            }
            
            // Adiciona evento de clique à pré-visualização da capa
            if (coverPreview) {
                coverPreview.addEventListener('click', () => coverInput.click());
            }
            
            // Adiciona evento de mudança ao input de arquivo
            if (coverInput) {
                coverInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (!file) return;
                    
                    const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                    const maxSize = 5 * 1024 * 1024;
                    
                    if (!validTypes.includes(file.type)) {
                        alert('Formato inválido. Use JPG ou PNG.');
                        this.value = '';
                        return;
                    }
                    
                    if (file.size > maxSize) {
                        alert('Ficheiro muito grande. Máx: 5MB');
                        this.value = '';
                        return;
                    }
                    
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        coverImage.src = e.target.result;
                        coverImage.style.display = 'block';
                        if (bookIcon) {
                            bookIcon.style.display = 'none';
                        }
                    };
                    reader.readAsDataURL(file);
                });
            }
            
            // Máscara para ISBN
            const isbnInput = document.querySelector('input[name="txtISBN"]');
            if (isbnInput) {
                isbnInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/[^\d-]/g, '');
                    
                    if (value.length > 3 && value.charAt(3) !== '-') {
                        value = value.substring(0, 3) + '-' + value.substring(3);
                    }
                    if (value.length > 5 && value.charAt(5) !== '-') {
                        value = value.substring(0, 5) + '-' + value.substring(5);
                    }
                    if (value.length > 8 && value.charAt(8) !== '-') {
                        value = value.substring(0, 8) + '-' + value.substring(8);
                    }
                    if (value.length > 15 && value.charAt(15) !== '-') {
                        value = value.substring(0, 15) + '-' + value.substring(15);
                    }
                    
                    value = value.substring(0, 17);
                    e.target.value = value;
                });
            }
            
            // Validação de número de páginas
            const paginasInput = document.querySelector('input[name="txtPAGINAS"]');
            if (paginasInput) {
                paginasInput.addEventListener('change', function() {
                    if (this.value < 1) {
                        this.value = 1;
                    }
                    if (this.value > 5000) {
                        this.value = 5000;
                        alert('Número de páginas muito elevado. Máximo: 5000');
                    }
                });
            }
            
            // Validação de unidades
            const unidadesInput = document.querySelector('input[name="txtUNIDADES"]');
            if (unidadesInput) {
                unidadesInput.addEventListener('change', function() {
                    if (this.value < 0) {
                        this.value = 0;
                    }
                });
            }
            
            // Foco automático no primeiro campo
            setTimeout(() => {
                if (isbnInput) isbnInput.focus();
            }, 500);
            
            // Validação do formulário
            const form = document.getElementById('livroForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    if (isbnInput && isbnInput.value.replace(/-/g, '').length < 10) {
                        e.preventDefault();
                        alert('Por favor, insira um ISBN válido (mínimo 10 dígitos)');
                        isbnInput.focus();
                        return false;
                    }
                    
                    if (paginasInput && (!paginasInput.value || paginasInput.value < 1)) {
                        e.preventDefault();
                        alert('Por favor, insira um número válido de páginas (mínimo 1)');
                        paginasInput.focus();
                        return false;
                    }
                    
                    return true;
                });
            }
        });
    </script>
</body>
</html>

<?php include('../../../layout/footer.html'); ?>