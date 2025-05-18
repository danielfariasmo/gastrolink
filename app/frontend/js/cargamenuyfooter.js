// 1. Primero, el código para cargar componentes
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

// 2. Función para manejar el menú, sesión y buscador
async function setupMenuAndSearch() {
    // 2.1 Carga del menú
    const menuLoaded = await loadComponent('../../backend/components/menu.php', 'menu-container');
    if (!menuLoaded) return;

    // 2.2 Gestión de sesión
    const guestView = document.getElementById('guest-view');
    const userView = document.getElementById('user-view');
    if (guestView && userView) {
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
        const logoutBtn = document.getElementById('logout-btn');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', e => {
                e.preventDefault();
                sessionStorage.removeItem('userData');
                window.location.href = '/index.html';
            });
        }
    }

    // 2.3 Inicializar búsqueda global
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

            if (data.length > 0) {
                searchResults.innerHTML = data
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

    // 2.4 Navegación al hacer click en un resultado
    searchResults.addEventListener('click', e => {
        const item = e.target.closest('.result-item');
        if (!item) return;

        const tipo = item.dataset.tipo;
        const id = item.dataset.id;
        let url = '';

        // URL según tipo
        switch (tipo) {
            case 'receta':
                url = `/gastrolink/app/frontend/html/recetas.html`;
                //url = `/gastrolink/app/frontend/html/recetas.html?id=${id}`;
                break;
            case 'restaurante':
                url = `/gastrolink/app/frontend/html/restaurantes.html`;
                //url = `/gastrolink/app/frontend/html/restaurantes.html?id=${id}`;
                break;
            case 'camarero':
                url = `/gastrolink/app/frontend/html/comunidad.html`;
                //url = `/gastrolink/app/frontend/html/comunidad.html?id=${id}`;
                break;
            case 'cocinero':
                url = `/gastrolink/app/frontend/html/comunidad.html`;
                //url = `/gastrolink/app/frontend/html/comunidad.html?id=${id}`;
                break;
            default:
                console.warn(`Tipo desconocido: ${tipo}`);
                return;
        }

        // Redirigir a la página correspondiente
        window.location.href = url;
    });
}

// 3. Inicialización

document.addEventListener('DOMContentLoaded', () => {
    setupMenuAndSearch();
    loadComponent('../../backend/components/footer.php', 'footer-container');
});
