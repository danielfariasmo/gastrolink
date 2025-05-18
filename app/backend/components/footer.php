<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tu Sitio Web</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Estilos base */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        main {
            flex: 1;
            padding: 20px;
        }

        a {
            text-decoration: none;
            color: #fff;
        }
        
        /* Estilos para el footer */
        .footer {
            background-color: #333;
            color: #fff;
            margin-top: auto;
            width: 100%;
        }

        .footer-main {
            padding: 40px 0;
        }   

        .footer-sections {
            display: flex;
            justify-content: space-between;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .footer-section {
            flex: 1;
            padding: 0 15px;
        }

        .footer-section h3 {
            font-size: 1.1rem;
            margin-bottom: 25px;
            color: #fff;
            font-weight: 500;
        }

        .social-icons {
            display: flex;
            gap: 15px;
        }

        .social-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: #fff;
            color: #333;
            text-decoration: none;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .social-icon:hover {
            background-color: #ff4d4d;
            color: #fff;
            transform: translateY(-3px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .footer-sections {
                flex-wrap: wrap;
            }
            
            .footer-section {
                flex: 0 0 50%;
                margin-bottom: 30px;
            }
        }

        @media (max-width: 480px) {
            .footer-section {
                flex: 0 0 100%;
                text-align: center;
            }
            
            .menu-lines {
                align-items: center;
            }
            
            .social-icons {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Footer -->
    <footer class="footer">
        <div class="footer-main">
            <div class="container">
                <div class="footer-sections">
                    <div class="footer-section">
                        <h3><a href="../../frontend/html/recetas.html">Recetas</a></h3>
                        <h3><a href="../../frontend/html/eventos.html">Eventos</a></h3>
                        <h3><a href="../../frontend/html/empleos.html">Empleos</a></h3>
                    </div>
                    
                    <div class="footer-section">
                        <h3><a href="../../frontend/html/restaurantes.html">Restaurantes</a></h3>
                        <h3><a href="../../frontend/html/comunidad.html">Comunidad</a></h3>
                    </div>
                    
                    <div class="footer-section">
                        <h3><a href="../../frontend/html/preguntas_frecuentes.html">Preguntas Frecuentes</a></h3>
                        <h3><a href="../../frontend/html/contacto.html">Contacto</a></h3>
                    </div>
                    
                    <div class="footer-section">
                        <h3>Conecta</h3>
                        <div class="social-icons">
                            <a href="https://www.facebook.com/" target="_blank" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                            <a href="https://twitter.com/" target="_blank" class="social-icon"><i class="fab fa-twitter"></i></a>
                            <a href="https://www.instagram.com/" target="_blank" class="social-icon"><i class="fab fa-instagram"></i></a>
                            <a href="https://www.linkedin.com/" target="_blank" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>