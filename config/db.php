<?php
/**
 * Conexión a Base de Datos MySQL (XAMPP)
 * Échele Camello - Portal de Empleo LATAM
 */

define('DB_NAME', 'echele_camello');
define('DB_USER', 'root');
define('DB_PASS', '');

// Función para obtener conexión PDO con soporte para puertos 3307 (XAMPP default en esta máquina) y 3306
function getDBConnection($selectDb = true) {
    $hosts = ['127.0.0.1', 'localhost'];
    $ports = [3307, 3306]; // Intenta primero el puerto 3307 configurado en este XAMPP, luego 3306
    
    $lastException = null;

    foreach ($ports as $port) {
        foreach ($hosts as $host) {
            try {
                $dsn = "mysql:host={$host};port={$port};charset=utf8mb4";
                if ($selectDb) {
                    $dsn .= ";dbname=" . DB_NAME;
                }
                
                $options = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                    PDO::ATTR_TIMEOUT            => 2,
                ];

                $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
                return $pdo;
            } catch (PDOException $e) {
                $lastException = $e;
            }
        }
    }

    // Si falló la conexión con la base de datos seleccionada, intentemos ver si el servidor MySQL está activo sin seleccionar BD
    if ($selectDb) {
        try {
            $pdoRaw = getDBConnection(false);
            if ($pdoRaw) {
                // El servidor MySQL está activo, pero la base de datos 'echele_camello' aún no existe.
                // Redirigir o auto-inicializar
                require_once __DIR__ . '/../setup_db.php';
                return getDBConnection(true);
            }
        } catch (Exception $e) {
            // Continuar al error general
        }
    }

    die('<div style="font-family:sans-serif;padding:30px;background:#fff1f0;border:1px solid #ffa39e;border-radius:12px;margin:20px;max-width:700px;color:#a8071a;">' .
        '<h2 style="margin-top:0;">⚠️ Error de conexión con MySQL (XAMPP)</h2>' .
        '<p>No se pudo conectar al servidor MySQL en <strong>localhost:3307</strong> o <strong>localhost:3306</strong>.</p>' .
        '<p><strong>Detalle:</strong> ' . htmlspecialchars($lastException ? $lastException->getMessage() : 'Desconocido') . '</p>' .
        '<hr style="border:none;border-top:1px solid #ffd8d6;margin:15px 0;">' .
        '<p><strong>Solución rápida en XAMPP:</strong></p>' .
        '<ol>' .
        '<li>Abra el <em>Panel de Control de XAMPP</em> (XAMPP Control Panel).</li>' .
        '<li>Asegúrese de que el módulo <strong>MySQL</strong> esté en estado <em>Running</em> (iniciado).</li>' .
        '<li>Si usa un puerto personalizado diferente a 3307/3306, ajústelo en <code>config/db.php</code>.</li>' .
        '</ol>' .
        '</div>');
}
