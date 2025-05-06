    document.addEventListener('DOMContentLoaded', function () {
        const itemsPerPage = 2; // Número de empleos por página
        const jobCards = Array.from(document.querySelectorAll('.job-card'));
        const paginationContainer = document.querySelector('.pagination');

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

            // Reasignar eventos
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

        // Inicializar paginación
        mostrarPagina(1);
    });
