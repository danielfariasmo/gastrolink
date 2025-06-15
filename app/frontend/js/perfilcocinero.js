document.addEventListener('DOMContentLoaded', function () {
    const userData = JSON.parse(sessionStorage.getItem('userData'));

    if (!userData || userData.tipo_usuario !== 'cocinero') {
        window.location.href = '../html/index.html';
        return;
    }

    cargarDatosCocinero(userData.id_usuario);

    document.querySelector('.edit-profile-btn').addEventListener('click', editarPerfil);
    document.querySelector('.add-recipe-btn').addEventListener('click', mostrarPopupReceta);
    document.querySelector('.close-popup').addEventListener('click', ocultarPopupReceta);
    // document.getElementById('recipe-form').addEventListener('submit', guardarReceta);

    const btnGuardados = document.getElementById('btn-guardados');
    const btnRecetas = document.getElementById('btn-recetas');
    const recetasSection = document.querySelector('.content');
    const guardadosSection = document.querySelector('.contenido-guardados');

    btnGuardados.addEventListener('click', () => {
        recetasSection.style.display = 'none';
        guardadosSection.style.display = 'block';
        mostrarGuardados();
        btnGuardados.classList.add('active');
        btnRecetas.classList.remove('active');
    });

    btnRecetas.addEventListener('click', () => {
        guardadosSection.style.display = 'none';
        recetasSection.style.display = 'block';
        cargarDatosCocinero(userData.id_usuario);
        btnRecetas.classList.add('active');
        btnGuardados.classList.remove('active');
    });
    configurarValidacionesFormulario();
});

function cargarDatosCocinero(id) {
    fetch(`../../backend/php/get_cocinero.php?id_cocinero=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                actualizarUI(data.cocinero, data.recetas);
            } else {
                mostrarError(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarError('Error al cargar los datos del cocinero');
        });
}

function actualizarUI(cocinero, recetas) {
    const avatar = document.querySelector('.profile-avatar');
    avatar.style.backgroundImage = `url('${cocinero.imagen || '../../img/default-avatar.jpg'}')`;

    document.querySelector('.profile-info h2').textContent = cocinero.nombre || '';

    const especialidad = cocinero.especialidad || '';
    const descripcion = cocinero.descripcion || '';
    const experiencia = cocinero.experiencia || '';
    const correo = cocinero.correo || '';

    document.querySelector('.profile-type').textContent = especialidad
        ? `Cocinero (${especialidad})`
        : 'Cocinero';

    let html = '';
    if (descripcion) html += descripcion + '<br>';
    if (experiencia) html += `Experiencia: ${experiencia}<br>`;
    if (correo) html += `Correo: ${correo}`;

    document.querySelector('.profile-details').innerHTML = html;

    const gridRecetas = document.querySelector('.recipes-grid');
    gridRecetas.innerHTML = '';

    if (!recetas || recetas.length === 0) {
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
                        <button class="icon-btn delete-recipe-btn" data-id="${receta.id_receta}"><i class="fa-solid fa-trash"></i></button>
                    </div>
                </div>
            </div>
        `;
        gridRecetas.appendChild(card);
    });

    document.querySelectorAll('.view-recipe-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const idReceta = this.getAttribute('data-id');
            window.location.href = `detalle_recetas.html?id=${idReceta}`;
        });
    });

    document.querySelectorAll('.edit-recipe-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            const idReceta = this.getAttribute('data-id');
            editarReceta(idReceta);
        });
    });

    document.querySelectorAll('.delete-recipe-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            const idReceta = this.getAttribute('data-id');
            eliminarReceta(idReceta);
        });
    });
}

let filtroInicializado = false; 

function mostrarGuardados() {
    const userData = JSON.parse(sessionStorage.getItem('userData'));
    const grid = document.getElementById('favoritos-grid');
    const tabs = document.querySelectorAll('#filtro-tabs .tab');

    grid.innerHTML = '<p>Cargando guardados...</p>';

    fetch(`../../backend/php/get_guardados.php?id_usuario=${userData.id_usuario}`)
        .then(res => res.json())
        .then(data => {
            grid.innerHTML = '';

            const recetas = data.recetas || [];
            const restaurantes = data.restaurantes || [];

            // Crear tarjetas de recetas
            recetas.forEach(receta => {
                const card = document.createElement('div');
                card.className = 'recipe-card card-receta';
                card.setAttribute('data-tipo', 'receta');
                card.innerHTML = `
                    <div class="recipe-image" style="background-image: url('${receta.img_receta || '../../img/recetas/default.jpg'}')"></div>
                    <h4 class="recipe-title">${receta.titulo}</h4>
                `;
                card.addEventListener('click', () => {
                    window.location.href = `detalle_recetas.html?id=${receta.id_receta}`;
                });
                grid.appendChild(card);
            });

            // Crear tarjetas de restaurantes
            restaurantes.forEach(rest => {
                const card = document.createElement('div');
                card.className = 'recipe-card card-restaurante';
                card.setAttribute('data-tipo', 'restaurante');
                card.innerHTML = `
                    <div class="recipe-image" style="background-image: url('${rest.img_usuario || '../../img/restaurantes/default.jpg'}')"></div>
                    <h4 class="recipe-title">${rest.nombre}</h4>
                `;
                card.addEventListener('click', () => {
                    window.location.href = `detalles_restaurantes.html?id=${rest.id_restaurante}`;
                });
                grid.appendChild(card);
            });

            if (recetas.length === 0 && restaurantes.length === 0) {
                grid.innerHTML = '<p>No tienes elementos guardados aún.</p>';
            }

            // Activar funcionalidad de filtros SIEMPRE después de renderizar
            tabs.forEach(tab => {
                tab.onclick = () => {
                    tabs.forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');

                    const filtro = tab.dataset.filtro;

                    document.querySelectorAll('#favoritos-grid .recipe-card').forEach(card => {
                        const tipo = card.getAttribute('data-tipo');
                        card.style.display = (filtro === 'todos' || filtro === tipo) ? 'block' : 'none';
                    });
                };
            });

            // Aplicar filtro actual por si ya estaba activo
            const activeFiltro = document.querySelector('#filtro-tabs .tab.active')?.dataset.filtro || 'todos';

            document.querySelectorAll('#favoritos-grid .recipe-card').forEach(card => {
                const tipo = card.getAttribute('data-tipo');
                card.style.display = (activeFiltro === 'todos' || activeFiltro === tipo) ? 'block' : 'none';
            });

        })
        .catch(err => {
            console.error('Error al cargar guardados:', err);
            grid.innerHTML = '<p>Error al mostrar los elementos guardados.</p>';
        });
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
    if (userData?.id_usuario) {
        window.location.href = `edit_cocinero.html?id=${userData.id_usuario}`;
    } else {
        alert("No se pudo identificar el usuario.");
    }
}

function mostrarPopupReceta() {
    document.getElementById('add-recipe-popup').style.display = 'flex';
}

function ocultarPopupReceta() {
    document.getElementById('recipe-form').reset();
    document.getElementById('add-recipe-popup').style.display = 'none';
}


function configurarValidacionesFormulario() {
    const form = document.getElementById('recipe-form');
    const inputs = form.querySelectorAll('input, textarea, select');
    
    // Validación en tiempo real
    inputs.forEach(input => {
        input.addEventListener('input', () => validarCampo(input));
        input.addEventListener('blur', () => validarCampo(input));
    });
    
    // Validación al enviar
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        let formularioValido = true;
        inputs.forEach(input => {
            if (!validarCampo(input)) {
                formularioValido = false;
            }
        });
        
        if (formularioValido) {
            guardarReceta(e);
        } else {
            mostrarError('Por favor, corrige los errores en el formulario');
        }
    });
}

function validarCampo(input) {
    const value = input.value.trim();
    const errorId = `${input.id}-error`;
    let errorElement = document.getElementById(errorId);
    
    if (!errorElement) {
        errorElement = document.createElement('div');
        errorElement.id = errorId;
        errorElement.className = 'input-error';
        input.parentNode.appendChild(errorElement);
    }
    
    // Validaciones específicas por tipo de campo
    let valido = true;
    let mensajeError = '';
    
    if (input.required && !value) {
        valido = false;
        mensajeError = 'Este campo es obligatorio';
    } else if (input.type === 'email' && !/^\S+@\S+\.\S+$/.test(value)) {
        valido = false;
        mensajeError = 'Ingresa un email válido';
    } else if (input.type === 'number' && value < 0) {
        valido = false;
        mensajeError = 'El valor no puede ser negativo';
    } else if (input.id === 'recipe-time' && (value < 1 || value > 1000)) {
        valido = false;
        mensajeError = 'El tiempo debe estar entre 1 y 1000 minutos';
    } else if (input.id === 'recipe-portions' && (value < 1 || value > 50)) {
        valido = false;
        mensajeError = 'Las porciones deben estar entre 1 y 50';
    }
    
    // Aplicar estilos según validación
    if (valido) {
        input.classList.remove('input-invalid');
        errorElement.style.display = 'none';
    } else {
        input.classList.add('input-invalid');
        errorElement.textContent = mensajeError;
        errorElement.style.display = 'block';
    }
    
    return valido;
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
                // alert('Receta creada con éxito');
                document.getElementById('recipe-form').reset();
                ocultarPopupReceta();
                cargarDatosCocinero(userData.id_usuario);
            } else {
                mostrarError('Error: ' + (data.message || 'Error desconocido al guardar'));
            }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarError('Error de conexión al guardar la receta');
    });
}

function editarReceta(idReceta) {
    console.log('Editar receta', idReceta);
}

function eliminarReceta(idReceta) {
    if (confirm('¿Estás seguro de que quieres eliminar esta receta?')) {
        const userData = JSON.parse(sessionStorage.getItem('userData'));

        fetch(`../../backend/php/eliminar_receta.php?id_receta=${idReceta}&id_cocinero=${userData.id_usuario}`, {
            method: 'DELETE'
        })
            .then(response => {
                if (!response.ok) throw new Error(`Error HTTP: ${response.status}`);
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // alert('Receta eliminada con éxito');
                    cargarDatosCocinero(userData.id_usuario);
                } else {
                    throw new Error(data.message || 'Error desconocido al eliminar');
                }
            })
            .catch(error => {
                console.error('Error al eliminar receta:', error);
                // alert(`Error al eliminar la receta: ${error.message}`);
            });
    }
}