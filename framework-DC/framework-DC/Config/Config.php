@'
<?php
    const BASE_URL = "http://localhost/framework-DC/";

    // Datos de conexión a Base de Datos (DOCKER)
    const CONNECTION = true;
    const DB_HOST = "127.0.0.1:3307";  // Puerto de Docker
    const DB_NAME = "db_sistema";
    const DB_USER = "root";
    const DB_PASSWORD = "";  // Sin contraseña en Docker
    const DB_CHARSET = "utf8";
?>
'@ | Out-File -FilePath "Config/Config.php" -Encoding UTF8 -Force