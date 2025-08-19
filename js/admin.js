// Inicialización cuando carga la página
document.addEventListener('DOMContentLoaded', function() {
    initializeSidebar();
    initializeNavigation();
    initializeQuickActions();
    initializeMobileMenu();
    
    console.log('Panel de administración cargado');
});

// Gestión del sidebar
function initializeSidebar() {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('open');
            toggleSidebarOverlay();
        });
    }
}

// Navegación entre secciones
function initializeNavigation() {
    const navLinks = document.querySelectorAll('.nav-link');
    const contentSections = document.querySelectorAll('.content-section');
    const pageTitle = document.getElementById('pageTitle');
    
    // Títulos para cada sección
    const sectionTitles = {
        'dashboard': 'Dashboard Principal',
        'clientes': 'Gestión de Clientes',
        'solicitudes': 'Solicitudes de Servicio',
        'equipos': 'Equipos en Servicio',
        'informes': 'Informes y Reportes',
        'configuracion': 'Configuración del Sistema'
    };
    
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const section = this.getAttribute('data-section');
            
            // Remover clase activa de todos los links
            navLinks.forEach(navLink => {
                navLink.classList.remove('active');
            });
            
            // Agregar clase activa al link clickeado
            this.classList.add('active');
            
            // Ocultar todas las secciones
            contentSections.forEach(contentSection => {
                contentSection.classList.remove('active');
            });
            
            // Mostrar la sección correspondiente
            const targetSection = document.getElementById(section + '-content');
            if (targetSection) {
                targetSection.classList.add('active');
            }
            
            // Actualizar título de la página
            if (pageTitle && sectionTitles[section]) {
                pageTitle.textContent = sectionTitles[section];
            }
            
            // Cerrar sidebar en móvil después de seleccionar
            if (window.innerWidth <= 768) {
                document.getElementById('sidebar').classList.remove('open');
                hideSidebarOverlay();
            }
        });
    });
    

}

// Acciones rápidas
function initializeQuickActions() {
    const quickActionBtns = document.querySelectorAll('.quick-action-btn');
    const navLinks = document.querySelectorAll('.nav-link');
    
    quickActionBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const section = this.getAttribute('data-section');
            
            if (section) {
                // Simular click en el nav correspondiente
                const navLink = document.querySelector(`[data-section="${section}"]`);
                if (navLink) {
                    navLink.click();
                }
            }
        });
    });
}

// Menú móvil
function initializeMobileMenu() {
    // Crear overlay para móvil si no existe
    let overlay = document.querySelector('.sidebar-overlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.className = 'sidebar-overlay';
        document.body.appendChild(overlay);
        
        overlay.addEventListener('click', function() {
            document.getElementById('sidebar').classList.remove('open');
            this.classList.remove('active');
        });
    }
    
    // Manejar redimensionado de ventana
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            document.getElementById('sidebar').classList.remove('open');
            hideSidebarOverlay();
        }
    });
}

// Funciones auxiliares para el overlay
function toggleSidebarOverlay() {
    const overlay = document.querySelector('.sidebar-overlay');
    if (overlay) {
        overlay.classList.toggle('active');
    }
}

function hideSidebarOverlay() {
    const overlay = document.querySelector('.sidebar-overlay');
    if (overlay) {
        overlay.classList.remove('active');
    }
}

// Funciones de utilidad para futuras implementaciones

// Mostrar notificación
function showNotification(message, type = 'info') {
    // Crear elemento de notificación
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    // Estilos inline básicos
    Object.assign(notification.style, {
        position: 'fixed',
        top: '20px',
        right: '20px',
        padding: '15px 20px',
        borderRadius: '6px',
        color: 'white',
        zIndex: '9999',
        fontSize: '14px',
        fontWeight: '500',
        maxWidth: '300px',
        boxShadow: '0 4px 12px rgba(0,0,0,0.15)',
        transform: 'translateX(400px)',
        transition: 'transform 0.3s ease'
    });
    
    // Colores según tipo
    const colors = {
        'info': '#3498db',
        'success': '#2ecc71',
        'warning': '#f39c12',
        'error': '#e74c3c'
    };
    
    notification.style.backgroundColor = colors[type] || colors.info;
    
    // Agregar al DOM
    document.body.appendChild(notification);
    
    // Animación de entrada
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Remover después de 4 segundos
    setTimeout(() => {
        notification.style.transform = 'translateX(400px)';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 4000);
}

// Función para cargar datos dinámicamente (placeholder)
function loadSectionData(section) {
    console.log(`Cargando datos para la sección: ${section}`);
    
    // Aquí puedes implementar llamadas AJAX para cargar datos específicos
    switch(section) {
        case 'clientes':
            loadClientesData();
            break;
        case 'solicitudes':
            loadSolicitudesData();
            break;
        case 'equipos':
            loadEquiposData();
            break;
        case 'informes':
            loadInformesData();
            break;
        default:
            console.log('Sección no encontrada');
    }
}

// Funciones placeholder para cargar datos específicos
function loadClientesData() {
    showNotification('Cargando lista de clientes...', 'info');
    // Implementar llamada AJAX aquí
}

function loadSolicitudesData() {
    showNotification('Cargando solicitudes pendientes...', 'info');
    // Implementar llamada AJAX aquí
}

function loadEquiposData() {
    showNotification('Cargando equipos en servicio...', 'info');
    // Implementar llamada AJAX aquí
}

function loadInformesData() {
    showNotification('Preparando módulo de informes...', 'info');
    // Implementar llamada AJAX aquí
}

// Función para actualizar estadísticas en tiempo real
function updateStats() {
    // Placeholder para actualización automática de estadísticas
    console.log('Actualizando estadísticas...');
    
    // Aquí puedes implementar una llamada AJAX para obtener estadísticas actuales
    // y actualizar los números en las tarjetas de estadísticas
}

// Función para manejar confirmaciones
function confirmAction(message, callback) {
    if (confirm(message)) {
        callback();
    }
}

// Función para formatear fechas
function formatDate(date) {
    const options = { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    };
    return new Date(date).toLocaleDateString('es-ES', options);
}

// Función para validar formularios (para uso futuro)
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;
    
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('error');
            isValid = false;
        } else {
            field.classList.remove('error');
        }
    });
    
    return isValid;
}

// Event listeners adicionales para mejorar la experiencia

// Manejar teclas de acceso rápido
document.addEventListener('keydown', function(e) {
    // ESC para cerrar sidebar en móvil
    if (e.key === 'Escape') {
        document.getElementById('sidebar').classList.remove('open');
        hideSidebarOverlay();
    }
    
    // Ctrl + números para navegar rápido
    if (e.ctrlKey && e.key >= '1' && e.key <= '6') {
        e.preventDefault();
        const navLinks = document.querySelectorAll('.nav-link');
        const index = parseInt(e.key) - 1;
        if (navLinks[index]) {
            navLinks[index].click();
        }
    }
});

// Actualizar estadísticas cada 5 minutos (opcional)
// setInterval(updateStats, 5 * 60 * 1000);

// Mostrar mensaje de bienvenida
setTimeout(() => {
    showNotification('Bienvenido al panel de administración', 'success');
}, 1000);

// Script para manejo de detalles en modal
document.querySelectorAll('.btn-detalle').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        // Oculta la tabla
        document.querySelector('.tabla-solicitudes').style.display = 'none';
        // Carga el detalle
        fetch('detalle_solicitud.php?id=' + id)
            .then(res => res.text())
            .then(html => {
                document.getElementById('detalleSolicitudVista').innerHTML = html +
                    '<button id="volverTabla" class="btn btn-secondary" style="margin-top:20px;">Volver a la lista</button>';
                document.getElementById('detalleSolicitudVista').style.display = 'block';
                // Botón para volver
                document.getElementById('volverTabla').onclick = function() {
                    document.getElementById('detalleSolicitudVista').style.display = 'none';
                    document.querySelector('.tabla-solicitudes').style.display = '';
                };
            });
    });
});