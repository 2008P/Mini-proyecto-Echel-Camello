<?php
/**
 * Script de inicialización y migración automática de la Base de Datos
 * Échele Camello - MySQL / XAMPP
 */

$isCli = (php_sapi_name() === 'cli');

if (!$isCli) {
    header('Content-Type: text/html; charset=utf-8');
}

$hosts = ['127.0.0.1', 'localhost'];
$ports = [3307, 3306];
$user = 'root';
$pass = '';

$pdo = null;
$connectedPort = null;
$connectedHost = null;
$lastError = '';

foreach ($ports as $port) {
    foreach ($hosts as $host) {
        try {
            $dsn = "mysql:host={$host};port={$port};charset=utf8mb4";
            $pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 2
            ]);
            $connectedPort = $port;
            $connectedHost = $host;
            break 2;
        } catch (PDOException $e) {
            $lastError = $e->getMessage();
        }
    }
}

if (!$pdo) {
    $msg = "Error: No se pudo conectar a MySQL en localhost:3307 ni localhost:3306. ($lastError)";
    if ($isCli) {
        fwrite(STDERR, $msg . PHP_EOL);
        exit(1);
    } else {
        die("<h2 style='color:red;'>$msg</h2><p>Por favor verifique que MySQL esté iniciado en su XAMPP Control Panel.</p>");
    }
}

// Leer archivo SQL
$sqlFile = __DIR__ . '/database.sql';
if (!file_exists($sqlFile)) {
    die("Error: No se encuentra el archivo database.sql en " . $sqlFile);
}

$sqlContent = file_get_contents($sqlFile);

try {
    // Ejecutar statements SQL
    $pdo->exec($sqlContent);
    
    // Contar registros creados
    $pdo->exec("USE `echele_camello`");
    $jobsCount = $pdo->query("SELECT COUNT(*) FROM `jobs`")->fetchColumn();
    $appsCount = $pdo->query("SELECT COUNT(*) FROM `applications`")->fetchColumn();
    $candidatesCount = $pdo->query("SELECT COUNT(*) FROM `candidates`")->fetchColumn();

    $successMsg = "Base de datos 'echele_camello' sincronizada exitosamente en MySQL (Puerto $connectedPort).";
    
    if ($isCli) {
        echo "=== ÉCHELE CAMELLO - INSTALACIÓN BD ===" . PHP_EOL;
        echo "[OK] Conectado a MySQL en {$connectedHost}:{$connectedPort}" . PHP_EOL;
        echo "[OK] Base de datos y tablas creadas." . PHP_EOL;
        echo "[OK] Vacantes activas: {$jobsCount}" . PHP_EOL;
        echo "[OK] Postulaciones de muestra: {$appsCount}" . PHP_EOL;
        echo "[OK] Candidatos disponibles: {$candidatesCount}" . PHP_EOL;
        echo "=======================================" . PHP_EOL;
    } else {
        echo "<div style='font-family:sans-serif;padding:30px;background:#f6ffed;border:1px solid #b7eb8f;border-radius:12px;margin:30px auto;max-width:650px;color:#135200;'>";
        echo "<h2>✅ Base de datos sincronizada con éxito en XAMPP</h2>";
        echo "<p>Conexión establecida con MySQL en el puerto <strong>{$connectedPort}</strong>.</p>";
        echo "<ul>";
        echo "<li><strong>Vacantes activas:</strong> {$jobsCount}</li>";
        echo "<li><strong>Postulaciones registradas:</strong> {$appsCount}</li>";
        echo "<li><strong>Candidatos:</strong> {$candidatesCount}</li>";
        echo "</ul>";
        echo "<p><a href='index.php' style='display:inline-block;padding:10px 20px;background:#a83301;color:white;text-decoration:none;border-radius:8px;font-weight:bold;'>Ir al Portal Principal</a></p>";
        echo "</div>";
    }
} catch (Exception $e) {
    $err = "Error al ejecutar migración SQL: " . $e->getMessage();
    if ($isCli) {
        fwrite(STDERR, $err . PHP_EOL);
        exit(1);
    } else {
        die("<h2 style='color:red;'>$err</h2>");
    }
}
