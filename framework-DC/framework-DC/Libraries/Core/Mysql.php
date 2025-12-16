@'
<?php

class Mysql extends Conexion
{
    private $conexion;
    private $strquery;
    private $arrValues;

    function __construct()
    {
        $this->conexion = new Conexion();
        $this->conexion = $this->conexion->connect();
    }

    // Insertar un registro
    public function insert(string $query, array $arrValues)
    {
        $this->strquery = $query;
        $this->arrValues = $arrValues;
        $insert = $this->conexion->prepare($this->strquery);
        $resInsert = $insert->execute($this->arrValues);
        if ($resInsert) {
            $lastInsert = $this->conexion->lastInsertId();
            return $lastInsert;
        } else {
            return 0;
        }
    }

    // Buscar un registro
    public function select(string $query, array $arrValues = [])
    {
        $this->strquery = $query;
        $this->arrValues = $arrValues;
        
        $result = $this->conexion->prepare($this->strquery);
        
        if (!empty($this->arrValues)) {
            $result->execute($this->arrValues);
        } else {
            $result->execute();
        }
        
        $data = $result->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    // Devolver todos los registros
    public function select_all(string $query)
    {
        $this->strquery = $query;
        $result = $this->conexion->prepare($this->strquery);
        $result->execute();
        $data = $result->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    // Actualizar registros
    public function update(string $query, array $arrValues)
    {
        $this->strquery = $query;
        $this->arrValues = $arrValues;
        $update = $this->conexion->prepare($this->strquery);
        $resExecute = $update->execute($this->arrValues);
        return $resExecute;
    }

    // Eliminar un registro
    public function delete(string $query, array $arrValues)
    {
        $this->strquery = $query;
        $this->arrValues = $arrValues;
        $delete = $this->conexion->prepare($this->strquery);
        $resDelete = $delete->execute($this->arrValues);
        return $resDelete;
    }
}
?>
'@ | Out-File -FilePath "Libraries\Core\Mysql.php" -Encoding UTF8 -Force