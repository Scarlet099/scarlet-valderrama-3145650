@'
<?php

$controller = ucfirst($controller);
$controllerFile = "Controllers/" . $controller . ".php";

if (file_exists($controllerFile)) {
    require_once($controllerFile);
    $controller = new $controller();
    
    if (method_exists($controller, $method)) {
        if (!empty($params)) {
            $arrParams = explode(',', $params);
            $controller->{$method}(...$arrParams);
        } else {
            $controller->{$method}();
        }
    } else {
        require_once("Controllers/Error.php");
    }
} else {
    require_once("Controllers/Error.php");
}

?>
'@ | Out-File -FilePath "Libraries/Core/Load.php" -Encoding UTF8 -Force