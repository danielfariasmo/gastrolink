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
            // case 'telefono':
            //     if (!/^[0-9]{9,15}$/.test(valor)) mostrarError(input, 'Teléfono inválido.');
            //     break;
            case 'password-register':
                if (valor.length < 6) mostrarError(input, 'Mínimo 6 caracteres.');
                break;
            // case 'confirm-password':
            //     if (valor !== password.value) mostrarError(input, 'Las contraseñas no coinciden.');
            //     break;
        }
    }

    // form.addEventListener('submit', (e) => {
    //     e.preventDefault(); // Evita el envío del formulario

    //     let valido = true;

    //     campos.forEach(input => {
    //         validarCampo(input);
    //         if (input.parentNode.querySelector('.error-message')) valido = false;
    //     });

    //     if (!terms.checked) {
    //         mostrarError(terms, 'Debes aceptar los términos.');
    //         valido = false;
    //     } else {
    //         limpiarError(terms);
    //     }

    //     if (valido) {
    //         console.log('Formulario válido. Puedes enviarlo o continuar...');
    //         // Aquí podrías enviar con fetch() o mostrar mensaje de éxito
    //     }
    // });

    async function registrarUsuario(datosUsuario) {
        try {
            const response = await fetch('../../backend/php/registro.php', {
                method: 'POST',
                body: datosUsuario
            });

            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }

            const data = await response.json();
            return data;
            
        } catch (error) {
            console.error('Error en el registro:', error);
            return {
                status: 'error',
                message: 'Error en la conexión. Inténtalo de nuevo.'
            };
        }
    }

    // Función para manejar el éxito del registro
    function manejarRegistroExitoso(data, email) {
        // Guardar datos del usuario en sessionStorage
        sessionStorage.setItem('userData', JSON.stringify({
            id: data.userId,
            nombre: data.nombre,
            correo: email,
            rol: data.role,
            fotoPerfil: data.fotoPerfil || '/ruta/a/imagen/default.png'
        }));

        // Redirigir según el rol
        const rutas = {
            restaurante: 'restaurante/dashboard.html',
            cocinero: 'cocinero/dashboard.html',
            camarero: 'camarero/dashboard.html'
        };

        window.location.href = rutas[data.role] || '../html/index.html';
    }

    // Función para preparar los datos del formulario
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

    // Event listener del formulario
    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        let valido = true;

        // Validación de campos
        campos.forEach(input => {
            validarCampo(input);
            if (input.parentNode.querySelector('.error-message')) valido = false;
        });

        // Validar términos
        if (!terms.checked) {
            mostrarError(terms, 'Debes aceptar los términos.');
            valido = false;
        } else {
            limpiarError(terms);
        }

        // Si todo es válido, proceder con el registro
        if (valido) {
            const datosFormulario = prepararDatosFormulario();
            const resultado = await registrarUsuario(datosFormulario);

            if (resultado.status === 'success') {
                manejarRegistroExitoso(resultado, email.value.trim());
            } else {
                mostrarMensaje(resultado.message, true);
            }
        }
    });

    function mostrarMensaje(mensaje, esError) {
        // Limpiamos errores previos
        limpiarError(email);
        limpiarError(password);

        if (esError) {
            mostrarError(terms, mensaje);
        } else {
            console.log(mensaje); // o mostrarlo en algún contenedor...
        }
    }
});
