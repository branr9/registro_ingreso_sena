<?php
/**
 * Modelo User - Gestión de usuarios
 */

class User
{
    private Database $db;
    private ?PDO $connection;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->connection = $this->db->getConnection();
    }

    /**
     * Buscar usuario por username o email
     */
    public function findByCredential(string $credential): ?array
    {
        $sql = "SELECT us.id,
                       us.persona_id,
                       us.username,
                       us.password_hash,
                       us.estado,
                       us.intentos_fallidos,
                       us.bloqueado_hasta,
                       us.last_login_at,
                       p.documento,
                       p.nombres,
                       p.apellidos,
                       CONCAT(p.nombres, ' ', p.apellidos) as nombre,
                       p.email,
                       p.estado as persona_estado,
                       crs.codigo as rol
                FROM usuarios_sistema us
                INNER JOIN personas p ON us.persona_id = p.id
                INNER JOIN cat_roles crs ON us.rol_id = crs.id
                WHERE (us.username = :credential OR p.email = :credential2)
                  AND us.estado = 'ACTIVO'
                  AND p.estado = 'ACTIVO'
                  AND p.deleted_at IS NULL
                LIMIT 1";
        
        return $this->db->fetchOne($sql, [
            'credential' => $credential,
            'credential2' => $credential
        ]);
    }

    /**
     * Buscar usuario por ID (persona_id)
     */
    public function findById(int $id): ?array
    {
        $sql = "SELECT us.id,
                       us.persona_id,
                       us.username,
                       us.password_hash,
                       us.estado,
                       us.intentos_fallidos,
                       us.bloqueado_hasta,
                       us.last_login_at,
                       p.documento,
                       p.nombres,
                       p.apellidos,
                       CONCAT(p.nombres, ' ', p.apellidos) as nombre,
                       p.email,
                       crs.codigo as rol
                FROM usuarios_sistema us
                INNER JOIN personas p ON us.persona_id = p.id
                INNER JOIN cat_roles crs ON us.rol_id = crs.id
                WHERE p.id = :id
                  AND p.deleted_at IS NULL
                LIMIT 1";
        return $this->db->fetchOne($sql, ['id' => $id]);
    }

    /**
     * Verificar si el usuario está bloqueado por intentos fallidos
     */
    public function isLocked(array $user): bool
    {
        if (!$user['bloqueado_hasta']) {
            return false;
        }

        $lockoutTime = strtotime($user['bloqueado_hasta']);
        $now = time();

        if ($now < $lockoutTime) {
            return true;
        }

        // Si el tiempo de bloqueo ya pasó, resetear el bloqueo
        $this->resetFailedAttempts($user['id']);
        return false;
    }

    /**
     * Incrementar intentos fallidos
     */
    public function incrementFailedAttempts(int $userId): void
    {
        $sql = "UPDATE usuarios_sistema 
                SET intentos_fallidos = intentos_fallidos + 1,
                    bloqueado_hasta = CASE 
                        WHEN intentos_fallidos + 1 >= :max_attempts 
                        THEN DATE_ADD(NOW(), INTERVAL :lockout_time SECOND)
                        ELSE bloqueado_hasta
                    END
                WHERE id = :id";

        $this->db->query($sql, [
            'max_attempts' => MAX_LOGIN_ATTEMPTS,
            'lockout_time' => LOCKOUT_TIME,
            'id' => $userId
        ]);
    }

    /**
     * Resetear intentos fallidos después de login exitoso
     */
    public function resetFailedAttempts(int $userId): void
    {
        $sql = "UPDATE usuarios_sistema 
                SET intentos_fallidos = 0, 
                    bloqueado_hasta = NULL 
                WHERE id = :id";
        
        $this->db->query($sql, ['id' => $userId]);
    }

    /**
     * Actualizar último acceso
     */
    public function updateLastAccess(int $userId): void
    {
        $sql = "UPDATE usuarios_sistema SET last_login_at = NOW() WHERE id = :id";
        $this->db->query($sql, ['id' => $userId]);
    }

    /**
     * Registrar auditoría de acceso
     */
    public function logAccess(
        ?int $userId, 
        string $action, 
        string $ipAddress, 
        string $userAgent, 
        ?string $details = null
    ): void {
        $sql = "INSERT INTO auditoria_accesos 
                (usuario_id, accion, ip_address, user_agent, detalles) 
                VALUES (:usuario_id, :accion, :ip_address, :user_agent, :detalles)";

        $this->db->query($sql, [
            'usuario_id' => $userId,
            'accion' => $action,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'detalles' => $details
        ]);
    }

    /**
     * Validar credenciales de usuario
     */
    public function validateCredentials(string $credential, string $password): ?array
    {
        // Buscar usuario
        $user = $this->findByCredential($credential);

        if (!$user) {
            return null;
        }

        // Verificar si está bloqueado
        if ($this->isLocked($user)) {
            return null;
        }

        // Verificar password
        if (!password_verify($password, $user['password_hash'])) {
            $this->incrementFailedAttempts($user['id']);
            return null;
        }

        // Verificar si está activo
        if (strtoupper($user['estado']) !== 'ACTIVO') {
            return null;
        }

        // Login exitoso
        $this->resetFailedAttempts($user['id']);
        $this->updateLastAccess($user['id']);

        return $user;
    }

    /**
     * Crear nuevo usuario (para uso administrativo futuro)
     */
    public function create(array $data): int
    {
        $sql = "INSERT INTO usuarios 
                (nombre, email, username, password_hash, rol, estado) 
                VALUES (:nombre, :email, :username, :password_hash, :rol, :estado)";

        $this->db->query($sql, [
            'nombre' => $data['nombre'],
            'email' => $data['email'],
            'username' => $data['username'],
            'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
            'rol' => $data['rol'] ?? 'vigilante',
            'estado' => $data['estado'] ?? 'activo'
        ]);

        return (int)$this->db->lastInsertId();
    }
}
