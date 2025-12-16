@'
<?php
require_once("Config/Config.php");
require_once("Libraries/Core/Autoload.php");
require_once("Libraries/Core/Conexion.php");
require_once("Libraries/Core/Mysql.php");
require_once("Libraries/Core/Controllers.php");
require_once("Helpers/Helpers.php");

$url = !empty($_GET['url']) ? $_GET['url'] : 'home/home';
$arrUrl = explode("/", $url);

$controller = ucfirst($arrUrl[0]);
$method = $arrUrl[1] ?? 'index';
$params = "";

if (!empty($arrUrl[2])) {
    for ($i = 2; $i < count($arrUrl); $i++) {
        $params .= $arrUrl[$i] . ',';
    }
    $params = trim($params, ',');
}

require_once("Libraries/Core/Load.php");
?>
'@ | Out-File -FilePath "index.php" -Encoding UTF8 -Force