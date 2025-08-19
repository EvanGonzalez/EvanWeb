document.addEventListener('DOMContentLoaded', function () {
    // Manejar clics en el menú lateral
    document.querySelectorAll('.nav-link').forEach(function (link) {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            // Quitar 'active' de todos los links y secciones
            document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
            document.querySelectorAll('.content-section').forEach(s => s.classList.remove('active'));

            // Activar el link y la sección correspondiente
            this.classList.add('active');
            const section = this.getAttribute('data-section');
            const sectionContent = document.getElementById(section + '-content');
            if (sectionContent) {
                sectionContent.classList.add('active');
                // Cambiar el título de la página si existe el elemento
                const pageTitle = document.getElementById('pageTitle');
                if (pageTitle) {
                    pageTitle.textContent = this.textContent.trim();
                }
            }
        });
    });

    // También para los botones de acciones rápidas
    document.querySelectorAll('[data-section]').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            const section = this.getAttribute('data-section');
            const sectionContent = document.getElementById(section + '-content');
            if (sectionContent) {
                // Quitar 'active' de todos los links y secciones
                document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
                document.querySelectorAll('.content-section').forEach(s => s.classList.remove('active'));
                // Activar la sección
                sectionContent.classList.add('active');
                // Cambiar el título de la página si existe el elemento
                const pageTitle = document.getElementById('pageTitle');
                if (pageTitle) {
                    pageTitle.textContent = btn.textContent.trim();
                }
            }
        });
    });
});