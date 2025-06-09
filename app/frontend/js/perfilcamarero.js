document.addEventListener('DOMContentLoaded', function () {
    const userData = JSON.parse(sessionStorage.getItem('userData'));

    if (!userData || userData.tipo_usuario !== 'camarero') {
        window.location.href = '../html/index.html';
        return;
    }

    cargarDatosCamarero(userData.id_usuario);

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
    const avatar = document.querySelector('.profile-avatar');
    if (avatar) {
        const rutaImagen = camarero.imagen
            ? camarero.imagen
            : '../../img/default-avatar.jpg';
        avatar.style.backgroundImage = `url('${rutaImagen}')`;
    }

    const h2 = document.querySelector('.profile-info h2');
    if (h2) h2.textContent = camarero.nombre;

    const tipo = document.querySelector('.profile-type');
    if (tipo) tipo.textContent = `Camarero (${camarero.idiomas})`;

    const detalles = document.querySelector('.profile-details');
    if (detalles) {
        detalles.innerHTML = `
            ${camarero.descripcion}<br>
            Experiencia: ${camarero.experiencia}<br>
            Correo: ${camarero.correo}
        `;
    }

    // Mostrar recetas por defecto al cargar
    actualizarRecetasFavoritas(recetasFavoritas);

    // Guardar datos para los tabs
    window.recetasGuardadas = recetasFavoritas;
    window.restaurantesGuardados = restaurantesFavoritos;
}

function actualizarRecetasFavoritas(recetas) {
    const gridRecetas = document.getElementById('favoritos-grid');
    gridRecetas.innerHTML = '';

    if (!recetas || recetas.length === 0) {
        gridRecetas.innerHTML = '<p>No hay recetas favoritas guardadas.</p>';
        return;
    }

    recetas.forEach(receta => {
        const card = document.createElement('a');
        card.className = 'mini-card';
        card.href = `detalle_recetas.html?id=${receta.id_receta}`;
        card.innerHTML = `
            <div class="mini-image">
                <img src="${receta.img_receta || '../../img/recetas/default.jpg'}" alt="${receta.titulo}">
            </div>
            <div class="mini-title">${receta.titulo}</div>
        `;
        gridRecetas.appendChild(card);
    });
}

function actualizarRestaurantesFavoritos(restaurantes) {
    const gridRecetas = document.getElementById('favoritos-grid');
    gridRecetas.innerHTML = '';

    if (!restaurantes || restaurantes.length === 0) {
        gridRecetas.innerHTML = '<p>No hay restaurantes favoritos guardados.</p>';
        return;
    }

    restaurantes.forEach(restaurante => {
        const card = document.createElement('a');
        card.className = 'mini-card';
        card.href = `detalles_restaurantes.html?id=${restaurante.id_restaurante}`;
        card.innerHTML = `
            <div class="mini-image">
                <img src="${restaurante.img_usuario || '../../img/default-avatar.jpg'}" alt="${restaurante.nombre}">
            </div>
            <div class="mini-title">${restaurante.nombre}</div>
        `;
        gridRecetas.appendChild(card);
    });
}


function cambiarTab(e) {
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    e.target.classList.add('active');

    const tab = e.target.textContent.trim();
    if (tab === 'Recetas') {
        actualizarRecetasFavoritas(window.recetasGuardadas);
    } else {
        actualizarRestaurantesFavoritos(window.restaurantesGuardados);
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
    const userData = JSON.parse(sessionStorage.getItem('userData'));
    if (userData && userData.id_usuario) {
        window.location.href = `edit_camarero.html?id=${userData.id_usuario}`;
    } else {
        mostrarError('No se pudo acceder al perfil');
    }
}

