<?php
/**
 * BOOTSTRAP DE PATRONES Y ARQUITECTURA - Échele Camello
 * Inicializa y conecta DTO, Repository, State, Strategy, Observer y Adapter.
 * Conforme a 05_PATRONES_DISENO_ECHELE_CAMELLO.md
 */

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/DTOs.php';
require_once __DIR__ . '/Repositories.php';
require_once __DIR__ . '/ObserverPattern.php';
require_once __DIR__ . '/AdapterPattern.php';
require_once __DIR__ . '/StatePattern.php';
require_once __DIR__ . '/StrategyPattern.php';

use EcheleCamello\Patterns\Repository\UsuarioRepository;
use EcheleCamello\Patterns\Repository\SolicitudRepository;
use EcheleCamello\Patterns\Repository\ConversacionRepository;
use EcheleCamello\Patterns\Repository\MensajeRepository;
use EcheleCamello\Patterns\Repository\NotificacionRepository;
use EcheleCamello\Patterns\Repository\CalificacionRepository;
use EcheleCamello\Patterns\Repository\ReporteRepository;
use EcheleCamello\Patterns\Observer\EventDispatcher;
use EcheleCamello\Patterns\Observer\NotificacionSubscriber;
use EcheleCamello\Patterns\Observer\ReputacionSubscriber;
use EcheleCamello\Patterns\Observer\SolicitudCreadaEvent;
use EcheleCamello\Patterns\Observer\SolicitudAceptadaEvent;
use EcheleCamello\Patterns\Observer\SolicitudRechazadaEvent;
use EcheleCamello\Patterns\Observer\SolicitudProgramadaEvent;
use EcheleCamello\Patterns\Observer\SolicitudIniciadaEvent;
use EcheleCamello\Patterns\Observer\SolicitudFinalizadaEvent;
use EcheleCamello\Patterns\Observer\SolicitudCanceladaEvent;
use EcheleCamello\Patterns\Observer\CalificacionRegistradaEvent;
use EcheleCamello\Patterns\Adapter\SimuladoCorreoAdapter;
use EcheleCamello\Patterns\Adapter\AlmacenamientoLocalAdapter;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Contenedor de Servicios e Inyección de Dependencias
 * (Evita Singleton manual con getInstance en entidades de negocio)
 */
class ServiceContainer {
    private static ?PDO $pdo = null;
    private static ?UsuarioRepository $usuarioRepo = null;
    private static ?SolicitudRepository $solicitudRepo = null;
    private static ?ConversacionRepository $conversacionRepo = null;
    private static ?MensajeRepository $mensajeRepo = null;
    private static ?NotificacionRepository $notificacionRepo = null;
    private static ?CalificacionRepository $calificacionRepo = null;
    private static ?ReporteRepository $reporteRepo = null;
    private static ?SimuladoCorreoAdapter $correoAdapter = null;
    private static ?AlmacenamientoLocalAdapter $storageAdapter = null;
    private static bool $bootstrapped = false;

    public static function getPDO(): PDO {
        if (self::$pdo === null) {
            self::$pdo = getDBConnection();
        }
        return self::$pdo;
    }

    public static function getUsuarioRepo(): UsuarioRepository {
        if (self::$usuarioRepo === null) {
            self::$usuarioRepo = new UsuarioRepository(self::getPDO());
        }
        return self::$usuarioRepo;
    }

    public static function getSolicitudRepo(): SolicitudRepository {
        if (self::$solicitudRepo === null) {
            self::$solicitudRepo = new SolicitudRepository(self::getPDO());
        }
        return self::$solicitudRepo;
    }

    public static function getConversacionRepo(): ConversacionRepository {
        if (self::$conversacionRepo === null) {
            self::$conversacionRepo = new ConversacionRepository(self::getPDO());
        }
        return self::$conversacionRepo;
    }

    public static function getMensajeRepo(): MensajeRepository {
        if (self::$mensajeRepo === null) {
            self::$mensajeRepo = new MensajeRepository(self::getPDO());
        }
        return self::$mensajeRepo;
    }

    public static function getNotificacionRepo(): NotificacionRepository {
        if (self::$notificacionRepo === null) {
            self::$notificacionRepo = new NotificacionRepository(self::getPDO());
        }
        return self::$notificacionRepo;
    }

    public static function getCalificacionRepo(): CalificacionRepository {
        if (self::$calificacionRepo === null) {
            self::$calificacionRepo = new CalificacionRepository(self::getPDO());
        }
        return self::$calificacionRepo;
    }

    public static function getReporteRepo(): ReporteRepository {
        if (self::$reporteRepo === null) {
            self::$reporteRepo = new ReporteRepository(self::getPDO());
        }
        return self::$reporteRepo;
    }

    public static function getCorreoAdapter(): SimuladoCorreoAdapter {
        if (self::$correoAdapter === null) {
            self::$correoAdapter = new SimuladoCorreoAdapter();
        }
        return self::$correoAdapter;
    }

    public static function getStorageAdapter(): AlmacenamientoLocalAdapter {
        if (self::$storageAdapter === null) {
            self::$storageAdapter = new AlmacenamientoLocalAdapter();
        }
        return self::$storageAdapter;
    }

    public static function initEventSubscriptions(): void {
        if (self::$bootstrapped) return;
        self::$bootstrapped = true;

        $dispatcher = EventDispatcher::getInstance();
        $notifSub = new NotificacionSubscriber(self::getNotificacionRepo());
        $repSub = new ReputacionSubscriber(self::getCalificacionRepo());

        // Suscribir eventos a notificación
        $dispatcher->subscribe(SolicitudCreadaEvent::class, $notifSub);
        $dispatcher->subscribe(SolicitudAceptadaEvent::class, $notifSub);
        $dispatcher->subscribe(SolicitudRechazadaEvent::class, $notifSub);
        $dispatcher->subscribe(SolicitudProgramadaEvent::class, $notifSub);
        $dispatcher->subscribe(SolicitudIniciadaEvent::class, $notifSub);
        $dispatcher->subscribe(SolicitudFinalizadaEvent::class, $notifSub);
        $dispatcher->subscribe(SolicitudCanceladaEvent::class, $notifSub);

        // Suscribir recálculo de reputación
        $dispatcher->subscribe(CalificacionRegistradaEvent::class, $repSub);
    }
}

// Inicializar suscriptores de eventos del dominio
ServiceContainer::initEventSubscriptions();

/**
 * Funciones de Autenticación y Contexto de Sesión
 */
function getAuthenticatedUser(): ?array {
    if (!isset($_SESSION['user']) || !is_array($_SESSION['user'])) {
        // Usuario demo por defecto para facilitar pruebas fluidas si no ha iniciado sesión
        return [
            'id' => 'usr-cliente-1',
            'name' => 'Camila Ramos',
            'email' => 'camila.ramos@empresa.com',
            'role' => 'CLIENTE',
            'status' => 'ACTIVO',
            'avatar' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=300&q=80',
            'profession' => 'Gerente de Proyectos & Contratación'
        ];
    }
    return $_SESSION['user'];
}

function isAuthenticated(): bool {
    return isset($_SESSION['user']);
}

function getCurrentUserRole(): string {
    $u = getAuthenticatedUser();
    return $u ? $u['role'] : 'INVITADO';
}

function requireAuth(): void {
    if (!isset($_SESSION['user'])) {
        header('Location: login.php?msg=debes_iniciar_sesion');
        exit;
    }
}
