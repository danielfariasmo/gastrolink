  // Definir los eventos con fechas específicas
  const eventos = [
    { fecha: '2025-05-15', titulo: 'Taller de Cocina Mediterránea', descripcion: 'Aprende a preparar los platos más emblemáticos de la dieta mediterránea.', ubicacion: 'Centro Gastronómico Madrid' },
    { fecha: '2025-05-22', titulo: 'Masterclass de Repostería Creativa', descripcion: 'Descubre técnicas avanzadas de repostería y decoración de la mano de pasteleros profesionales.', ubicacion: 'Escuela de Cocina de mi casa' },
    { fecha: '2025-06-05', titulo: 'Cata de Vinos Españoles', descripcion: 'Una experiencia sensorial para descubrir los mejores vinos de las denominaciones de origen españolas.', ubicacion: 'Cuenca' },
];

let currentDate = new Date();

function renderCalendar() {
    // Obtener el mes y año actual
    const month = currentDate.getMonth();
    const year = currentDate.getFullYear();

    // Actualizar el título del mes
    const monthNames = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
    document.getElementById('calendar-title').innerText = `${monthNames[month]} ${year}`;

    // Obtener el primer día del mes y la cantidad de días en el mes
    const firstDayOfMonth = new Date(year, month, 1);
    const lastDayOfMonth = new Date(year, month + 1, 0);
    const daysInMonth = lastDayOfMonth.getDate();
    const firstDay = firstDayOfMonth.getDay(); // Día de la semana en el que comienza el mes

    // Limpiar la grilla del calendario
    const calendarGrid = document.getElementById('calendar-grid');
    calendarGrid.innerHTML = '';

    // Agregar celdas vacías hasta el primer día
    for (let i = 0; i < firstDay; i++) {
        const emptyCell = document.createElement('div');
        calendarGrid.appendChild(emptyCell);
    }

    // Agregar los días del mes
    for (let day = 1; day <= daysInMonth; day++) {
        const dayCell = document.createElement('div');
        dayCell.classList.add('calendar-day');
        dayCell.textContent = day;

        // Verificar si hay evento en el día
        const eventDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        const event = eventos.find(event => event.fecha === eventDate);
        if (event) {
            dayCell.classList.add('has-event');
            dayCell.addEventListener('click', () => {
                alert(`Evento: ${event.titulo}\n${event.descripcion}\nUbicación: ${event.ubicacion}`);
            });
        }

        calendarGrid.appendChild(dayCell);
    }
}

// Función para navegar entre meses , las dos funciones sirven para que funcionen los meses el previus y el next que es el anterior y el siguiente
document.getElementById('prev-month-btn').addEventListener('click', () => {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar();
});

document.getElementById('next-month-btn').addEventListener('click', () => {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar();
});

// Función para suscribirse
// Te salta si el email no es válido
function suscribirse() {
    const email = document.getElementById('email-subscription').value;
    if (email && email.includes('@')) {
        // Podemos cambiar el mensaje  
        alert('¡Gracias por suscribirte! Recibirás notificaciones sobre nuestros próximos eventos.');
        document.getElementById('email-subscription').value = '';
    } else {
        alert('Por favor, introduce un email válido.');
    }
}

// Inicializar el calendario
renderCalendar();