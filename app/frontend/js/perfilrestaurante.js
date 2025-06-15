let editandoOfertaId = null;

function limpiarErrores() {
    document.querySelectorAll('.error-message').forEach(error => error.remove());
}

document.addEventListener('DOMContentLoaded', function () {
    const userData = JSON.parse(sessionStorage.getItem('userData'));

    if (!userData || userData.tipo_usuario !== 'restaurante') {
        window.location.href = '../html/index.html';
        return;
    }

    cargarDatosRestaurante(userData.id_usuario);

    document.querySelector('.edit-profile-btn').addEventListener('click', editarPerfil);
    document.querySelectorAll('.sidebar-item').forEach(item => {
        item.addEventListener('click', cambiarSeccion);
    });

    const addOfertaBtn = document.querySelector('.add-oferta-btn');
    const ofertaPopup = document.getElementById('add-oferta-popup');
    const closePopup = document.querySelector('.close-popup');
    const ofertaForm = document.getElementById('oferta-form');

    addOfertaBtn.addEventListener('click', () => {
        editandoOfertaId = null;
        ofertaForm.reset();
        limpiarErrores();
        document.getElementById('oferta-fecha').value = new Date().toISOString().split('T')[0];
        ofertaPopup.style.display = 'flex';
    });

    closePopup.addEventListener('click', () => ofertaPopup.style.display = 'none');
    ofertaPopup.addEventListener('click', e => {
        if (e.target === ofertaPopup) ofertaPopup.style.display = 'none';
    });

    ofertaForm.querySelectorAll('input, textarea, select').forEach(input => {
        input.addEventListener('blur', () => {
            if (!input.value.trim()) {
                mostrarErrorInput(input, 'Este campo es obligatorio.');
            } else {
                limpiarErrorInput(input);
            }
        });
    });

    ofertaForm.addEventListener('submit', function (e) {
        e.preventDefault();
        let valido = true;

        ofertaForm.querySelectorAll('input, textarea, select').forEach(input => {
            if (!input.value.trim()) {
                mostrarErrorInput(input, 'Este campo es obligatorio.');
                valido = false;
            } else {
                limpiarErrorInput(input);
            }
        });

        if (!valido) return;

        const formData = new FormData(ofertaForm);
        formData.append('id_restaurante', userData.id_usuario);

        const url = editandoOfertaId
            ? '../../backend/php/editar_oferta.php'
            : '../../backend/php/crear_oferta.php';

        if (editandoOfertaId) {
            formData.append('id_oferta', editandoOfertaId);
        }

        fetch(url, {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarPopupExito(editandoOfertaId ? 'Oferta actualizada con éxito' : 'Oferta creada con éxito');
                    ofertaPopup.style.display = 'none';
                    ofertaForm.reset();
                    cargarDatosRestaurante(userData.id_usuario);
                    editandoOfertaId = null;
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al enviar el formulario');
            });
    });

    document.getElementById('oferta-fecha').value = new Date().toISOString().split('T')[0];
});

function cargarDatosRestaurante(id) {
    fetch(`../../backend/php/get_p_restaurante.php?id_restaurante=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                actualizarUI(data.restaurante, data.ofertas, data.eventos);
            } else {
                mostrarError(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarError('Error al cargar los datos del restaurante');
        });
}

function actualizarUI(restaurante, ofertas, eventos) {
    const avatar = document.querySelector('.profile-avatar');
    avatar.style.backgroundImage = `url('${restaurante.imagen || '../../img/default-avatar.jpg'}')`;

    document.querySelector('.profile-info h2').textContent = restaurante.nombre || '';

    // Mostrar tipo solo si existe
    const tipoRestaurante = restaurante.tipo_restaurante ? `Restaurante (${restaurante.tipo_restaurante})` : '';
    document.querySelector('.profile-type').textContent = tipoRestaurante;

    // Web
    let webHtml = '';
    if (restaurante.web) {
        const webUrl = restaurante.web.startsWith('http')
            ? restaurante.web
            : 'https://' + restaurante.web;
        webHtml = `Web: <a href="${webUrl}" target="_blank">${webUrl}</a><br>`;
    }

    // Descripción, Dirección y Teléfono (solo si existen)
    const descripcion = restaurante.descripcion ? restaurante.descripcion + '<br>' : '';
    const direccion = restaurante.direccion ? `Dirección: ${restaurante.direccion}<br>` : '';
    const telefono = restaurante.telefono ? `Teléfono: ${restaurante.telefono}<br>` : '';

    document.querySelector('.profile-details').innerHTML = `
        ${descripcion}
        ${direccion}
        ${telefono}
        ${webHtml}
    `;

    actualizarOfertasEmpleo(ofertas, restaurante.imagen);

    if (eventos && eventos.length > 0) {
        actualizarEventos(eventos);
    }
}



function actualizarOfertasEmpleo(ofertas, imagen) {
    const companiesList = document.querySelector('.ofertas-list');
    companiesList.innerHTML = '';

    if (ofertas.length === 0) {
        companiesList.innerHTML = '<p>No hay ofertas de empleo publicadas.</p>';
        return;
    }

    const imageUrl = imagen || '../../img/default-avatar.jpg';

    ofertas.forEach(oferta => {
        const companyItem = document.createElement('div');
        companyItem.className = 'oferta-item';
        companyItem.innerHTML = `
            <div class="oferta-avatar" style="background-image: url('${imageUrl}')"></div>
            <div class="oferta-info">
                <h4 class="oferta-name">${oferta.titulo}</h4>
                <div class="oferta-details">
                    <div>Tipo: ${oferta.tipo_puesto}</div>
                    <div>Publicado: ${new Date(oferta.fecha_publicacion).toLocaleDateString()}</div>
                    <div>Estado: ${oferta.estado}</div>
                    <div>${oferta.descripcion}</div>
                </div>
            </div>
            <div class="oferta-actions">
                <button class="icon-btn edit-btn" data-id='${JSON.stringify(oferta)}'>✏️</button>
                <button class="icon-btn delete-btn" data-id="${oferta.id_oferta}">🗑️</button>
            </div>
        `;
        companiesList.appendChild(companyItem);
    });

    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const oferta = JSON.parse(this.getAttribute('data-id'));
            abrirEditarOferta(oferta);
        });
    });

    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const idOferta = this.getAttribute('data-id');
            eliminarOferta(idOferta);
        });
    });
}

function actualizarEventos(eventos) {
    console.log('Eventos:', eventos);
}

function cambiarSeccion(e) {
    document.querySelectorAll('.sidebar-item').forEach(item => item.classList.remove('active'));
    e.target.classList.add('active');

    const empleos = document.querySelector('.contenido-empleos');
    const guardados = document.querySelector('.contenido-guardados');

    if (!empleos || !guardados) return;

    if (e.target.textContent.trim().toLowerCase() === 'guardados') {
        empleos.style.display = 'none';
        guardados.style.display = 'block';
        cargarFavoritos();
    } else {
        empleos.style.display = 'block';
        guardados.style.display = 'none';
    }
}

document.querySelectorAll('#filtro-tabs .tab').forEach(tab => {
    tab.addEventListener('click', function () {
        document.querySelectorAll('#filtro-tabs .tab').forEach(t => t.classList.remove('active'));
        this.classList.add('active');
        cargarFavoritos();
    });
});

function cargarFavoritos() {
    const userData = JSON.parse(sessionStorage.getItem('userData'));
    const filtro = document.querySelector('#filtro-tabs .tab.active')?.dataset.filtro || 'todos';

    fetch(`../../backend/php/get_favoritos.php?id_usuario=${userData.id_usuario}`)
        .then(res => res.json())
        .then(data => {
            const contenedor = document.getElementById('favoritos-grid');

            contenedor.innerHTML = '';

            if (!data.success || (!data.restaurantes.length && !data.recetas.length)) {
                contenedor.innerHTML = '<p>No tienes elementos guardados.</p>';
                return;
            }

            if (filtro === 'todos' || filtro === 'restaurantes') {
                data.restaurantes.forEach(fav => {
                    contenedor.innerHTML += `
                        <a href="../html/detalles_restaurantes.html?id=${fav.id_usuario}" class="recipe-card">
                        <div class="recipe-card">
                            <div class="recipe-image">
                                <img src="${fav.img_usuario || '../../img/default-avatar.jpg'}" alt="${fav.nombre}">
                            </div>
                            <div class="recipe-details">
                                <h3 class="recipe-title">${fav.nombre}</h3>
                            </div>
                        </div>`;
                });
            }

            if (filtro === 'todos' || filtro === 'recetas') {
                data.recetas.forEach(receta => {
                    contenedor.innerHTML += `
                        <a href="../html/detalle_recetas.html?id=${receta.id_receta}" class="recipe-card">
                        <div class="recipe-card">
                            <div class="recipe-image">
                                <img src="${receta.imagen || '../../img/default-avatar.jpg'}" alt="${receta.nombre}">
                            </div>
                            <div class="recipe-details">
                                <h3 class="recipe-title">${receta.nombre}</h3>
                            </div>
                        </div>`;
                });
            }
        })
        .catch(err => {
            console.error('Error al cargar favoritos', err);
        });
}

function mostrarError(mensaje) {
    const main = document.querySelector('main');
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.textContent = mensaje;
    main.prepend(errorDiv);
    setTimeout(() => errorDiv.remove(), 5000);
}

function editarPerfil() {
    const userData = JSON.parse(sessionStorage.getItem('userData'));
    if (userData?.id_usuario) {
        window.location.href = `edit_restaurante.html?id=${userData.id_usuario}`;
    } else {
        alert("No se pudo identificar el usuario.");
    }
}

let ofertaAEliminar = null;

function eliminarOferta(idOferta) {
    ofertaAEliminar = idOferta;
    document.getElementById('confirm-delete-popup').style.display = 'flex';
}

document.getElementById('cancel-delete').addEventListener('click', () => {
    document.getElementById('confirm-delete-popup').style.display = 'none';
    ofertaAEliminar = null;
});

document.getElementById('close-delete-popup').addEventListener('click', () => {
    document.getElementById('confirm-delete-popup').style.display = 'none';
    ofertaAEliminar = null;
});

document.getElementById('confirm-delete').addEventListener('click', () => {
    if (!ofertaAEliminar) return;

    const userData = JSON.parse(sessionStorage.getItem('userData'));

    fetch('../../backend/php/eliminar_oferta.php', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            id_oferta: ofertaAEliminar,
            id_restaurante: userData.id_usuario
        })
    })
        .then(response => {
            if (!response.ok) throw new Error(`HTTP error ${response.status}`);
            return response.json();
        })
        .then(data => {
            if (data.success) {
                mostrarPopupExito('Oferta eliminada con éxito');
                cargarDatosRestaurante(userData.id_usuario);
            } else {
                throw new Error(data.message || 'Error desconocido');
            }
        })
        .catch(error => {
            console.error('Error al eliminar oferta:', error);
            alert(`Error al eliminar la oferta: ${error.message}`);
        })
        .finally(() => {
            document.getElementById('confirm-delete-popup').style.display = 'none';
            ofertaAEliminar = null;
        });
});

function abrirEditarOferta(oferta) {
    const form = document.getElementById('oferta-form');
    form.reset();
    limpiarErrores();

    document.getElementById('oferta-titulo').value = oferta.titulo;
    document.getElementById('oferta-descripcion').value = oferta.descripcion;
    document.getElementById('oferta-tipo').value = oferta.tipo_puesto;
    document.getElementById('oferta-fecha').value = oferta.fecha_publicacion;
    document.getElementById('oferta-estado').value = oferta.estado;

    editandoOfertaId = oferta.id_oferta;
    document.getElementById('add-oferta-popup').style.display = 'flex';
}

function mostrarErrorInput(input, mensaje) {
    let error = input.parentElement.querySelector('.error-message');
    if (!error) {
        error = document.createElement('div');
        error.className = 'error-message';
        error.style.color = 'red';
        error.style.fontSize = '13px';
        error.style.marginTop = '4px';
        input.parentElement.appendChild(error);
    }
    error.textContent = mensaje;
}

function limpiarErrorInput(input) {
    const error = input.parentElement.querySelector('.error-message');
    if (error) error.remove();
}

function mostrarPopupExito(mensaje) {
    const popup = document.getElementById('success-popup');
    document.getElementById('success-message').textContent = mensaje;
    popup.style.display = 'flex';
}

document.getElementById('close-success-popup').addEventListener('click', () => {
    document.getElementById('success-popup').style.display = 'none';
});
document.getElementById('ok-success-btn').addEventListener('click', () => {
    document.getElementById('success-popup').style.display = 'none';
});


