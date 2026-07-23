<?php
/**
 * Clase Database - Singleton para conexión PDO
 */

class Database
{
    private static ?Database $instance = null;
    private ?PDO $connection = null;
    private array $config;

    private function __construct()
    {
        $this->config = require CONFIG_PATH . '/database.php';
        $this->connect();
    }

    /**
     * Obtener instancia única de Database (Singleton)
     */
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Establecer conexión con la base de datos
     */
    private function connect(): void
    {
        try {
            $dsn = sprintf(
                "mysql:host=%s;port=%s;dbname=%s;charset=%s",
                $this->config['host'],
                $this->config['port'],
                $this->config['database'],
                $this->config['charset']
            );

            $this->connection = new PDO(
                $dsn,
                $this->config['username'],
                $this->config['password'],
                $this->config['options']
            );
        } catch (PDOException $e) {
            $this->logError('Error de conexión a la base de datos: ' . $e->getMessage());
            if (APP_DEBUG) {
                die('Error de conexión a la base de datos: ' . $e->getMessage());
            } else {
                die('Error de conexión a la base de datos. Contacte al administrador.');
            }
        }
    }

    /**
     * Obtener la conexión PDO
     */
    public function getConnection(): PDO
    {
        if ($this->connection === null) {
            $this->connect();
        }
        return $this->connection;
    }

    /**
     * Ejecutar una consulta preparada
     */
    public function query(string $sql, array $params = []): PDOStatement
    {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            $this->logError('Error en consulta SQL: ' . $e->getMessage() . ' | SQL: ' . $sql);
            throw $e;
        }
    }

    /**
     * Obtener un solo registro
     */
    public function fetchOne(string $sql, array $params = []): ?array
    {
        $stmt = $this->query($sql, $params);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Obtener múltiples registros
     */
    public function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }

    /**
     * Obtener el último ID insertado
     */
    public function lastInsertId(): string
    {
        return $this->connection->lastInsertId();
    }

    /**
     * Iniciar transacción
     */
    public function beginTransaction(): bool
    {
        return $this->connection->beginTransaction();
    }

    /**
     * Confirmar transacción
     */
    public function commit(): bool
    {
        return $this->connection->commit();
    }

    /**
     * Revertir transacción
     */
    public function rollback(): bool
    {
        return $this->connection->rollBack();
    }

    /**
     * Registrar errores
     */
    private function logError(string $message): void
    {
        $logFile = STORAGE_PATH . '/logs/database_errors.log';
        $logDir = dirname($logFile);
        
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[{$timestamp}] {$message}" . PHP_EOL;
        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }

    /**
     * Prevenir clonación del Singleton
     */
    private function __clone() {}

    /**
     * Prevenir deserialización del Singleton
     */
    public function __wakeup()
    {
        throw new Exception("No se puede deserializar un singleton.");
    }
}
