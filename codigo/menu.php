<header class="header">
    <div class="container">
        <nav class="navbar">
            <div class="logo">
                <a href="index.html">
                    <img src="/placeholder.svg?height=40&width=120" alt="GrupoLink Logo">
                </a>
            </div>
            <div class="nav-links">
                <ul>
                    <li><a href="index.html">Inicio</a></li>
                    <li><a href="servicios.html">Servicios</a></li>
                    <li><a href="sobre-nosotros.html">Sobre Nosotros</a></li>
                    <li><a href="contacto.html">Contacto</a></li>
                </ul>
            </div>
            <div class="auth-buttons">
                <a href="login.html" class="btn login">Iniciar Sesión</a>
                <a href="registro.html" class="btn register">Registrarse</a>
            </div>
            <div class="mobile-menu-toggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </nav>
    </div>
</header>

<style>
    /* Estilos para el menú */
    .header {
        background-color: white;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 0;
    }

    .logo img {
        height: 40px;
    }

    .nav-links ul {
        display: flex;
        list-style: none;
    }

    .nav-links li {
        margin: 0 15px;
    }

    .nav-links a {
        text-decoration: none;
        color: #333;
        font-weight: 500;
        transition: color 0.3s ease;
        position: relative;
    }

    .nav-links a:after {
        content: '';
        position: absolute;
        width: 0;
        height: 2px;
        bottom: -5px;
        left: 0;
        background-color: #ff4d4d;
        transition: width 0.3s ease;
    }

    .nav-links a:hover {
        color: #ff4d4d;
    }

    .nav-links a:hover:after {
        width: 100%;
    }

    .auth-buttons {
        display: flex;
        gap: 15px;
    }

    .btn.login {
        background-color: transparent;
        color: #333;
        border: 1px solid #ddd;
        transition: all 0.3s ease;
    }

    .btn.login:hover {
        border-color: #ff4d4d;
        color: #ff4d4d;
    }

    .btn.register {
        background-color: #ff4d4d;
        color: white;
    }

    .mobile-menu-toggle {
        display: none;
        flex-direction: column;
        cursor: pointer;
    }

    .mobile-menu-toggle span {
        width: 25px;
        height: 3px;
        background-color: #333;
        margin: 2px 0;
        transition: all 0.3s ease;
    }

    @media (max-width: 768px) {
        .nav-links, .auth-buttons {
            display: none;
        }

        .mobile-menu-toggle {
            display: flex;
        }
        
        .nav-links.active, .auth-buttons.active {
            display: flex;
            flex-direction: column;
            position: absolute;
            top: 70px;
            left: 0;
            width: 100%;
            background-color: white;
            padding: 20px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        }
        
        .nav-links.active ul {
            flex-direction: column;
        }
        
        .nav-links.active li {
            margin: 10px 0;
        }
        
        .auth-buttons.active {
            top: 200px;
        }
    }
</style>

<script>
    // Script para el menú móvil
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
        const navLinks = document.querySelector('.nav-links');
        const authButtons = document.querySelector('.auth-buttons');

        if (mobileMenuToggle) {
            mobileMenuToggle.addEventListener('click', function() {
                this.classList.toggle('active');
                navLinks.classList.toggle('active');
                authButtons.classList.toggle('active');
            });
        }
    });
</script>
