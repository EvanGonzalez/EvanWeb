<body>

<nav class="custom-navbar">
    <div class="nav-container">
        <a href="../index.php" class="logo">
            <img src="/assets/img/soloNamewhite.svg" alt="Logo" class="logo-img" />
        </a>

        <input type="checkbox" id="menu-toggle" />
        <label for="menu-toggle" class="menu-icon">
            <i class="fas fa-bars icon-open"></i>
            <i class="fas fa-times icon-close"></i>
        </label>

        <div class="nav-sidebar">
            <div class="nav-content">
                <ul class="nav-links">
                    <li><a href="../index.php">Inicio</a></li>
                    <li><a href="./pages/blog.php">Acerca de</a></li>
                    <li class="custom-dropdown">
                        <input type="checkbox" id="drop-toggle" />
                        <label for="drop-toggle" class="custom-drop-label">
                            Proyectos <i class="fas fa-caret-down"></i>
                        </label>
                        <ul class="custom-dropdown-menu">
                            <li><a href="#">Drop menu 1</a></li>
                            <li><a href="#">Drop menu 2</a></li>
                            <li><a href="#">Drop menu 3</a></li>
                        </ul>
                    </li>
                    <li><a href="#">Contacto</a></li>
                </ul>

                <div class="nav-buttons">
                    <a href="/login.php" class="custom-btn login">Iniciar sesión</a>
                    <a href="/registro.php" class="custom-btn register">Registrarse</a>
                </div>
            </div>
        </div>
        <div class="nav-overlay"></div>
    </div>
</nav>




</body>
</html>