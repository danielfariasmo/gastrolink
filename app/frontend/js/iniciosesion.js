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
        e.preventDefault();
        let valido = true;

        campos.forEach(input => {
            validarCampo(input);
            if (input.parentNode.querySelector('.error-message')) valido = false;
        });

        if (valido) {
            validarUsuarioContra(email.value.trim(), password.value.trim());
        }
    });

    function mostrarMensajeLogin(mensaje, esError) {
        limpiarError(email);
        limpiarError(password);
        if (esError) {
            mostrarError(password, mensaje);
        }
    }

    function validarUsuarioContra(usuario, contra) {
        const formData = new FormData();
        formData.append("funcion", "validando");
        formData.append("usuario", usuario);
        formData.append("contra", contra);

        fetch("../../backend/php/iniciosesion.php", {
            method: "POST",
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error("Error en la solicitud: " + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            if (data.status === "success") {
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
            } else {
                mostrarMensajeLogin(data.message, true);
            }
        })
        .catch(error => {
            console.error("Error en la solicitud:", error);
            mostrarMensajeLogin("Error en la conexión. Inténtalo de nuevo.", true);
        });
    }
});