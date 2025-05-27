document.addEventListener('DOMContentLoaded', function () {
    const itemsPerPage = 6;
    const paginationContainer = document.querySelector('.pagination');
    const tabButtons = document.querySelectorAll('.tab');
    let todasLasOfertas = [];
    let jobCards = [];
    let filtroActual = 'todos';

    fetch('../../backend/php/get_empleos.php')
        .then(response => response.json())
        .then(data => {
            todasLasOfertas = data;
            aplicarFiltro();
        })
        .catch(error => console.error('Error al obtener los empleos:', error));

    function aplicarFiltro() {
        const ofertasFiltradas = (filtroActual === 'todos')
            ? todasLasOfertas
            : todasLasOfertas.filter(oferta => oferta.tipo_puesto === filtroActual);

        mostrarEmpleos(ofertasFiltradas);
        jobCards = Array.from(document.querySelectorAll('.job-card'));
        mostrarPagina(1);
    }

    function mostrarEmpleos(ofertas) {
        const contenedor = document.querySelector('.job-list');
        contenedor.innerHTML = '';

        ofertas.forEach(oferta => {
            const card = document.createElement('div');
            card.classList.add('job-card');
            const correoDestino = oferta.correo;
            const asunto = encodeURIComponent(`Aplicación a oferta en ${oferta.restaurante}`);
            const cuerpo = encodeURIComponent(
                `Hola,
                \n\nEstoy interesado en la oferta:
                    \n- Puesto: ${oferta.tipo_puesto}
                    \n- Restaurante: ${oferta.restaurante}
                    \n- Título de la oferta: ${oferta.titulo}\n\nGracias.`);

            card.innerHTML = `
                <div class="job-company">
                    <div class="company-image">
                            <img src="${oferta.img_usuario}" alt="Logo de empresa" 
                                onerror="this.src='/gastrolink/app/img/usuarios/user_default.jpg'">
                        </div>
                    <div class="company-name">${oferta.restaurante}</div>
                    </div>
                    <div class="job-details">
                        <div class="job-description">
                            <p><strong>${oferta.titulo}</strong></p>
                            <p>${oferta.descripcion}</p>
                            <p>Puesto: ${oferta.tipo_puesto}</p>
                            <p>Fecha: ${oferta.fecha_publicacion}</p>
                        </div>
                    <div class="job-actions">
                        <a href="mailto:${correoDestino}?subject=${asunto}&body=${cuerpo}" class="apply-btn">Aplicar</a>
                    </div>
                </div>`;
            contenedor.appendChild(card);
        });
    }

    function mostrarPagina(pagina) {
        const inicio = (pagina - 1) * itemsPerPage;
        const fin = inicio + itemsPerPage;

        jobCards.forEach((card, index) => {
            card.style.display = (index >= inicio && index < fin) ? 'flex' : 'none';
        });

        actualizarPaginacion(pagina);
    }

    function actualizarPaginacion(paginaActual) {
        const totalPaginas = Math.ceil(jobCards.length / itemsPerPage);
        let htmlPaginacion = `<a href="#" class="arrow" data-page="${paginaActual - 1}">←</a>`;

        for (let i = 1; i <= totalPaginas; i++) {
            htmlPaginacion += `<a href="#" class="${i === paginaActual ? 'active' : ''}" data-page="${i}">${i}</a>`;
        }

        htmlPaginacion += `<a href="#" class="arrow" data-page="${paginaActual + 1}">→</a>`;
        paginationContainer.innerHTML = htmlPaginacion;

        document.querySelectorAll('.pagination a').forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const paginaSeleccionada = parseInt(this.getAttribute('data-page'));
                const totalPaginas = Math.ceil(jobCards.length / itemsPerPage);

                if (paginaSeleccionada >= 1 && paginaSeleccionada <= totalPaginas) {
                    mostrarPagina(paginaSeleccionada);
                }
            });
        });
    }

    // Evento para los botones de filtro
    tabButtons.forEach(tab => {
        tab.addEventListener('click', function () {
            tabButtons.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            filtroActual = this.getAttribute('data-filtro');
            aplicarFiltro();
        });
    });
});
