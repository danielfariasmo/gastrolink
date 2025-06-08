document.addEventListener('DOMContentLoaded', function() {
    // Obtener datos del usuario desde sessionStorage
    const userData = JSON.parse(sessionStorage.getItem('userData'));
    
    // Verificar que el usuario es un cocinero
    if (!userData || userData.tipo_usuario !== 'cocinero') {
        window.location.href = '../html/index.html';
        return;
    }

    // Cargar datos del cocinero
    cargarDatosCocinero(userData.id_usuario);
    
    // Configurar eventos
    document.querySelector('.edit-profile-btn').addEventListener('click', editarPerfil);
    document.querySelector('.add-recipe-btn').addEventListener('click', mostrarPopupReceta);
    document.querySelector('.close-popup').addEventListener('click', ocultarPopupReceta);
    document.getElementById('recipe-form').addEventListener('submit', guardarReceta);
});

function cargarDatosCocinero(id) {
    fetch(`../../backend/php/get_cocinero.php?id_cocinero=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                actualizarUI(data.cocinero, data.recetas, data.restaurantes_favoritos);
            } else {
                mostrarError(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarError('Error al cargar los datos del cocinero');
        });
}

function actualizarUI(cocinero, recetas, restaurantesFavoritos) {
    // Actualizar información del perfil
    const avatar = document.querySelector('.profile-avatar');
        avatar.style.backgroundImage = `url('${cocinero.imagen}')`;
    
    document.querySelector('.profile-info h2').textContent = cocinero.nombre;
    document.querySelector('.profile-type').textContent = `Cocinero (${cocinero.especialidad})`;
    document.querySelector('.profile-details').innerHTML = `
        ${cocinero.descripcion}<br>
        Experiencia: ${cocinero.experiencia}<br>
        Correo: ${cocinero.correo}
    `;
    
    // Actualizar recetas
    const gridRecetas = document.querySelector('.recipes-grid');
    gridRecetas.innerHTML = '';
    
    if (recetas.length === 0) {
        gridRecetas.innerHTML = '<p>No hay recetas publicadas todavía.</p>';
        return;
    }
    
    recetas.forEach(receta => {
        const card = document.createElement('div');
        card.className = 'recipe-card';
        card.innerHTML = `
            <div class="recipe-image" style="background-image: url('${receta.img_receta || '../../img/recetas/default.jpg'}')"></div>
            <div class="recipe-content">
                <h4 class="recipe-title">${receta.titulo}</h4>
                <div class="recipe-time">${receta.tiempo_preparacion} min - ${receta.dificultad}</div>
                <div class="recipe-actions">
                    <button class="view-recipe-btn" data-id="${receta.id_receta}">Ver receta</button>
                    <div class="recipe-icons">
                        <button class="icon-btn edit-recipe-btn" data-id="${receta.id_receta}"><i class="fa-solid fa-pen"></i></button>
                        <button class="icon-btn delete-recipe-btn" data-id="${receta.id_receta}"><i class="fa-solid fa-trash"></i></button>
                    </div>
                </div>
            </div>
        `;
        gridRecetas.appendChild(card);
    });
    
    // Agregar eventos a los botones
    document.querySelectorAll('.view-recipe-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const idReceta = this.getAttribute('data-id');
            window.location.href = `detalle_recetas.html?id=${idReceta}`;
        });
    });
    
    document.querySelectorAll('.edit-recipe-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const idReceta = this.getAttribute('data-id');
            editarReceta(idReceta);
        });
    });
    
    document.querySelectorAll('.delete-recipe-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const idReceta = this.getAttribute('data-id');
            eliminarReceta(idReceta);
        });
    });
    
    // Actualizar restaurantes favoritos en el sidebar si existe el elemento
    const sidebar = document.querySelector('.sidebar');
    if (sidebar && restaurantesFavoritos.length > 0) {
        const favoritosDiv = document.createElement('div');
        favoritosDiv.className = 'sidebar-favorites';
        favoritosDiv.innerHTML = `
            <h4>Restaurantes favoritos</h4>
            <div class="favorites-list">
                ${restaurantesFavoritos.map(rest => `
                    <div class="favorite-item">
                        <div class="favorite-avatar" style="background-image: url('${rest.img_usuario }')"></div>
                        <span>${rest.nombre}</span>
                    </div>
                `).join('')}
            </div>
        `;
        sidebar.appendChild(favoritosDiv);
    }
}

function mostrarError(mensaje) {
    const main = document.querySelector('main');
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.textContent = mensaje;
    main.prepend(errorDiv);
    
    setTimeout(() => {
        errorDiv.remove();
    }, 5000);
}

function editarPerfil() {
    // Implementar lógica para editar perfil
    console.log('Editar perfil');
}

function mostrarPopupReceta() {
    document.getElementById('add-recipe-popup').style.display = 'flex';
}

function ocultarPopupReceta() {
    document.getElementById('add-recipe-popup').style.display = 'none';
}

function guardarReceta(e) {
    e.preventDefault();
    
    const userData = JSON.parse(sessionStorage.getItem('userData'));
    const formData = new FormData(document.getElementById('recipe-form'));
    formData.append('action', 'create_recipe');
    formData.append('id_cocinero', userData.id_usuario);
    
    fetch('../../backend/php/crear_receta.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Receta creada con éxito');
            ocultarPopupReceta();
            cargarDatosCocinero(userData.id_usuario);
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al guardar la receta');
    });
}

function editarReceta(idReceta) {
    // Implementar lógica para editar receta
    console.log('Editar receta', idReceta);
}

function eliminarReceta(idReceta) {
    if (confirm('¿Estás seguro de que quieres eliminar esta receta?')) {
        const userData = JSON.parse(sessionStorage.getItem('userData'));
        
        fetch(`../../backend/php/eliminar_receta.php?id_receta=${idReceta}&id_cocinero=${userData.id_usuario}`, {
            method: 'DELETE'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Receta eliminada con éxito');
                // Recargar las recetas después de eliminar
                cargarDatosCocinero(userData.id_usuario);
            } else {
                throw new Error(data.message || 'Error desconocido al eliminar');
            }
        })
        .catch(error => {
            console.error('Error al eliminar receta:', error);
            alert(`Error al eliminar la receta: ${error.message}`);
        });
    }
}