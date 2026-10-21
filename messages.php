<?php
/**
 * Centro de Mensajería y Chat en Vivo - Échele Camello
 * Conforme a 05_PATRONES_DISENO_ECHELE_CAMELLO.md (RF-41 al 46)
 * Implementa Repository (ConversacionRepository, MensajeRepository) + DTO + Adapter de Almacenamiento.
 */

require_once __DIR__ . '/includes/patterns/bootstrap.php';

use EcheleCamello\Patterns\DTO\EnviarMensajeDTO;

$pageTitle = 'Bandeja de Mensajes';
$authUser = getAuthenticatedUser();
$conversacionRepo = ServiceContainer::getConversacionRepo();
$mensajeRepo = ServiceContainer::getMensajeRepo();
$usuarioRepo = ServiceContainer::getUsuarioRepo();
$storageAdapter = ServiceContainer::getStorageAdapter();

$error = '';
$success = '';

// Si viene con parámetro de inicio de conversación (?destinatario=usr-xxx)
if (!empty($_GET['destinatario']) && $authUser) {
    $destId = $_GET['destinatario'];
    $solId = $_GET['solicitud'] ?? null;
    $convId = $conversacionRepo->obtenerOcrear($authUser['id'], $destId, $solId);
    header("Location: messages.php?conv={$convId}");
    exit;
}

// Procesar envío de mensaje
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $authUser) {
    try {
        $convId = $_POST['conversacion_id'] ?? '';
        $contenido = trim($_POST['contenido'] ?? '');
        $adjuntoUrl = null;
        $adjuntoTipo = null;

        // Manejo de archivo adjunto mediante AlmacenamientoLocalAdapter (Patrón Adapter)
        if (!empty($_FILES['adjunto']) && $_FILES['adjunto']['error'] === UPLOAD_ERR_OK) {
            $archivoSubido = $storageAdapter->guardar($_FILES['adjunto'], 'chat');
            $adjuntoUrl = $archivoSubido['url'];
            $adjuntoTipo = strpos($archivoSubido['mime_type'], 'image/') === 0 ? 'image' : 'file';
        }

        $dto = new EnviarMensajeDTO([
            'conversacion_id' => $convId,
            'contenido' => $contenido,
            'adjunto_url' => $adjuntoUrl,
            'adjunto_tipo' => $adjuntoTipo
        ], $authUser['id']);

        $errs = $dto->validate();
        if (!empty($errs)) {
            $error = implode(' ', $errs);
        } else {
            $mensajeRepo->guardar([
                'conversacion_id' => $dto->conversacionId,
                'remitente_id' => $dto->remitenteId,
                'contenido' => $dto->contenido,
                'adjunto_url' => $dto->adjuntoUrl,
                'adjunto_tipo' => $dto->adjuntoTipo
            ]);
            header("Location: messages.php?conv={$convId}");
            exit;
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Cargar conversaciones del usuario autenticado
$conversaciones = $authUser ? $conversacionRepo->listarPorUsuario($authUser['id']) : [];
$activeConvId = $_GET['conv'] ?? (!empty($conversaciones) ? $conversaciones[0]['id'] : null);
$activeConv = null;
$mensajes = [];

if ($activeConvId && $authUser) {
    $activeConv = $conversacionRepo->buscarPorId($activeConvId);
    if ($activeConv) {
        $mensajes = $mensajeRepo->listarPorConversacion($activeConvId);
        // Marcar como leídos
        $mensajeRepo->marcarComoLeidos($activeConvId, $authUser['id']);
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-body">

  <div class="flex items-center justify-between pb-6 mb-6 border-b border-[#E8E2D8]">
    <div>
      <div class="flex items-center gap-2 mb-1">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#F2F4FB] text-[#655B8B] border border-[#DCD4F0]">
          <span class="material-symbols-outlined text-[15px]">forum</span>
          Módulo de Comunicación (RF-41 a 46)
        </span>
        <span class="text-xs text-[#8C847C]">Repositorio de Mensajes & Adapter de Archivos</span>
      </div>
      <h1 class="text-2xl sm:text-3xl font-bold font-headline text-[#2C2724]">Mensajería en Vivo</h1>
    </div>
  </div>

  <?php if ($error): ?>
    <div class="mb-4 p-4 rounded-2xl bg-[#FFF0F0] border border-[#F5C6CB] text-[#B94A48] text-xs font-semibold flex items-center gap-2">
      <span class="material-symbols-outlined text-[18px]">error</span>
      <span><?php echo htmlspecialchars($error); ?></span>
    </div>
  <?php endif; ?>

  <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 bg-white rounded-3xl border border-[#E8E2D8] shadow-[0_12px_40px_rgba(44,39,36,0.04)] overflow-hidden min-h-[580px]">
    
    <!-- COLUMNA IZQUIERDA: LISTA DE CONVERSACIONES -->
    <div class="lg:col-span-4 border-r border-[#E8E2D8] flex flex-col bg-[#FDFBF9]">
      <div class="p-4 border-b border-[#E8E2D8]">
        <div class="relative">
          <input 
            type="text" 
            placeholder="Buscar en mensajes..." 
            class="w-full pl-9 pr-3 py-2 rounded-xl border border-[#E8E2D8] text-xs focus:border-[#6B9080]"
          />
          <span class="material-symbols-outlined absolute left-3 top-2.5 text-[#8C847C] text-[16px]">search</span>
        </div>
      </div>

      <div class="overflow-y-auto flex-grow divide-y divide-[#F0EAE1]">
        <?php if (empty($conversaciones)): ?>
          <div class="p-8 text-center text-xs text-[#8C847C]">
            No tienes conversaciones activas. Inicia una desde el perfil de un camellador o en tus solicitudes.
          </div>
        <?php else: ?>
          <?php foreach ($conversaciones as $c): 
            $esCliente = ($authUser && $c['cliente_id'] === $authUser['id']);
            $otroNombre = $esCliente ? $c['trabajador_nombre'] : $c['cliente_nombre'];
            $otroAvatar = $esCliente ? $c['trabajador_avatar'] : $c['cliente_avatar'];
            $isActive = ($activeConvId === $c['id']);
          ?>
            <a 
              href="messages.php?conv=<?php echo $c['id']; ?>" 
              class="block p-4 transition-all <?php echo $isActive ? 'bg-[#EAF4F4]/70 border-l-4 border-[#6B9080]' : 'hover:bg-[#F6F3EC]/60'; ?>"
            >
              <div class="flex items-center gap-3">
                <img 
                  src="<?php echo htmlspecialchars($otroAvatar ?: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&q=80'); ?>" 
                  class="w-10 h-10 rounded-xl object-cover shrink-0 border border-[#E8E2D8]" 
                />
                <div class="flex-grow min-w-0">
                  <div class="flex items-center justify-between mb-0.5">
                    <span class="font-bold text-xs text-[#2C2724] truncate"><?php echo htmlspecialchars($otroNombre); ?></span>
                    <span class="text-[10px] text-[#8C847C]"><?php echo date('H:i', strtotime($c['ultimo_mensaje_at'])); ?></span>
                  </div>
                  <p class="text-xs text-[#7A726A] truncate">
                    <?php echo htmlspecialchars($c['ultimo_mensaje'] ?? 'Sin mensajes'); ?>
                  </p>
                </div>
                <?php if (!empty($c['no_leidos']) && $c['no_leidos'] > 0): ?>
                  <span class="w-5 h-5 rounded-full bg-[#E79E78] text-white text-[10px] font-bold flex items-center justify-center shrink-0">
                    <?php echo $c['no_leidos']; ?>
                  </span>
                <?php endif; ?>
              </div>
            </a>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

    <!-- COLUMNA DERECHA: VENTANA DE CHAT ACTIVA -->
    <div class="lg:col-span-8 flex flex-col justify-between bg-white min-h-[500px]">
      <?php if ($activeConv): 
        $esCliente = ($authUser && $activeConv['cliente_id'] === $authUser['id']);
        $otroNombre = $esCliente ? $activeConv['trabajador_nombre'] : $activeConv['cliente_nombre'];
        $otroAvatar = $esCliente ? $activeConv['trabajador_avatar'] : $activeConv['cliente_avatar'];
      ?>
        <!-- Encabezado del Chat -->
        <div class="p-4 px-6 border-b border-[#E8E2D8] flex items-center justify-between bg-white">
          <div class="flex items-center gap-3">
            <img src="<?php echo htmlspecialchars($otroAvatar ?: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&q=80'); ?>" class="w-10 h-10 rounded-xl object-cover border border-[#E8E2D8]" />
            <div>
              <h3 class="font-bold font-headline text-sm text-[#2C2724]"><?php echo htmlspecialchars($otroNombre); ?></h3>
              <span class="text-[11px] text-[#537A72] flex items-center gap-1 font-semibold">
                <span class="w-1.5 h-1.5 rounded-full bg-[#6B9080]"></span> En línea · Verificado
              </span>
            </div>
          </div>
          <?php if (!empty($activeConv['solicitud_id'])): ?>
            <a href="requests.php?id=<?php echo $activeConv['solicitud_id']; ?>" class="text-xs font-semibold px-3 py-1 rounded-xl bg-[#F6F3EC] text-[#56504A] hover:bg-[#EAE4D9] flex items-center gap-1">
              <span class="material-symbols-outlined text-[15px] text-[#6B9080]">task</span>
              <span>Ver Solicitud Vinculada</span>
            </a>
          <?php endif; ?>
        </div>

        <!-- Flujo de Mensajes -->
        <div class="p-6 overflow-y-auto flex-grow space-y-4 max-h-[420px] bg-[#FDFBF9]">
          <?php if (empty($mensajes)): ?>
            <div class="text-center py-12 text-xs text-[#8C847C]">
              No hay mensajes aún en esta conversación. ¡Saluda y acuerda los detalles de tu camello!
            </div>
          <?php else: ?>
            <?php foreach ($mensajes as $m): 
              $esMio = ($authUser && $m['remitente_id'] === $authUser['id']);
            ?>
              <div class="flex flex-col <?php echo $esMio ? 'items-end' : 'items-start'; ?>">
                <div class="max-w-[78%] rounded-2xl p-3.5 text-xs shadow-sm <?php echo $esMio ? 'bg-[#6B9080] text-white rounded-tr-none' : 'bg-white text-[#2C2724] border border-[#E8E2D8] rounded-tl-none'; ?>">
                  
                  <?php if (!empty($m['adjunto_url'])): ?>
                    <div class="mb-2">
                      <?php if ($m['adjunto_tipo'] === 'image'): ?>
                        <img src="<?php echo htmlspecialchars($m['adjunto_url']); ?>" alt="Adjunto" class="max-h-48 rounded-xl object-cover border border-white/20" />
                      <?php else: ?>
                        <a href="<?php echo htmlspecialchars($m['adjunto_url']); ?>" target="_blank" class="inline-flex items-center gap-1.5 underline font-bold">
                          <span class="material-symbols-outlined text-[16px]">attach_file</span>
                          <span>Descargar Documento Adjunto</span>
                        </a>
                      <?php endif; ?>
                    </div>
                  <?php endif; ?>

                  <?php if (!empty($m['contenido'])): ?>
                    <p class="leading-relaxed"><?php echo nl2br(htmlspecialchars($m['contenido'])); ?></p>
                  <?php endif; ?>
                  
                  <div class="flex items-center justify-end gap-1 mt-1 text-[9px] <?php echo $esMio ? 'text-white/80' : 'text-[#8C847C]'; ?>">
                    <span><?php echo date('H:i', strtotime($m['created_at'])); ?></span>
                    <?php if ($esMio): ?>
                      <span class="material-symbols-outlined text-[12px]">done_all</span>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

        <!-- Barra de Entrada de Mensaje con Adapter de Archivos -->
        <form method="POST" action="messages.php" enctype="multipart/form-data" class="p-4 border-t border-[#E8E2D8] bg-white flex items-center gap-2">
          <input type="hidden" name="conversacion_id" value="<?php echo htmlspecialchars($activeConvId); ?>" />
          
          <!-- Botón de Adjunto (Adapter Archivos) -->
          <label class="cursor-pointer p-2.5 rounded-xl border border-[#E8E2D8] hover:bg-[#F6F3EC] text-[#537A72] flex items-center justify-center transition-all" title="Adjuntar foto o archivo">
            <input type="file" name="adjunto" class="hidden" onchange="document.getElementById('file-indicator').classList.remove('hidden')" />
            <span class="material-symbols-outlined text-[18px]">attachment</span>
          </label>

          <span id="file-indicator" class="hidden text-[10px] font-bold text-[#6B9080] bg-[#EAF4F4] px-2 py-1 rounded-md">
            Archivo listo
          </span>

          <input 
            type="text" 
            name="contenido" 
            placeholder="Escribe un mensaje aquí..." 
            class="flex-grow py-2.5 px-4 rounded-xl border border-[#E8E2D8] text-xs focus:border-[#6B9080] focus:ring-1 focus:ring-[#6B9080]"
            autocomplete="off"
          />

          <button 
            type="submit" 
            class="py-2.5 px-5 rounded-xl bg-[#6B9080] hover:bg-[#5A7C6E] text-white text-xs font-bold shadow-sm transition-all flex items-center gap-1.5"
          >
            <span>Enviar</span>
            <span class="material-symbols-outlined text-[16px]">send</span>
          </button>
        </form>

      <?php else: ?>
        <div class="flex flex-col items-center justify-center flex-grow p-12 text-center text-xs text-[#8C847C]">
          <span class="material-symbols-outlined text-4xl text-[#CCE3DE] mb-2">chat</span>
          <p>Selecciona una conversación del listado para ver el historial y responder.</p>
        </div>
      <?php endif; ?>
    </div>

  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
