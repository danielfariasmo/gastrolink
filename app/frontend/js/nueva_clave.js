document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('nuevaContrasenaForm');
    const nuevaContrasenaInput = document.getElementById('nuevaContrasena');

    const queryParams = new URLSearchParams(window.location.search);
    const token = queryParams.get('token');
    const email = queryParams.get('email');

    if (!token || !email) {
        alert('Falta información para cambiar la contraseña.');
        form.querySelector('button').disabled = true;
        return;
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const clave = nuevaContrasenaInput.value.trim();

        const datos = new URLSearchParams();
        datos.append('token', token);
        datos.append('email', email);
        datos.append('nuevaContrasena', clave);

        fetch('../../backend/php/nueva_clave.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: datos.toString()
        })
        .then(res => res.text())
        .then(data => {
            if (data.includes('actualizada')) {
                alert('Contraseña actualizada correctamente. Ahora puedes iniciar sesión.');
                window.location.href = 'index.html';
            } else {
                alert('Error: ' + data);
            }
        })
        .catch(err => {
            console.error(err);
            alert('Error inesperado al intentar guardar la contraseña.');
        });
    });
});
