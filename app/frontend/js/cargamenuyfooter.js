//  // Función para cargar componentes PHP
//  async function loadComponent(url, containerId) {
//     try {
//         const response = await fetch(url);
//         const html = await response.text();
//         document.getElementById(containerId).innerHTML = html;
//     } catch (error) {
//         console.error(`Error cargando ${url}:`, error);
//     }
// }

// // Cargar componentes cuando el DOM esté listo
// document.addEventListener('DOMContentLoaded', function() {
//     //Carpeta que tiene el menu
//     loadComponent('../../backend/components/menu.php', 'menu-container');
//     //Carpeta que tiene el footer
//     loadComponent('../../backend/components/footer.php', 'footer-container');
// });

// 1. Primero, el código para cargar componentes (el que ya tienes)
async function loadComponent(url, containerId) {
    try {
        const response = await fetch(url);
        const html = await response.text();
        document.getElementById(containerId).innerHTML = html;
        return true; // Indica que se cargó correctamente
    } catch (error) {
        console.error(`Error cargando ${url}:`, error);
        return false;
    }
}

// 2. Función para manejar la sesión (debes esperar a que el menú esté cargado)
async function setupSessionMenu() {
    
    // Espera a que el menú esté cargado
    const menuLoaded = await loadComponent('../../backend/components/menu.php', 'menu-container');
    
    if (menuLoaded) {
        
        // Ahora puedes seleccionar los elementos del menú
        const guestView = document.getElementById('guest-view');
        const userView = document.getElementById('user-view');
        
        if (!guestView || !userView) {
            console.error("Elementos del menú no encontrados");
            return;
        }
        
        // Tu lógica de sesión aquí
        const userData = sessionStorage.getItem('userData');
        if (userData) {
            const user = JSON.parse(userData);
            guestView.style.display = 'none';
            userView.style.display = 'flex';
            document.getElementById('username-display').textContent = user.nombre || user.correo.split('@')[0];
            
            if (user.fotoPerfil) {
                document.getElementById('user-avatar').querySelector('img').src = user.fotoPerfil;
            }
        }
        
        // Configurar el evento de logout
        const logoutBtn = document.getElementById('logout-btn');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', (e) => {
                e.preventDefault();
                sessionStorage.removeItem('userData');
                window.location.href = '/index.html';
            });
        }
    }
}

// 3. Inicialización cuando el DOM está listo
document.addEventListener('DOMContentLoaded', function() {
    // Cargar componentes
    setupSessionMenu(); // Primero el menú con la sesión
    loadComponent('../../backend/components/footer.php', 'footer-container');
});