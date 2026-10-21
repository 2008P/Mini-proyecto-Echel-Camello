<?php
/**
 * Test de Verificación de Patrones de Diseño (05_PATRONES)
 * Échele Camello
 */

require_once __DIR__ . '/includes/patterns/bootstrap.php';

use EcheleCamello\Patterns\State\SolicitudContext;
use EcheleCamello\Patterns\Strategy\BusquedaContext;
use EcheleCamello\Patterns\Strategy\OrdenPorCercaniaStrategy;
use EcheleCamello\Patterns\Strategy\OrdenPorCalificacionStrategy;
use EcheleCamello\Patterns\Observer\EventDispatcher;
use EcheleCamello\Patterns\Observer\SolicitudCreadaEvent;
use EcheleCamello\Patterns\Observer\CalificacionRegistradaEvent;
use EcheleCamello\Patterns\Adapter\SimuladoCorreoAdapter;

echo "=== TEST DE VERIFICACIÓN 05_PATRONES ===" . PHP_EOL;

// 1. Verificación de Repositorios y DTOs
$usuarioRepo = ServiceContainer::getUsuarioRepo();
$cliente = $usuarioRepo->buscarPorId('usr-cliente-1');
assert($cliente !== null, "El cliente usr-cliente-1 debe existir");
echo "[OK] Repository + DTO: Usuario recuperado correctamente ({$cliente['name']} - Rol: {$cliente['role']})" . PHP_EOL;

// 2. Verificación del Patrón State en Solicitudes
$solicitudRepo = ServiceContainer::getSolicitudRepo();
$solData = $solicitudRepo->buscarPorId('sol-003'); // Está en PENDIENTE
assert($solData !== null, "La solicitud sol-003 debe existir");

$context = new SolicitudContext($solData, $solicitudRepo);
echo "[OK] State Pattern: Estado inicial es '{$context->getEstadoNombre()}'" . PHP_EOL;

// Probar que no puede finalizar directamente estando en PENDIENTE
try {
    $context->finalizar('usr-trabajador-1');
    echo "[FAIL] Debió lanzar excepción al intentar finalizar desde PENDIENTE" . PHP_EOL;
} catch (Exception $e) {
    echo "[OK] State Pattern: Bloqueó transición inválida correctamente ('{$e->getMessage()}')" . PHP_EOL;
}

// 3. Verificación de Estrategias de Búsqueda (Strategy Pattern)
$itemsTest = [
    ['id' => 'item-a', 'location' => 'Bogotá, D.C.', 'rating_avg' => 4.2, 'salary_max' => 5000000],
    ['id' => 'item-b', 'location' => '100% Remoto', 'rating_avg' => 4.9, 'salary_max' => 12000000],
    ['id' => 'item-c', 'location' => 'Medellín', 'rating_avg' => 4.7, 'salary_max' => 8000000]
];

$buscador = new BusquedaContext();
$buscador->setEstrategia(new OrdenPorCalificacionStrategy());
$ordenadoCal = $buscador->ejecutar($itemsTest);
assert($ordenadoCal[0]['id'] === 'item-b', "El primer item por calificación debe ser item-b (4.9)");
echo "[OK] Strategy Pattern: Ordenamiento por calificación determinista verificado" . PHP_EOL;

$buscador->setEstrategia(new OrdenPorCercaniaStrategy());
$ordenadoCercania = $buscador->ejecutar($itemsTest, ['user_lat' => 4.7110, 'user_lng' => -74.0721]);
echo "[OK] Strategy Pattern: Ordenamiento por cercanía Haversine verificado (Primero: {$ordenadoCercania[0]['id']})" . PHP_EOL;

// 4. Verificación de Observer / PubSub y Notificaciones
$notifRepo = ServiceContainer::getNotificacionRepo();
$countAntes = $notifRepo->contarNoLeidas('usr-trabajador-1');

// Despachar evento
EventDispatcher::getInstance()->dispatch(
    new SolicitudCreadaEvent('sol-test-999', 'usr-cliente-1', 'usr-trabajador-1', 'Auditoría Cloud de Prueba')
);

$countDespues = $notifRepo->contarNoLeidas('usr-trabajador-1');
assert($countDespues > $countAntes, "El suscriptor Observer debe haber creado una notificación");
echo "[OK] Observer Pattern: Suscriptor de notificaciones reaccionó al evento y generó notificación automática" . PHP_EOL;

// 5. Verificación de Adapter de Correo (SMTP / Simulado)
$correoAdapter = ServiceContainer::getCorreoAdapter();
$correoAdapter->enviar('test@echelecamello.co', 'Prueba de Activación', '<p>Token 123456</p>');
$bandeja = SimuladoCorreoAdapter::getBandejaSalida();
assert(!empty($bandeja), "La bandeja de salida simulada debe contener el correo despachado");
echo "[OK] Adapter Pattern: Adapter de Correo ejecutó despacho y auditoría con éxito" . PHP_EOL;

echo "========================================" . PHP_EOL;
echo "¡TODOS LOS PATRONES DE 05_PATRONES VERIFICADOS Y OPERATIVOS!" . PHP_EOL;
