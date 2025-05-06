// Función para seguir/dejar de seguir a un miembro
function toggleFollow(button) {
    button.classList.toggle('following');
    
    if (button.classList.contains('following')) {
        button.textContent = 'Siguiendo';
        alert('Ahora estás siguiendo a este miembro');
    } else {
        button.textContent = 'Seguir';
        alert('Has dejado de seguir a este miembro');
    }
}

const miembrosPorPagina = 4;
const memberCards = document.querySelectorAll('.member-card');
const totalPaginas = Math.ceil(memberCards.length / miembrosPorPagina);
const paginationLinks = document.querySelectorAll('.pagination a');

function mostrarPagina(pagina) {
const inicio = (pagina - 1) * miembrosPorPagina;
const fin = pagina * miembrosPorPagina;

// Ocultar todos los miembros
memberCards.forEach((card, index) => {
    card.style.display = (index >= inicio && index < fin) ? 'block' : 'none';
});

// Actualizar clases activas
paginationLinks.forEach(link => {
    if (!link.classList.contains('arrow')) {
        link.classList.remove('active');
        if (link.textContent == pagina) {
            link.classList.add('active');
        }
    }
});
}

// Evento de clic en la paginación
paginationLinks.forEach(link => {
link.addEventListener('click', function (e) {
    e.preventDefault();

    let paginaActual = document.querySelector('.pagination a.active');
    let numeroActual = parseInt(paginaActual ? paginaActual.textContent : 1);
    let nuevaPagina = numeroActual;

    if (this.classList.contains('arrow')) {
        nuevaPagina = (this.textContent.includes('←')) ? numeroActual - 1 : numeroActual + 1;
    } else {
        nuevaPagina = parseInt(this.textContent);
    }

    if (nuevaPagina < 1 || nuevaPagina > totalPaginas) return;

    mostrarPagina(nuevaPagina);
});
});

// Mostrar la primera página al cargar
mostrarPagina(1);
