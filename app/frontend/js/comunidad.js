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

const miembrosPorPagina = 8;
const membersContainer = document.querySelector('.members-grid');
const paginationContainer = document.querySelector('.pagination');
let currentPage = 1;
let currentTipo = 'cocinero';
let miembrosData = [];

async function fetchMiembros(tipo) {
    try {
        const response = await fetch(`../../backend/php/get_comunidad.php?tipo=${tipo}`);
        const data = await response.json();
        if (response.ok) {
            miembrosData = data;
            currentTipo = tipo;
            currentPage = 1;
            renderMiembros();
        } else {
            console.error('Error al obtener los miembros:', data);
        }
    } catch (error) {
        console.error('Error en la petición:', error);
    }
}

function renderMiembros() {
    membersContainer.innerHTML = '';
    const start = (currentPage - 1) * miembrosPorPagina;
    const end = start + miembrosPorPagina;
    const pageData = miembrosData.slice(start, end);

    pageData.forEach(miembro => {
        const memberCard = document.createElement('div');
        memberCard.className = 'member-card';
        memberCard.innerHTML = `
            <div class="member-image">
                <img src="${miembro.img_usuario}" alt="${miembro.nombre}">
            </div>
            <div class="member-details">
                <h3 class="member-name">${miembro.nombre}</h3>
                <p class="member-bio">${miembro.descripcion}</p>
                ${miembro.especialidad ? `<p class="member-specialty">Especialidad: ${miembro.especialidad}</p>` : ''}
                ${miembro.idiomas ? `<p class="member-languages">Idiomas: ${miembro.idiomas}</p>` : ''}
                <button class="follow-btn" onclick="toggleFollow(this)">Seguir</button>
            </div>
        `;
        membersContainer.appendChild(memberCard);
    });

    renderPagination();
}

function renderPagination() {
    paginationContainer.innerHTML = '';
    const totalPages = Math.ceil(miembrosData.length / miembrosPorPagina);

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

function changePage(newPage) {
    const totalPages = Math.ceil(miembrosData.length / miembrosPorPagina);
    if (newPage < 1 || newPage > totalPages) return;
    currentPage = newPage;
    renderMiembros();
}

// Evento para cambiar entre cocineros y camareros
document.querySelector('.tabs').addEventListener('click', (e) => {
    if (e.target.classList.contains('tab')) {
        document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
        e.target.classList.add('active');
        const tipo = e.target.textContent.toLowerCase();
        fetchMiembros(tipo);
    }
});

// Cargar cocineros por defecto al cargar la página
fetchMiembros('cocinero');