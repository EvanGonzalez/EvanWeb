<?php
// Verificar si la extensión mysqli está habilitada
if (!extension_loaded('mysqli')) {
    die("❌ Error: La extensión MySQLi no está habilitada en PHP. 
    Habilítala en php.ini (quita el ; de 'extension=mysqli') y reinicia Apache.");
}

// Configuración de la base de datos
$mysql_host = 'localhost';
$mysql_user = 'root';
$mysql_pass = '';
$mysql_db   = 'evanmsdata';

// Conexión a MySQL
$db = mysqli_connect($mysql_host, $mysql_user, $mysql_pass, $mysql_db);
