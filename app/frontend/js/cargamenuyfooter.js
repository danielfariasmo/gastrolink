 // Función para cargar componentes PHP
 async function loadComponent(url, containerId) {
    try {
        const response = await fetch(url);
        const html = await response.text();
        document.getElementById(containerId).innerHTML = html;
    } catch (error) {
        console.error(`Error cargando ${url}:`, error);
    }
}

// Cargar componentes cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    //Carpeta que tiene el menu
    loadComponent('../../backend/components/menu.php', 'menu-container');
    //Carpeta que tiene el footer
    loadComponent('../../backend/components/footer.php', 'footer-container');
});