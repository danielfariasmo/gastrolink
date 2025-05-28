document.addEventListener('DOMContentLoaded', function() {
    // Obtener datos del usuario desde sessionStorage
    const userData = JSON.parse(sessionStorage.getItem('userData'));
    
    // Verificar que el usuario es un camarero
    if (!userData || userData.tipo_usuario !== 'camarero') {
        window.location.href = '../html/index.html';
        return;
    }

    // Cargar datos del camarero
    cargarDatosCamarero(userData.id_usuario);
    
    // Configurar eventos
    document.querySelector('.edit-profile-btn').addEventListener('click', editarPerfil);
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', cambiarTab);
    });
});

function cargarDatosCamarero(id) {
    fetch(`../../backend/php/get_camarero.php?id_camarero=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                actualizarUI(data.camarero, data.recetas_favoritas, data.restaurantes_favoritos);
            } else {
                mostrarError(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarError('Error al cargar los datos del camarero');
        });
}

function actualizarUI(camarero, recetasFavoritas, restaurantesFavoritos) {
    // Actualizar información del perfil
    const avatar = document.querySelector('.profile-avatar');
    if (camarero.imagen) {
        avatar.style.backgroundImage = `url('${camarero.imagen}')`;
    } else {
        avatar.style.backgroundImage = "url('../../img/default-avatar.jpg')";
    }
    
    document.querySelector('.profile-info h2').textContent = camarero.nombre;
    document.querySelector('.profile-type').textContent = `Camarero (${camarero.idiomas})`;
    document.querySelector('.profile-details').innerHTML = `
        ${camarero.descripcion}<br>
        Experiencia: ${camarero.experiencia}<br>
        Correo: ${camarero.correo}
    `;
    
    // Actualizar recetas favoritas
    actualizarRecetasFavoritas(recetasFavoritas);
    
    // Configurar eventos de tabs
    document.querySelector('.tab-btn:nth-child(1)').addEventListener('click', () => {
        actualizarRecetasFavoritas(recetasFavoritas);
    });
    
    document.querySelector('.tab-btn:nth-child(2)').addEventListener('click', () => {
        actualizarRestaurantesFavoritos(restaurantesFavoritos);
    });
}

function actualizarRecetasFavoritas(recetas) {
    const gridRecetas = document.querySelector('.recipes-grid');
    gridRecetas.innerHTML = '';
    
    if (recetas.length === 0) {
        gridRecetas.innerHTML = '<p>No hay recetas favoritas guardadas.</p>';
        return;
    }
    
    recetas.forEach(receta => {
        const card = document.createElement('div');
        card.className = 'recipe-card';
        card.innerHTML = `
            <div class="recipe-image" style="background-image: url('${receta.img_receta || '../../img/recetas/default.jpg'}')"></div>
            <div class="recipe-content">
                <h4 class="recipe-title">${receta.titulo}</h4>
                <div class="recipe-chef">Chef: ${receta.nombre_chef || 'Desconocido'}</div>
                <div class="recipe-time">${receta.tiempo_preparacion} min - ${receta.dificultad}</div>
                <div class="recipe-actions">
                    <button class="view-recipe-btn" data-id="${receta.id_receta}">Ver receta</button>
                    <div class="recipe-icons">
                        <button class="icon-btn heart-icon" data-id="${receta.id_receta}">♥</button>
                        <button class="icon-btn bookmark-icon" data-id="${receta.id_receta}">🔖</button>
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
}

function actualizarRestaurantesFavoritos(restaurantes) {
    const gridRecetas = document.querySelector('.recipes-grid');
    gridRecetas.innerHTML = '';
    
    if (restaurantes.length === 0) {
        gridRecetas.innerHTML = '<p>No hay restaurantes favoritos guardados.</p>';
        return;
    }
    
    restaurantes.forEach(restaurante => {
        const card = document.createElement('div');
        card.className = 'restaurant-card';
        card.innerHTML = `
            <div class="restaurant-image" style="background-image: url('${restaurante.img_usuario || '../../img/default-avatar.jpg'}')"></div>
            <div class="restaurant-content">
                <h4 class="restaurant-title">${restaurante.nombre}</h4>
                <div class="restaurant-type">${restaurante.tipo_restaurante || 'Tipo no especificado'}</div>
                <div class="restaurant-actions">
                    <button class="view-restaurant-btn" data-id="${restaurante.id_restaurante}">Ver restaurante</button>
                    <div class="restaurant-icons">
                        <button class="icon-btn heart-icon" data-id="${restaurante.id_restaurante}">♥</button>
                    </div>
                </div>
            </div>
        `;
        gridRecetas.appendChild(card);
    });
    
    // Agregar eventos a los botones
    document.querySelectorAll('.view-restaurant-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const idRestaurante = this.getAttribute('data-id');
            window.location.href = `perfil_restaurante.html?id=${idRestaurante}`;
        });
    });
}

function cambiarTab(e) {
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    e.target.classList.add('active');
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
    // Aquí puedes redirigir a una página de edición o mostrar un modal
    window.location.href = 'editar_perfil.html';
}