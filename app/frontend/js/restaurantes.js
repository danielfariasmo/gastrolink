// Variables de configuración
const restaurantesPorPagina = 12;
const restaurantsContainer = document.querySelector('.restaurants-grid');
const paginationContainer = document.querySelector('.pagination');
let currentPage = 1;
let currentFilter = 'todos';
let restaurantesData = [];

// Fetch restaurantes
async function fetchRestaurantes(filtro) {
    try {
        const response = await fetch(`../../backend/php/get_restaurantes.php?filtro=${filtro}`);
        const data = await response.json();
        if (response.ok) {
            restaurantesData = data;
            currentFilter = filtro;
            currentPage = 1;
            renderRestaurantes();
        } else {
            console.error('Error al obtener los restaurantes:', data);
        }
    } catch (error) {
        console.error('Error en la petición:', error);
    }
}

// Renderizar restaurantes
function renderRestaurantes() {
    restaurantsContainer.innerHTML = '';
    const start = (currentPage - 1) * restaurantesPorPagina;
    const end = start + restaurantesPorPagina;
    const pageData = restaurantesData.slice(start, end);

    pageData.forEach(restaurante => {
        const restaurantCard = document.createElement('a');
        restaurantCard.className = 'restaurant-card';
        restaurantCard.href = `detalle-restaurante.php?id=${restaurante.id_usuario}`;
        restaurantCard.innerHTML = `
            <div class="restaurant-image">
                <img src="${restaurante.img_usuario}" alt="${restaurante.nombre}">
            </div>
            <div class="restaurant-details">
                <h3 class="restaurant-name">${restaurante.nombre}</h3>
                <p class="restaurant-type">${restaurante.tipo_restaurante}</p>
                <p class="restaurant-description">${restaurante.descripcion}</p>
            </div>
        `;
        restaurantsContainer.appendChild(restaurantCard);
    });

    renderPagination();
}

// Renderizar paginación
function renderPagination() {
    paginationContainer.innerHTML = '';
    const totalPages = Math.ceil(restaurantesData.length / restaurantesPorPagina);

    const prevLink = document.createElement('a');
    prevLink.href = '#';
    prevLink.className = 'arrow';
    prevLink.textContent = '←';
    prevLink.addEventListener('click', () => changePage(currentPage - 1));
    paginationContainer.appendChild(prevLink);

    for (let i = 1; i <= totalPages; i++) {
        const pageLink = document.createElement('a');
        pageLink.href = '#';
        pageLink.textContent = i;
        if (i === currentPage) pageLink.classList.add('active');
        pageLink.addEventListener('click', () => changePage(i));
        paginationContainer.appendChild(pageLink);
    }

    const nextLink = document.createElement('a');
    nextLink.href = '#';
    nextLink.className = 'arrow';
    nextLink.textContent = '→';
    nextLink.addEventListener('click', () => changePage(currentPage + 1));
    paginationContainer.appendChild(nextLink);
}

// Cambiar página
function changePage(newPage) {
    const totalPages = Math.ceil(restaurantesData.length / restaurantesPorPagina);
    if (newPage < 1 || newPage > totalPages) return;
    currentPage = newPage;
    renderRestaurantes();
}

// Filtros
document.querySelector('.filters').addEventListener('click', (e) => {
    if (e.target.classList.contains('filter-btn')) {
        document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
        e.target.classList.add('active');
        const filtro = e.target.getAttribute('data-tipo');
        fetchRestaurantes(filtro);
    }
});

// Cargar restaurantes por defecto
fetchRestaurantes('todos');