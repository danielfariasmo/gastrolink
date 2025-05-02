document.addEventListener('DOMContentLoaded', function () {
    // Referencias a elementos del DOM
    const form = document.getElementById('contactoForm');
    const nombreInput = document.getElementById('nombre');
    const emailInput = document.getElementById('email');
    const asuntoInput = document.getElementById('asunto');
    const mensajeInput = document.getElementById('mensaje');

    const nombreError = document.getElementById('nombreError');
    const emailError = document.getElementById('emailError');
    const asuntoError = document.getElementById('asuntoError');
    const mensajeError = document.getElementById('mensajeError');

    const formContainer = document.getElementById('formContainer');
    const confirmationContainer = document.getElementById('confirmationContainer');
    const resetButton = document.getElementById('resetButton');

    // Objeto para almacenar el estado del formulario
    const formData = {
        nombre: '',
        email: '',
        asunto: '',
        mensaje: ''
    };

    // Función para validar el formulario
    function validateForm() {
        let isValid = true;
        let errors = {
            nombre: '',
            email: '',
            asunto: '',
            mensaje: ''
        };

        // Validar nombre
        if (formData.nombre.trim().length < 2) {
            errors.nombre = 'El nombre es requerido';
            nombreInput.classList.add('error');
            isValid = false;
        } else {
            errors.nombre = '';
            nombreInput.classList.remove('error');
        }

        // Validar email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(formData.email)) {
            errors.email = 'Ingresa un email válido';
            emailInput.classList.add('error');
            isValid = false;
        } else {
            errors.email = '';
            emailInput.classList.remove('error');
        }

        // Validar asunto
        if (formData.asunto.trim().length < 3) {
            errors.asunto = 'El asunto es requerido';
            asuntoInput.classList.add('error');
            isValid = false;
        } else {
            errors.asunto = '';
            asuntoInput.classList.remove('error');
        }

        // Validar mensaje
        if (formData.mensaje.trim().length < 10) {
            errors.mensaje = 'El mensaje debe tener al menos 10 caracteres';
            mensajeInput.classList.add('error');
            isValid = false;
        } else {
            errors.mensaje = '';
            mensajeInput.classList.remove('error');
        }

        // Actualizar mensajes de error
        nombreError.textContent = errors.nombre;
        emailError.textContent = errors.email;
        asuntoError.textContent = errors.asunto;
        mensajeError.textContent = errors.mensaje;

        return isValid;
    }

    // Validaciones en tiempo real
    nombreInput.addEventListener('input', function (e) {
        formData.nombre = e.target.value;
        validateForm();
    });

    emailInput.addEventListener('input', function (e) {
        formData.email = e.target.value;
        validateForm();
    });

    asuntoInput.addEventListener('input', function (e) {
        formData.asunto = e.target.value;
        validateForm();
    });

    mensajeInput.addEventListener('input', function (e) {
        formData.mensaje = e.target.value;
        validateForm();
    });

    // Envío del formulario
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        if (validateForm()) {
            // Mostrar confirmación
            formContainer.classList.add('hidden');
            confirmationContainer.classList.remove('hidden');

            // Aquí iría la lógica para enviar el formulario
        }
    });

    // Resetear el formulario
    resetButton.addEventListener('click', function () {
        // Limpiar campos
        nombreInput.value = '';
        emailInput.value = '';
        asuntoInput.value = '';
        mensajeInput.value = '';

        // Limpiar errores
        nombreError.textContent = '';
        emailError.textContent = '';
        asuntoError.textContent = '';
        mensajeError.textContent = '';

        // Quitar clases de error
        nombreInput.classList.remove('error');
        emailInput.classList.remove('error');
        asuntoInput.classList.remove('error');
        mensajeInput.classList.remove('error');

        // Resetear objeto de datos
        formData.nombre = '';
        formData.email = '';
        formData.asunto = '';
        formData.mensaje = '';

        // Mostrar formulario
        formContainer.classList.remove('hidden');
        confirmationContainer.classList.add('hidden');
    });
});