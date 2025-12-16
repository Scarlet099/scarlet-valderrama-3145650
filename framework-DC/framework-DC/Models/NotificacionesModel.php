@'
<?php

class NotificacionesModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    // Seleccionar todas las notificaciones
    public function selectNotificaciones()
    {
        $sql = "SELECT n.*, u.nombre as usuario_nombre, u.username
                FROM notificaciones n
                INNER JOIN usuarios u ON n.usuario_id = u.id
                ORDER BY n.created_at DESC";
        $request = $this->select_all($sql);
        return $request;
    }

    // Seleccionar una notificación por ID
    public function selectNotificacion(int $id)
    {
        $sql = "SELECT n.*, u.nombre as usuario_nombre, u.username
                FROM notificaciones n
                INNER JOIN usuarios u ON n.usuario_id = u.id
                WHERE n.id = ?";
        $arrData = array($id);
        $request = $this->select($sql, $arrData);
        return !empty($request) ? $request[0] : array();
    }

    // Seleccionar notificaciones por usuario
    public function selectNotificacionesByUsuario(int $usuarioId)
    {
        $sql = "SELECT n.*, u.nombre as usuario_nombre, u.username
                FROM notificaciones n
                INNER JOIN usuarios u ON n.usuario_id = u.id
                WHERE n.usuario_id = ?
                ORDER BY n.created_at DESC";
        $arrData = array($usuarioId);
        $request = $this->select($sql, $arrData);
        return $request;
    }

    // Seleccionar notificaciones no leídas de un usuario
    public function selectNotificacionesNoLeidas(int $usuarioId)
    {
        $sql = "SELECT n.*, u.nombre as usuario_nombre, u.username
                FROM notificaciones n
                INNER JOIN usuarios u ON n.usuario_id = u.id
                WHERE n.usuario_id = ? AND n.leido = 0
                ORDER BY n.created_at DESC";
        $arrData = array($usuarioId);
        $request = $this->select($sql, $arrData);
        return $request;
    }

    // Insertar notificación
    public function insertNotificacion(
        int $usuarioId,
        string $tipo,
        string $titulo,
        string $mensaje,
        string $icono,
        string $color,
        string $urlDestino
    ) {
        $sql = "INSERT INTO notificaciones 
                (usuario_id, tipo, titulo, mensaje, icono, color, url_destino, leido, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 0, NOW())";
        $arrData = array($usuarioId, $tipo, $titulo, $mensaje, $icono, $color, $urlDestino);
        $request = $this->insert($sql, $arrData);
        return $request;
    }

    // Actualizar notificación
    public function updateNotificacion(
        int $id,
        string $tipo,
        string $titulo,
        string $mensaje,
        string $icono,
        string $color,
        string $urlDestino
    ) {
        // Verificar que la notificación exista
        $sqlCheck = "SELECT id FROM notificaciones WHERE id = ?";
        $check = $this->select($sqlCheck, array($id));
        
        if (empty($check)) {
            return false;
        }

        $sql = "UPDATE notificaciones 
                SET tipo = ?, titulo = ?, mensaje = ?, icono = ?, color = ?, url_destino = ? 
                WHERE id = ?";
        $arrData = array($tipo, $titulo, $mensaje, $icono, $color, $urlDestino, $id);
        $request = $this->update($sql, $arrData);
        return $request;
    }

    // Marcar notificación como leída
    public function marcarComoLeida(int $id)
    {
        $sql = "UPDATE notificaciones 
                SET leido = 1, fecha_leido = NOW() 
                WHERE id = ?";
        $arrData = array($id);
        $request = $this->update($sql, $arrData);
        return $request;
    }

    // Marcar todas las notificaciones de un usuario como leídas
    public function marcarTodasComoLeidas(int $usuarioId)
    {
        $sql = "UPDATE notificaciones 
                SET leido = 1, fecha_leido = NOW() 
                WHERE usuario_id = ? AND leido = 0";
        $arrData = array($usuarioId);
        $request = $this->update($sql, $arrData);
        return $request;
    }

    // Eliminar notificación (HARD DELETE)
    public function deleteNotificacion(int $id)
    {
        $sql = "DELETE FROM notificaciones WHERE id = ?";
        $arrData = array($id);
        $request = $this->delete($sql, $arrData);
        return $request;
    }

    // Eliminar todas las notificaciones de un usuario (HARD DELETE)
    public function deleteNotificacionesByUsuario(int $usuarioId)
    {
        $sql = "DELETE FROM notificaciones WHERE usuario_id = ?";
        $arrData = array($usuarioId);
        $request = $this->delete($sql, $arrData);
        return $request;
    }
}
?>
'@ | Out-File -FilePath "Models\NotificacionesModel.php" -Encoding UTF8 -Force