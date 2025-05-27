document.addEventListener('DOMContentLoaded', function () {
    // Marcar el día actual en el horario
    const days = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
    const today = new Date().getDay();
    const todayName = days[today];

    document.querySelectorAll('.hours-day').forEach(day => {
        if (day.textContent === todayName) {
            day.classList.add('today');
        } else {
            day.classList.remove('today');
        }
    });

    // Establecer la fecha mínima en el formulario de reserva como hoy
    const dateInput = document.getElementById('date');
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');

    dateInput.min = `${year}-${month}-${day}`;
    dateInput.value = `${year}-${month}-${day}`;

    // Funcionalidad para el formulario de reserva
    const reservationForm = document.querySelector('.reservation-form');
    reservationForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const date = document.getElementById('date').value;
        const time = document.getElementById('time').value;
        const guests = document.getElementById('guests').value;
        const name = document.getElementById('name').value;

        alert(`Reserva confirmada para ${name} el ${date} a las ${time} para ${guests} personas.`);
    });

    // Funcionalidad para los botones de acción
    const callButton = document.querySelector('.action-button:nth-child(1)');
    callButton.addEventListener('click', function () {
        window.location.href = 'tel:+34911234567';
    });

    const saveButton = document.querySelector('.action-button:nth-child(2)');
    saveButton.addEventListener('click', function () {
        this.innerHTML = '<span class="action-icon">✓</span> Guardado';
        setTimeout(() => {
            this.innerHTML = '<span class="action-icon">💾</span> Guardar';
        }, 2000);
    });

    const rateButton = document.querySelector('.action-button:nth-child(3)');
    rateButton.addEventListener('click', function () {
        alert('¡Gracias por valorar La Oliva!');
    });

    const shareButton = document.querySelector('.action-button.primary');
    shareButton.addEventListener('click', function () {
        if (navigator.share) {
            navigator.share({
                title: 'La Oliva - Restaurante Mediterráneo',
                text: 'Te recomiendo este restaurante de cocina mediterránea en Madrid',
                url: window.location.href
            });
        } else {
            alert('Compartir: ' + window.location.href);
        }
    });

    // Funcionalidad para el botón de direcciones
    const directionsBtn = document.querySelector('.directions-btn');
    directionsBtn.addEventListener('click', function () {
        window.open('https://maps.google.com/?q=Calle+Gran+Vía+24,+Madrid', '_blank');
    });

    // Funcionalidad para la galería de imágenes
    const galleryItems = document.querySelectorAll('.gallery-item');
    galleryItems.forEach(item => {
        item.addEventListener('click', function () {
            const imgSrc = this.querySelector('img').src;
            alert('Abriendo imagen en tamaño completo: ' + imgSrc);
        });
    });

    // Funcionalidad para las tarjetas de restaurantes similares
    const similarCards = document.querySelectorAll('.similar-card');
    similarCards.forEach(card => {
        card.addEventListener('click', function () {
            const restaurantName = this.querySelector('.similar-name').textContent;
            alert(`Navegando a: ${restaurantName}`);
        });
    });
});