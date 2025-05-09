<footer class="footer">
    <div class="footer-main">
        <div class="container">
            <div class="footer-sections">
                <div class="footer-section">
                    <h3>(Menu)</h3>
                    <div class="menu-lines">
                        <div class="menu-line"></div>
                        <div class="menu-line"></div>
                        <div class="menu-line"></div>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h3>Preguntas Frecuentes</h3>
                    <div class="menu-lines">
                        <div class="menu-line"></div>
                        <div class="menu-line"></div>
                        <div class="menu-line"></div>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h3>Contacto</h3>
                    <div class="menu-lines">
                        <div class="menu-line"></div>
                        <div class="menu-line"></div>
                        <div class="menu-line"></div>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h3>Conecta</h3>
                    <div class="social-icons">
                        <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
    /* Estilos para el footer */
    .footer {
        background-color: #333;
        color: #fff;
    }

    .footer-main {
        padding: 40px 0;
    }   

    .footer-sections {
        display: flex;
        justify-content: space-between;
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

    .menu-lines {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .menu-line {
        height: 2px;
        background-color: #fff;
        width: 80%;
        opacity: 0.7;
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
        }
    }
</style>
