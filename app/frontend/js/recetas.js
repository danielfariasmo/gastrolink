// Recetas.js
const recetasPorPagina = 12;
let recetas = [];
let paginaActual = 1;

// Función para mostrar las recetas en la página
function mostrarPagina(numPagina) {
    paginaActual = numPagina;
    const recipesGrid = document.getElementById('recipes-grid');
    recipesGrid.innerHTML = '';

    const inicio = (numPagina - 1) * recetasPorPagina;
    const fin = inicio + recetasPorPagina;
    const recetasPagina = recetas.slice(inicio, fin);

    recetasPagina.forEach(receta => {
        const recipeCard = document.createElement('div');
        recipeCard.classList.add('recipe-card');

        const recipeImage = document.createElement('div');
        recipeImage.classList.add('recipe-image');
        const img = document.createElement('img');
        img.src = receta.img_receta;
        img.alt = receta.titulo;
        recipeImage.appendChild(img);

        const recipeDetails = document.createElement('div');
        recipeDetails.classList.add('recipe-details');

        const recipeTitle = document.createElement('h3');
        recipeTitle.classList.add('recipe-title');
        recipeTitle.textContent = receta.titulo;

        const recipeDescription = document.createElement('p');
        recipeDescription.classList.add('recipe-description');
        recipeDescription.textContent = receta.introduccion;

        const recipeMeta = document.createElement('div');
        recipeMeta.classList.add('recipe-meta');

        const recipeSave = document.createElement('div');
        recipeSave.classList.add('recipe-save');
        recipeSave.innerHTML = '<i class="far fa-heart"></i>';

        recipeMeta.appendChild(recipeSave);
        recipeDetails.appendChild(recipeTitle);
        recipeDetails.appendChild(recipeDescription);
        recipeDetails.appendChild(recipeMeta);

        recipeCard.appendChild(recipeImage);
        recipeCard.appendChild(recipeDetails);

        recipesGrid.appendChild(recipeCard);
    });

    actualizarPaginacion();
}

// Función para actualizar la paginación
function actualizarPaginacion() {
    const paginacion = document.querySelector('.pagination');
    paginacion.innerHTML = '';
    const totalPaginas = Math.ceil(recetas.length / recetasPorPagina);

    const prev = document.createElement('a');
    prev.href = '#';
    prev.classList.add('arrow');
    prev.textContent = '←';
    prev.onclick = (e) => {
        e.preventDefault();
        if (paginaActual > 1) mostrarPagina(paginaActual - 1);
    };
    paginacion.appendChild(prev);

    for (let i = 1; i <= totalPaginas; i++) {
        const pageLink = document.createElement('a');
        pageLink.href = '#';
        pageLink.textContent = i;
        if (i === paginaActual) pageLink.classList.add('active');
        pageLink.onclick = (e) => {
            e.preventDefault();
            mostrarPagina(i);
        };
        paginacion.appendChild(pageLink);
    }

    const next = document.createElement('a');
    next.href = '#';
    next.classList.add('arrow');
    next.textContent = '→';
    next.onclick = (e) => {
        e.preventDefault();
        if (paginaActual < totalPaginas) mostrarPagina(paginaActual + 1);
    };
    paginacion.appendChild(next);
}

// Función para cargar los filtros
function cargarFiltros(filtros) {
    const filtersContainer = document.querySelector('.filters');
    filtersContainer.innerHTML = '';

    const allBtn = document.createElement('button');
    allBtn.classList.add('filter-btn', 'active');
    allBtn.textContent = 'Todos';
    allBtn.onclick = () => cargarRecetas('todos');
    filtersContainer.appendChild(allBtn);

    filtros.forEach(filtro => {
        const filterBtn = document.createElement('button');
        filterBtn.classList.add('filter-btn');
        filterBtn.textContent = filtro;
        filterBtn.onclick = () => cargarRecetas(filtro);
        filtersContainer.appendChild(filterBtn);
    });
}

// Función para cargar las recetas desde el backend
function cargarRecetas(tipo = 'todos') {
    const url = tipo === 'todos' ? '../../backend/php/get_recetas.php' : `../../backend/php/get_recetas.php?tipo=${tipo}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (Array.isArray(data.recetas)) {
                recetas = data.recetas;
                mostrarPagina(1);
            }
            if (Array.isArray(data.filtros)) {
                cargarFiltros(data.filtros);
            }
        })
        .catch(error => console.error('Error fetching recetas:', error));
}

// Inicializar la carga de recetas y filtros
document.addEventListener('DOMContentLoaded', () => {
    cargarRecetas();
});
