document.addEventListener("DOMContentLoaded", () => {
    function validarCampo(id, mensaje) {
        const campo = document.getElementById(id);
        if (!campo) return false;

        const valor = campo.value.trim();
        const errorId = id + "-error";
        let error = document.getElementById(errorId);

        if (valor === "") {
            if (!error) {
                error = document.createElement("div");
                error.id = errorId;
                error.className = "input-error";
                error.innerText = mensaje;
                campo.parentNode.appendChild(error);
            }
            campo.classList.add("input-invalid");
            return false;
        } else {
            if (error) error.remove();
            campo.classList.remove("input-invalid");
            return true;
        }
    }

    function validarFormulario() {
        const validaciones = [
            validarCampo("restaurant-name", "El nombre es obligatorio."),
            validarCampo("email", "El correo electrónico es obligatorio."),
            validarCampo("experiencia", "La experiencia es obligatoria."),
            validarCampo("especialidad", "La especialidad es obligatoria."),
            validarCampo("description", "La descripción es obligatoria.")
        ];
        return validaciones.every(v => v);
    }

    const form = document.getElementById("restaurant-form");
    if (form) {
        form.addEventListener("submit", function (e) {
            if (!validarFormulario()) {
                e.preventDefault();
            }
        });
    }

    const campos = [
        { id: "restaurant-name", mensaje: "El nombre es obligatorio." },
        { id: "email", mensaje: "El correo electrónico es obligatorio." },
        { id: "experiencia", mensaje: "La experiencia es obligatoria." },
        { id: "especialidad", mensaje: "La especialidad es obligatoria." },
        { id: "description", mensaje: "La descripción es obligatoria." }
    ];

    campos.forEach(({ id, mensaje }) => {
        const input = document.getElementById(id);
        if (input) {
            input.addEventListener("input", () => validarCampo(id, mensaje));
            input.addEventListener("blur", () => validarCampo(id, mensaje));
        }
    });

    window.volverAlPerfil = function () {
        window.history.back();
    };
});
