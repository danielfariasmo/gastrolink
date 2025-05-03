document.addEventListener('DOMContentLoaded', function() {
    // Referencias a elementos del DOM
    const form = document.getElementById('recuperarForm');
    const emailInput = document.getElementById('email');
    const emailError = document.getElementById('emailError');
    const formContainer = document.getElementById('formContainer');
    const confirmationContainer = document.getElementById('confirmationContainer');
    const emailConfirmation = document.getElementById('emailConfirmation');
    const resetButton = document.getElementById('resetButton');

    // Variable para almacenar el email
    let emailValue = '';

    // Función para validar email
    function validateEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Función para mostrar u ocultar error
    function validateEmailInput() {
        if (emailValue.trim() === '') {
            emailError.textContent = 'El email es obligatorio';
            emailInput.classList.add('error');
            return false;
        } else if (!validateEmail(emailValue)) {
            emailError.textContent = 'Por favor, ingresa un email válido';
            emailInput.classList.add('error');
            return false;
        } else {
            emailError.textContent = '';
            emailInput.classList.remove('error');
            return true;
        }
    }

    // Validación en tiempo real
    emailInput.addEventListener('input', function(e) {
        emailValue = e.target.value;
        validateEmailInput();
    });

    // Envío del formulario
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        if (!validateEmailInput()) {
            return;
        }

        // Mostrar confirmación
        emailConfirmation.textContent = emailValue;
        formContainer.classList.add('hidden');
        confirmationContainer.classList.remove('hidden');

        // Aquí iría la lógica para enviar el email de recuperación
    });

    // Reseteo del formulario
    resetButton.addEventListener('click', function() {
        emailInput.value = '';
        emailValue = '';
        emailError.textContent = '';
        emailInput.classList.remove('error');

        formContainer.classList.remove('hidden');
        confirmationContainer.classList.add('hidden');
    });
});