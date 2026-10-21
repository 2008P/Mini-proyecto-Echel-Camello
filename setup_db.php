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
    $usersCount = $pdo->query("SELECT COUNT(*) FROM `users`")->fetchColumn();
    $solicitudesCount = $pdo->query("SELECT COUNT(*) FROM `solicitudes`")->fetchColumn();
    $conversacionesCount = $pdo->query("SELECT COUNT(*) FROM `conversaciones`")->fetchColumn();
    $notificacionesCount = $pdo->query("SELECT COUNT(*) FROM `notificaciones`")->fetchColumn();

    $successMsg = "Base de datos 'echele_camello' sincronizada exitosamente en MySQL (Puerto $connectedPort).";
    
    if ($isCli) {
        echo "=== ÉCHELE CAMELLO - INSTALACIÓN BD (05_PATRONES) ===" . PHP_EOL;
        echo "[OK] Conectado a MySQL en {$connectedHost}:{$connectedPort}" . PHP_EOL;
        echo "[OK] Base de datos y tablas de arquitectura creadas." . PHP_EOL;
        echo "[OK] Usuarios y Roles: {$usersCount}" . PHP_EOL;
        echo "[OK] Solicitudes (State Pattern): {$solicitudesCount}" . PHP_EOL;
        echo "[OK] Conversaciones & Chat: {$conversacionesCount}" . PHP_EOL;
        echo "[OK] Notificaciones: {$notificacionesCount}" . PHP_EOL;
        echo "[OK] Vacantes activas: {$jobsCount}" . PHP_EOL;
        echo "=====================================================" . PHP_EOL;
    } else {
        echo "<!DOCTYPE html><html lang='es'><head><meta charset='utf-8'><title>Échele Camello | BD Sincronizada</title>";
        echo "<link href='https://fonts.googleapis.com/css2?family=Outfit:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap' rel='stylesheet'>";
        echo "<style>body{font-family:'Plus Jakarta Sans',sans-serif;background:#FBF9F5;color:#2C2724;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;padding:20px;}</style></head><body>";
        echo "<div style='background:#ffffff;border:1px solid #E2ECE9;border-radius:24px;padding:36px;max-width:620px;width:100%;box-shadow:0 12px 36px rgba(107,144,128,0.08);'>";
        echo "<div style='display:inline-block;padding:6px 14px;background:#EAF4F4;color:#537A72;border-radius:99px;font-size:13px;font-weight:600;margin-bottom:12px;'>✦ Ecosistema Échele Camello ✦</div>";
        echo "<h2 style='font-family:Outfit,sans-serif;color:#2C2724;font-size:26px;margin:0 0 10px 0;'>Base de datos sincronizada con éxito</h2>";
        echo "<p style='color:#645D56;font-size:14px;margin-bottom:20px;'>Conexión establecida con MySQL en el puerto <strong>{$connectedPort}</strong> con arquitectura de patrones activa.</p>";
        echo "<div style='display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:24px;'>";
        echo "<div style='background:#F4F8F7;padding:12px 16px;border-radius:14px;border:1px solid #CCE3DE;'><div style='font-size:11px;color:#537A72;text-transform:uppercase;font-weight:700;'>Usuarios & Roles</div><div style='font-size:22px;font-weight:700;color:#2C2724;'>{$usersCount}</div></div>";
        echo "<div style='background:#FDF5F2;padding:12px 16px;border-radius:14px;border:1px solid #F9D6C9;'><div style='font-size:11px;color:#A25844;text-transform:uppercase;font-weight:700;'>Solicitudes (State)</div><div style='font-size:22px;font-weight:700;color:#2C2724;'>{$solicitudesCount}</div></div>";
        echo "<div style='background:#F2F4FB;padding:12px 16px;border-radius:14px;border:1px solid #DCD4F0;'><div style='font-size:11px;color:#655B8B;text-transform:uppercase;font-weight:700;'>Mensajería & Chat</div><div style='font-size:22px;font-weight:700;color:#2C2724;'>{$conversacionesCount}</div></div>";
        echo "<div style='background:#FEFAF0;padding:12px 16px;border-radius:14px;border:1px solid #FDF1B8;'><div style='font-size:11px;color:#8C7738;text-transform:uppercase;font-weight:700;'>Notificaciones</div><div style='font-size:22px;font-weight:700;color:#2C2724;'>{$notificacionesCount}</div></div>";
        echo "</div>";
        echo "<a href='index.php' style='display:block;text-align:center;padding:14px 24px;background:#6B9080;color:#ffffff;text-decoration:none;border-radius:16px;font-weight:700;font-size:15px;box-shadow:0 4px 14px rgba(107,144,128,0.3);'>Explorar Portal Échele Camello</a>";
        echo "</div></body></html>";
    }
} catch (Exception $e) {
    $err = "Error al ejecutar migración SQL: " . $e->getMessage();
    if ($isCli) {
        fwrite(STDERR, $err . PHP_EOL);
        exit(1);
    } else {
        die("<h2 style='color:#ba1a1a;'>$err</h2>");
    }
}
