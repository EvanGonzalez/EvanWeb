<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<title>Evan González</title>
	<link href="/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="./css/estiloGlobal.css">
	<link rel="stylesheet" href="./css/menu.css">
	<link rel="stylesheet" href="./css/img.css">
	<link rel="shortcut icon" href="/assets/img/3_icon-black.svg" />
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>
</head>

<body class="holy-grail">

	<header>

		<?php
		include("./components/menu.php");
		?>
	</header>
	<section class="holy-grail-content2">
		<!-- banner -->
		<?php
		include("./components/carrusel.php");
		?>
		<!-- banner -->
	</section>
	<div class="holy-grail-body">

		<section class="holy-grail-content">
			<!-- contCenter -->
			<?php
			include(__DIR__ . "/infoWeb/inicio.html");
			?>

		</section>

		<div class="holy-grail-sidebar-1 hg-sidebar">
			<!-- textoejemplo -->
		</div>
		<div class="holy-grail-sidebar-2 hg-sidebar">
			<!-- textoejemplo -->
		</div>

	</div>

	<footer>
		<p>Evan'ms 2025</p>
	</footer>
	<script src="./js/img.js"></script>
	<script src="./js/bootstrap.js"></script>
</body>

</html>