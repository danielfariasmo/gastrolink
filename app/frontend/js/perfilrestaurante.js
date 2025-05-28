document.addEventListener('DOMContentLoaded', function() {
    // Obtener datos del usuario desde sessionStorage
    const userData = JSON.parse(sessionStorage.getItem('userData'));
    
    // Verificar que el usuario es un restaurante
    if (!userData || userData.tipo_usuario !== 'restaurante') {
        window.location.href = '../html/index.html';
        return;
    }

    // Cargar datos del restaurante
    cargarDatosRestaurante(userData.id_usuario);
    
    // Configurar eventos
    document.querySelector('.edit-profile-btn').addEventListener('click', editarPerfil);
    document.querySelector('.add-company-btn').addEventListener('click', mostrarPopupEmpresa);
    document.querySelectorAll('.sidebar-item').forEach(item => {
        item.addEventListener('click', cambiarSeccion);
    });
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
    // Actualizar información del perfil
    const avatar = document.querySelector('.profile-avatar');
    if (restaurante.imagen) {
        avatar.style.backgroundImage = `url('${restaurante.imagen}')`;
    } else {
        avatar.style.backgroundImage = "url('../../img/default-avatar.jpg')";
    }
    
    document.querySelector('.profile-info h2').textContent = restaurante.nombre;
    document.querySelector('.profile-type').textContent = `Restaurante (${restaurante.tipo_restaurante})`;
    document.querySelector('.profile-details').innerHTML = `
        ${restaurante.descripcion}<br>
        Dirección: ${restaurante.direccion}<br>
        Teléfono: ${restaurante.telefono}<br>
        Web: <a href="${restaurante.web}" target="_blank">${restaurante.web}</a>
    `;
    
    // Actualizar ofertas de empleo
    actualizarOfertasEmpleo(ofertas);
    
    // Actualizar eventos (si existen)
    if (eventos && eventos.length > 0) {
        actualizarEventos(eventos);
    }
}

function actualizarOfertasEmpleo(ofertas) {
    const companiesList = document.querySelector('.companies-list');
    companiesList.innerHTML = '';
    
    if (ofertas.length === 0) {
        companiesList.innerHTML = '<p>No hay ofertas de empleo publicadas.</p>';
        return;
    }
    
    ofertas.forEach(oferta => {
        const companyItem = document.createElement('div');
        companyItem.className = 'company-item';
        companyItem.innerHTML = `
            <div class="company-avatar" style="background-image: url('${restaurante.imagen || '../../img/default-avatar.jpg'}')"></div>
            <div class="company-info">
                <h4 class="company-name">${oferta.titulo}</h4>
                <div class="company-details">
                    <div class="company-detail-line">Tipo: ${oferta.tipo_puesto}</div>
                    <div class="company-detail-line">Publicado: ${new Date(oferta.fecha_publicacion).toLocaleDateString()}</div>
                    <div class="company-detail-line">Estado: ${oferta.estado}</div>
                    <div class="company-detail-line">${oferta.descripcion.substring(0, 60)}...</div>
                </div>
            </div>
            <div class="company-actions">
                <button class="icon-btn edit-btn" data-id="${oferta.id_oferta}">
                    <i class="icon-edit">✏️</i>
                </button>
                <button class="icon-btn delete-btn" data-id="${oferta.id_oferta}">
                    <i class="icon-delete">🗑️</i>
                </button>
            </div>
        `;
        companiesList.appendChild(companyItem);
    });
    
    // Agregar eventos a los botones
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const idOferta = this.getAttribute('data-id');
            editarOferta(idOferta);
        });
    });
    
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const idOferta = this.getAttribute('data-id');
            eliminarOferta(idOferta);
        });
    });
}

function actualizarEventos(eventos) {
    // Implementar lógica para mostrar eventos si es necesario
    console.log('Eventos del restaurante:', eventos);
}

function cambiarSeccion(e) {
    document.querySelectorAll('.sidebar-item').forEach(item => {
        item.classList.remove('active');
    });
    e.target.classList.add('active');
    
    // Aquí puedes implementar la lógica para cambiar entre secciones
    console.log('Cambiando a sección:', e.target.textContent);
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
    window.location.href = 'editar_perfil.html';
}

function mostrarPopupEmpresa() {
    // Implementar lógica para mostrar popup de nueva oferta
    console.log('Mostrar popup para añadir empresa/oferta');
    // Aquí puedes mostrar un modal o redirigir a otra página
    window.location.href = 'nueva_oferta.html';
}

function editarOferta(idOferta) {
    // Implementar lógica para editar oferta
    console.log('Editar oferta con ID:', idOferta);
    window.location.href = `editar_oferta.html?id=${idOferta}`;
}

function eliminarOferta(idOferta) {
    if (confirm('¿Estás seguro de que quieres eliminar esta oferta?')) {
        const userData = JSON.parse(sessionStorage.getItem('userData'));
        
        fetch(`../../backend/php/eliminar_oferta.php?id_oferta=${idOferta}&id_restaurante=${userData.id_usuario}`, {
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
                alert('Oferta eliminada con éxito');
                cargarDatosRestaurante(userData.id_usuario);
            } else {
                throw new Error(data.message || 'Error desconocido al eliminar');
            }
        })
        .catch(error => {
            console.error('Error al eliminar oferta:', error);
            alert(`Error al eliminar la oferta: ${error.message}`);
        });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Elementos del popup
    const addOfertaBtn = document.querySelector('.add-oferta-btn');
    const ofertaPopup = document.getElementById('add-oferta-popup');
    const closePopup = document.querySelector('.close-popup');
    const ofertaForm = document.getElementById('oferta-form');
    
    // Mostrar popup al hacer clic en "Añadir oferta"
    addOfertaBtn.addEventListener('click', function() {
        ofertaPopup.style.display = 'flex';
    });
    
    // Cerrar popup
    closePopup.addEventListener('click', function() {
        ofertaPopup.style.display = 'none';
    });
    
    // Cerrar popup al hacer clic fuera del contenido
    ofertaPopup.addEventListener('click', function(e) {
        if (e.target === ofertaPopup) {
            ofertaPopup.style.display = 'none';
        }
    });
    
    // Manejar envío del formulario
    ofertaForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Aquí iría el código para enviar los datos al servidor
        // Puedes usar fetch() para enviar los datos
        
        // Ejemplo:
        const formData = new FormData(ofertaForm);
        
        fetch('../../backend/php/get_p_restaurante.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Oferta creada con éxito');
                ofertaPopup.style.display = 'none';
                ofertaForm.reset();
                // Aquí podrías recargar la lista de ofertas
            } else {
                alert('Error al crear la oferta: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al enviar el formulario');
        });
    });
    
    // Establecer fecha actual como predeterminada
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('oferta-fecha').value = today;
});