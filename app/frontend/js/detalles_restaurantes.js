function getIdFromUrl() {
    const params = new URLSearchParams(window.location.search);
    return params.get('id');
}

document.addEventListener('DOMContentLoaded', async () => {
    const id = getIdFromUrl();
    if (!id) return;

    // Verificar si hay usuario logeado
    const userData = sessionStorage.getItem('userData');
    const saveBtn = document.querySelector('.action-button:nth-child(2)');
    
    if (!userData && saveBtn) {
        saveBtn.style.display = 'none';
    }

    try {
        const response = await fetch(`../../backend/php/get_detalle_restaurante.php?id=${id}`);
        const data = await response.json();

        if (data.error) {
            document.querySelector('.restaurant-title').textContent = 'Restaurante no encontrado';
            return;
        }

        const r = data.restaurante;

        // Ocultar botón Guardar si el usuario es el dueño del restaurante
        if (userData) {
            
            const usuario = JSON.parse(userData);
            if (usuario.id_usuario == id && saveBtn) {
                
                saveBtn.style.display = 'none';
            }
        } else if (saveBtn) {
            // Ocultar si no hay usuario logeado
            saveBtn.style.display = 'none';
        }

        // Portada
        document.querySelector('.restaurant-cover').src = r.img_usuario;
        document.querySelector('.restaurant-title').textContent = r.nombre;
        document.querySelector('.restaurant-subtitle').textContent = r.tipo_restaurante;

        // Meta
        document.querySelector('.restaurant-address').textContent = r.direccion;
        document.querySelector('.restaurant-price').textContent = r.rango_precio
            .split('-')
            .map(p => `${p}€`)
            .join(' - ');

        // Sobre el restaurante
        document.querySelector('.restaurant-description').innerHTML = `
            <p>${r.descripcion}</p><br>
            <p>${r.historial}</p>
        `;

        // Galería
        const gallery = document.querySelector('.gallery-grid');
        gallery.innerHTML = '';
        data.imagenes.forEach(img => {
            const div = document.createElement('div');
            div.className = 'gallery-item';
            div.innerHTML = `<img src="${img.url_imagen}" alt="${img.alt}">`;
            gallery.appendChild(div);
        });

        // Sidebar
        const info = document.querySelectorAll('.info-item');
        info[0].querySelector('.info-value').textContent = r.tipo_restaurante;
        info[1].querySelector('.info-value').textContent = r.rango_precio.split('-').map(p => `${p}€`).join(' - ');
        info[2].querySelector('.info-value').textContent = r.telefono;
        info[3].querySelector('.info-value').textContent = r.web;
        info[4].querySelector('.info-value').textContent = r.correo;

        // Dirección + mapa
        const mapa = document.querySelector('.map-container iframe');
        if (mapa) mapa.src = r.ubicacion;
        const dirTxt = document.querySelector('.address-text');
        if (dirTxt) dirTxt.textContent = r.direccion;

        const dirBtn = document.querySelector('.directions-btn');
        if (dirBtn) {
            dirBtn.onclick = () => {
                window.open(`https://www.google.com/maps/dir/?api=1&destination=${encodeURIComponent(r.direccion)}`, '_blank');
            };
        }

        // Horarios
        const dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
        const ul = document.querySelector('.hours-list');
        if (ul) {
            ul.innerHTML = '';
            dias.forEach(dia => {
                const horario = data.horarios.find(h => h.dia_semana === dia);
                const time = horario ? `${horario.hora_apertura.slice(0, 5)} - ${horario.hora_cierre.slice(0, 5)}` : 'Cerrado';
                ul.innerHTML += `<li class="hours-item"><span class="hours-day">${dia}</span><span class="hours-time">${time}</span></li>`;
            });

            // Marcar el día actual
            const today = new Date().getDay();
            const days = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
            const todayName = days[today];
            ul.querySelectorAll('.hours-day').forEach(dayEl => {
                if (dayEl.textContent === todayName) {
                    dayEl.classList.add('today');
                }
            });
        }

        // Restaurantes similares
        const similarContainer = document.querySelector('.similar-grid');
        if (similarContainer) {
            similarContainer.innerHTML = '';
            if (data.similares && data.similares.length > 0) {
                data.similares.forEach(similar => {
                    const card = document.createElement('div');
                    card.classList.add('similar-card');
                    card.innerHTML = `
                        <div class="similar-image">
                            <img src="${similar.img_usuario}" alt="${similar.nombre}">
                        </div>
                        <div class="similar-details">
                            <h3 class="similar-name">${similar.nombre}</h3>
                            <p class="similar-info">${similar.tipo_restaurante} • ${similar.rango_precio.split('-').map(p => `${p}€`).join(' - ')}</p>
                            <p class="similar-description">${similar.descripcion || ''}</p>
                        </div>`;
                    card.addEventListener('click', () => {
                        window.location.href = `detalles_restaurantes.html?id=${similar.id_usuario}`;
                    });
                    similarContainer.appendChild(card);
                });
            } else {
                similarContainer.innerHTML = '<p>No hay restaurantes similares disponibles.</p>';
            }
        }

        // Botón Llamar
        const callBtn = document.querySelector('.action-button:nth-child(1)');
        if (callBtn) {
            callBtn.addEventListener('click', () => {
                if (r.telefono) {
                    window.location.href = `tel:${r.telefono.replace(/\s+/g, '')}`;
                } else {
                    alert('Teléfono no disponible.');
                }
            });
        }

        

        // Botón Compartir
        const shareBtn = document.querySelector('.action-button.primary');
        if (shareBtn) {
            shareBtn.addEventListener('click', () => {
                const titulo = r.nombre || 'Restaurante en GastroLink';
                if (navigator.share) {
                    navigator.share({
                        title: titulo,
                        text: `Mira este restaurante: ${titulo}`,
                        url: window.location.href
                    });
                } else {
                    alert('Enlace copiado: ' + window.location.href);
                }
            });
        }

    // Botón Guardar - Nueva implementación
    if (saveBtn) {
        // Verificar si el restaurante está guardado (solo si hay usuario logeado)
        if (userData) {
            const usuario = JSON.parse(userData);
            await verificarRestauranteGuardado(id, usuario.id_usuario, saveBtn);
        }

        saveBtn.addEventListener('click', async function() {
            if (!userData) {
                alert('Debes iniciar sesión para guardar restaurantes');
                return;
            }
            
            const usuario = JSON.parse(userData);
            const isSaved = this.classList.contains('saved');
            
            if (isSaved) {
                // Eliminar de favoritos
                await eliminarRestauranteGuardado(id, usuario.id_usuario);
                this.classList.remove('saved');
                this.innerHTML = '<span class="action-icon">💾</span> Guardar';
            } else {
                // Guardar en favoritos
                await guardarRestauranteFavorito(id, usuario.id_usuario);
                this.classList.add('saved');
                this.innerHTML = '<span class="action-icon">✓</span> Guardado';
            }
        });
    }

} catch (err) {
    console.error('Error al cargar datos:', err);
}
});

// Funciones auxiliares para restaurantes favoritos
async function verificarRestauranteGuardado(restauranteId, usuarioId, saveBtn) {
    try {
        const response = await fetch(`../../backend/php/verificar_favorito_restaurante.php?restaurante_id=${restauranteId}&usuario_id=${usuarioId}`);
        const data = await response.json();
        
        if (data.isSaved && saveBtn) {
            saveBtn.classList.add('saved');
            saveBtn.innerHTML = '<span class="action-icon">✓</span> Guardado';
        }
    } catch (error) {
        console.error('Error al verificar restaurante guardado:', error);
    }
}

async function guardarRestauranteFavorito(restauranteId, usuarioId) {
    return fetch('../../backend/php/guardar_favorito_restaurante.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            restaurante_id: restauranteId,
            usuario_id: usuarioId
        })
    }).then(res => res.json());
}

async function eliminarRestauranteGuardado(restauranteId, usuarioId) {
    return fetch('../../backend/php/eliminar_favorito_restaurante.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            restaurante_id: restauranteId,
            usuario_id: usuarioId
        })
    }).then(res => res.json());
}