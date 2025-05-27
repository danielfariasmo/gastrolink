document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    const id = params.get('id');

    if (!id) {
        document.querySelector('.recipe-detail').innerHTML = '<p>Receta no encontrada.</p>';
        return;
    }

    fetch(`../../backend/php/receta.php?id=${id}`)
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                document.querySelector('.recipe-detail').innerHTML = `<p>${data.error}</p>`;
            } else {
                cargarReceta(data);
            }
        })
        .catch(error => {
            console.error('Error al obtener la receta:', error);
            document.querySelector('.recipe-detail').innerHTML = '<p>Error al cargar la receta.</p>';
        });

    // Botones funcionales
    document.querySelector('.imprimir-btn').addEventListener('click', () => window.print());

    document.querySelector('.guardar-btn').addEventListener('click', function () {
        this.innerHTML = '<span class="action-icon">✓</span> Receta guardada';
        setTimeout(() => {
            this.innerHTML = '<span class="action-icon">💾</span> Guardar receta';
        }, 2000);
    });

    document.querySelector('.compartir-btn').addEventListener('click', function () {
        const titulo = document.querySelector('.recipe-title')?.textContent || 'Receta de GastroLink';
        if (navigator.share) {
            navigator.share({
                title: titulo,
                text: `Mira esta deliciosa receta: ${titulo}`,
                url: window.location.href
            });
        } else {
            alert('Enlace copiado: ' + window.location.href);
        }
    });
});

function cargarReceta(receta) {
    document.title = `${receta.titulo}`;
    document.querySelector('.recipe-title').textContent = receta.titulo;
    document.querySelector('.recipe-subtitle').textContent = receta.introduccion;
    document.querySelector('.recipe-image').src = receta.img_receta;
    document.querySelector('.recipe-image').alt = receta.titulo;

    // Mostrar tiempo de preparación, porciones y dificultad
    const tiempoPrep = document.querySelector('.prep-time-value');
    const porciones = document.querySelector('.porciones-value');
    const dificultad = document.querySelector('.dificultad-value');

    if (tiempoPrep && porciones && dificultad) {
        tiempoPrep.textContent = receta.tiempo_preparacion ?? '--';
        porciones.textContent = receta.porciones ?? '--';
        dificultad.textContent = receta.dificultad ?? '--';
    }

    // Ingredientes
    const listaIngredientes = document.querySelector('.ingredients-list');
    listaIngredientes.innerHTML = '';
    receta.ingredientes.split(',').forEach(ing => {
        const li = document.createElement('li');
        li.classList.add('ingredient-item');
        li.textContent = ing.trim();
        listaIngredientes.appendChild(li);
    });

    // Información nutricional
    document.querySelector('.calorias-value').textContent = receta.calorias || '--';
    document.querySelector('.proteinas-value').textContent = receta.proteinas || '--';
    document.querySelector('.carbohidratos-value').textContent = receta.carbohidratos || '--';
    document.querySelector('.grasas-value').textContent = receta.grasas || '--';

    // Pasos
    const listaPasos = document.querySelector('.steps-list');
    listaPasos.innerHTML = '';

    const pasos = receta.pasos.split(/(?:\d+\.\s)/).filter(Boolean);

    pasos.forEach(paso => {
        const li = document.createElement('li');
        li.classList.add('step-item');
        li.textContent = paso.trim();
        listaPasos.appendChild(li);
    });

    // Cocinero
    const chefImg = document.querySelector('.chef-image img');
    const chefName = document.querySelector('.chef-name');

    if (chefImg && chefName) {
        chefImg.src = receta.img_cocinero;
        chefImg.alt = receta.nombre_cocinero;
        chefName.textContent = receta.nombre_cocinero;
    }

    // Recetas relacionadas
    const relatedGrid = document.querySelector('.related-grid');
    relatedGrid.innerHTML = '';

    if (receta.relacionadas && receta.relacionadas.length > 0) {
        receta.relacionadas.forEach(r => {
            const card = document.createElement('div');
            card.classList.add('related-card');

            card.innerHTML = `
            <div class="related-image">
                <img src="${r.img_receta}" alt="${r.titulo}">
            </div>
            <div class="related-details">
                <h3 class="related-name">${r.titulo}</h3>
                <p class="related-info">${r.introduccion}</p>
            </div>
        `;

            // Ir al detalle al hacer clic
            card.addEventListener('click', () => {
                window.location.href = `detalle_recetas.html?id=${r.id_receta}`;
            });

            relatedGrid.appendChild(card);
        });
    } else {
        relatedGrid.innerHTML = '<p>No hay recetas relacionadas.</p>';
    }

}
