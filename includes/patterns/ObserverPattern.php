<?php
/**
 * OBSERVER PATTERN - Échele Camello
 * Conforme a 05_PATRONES_DISENO_ECHELE_CAMELLO.md (Sección 10)
 * Desacopla las transiciones de negocio de las notificaciones, correos y recálculo de reputación.
 */

namespace EcheleCamello\Patterns\Observer;

use Exception;
use PDO;
use EcheleCamello\Patterns\Repository\NotificacionRepository;
use EcheleCamello\Patterns\Repository\CalificacionRepository;
use EcheleCamello\Patterns\Adapter\CorreoPort;

interface DomainEvent {
    public function getNombre(): string;
    public function getOcurridoEn(): string;
}

class SolicitudCreadaEvent implements DomainEvent {
    public string $solicitudId;
    public string $clienteId;
    public string $trabajadorId;
    public string $titulo;
    public string $ocurridoEn;

    public function __construct(string $solicitudId, string $clienteId, string $trabajadorId, string $titulo) {
        $this->solicitudId = $solicitudId;
        $this->clienteId = $clienteId;
        $this->trabajadorId = $trabajadorId;
        $this->titulo = $titulo;
        $this->ocurridoEn = date('Y-m-d H:i:s');
    }
    public function getNombre(): string { return 'SolicitudCreada'; }
    public function getOcurridoEn(): string { return $this->ocurridoEn; }
}

class SolicitudAceptadaEvent implements DomainEvent {
    public string $solicitudId;
    public string $clienteId;
    public string $trabajadorId;
    public string $titulo;
    public string $ocurridoEn;

    public function __construct(string $solicitudId, string $clienteId, string $trabajadorId, string $titulo) {
        $this->solicitudId = $solicitudId;
        $this->clienteId = $clienteId;
        $this->trabajadorId = $trabajadorId;
        $this->titulo = $titulo;
        $this->ocurridoEn = date('Y-m-d H:i:s');
    }
    public function getNombre(): string { return 'SolicitudAceptada'; }
    public function getOcurridoEn(): string { return $this->ocurridoEn; }
}

class SolicitudRechazadaEvent implements DomainEvent {
    public string $solicitudId;
    public string $clienteId;
    public string $trabajadorId;
    public string $motivo;
    public string $ocurridoEn;

    public function __construct(string $solicitudId, string $clienteId, string $trabajadorId, string $motivo) {
        $this->solicitudId = $solicitudId;
        $this->clienteId = $clienteId;
        $this->trabajadorId = $trabajadorId;
        $this->motivo = $motivo;
        $this->ocurridoEn = date('Y-m-d H:i:s');
    }
    public function getNombre(): string { return 'SolicitudRechazada'; }
    public function getOcurridoEn(): string { return $this->ocurridoEn; }
}

class SolicitudProgramadaEvent implements DomainEvent {
    public string $solicitudId;
    public string $clienteId;
    public string $trabajadorId;
    public string $fechaHora;
    public string $ocurridoEn;

    public function __construct(string $solicitudId, string $clienteId, string $trabajadorId, string $fechaHora) {
        $this->solicitudId = $solicitudId;
        $this->clienteId = $clienteId;
        $this->trabajadorId = $trabajadorId;
        $this->fechaHora = $fechaHora;
        $this->ocurridoEn = date('Y-m-d H:i:s');
    }
    public function getNombre(): string { return 'SolicitudProgramada'; }
    public function getOcurridoEn(): string { return $this->ocurridoEn; }
}

class SolicitudIniciadaEvent implements DomainEvent {
    public string $solicitudId;
    public string $clienteId;
    public string $trabajadorId;
    public string $ocurridoEn;

    public function __construct(string $solicitudId, string $clienteId, string $trabajadorId) {
        $this->solicitudId = $solicitudId;
        $this->clienteId = $clienteId;
        $this->trabajadorId = $trabajadorId;
        $this->ocurridoEn = date('Y-m-d H:i:s');
    }
    public function getNombre(): string { return 'SolicitudIniciada'; }
    public function getOcurridoEn(): string { return $this->ocurridoEn; }
}

class SolicitudFinalizadaEvent implements DomainEvent {
    public string $solicitudId;
    public string $clienteId;
    public string $trabajadorId;
    public string $ocurridoEn;

    public function __construct(string $solicitudId, string $clienteId, string $trabajadorId) {
        $this->solicitudId = $solicitudId;
        $this->clienteId = $clienteId;
        $this->trabajadorId = $trabajadorId;
        $this->ocurridoEn = date('Y-m-d H:i:s');
    }
    public function getNombre(): string { return 'SolicitudFinalizada'; }
    public function getOcurridoEn(): string { return $this->ocurridoEn; }
}

class SolicitudCanceladaEvent implements DomainEvent {
    public string $solicitudId;
    public string $clienteId;
    public string $trabajadorId;
    public string $motivo;
    public string $ocurridoEn;

    public function __construct(string $solicitudId, string $clienteId, string $trabajadorId, string $motivo) {
        $this->solicitudId = $solicitudId;
        $this->clienteId = $clienteId;
        $this->trabajadorId = $trabajadorId;
        $this->motivo = $motivo;
        $this->ocurridoEn = date('Y-m-d H:i:s');
    }
    public function getNombre(): string { return 'SolicitudCancelada'; }
    public function getOcurridoEn(): string { return $this->ocurridoEn; }
}

class CalificacionRegistradaEvent implements DomainEvent {
    public string $solicitudId;
    public string $trabajadorId;
    public int $puntuacion;
    public string $ocurridoEn;

    public function __construct(string $solicitudId, string $trabajadorId, int $puntuacion) {
        $this->solicitudId = $solicitudId;
        $this->trabajadorId = $trabajadorId;
        $this->puntuacion = $puntuacion;
        $this->ocurridoEn = date('Y-m-d H:i:s');
    }
    public function getNombre(): string { return 'CalificacionRegistrada'; }
    public function getOcurridoEn(): string { return $this->ocurridoEn; }
}

interface EventSubscriber {
    public function handle(DomainEvent $event): void;
}

/**
 * Suscriptor: Genera Notificaciones en el módulo de comunicación
 */
class NotificacionSubscriber implements EventSubscriber {
    private NotificacionRepository $repo;

    public function __construct(NotificacionRepository $repo) {
        $this->repo = $repo;
    }

    public function handle(DomainEvent $event): void {
        if ($event instanceof SolicitudCreadaEvent) {
            $this->repo->crear(
                $event->trabajadorId,
                'Nueva Solicitud Recibida',
                "Tienes una nueva solicitud de servicio: '{$event->titulo}'",
                'solicitud',
                "requests.php?id={$event->solicitudId}"
            );
        } elseif ($event instanceof SolicitudAceptadaEvent) {
            $this->repo->crear(
                $event->clienteId,
                'Solicitud Aceptada',
                "El trabajador ha aceptado tu solicitud '{$event->titulo}'. Ya pueden acordar la fecha.",
                'solicitud',
                "requests.php?id={$event->solicitudId}"
            );
        } elseif ($event instanceof SolicitudRechazadaEvent) {
            $this->repo->crear(
                $event->clienteId,
                'Solicitud Rechazada',
                "El trabajador no pudo aceptar la solicitud. Motivo: {$event->motivo}",
                'solicitud',
                "requests.php?id={$event->solicitudId}"
            );
        } elseif ($event instanceof SolicitudProgramadaEvent) {
            $fechaFmt = date('d/m/Y H:i', strtotime($event->fechaHora));
            $this->repo->crear(
                $event->clienteId,
                'Servicio Programado',
                "Tu servicio ha sido agendado para {$fechaFmt}.",
                'solicitud',
                "requests.php?id={$event->solicitudId}"
            );
            $this->repo->crear(
                $event->trabajadorId,
                'Compromiso en Agenda',
                "Tienes un camello programado para {$fechaFmt}.",
                'solicitud',
                "requests.php?id={$event->solicitudId}"
            );
        } elseif ($event instanceof SolicitudIniciadaEvent) {
            $this->repo->crear(
                $event->clienteId,
                'Camello en Ejecución',
                "El trabajador ha iniciado la prestación del servicio acordado.",
                'solicitud',
                "requests.php?id={$event->solicitudId}"
            );
        } elseif ($event instanceof SolicitudFinalizadaEvent) {
            $this->repo->crear(
                $event->clienteId,
                'Servicio Finalizado 🎉',
                "El camello fue completado. Ya puedes calificar la atención y calidad del trabajador.",
                'solicitud',
                "requests.php?id={$event->solicitudId}"
            );
        } elseif ($event instanceof SolicitudCanceladaEvent) {
            $this->repo->crear(
                $event->clienteId,
                'Solicitud Cancelada',
                "La solicitud fue cancelada. Motivo: {$event->motivo}",
                'solicitud',
                "requests.php?id={$event->solicitudId}"
            );
            $this->repo->crear(
                $event->trabajadorId,
                'Solicitud Cancelada',
                "La solicitud fue cancelada. Motivo: {$event->motivo}",
                'solicitud',
                "requests.php?id={$event->solicitudId}"
            );
        }
    }
}

/**
 * Suscriptor: Recalcula promedios de calificación atómicamente
 */
class ReputacionSubscriber implements EventSubscriber {
    private CalificacionRepository $calificacionRepo;

    public function __construct(CalificacionRepository $calificacionRepo) {
        $this->calificacionRepo = $calificacionRepo;
    }

    public function handle(DomainEvent $event): void {
        if ($event instanceof CalificacionRegistradaEvent) {
            $this->calificacionRepo->recalcularPromedio($event->trabajadorId);
        }
    }
}

/**
 * Event Dispatcher (Despachador de Eventos Observer / PubSub)
 */
class EventDispatcher {
    private static ?EventDispatcher $instance = null;
    /** @var array<string, EventSubscriber[]> */
    private array $subscribers = [];

    public static function getInstance(): EventDispatcher {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function subscribe(string $eventClass, EventSubscriber $subscriber): void {
        $this->subscribers[$eventClass][] = $subscriber;
    }

    public function dispatch(DomainEvent $event): void {
        $eventClass = get_class($event);
        if (isset($this->subscribers[$eventClass])) {
            foreach ($this->subscribers[$eventClass] as $subscriber) {
                try {
                    $subscriber->handle($event);
                } catch (Exception $e) {
                    error_log("Error en suscriptor de evento {$eventClass}: " . $e->getMessage());
                }
            }
        }
    }
}
