document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('register-form');
    const nombre = document.getElementById('nombre');
    const apellidos = document.getElementById('apellidos');
    const email = document.getElementById('email-register');
    const fotoPerfil = document.getElementById('profilePhoto');
    const password = document.getElementById('password-register');
    const rol = document.getElementById('userType');
    const terms = document.getElementById('terms');
    const campos = [nombre, apellidos, email, password];

    // Función para mostrar o quitar errores
    function mostrarError(input, mensaje) {
        let error = input.nextElementSibling;
        if (!error || !error.classList.contains('error-message')) {
            error = document.createElement('div');
            error.className = 'error-message';
            input.parentNode.appendChild(error);
        }
        error.textContent = mensaje;
    }

    function limpiarError(input) {
        const error = input.parentNode.querySelector('.error-message');
        if (error) error.remove();
    }

    // Validación en tiempo real
    campos.forEach(input => {
        input.addEventListener('input', () => validarCampo(input));
    });

    function validarCampo(input) {
        const valor = input.value.trim();
        limpiarError(input);

        switch (input.id) {
            case 'nombre':
            case 'apellidos':
                if (valor === '') mostrarError(input, 'Este campo es obligatorio.');
                break;
            case 'email-register':
                if (!/^\S+@\S+\.\S+$/.test(valor)) mostrarError(input, 'Email no válido.');
                break;
            case 'password-register':
                if (valor.length < 6) mostrarError(input, 'Mínimo 6 caracteres.');
                break;
        }
    }

    async function registrarUsuario(datosUsuario) {
        try {
            const response = await fetch('../../backend/php/registro.php', {
                method: 'POST',
                body: datosUsuario
            });

            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return await response.json();
        } catch (error) {
            console.error('Error en el registro:', error);
            return {
                status: 'error',
                message: 'Error en la conexión. Inténtalo de nuevo.'
            };
        }
    }

    function manejarRegistroExitoso(data) {
        // Guardar datos del usuario en sessionStorage con la nueva estructura
        const userData = {
            id_usuario: data.userData.id_usuario,
            nombre: data.userData.nombre,
            correo: data.userData.correo,
            tipo_usuario: data.userData.tipo_usuario,
            img_usuario: data.userData.img_usuario
        };
        sessionStorage.setItem('userData', JSON.stringify(userData));

        // Redirigir a la página principal con parámetro de login exitoso
        window.location.href = '../html/index.html?login=success';
    }

    function prepararDatosFormulario() {
        const formData = new FormData();
        formData.append('funcion', 'registrar');
        formData.append('nombre', nombre.value.trim());
        formData.append('apellidos', apellidos.value.trim());
        formData.append('email', email.value.trim());
        formData.append('password', password.value);
        formData.append('rol', rol.value);
        
        if (fotoPerfil.files[0]) {
            formData.append('fotoPerfil', fotoPerfil.files[0]);
        }
        
        return formData;
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        let valido = true;

        campos.forEach(input => {
            validarCampo(input);
            if (input.parentNode.querySelector('.error-message')) valido = false;
        });

        if (!terms.checked) {
            mostrarError(terms, 'Debes aceptar los términos.');
            valido = false;
        } else {
            limpiarError(terms);
        }

        if (valido) {
            const datosFormulario = prepararDatosFormulario();
            const resultado = await registrarUsuario(datosFormulario);

            if (resultado.status === 'success') {
                manejarRegistroExitoso(resultado);
            } else {
                mostrarMensaje(resultado.message, true);
            }
        }
    });

    function mostrarMensaje(mensaje, esError) {
        limpiarError(email);
        limpiarError(password);
        if (esError) {
            mostrarError(terms, mensaje);
        }
    }
});