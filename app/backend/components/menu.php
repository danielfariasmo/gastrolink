<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Menú</title>
    <style>
        /* Estilos generales */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f5f5f5;
        }

        /* Encabezado y navegación */
        .header {
            background-color: white;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
        }

        .left-section {
            display: flex;
            align-items: center;
            position: relative;
        }

        .logo img {
            height: 40px;
            margin-right: 20px;
        }

        .mobile-menu-toggle {
            display: flex;
            flex-direction: column;
            cursor: pointer;
            margin-right: 20px;
        }

        .mobile-menu-toggle span {
            width: 25px;
            height: 3px;
            background-color: #333;
            margin: 2px 0;
            transition: all 0.3s ease;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
            padding: 10px 0;
            min-width: 200px;
            z-index: 1001;
        }

        .mobile-menu-toggle:hover + .dropdown-menu,
        .dropdown-menu:hover {
            display: block;
        }

        .dropdown-menu ul {
            list-style: none;
        }

        .dropdown-menu li {
            padding: 8px 20px;
        }

        .dropdown-menu a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: color 0.3s ease;
            display: block;
        }

        .dropdown-menu a:hover {
            color: #ff4d4d;
        }

        /* Barra de búsqueda */
        .search-section {
            flex: 1;
            max-width: 400px;
            margin: 0 20px;
        }

        .search-container {
            position: relative;
            width: 100%;
        }

        .search-container input {
            width: 100%;
            padding: 10px 40px 10px 15px;
            border: 1px solid #ddd;
            border-radius: 20px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .search-container input:focus {
            border-color: #ff4d4d;
        }

        .search-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #777;
            cursor: pointer;
        }

        /* Botones de autenticación */
        .auth-buttons {
            display: flex;
            gap: 15px;
        }

        .btn {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn.login {
            background-color: transparent;
            color: #333;
            border: 1px solid #ddd;
        }

        .btn.login:hover {
            border-color: #ff4d4d;
            color: #ff4d4d;
        }

        .btn.register {
            background-color: #ff4d4d;
            color: white;
            border: 1px solid #ff4d4d;
        }

        .btn.register:hover {
            background-color: #e63c3c;
            border-color: #e63c3c;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .search-section {
                max-width: none;
                margin: 0 10px;
            }

            .auth-buttons .btn.login {
                display: none;
            }

            .auth-buttons .btn.register {
                padding: 6px 12px;
                font-size: 13px;
            }
        }

        @media (max-width: 576px) {
            .search-section {
                max-width: 150px;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <!-- Sección izquierda: Logo y menú hamburguesa -->
                <div class="left-section">
                    <div class="logo">
                        <a href="index.html">
                            <img src="/Proyectogastrolink/gastrolink/app/img/logo.png" alt="Logo" />
                        </a>
                    </div>
                    <div class="mobile-menu-toggle">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                    <div class="dropdown-menu">
                        <ul>
                            <li><a href="/Proyectogastrolink/gastrolink/app/frontend/html/recetas.html">Recetas</a></li>
                            <li><a href="/Proyectogastrolink/gastrolink/app/frontend/html/eventos.html">Eventos</a></li>
                            <li><a href="/Proyectogastrolink/gastrolink/app/frontend/html/restaurantes.html">Restaurantes</a></li>
                            <li><a href="/Proyectogastrolink/gastrolink/app/frontend/html/comunidad.html">Comunidad</a></li>
                            <li><a href="/Proyectogastrolink/gastrolink/app/frontend/html/empleos.html">Empleos</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Sección central: Búsqueda -->
                <div class="search-section">
                    <div class="search-container">
                        <input type="text" placeholder="Buscar..." />
                        <div class="search-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Sección derecha: Autenticación -->
                <div class="auth-buttons">
                    <a href="/Proyectogastrolink/gastrolink/app/frontend/html/iniciosesion.html" class="btn login">Iniciar Sesión</a>
                    <a href="/Proyectogastrolink/gastrolink/app/frontend/html/registro.html" class="btn register">Suscribirse</a>
                </div>
            </nav>
        </div>
    </header>

    <script>
        // Script para mantener visible el menú al hacer hover
        document.addEventListener('DOMContentLoaded', () => {
            const dropdownMenu = document.querySelector('.dropdown-menu');
            const menuToggle = document.querySelector('.mobile-menu-toggle');
            let timeoutId;

            menuToggle.addEventListener('mouseenter', () => {
                clearTimeout(timeoutId);
                dropdownMenu.style.display = 'block';
            });

            menuToggle.addEventListener('mouseleave', () => {
                timeoutId = setTimeout(() => {
                    if (!dropdownMenu.matches(':hover')) {
                        dropdownMenu.style.display = 'none';
                    }
                }, 500);
            });

            dropdownMenu.addEventListener('mouseenter', () => {
                clearTimeout(timeoutId);
            });

            dropdownMenu.addEventListener('mouseleave', () => {
                timeoutId = setTimeout(() => {
                    dropdownMenu.style.display = 'none';
                }, 900);
            });
        });
    </script>
</body>
</html>
