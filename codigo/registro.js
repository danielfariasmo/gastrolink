document.addEventListener('DOMContentLoaded', function () {
    // Referencias a elementos del DOM
    const form = document.getElementById('registroForm');
    const nombreInput = document.getElementById('nombre');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirmPassword');
    const aceptaTerminosInput = document.getElementById('aceptaTerminos');
    const submitButton = document.getElementById('submitButton');

    // Mensajes de error
    const nombreError = document.getElementById('nombreError');
    const emailError = document.getElementById('emailError');
    const passwordError = document.getElementById('passwordError');
    const confirmPasswordError = document.getElementById('confirmPasswordError');
    const aceptaTerminosError = document.getElementById('aceptaTerminosError');

    // Estado del formulario
    const formData = {
        nombre: '',
        email: '',
        password: '',
        confirmPassword: '',
        aceptaTerminos: false
    };

    // Función de validación
    function validateForm() {
        let isValid = true;

        // Validar nombre
        if (formData.nombre.trim().length < 3) {
            nombreError.textContent = 'El nombre debe tener al menos 3 caracteres';
            nombreInput.classList.add('error');
            isValid = false;
        } else {
            nombreError.textContent = '';
            nombreInput.classList.remove('error');
        }

        // Validar email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(formData.email)) {
            emailError.textContent = 'Ingresa un email válido';
            emailInput.classList.add('error');
            isValid = false;
        } else {
            emailError.textContent = '';
            emailInput.classList.remove('error');
        }

        // Validar contraseña
        if (formData.password.length < 8) {
            passwordError.textContent = 'La contraseña debe tener al menos 8 caracteres';
            passwordInput.classList.add('error');
            isValid = false;
        } else if (!/[A-Z]/.test(formData.password)) {
            passwordError.textContent = 'La contraseña debe incluir al menos una mayúscula';
            passwordInput.classList.add('error');
            isValid = false;
        } else if (!/[0-9]/.test(formData.password)) {
            passwordError.textContent = 'La contraseña debe incluir al menos un número';
            passwordInput.classList.add('error');
            isValid = false;
        } else {
            passwordError.textContent = '';
            passwordInput.classList.remove('error');
        }

        // Validar confirmación de contraseña
        if (formData.password !== formData.confirmPassword) {
            confirmPasswordError.textContent = 'Las contraseñas no coinciden';
            confirmPasswordInput.classList.add('error');
            isValid = false;
        } else {
            confirmPasswordError.textContent = '';
            confirmPasswordInput.classList.remove('error');
        }

        // Validar aceptación de términos
        if (!formData.aceptaTerminos) {
            aceptaTerminosError.textContent = 'Debes aceptar los términos y condiciones';
            isValid = false;
        } else {
            aceptaTerminosError.textContent = '';
        }

        // Habilitar o deshabilitar el botón de envío
        submitButton.disabled = !isValid;

        return isValid;
    }

    // Validación en tiempo real
    nombreInput.addEventListener('input', (e) => {
        formData.nombre = e.target.value;
        validateForm();
    });

    emailInput.addEventListener('input', (e) => {
        formData.email = e.target.value;
        validateForm();
    });

    passwordInput.addEventListener('input', (e) => {
        formData.password = e.target.value;
        validateForm();
    });

    confirmPasswordInput.addEventListener('input', (e) => {
        formData.confirmPassword = e.target.value;
        validateForm();
    });

    aceptaTerminosInput.addEventListener('change', (e) => {
        formData.aceptaTerminos = e.target.checked;
        validateForm();
    });

    // Envío del formulario
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        if (validateForm()) {
            alert('Formulario enviado correctamente');
            // Aquí iría la lógica de envío real
        } else {
            alert('Por favor, corrige los errores en el formulario');
        }
    });

    // Desactivar el botón al inicio si el formulario está incompleto
    validateForm();
});