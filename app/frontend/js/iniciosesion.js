document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('login-form');
    const email = document.getElementById('email-login');
    const password = document.getElementById('password-login');

    const campos = [email, password];

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
            case 'email-login':
                if (valor === '') {
                    mostrarError(input, 'El email es obligatorio.');
                } else if (!/^\S+@\S+\.\S+$/.test(valor)) {
                    mostrarError(input, 'Email no válido.');
                }
                break;
            case 'password-login':
                if (valor === '') {
                    mostrarError(input, 'La contraseña es obligatoria.');
                }
                break;
        }
    }

    form.addEventListener('submit', (e) => {
        e.preventDefault(); // Evita el envío del formulario

        let valido = true;

        campos.forEach(input => {
            validarCampo(input);
            if (input.parentNode.querySelector('.error-message')) valido = false;
        });

        if (valido) {
            console.log('Formulario válido. Puedes enviarlo o continuar...');
            // Aquí podrías enviar con fetch() o mostrar mensaje de éxito
        }
    });
});
