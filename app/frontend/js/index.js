// Database initialization
fetch('../../../server/database.php')
  .then(response => {
    if (!response.ok) {
      console.error('Error al inicializar la base de datos');
    }
  })
  .catch(error => console.error('Error de conexión:', error));

