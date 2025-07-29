<body>
    <nav>
        <div class="wrapper">
            <div class="logo">
                <img src="/assets/img/soloNamewhite.svg" alt="Logo de mi página" class="logo-img">

            </div>

            <input type="radio" name="slider" id="menu-btn">
            <input type="radio" name="slider" id="close-btn">
            <ul class="nav-links menuBar">
                <label for="close-btn" class="btn close-btn"><i class="fas fa-times"></i></label>
                <li><a href="../index.php">Inicio</a></li>
                <li><a href="./pages/blog.php">Acerca de</a></li>
                <li>
                    <a href="#" class="desktop-item">Proyectos</a>
                    <input type="checkbox" id="showDrop">
                    <label for="showDrop" class="mobile-item">Proyectos</label>
                    <ul class="drop-menu">
                        <li><a href="#">Drop menu 1</a></li>
                        <li><a href="#">Drop menu 2</a></li>
                        <li><a href="#">Drop menu 3</a></li>
                        <li><a href="#">Drop menu 4</a></li>
                    </ul>
                </li>
                <li><a href="#">Contacto</a></li>
            </ul>
            <label for="menu-btn" class="btn menu-btn"><i class="fas fa-bars"></i></label>
        </div>
    </nav>


</body>

</html>