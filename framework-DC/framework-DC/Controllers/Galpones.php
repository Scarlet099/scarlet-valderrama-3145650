@'
<?php

class Galpones extends Controllers
{
    public function __construct()
    {
        parent::__construct();
    }

    // GET - Obtener todos los galpones
    public function getGalpones()
    {
        try {
            $arrData = $this->model->selectGalpones();
            
            if (empty($arrData)) {
                json_response(['status' => false, 'msg' => 'No hay galpones registrados'], 404);
            } else {
                json_response(['status' => true, 'data' => $arrData, 'count' => count($arrData)], 200);
            }
        } catch (Exception $e) {
            json_response(['status' => false, 'msg' => 'Error en el servidor: ' . $e->getMessage()], 500);
        }
    }

    // GET - Obtener un galpón por ID
    public function getGalpon($id)
    {
        try {
            $intId = intval(strClean($id));
            
            if ($intId > 0) {
                $arrData = $this->model->selectGalpon($intId);
                
                if (empty($arrData)) {
                    json_response(['status' => false, 'msg' => 'Galpón no encontrado'], 404);
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

    // POST - Crear galpón
    public function setGalpon()
    {
        try {
            $jsonData = file_get_contents('php://input');
            $data = json_decode($jsonData, true);

            if (empty($data)) {
                json_response(['status' => false, 'msg' => 'No se recibieron datos'], 400);
                return;
            }

            $strCodigo = strClean($data['codigo'] ?? '');
            $strNombre = strClean($data['nombre'] ?? '');
            $intCapacidad = intval($data['capacidad_maxima'] ?? 0);
            $floatArea = floatval($data['area_m2'] ?? 0);
            $strUbicacion = strClean($data['ubicacion'] ?? '');
            $strEstado = strClean($data['estado'] ?? 'activo');
            $strDescripcion = strClean($data['descripcion'] ?? '');

            if (empty($strCodigo) || empty($strNombre) || $intCapacidad <= 0) {
                json_response(['status' => false, 'msg' => 'Los campos codigo, nombre y capacidad_maxima son obligatorios'], 400);
                return;
            }

            $request = $this->model->insertGalpon(
                $strCodigo,
                $strNombre,
                $intCapacidad,
                $floatArea,
                $strUbicacion,
                $strEstado,
                $strDescripcion
            );

            if ($request > 0) {
                json_response([
                    'status' => true,
                    'msg' => 'Galpón creado exitosamente',
                    'id' => $request
                ], 201);
            } else if ($request == 'exist') {
                json_response(['status' => false, 'msg' => 'El código del galpón ya existe'], 409);
            } else {
                json_response(['status' => false, 'msg' => 'Error al crear galpón'], 500);
            }
        } catch (Exception $e) {
            json_response(['status' => false, 'msg' => 'Error en el servidor: ' . $e->getMessage()], 500);
        }
    }

    // PUT - Actualizar galpón
    public function putGalpon($id)
    {
        try {
            $intId = intval(strClean($id));
            $jsonData = file_get_contents('php://input');
            $data = json_decode($jsonData, true);

            if (empty($data)) {
                json_response(['status' => false, 'msg' => 'No se recibieron datos'], 400);
                return;
            }

            $strCodigo = strClean($data['codigo'] ?? '');
            $strNombre = strClean($data['nombre'] ?? '');
            $intCapacidad = intval($data['capacidad_maxima'] ?? 0);
            $floatArea = floatval($data['area_m2'] ?? 0);
            $strUbicacion = strClean($data['ubicacion'] ?? '');
            $strEstado = strClean($data['estado'] ?? '');
            $strDescripcion = strClean($data['descripcion'] ?? '');

            if ($intId <= 0 || empty($strCodigo) || empty($strNombre)) {
                json_response(['status' => false, 'msg' => 'Datos incompletos o inválidos'], 400);
                return;
            }

            $request = $this->model->updateGalpon(
                $intId,
                $strCodigo,
                $strNombre,
                $intCapacidad,
                $floatArea,
                $strUbicacion,
                $strEstado,
                $strDescripcion
            );

            if ($request) {
                json_response(['status' => true, 'msg' => 'Galpón actualizado exitosamente'], 200);
            } else {
                json_response(['status' => false, 'msg' => 'Error al actualizar galpón o galpón no encontrado'], 500);
            }
        } catch (Exception $e) {
            json_response(['status' => false, 'msg' => 'Error en el servidor: ' . $e->getMessage()], 500);
        }
    }

    // DELETE - Eliminar galpón permanentemente
    public function deleteGalpon($id)
    {
        try {
            $intId = intval(strClean($id));

            if ($intId > 0) {
                $request = $this->model->deleteGalpon($intId);

                if ($request) {
                    json_response(['status' => true, 'msg' => 'Galpón eliminado exitosamente'], 200);
                } else {
                    json_response(['status' => false, 'msg' => 'Error al eliminar galpón o galpón no encontrado'], 500);
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
'@ | Out-File -FilePath "Controllers\Galpones.php" -Encoding UTF8 -Force