<?php
/**
 * ═══════════════════════════════════════════════════════════════
 *  Database Class - PDO Wrapper dengan prepared statement
 * ═══════════════════════════════════════════════════════════════
 * Pattern: Singleton + PDO
 */
class Database
{
    private static $instance = null;
    private $pdo;

    private function __construct()
    {
        $db = DB_CONFIG;
        $dsn = "mysql:host={$db['host']};port={$db['port']};dbname={$db['name']};charset={$db['charset']}";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE  => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES    => false,
            PDO::MYSQL_ATTR_INIT_COMMAND  => "SET NAMES {$db['charset']}",
        ];
        try {
            $this->pdo = new PDO($dsn, $db['user'], $db['pass'], $options);
        } catch (PDOException $e) {
            // Jangan expose detail error di production
            if (defined('APP_DEBUG') && APP_DEBUG) {
                die("Koneksi database gagal: " . htmlspecialchars($e->getMessage()));
            } else {
                error_log("Database connection failed: " . $e->getMessage());
                die("Koneksi database gagal. Silakan hubungi administrator.");
            }
        }
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }

    public function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function fetch($sql, array $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    public function execute(string $sql, array $params = []): int
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    public function insert(string $sql, array $params = []): string
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $this->pdo->lastInsertId();
    }

    /**
     * Get list of tables in database
     */
    public function getTables(): array
    {
        $sql = "SHOW TABLES";
        $stmt = $this->pdo->query($sql);
        $tables = [];
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $tables[] = $row[0];
        }
        return $tables;
    }

    /**
     * Get column structure for a table
     */
    public function getTableColumns(string $table): array
    {
        $sql = "DESCRIBE `{$table}`";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }

    public function commit(): bool
    {
        return $this->pdo->commit();
    }

    public function rollback(): bool
    {
        return $this->pdo->rollBack();
    }
}
