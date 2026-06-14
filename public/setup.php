<?php
$base = '/root/.openclaw-autoclaw/workspace/template-src';
$files = [
  'app/controllers/MenuController.php',
  'app/controllers/UserController.php',
  'app/views/dashboard/index.php',
  'app/views/menu/edit.php',
  'app/views/menu/index.php',
  'app/views/tesmenu/index.php',
  'public/index.php',
  'public/reset-password.php',
  'install/database.sql',
];

$patcher = "<?php\n";
$patcher .= "// ═══ Patcher - Update Template ═══\n";
$patcher .= "// Jalankan: php setup.php\n";
$patcher .= "define('BASE', __DIR__);\n\n";
$patcher .= "// Hapus CRUD Generator\n";
$patcher .= "\$crudFile = BASE . '/app/controllers/CrudGeneratorController.php';\n";
$patcher .= "if (file_exists(\$crudFile)) { unlink(\$crudFile); echo \"  ❌ Hapus CrudGeneratorController.php\\n\"; }\n\n";
$patcher .= "echo \"📝 Menulis ulang file...\\n\\n\";\n";

foreach ($files as $f) {
  $content = file_get_contents($base . '/' . $f);
  $b64 = base64_encode(gzcompress($content, 9));
  $patcher .= "file_put_contents(BASE . '/$f', gzuncompress(base64_decode('$b64')));\n";
  $patcher .= "echo \"  ✅ $f\\n\";\n\n";
}

$patcher .= "echo \"\\n╔══════════════════════╗\\n\";\n";
$patcher .= "echo \"║  SELESAI!           ║\\n\";\n";
$patcher .= "echo \"║  " . count($files) . " file terupdate   ║\\n\";\n";
$patcher .= "╚══════════════════════╝\n";\n";

file_put_contents('/root/.openclaw-autoclaw/workspace/setup.php', $patcher);
echo "Patcher created: " . filesize('/root/.openclaw-autoclaw/workspace/setup.php') . " bytes\n";
