document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    const id_restaurante = params.get('id');

    console.log("ID capturado:", id_restaurante);


    if (!id_restaurante) {
        console.error("ID del restaurante no especificado");
        return;
    }

    fetch(`../../backend/php/get_restaurante_by_id.php?id_restaurante=${id_restaurante}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.restaurante) {
                const r = data.restaurante;

                document.getElementById('restaurant-name').value = r.nombre_restaurante || '';
                document.getElementById('cuisine-type').value = normalizarTipo(r.tipo_restaurante) || '';
                document.getElementById('description').value = r.descripcion || '';
                document.getElementById('address').value = r.direccion || '';
                document.getElementById('phone').value = r.telefono || '';
                document.getElementById('email').value = r.correo || '';
                document.getElementById('website').value = r.web || '';

                const radios = document.getElementsByName('price-range');
                radios.forEach(radio => {
                    if (radio.value === convertirPrecioASimbolo(r.rango_precio)) {
                        radio.checked = true;
                    }
                });

                if (r.img_usuario) {
                    const coverContainer = document.querySelector('.image-upload');
                    const img = document.createElement('img');
                    img.src = r.img_usuario;
                    img.alt = 'Imagen principal';
                    img.style.maxWidth = '120px';
                    img.style.borderRadius = '8px';
                    img.style.marginBottom = '10px';
                    coverContainer.insertBefore(img, coverContainer.firstChild);

                    const coverInput = document.getElementById('cover-image');
                    coverInput.addEventListener('change', () => {
                        const file = coverInput.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = e => {
                                const oldImg = coverContainer.querySelector('img');
                                if (oldImg) oldImg.remove();

                                const newImg = document.createElement('img');
                                newImg.src = e.target.result;
                                newImg.alt = 'Imagen seleccionada';
                                newImg.style.maxWidth = '120px';
                                newImg.style.borderRadius = '8px';
                                newImg.style.marginBottom = '10px';
                                coverContainer.insertBefore(newImg, coverContainer.firstChild);
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                }

                mostrarGaleria(r.imagenes || []);
                mostrarHorarioEditable(r.horarios || []);
            } else {
                mostrarGaleria([]);
                mostrarHorarioEditable([]);
            }
        })
        .catch(err => {
            console.error("Error al obtener datos del restaurante", err);
        });

    const form = document.getElementById('restaurant-form');
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        if (!validarFormulario()) return;
        console.log("✅ Validación OK. Listo para enviar.");
    });

    ['restaurant-name', 'cuisine-type', 'description', 'address', 'phone', 'email', 'website'].forEach(id => {
        const campo = document.getElementById(id);
        campo.addEventListener('input', () => validarFormulario());
    });

    document.getElementById('phone').addEventListener('blur', formatearTelefono);
});

function validarCampo(campo, condicion, mensaje) {
    const errorId = campo.id + '-error';
    let error = document.getElementById(errorId);

    if (!condicion(campo.value.trim())) {
        if (!error) {
            error = document.createElement('div');
            error.id = errorId;
            error.className = 'input-error';
            error.textContent = mensaje;
            campo.parentNode.appendChild(error);
        }
        campo.classList.add('input-invalid');
        return false;
    } else {
        if (error) error.remove();
        campo.classList.remove('input-invalid');
        return true;
    }
}

function validarFormulario() {
    const campos = [
        {
            id: 'restaurant-name',
            mensaje: 'El nombre es obligatorio',
            condicion: val => val !== ''
        },
        {
            id: 'cuisine-type',
            mensaje: 'El tipo de cocina es obligatorio',
            condicion: val => val !== ''
        },
        {
            id: 'description',
            mensaje: 'La descripción es obligatoria',
            condicion: val => val !== ''
        },
        {
            id: 'address',
            mensaje: 'La dirección es obligatoria',
            condicion: val => val !== ''
        },
        {
            id: 'phone',
            mensaje: 'Introduce un teléfono válido (+34 911 111 111)',
            condicion: val => /^\+\d{2}\s\d{3}\s\d{3}\s\d{3}$/.test(val)
        },
        {
            id: 'email',
            mensaje: 'Introduce un correo válido',
            condicion: val => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)
        },
        {
            id: 'website',
            mensaje: 'La web debe comenzar con www.',
            condicion: val => /^www\.[^\s]+(\.[^\s]+)+$/.test(val)
        }
    ];

    let valid = true;
    for (const campo of campos) {
        const input = document.getElementById(campo.id);
        if (!validarCampo(input, campo.condicion, campo.mensaje)) {
            valid = false;
        }
    }

    const precio = document.querySelector('input[name="price-range"]:checked');
    if (!precio) {
        alert("Selecciona un rango de precio.");
        valid = false;
    }

    return valid;
}

function formatearTelefono() {
    const phoneInput = document.getElementById('phone');
    let valor = phoneInput.value.replace(/[^\d]/g, '');
    if (valor.length >= 9) {
        valor = valor.slice(-9);
        phoneInput.value = `+34 ${valor.slice(0, 3)} ${valor.slice(3, 6)} ${valor.slice(6, 9)}`;
    }
}

function volverAlPerfil() {
    const params = new URLSearchParams(window.location.search);
    const id = params.get('id');
    if (id) {
        window.location.href = `perfil_restaurante.html?id=${id}`;
    } else {
        window.history.back();
    }
}

function normalizarTipo(tipoBD) {
    const mapa = {
        'Mediterráneo': 'mediterranea',
        'Carnes': 'carnes',
        'Gourmet': 'gourmet',
        'Vegetariano': 'vegetariana',
        'Asiático': 'asiatica',
        'Italiano': 'italiana',
        'Mariscos': 'mariscos',
        'Tapas': 'tapas',
        'Francés': 'francesa',
        'Cafés': 'cafes'
    };
    return mapa[tipoBD] || '';
}

function convertirPrecioASimbolo(precio) {
    const valor = parseFloat(precio.split('-')[0]);
    if (valor <= 15) return '€';
    if (valor <= 30) return '€€';
    if (valor <= 50) return '€€€';
    return '€€€€';
}



function mostrarGaleria(imagenes) {
    const galleryManager = document.querySelector('.gallery-manager');
    galleryManager.innerHTML = '';

    imagenes.forEach((imgData) => {
        const item = document.createElement('div');
        item.className = 'gallery-item gallery-upload';

        const img = document.createElement('img');
        img.src = imgData.url_imagen;
        img.alt = imgData.alt || '';
        img.style.maxWidth = '100px';
        img.style.borderRadius = '8px';
        img.style.marginBottom = '8px';

        const input = document.createElement('input');
        input.type = 'file';
        input.name = 'gallery[]';
        input.accept = 'image/*';
        input.style.display = 'none';

        const deleteBtn = document.createElement('button');
        deleteBtn.type = 'button';
        deleteBtn.textContent = '❌';
        deleteBtn.className = 'btn-delete';
        deleteBtn.onclick = (e) => {
            e.stopPropagation();
            item.remove();
        };

        item.appendChild(img);
        item.appendChild(input);
        item.appendChild(deleteBtn);

        item.onclick = () => input.click();

        galleryManager.appendChild(item);
    });

    const addBtnContainer = document.createElement('div');
    addBtnContainer.className = 'gallery-item';

    const addButton = document.createElement('button');
    addButton.type = 'button';
    addButton.textContent = '➕ Añadir imagen';
    addButton.className = 'btn btn-outline';
    addButton.onclick = agregarCampoGaleria;

    addBtnContainer.appendChild(addButton);
    galleryManager.appendChild(addBtnContainer);
}

function agregarCampoGaleria() {
    const galleryManager = document.querySelector('.gallery-manager');

    const item = document.createElement('div');
    item.className = 'gallery-item gallery-upload';
    item.style.cursor = 'pointer';

    const input = document.createElement('input');
    input.type = 'file';
    input.name = 'gallery[]';
    input.accept = 'image/*';
    input.style.display = 'none';

    const placeholder = document.createElement('span');
    placeholder.textContent = '📷 Añadir imagen';
    placeholder.style.display = 'block';
    placeholder.style.textAlign = 'center';
    placeholder.style.padding = '10px';
    placeholder.style.border = '1px dashed #ccc';
    placeholder.style.borderRadius = '8px';
    placeholder.style.background = '#f9f9f9';

    const deleteBtn = document.createElement('button');
    deleteBtn.type = 'button';
    deleteBtn.textContent = '❌';
    deleteBtn.className = 'btn-delete';
    deleteBtn.style.marginTop = '5px';
    deleteBtn.onclick = (e) => {
        e.stopPropagation();
        item.remove();
    };

    input.addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '100px';
                img.style.borderRadius = '8px';
                img.style.marginBottom = '8px';

                if (placeholder) placeholder.remove();
                item.insertBefore(img, input);
            };
            reader.readAsDataURL(file);
        }
    });

    item.onclick = () => input.click();

    item.appendChild(placeholder);
    item.appendChild(input);
    item.appendChild(deleteBtn);

    galleryManager.insertBefore(item, galleryManager.lastChild);
}

function mostrarHorarioEditable(horarios) {
    const diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
    const horariosMap = {};

    horarios.forEach(h => {
        horariosMap[h.dia_semana] = h;
    });

    const contenedor = document.createElement('div');
    contenedor.id = 'horario-container';
    contenedor.classList.add('horario-container');

    const h2 = document.createElement('h2');
    h2.textContent = '⏰ Horario Semanal';
    h2.classList.add('section-title');

    diasSemana.forEach(dia => {
        const fila = document.createElement('div');
        fila.classList.add('horario-fila');

        const apertura = horariosMap[dia]?.hora_apertura?.slice(0, 5) || '';
        const cierre = horariosMap[dia]?.hora_cierre?.slice(0, 5) || '';

        fila.innerHTML = `
            <label><strong>${dia}:</strong></label>
            <input type="time" name="apertura_${dia}" value="${apertura}"> -
            <input type="time" name="cierre_${dia}" value="${cierre}">
        `;

        contenedor.appendChild(fila);
    });

    const section = document.createElement('div');
    section.classList.add('form-section');
    section.appendChild(h2);
    section.appendChild(contenedor);

    const form = document.querySelector('#restaurant-form');
    form.insertBefore(section, form.querySelector('.actions'));
}
