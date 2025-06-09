document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const idCamarero = urlParams.get('id');

    if (!idCamarero) {
        mostrarError('ID de camarero no proporcionado.');
        return;
    }

    cargarDatosCamarero(idCamarero);

    const form = document.getElementById('restaurant-form');
    const inputs = form.querySelectorAll('input:not([type="file"]), textarea');

    // Validación en tiempo real
    inputs.forEach(input => {
        input.addEventListener('input', () => validarCampo(input));
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        let valido = true;
        inputs.forEach(input => {
            if (!validarCampo(input)) {
                valido = false;
            }
        });

        if (valido) {
            enviarFormulario(idCamarero);
        }
    });
});

function cargarDatosCamarero(id) {
    fetch(`../../backend/php/get_camarero_by_id.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.camarero) {
                const c = data.camarero;

                setValue('restaurant-name', c.nombre);
                setValue('email', c.correo);
                setValue('experiencia', c.experiencia || '');
                setValue('idiomas', c.idiomas || '');
                setValue('description', c.descripcion || '');

                // Mostrar imagen si existe
                if (c.img_usuario) {
                    const coverContainer = document.querySelector('.image-upload');
                    const img = document.createElement('img');
                    img.src = c.img_usuario;
                    img.alt = 'Foto actual';
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
            }

        })
        .catch(error => {
            console.error('Error al cargar datos:', error);
            mostrarError('Error al cargar los datos del camarero.');
        });
}

function setValue(id, value) {
    const input = document.getElementById(id);
    if (input) input.value = value;
}

function validarCampo(input) {
    if (input.type === 'file') return true;

    const value = input.value.trim();
    const errorId = `${input.id}-error`;

    let valido = true;
    if (!value) {
        valido = false;
    } else if (input.type === 'email' && !/^\S+@\S+\.\S+$/.test(value)) {
        valido = false;
    }

    let error = document.getElementById(errorId);
    if (!error) {
        error = document.createElement('div');
        error.className = 'input-error';
        error.id = errorId;
        input.parentNode.appendChild(error);
    }

    if (!valido) {
        input.classList.add('input-invalid');
        error.textContent = 'Campo obligatorio o formato inválido';
    } else {
        input.classList.remove('input-invalid');
        error.textContent = '';
    }

    return valido;
}

function enviarFormulario(id) {
    const formData = new FormData();
    formData.append('id', id);
    formData.append('nombre', document.getElementById('restaurant-name').value.trim());
    formData.append('correo', document.getElementById('email').value.trim());
    formData.append('experiencia', document.getElementById('experiencia').value.trim());
    formData.append('idiomas', document.getElementById('idiomas').value.trim());
    formData.append('descripcion', document.getElementById('description').value.trim());

    const imagen = document.getElementById('cover-image').files[0];
    if (imagen) {
        formData.append('imagen', imagen);
    }

    fetch('../../backend/php/save_camarero.php', {
        method: 'POST',
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                window.location.href = `perfilcamarero.html?id=${id}`;
            } else {
                mostrarError(data.message || 'Error al guardar los datos.');
            }
        })
        .catch(error => {
            console.error('Error al enviar formulario:', error);
            mostrarError('Error en el servidor.');
        });
}

function mostrarError(msg) {
    alert(msg);
}

function volverAlPerfil() {
    window.history.back();
}
