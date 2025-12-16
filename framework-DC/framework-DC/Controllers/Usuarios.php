@'
<?php

class Usuarios extends Controllers
{
    public function __construct()
    {
        parent::__construct();
    }

    // GET - Obtener todos los usuarios
    public function getUsuarios()
    {
        try {
            $arrData = $this->model->selectUsuarios();
            
            if (empty($arrData)) {
                json_response(['status' => false, 'msg' => 'No hay usuarios registrados'], 404);
            } else {
                json_response(['status' => true, 'data' => $arrData, 'count' => count($arrData)], 200);
            }
        } catch (Exception $e) {
            json_response(['status' => false, 'msg' => 'Error en el servidor: ' . $e->getMessage()], 500);
        }
    }

    // GET - Obtener un usuario por ID
    public function getUsuario($id)
    {
        try {
            $intId = intval(strClean($id));
            
            if ($intId > 0) {
                $arrData = $this->model->selectUsuario($intId);
                
                if (empty($arrData)) {
                    json_response(['status' => false, 'msg' => 'Usuario no encontrado'], 404);
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

    // POST - Crear usuario
    public function setUsuario()
    {
        try {
            $jsonData = file_get_contents('php://input');
            $data = json_decode($jsonData, true);

            if (empty($data)) {
                json_response(['status' => false, 'msg' => 'No se recibieron datos'], 400);
                return;
            }

            $strNombre = strClean($data['nombre'] ?? '');
            $strUsername = strClean($data['username'] ?? '');
            $strEmail = strClean($data['email'] ?? '');
            $strPassword = strClean($data['password'] ?? '');
            $strRol = strClean($data['rol'] ?? 'usuario');
            $strEstado = strClean($data['estado'] ?? 'activo');

            if (empty($strNombre) || empty($strUsername) || empty($strEmail) || empty($strPassword)) {
                json_response(['status' => false, 'msg' => 'Los campos nombre, username, email y password son obligatorios'], 400);
                return;
            }

            // Validar email
            if (!filter_var($strEmail, FILTER_VALIDATE_EMAIL)) {
                json_response(['status' => false, 'msg' => 'Email inválido'], 400);
                return;
            }

            $strPasswordHash = password_hash($strPassword, PASSWORD_DEFAULT);

            $request = $this->model->insertUsuario(
                $strNombre,
                $strUsername,
                $strEmail,
                $strPasswordHash,
                $strRol,
                $strEstado
            );

            if ($request > 0) {
                json_response([
                    'status' => true,
                    'msg' => 'Usuario creado exitosamente',
                    'id' => $request
                ], 201);
            } else if ($request == 'exist') {
                json_response(['status' => false, 'msg' => 'El usuario o email ya existe'], 409);
            } else {
                json_response(['status' => false, 'msg' => 'Error al crear usuario'], 500);
            }
        } catch (Exception $e) {
            json_response(['status' => false, 'msg' => 'Error en el servidor: ' . $e->getMessage()], 500);
        }
    }

    // PUT - Actualizar usuario
    public function putUsuario($id)
    {
        try {
            $intId = intval(strClean($id));
            $jsonData = file_get_contents('php://input');
            $data = json_decode($jsonData, true);

            if (empty($data)) {
                json_response(['status' => false, 'msg' => 'No se recibieron datos'], 400);
                return;
            }

            $strNombre = strClean($data['nombre'] ?? '');
            $strUsername = strClean($data['username'] ?? '');
            $strEmail = strClean($data['email'] ?? '');
            $strRol = strClean($data['rol'] ?? '');
            $strEstado = strClean($data['estado'] ?? '');

            if ($intId <= 0 || empty($strNombre) || empty($strUsername) || empty($strEmail)) {
                json_response(['status' => false, 'msg' => 'Datos incompletos o inválidos'], 400);
                return;
            }

            // Validar email
            if (!filter_var($strEmail, FILTER_VALIDATE_EMAIL)) {
                json_response(['status' => false, 'msg' => 'Email inválido'], 400);
                return;
            }

            $request = $this->model->updateUsuario(
                $intId,
                $strNombre,
                $strUsername,
                $strEmail,
                $strRol,
                $strEstado
            );

            if ($request) {
                json_response(['status' => true, 'msg' => 'Usuario actualizado exitosamente'], 200);
            } else {
                json_response(['status' => false, 'msg' => 'Error al actualizar usuario o usuario no encontrado'], 500);
            }
        } catch (Exception $e) {
            json_response(['status' => false, 'msg' => 'Error en el servidor: ' . $e->getMessage()], 500);
        }
    }

    // DELETE - Eliminar usuario permanentemente
    public function deleteUsuario($id)
    {
        try {
            $intId = intval(strClean($id));

            if ($intId > 0) {
                $request = $this->model->deleteUsuario($intId);

                if ($request) {
                    json_response(['status' => true, 'msg' => 'Usuario eliminado exitosamente'], 200);
                } else {
                    json_response(['status' => false, 'msg' => 'Error al eliminar usuario o usuario no encontrado'], 500);
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
'@ | Out-File -FilePath "Controllers\Usuarios.php" -Encoding UTF8 -Force