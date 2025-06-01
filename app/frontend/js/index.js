// Database initialization
fetch('../../../server/database.php')
  .then(response => {
    if (!response.ok) {
      console.error('Error al inicializar la base de datos');
    }
  })
  .catch(error => console.error('Error de conexión:', error));

document.addEventListener('DOMContentLoaded', async () => {
  try {
    const res = await fetch('../../backend/php/get_estadisticas.php');
    const data = await res.json();

    document.getElementById('stat-cocinero-camarero').textContent = `+${data.totalCocineroCamarero.toLocaleString()}`;
    document.getElementById('stat-restaurantes').textContent = `+${data.totalRestaurantes.toLocaleString()}`;
  } catch (e) {
    console.error('Error al cargar estadísticas:', e);
    document.getElementById('stat-cocinero-camarero').textContent = 'N/A';
    document.getElementById('stat-restaurantes').textContent = 'N/A';
  }
});


