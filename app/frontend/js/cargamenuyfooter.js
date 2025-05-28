// 1. Función para cargar componentes (se mantiene igual)
async function loadComponent(url, containerId) {
    try {
        const response = await fetch(url);
        const html = await response.text();
        document.getElementById(containerId).innerHTML = html;
        return true;
    } catch (error) {
        console.error(`Error cargando ${url}:`, error);
        return false;
    }
}

// 2. Función principal con filtrado por tipo de usuario
async function setupMenuAndSearch() {
    // 2.1 Cargar menú (se mantiene igual)
    const menuLoaded = await loadComponent('../../backend/components/menu.php', 'menu-container');
    if (!menuLoaded) return;

    // 2.1.1 Configurar menú hamburguesa (se mantiene igual)
    const menuToggle = document.querySelector('.mobile-menu-toggle');
    const dropdownMenu = document.querySelector('.dropdown-menu');
    if (menuToggle && dropdownMenu) {
        menuToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
        });
        document.addEventListener('click', (e) => {
            if (!dropdownMenu.contains(e.target) && !menuToggle.contains(e.target)) {
                dropdownMenu.style.display = 'none';
            }
        });
    }

    // 2.2 Gestión de sesión con filtrado por tipo de usuario
    const guestView = document.getElementById('guest-view');
    const userView = document.getElementById('user-view');
    const profileLink = document.getElementById('profile-link');

    function updateSessionView() {
        const userData = sessionStorage.getItem('userData');
        if (userData) {
            try {
                const user = JSON.parse(userData);
                
                // Mostrar vista según tipo de usuario
                guestView.style.display = 'none';
                userView.style.display = 'flex';
                
                // Actualizar datos del usuario
                document.getElementById('username-display').textContent = user.nombre || user.correo?.split('@')[0];
                if (user.img_usuario) {
                    document.getElementById('user-avatar').querySelector('img').src = user.img_usuario;
                }

                // Configurar enlace de perfil según tipo de usuario
                if (profileLink) {
                    const profilePages = {
                        'cocinero': '../../frontend/html/perfilcocinero.html',
                        'camarero': '../../frontend/html/perfilcamarero.html',
                        'restaurante': '../../frontend/html/perfilrestaurante.html'
                    };
                    
                    profileLink.href = profilePages[user.tipo_usuario] || '#';
                    profileLink.textContent = user.tipo_usuario === 'restaurante' ? 'Mi Restaurante' : 'Mi Perfil';
                    
                    // Manejador de clic seguro
                    profileLink.onclick = function(e) {
                        if (!this.href.includes('#')) {
                            window.location.href = this.href;
                        }
                        e.preventDefault();
                    };
                }

                // Filtrado adicional para elementos específicos por tipo de usuario
                filterByUserType(user.tipo_usuario);

            } catch (e) {
                console.error("Error procesando datos de usuario:", e);
            }
        } else {
            guestView.style.display = 'flex';
            userView.style.display = 'none';
        }
    }

    // 2.2.1 Función para filtrar elementos por tipo de usuario
    function filterByUserType(userType) {
        console.log(`Aplicando filtros para usuario tipo: ${userType}`);
        
        // Ejemplo: Ocultar/mostrar elementos del menú según el tipo de usuario
        const menuItems = document.querySelectorAll('.dropdown-menu li');
        
        menuItems.forEach(item => {
            const dataType = item.dataset.userType;
            if (dataType) {
                item.style.display = dataType.split(',').includes(userType) ? 'block' : 'none';
            }
        });
        
        // Puedes añadir más lógica de filtrado aquí según necesites
    }

    updateSessionView();

    // 2.3 Configurar logout (se mantiene igual)
    const logoutBtn = document.getElementById('logout-btn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', e => {
            e.preventDefault();
            sessionStorage.removeItem('userData');
            location.replace('/gastrolink/app/frontend/html/index.html');
        });
    }

    // 2.4 Búsqueda global con filtrado por tipo de usuario (se mantiene igual)
    const searchInput = document.getElementById('global-search');
    const searchResults = document.getElementById('search-results');
    if (!searchInput || !searchResults) {
        console.warn('Buscador no encontrado en el menú');
        return;
    }

    searchInput.addEventListener('input', async e => {
        const query = e.target.value.trim();
        if (!query) {
            searchResults.style.display = 'none';
            searchResults.innerHTML = '';
            return;
        }

        try {
            const response = await fetch(`../../backend/php/search.php?query=${encodeURIComponent(query)}`);
            const data = await response.json();

            // Filtrar resultados según tipo de usuario logueado
            const userData = sessionStorage.getItem('userData');
            const userType = userData ? JSON.parse(userData).tipo_usuario : null;
            
            const filteredData = userType 
                ? data.filter(item => item.tipo === userType || item.tipo === 'general') 
                : data;

            if (filteredData.length > 0) {
                searchResults.innerHTML = filteredData
                    .map(item =>
                        `<div class="result-item" data-id="${item.id_usuario}" data-tipo="${item.tipo}">` +
                        `<strong>${item.nombre}</strong> <small>(${item.tipo})</small>` +
                        `</div>`
                    )
                    .join('');
            } else {
                searchResults.innerHTML = `<div class="result-item">Sin resultados</div>`;
            }
            searchResults.style.display = 'block';
        } catch (error) {
            console.error('Error en la petición de búsqueda:', error);
            searchResults.innerHTML = `<div class="result-item">Error al cargar resultados</div>`;
            searchResults.style.display = 'block';
        }
    });

    // 2.5 Navegación desde resultados de búsqueda (se mantiene igual)
    searchResults.addEventListener('click', e => {
        const item = e.target.closest('.result-item');
        if (!item) return;

        const tipo = item.dataset.tipo;
        const id = item.dataset.id;
        let url = '';

        switch (tipo) {
            case 'receta':
                url = `/gastrolink/app/frontend/html/recetas.html`;
                break;
            case 'restaurante':
                url = `/gastrolink/app/frontend/html/restaurantes.html`;
                break;
            case 'camarero':
            case 'cocinero':
                url = `/gastrolink/app/frontend/html/comunidad.html`;
                break;
            default:
                console.warn(`Tipo desconocido: ${tipo}`);
                return;
        }

        window.location.href = url;
    });
}

// 3. Inicialización (se mantiene igual)
document.addEventListener('DOMContentLoaded', () => {
    setupMenuAndSearch();
    loadComponent('../../backend/components/footer.php', 'footer-container');
});