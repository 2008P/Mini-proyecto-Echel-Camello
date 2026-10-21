<?php
/**
 * REPOSITORY PATTERN - Échele Camello
 * Conforme a 05_PATRONES_DISENO_ECHELE_CAMELLO.md (Sección 5)
 * Encapsula el acceso a datos del dominio propietario.
 * No hay Singleton manual: recibe la conexión PDO por constructor (Inyección de dependencias).
 */

namespace EcheleCamello\Patterns\Repository;

use PDO;
use Exception;

class UsuarioRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function buscarPorId(string $id): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function buscarPorEmail(string $email): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE LOWER(email) = LOWER(?)");
        $stmt->execute([$email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function buscarPorTokenVerificacion(string $token): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE verification_token = ?");
        $stmt->execute([$token]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function buscarPorTokenRecuperacion(string $token): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE recovery_token = ?");
        $stmt->execute([$token]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function registrar(array $datos): string {
        $id = 'usr-' . bin2hex(random_bytes(6));
        $stmt = $this->pdo->prepare("
            INSERT INTO users (id, name, email, password_hash, role, status, phone, location, profession, verification_token)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $id,
            $datos['name'],
            $datos['email'],
            password_hash($datos['password'], PASSWORD_BCRYPT),
            $datos['role'],
            $datos['status'] ?? 'ACTIVO',
            $datos['phone'] ?? null,
            $datos['location'] ?? 'Colombia',
            $datos['profession'] ?? null,
            $datos['verification_token'] ?? null
        ]);
        return $id;
    }

    public function activarCuenta(string $id): bool {
        $stmt = $this->pdo->prepare("UPDATE users SET status = 'ACTIVO', verification_token = NULL WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function guardarTokenRecuperacion(string $id, string $token): bool {
        $stmt = $this->pdo->prepare("UPDATE users SET recovery_token = ? WHERE id = ?");
        return $stmt->execute([$token, $id]);
    }

    public function actualizarPassword(string $id, string $nuevaPassword): bool {
        $hash = password_hash($nuevaPassword, PASSWORD_BCRYPT);
        $stmt = $this->pdo->prepare("UPDATE users SET password_hash = ?, recovery_token = NULL WHERE id = ?");
        return $stmt->execute([$hash, $id]);
    }

    public function listarTrabajadores(int $limit = 20): array {
        $stmt = $this->pdo->prepare("SELECT id, name, email, role, avatar, profession, location, rating_avg, rating_count FROM users WHERE role = 'TRABAJADOR' LIMIT ?");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

class SolicitudRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function buscarPorId(string $id): ?array {
        $stmt = $this->pdo->prepare("
            SELECT s.*, 
                   c.name AS cliente_nombre, c.avatar AS cliente_avatar, c.email AS cliente_email,
                   t.name AS trabajador_nombre, t.avatar AS trabajador_avatar, t.profession AS trabajador_profesion
            FROM solicitudes s
            LEFT JOIN users c ON s.cliente_id = c.id
            LEFT JOIN users t ON s.trabajador_id = t.id
            WHERE s.id = ?
        ");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function listarPorUsuario(string $usuarioId, ?string $filtroEstado = null): array {
        $sql = "
            SELECT s.*, 
                   c.name AS cliente_nombre, c.avatar AS cliente_avatar,
                   t.name AS trabajador_nombre, t.avatar AS trabajador_avatar, t.profession AS trabajador_profesion
            FROM solicitudes s
            LEFT JOIN users c ON s.cliente_id = c.id
            LEFT JOIN users t ON s.trabajador_id = t.id
            WHERE (s.cliente_id = ? OR s.trabajador_id = ?)
        ";
        $params = [$usuarioId, $usuarioId];

        if ($filtroEstado && $filtroEstado !== 'todas') {
            $sql .= " AND s.estado = ?";
            $params[] = strtoupper($filtroEstado);
        }
        $sql .= " ORDER BY s.created_at DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crear(array $datos): string {
        $id = 'sol-' . bin2hex(random_bytes(5));
        $stmt = $this->pdo->prepare("
            INSERT INTO solicitudes (id, cliente_id, trabajador_id, servicio_id, titulo, descripcion, estado, direccion, valor_propuesto)
            VALUES (?, ?, ?, ?, ?, ?, 'PENDIENTE', ?, ?)
        ");
        $stmt->execute([
            $id,
            $datos['cliente_id'],
            $datos['trabajador_id'],
            $datos['servicio_id'] ?? null,
            $datos['titulo'],
            $datos['descripcion'],
            $datos['direccion'] ?? 'Remoto',
            $datos['valor_propuesto'] ?? 0
        ]);
        return $id;
    }

    public function actualizarEstado(string $id, string $nuevoEstado, ?string $motivo = null, ?string $fechaProgramada = null, ?float $valorAcordado = null): bool {
        $sql = "UPDATE solicitudes SET estado = ?";
        $params = [$nuevoEstado];

        if ($motivo !== null) {
            $sql .= ", motivo_cancelacion = ?";
            $params[] = $motivo;
        }
        if ($fechaProgramada !== null) {
            $sql .= ", fecha_programada = ?";
            $params[] = $fechaProgramada;
        }
        if ($valorAcordado !== null) {
            $sql .= ", valor_acordado = ?";
            $params[] = $valorAcordado;
        }

        $sql .= " WHERE id = ?";
        $params[] = $id;

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function verificarConflictoHorario(string $trabajadorId, string $fechaHora, string $excludeSolicitudId = ''): bool {
        // Validación de no solapamiento (+/- 1 hora)
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM solicitudes 
            WHERE trabajador_id = ? 
              AND estado IN ('PROGRAMADA', 'EN_PROCESO')
              AND id != ?
              AND fecha_programada BETWEEN DATE_SUB(?, INTERVAL 59 MINUTE) AND DATE_ADD(?, INTERVAL 59 MINUTE)
        ");
        $stmt->execute([$trabajadorId, $excludeSolicitudId, $fechaHora, $fechaHora]);
        return (int)$stmt->fetchColumn() > 0;
    }
}

class ConversacionRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerOcrear(string $clienteId, string $trabajadorId, ?string $solicitudId = null): string {
        $stmt = $this->pdo->prepare("
            SELECT id FROM conversaciones 
            WHERE (cliente_id = ? AND trabajador_id = ?) 
               OR (cliente_id = ? AND trabajador_id = ?)
            LIMIT 1
        ");
        $stmt->execute([$clienteId, $trabajadorId, $trabajadorId, $clienteId]);
        $existente = $stmt->fetchColumn();
        if ($existente) {
            return $existente;
        }

        $id = 'conv-' . bin2hex(random_bytes(5));
        $stmt = $this->pdo->prepare("
            INSERT INTO conversaciones (id, cliente_id, trabajador_id, solicitud_id, ultimo_mensaje)
            VALUES (?, ?, ?, ?, 'Conversación iniciada')
        ");
        $stmt->execute([$id, $clienteId, $trabajadorId, $solicitudId]);
        return $id;
    }

    public function listarPorUsuario(string $usuarioId): array {
        $stmt = $this->pdo->prepare("
            SELECT c.*,
                   u1.name AS cliente_nombre, u1.avatar AS cliente_avatar,
                   u2.name AS trabajador_nombre, u2.avatar AS trabajador_avatar,
                   (SELECT COUNT(*) FROM mensajes m WHERE m.conversacion_id = c.id AND m.remitente_id != ? AND m.leido = 0) AS no_leidos
            FROM conversaciones c
            JOIN users u1 ON c.cliente_id = u1.id
            JOIN users u2 ON c.trabajador_id = u2.id
            WHERE c.cliente_id = ? OR c.trabajador_id = ?
            ORDER BY c.ultimo_mensaje_at DESC
        ");
        $stmt->execute([$usuarioId, $usuarioId, $usuarioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId(string $id): ?array {
        $stmt = $this->pdo->prepare("
            SELECT c.*,
                   u1.name AS cliente_nombre, u1.avatar AS cliente_avatar,
                   u2.name AS trabajador_nombre, u2.avatar AS trabajador_avatar
            FROM conversaciones c
            JOIN users u1 ON c.cliente_id = u1.id
            JOIN users u2 ON c.trabajador_id = u2.id
            WHERE c.id = ?
        ");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }
}

class MensajeRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function guardar(array $datos): string {
        $id = 'msg-' . bin2hex(random_bytes(5));
        $stmt = $this->pdo->prepare("
            INSERT INTO mensajes (id, conversacion_id, remitente_id, contenido, adjunto_url, adjunto_tipo, leido)
            VALUES (?, ?, ?, ?, ?, ?, 0)
        ");
        $stmt->execute([
            $id,
            $datos['conversacion_id'],
            $datos['remitente_id'],
            $datos['contenido'],
            $datos['adjunto_url'] ?? null,
            $datos['adjunto_tipo'] ?? null
        ]);

        // Actualizar último mensaje de la conversación
        $upd = $this->pdo->prepare("UPDATE conversaciones SET ultimo_mensaje = ?, ultimo_mensaje_at = NOW() WHERE id = ?");
        $snippet = mb_strimwidth($datos['contenido'] ?: 'Archivo adjunto', 0, 90, '...');
        $upd->execute([$snippet, $datos['conversacion_id']]);

        return $id;
    }

    public function listarPorConversacion(string $conversacionId): array {
        $stmt = $this->pdo->prepare("
            SELECT m.*, u.name AS remitente_nombre, u.avatar AS remitente_avatar
            FROM mensajes m
            JOIN users u ON m.remitente_id = u.id
            WHERE m.conversacion_id = ?
            ORDER BY m.created_at ASC
        ");
        $stmt->execute([$conversacionId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function marcarComoLeidos(string $conversacionId, string $lectorId): void {
        $stmt = $this->pdo->prepare("UPDATE mensajes SET leido = 1 WHERE conversacion_id = ? AND remitente_id != ?");
        $stmt->execute([$conversacionId, $lectorId]);
    }
}

class NotificacionRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function crear(string $usuarioId, string $titulo, string $mensaje, string $tipo = 'sistema', ?string $enlace = null): string {
        $id = 'notif-' . bin2hex(random_bytes(5));
        $stmt = $this->pdo->prepare("
            INSERT INTO notificaciones (id, usuario_id, titulo, mensaje, tipo, enlace, leida)
            VALUES (?, ?, ?, ?, ?, ?, 0)
        ");
        $stmt->execute([$id, $usuarioId, $titulo, $mensaje, $tipo, $enlace]);
        return $id;
    }

    public function listarPorUsuario(string $usuarioId, int $limit = 30): array {
        $stmt = $this->pdo->prepare("
            SELECT * FROM notificaciones 
            WHERE usuario_id = ? 
            ORDER BY created_at DESC 
            LIMIT ?
        ");
        $stmt->bindValue(1, $usuarioId);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarNoLeidas(string $usuarioId): int {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM notificaciones WHERE usuario_id = ? AND leida = 0");
        $stmt->execute([$usuarioId]);
        return (int)$stmt->fetchColumn();
    }

    public function marcarComoLeida(string $id, string $usuarioId): bool {
        $stmt = $this->pdo->prepare("UPDATE notificaciones SET leida = 1 WHERE id = ? AND usuario_id = ?");
        return $stmt->execute([$id, $usuarioId]);
    }

    public function marcarTodasLeidas(string $usuarioId): bool {
        $stmt = $this->pdo->prepare("UPDATE notificaciones SET leida = 1 WHERE usuario_id = ?");
        return $stmt->execute([$usuarioId]);
    }
}

class CalificacionRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function registrar(string $solicitudId, string $clienteId, string $trabajadorId, int $puntuacion, ?string $comentario = null): string {
        $id = 'cal-' . bin2hex(random_bytes(5));
        $stmt = $this->pdo->prepare("
            INSERT INTO calificaciones (id, solicitud_id, cliente_id, trabajador_id, puntuacion, comentario)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$id, $solicitudId, $clienteId, $trabajadorId, $puntuacion, $comentario]);
        return $id;
    }

    public function recalcularPromedio(string $trabajadorId): array {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) AS total, AVG(puntuacion) AS promedio FROM calificaciones WHERE trabajador_id = ?");
        $stmt->execute([$trabajadorId]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        $total = (int)($res['total'] ?? 0);
        $promedio = round((float)($res['promedio'] ?? 5.0), 2);

        $upd = $this->pdo->prepare("UPDATE users SET rating_avg = ?, rating_count = ? WHERE id = ?");
        $upd->execute([$promedio, $total, $trabajadorId]);

        return ['promedio' => $promedio, 'total' => $total];
    }

    public function yaCalificado(string $solicitudId): bool {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM calificaciones WHERE solicitud_id = ?");
        $stmt->execute([$solicitudId]);
        return (int)$stmt->fetchColumn() > 0;
    }
}

class ReporteRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function crear(string $reportanteId, string $tipoEntidad, string $entidadId, string $motivo): string {
        $id = 'rep-' . bin2hex(random_bytes(5));
        $stmt = $this->pdo->prepare("
            INSERT INTO reportes (id, reportante_id, tipo_entidad, entidad_id, motivo, estado)
            VALUES (?, ?, ?, ?, ?, 'ABIERTO')
        ");
        $stmt->execute([$id, $reportanteId, $tipoEntidad, $entidadId, $motivo]);
        return $id;
    }

    public function resolver(string $reporteId, string $moderadorId, string $estado, string $resolucion): bool {
        $stmt = $this->pdo->prepare("
            UPDATE reportes 
            SET estado = ?, resolucion = ?, moderador_id = ? 
            WHERE id = ?
        ");
        return $stmt->execute([$estado, $resolucion, $moderadorId, $reporteId]);
    }
}
