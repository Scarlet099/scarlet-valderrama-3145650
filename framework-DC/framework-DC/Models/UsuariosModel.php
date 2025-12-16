@'
<?php

class UsuariosModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    // Seleccionar todos los usuarios
    public function selectUsuarios()
    {
        $sql = "SELECT id, nombre, username, email, rol, estado, avatar_url, 
                ultimo_acceso, created_at, updated_at 
                FROM usuarios 
                ORDER BY id DESC";
        $request = $this->select_all($sql);
        return $request;
    }

    // Seleccionar un usuario por ID
    public function selectUsuario(int $id)
    {
        $sql = "SELECT id, nombre, username, email, rol, estado, avatar_url, 
                ultimo_acceso, created_at, updated_at 
                FROM usuarios 
                WHERE id = ?";
        $arrData = array($id);
        $request = $this->select($sql, $arrData);
        return !empty($request) ? $request[0] : array();
    }

    // Insertar usuario
    public function insertUsuario(
        string $nombre,
        string $username,
        string $email,
        string $password,
        string $rol,
        string $estado
    ) {
        // Verificar si el usuario o email ya existe
        $sql = "SELECT * FROM usuarios WHERE username = ? OR email = ?";
        $arrData = array($username, $email);
        $request = $this->select($sql, $arrData);

        if (empty($request)) {
            $query = "INSERT INTO usuarios (nombre, username, email, password_hash, rol, estado, created_at, updated_at) 
                      VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())";
            $arrData = array($nombre, $username, $email, $password, $rol, $estado);
            $request_insert = $this->insert($query, $arrData);
            return $request_insert;
        } else {
            return "exist";
        }
    }

    // Actualizar usuario
    public function updateUsuario(
        int $id,
        string $nombre,
        string $username,
        string $email,
        string $rol,
        string $estado
    ) {
        // Verificar que el usuario exista
        $sqlCheck = "SELECT id FROM usuarios WHERE id = ?";
        $check = $this->select($sqlCheck, array($id));
        
        if (empty($check)) {
            return false;
        }

        $sql = "UPDATE usuarios 
                SET nombre = ?, username = ?, email = ?, rol = ?, estado = ?, 
                    updated_at = NOW() 
                WHERE id = ?";
        $arrData = array($nombre, $username, $email, $rol, $estado, $id);
        $request = $this->update($sql, $arrData);
        return $request;
    }

    // Eliminar usuario (HARD DELETE)
    public function deleteUsuario(int $id)
    {
        $sql = "DELETE FROM usuarios WHERE id = ?";
        $arrData = array($id);
        $request = $this->delete($sql, $arrData);
        return $request;
    }
}
?>
'@ | Out-File -FilePath "Models\UsuariosModel.php" -Encoding UTF8 -Force