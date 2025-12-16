@'
<?php

class Clasificacion extends Controllers
{
    public function __construct()
    {
        parent::__construct();
    }

    // GET - Obtener todas las clasificaciones
    public function getClasificaciones()
    {
        try {
            $arrData = $this->model->selectClasificaciones();
            
            if (empty($arrData)) {
                json_response(['status' => false, 'msg' => 'No hay clasificaciones registradas'], 404);
            } else {
                json_response(['status' => true, 'data' => $arrData, 'count' => count($arrData)], 200);
            }
        } catch (Exception $e) {
            json_response(['status' => false, 'msg' => 'Error en el servidor: ' . $e->getMessage(), 'trace' => $e->getTraceAsString()], 500);
        }
    }

    // GET - Obtener una clasificación por ID
    public function getClasificacion($id)
    {
        try {
            $intId = intval(strClean($id));
            
            if ($intId > 0) {
                $arrData = $this->model->selectClasificacion($intId);
                
                if (empty($arrData)) {
                    json_response(['status' => false, 'msg' => 'Clasificación no encontrada'], 404);
                } else {
                    json_response(['status' => true, 'data' => $arrData], 200);
                }
            } else {
                json_response(['status' => false, 'msg' => 'ID inválido'], 400);
            }
        } catch (Exception $e) {
            json_response(['status' => false, 'msg' => 'Error en el servidor: ' . $e->getMessage()], 500);
        }
    }

    // GET - Obtener clasificaciones por lote
    public function getClasificacionesByLote($loteId)
    {
        try {
            $intLoteId = intval(strClean($loteId));
            
            if ($intLoteId > 0) {
                $arrData = $this->model->selectClasificacionesByLote($intLoteId);
                
                if (empty($arrData)) {
                    json_response(['status' => false, 'msg' => 'No hay clasificaciones para este lote'], 404);
                } else {
                    json_response(['status' => true, 'data' => $arrData, 'count' => count($arrData)], 200);
                }
            } else {
                json_response(['status' => false, 'msg' => 'ID de lote inválido'], 400);
            }
        } catch (Exception $e) {
            json_response(['status' => false, 'msg' => 'Error en el servidor: ' . $e->getMessage()], 500);
        }
    }

    // POST - Crear clasificación
    public function setClasificacion()
    {
        try {
            $jsonData = file_get_contents('php://input');
            $data = json_decode($jsonData, true);

            if (empty($data)) {
                json_response(['status' => false, 'msg' => 'No se recibieron datos'], 400);
                return;
            }

            $intLoteId = intval($data['lote_id'] ?? 0);
            $strFecha = strClean($data['fecha'] ?? '');
            $intJumbo = intval($data['cantidad_jumbo'] ?? 0);
            $intAAA = intval($data['cantidad_aaa'] ?? 0);
            $intAA = intval($data['cantidad_aa'] ?? 0);
            $intA = intval($data['cantidad_a'] ?? 0);
            $intB = intval($data['cantidad_b'] ?? 0);
            $intC = intval($data['cantidad_c'] ?? 0);
            $intRotos = intval($data['cantidad_rotos'] ?? 0);
            $strObservaciones = strClean($data['observaciones'] ?? '');
            $intRegistradoPor = intval($data['registrado_por'] ?? 1);

            if ($intLoteId <= 0 || empty($strFecha)) {
                json_response(['status' => false, 'msg' => 'Los campos lote_id y fecha son obligatorios'], 400);
                return;
            }

            $request = $this->model->insertClasificacion(
                $intLoteId,
                $strFecha,
                $intJumbo,
                $intAAA,
                $intAA,
                $intA,
                $intB,
                $intC,
                $intRotos,
                $strObservaciones,
                $intRegistradoPor
            );

            if ($request > 0) {
                json_response([
                    'status' => true,
                    'msg' => 'Clasificación creada exitosamente',
                    'id' => $request
                ], 201);
            } else {
                json_response(['status' => false, 'msg' => 'Error al crear clasificación'], 500);
            }
        } catch (Exception $e) {
            json_response(['status' => false, 'msg' => 'Error en el servidor: ' . $e->getMessage()], 500);
        }
    }

    // PUT - Actualizar clasificación
    public function putClasificacion($id)
    {
        try {
            $intId = intval(strClean($id));
            $jsonData = file_get_contents('php://input');
            $data = json_decode($jsonData, true);

            if (empty($data)) {
                json_response(['status' => false, 'msg' => 'No se recibieron datos'], 400);
                return;
            }

            $intLoteId = intval($data['lote_id'] ?? 0);
            $strFecha = strClean($data['fecha'] ?? '');
            $intJumbo = intval($data['cantidad_jumbo'] ?? 0);
            $intAAA = intval($data['cantidad_aaa'] ?? 0);
            $intAA = intval($data['cantidad_aa'] ?? 0);
            $intA = intval($data['cantidad_a'] ?? 0);
            $intB = intval($data['cantidad_b'] ?? 0);
            $intC = intval($data['cantidad_c'] ?? 0);
            $intRotos = intval($data['cantidad_rotos'] ?? 0);
            $strObservaciones = strClean($data['observaciones'] ?? '');

            if ($intId <= 0 || $intLoteId <= 0 || empty($strFecha)) {
                json_response(['status' => false, 'msg' => 'Datos incompletos o inválidos'], 400);
                return;
            }

            $request = $this->model->updateClasificacion(
                $intId,
                $intLoteId,
                $strFecha,
                $intJumbo,
                $intAAA,
                $intAA,
                $intA,
                $intB,
                $intC,
                $intRotos,
                $strObservaciones
            );

            if ($request) {
                json_response(['status' => true, 'msg' => 'Clasificación actualizada exitosamente'], 200);
            } else {
                json_response(['status' => false, 'msg' => 'Error al actualizar clasificación o clasificación no encontrada'], 500);
            }
        } catch (Exception $e) {
            json_response(['status' => false, 'msg' => 'Error en el servidor: ' . $e->getMessage()], 500);
        }
    }

    // DELETE - Eliminar clasificación permanentemente
    public function deleteClasificacion($id)
    {
        try {
            $intId = intval(strClean($id));

            if ($intId > 0) {
                $request = $this->model->deleteClasificacion($intId);

                if ($request) {
                    json_response(['status' => true, 'msg' => 'Clasificación eliminada exitosamente'], 200);
                } else {
                    json_response(['status' => false, 'msg' => 'Error al eliminar clasificación o clasificación no encontrada'], 500);
                }
            } else {
                json_response(['status' => false, 'msg' => 'ID inválido'], 400);
            }
        } catch (Exception $e) {
            json_response(['status' => false, 'msg' => 'Error en el servidor: ' . $e->getMessage()], 500);
        }
    }
}
?>
'@ | Out-File -FilePath "Controllers/Clasificacion.php" -Encoding UTF8 -Force