<?php
/**
 * DTOs (Data Transfer Objects) - Échele Camello
 * Conforme a 05_PATRONES_DISENO_ECHELE_CAMELLO.md (Sección 6)
 * Aísla entradas y salidas de la API, previene sobreexposición de secretos y escalado de privilegios.
 */

namespace EcheleCamello\Patterns\DTO;

class RegisterRequestDTO {
    public string $name;
    public string $email;
    public string $password;
    public string $role;
    public ?string $phone;
    public ?string $location;
    public ?string $profession;

    public function __construct(array $data) {
        $this->name = trim($data['name'] ?? '');
        $this->email = strtolower(trim($data['email'] ?? ''));
        $this->password = $data['password'] ?? '';
        
        // Regla 05_PATRONES: El registro público NO permite auto-asignarse ADMINISTRADOR
        $requestedRole = strtoupper(trim($data['role'] ?? 'CLIENTE'));
        if ($requestedRole === 'ADMINISTRADOR') {
            $requestedRole = 'CLIENTE';
        }
        $this->role = in_array($requestedRole, ['CLIENTE', 'TRABAJADOR']) ? $requestedRole : 'CLIENTE';

        $this->phone = !empty($data['phone']) ? trim($data['phone']) : null;
        $this->location = !empty($data['location']) ? trim($data['location']) : 'Colombia';
        $this->profession = !empty($data['profession']) ? trim($data['profession']) : null;
    }

    public function validate(): array {
        $errors = [];
        if (strlen($this->name) < 3) $errors[] = 'El nombre completo debe tener al menos 3 caracteres.';
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) $errors[] = 'El correo electrónico no es válido.';
        if (strlen($this->password) < 6) $errors[] = 'La contraseña debe tener mínimo 6 caracteres.';
        return $errors;
    }
}

class LoginRequestDTO {
    public string $email;
    public string $password;
    public bool $remember;

    public function __construct(array $data) {
        $this->email = strtolower(trim($data['email'] ?? ''));
        $this->password = $data['password'] ?? '';
        $this->remember = !empty($data['remember']);
    }

    public function validate(): array {
        $errors = [];
        if (empty($this->email)) $errors[] = 'El correo electrónico es requerido.';
        if (empty($this->password)) $errors[] = 'La contraseña es requerida.';
        return $errors;
    }
}

class AuthResponseDTO {
    public string $id;
    public string $name;
    public string $email;
    public string $role;
    public string $status;
    public ?string $avatar;
    public ?string $profession;
    public float $ratingAvg;

    public function __construct(array $user) {
        $this->id = $user['id'];
        $this->name = $user['name'];
        $this->email = $user['email'];
        $this->role = $user['role'];
        $this->status = $user['status'];
        $this->avatar = $user['avatar'] ?? null;
        $this->profession = $user['profession'] ?? null;
        $this->ratingAvg = (float)($user['rating_avg'] ?? 5.0);
        // Regla 05_PATRONES: Nunca exponer password_hash, tokens o secretos en respuestas
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'status' => $this->status,
            'avatar' => $this->avatar,
            'profession' => $this->profession,
            'rating_avg' => $this->ratingAvg
        ];
    }
}

class CrearSolicitudDTO {
    public string $clienteId;
    public string $trabajadorId;
    public ?string $servicioId;
    public string $titulo;
    public string $descripcion;
    public ?string $direccion;
    public float $valorPropuesto;

    public function __construct(array $data, string $actorId) {
        $this->clienteId = $actorId;
        $this->trabajadorId = trim($data['trabajador_id'] ?? '');
        $this->servicioId = !empty($data['servicio_id']) ? trim($data['servicio_id']) : null;
        $this->titulo = trim($data['titulo'] ?? '');
        $this->descripcion = trim($data['descripcion'] ?? '');
        $this->direccion = !empty($data['direccion']) ? trim($data['direccion']) : 'Remoto / Por coordinar';
        $this->valorPropuesto = (float)($data['valor_propuesto'] ?? 0);
    }

    public function validate(): array {
        $errors = [];
        if (empty($this->trabajadorId)) $errors[] = 'Debe seleccionar un trabajador destinatario.';
        if (strlen($this->titulo) < 5) $errors[] = 'El título de la solicitud debe ser más descriptivo (mínimo 5 caracteres).';
        if (strlen($this->descripcion) < 10) $errors[] = 'La descripción del trabajo debe tener al menos 10 caracteres.';
        return $errors;
    }
}

class EnviarMensajeDTO {
    public string $conversacionId;
    public string $remitenteId;
    public string $contenido;
    public ?string $adjuntoUrl;
    public ?string $adjuntoTipo;

    public function __construct(array $data, string $actorId) {
        $this->conversacionId = trim($data['conversacion_id'] ?? '');
        $this->remitenteId = $actorId;
        $this->contenido = trim($data['contenido'] ?? '');
        $this->adjuntoUrl = !empty($data['adjunto_url']) ? trim($data['adjunto_url']) : null;
        $this->adjuntoTipo = !empty($data['adjunto_tipo']) ? trim($data['adjunto_tipo']) : null;
    }

    public function validate(): array {
        $errors = [];
        if (empty($this->conversacionId)) $errors[] = 'ID de conversación no especificado.';
        if (empty($this->contenido) && empty($this->adjuntoUrl)) $errors[] = 'El mensaje no puede estar vacío.';
        return $errors;
    }
}

class CalificarServicioDTO {
    public string $solicitudId;
    public string $clienteId;
    public int $puntuacion;
    public ?string $comentario;

    public function __construct(array $data, string $actorId) {
        $this->solicitudId = trim($data['solicitud_id'] ?? '');
        $this->clienteId = $actorId;
        $this->puntuacion = (int)($data['puntuacion'] ?? 5);
        $this->comentario = !empty($data['comentario']) ? trim($data['comentario']) : null;
    }

    public function validate(): array {
        $errors = [];
        if (empty($this->solicitudId)) $errors[] = 'ID de solicitud requerido.';
        if ($this->puntuacion < 1 || $this->puntuacion > 5) $errors[] = 'La calificación debe estar entre 1 y 5 estrellas.';
        return $errors;
    }
}
