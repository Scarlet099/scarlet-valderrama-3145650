<?php

require_once("Config/Config.php");
require_once("Libraries/Core/Conexion.php");

$db = new Conexion();
$conn = $db->connect();

if ($conn instanceof PDO) {

    echo "✅ Conexión exitosa a la base de datos.\n\n";

    // Mostrar base de datos actual
    $dbActual = $conn->query("SELECT DATABASE()")->fetchColumn();
    echo "📊 Base de datos en uso: {$dbActual}\n\n";

    // Obtener tablas
    $stmt = $conn->query("SHOW TABLES");
    $tablas = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (count($tablas) > 0) {
        echo "📋 Tablas encontradas (" . count($tablas) . "):\n";
        foreach ($tablas as $tabla) {
            echo "  • {$tabla}\n";
        }
    } else {
        echo "⚠️ La base de datos no tiene tablas.\n";
    }

} else {
    echo "❌ No se pudo conectar a la base de datos.\n";
}