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

    document.querySelector('.guardar-btn').addEventListener('click', function() {
        this.innerHTML = '<span class="action-icon">✓</span> Receta guardada';
        setTimeout(() => {
            this.innerHTML = '<span class="action-icon">💾</span> Guardar receta';
        }, 2000);
    });

    document.querySelector('.compartir-btn').addEventListener('click', function() {
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

function configurarPopupEdicion(receta) {
    const popup = document.getElementById('edit-popup');
    const cancelBtn = document.querySelector('.cancel-btn');
    const editForm = document.getElementById('edit-recipe-form');

    // Rellenar el formulario con los datos actuales
    document.getElementById('edit-title').value = receta.titulo;
    document.getElementById('edit-intro').value = receta.introduccion;
    document.getElementById('edit-ingredients').value = receta.ingredientes;
    document.getElementById('edit-steps').value = receta.pasos;
    document.getElementById('edit-time').value = receta.tiempo_preparacion;
    document.getElementById('edit-portions').value = receta.porciones;
    document.getElementById('edit-difficulty').value = receta.dificultad;

    // Mostrar popup al hacer clic en editar
    document.querySelector('.editar-btn').addEventListener('click', () => {
        popup.style.display = 'flex';
    });

    // Ocultar popup al hacer clic en cancelar
    cancelBtn.addEventListener('click', () => {
        popup.style.display = 'none';
    });

    // Ocultar popup al hacer clic fuera del contenido
    popup.addEventListener('click', (e) => {
        if (e.target === popup) {
            popup.style.display = 'none';
        }
    });

    // Manejar el envío del formulario
    editForm.addEventListener('submit', (e) => {
        e.preventDefault();
        guardarCambiosReceta(receta.id_receta);
    });
}

function guardarCambiosReceta(idReceta) {
    // Crear FormData para enviar también archivos si es necesario
    const formData = new FormData();
    const usuario = JSON.parse(userData);
    formData.append('action', 'update_recipe');
    formData.append('id_receta', idReceta);
    formData.append('id_usuario', usuario.id_usuario);
    formData.append('title', document.getElementById('edit-title').value);
    formData.append('description', document.getElementById('edit-intro').value);
    formData.append('ingredients', document.getElementById('edit-ingredients').value);
    formData.append('steps', document.getElementById('edit-steps').value);
    formData.append('time', document.getElementById('edit-time').value);
    formData.append('portions', document.getElementById('edit-portions').value);
    formData.append('difficulty', document.getElementById('edit-difficulty').value);
    
    // Añadir la imagen si se seleccionó una nueva
    const imageInput = document.getElementById('edit-image');
    if (imageInput && imageInput.files[0]) {
        formData.append('image', imageInput.files[0]);
    }

    // Enviar los datos
    fetch('../../backend/php/actualizar_receta.php', {
        method: 'POST',
        body: formData // No establezcas Content-Type, FormData lo maneja automáticamente
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Receta actualizada correctamente');
            document.getElementById('edit-popup').style.display = 'none';
            location.reload();
        } else {
            alert('Error al actualizar: ' + (data.message || 'Error desconocido'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al conectar con el servidor');
    });
}

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

    const pasos = receta.pasos.split(/\n/).filter(Boolean);

    pasos.forEach(paso => {
        const li = document.createElement('li');
        li.classList.add('step-item');
        li.textContent = paso.trim();
        listaPasos.appendChild(li);
    });

    console.log("Datos de la receta:", receta);
    console.log("Datos del usuario:", JSON.parse(sessionStorage.getItem('userData')));

    // Verificar si el usuario logueado es el creador
    const editarBtn = document.querySelector('.editar-btn');
    const guardarBtn = document.querySelector('.guardar-btn');

    if (editarBtn && guardarBtn) {
        const userData = sessionStorage.getItem('userData');

        if (userData) {
            try {
                const usuario = JSON.parse(userData);
                if (usuario.id_usuario && receta.id_cocinero && usuario.id_usuario == receta.id_cocinero) {
                    // Mostrar botón editar y ocultar guardar
                    editarBtn.style.display = 'inline-block';
                    guardarBtn.style.display = 'none';

                    // Configurar el popup de edición
                    configurarPopupEdicion(receta);
                }
            } catch (e) {
                console.error("Error al parsear userData:", e);
            }
        }
    }

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