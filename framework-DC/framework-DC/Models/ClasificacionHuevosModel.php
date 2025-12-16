@'
<?php

class ClasificacionHuevosModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    public function selectClasificaciones()
    {
        $sql = "SELECT c.*, 
                l.nombre as lote_nombre, 
                l.codigo as lote_codigo,
                g.nombre as galpon_nombre, 
                u.nombre as registrado_por_nombre,
                (c.cantidad_jumbo + c.cantidad_aaa + c.cantidad_aa + 
                 c.cantidad_a + c.cantidad_b + c.cantidad_c + c.cantidad_rotos) as total_huevos
                FROM clasificacion_huevos c
                LEFT JOIN lotes l ON c.lote_id = l.id
                LEFT JOIN galpones g ON l.galpon_id = g.id
                LEFT JOIN usuarios u ON c.registrado_por = u.id
                ORDER BY c.fecha DESC, c.id DESC";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectClasificacion(int $id)
    {
        $sql = "SELECT c.*, 
                l.nombre as lote_nombre, 
                l.codigo as lote_codigo,
                g.nombre as galpon_nombre, 
                u.nombre as registrado_por_nombre,
                (c.cantidad_jumbo + c.cantidad_aaa + c.cantidad_aa + 
                 c.cantidad_a + c.cantidad_b + c.cantidad_c + c.cantidad_rotos) as total_huevos
                FROM clasificacion_huevos c
                LEFT JOIN lotes l ON c.lote_id = l.id
                LEFT JOIN galpones g ON l.galpon_id = g.id
                LEFT JOIN usuarios u ON c.registrado_por = u.id
                WHERE c.id = ?";
        $arrData = array($id);
        $request = $this->select($sql, $arrData);
        return !empty($request) ? $request[0] : array();
    }

    public function selectClasificacionesByLote(int $loteId)
    {
        $sql = "SELECT c.*, 
                l.nombre as lote_nombre, 
                l.codigo as lote_codigo,
                u.nombre as registrado_por_nombre,
                (c.cantidad_jumbo + c.cantidad_aaa + c.cantidad_aa + 
                 c.cantidad_a + c.cantidad_b + c.cantidad_c + c.cantidad_rotos) as total_huevos
                FROM clasificacion_huevos c
                LEFT JOIN lotes l ON c.lote_id = l.id
                LEFT JOIN usuarios u ON c.registrado_por = u.id
                WHERE c.lote_id = ?
                ORDER BY c.fecha DESC";
        $arrData = array($loteId);
        $request = $this->select($sql, $arrData);
        return $request;
    }

    public function insertClasificacion(
        int $loteId, string $fecha, int $jumbo, int $aaa, int $aa,
        int $a, int $b, int $c, int $rotos, string $observaciones, int $registradoPor
    ) {
        $sql = "INSERT INTO clasificacion_huevos 
                (lote_id, fecha, cantidad_jumbo, cantidad_aaa, cantidad_aa, 
                cantidad_a, cantidad_b, cantidad_c, cantidad_rotos, 
                observaciones, registrado_por, created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
        $arrData = array($loteId, $fecha, $jumbo, $aaa, $aa, $a, $b, $c, $rotos, $observaciones, $registradoPor);
        $request = $this->insert($sql, $arrData);
        return $request;
    }

    public function updateClasificacion(
        int $id, int $loteId, string $fecha, int $jumbo, int $aaa, int $aa,
        int $a, int $b, int $c, int $rotos, string $observaciones
    ) {
        $sqlCheck = "SELECT id FROM clasificacion_huevos WHERE id = ?";
        $check = $this->select($sqlCheck, array($id));
        
        if (empty($check)) {
            return false;
        }

        $sql = "UPDATE clasificacion_huevos 
                SET lote_id=?, fecha=?, cantidad_jumbo=?, cantidad_aaa=?, cantidad_aa=?, 
                    cantidad_a=?, cantidad_b=?, cantidad_c=?, cantidad_rotos=?, 
                    observaciones=?, updated_at=NOW() 
                WHERE id=?";
        $arrData = array($loteId, $fecha, $jumbo, $aaa, $aa, $a, $b, $c, $rotos, $observaciones, $id);
        $request = $this->update($sql, $arrData);
        return $request;
    }

    public function deleteClasificacion(int $id)
    {
        $sql = "DELETE FROM clasificacion_huevos WHERE id = ?";
        $arrData = array($id);
        $request = $this->delete($sql, $arrData);
        return $request;
    }
}
?>
'@ | Out-File -FilePath "Models/ClasificacionHuevosModel.php" -Encoding UTF8 -Force

Write-Host "✅ Modelo ClasificacionHuevosModel.php creado" -ForegroundColor Green