<?php
/**
 * ADAPTER PATTERN - Échele Camello
 * Conforme a 05_PATRONES_DISENO_ECHELE_CAMELLO.md (Sección 11)
 * Aísla las dependencias tecnológicas externas (Servidores SMTP de correo y Almacenamiento local de archivos).
 */

namespace EcheleCamello\Patterns\Adapter;

use Exception;

/**
 * PUERTO DE CORREO (Contrato de Dominio)
 */
interface CorreoPort {
    public function enviar(string $destinatario, string $asunto, string $contenidoHtml): bool;
}

/**
 * ADAPTADOR DE CORREO SIMULADO / AUDITABLE (Para desarrollo y XAMPP local)
 * Registra los correos en sesión / logs permitiendo probar la recuperación de contraseña
 * y activación de cuenta sin depender de que un servidor SMTP externo esté encendido.
 */
class SimuladoCorreoAdapter implements CorreoPort {
    private static array $bandejaSalida = [];

    public function enviar(string $destinatario, string $asunto, string $contenidoHtml): bool {
        $envio = [
            'id' => uniqid('email_'),
            'destinatario' => $destinatario,
            'asunto' => $asunto,
            'contenido' => $contenidoHtml,
            'fecha' => date('Y-m-d H:i:s')
        ];

        self::$bandejaSalida[] = $envio;
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION['correos_simulados'][] = $envio;
        }

        // También registrar en archivo de log para auditoría
        $logDir = __DIR__ . '/../../logs';
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0777, true);
        }
        @file_put_contents(
            $logDir . '/emails_audit.log', 
            "[" . date('Y-m-d H:i:s') . "] TO: {$destinatario} | ASUNTO: {$asunto}\n", 
            FILE_APPEND
        );

        return true;
    }

    public static function getBandejaSalida(): array {
        if (session_status() === PHP_SESSION_ACTIVE && isset($_SESSION['correos_simulados'])) {
            return $_SESSION['correos_simulados'];
        }
        return self::$bandejaSalida;
    }
}

/**
 * ADAPTADOR DE CORREO SMTP NATIVO
 */
class SmtpCorreoAdapter implements CorreoPort {
    public function enviar(string $destinatario, string $asunto, string $contenidoHtml): bool {
        $headers = [
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=utf-8',
            'From: Échele Camello <no-reply@echelecamello.co>',
            'X-Mailer: PHP/' . phpversion()
        ];
        // Intentar envío por función mail nativa; si falla retorna falso sin romper la ejecución
        return @mail($destinatario, $asunto, $contenidoHtml, implode("\r\n", $headers));
    }
}

/**
 * PUERTO DE ALMACENAMIENTO DE ARCHIVOS (Contrato de Dominio)
 */
interface AlmacenamientoArchivosPort {
    public function guardar(array $archivo, string $carpeta = 'general'): array;
    public function obtenerUrl(string $nombreArchivo, string $carpeta = 'general'): string;
    public function eliminar(string $rutaRelativa): bool;
}

/**
 * ADAPTADOR DE ALMACENAMIENTO LOCAL EN DISCO (/uploads)
 */
class AlmacenamientoLocalAdapter implements AlmacenamientoArchivosPort {
    private string $uploadBasePath;
    private string $publicUrlPrefix;
    private array $allowedMimeTypes = [
        'image/jpeg', 'image/png', 'image/webp', 'image/gif',
        'application/pdf', 'text/plain'
    ];
    private int $maxSizeBytes = 5242880; // 5 MB

    public function __construct(?string $uploadBasePath = null, ?string $publicUrlPrefix = null) {
        $this->uploadBasePath = $uploadBasePath ?: (__DIR__ . '/../../uploads');
        $this->publicUrlPrefix = $publicUrlPrefix ?: 'uploads';

        if (!is_dir($this->uploadBasePath)) {
            @mkdir($this->uploadBasePath, 0777, true);
        }
    }

    public function guardar(array $archivo, string $carpeta = 'general'): array {
        if (!isset($archivo['error']) || is_array($archivo['error'])) {
            throw new Exception("Parámetros de archivo no válidos.");
        }

        if ($archivo['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Error al subir archivo (Código: {$archivo['error']}).");
        }

        if ($archivo['size'] > $this->maxSizeBytes) {
            throw new Exception("El archivo supera el tamaño máximo permitido (5 MB).");
        }

        // Verificación real de MIME type
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($archivo['tmp_name']);
        if (!in_array($mimeType, $this->allowedMimeTypes)) {
            throw new Exception("Formato de archivo no permitido ({$mimeType}). Solo imágenes, PDF y texto.");
        }

        // Directorio destino
        $targetDir = $this->uploadBasePath . DIRECTORY_SEPARATOR . $carpeta;
        if (!is_dir($targetDir)) {
            @mkdir($targetDir, 0777, true);
        }

        // Nombre de archivo sanitizado y único (Regla 05_PATRONES: evitar colisiones y path traversal)
        $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
        $safeName = sprintf('%s_%s.%s', bin2hex(random_bytes(8)), time(), strtolower($extension));
        $targetPath = $targetDir . DIRECTORY_SEPARATOR . $safeName;

        if (!move_uploaded_file($archivo['tmp_name'], $targetPath)) {
            throw new Exception("Error al persistir el archivo en el almacenamiento.");
        }

        $publicUrl = $this->publicUrlPrefix . '/' . $carpeta . '/' . $safeName;

        return [
            'nombre_guardado' => $safeName,
            'nombre_original' => htmlspecialchars($archivo['name']),
            'mime_type' => $mimeType,
            'tamano' => $archivo['size'],
            'url' => $publicUrl
        ];
    }

    public function obtenerUrl(string $nombreArchivo, string $carpeta = 'general'): string {
        return $this->publicUrlPrefix . '/' . $carpeta . '/' . $nombreArchivo;
    }

    public function eliminar(string $rutaRelativa): bool {
        $realPath = realpath($this->uploadBasePath . '/' . ltrim($rutaRelativa, '/'));
        if ($realPath && strpos($realPath, realpath($this->uploadBasePath)) === 0 && file_exists($realPath)) {
            return @unlink($realPath);
        }
        return false;
    }
}
