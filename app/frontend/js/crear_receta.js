document.addEventListener('DOMContentLoaded', function() {
    // Elementos del DOM
    const addRecipeBtn = document.querySelector('.add-recipe-btn');
    const popup = document.getElementById('add-recipe-popup');
    const closeBtn = document.querySelector('.close-popup');
    const recipeForm = document.getElementById('recipe-form');
    
    // Mostrar popup
    addRecipeBtn.addEventListener('click', function() {
        popup.style.display = 'flex';
    });
    
    // Ocultar popup
    closeBtn.addEventListener('click', function() {
        popup.style.display = 'none';
    });
    
    // Ocultar popup al hacer clic fuera del contenido
    popup.addEventListener('click', function(e) {
        if (e.target === popup) {
            popup.style.display = 'none';
        }
    });
    
    // Enviar formulario
    recipeForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Obtener datos del formulario
        const formData = new FormData(recipeForm);
        const userData = JSON.parse(sessionStorage.getItem('userData'));
        
        if (userData && userData.id_usuario) {
            formData.append('id_cocinero', userData.id_usuario);
            formData.append('action', 'create_recipe');
            
            // Enviar datos al servidor
            fetch("../../backend/php/crear_receta.php", {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Receta creada con éxito!');
                    popup.style.display = 'none';
                    recipeForm.reset();
                    // Recargar o actualizar la lista de recetas
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'No se pudo crear la receta'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al conectar con el servidor');
            });
        } else {
            alert('Debes iniciar sesión para crear recetas');
        }
    });
});