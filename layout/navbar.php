<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar Minimalista</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        /* Seus estilos CSS permanecem os mesmos */
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #3498db;
            --text-light: #ecf0f1;
            --text-dark: #2c3e50;
            --border-color: #bdc3c7;
            --hover-color: #ecf0f1;
            --card-shadow: 0 2px 8px rgba(0,0,0,0.08);
            --hover-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }

        .navbar-minimalista {
            background: white !important;
            border-bottom: 1px solid var(--border-color);
            box-shadow: var(--card-shadow);
            padding: 1rem 0;
            transition: all 0.3s ease;
        }

        .navbar-minimalista.scrolled {
            padding: 0.75rem 0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .navbar-brand-custom {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color) !important;
            letter-spacing: -0.5px;
            transition: all 0.3s ease;
            position: relative;
        }

        .navbar-brand-custom:hover {
            color: var(--accent-color) !important;
            transform: translateY(-1px);
        }

        .navbar-brand-custom::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--accent-color);
            transition: width 0.3s ease;
        }

        .navbar-brand-custom:hover::after {
            width: 100%;
        }

        .nav-link-custom {
            color: var(--text-dark) !important;
            font-weight: 500;
            font-size: 0.9rem;
            padding: 0.5rem 1rem !important;
            margin: 0 0.25rem;
            border-radius: 6px;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link-custom:hover {
            color: var(--accent-color) !important;
            background: var(--hover-color);
            transform: translateY(-1px);
        }

        .nav-link-custom.active {
            color: var(--accent-color) !important;
            background: rgba(52, 152, 219, 0.1);
        }

        .nav-link-custom.active::before {
            content: '';
            position: absolute;
            left: 1rem;
            right: 1rem;
            bottom: -1px;
            height: 2px;
            background: var(--accent-color);
            border-radius: 2px;
        }

        .dropdown-menu-custom {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            box-shadow: var(--hover-shadow);
            padding: 0.5rem;
            margin-top: 0.5rem !important;
            min-width: 200px;
        }

        .dropdown-item-custom {
            color: var(--text-dark);
            font-weight: 500;
            font-size: 0.85rem;
            padding: 0.75rem 1rem;
            border-radius: 6px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .dropdown-item-custom:hover {
            background: var(--hover-color);
            color: var(--accent-color);
            transform: translateX(4px);
        }

        .dropdown-item-custom i {
            width: 16px;
            font-size: 0.9rem;
        }

        .btn-logout {
            background: transparent;
            border: 1px solid var(--border-color);
            color: var(--text-dark);
            font-weight: 500;
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        .btn-logout:hover {
            background: #fee;
            border-color: #e74c3c;
            color: #e74c3c;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(231, 76, 60, 0.2);
        }

        .navbar-toggler-custom {
            border: 1px solid var(--border-color);
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
        }

        .navbar-toggler-custom:focus {
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }

        .nav-divider {
            height: 1px;
            background: var(--border-color);
            margin: 0.5rem 0;
        }

        .user-badge {
            background: var(--accent-color);
            color: white;
            font-size: 0.7rem;
            padding: 0.2rem 0.6rem;
            border-radius: 12px;
            margin-left: 0.5rem;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .dropdown-menu-custom {
            animation: fadeIn 0.2s ease;
        }

        @media (max-width: 991.98px) {
            .navbar-collapse {
                background: white;
                border-radius: 8px;
                box-shadow: var(--hover-shadow);
                padding: 1rem;
                margin-top: 1rem;
            }

            .nav-link-custom {
                margin: 0.25rem 0;
            }

            .dropdown-menu-custom {
                border: none;
                box-shadow: none;
                margin-top: 0 !important;
                padding-left: 1rem;
            }
        }
    </style>
</head>
<body>
    <?php
    // Inclua a verificação da sessão no início do arquivo
   
    $isAdmin = isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin';
    $isUser = isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'user';
    $userEmail = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : '';
    ?>

    <!-- Barra de Navegação Minimalista -->
    <nav class="navbar navbar-expand-lg navbar-minimalista fixed-top">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand navbar-brand-custom" href="<?php echo $isAdmin ? '/views/Admin/Home/home.php' : '/views/Client/Home/home.php'; ?>">
                <i class="bi bi-book me-2"></i>
                DIGITECA
            </a>

            <!-- Botão Mobile -->
            <button class="navbar-toggler navbar-toggler-custom" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Conteúdo da Navbar -->
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
                    
                    <!-- Home -->
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom active" href="<?php echo $isAdmin ? '/views/Admin/Home/home.php' : '/views/Client/Home/home.php'; ?>">
                            <i class="bi bi-house"></i>
                            Home
                        </a>
                    </li>

                    <!-- Livros -->
                    <li class="nav-item dropdown">
                        <a class="nav-link nav-link-custom dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-book"></i>
                            Livros
                        </a>
                        <div class="dropdown-menu dropdown-menu-custom">
                            <?php if ($isAdmin): ?>
                                <a class="dropdown-item dropdown-item-custom" href="/views/admin/Books/addBooks.php">
                                    <i class="bi bi-plus-circle"></i>
                                    Registar Livros
                                </a>
                            <?php endif; ?>
                            
                            <!-- Visualizar Livros separado para Admin e User -->
                            <?php if ($isAdmin): ?>
                                <a class="dropdown-item dropdown-item-custom" href="/views/Admin/Books/viewBooks.php">
                                    <i class="bi bi-eye"></i>
                                    Visualizar Livros
                                </a>
                            <?php else: ?>
                                <a class="dropdown-item dropdown-item-custom" href="/views/Client/Books/viewBooks.php">
                                    <i class="bi bi-eye"></i>
                                    Visualizar Livros
                                </a>
                            <?php endif; ?>
                        </div>
                    </li>

                    <!-- Utilizadores (APENAS ADMIN) -->
                    <?php if ($isAdmin): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link nav-link-custom dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-people"></i>
                            Utilizadores
                        </a>
                        <div class="dropdown-menu dropdown-menu-custom">
                            <a class="dropdown-item dropdown-item-custom" href="/views/Admin/Clients/addClients.php">
                                <i class="bi bi-person-plus"></i>
                                Registar Utilizadores
                            </a>
                            <a class="dropdown-item dropdown-item-custom" href="/views/Admin/Clients/viewClients.php">
                                <i class="bi bi-person-check"></i>
                                Visualizar Utilizadores
                            </a>
                        </div>
                    </li>
                    <?php endif; ?>

                    <!-- Empréstimos -->
                    <li class="nav-item dropdown">
                        <a class="nav-link nav-link-custom dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-arrow-left-right"></i>
                            Empréstimos
                        </a>
                        <div class="dropdown-menu dropdown-menu-custom">
                            <?php if ($isAdmin): ?>
                                <a class="dropdown-item dropdown-item-custom" href="/views/Admin/Loans/addLoans.php">
                                    <i class="bi bi-send-plus"></i>
                                    Emprestar Livro
                                </a>
                            <?php endif; ?>
                            
                            <!-- Meus Empréstimos separado para Admin e User -->
                            <?php if ($isAdmin): ?>
                                <a class="dropdown-item dropdown-item-custom" href="/views/Admin/Loans/viewLoans.php">
                                    <i class="bi bi-list-check"></i>
                                    Visualizar Empréstimos
                                </a>
                            <?php else: ?>
                                <a class="dropdown-item dropdown-item-custom" href="/views/User/Loans/viewLoans.php">
                                    <i class="bi bi-list-check"></i>
                                    Meus Empréstimos
                                </a>
                            <?php endif; ?>
                        </div>
                    </li>

                    <!-- Perfil do Utilizador (APENAS USER) -->
                    <?php if ($isUser): ?>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="/views/Client/Profile/ViewProfile.php">
                            <i class="bi bi-person"></i>
                            Meu Perfil
                        </a>
                    </li>
                    <?php endif; ?>

                    <!-- Informações do Utilizador -->
                    <li class="nav-item">
                        <span class="nav-link nav-link-custom" style="cursor: default;">
                            <i class="bi bi-person-circle"></i>
                            <?php echo htmlspecialchars($userEmail); ?>
                        </span>
                    </li>

                    <!-- Divisor -->
                    <li class="nav-divider d-none d-lg-block"></li>

                    <!-- Logout -->
                    <li class="nav-item">
                        <form method="POST" action="/Api/logout.php" class="d-inline">
                            <button type="submit" class="btn-logout">
                                <i class="bi bi-box-arrow-right"></i>
                                Sair
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Espaço para conteúdo -->
    <div style="padding-top: 50px;"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Efeito de scroll na navbar
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar-minimalista');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Ativar link atual
        document.addEventListener('DOMContentLoaded', function() {
            const currentPage = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-link-custom');
            
            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPage) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });

            // Controle de tempo dos dropdowns
            const dropdowns = document.querySelectorAll('.dropdown');
            let closeTimeout;
            const closeDelay = 100;

            dropdowns.forEach(dropdown => {
                dropdown.addEventListener('mouseenter', function() {
                    clearTimeout(closeTimeout);
                    
                    dropdowns.forEach(otherDropdown => {
                        if (otherDropdown !== dropdown) {
                            const otherMenu = otherDropdown.querySelector('.dropdown-menu');
                            otherMenu.classList.remove('show');
                        }
                    });
                    
                    const dropdownMenu = this.querySelector('.dropdown-menu');
                    dropdownMenu.classList.add('show');
                });
                
                dropdown.addEventListener('mouseleave', function() {
                    closeTimeout = setTimeout(() => {
                        const dropdownMenu = this.querySelector('.dropdown-menu');
                        dropdownMenu.classList.remove('show');
                    }, closeDelay);
                });

                const dropdownItems = dropdown.querySelectorAll('.dropdown-menu');
                dropdownItems.forEach(menu => {
                    menu.addEventListener('mouseenter', function() {
                        clearTimeout(closeTimeout);
                    });
                    
                    menu.addEventListener('mouseleave', function() {
                        closeTimeout = setTimeout(() => {
                            this.classList.remove('show');
                        }, closeDelay);
                    });
                });
            });

            document.addEventListener('click', function(event) {
                if (!event.target.closest('.dropdown')) {
                    dropdowns.forEach(dropdown => {
                        const dropdownMenu = dropdown.querySelector('.dropdown-menu');
                        dropdownMenu.classList.remove('show');
                    });
                }
            });
        });
    </script>
</body>
</html>