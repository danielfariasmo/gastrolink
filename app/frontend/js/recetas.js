const recetasPorPagina = 6;
const recetas = Array.from(document.querySelectorAll('.recipe-card'));
const totalPaginas = Math.ceil(recetas.length / recetasPorPagina);
let paginaActual = 1;

function mostrarPagina(numPagina) {
    paginaActual = numPagina;

    // Ocultar todas las recetas
    recetas.forEach((receta, index) => {
        receta.style.display = 'none';
    });

    // Mostrar solo las recetas de la página actual
    const inicio = (numPagina - 1) * recetasPorPagina;
    const fin = inicio + recetasPorPagina;
    for (let i = inicio; i < fin && i < recetas.length; i++) {
        recetas[i].style.display = 'block';
    }

    actualizarPaginacion();
}

function actualizarPaginacion() {
    const paginacion = document.querySelector('.pagination');
    paginacion.innerHTML = '';

    // Botón ←
    const prev = document.createElement('a');
    prev.href = '#';
    prev.classList.add('arrow');
    prev.textContent = '←';
    prev.onclick = (e) => {
        e.preventDefault();
        if (paginaActual > 1) {
            mostrarPagina(paginaActual - 1);
        }
    };
    paginacion.appendChild(prev);

    // Números de página
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

    // Botón →
    const next = document.createElement('a');
    next.href = '#';
    next.classList.add('arrow');
    next.textContent = '→';
    next.onclick = (e) => {
        e.preventDefault();
        if (paginaActual < totalPaginas) {
            mostrarPagina(paginaActual + 1);
        }
    };
    paginacion.appendChild(next);
}
// Mostrar la primera página al cargar
mostrarPagina(1);