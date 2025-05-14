document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('register-form');
    const nombre = document.getElementById('nombre');
    const apellidos = document.getElementById('apellidos');
    const email = document.getElementById('email-register');
    const telefono = document.getElementById('telefono');
    const password = document.getElementById('password-register');
    const confirmPassword = document.getElementById('confirm-password');
    const terms = document.getElementById('terms');

    const campos = [nombre, apellidos, email, telefono, password, confirmPassword];

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
            case 'telefono':
                if (!/^[0-9]{9,15}$/.test(valor)) mostrarError(input, 'Teléfono inválido.');
                break;
            case 'password-register':
                if (valor.length < 6) mostrarError(input, 'Mínimo 6 caracteres.');
                break;
            case 'confirm-password':
                if (valor !== password.value) mostrarError(input, 'Las contraseñas no coinciden.');
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

        if (!terms.checked) {
            mostrarError(terms, 'Debes aceptar los términos.');
            valido = false;
        } else {
            limpiarError(terms);
        }

        if (valido) {
            console.log('Formulario válido. Puedes enviarlo o continuar...');
            // Aquí podrías enviar con fetch() o mostrar mensaje de éxito
        }
    });
});
