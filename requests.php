<?php
/**
 * Gestor de Solicitudes y Agendamiento - Échele Camello
 * Conforme a 05_PATRONES_DISENO_ECHELE_CAMELLO.md (Sección 8 - State Pattern)
 * Implementa el ciclo de vida de solicitudes con Estado GoF y emisión de eventos Observer.
 */

require_once __DIR__ . '/includes/patterns/bootstrap.php';

use EcheleCamello\Patterns\State\SolicitudContext;
use EcheleCamello\Patterns\DTO\CrearSolicitudDTO;
use EcheleCamello\Patterns\DTO\CalificarServicioDTO;
use EcheleCamello\Patterns\Observer\EventDispatcher;
use EcheleCamello\Patterns\Observer\CalificacionRegistradaEvent;
use EcheleCamello\Patterns\Observer\SolicitudCreadaEvent;

$pageTitle = 'Gestor de Solicitudes';
$authUser = getAuthenticatedUser();
$solicitudRepo = ServiceContainer::getSolicitudRepo();
$usuarioRepo = ServiceContainer::getUsuarioRepo();
$calificacionRepo = ServiceContainer::getCalificacionRepo();

$error = '';
$success = '';

// Manejo de Acciones de Transición de Estado (State Pattern)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $authUser) {
    $action = $_POST['action'] ?? '';
    $solicitudId = $_POST['solicitud_id'] ?? '';

    try {
        if ($action === 'crear_solicitud') {
            $dto = new CrearSolicitudDTO($_POST, $authUser['id']);
            $errs = $dto->validate();
            if (!empty($errs)) {
                $error = implode(' ', $errs);
            } else {
                $nuevoId = $solicitudRepo->crear([
                    'cliente_id' => $dto->clienteId,
                    'trabajador_id' => $dto->trabajadorId,
                    'servicio_id' => $dto->servicioId,
                    'titulo' => $dto->titulo,
                    'descripcion' => $dto->descripcion,
                    'direccion' => $dto->direccion,
                    'valor_propuesto' => $dto->valorPropuesto
                ]);
                // Emitir evento SolicitudCreada
                EventDispatcher::getInstance()->dispatch(new SolicitudCreadaEvent($nuevoId, $dto->clienteId, $dto->trabajadorId, $dto->titulo));
                $success = "¡Solicitud '{$dto->titulo}' creada exitosamente en estado PENDIENTE!";
            }
        } elseif (!empty($solicitudId)) {
            $solData = $solicitudRepo->buscarPorId($solicitudId);
            if (!$solData) {
                throw new Exception("Solicitud no encontrada.");
            }

            // Instanciar Contexto de la Solicitud (Patrón State)
            $context = new SolicitudContext($solData, $solicitudRepo);

            switch ($action) {
                case 'aceptar':
                    $context->aceptar($authUser['id']);
                    $success = "La solicitud ha pasado a estado ACEPTADA.";
                    break;

                case 'rechazar':
                    $motivo = trim($_POST['motivo'] ?? 'No disponible en el momento');
                    $context->rechazar($authUser['id'], $motivo);
                    $success = "La solicitud ha sido RECHAZADA.";
                    break;

                case 'programar':
                    $fecha = $_POST['fecha_programada'] ?? '';
                    $valor = (float)($_POST['valor_acordado'] ?? 0);
                    if (empty($fecha)) {
                        throw new Exception("Debe seleccionar una fecha y hora de agendamiento.");
                    }
                    $context->programar($authUser['id'], $fecha, $valor);
                    $success = "Solicitud agendada con éxito en estado PROGRAMADA.";
                    break;

                case 'iniciar':
                    $context->iniciar($authUser['id']);
                    $success = "Servicio iniciado. Estado actual: EN_PROCESO.";
                    break;

                case 'finalizar':
                    $context->finalizar($authUser['id']);
                    $success = "¡Servicio completado con éxito! Estado actual: FINALIZADA.";
                    break;

                case 'reprogramar':
                    $nuevaFecha = $_POST['nueva_fecha'] ?? '';
                    if (empty($nuevaFecha)) {
                        throw new Exception("Ingrese la nueva fecha y hora.");
                    }
                    $context->reprogramar($authUser['id'], $nuevaFecha);
                    $success = "Fecha reprogramada correctamente.";
                    break;

                case 'cancelar':
                    $motivo = trim($_POST['motivo_cancelacion'] ?? 'Cancelación solicitada por usuario');
                    $context->cancelar($authUser['id'], $motivo);
                    $success = "La solicitud ha sido CANCELADA.";
                    break;

                case 'calificar':
                    $dtoCal = new CalificarServicioDTO($_POST, $authUser['id']);
                    $errs = $dtoCal->validate();
                    if (!empty($errs)) {
                        $error = implode(' ', $errs);
                    } else {
                        if ($calificacionRepo->yaCalificado($solicitudId)) {
                            throw new Exception("Esta solicitud ya ha sido calificada.");
                        }
                        $calificacionRepo->registrar(
                            $solicitudId,
                            $authUser['id'],
                            $solData['trabajador_id'],
                            $dtoCal->puntuacion,
                            $dtoCal->comentario
                        );
                        // Emitir evento Observer para recalcular reputación
                        EventDispatcher::getInstance()->dispatch(
                            new CalificacionRegistradaEvent($solicitudId, $solData['trabajador_id'], $dtoCal->puntuacion)
                        );
                        $success = "¡Gracias por calificar! Se ha recalculado el promedio de reputación del camellador.";
                    }
                    break;
            }
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Filtro de estado
$filtroEstado = $_GET['estado'] ?? 'todas';
$solicitudes = $authUser ? $solicitudRepo->listarPorUsuario($authUser['id'], $filtroEstado) : [];
$trabajadoresDisponibles = $usuarioRepo->listarTrabajadores(15);

require_once __DIR__ . '/includes/header.php';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 font-body">

  <!-- ENCABEZADO PRINCIPAL -->
  <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 pb-6 border-b border-[#E8E2D8]">
    <div>
      <div class="flex items-center gap-2 mb-1.5">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#EAF4F4] text-[#537A72] border border-[#CCE3DE]">
          <span class="w-2 h-2 rounded-full bg-[#6B9080]"></span>
          Patrón de Diseño State Activo
        </span>
        <span class="text-xs text-[#8C847C]">Ciclo: PENDIENTE → ACEPTADA → PROGRAMADA → EN PROCESO → FINALIZADA</span>
      </div>
      <h1 class="text-3xl font-bold font-headline text-[#2C2724]">Gestor de Solicitudes & Contratos</h1>
      <p class="text-sm text-[#7A726A] mt-1">
        Administra tus acuerdos laborales y servicios solicitados con protección de transiciones de estado.
      </p>
    </div>

    <!-- Botón Crear Solicitud -->
    <div>
      <button 
        onclick="document.getElementById('modal-crear-solicitud').classList.remove('hidden')" 
        class="py-2.5 px-5 rounded-xl bg-[#6B9080] hover:bg-[#5A7C6E] text-white font-bold text-xs shadow-sm transition-all flex items-center gap-2"
      >
        <span class="material-symbols-outlined text-[18px]">add_task</span>
        <span>Nueva Solicitud de Servicio</span>
      </button>
    </div>
  </div>

  <!-- ALERTAS -->
  <?php if ($error): ?>
    <div class="mb-6 p-4 rounded-2xl bg-[#FFF0F0] border border-[#F5C6CB] text-[#B94A48] text-xs font-semibold flex items-center gap-2">
      <span class="material-symbols-outlined text-[18px]">error</span>
      <span><?php echo htmlspecialchars($error); ?></span>
    </div>
  <?php endif; ?>

  <?php if ($success): ?>
    <div class="mb-6 p-4 rounded-2xl bg-[#EAF4F4] border border-[#CCE3DE] text-[#537A72] text-xs font-semibold flex items-center gap-2">
      <span class="material-symbols-outlined text-[18px]">check_circle</span>
      <span><?php echo htmlspecialchars($success); ?></span>
    </div>
  <?php endif; ?>

  <!-- PESTAÑAS DE FILTRO POR ESTADO (STATE PATTERN) -->
  <div class="flex items-center gap-2 overflow-x-auto pb-4 mb-6 text-xs font-semibold scrollbar-none">
    <?php
    $estadosPestanas = [
      'todas' => 'Todas las Solicitudes',
      'PENDIENTE' => 'Pendientes de Respuesta',
      'ACEPTADA' => 'Aceptadas (Por Agendar)',
      'PROGRAMADA' => 'Programadas en Agenda',
      'EN_PROCESO' => 'En Ejecución',
      'FINALIZADA' => 'Finalizadas',
      'CANCELADA' => 'Canceladas / Rechazadas'
    ];
    foreach ($estadosPestanas as $estKey => $estLabel):
      $activo = ($filtroEstado === $estKey) || ($estKey === 'todas' && empty($filtroEstado));
    ?>
      <a 
        href="requests.php?estado=<?php echo $estKey; ?>" 
        class="whitespace-nowrap px-4 py-2 rounded-xl transition-all <?php echo $activo ? 'bg-[#6B9080] text-white shadow-sm font-bold' : 'bg-white text-[#7A726A] border border-[#E8E2D8] hover:border-[#CCE3DE] hover:text-[#2C2724]'; ?>"
      >
        <?php echo $estLabel; ?>
      </a>
    <?php endforeach; ?>
  </div>

  <!-- LISTA DE SOLICITUDES -->
  <?php if (empty($solicitudes)): ?>
    <div class="text-center py-16 bg-white rounded-3xl border border-[#E8E2D8] p-8">
      <div class="w-16 h-16 rounded-full bg-[#EAF4F4] text-[#537A72] flex items-center justify-center mx-auto mb-3">
        <span class="material-symbols-outlined text-3xl">task</span>
      </div>
      <h3 class="text-lg font-bold font-headline text-[#2C2724]">No hay solicitudes en este filtro</h3>
      <p class="text-xs text-[#7A726A] mt-1 mb-4">Crea una nueva solicitud para acordar un camello con un profesional.</p>
      <button 
        onclick="document.getElementById('modal-crear-solicitud').classList.remove('hidden')" 
        class="py-2 px-4 rounded-xl bg-[#6B9080] text-white font-bold text-xs shadow-sm hover:bg-[#5A7C6E]"
      >
        Crear Primera Solicitud
      </button>
    </div>
  <?php else: ?>
    <div class="grid grid-cols-1 gap-5">
      <?php foreach ($solicitudes as $sol): 
        $esCliente = ($authUser && $sol['cliente_id'] === $authUser['id']);
        $esTrabajador = ($authUser && $sol['trabajador_id'] === $authUser['id']);
        $estado = $sol['estado'];
        $badgeClass = 'badge-estado-' . strtolower($estado);
      ?>
        <div class="pastel-card p-6 rounded-3xl border border-[#E8E2D8] bg-white transition-all">
          
          <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-4 border-b border-[#F0EAE1]">
            <div class="flex items-center gap-3">
              <span class="text-xs font-bold px-3 py-1 rounded-full <?php echo $badgeClass; ?>">
                ● Estado: <?php echo htmlspecialchars($estado); ?>
              </span>
              <span class="text-xs text-[#8C847C] font-mono">ID: <?php echo htmlspecialchars($sol['id']); ?></span>
              <span class="text-xs text-[#8C847C]">•</span>
              <span class="text-xs text-[#8C847C]">Creada el <?php echo date('d/m/Y H:i', strtotime($sol['created_at'])); ?></span>
            </div>

            <!-- Precios acordados -->
            <div class="flex items-center gap-4 text-xs">
              <div>
                <span class="text-[#8C847C] block text-[10px]">Propuesto:</span>
                <span class="font-bold text-[#2C2724]"><?php echo formatCOP($sol['valor_propuesto']); ?></span>
              </div>
              <?php if ($sol['valor_acordado'] > 0): ?>
                <div class="pl-3 border-l border-[#E8E2D8]">
                  <span class="text-[#537A72] block text-[10px] font-bold">Acordado Final:</span>
                  <span class="font-bold text-[#537A72] text-sm"><?php echo formatCOP($sol['valor_acordado']); ?></span>
                </div>
              <?php endif; ?>
            </div>
          </div>

          <!-- Título y Descripción -->
          <div class="py-4">
            <h3 class="text-lg font-bold font-headline text-[#2C2724] mb-1">
              <?php echo htmlspecialchars($sol['titulo']); ?>
            </h3>
            <p class="text-xs text-[#56504A] leading-relaxed">
              <?php echo nl2br(htmlspecialchars($sol['descripcion'])); ?>
            </p>
          </div>

          <!-- Datos de las Partes y Fecha -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-3 p-3.5 rounded-2xl bg-[#FBF9F5] border border-[#E8E2D8] mb-4 text-xs">
            <!-- Cliente -->
            <div class="flex items-center gap-2.5">
              <img src="<?php echo htmlspecialchars($sol['cliente_avatar'] ?: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=100&q=80'); ?>" class="w-8 h-8 rounded-lg object-cover" />
              <div>
                <span class="text-[10px] text-[#8C847C] block">Cliente:</span>
                <span class="font-bold text-[#2C2724]"><?php echo htmlspecialchars($sol['cliente_nombre']); ?></span>
              </div>
            </div>

            <!-- Trabajador -->
            <div class="flex items-center gap-2.5">
              <img src="<?php echo htmlspecialchars($sol['trabajador_avatar'] ?: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&q=80'); ?>" class="w-8 h-8 rounded-lg object-cover" />
              <div>
                <span class="text-[10px] text-[#8C847C] block">Camellador:</span>
                <span class="font-bold text-[#2C2724]"><?php echo htmlspecialchars($sol['trabajador_nombre']); ?></span>
              </div>
            </div>

            <!-- Programación o Ubicación -->
            <div class="flex items-center gap-2">
              <span class="material-symbols-outlined text-[20px] text-[#537A72]">event</span>
              <div>
                <span class="text-[10px] text-[#8C847C] block">Fecha Programada:</span>
                <span class="font-bold text-[#2C2724]">
                  <?php echo $sol['fecha_programada'] ? date('d/m/Y - h:i A', strtotime($sol['fecha_programada'])) : 'Sin agendar aún'; ?>
                </span>
              </div>
            </div>
          </div>

          <?php if (!empty($sol['motivo_cancelacion'])): ?>
            <div class="mb-4 p-3 rounded-xl bg-[#FFF0F0] border border-[#F5C6CB] text-xs text-[#B94A48]">
              <strong>Motivo registrado:</strong> <?php echo htmlspecialchars($sol['motivo_cancelacion']); ?>
            </div>
          <?php endif; ?>

          <!-- BOTONES DE TRANSICIÓN DEL PATRÓN STATE -->
          <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-[#F0EAE1]">
            
            <!-- Abrir Chat Directo -->
            <a href="messages.php?destinatario=<?php echo $esCliente ? $sol['trabajador_id'] : $sol['cliente_id']; ?>&solicitud=<?php echo $sol['id']; ?>" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white border border-[#E8E2D8] hover:border-[#655B8B] text-xs font-semibold text-[#655B8B] transition-all">
              <span class="material-symbols-outlined text-[16px]">chat</span>
              <span>Mensaje con la otra parte</span>
            </a>

            <!-- Acciones según Estado Actual -->
            <div class="flex flex-wrap items-center gap-2">
              
              <!-- Si PENDIENTE -->
              <?php if ($estado === 'PENDIENTE'): ?>
                <?php if ($esTrabajador): ?>
                  <form method="POST" action="requests.php">
                    <input type="hidden" name="action" value="aceptar" />
                    <input type="hidden" name="solicitud_id" value="<?php echo $sol['id']; ?>" />
                    <button type="submit" class="px-3.5 py-1.5 rounded-xl bg-[#6B9080] hover:bg-[#5A7C6E] text-white text-xs font-bold shadow-sm transition-all flex items-center gap-1">
                      <span class="material-symbols-outlined text-[16px]">check</span>
                      <span>Aceptar Solicitud</span>
                    </button>
                  </form>

                  <button 
                    onclick="openRechazarModal('<?php echo $sol['id']; ?>')" 
                    class="px-3 py-1.5 rounded-xl bg-[#FFF0F0] text-[#B94A48] hover:bg-[#FDE2E4] border border-[#F5C6CB] text-xs font-bold transition-all"
                  >
                    Rechazar
                  </button>
                <?php else: ?>
                  <span class="text-xs text-[#8C847C] italic">Esperando respuesta del trabajador...</span>
                  <button onclick="openCancelarModal('<?php echo $sol['id']; ?>')" class="text-xs text-[#B94A48] hover:underline font-semibold">
                    Cancelar
                  </button>
                <?php endif; ?>
              <?php endif; ?>

              <!-- Si ACEPTADA -->
              <?php if ($estado === 'ACEPTADA'): ?>
                <button 
                  onclick="openProgramarModal('<?php echo $sol['id']; ?>', '<?php echo $sol['valor_propuesto']; ?>')" 
                  class="px-4 py-1.5 rounded-xl bg-[#655B8B] hover:bg-[#524872] text-white text-xs font-bold shadow-sm transition-all flex items-center gap-1.5"
                >
                  <span class="material-symbols-outlined text-[16px]">calendar_month</span>
                  <span>Agendar Fecha & Valor</span>
                </button>
                <button onclick="openCancelarModal('<?php echo $sol['id']; ?>')" class="text-xs text-[#B94A48] hover:underline font-semibold ml-2">
                  Cancelar
                </button>
              <?php endif; ?>

              <!-- Si PROGRAMADA -->
              <?php if ($estado === 'PROGRAMADA'): ?>
                <?php if ($esTrabajador): ?>
                  <form method="POST" action="requests.php">
                    <input type="hidden" name="action" value="iniciar" />
                    <input type="hidden" name="solicitud_id" value="<?php echo $sol['id']; ?>" />
                    <button type="submit" class="px-4 py-1.5 rounded-xl bg-[#E79E78] hover:bg-[#D58C66] text-white text-xs font-bold shadow-sm transition-all flex items-center gap-1.5">
                      <span class="material-symbols-outlined text-[16px]">play_arrow</span>
                      <span>Iniciar Camello</span>
                    </button>
                  </form>
                <?php endif; ?>
                <button 
                  onclick="openReprogramarModal('<?php echo $sol['id']; ?>')" 
                  class="px-3 py-1.5 rounded-xl bg-white border border-[#E8E2D8] text-xs font-semibold text-[#56504A] hover:bg-[#F6F3EC]"
                >
                  Reprogramar
                </button>
                <button onclick="openCancelarModal('<?php echo $sol['id']; ?>')" class="text-xs text-[#B94A48] hover:underline font-semibold ml-2">
                  Cancelar
                </button>
              <?php endif; ?>

              <!-- Si EN_PROCESO -->
              <?php if ($estado === 'EN_PROCESO'): ?>
                <form method="POST" action="requests.php">
                  <input type="hidden" name="action" value="finalizar" />
                  <input type="hidden" name="solicitud_id" value="<?php echo $sol['id']; ?>" />
                  <button type="submit" class="px-4 py-1.5 rounded-xl bg-[#6B9080] hover:bg-[#5A7C6E] text-white text-xs font-bold shadow-sm transition-all flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">task_alt</span>
                    <span>Finalizar y Entregar</span>
                  </button>
                </form>
                <button onclick="openCancelarModal('<?php echo $sol['id']; ?>')" class="text-xs text-[#B94A48] hover:underline font-semibold ml-2">
                  Cancelar con Justificación
                </button>
              <?php endif; ?>

              <!-- Si FINALIZADA -->
              <?php if ($estado === 'FINALIZADA'): ?>
                <?php if ($esCliente): ?>
                  <?php if ($calificacionRepo->yaCalificado($sol['id'])): ?>
                    <span class="inline-flex items-center gap-1 text-xs text-[#6B9080] font-bold">
                      <span class="material-symbols-outlined text-[16px]">star</span>
                      <span>Servicio Calificado</span>
                    </span>
                  <?php else: ?>
                    <button 
                      onclick="openCalificarModal('<?php echo $sol['id']; ?>', '<?php echo htmlspecialchars($sol['trabajador_nombre']); ?>')" 
                      class="px-3.5 py-1.5 rounded-xl bg-[#FEFAF0] border border-[#FDF1B8] text-[#8C7738] text-xs font-bold hover:bg-[#FDF1B8] transition-all flex items-center gap-1"
                    >
                      <span class="material-symbols-outlined text-[16px]">hotel_class</span>
                      <span>Calificar al Trabajador</span>
                    </button>
                  <?php endif; ?>
                <?php else: ?>
                  <span class="text-xs text-[#6B9080] font-bold flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">check_circle</span>
                    <span>Camello Completado con Éxito</span>
                  </span>
                <?php endif; ?>
              <?php endif; ?>

            </div>
          </div>

        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

</div>

<!-- MODAL CREAR SOLICITUD -->
<div id="modal-crear-solicitud" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm hidden animate-fade-in">
  <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-[#E8E2D8] relative animate-scale-up">
    <div class="flex items-center justify-between pb-3 border-b border-[#F0EAE1] mb-5">
      <div class="flex items-center gap-2">
        <span class="material-symbols-outlined text-[#6B9080]">add_task</span>
        <h3 class="font-bold font-headline text-lg text-[#2C2724]">Nueva Solicitud de Servicio</h3>
      </div>
      <button onclick="document.getElementById('modal-crear-solicitud').classList.add('hidden')" class="text-[#8C847C] hover:text-[#2C2724]">✕</button>
    </div>

    <form method="POST" action="requests.php" class="space-y-4">
      <input type="hidden" name="action" value="crear_solicitud" />

      <div>
        <label class="block text-xs font-bold text-[#2C2724] uppercase mb-1">Seleccionar Trabajador</label>
        <select name="trabajador_id" required class="w-full py-2.5 px-3 rounded-xl border border-[#E8E2D8] text-xs">
          <?php foreach ($trabajadoresDisponibles as $t): ?>
            <option value="<?php echo $t['id']; ?>">
              <?php echo htmlspecialchars($t['name']); ?> — <?php echo htmlspecialchars($t['profession'] ?? 'Camellador'); ?> (⭐ <?php echo number_format($t['rating_avg'], 1); ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label class="block text-xs font-bold text-[#2C2724] uppercase mb-1">Título del Trabajo</label>
        <input type="text" name="titulo" required placeholder="Ej. Instalación de Red Eléctrica / Diseño UI" class="w-full py-2 px-3 rounded-xl border border-[#E8E2D8] text-xs" />
      </div>

      <div>
        <label class="block text-xs font-bold text-[#2C2724] uppercase mb-1">Descripción del Servicio Requerido</label>
        <textarea name="descripcion" rows="3" required placeholder="Describe los entregables, requerimientos y alcance..." class="w-full py-2 px-3 rounded-xl border border-[#E8E2D8] text-xs"></textarea>
      </div>

      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-xs font-bold text-[#2C2724] uppercase mb-1">Presupuesto Propuesto (COP)</label>
          <input type="number" name="valor_propuesto" value="500000" step="50000" class="w-full py-2 px-3 rounded-xl border border-[#E8E2D8] text-xs" />
        </div>
        <div>
          <label class="block text-xs font-bold text-[#2C2724] uppercase mb-1">Ubicación / Modalidad</label>
          <input type="text" name="direccion" value="Remoto / Bogotá" class="w-full py-2 px-3 rounded-xl border border-[#E8E2D8] text-xs" />
        </div>
      </div>

      <div class="pt-3 flex gap-2">
        <button type="submit" class="flex-1 py-2.5 rounded-xl bg-[#6B9080] hover:bg-[#5A7C6E] text-white text-xs font-bold shadow-sm">
          Enviar Solicitud
        </button>
        <button type="button" onclick="document.getElementById('modal-crear-solicitud').classList.add('hidden')" class="px-4 py-2.5 rounded-xl bg-[#F6F3EC] text-xs font-semibold text-[#56504A]">
          Cancelar
        </button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL PROGRAMAR FECHA -->
<div id="modal-programar" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm hidden animate-fade-in">
  <div class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl border border-[#E8E2D8] animate-scale-up">
    <h3 class="font-bold font-headline text-base text-[#2C2724] mb-3">Agendar Fecha de Servicio</h3>
    <form method="POST" action="requests.php" class="space-y-3">
      <input type="hidden" name="action" value="programar" />
      <input type="hidden" name="solicitud_id" id="prog-sol-id" value="" />

      <div>
        <label class="block text-xs font-bold text-[#2C2724] mb-1">Fecha y Hora</label>
        <input type="datetime-local" name="fecha_programada" required class="w-full py-2 px-3 rounded-xl border border-[#E8E2D8] text-xs" />
      </div>

      <div>
        <label class="block text-xs font-bold text-[#2C2724] mb-1">Valor Acordado Final (COP)</label>
        <input type="number" name="valor_acordado" id="prog-valor" class="w-full py-2 px-3 rounded-xl border border-[#E8E2D8] text-xs" />
      </div>

      <div class="pt-2 flex gap-2">
        <button type="submit" class="flex-1 py-2 rounded-xl bg-[#655B8B] hover:bg-[#524872] text-white text-xs font-bold">
          Confirmar Agenda
        </button>
        <button type="button" onclick="document.getElementById('modal-programar').classList.add('hidden')" class="px-3 py-2 rounded-xl bg-[#F6F3EC] text-xs">
          Cerrar
        </button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL RECHAZAR / CANCELAR -->
<div id="modal-cancelar" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm hidden animate-fade-in">
  <div class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl border border-[#E8E2D8] animate-scale-up">
    <h3 class="font-bold font-headline text-base text-[#B94A48] mb-2" id="modal-cancelar-titulo">Cancelar Solicitud</h3>
    <form method="POST" action="requests.php" class="space-y-3">
      <input type="hidden" name="action" id="cancelar-action" value="cancelar" />
      <input type="hidden" name="solicitud_id" id="cancelar-sol-id" value="" />

      <div>
        <label class="block text-xs font-bold text-[#2C2724] mb-1">Ingresa el motivo:</label>
        <textarea name="motivo" rows="2" required placeholder="Motivo de la decisión..." class="w-full py-2 px-3 rounded-xl border border-[#E8E2D8] text-xs"></textarea>
      </div>

      <div class="flex gap-2">
        <button type="submit" class="flex-1 py-2 rounded-xl bg-[#B94A48] text-white text-xs font-bold">Confirmar</button>
        <button type="button" onclick="document.getElementById('modal-cancelar').classList.add('hidden')" class="px-3 py-2 rounded-xl bg-[#F6F3EC] text-xs">Cerrar</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL CALIFICAR -->
<div id="modal-calificar" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm hidden animate-fade-in">
  <div class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl border border-[#E8E2D8] animate-scale-up">
    <div class="text-center mb-3">
      <div class="w-12 h-12 rounded-full bg-[#FEFAF0] text-[#8C7738] flex items-center justify-center mx-auto mb-2 border border-[#FDF1B8]">
        <span class="material-symbols-outlined text-2xl">star</span>
      </div>
      <h3 class="font-bold font-headline text-base text-[#2C2724]">Calificar Servicio</h3>
      <p class="text-xs text-[#7A726A]" id="calificar-subtitulo">Valoración de 1 a 5 estrellas</p>
    </div>

    <form method="POST" action="requests.php" class="space-y-3">
      <input type="hidden" name="action" value="calificar" />
      <input type="hidden" name="solicitud_id" id="calificar-sol-id" value="" />

      <div>
        <label class="block text-xs font-bold text-[#2C2724] mb-1">Puntuación:</label>
        <select name="puntuacion" class="w-full py-2 px-3 rounded-xl border border-[#E8E2D8] text-xs font-bold">
          <option value="5">⭐⭐⭐⭐⭐ 5 Estrellas (Excelente)</option>
          <option value="4">⭐⭐⭐⭐ 4 Estrellas (Muy Bueno)</option>
          <option value="3">⭐⭐⭐ 3 Estrellas (Aceptable)</option>
          <option value="2">⭐⭐ 2 Estrellas (Regular)</option>
          <option value="1">⭐ 1 Estrella (Insatisfactorio)</option>
        </select>
      </div>

      <div>
        <label class="block text-xs font-bold text-[#2C2724] mb-1">Comentario / Reseña:</label>
        <textarea name="comentario" rows="2" placeholder="Describe tu experiencia con el profesional..." class="w-full py-2 px-3 rounded-xl border border-[#E8E2D8] text-xs"></textarea>
      </div>

      <div class="flex gap-2">
        <button type="submit" class="flex-1 py-2 rounded-xl bg-[#6B9080] hover:bg-[#5A7C6E] text-white text-xs font-bold">
          Registrar Calificación
        </button>
        <button type="button" onclick="document.getElementById('modal-calificar').classList.add('hidden')" class="px-3 py-2 rounded-xl bg-[#F6F3EC] text-xs">
          Cerrar
        </button>
      </div>
    </form>
  </div>
</div>

<script>
function openProgramarModal(solId, valor) {
  document.getElementById('prog-sol-id').value = solId;
  document.getElementById('prog-valor').value = valor || 0;
  document.getElementById('modal-programar').classList.remove('hidden');
}

function openRechazarModal(solId) {
  document.getElementById('cancelar-sol-id').value = solId;
  document.getElementById('cancelar-action').value = 'rechazar';
  document.getElementById('modal-cancelar-titulo').textContent = 'Rechazar Solicitud';
  document.getElementById('modal-cancelar').classList.remove('hidden');
}

function openCancelarModal(solId) {
  document.getElementById('cancelar-sol-id').value = solId;
  document.getElementById('cancelar-action').value = 'cancelar';
  document.getElementById('modal-cancelar-titulo').textContent = 'Cancelar Solicitud';
  document.getElementById('modal-cancelar').classList.remove('hidden');
}

function openCalificarModal(solId, trabajadorNombre) {
  document.getElementById('calificar-sol-id').value = solId;
  document.getElementById('calificar-subtitulo').textContent = 'Valoración para ' + trabajadorNombre;
  document.getElementById('modal-calificar').classList.remove('hidden');
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
