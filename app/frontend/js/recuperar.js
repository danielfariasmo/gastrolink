document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('recuperarForm');
    const emailInput = document.getElementById('email');
    const emailError = document.getElementById('emailError');
    const formContainer = document.getElementById('formContainer');
    const confirmationContainer = document.getElementById('confirmationContainer');
    const emailConfirmation = document.getElementById('emailConfirmation');
    const resetButton = document.getElementById('resetButton');

    let emailValue = '';

    function validateEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

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

    emailInput.addEventListener('input', function(e) {
        emailValue = e.target.value;
        validateEmailInput();
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        if (!validateEmailInput()) return;

        // Enviar la solicitud al backend
        fetch('../../backend/php/recuperar_clave.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({ email: emailValue })
        })
        .then(response => response.text())
        .then(data => {
            if (data.includes('Correo enviado con éxito')) {
                emailConfirmation.textContent = emailValue;
                formContainer.classList.add('hidden');
                confirmationContainer.classList.remove('hidden');
            } else {
                emailError.textContent = data || 'Error al enviar el correo.';
                emailInput.classList.add('error');
            }
        })
        .catch(error => {
            console.error('Error en la solicitud:', error);
            emailError.textContent = 'Ocurrió un error inesperado.';
            emailInput.classList.add('error');
        });
    });

    resetButton.addEventListener('click', function() {
        emailInput.value = '';
        emailValue = '';
        emailError.textContent = '';
        emailInput.classList.remove('error');

        formContainer.classList.remove('hidden');
        confirmationContainer.classList.add('hidden');
    });
});
