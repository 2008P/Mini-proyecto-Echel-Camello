<?php
/**
 * STATE PATTERN - Échele Camello
 * Conforme a 05_PATRONES_DISENO_ECHELE_CAMELLO.md (Sección 8)
 * Encapsula comportamiento y transiciones según el estado actual de la solicitud.
 * Protege el ciclo de vida e impide transiciones no permitidas mediante State GoF.
 */

namespace EcheleCamello\Patterns\State;

use Exception;
use EcheleCamello\Patterns\Repository\SolicitudRepository;
use EcheleCamello\Patterns\Observer\EventDispatcher;
use EcheleCamello\Patterns\Observer\SolicitudAceptadaEvent;
use EcheleCamello\Patterns\Observer\SolicitudRechazadaEvent;
use EcheleCamello\Patterns\Observer\SolicitudProgramadaEvent;
use EcheleCamello\Patterns\Observer\SolicitudIniciadaEvent;
use EcheleCamello\Patterns\Observer\SolicitudFinalizadaEvent;
use EcheleCamello\Patterns\Observer\SolicitudCanceladaEvent;

interface EstadoSolicitud {
    public function getNombre(): string;
    public function aceptar(SolicitudContext $context, string $actorId): void;
    public function rechazar(SolicitudContext $context, string $actorId, string $motivo): void;
    public function programar(SolicitudContext $context, string $actorId, string $fechaHora, float $valorAcordado): void;
    public function iniciar(SolicitudContext $context, string $actorId): void;
    public function finalizar(SolicitudContext $context, string $actorId): void;
    public function reprogramar(SolicitudContext $context, string $actorId, string $nuevaFechaHora): void;
    public function cancelar(SolicitudContext $context, string $actorId, string $motivo): void;
}

abstract class BaseEstadoSolicitud implements EstadoSolicitud {
    protected function errorTransicionInvalida(string $accion): void {
        throw new Exception("Operación no permitida: no se puede '{$accion}' cuando la solicitud está en estado {$this->getNombre()}.");
    }

    public function aceptar(SolicitudContext $context, string $actorId): void {
        $this->errorTransicionInvalida('aceptar');
    }

    public function rechazar(SolicitudContext $context, string $actorId, string $motivo): void {
        $this->errorTransicionInvalida('rechazar');
    }

    public function programar(SolicitudContext $context, string $actorId, string $fechaHora, float $valorAcordado): void {
        $this->errorTransicionInvalida('programar');
    }

    public function iniciar(SolicitudContext $context, string $actorId): void {
        $this->errorTransicionInvalida('iniciar');
    }

    public function finalizar(SolicitudContext $context, string $actorId): void {
        $this->errorTransicionInvalida('finalizar');
    }

    public function reprogramar(SolicitudContext $context, string $actorId, string $nuevaFechaHora): void {
        $this->errorTransicionInvalida('reprogramar');
    }

    public function cancelar(SolicitudContext $context, string $actorId, string $motivo): void {
        $this->errorTransicionInvalida('cancelar');
    }
}

class SolicitudPendiente extends BaseEstadoSolicitud {
    public function getNombre(): string { return 'PENDIENTE'; }

    public function aceptar(SolicitudContext $context, string $actorId): void {
        if ($context->getTrabajadorId() !== $actorId) {
            throw new Exception("Solo el trabajador asignado puede aceptar esta solicitud.");
        }
        $context->cambiarEstado(new SolicitudAceptada());
        $context->persistir();
        EventDispatcher::getInstance()->dispatch(new SolicitudAceptadaEvent($context->getId(), $context->getClienteId(), $context->getTrabajadorId(), $context->getTitulo()));
    }

    public function rechazar(SolicitudContext $context, string $actorId, string $motivo): void {
        if ($context->getTrabajadorId() !== $actorId) {
            throw new Exception("Solo el trabajador asignado puede rechazar esta solicitud.");
        }
        $context->setMotivoCancelacion($motivo ?: 'Rechazada por el trabajador.');
        $context->cambiarEstado(new SolicitudRechazada());
        $context->persistir();
        EventDispatcher::getInstance()->dispatch(new SolicitudRechazadaEvent($context->getId(), $context->getClienteId(), $context->getTrabajadorId(), $motivo));
    }

    public function cancelar(SolicitudContext $context, string $actorId, string $motivo): void {
        if ($context->getClienteId() !== $actorId && $context->getTrabajadorId() !== $actorId) {
            throw new Exception("No tienes autorización para cancelar esta solicitud.");
        }
        $context->setMotivoCancelacion($motivo ?: 'Cancelada por el solicitante.');
        $context->cambiarEstado(new SolicitudCancelada());
        $context->persistir();
        EventDispatcher::getInstance()->dispatch(new SolicitudCanceladaEvent($context->getId(), $context->getClienteId(), $context->getTrabajadorId(), $motivo));
    }
}

class SolicitudAceptada extends BaseEstadoSolicitud {
    public function getNombre(): string { return 'ACEPTADA'; }

    public function programar(SolicitudContext $context, string $actorId, string $fechaHora, float $valorAcordado): void {
        if ($context->getClienteId() !== $actorId && $context->getTrabajadorId() !== $actorId) {
            throw new Exception("Solo las partes involucradas pueden acordar la fecha de agendamiento.");
        }

        // Validación de fecha no en el pasado
        if (strtotime($fechaHora) < time() - 300) {
            throw new Exception("La fecha programada no puede ser en el pasado.");
        }

        // Validación de no conflicto horario en el repositorio
        if ($context->getRepository()->verificarConflictoHorario($context->getTrabajadorId(), $fechaHora, $context->getId())) {
            throw new Exception("El trabajador ya cuenta con un servicio programado en ese horario.");
        }

        $context->setFechaProgramada($fechaHora);
        $context->setValorAcordado($valorAcordado > 0 ? $valorAcordado : $context->getValorPropuesto());
        $context->cambiarEstado(new SolicitudProgramada());
        $context->persistir();
        EventDispatcher::getInstance()->dispatch(new SolicitudProgramadaEvent($context->getId(), $context->getClienteId(), $context->getTrabajadorId(), $fechaHora));
    }

    public function cancelar(SolicitudContext $context, string $actorId, string $motivo): void {
        $context->setMotivoCancelacion($motivo);
        $context->cambiarEstado(new SolicitudCancelada());
        $context->persistir();
        EventDispatcher::getInstance()->dispatch(new SolicitudCanceladaEvent($context->getId(), $context->getClienteId(), $context->getTrabajadorId(), $motivo));
    }
}

class SolicitudProgramada extends BaseEstadoSolicitud {
    public function getNombre(): string { return 'PROGRAMADA'; }

    public function iniciar(SolicitudContext $context, string $actorId): void {
        if ($context->getTrabajadorId() !== $actorId) {
            throw new Exception("Solo el trabajador puede dar inicio a la prestación del servicio.");
        }
        $context->cambiarEstado(new SolicitudEnProceso());
        $context->persistir();
        EventDispatcher::getInstance()->dispatch(new SolicitudIniciadaEvent($context->getId(), $context->getClienteId(), $context->getTrabajadorId()));
    }

    public function reprogramar(SolicitudContext $context, string $actorId, string $nuevaFechaHora): void {
        if (strtotime($nuevaFechaHora) < time() - 300) {
            throw new Exception("La nueva fecha programada no puede ser en el pasado.");
        }
        if ($context->getRepository()->verificarConflictoHorario($context->getTrabajadorId(), $nuevaFechaHora, $context->getId())) {
            throw new Exception("El trabajador tiene un compromiso existente en la nueva franja horaria.");
        }
        $context->setFechaProgramada($nuevaFechaHora);
        $context->persistir();
        EventDispatcher::getInstance()->dispatch(new SolicitudProgramadaEvent($context->getId(), $context->getClienteId(), $context->getTrabajadorId(), $nuevaFechaHora));
    }

    public function cancelar(SolicitudContext $context, string $actorId, string $motivo): void {
        $context->setMotivoCancelacion($motivo);
        $context->cambiarEstado(new SolicitudCancelada());
        $context->persistir();
        EventDispatcher::getInstance()->dispatch(new SolicitudCanceladaEvent($context->getId(), $context->getClienteId(), $context->getTrabajadorId(), $motivo));
    }
}

class SolicitudEnProceso extends BaseEstadoSolicitud {
    public function getNombre(): string { return 'EN_PROCESO'; }

    public function finalizar(SolicitudContext $context, string $actorId): void {
        // Puede finalizar el trabajador (al terminar) o el cliente (al confirmar recibido)
        if ($context->getTrabajadorId() !== $actorId && $context->getClienteId() !== $actorId) {
            throw new Exception("Solo las partes de la solicitud pueden finalizar el servicio.");
        }
        $context->cambiarEstado(new SolicitudFinalizada());
        $context->persistir();
        EventDispatcher::getInstance()->dispatch(new SolicitudFinalizadaEvent($context->getId(), $context->getClienteId(), $context->getTrabajadorId()));
    }

    public function cancelar(SolicitudContext $context, string $actorId, string $motivo): void {
        // En proceso requiere motivo explícito
        if (empty(trim($motivo))) {
            throw new Exception("Es obligatorio ingresar un motivo formal para cancelar un servicio en ejecución.");
        }
        $context->setMotivoCancelacion("Cancelado durante ejecución: " . $motivo);
        $context->cambiarEstado(new SolicitudCancelada());
        $context->persistir();
        EventDispatcher::getInstance()->dispatch(new SolicitudCanceladaEvent($context->getId(), $context->getClienteId(), $context->getTrabajadorId(), $motivo));
    }
}

class SolicitudFinalizada extends BaseEstadoSolicitud {
    public function getNombre(): string { return 'FINALIZADA'; }
    // Estado terminal de éxito: no admite cambios de estado adicionales
}

class SolicitudRechazada extends BaseEstadoSolicitud {
    public function getNombre(): string { return 'RECHAZADA'; }
    // Estado terminal: no admite transicionar a programada ni iniciada
}

class SolicitudCancelada extends BaseEstadoSolicitud {
    public function getNombre(): string { return 'CANCELADA'; }
    // Estado terminal
}

/**
 * CONTEXTO DE LA SOLICITUD (State Pattern)
 */
class SolicitudContext {
    private string $id;
    private string $clienteId;
    private string $trabajadorId;
    private ?string $servicioId;
    private string $titulo;
    private string $descripcion;
    private ?string $direccion;
    private float $valorPropuesto;
    private float $valorAcordado;
    private ?string $fechaProgramada = null;
    private ?string $motivoCancelacion = null;
    private EstadoSolicitud $estadoActual;
    private SolicitudRepository $repository;

    public function __construct(array $datos, SolicitudRepository $repository) {
        $this->repository = $repository;
        $this->id = $datos['id'];
        $this->clienteId = $datos['cliente_id'];
        $this->trabajadorId = $datos['trabajador_id'];
        $this->servicioId = $datos['servicio_id'] ?? null;
        $this->titulo = $datos['titulo'];
        $this->descripcion = $datos['descripcion'];
        $this->direccion = $datos['direccion'] ?? 'Remoto';
        $this->valorPropuesto = (float)($datos['valor_propuesto'] ?? 0);
        $this->valorAcordado = (float)($datos['valor_acordado'] ?? 0);
        $this->fechaProgramada = $datos['fecha_programada'] ?? null;
        $this->motivoCancelacion = $datos['motivo_cancelacion'] ?? null;

        $this->estadoActual = self::crearEstado($datos['estado'] ?? 'PENDIENTE');
    }

    public static function crearEstado(string $nombreEstado): EstadoSolicitud {
        switch (strtoupper(trim($nombreEstado))) {
            case 'PENDIENTE': return new SolicitudPendiente();
            case 'ACEPTADA': return new SolicitudAceptada();
            case 'RECHAZADA': return new SolicitudRechazada();
            case 'PROGRAMADA': return new SolicitudProgramada();
            case 'EN_PROCESO': return new SolicitudEnProceso();
            case 'FINALIZADA': return new SolicitudFinalizada();
            case 'CANCELADA': return new SolicitudCancelada();
            default: return new SolicitudPendiente();
        }
    }

    public function cambiarEstado(EstadoSolicitud $nuevoEstado): void {
        $this->estadoActual = $nuevoEstado;
    }

    public function persistir(): void {
        $this->repository->actualizarEstado(
            $this->id,
            $this->estadoActual->getNombre(),
            $this->motivoCancelacion,
            $this->fechaProgramada,
            $this->valorAcordado
        );
    }

    // Delegaciones a la clase Estado
    public function aceptar(string $actorId): void {
        $this->estadoActual->aceptar($this, $actorId);
    }

    public function rechazar(string $actorId, string $motivo): void {
        $this->estadoActual->rechazar($this, $actorId, $motivo);
    }

    public function programar(string $actorId, string $fechaHora, float $valorAcordado): void {
        $this->estadoActual->programar($this, $actorId, $fechaHora, $valorAcordado);
    }

    public function iniciar(string $actorId): void {
        $this->estadoActual->iniciar($this, $actorId);
    }

    public function finalizar(string $actorId): void {
        $this->estadoActual->finalizar($this, $actorId);
    }

    public function reprogramar(string $actorId, string $nuevaFechaHora): void {
        $this->estadoActual->reprogramar($this, $actorId, $nuevaFechaHora);
    }

    public function cancelar(string $actorId, string $motivo): void {
        $this->estadoActual->cancelar($this, $actorId, $motivo);
    }

    // Getters y Setters
    public function getId(): string { return $this->id; }
    public function getClienteId(): string { return $this->clienteId; }
    public function getTrabajadorId(): string { return $this->trabajadorId; }
    public function getTitulo(): string { return $this->titulo; }
    public function getEstado(): EstadoSolicitud { return $this->estadoActual; }
    public function getEstadoNombre(): string { return $this->estadoActual->getNombre(); }
    public function getRepository(): SolicitudRepository { return $this->repository; }
    public function getValorPropuesto(): float { return $this->valorPropuesto; }
    public function setValorAcordado(float $val): void { $this->valorAcordado = $val; }
    public function setFechaProgramada(?string $fecha): void { $this->fechaProgramada = $fecha; }
    public function setMotivoCancelacion(?string $motivo): void { $this->motivoCancelacion = $motivo; }
}
