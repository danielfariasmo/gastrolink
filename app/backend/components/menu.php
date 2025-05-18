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

        .mobile-menu-toggle:hover+.dropdown-menu,
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

        /* ################ PRUEBASSSS #####################*/
        /* Estilos para el menú de usuario */
        .auth-section {
            position: relative;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 10px;
            position: relative;
        }

        .user-greeting {
            display: flex;
            flex-direction: column;
            font-size: 12px;
            text-align: right;
        }

        #username-display {
            font-weight: bold;
            font-size: 14px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            cursor: pointer;
        }

        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-dropdown {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background-color: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
            padding: 10px 0;
            min-width: 150px;
            z-index: 1001;
        }

        .user-dropdown ul {
            list-style: none;
        }

        .user-dropdown li {
            padding: 8px 20px;
        }

        .user-dropdown a {
            text-decoration: none;
            color: #333;
            font-size: 14px;
            transition: color 0.3s ease;
            display: block;
        }

        .user-dropdown a:hover {
            color: #ff4d4d;
        }

        .user-menu:hover .user-dropdown {
            display: block;
        }

        /*Search*/
        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            max-width: 400px;
            background-color: #fff;
            box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.1);
            z-index: 1001;
            display: none;
            padding: 10px;
            border-radius: 5px;
            max-height: 300px;
            overflow-y: auto;
        }

        .result-item {
            padding: 8px;
            cursor: pointer;
            border-bottom: 1px solid #ddd;
        }

        .result-item:hover {
            background-color: #f5f5f5;
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
                            <img src="/gastrolink/app/img/menu/logo.png" alt="Logo" />
                        </a>
                    </div>
                    <div class="mobile-menu-toggle">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                    <div class="dropdown-menu">
                        <ul>
                            <li><a href="../../frontend/html/recetas.html">Recetas</a></li>
                            <li><a href="../../frontend/html/eventos.html">Eventos</a></li>
                            <li><a href="../../frontend/html/restaurantes.html">Restaurantes</a></li>
                            <li><a href="./../frontend/html/empleos.html">Comunidad</a></li>
                            <li><a href="../../frontend/html/empleos.html">Empleos</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Sección central: Búsqueda -->
                <div class="search-section">
                    <div class="search-container">
                        <input type="text" id="global-search" placeholder="Buscar..." />
                        <div class="search-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </div>
                    </div>
                </div>
                <div id="search-results" class="search-results"></div>

                <!-- Sección derecha: Autenticación -->
                <!-- <div class="auth-buttons">
                    <a href="/Proyectogastrolink/gastrolink/app/frontend/html/iniciosesion.html" class="btn login">Iniciar Sesión</a>
                    <a href="/Proyectogastrolink/gastrolink/app/frontend/html/registro.html" class="btn register">Suscribirse</a>
                </div> -->
                <!-- Reemplaza la sección de auth-buttons con esto -->
                <div class="auth-section">
                    <!-- Estado cuando NO hay sesión -->
                    <div class="auth-buttons" id="guest-view">
                        <a href="../../frontend/html/iniciosesion.html" class="btn login">Iniciar Sesión</a>
                        <a href="../../frontend/html/registro.html" class="btn register">Suscribirse</a>
                    </div>

                    <!-- Estado cuando SÍ hay sesión -->
                    <div class="user-menu" id="user-view" style="display: none;">
                        <div class="user-greeting">
                            <span id="welcome-message">Bienvenido/a</span>
                            <span id="username-display"></span>
                        </div>
                        <div class="user-avatar" id="user-avatar">
                            <img src="/gastrolink/app/img/default-avatar.png" alt="Foto de perfil">
                        </div>
                        <div class="user-dropdown">
                            <ul>
                                <li><a href="/ajustes">Ajustes</a></li>
                                <li><a href="/cuenta">Mi Cuenta</a></li>
                                <li><a href="#" id="logout-btn">Cerrar Sesión</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <script>
        console.log("El script de sesión se está cargando");
        document.addEventListener('DOMContentLoaded', () => {
            const menuToggle = document.querySelector('.mobile-menu-toggle');
            const dropdownMenu = document.querySelector('.dropdown-menu');

            // Alternar visibilidad con clic
            menuToggle.addEventListener('click', (e) => {
                e.stopPropagation(); // Evita que se dispare el evento de cierre al hacer clic en el botón
                dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
            });

            // Cierra el menú si se hace clic fuera
            document.addEventListener('click', (e) => {
                const isClickInsideMenu = dropdownMenu.contains(e.target);
                const isClickOnToggle = menuToggle.contains(e.target);
                if (!isClickInsideMenu && !isClickOnToggle) {
                    dropdownMenu.style.display = 'none';
                }
            });
        });
    </script>
    <script>
        console.log("El script de sesión se está cargando");
        document.addEventListener('DOMContentLoaded', () => {
            // Elementos del DOM
            const guestView = document.getElementById('guest-view');
            const userView = document.getElementById('user-view');
            const usernameDisplay = document.getElementById('username-display');
            const userAvatar = document.getElementById('user-avatar');
            const logoutBtn = document.getElementById('logout-btn');

            // Verificar sesión al cargar la página
            checkSession();

            // Función para verificar el estado de sesión
            function checkSession() {
                const userData = sessionStorage.getItem('userData');
                if (userData) {
                    const user = JSON.parse(userData);
                    updateMenuForLoggedInUser(user);
                }
            }

            // Función para actualizar el menú cuando el usuario inicia sesión
            function updateMenuForLoggedInUser(user) {
                guestView.style.display = 'none';
                userView.style.display = 'flex';
                usernameDisplay.textContent = user.nombre || user.correo.split('@')[0];
                if (user.fotoPerfil) {
                    userAvatar.querySelector('img').src = user.fotoPerfil;
                }
            }

            // Función para cerrar sesión
            function logout() {
                sessionStorage.removeItem('userData');
                window.location.href = '/index.html';
            }

            // Evento para cerrar sesión
            if (logoutBtn) {
                logoutBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    logout();
                });
            }

            // Verificar si viene de un login exitoso
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('login') && urlParams.get('login') === 'success') {
                const userData = sessionStorage.getItem('userData');
                if (userData) {
                    const user = JSON.parse(userData);
                    updateMenuForLoggedInUser(user);
                }
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        });
    </script>
</body>

</html>