<?php
/**
 * Controller: CRUD Generator Tool
 * 
 * Halaman untuk generate Controller, Model, View secara otomatis.
 * Hanya admin yang bisa akses.
 */
class CrudGeneratorController extends BaseController
{
    public function __construct()
    {
        AuthMiddleware::handle('admin');
    }

    /**
     * Halaman utama CRUD Generator
     */
    public function index(): void
    {
        $this->view('crud-generator/index', [
            'title' => 'CRUD Generator',
        ]);
    }

    /**
     * Proses generate CRUD dari form
     */
    public function generate(): void
    {
        if (!$this->isPost()) $this->redirect('/crud-generator');
        $this->validateCsrf();

        $moduleName = strtolower(trim($this->input('module_name', '')));
        $tableName  = strtolower(trim($this->input('table_name', $moduleName)));
        $label      = trim($this->input('label', ''));

        if (empty($moduleName)) {
            Session::setFlash('error', 'Nama modul wajib diisi.');
            $this->redirect('/crud-generator');
            return;
        }

        // Parse field definitions dari textarea
        $fieldsRaw = $this->input('fields', '');
        $fields = $this->parseFields($fieldsRaw);

        if (empty($fields)) {
            Session::setFlash('error', 'Minimal 1 field harus diisi.');
            $this->redirect('/crud-generator');
            return;
        }

        try {
            $generator = new CrudGenerator();
            $generator->setName($moduleName)
                      ->setTable($tableName)
                      ->setLabel($label ?: ucwords(str_replace('_', ' ', $moduleName)))
                      ->setFields($fields);

            $results = $generator->generate();

            // Tambahkan routing ke index.php (opsional, manual)
            $sql = $generator->generateSql();

            Session::setFlash('success', "Modul <b>{$label}</b> berhasil digenerate! " .
                "File dibuat: Controller, Model, dan Views (index, create, edit). " .
                "Silakan tambahkan routing di <code>public/index.php</code> secara manual.");

            // Simpan SQL untuk referensi
            Session::set('_generated_sql', $sql);

        } catch (\Exception $e) {
            Session::setFlash('error', 'Gagal generate: ' . $e->getMessage());
        }

        $this->redirect('/crud-generator');
    }

    /**
     * Parse field definitions dari textarea
     * 
     * Format per baris:
     *   nama_kolom|Label|type|required|options
     * 
     * Contoh:
     *   nama|Nama Lengkap|text|required|
     *   jenis_kelamin|Jenis Kelamin|select|required|L:Laki-laki,P:Perempuan
     *   alamat|Alamat|textarea||
     */
    private function parseFields(string $raw): array
    {
        $fields = [];
        $lines = explode("\n", trim($raw));

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;

            $parts = explode('|', $line);
            $name = trim($parts[0] ?? '');

            if (empty($name)) continue;

            $field = [
                'name'     => $name,
                'label'    => trim($parts[1] ?? ucwords(str_replace('_', ' ', $name))),
                'type'     => trim($parts[2] ?? 'text'),
                'required' => strtolower(trim($parts[3] ?? '')) === 'required',
            ];

            // Parse options untuk type select
            if ($field['type'] === 'select' && !empty($parts[4])) {
                $options = [];
                $optPairs = explode(',', $parts[4]);
                foreach ($optPairs as $pair) {
                    $kv = explode(':', trim($pair));
                    $options[] = [
                        'value' => $kv[0] ?? '',
                        'label' => $kv[1] ?? $kv[0] ?? '',
                    ];
                }
                $field['options'] = $options;
            }

            $fields[] = $field;
        }

        return $fields;
    }
}
