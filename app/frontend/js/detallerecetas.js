// Script para hacer funcionales los botones
document.addEventListener('DOMContentLoaded', function () {
    // Botón de imprimir
    document.querySelector('.action-button:nth-child(1)').addEventListener('click', function () {
        window.print();
    });

    // Botón de añadir a la lista de compra
    document.querySelector('.action-button:nth-child(2)').addEventListener('click', function () {
        alert('Ingredientes añadidos a tu lista de compra');
    });

    // Botón de guardar receta
    document.querySelector('.action-button:nth-child(3)').addEventListener('click', function () {
        this.innerHTML = '<span class="action-icon">✓</span> Receta guardada';
        setTimeout(() => {
            this.innerHTML = '<span class="action-icon">💾</span> Guardar receta';
        }, 2000);
    });

    // Botón de compartir
    document.querySelector('.action-button.primary').addEventListener('click', function () {
        if (navigator.share) {
            navigator.share({
                title: 'Risotto de Setas Silvestres',
                text: 'Mira esta deliciosa receta de Risotto de Setas Silvestres',
                url: window.location.href
            });
        } else {
            alert('Compartir: ' + window.location.href);
        }
    });

    // Hacer funcionales las estrellas de valoración
    const stars = document.querySelectorAll('.recipe-rating .star');
    stars.forEach((star, index) => {
        star.addEventListener('click', () => {
            stars.forEach((s, i) => {
                if (i <= index) {
                    s.classList.add('filled');
                } else {
                    s.classList.remove('filled');
                }
            });
            alert(`Has valorado esta receta con ${index + 1} estrellas`);
        });
    });

    // Hacer funcionales las tarjetas de recetas relacionadas
    const relatedCards = document.querySelectorAll('.related-card');
    relatedCards.forEach(card => {
        card.addEventListener('click', function () {
            const recipeName = this.querySelector('.related-name').textContent;
            alert(`Navegando a la receta: ${recipeName}`);
        });
    });
});