@'
<?php

class GalponesModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    // Seleccionar todos los galpones
    public function selectGalpones()
    {
        $sql = "SELECT id, codigo, nombre, capacidad_maxima, area_m2, ubicacion, 
                estado, descripcion, created_at, updated_at 
                FROM galpones 
                ORDER BY id DESC";
        $request = $this->select_all($sql);
        return $request;
    }

    // Seleccionar un galpón por ID
    public function selectGalpon(int $id)
    {
        $sql = "SELECT id, codigo, nombre, capacidad_maxima, area_m2, ubicacion, 
                estado, descripcion, created_at, updated_at 
                FROM galpones 
                WHERE id = ?";
        $arrData = array($id);
        $request = $this->select($sql, $arrData);
        return !empty($request) ? $request[0] : array();
    }

    // Insertar galpón
    public function insertGalpon(
        string $codigo,
        string $nombre,
        int $capacidad,
        float $area,
        string $ubicacion,
        string $estado,
        string $descripcion
    ) {
        // Verificar si el código ya existe
        $sql = "SELECT * FROM galpones WHERE codigo = ?";
        $arrData = array($codigo);
        $request = $this->select($sql, $arrData);

        if (empty($request)) {
            $query = "INSERT INTO galpones (codigo, nombre, capacidad_maxima, area_m2, 
                      ubicacion, estado, descripcion, created_at, updated_at) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
            $arrData = array($codigo, $nombre, $capacidad, $area, $ubicacion, $estado, $descripcion);
            $request_insert = $this->insert($query, $arrData);
            return $request_insert;
        } else {
            return "exist";
        }
    }

    // Actualizar galpón
    public function updateGalpon(
        int $id,
        string $codigo,
        string $nombre,
        int $capacidad,
        float $area,
        string $ubicacion,
        string $estado,
        string $descripcion
    ) {
        // Verificar que el galpón exista
        $sqlCheck = "SELECT id FROM galpones WHERE id = ?";
        $check = $this->select($sqlCheck, array($id));
        
        if (empty($check)) {
            return false;
        }

        $sql = "UPDATE galpones 
                SET codigo = ?, nombre = ?, capacidad_maxima = ?, area_m2 = ?, 
                    ubicacion = ?, estado = ?, descripcion = ?, updated_at = NOW() 
                WHERE id = ?";
        $arrData = array($codigo, $nombre, $capacidad, $area, $ubicacion, $estado, $descripcion, $id);
        $request = $this->update($sql, $arrData);
        return $request;
    }

    // Eliminar galpón (HARD DELETE)
    public function deleteGalpon(int $id)
    {
        $sql = "DELETE FROM galpones WHERE id = ?";
        $arrData = array($id);
        $request = $this->delete($sql, $arrData);
        return $request;
    }
}
?>
'@ | Out-File -FilePath "Models\GalponesModel.php" -Encoding UTF8 -Force