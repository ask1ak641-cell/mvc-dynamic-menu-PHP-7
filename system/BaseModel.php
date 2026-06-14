<?php
/**
 * ═══════════════════════════════════════════════════════════════
 *  BaseModel - Kelas dasar untuk semua model
 * ═══════════════════════════════════════════════════════════════
 */
abstract class BaseModel
{
    protected $db;
    protected $table;
    protected $primaryKey = 'id';

    /** @var array Kolom yang diizinkan untuk whitelist (override di child) */
    protected $fillable = [];

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Ambil semua record
     * 
     * @param string $orderBy Kolom urutan (hanya alphanumeric, dot, underscore)
     */
    public function all(string $orderBy = ''): array
    {
        $sql = "SELECT * FROM `{$this->table}`";
        if ($orderBy !== '') {
            // Validasi orderBy untuk mencegah SQL injection
            // Hanya izinkan: huruf, angka, underscore, dot, spasi, ASC, DESC, koma
            if (!preg_match('/^[a-zA-Z0-9_\.\s,]+$/', $orderBy)) {
                $orderBy = '';
            } else {
                $sql .= " ORDER BY {$orderBy}";
            }
        }
        return $this->db->fetchAll($sql);
    }

    /**
     * Cari record berdasarkan primary key
     */
    public function find($id)
    {
        $sql = "SELECT * FROM `{$this->table}` WHERE `{$this->primaryKey}` = ? LIMIT 1";
        return $this->db->fetch($sql, [$id]);
    }

    /**
     * Insert record baru
     * Jika $fillable di-set, hanya kolom tersebut yang diizinkan
     */
    public function insert(array $data): string
    {
        // Filter berdasarkan fillable jika di-set
        if (!empty($this->fillable)) {
            $data = array_intersect_key($data, array_flip($this->fillable));
        }

        if (empty($data)) {
            throw new \InvalidArgumentException('Tidak ada data valid untuk di-insert.');
        }

        $columns = implode('`, `', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO `{$this->table}` (`{$columns}`) VALUES ({$placeholders})";
        return $this->db->insert($sql, array_values($data));
    }

    /**
     * Update record berdasarkan primary key
     * Jika $fillable di-set, hanya kolom tersebut yang diizinkan
     */
    public function update($id, array $data)
    {
        // Filter berdasarkan fillable jika di-set
        if (!empty($this->fillable)) {
            $data = array_intersect_key($data, array_flip($this->fillable));
        }

        if (empty($data)) {
            return 0;
        }

        $sets = implode(' = ?, ', array_keys($data)) . ' = ?';
        $sql = "UPDATE `{$this->table}` SET {$sets} WHERE `{$this->primaryKey}` = ?";
        $params = array_values($data);
        $params[] = $id;
        return $this->db->execute($sql, $params);
    }

    /**
     * Hapus record berdasarkan primary key
     */
    public function delete($id)
    {
        $sql = "DELETE FROM `{$this->table}` WHERE `{$this->primaryKey}` = ?";
        return $this->db->execute($sql, [$id]);
    }

    /**
     * Hitung jumlah record
     */
    public function count(string $where = '', array $params = []): int
    {
        $sql = "SELECT COUNT(*) as total FROM `{$this->table}`";
        if ($where) $sql .= " WHERE {$where}";
        $result = $this->db->fetch($sql, $params);
        return (int) ($result['total'] ?? 0);
    }

    /**
     * Custom query - fetchAll
     */
    public function query(string $sql, array $params = []): array
    {
        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Paginasi record
     * 
     * @param int $page Halaman saat ini (min 1)
     * @param int $perPage Jumlah per halaman (min 1, max 100)
     */
    public function paginate(int $page = 1, int $perPage = 10, string $where = '', array $params = [], string $orderBy = ''): array
    {
        // Validasi input
        $page = max(1, $page);
        $perPage = min(max(1, $perPage), 100); // Max 100 per halaman
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT * FROM `{$this->table}`";
        if ($where) $sql .= " WHERE {$where}";
        if ($orderBy !== '') {
            // Validasi orderBy
            if (preg_match('/^[a-zA-Z0-9_\.\s,]+$/', $orderBy)) {
                $sql .= " ORDER BY {$orderBy}";
            }
        }
        $sql .= " LIMIT {$perPage} OFFSET {$offset}";

        $data = $this->db->fetchAll($sql, $params);
        $total = $this->count($where, $params);

        return [
            'data'        => $data,
            'total'       => $total,
            'page'        => $page,
            'per_page'    => $perPage,
            'total_pages' => (int) ceil($total / $perPage),
        ];
    }
}
