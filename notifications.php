<?php
/**
 * Centro de Notificaciones - Échele Camello
 * Conforme a 05_PATRONES_DISENO_ECHELE_CAMELLO.md (Sección 10 - Observer Pattern)
 * Visualiza notificaciones generadas automáticamente por los suscriptores de eventos.
 */

require_once __DIR__ . '/includes/patterns/bootstrap.php';

$pageTitle = 'Centro de Notificaciones';
$authUser = getAuthenticatedUser();
$notifRepo = ServiceContainer::getNotificacionRepo();

if (!$authUser) {
    header('Location: login.php?msg=debes_iniciar_sesion');
    exit;
}

// Acción: Marcar todas como leídas
if (isset($_GET['action']) && $_GET['action'] === 'read_all') {
    $notifRepo->marcarTodasLeidas($authUser['id']);
    header('Location: notifications.php');
    exit;
}

// Acción: Marcar individual como leída
if (isset($_GET['read'])) {
    $notifRepo->marcarComoLeida($_GET['read'], $authUser['id']);
    if (!empty($_GET['redirect'])) {
        header('Location: ' . $_GET['redirect']);
        exit;
    }
    header('Location: notifications.php');
    exit;
}

$filtroTipo = $_GET['tipo'] ?? 'todas';
$todasNotifs = $notifRepo->listarPorUsuario($authUser['id'], 50);

// Filtrado en memoria
$notificaciones = array_filter($todasNotifs, function($n) use ($filtroTipo) {
    if ($filtroTipo === 'todas') return true;
    return $n['tipo'] === $filtroTipo;
});

require_once __DIR__ . '/includes/header.php';
?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 font-body">

  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 mb-6 border-b border-[#E8E2D8]">
    <div>
      <div class="flex items-center gap-2 mb-1.5">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#EAF4F4] text-[#537A72] border border-[#CCE3DE]">
          <span class="material-symbols-outlined text-[15px]">notifications_active</span>
          Observer / Event Subscriber
        </span>
        <span class="text-xs text-[#8C847C]">Reacción automática a transiciones de solicitudes y mensajes</span>
      </div>
      <h1 class="text-3xl font-bold font-headline text-[#2C2724]">Centro de Notificaciones</h1>
    </div>

    <?php if (!empty($todasNotifs)): ?>
      <div>
        <a 
          href="notifications.php?action=read_all" 
          class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white border border-[#E8E2D8] hover:border-[#CCE3DE] text-xs font-bold text-[#537A72] shadow-sm transition-all"
        >
          <span class="material-symbols-outlined text-[16px]">done_all</span>
          <span>Marcar todas como leídas</span>
        </a>
      </div>
    <?php endif; ?>
  </div>

  <!-- FILTROS POR TIPO DE NOTIFICACIÓN -->
  <div class="flex items-center gap-2 mb-6 overflow-x-auto pb-2 text-xs font-semibold">
    <?php
    $tipos = [
      'todas' => 'Todas',
      'solicitud' => 'Solicitudes & Ciclo State',
      'mensaje' => 'Mensajes & Chat',
      'calificacion' => 'Calificaciones & Reputación',
      'sistema' => 'Sistema'
    ];
    foreach ($tipos as $k => $label):
      $active = ($filtroTipo === $k);
    ?>
      <a 
        href="notifications.php?tipo=<?php echo $k; ?>" 
        class="px-3.5 py-1.5 rounded-xl transition-all whitespace-nowrap <?php echo $active ? 'bg-[#6B9080] text-white shadow-sm font-bold' : 'bg-white text-[#7A726A] border border-[#E8E2D8] hover:border-[#CCE3DE]'; ?>"
      >
        <?php echo $label; ?>
      </a>
    <?php endforeach; ?>
  </div>

  <!-- LISTA DE NOTIFICACIONES -->
  <?php if (empty($notificaciones)): ?>
    <div class="text-center py-16 bg-white rounded-3xl border border-[#E8E2D8] p-8 shadow-sm">
      <div class="w-16 h-16 rounded-full bg-[#EAF4F4] text-[#537A72] flex items-center justify-center mx-auto mb-3">
        <span class="material-symbols-outlined text-3xl">notifications_off</span>
      </div>
      <h3 class="text-lg font-bold font-headline text-[#2C2724]">No tienes notificaciones en este filtro</h3>
      <p class="text-xs text-[#7A726A] mt-1">
        Las notificaciones se crearán automáticamente cada vez que cambie el estado de tus solicitudes o recibas mensajes.
      </p>
    </div>
  <?php else: ?>
    <div class="space-y-3">
      <?php foreach ($notificaciones as $n): 
        $leida = (bool)$n['leida'];
        $tipo = $n['tipo'];
        $icono = 'notifications';
        $colorBg = 'bg-[#EAF4F4] text-[#537A72] border-[#CCE3DE]';

        if ($tipo === 'solicitud') {
            $icono = 'sync_alt';
            $colorBg = 'bg-[#FDF5F2] text-[#A25844] border-[#F9D6C9]';
        } elseif ($tipo === 'mensaje') {
            $icono = 'chat_bubble';
            $colorBg = 'bg-[#F2F4FB] text-[#655B8B] border-[#DCD4F0]';
        } elseif ($tipo === 'calificacion') {
            $icono = 'star';
            $colorBg = 'bg-[#FEFAF0] text-[#8C7738] border-[#FDF1B8]';
        }
      ?>
        <div class="p-4 rounded-2xl border transition-all flex items-start gap-4 <?php echo $leida ? 'bg-white border-[#E8E2D8]' : 'bg-[#FBF9F5] border-[#CCE3DE] shadow-sm'; ?>">
          <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 border <?php echo $colorBg; ?>">
            <span class="material-symbols-outlined text-[20px]"><?php echo $icono; ?></span>
          </div>

          <div class="flex-grow min-w-0">
            <div class="flex items-center justify-between mb-1">
              <h4 class="font-bold font-headline text-sm text-[#2C2724] truncate">
                <?php echo htmlspecialchars($n['titulo']); ?>
              </h4>
              <span class="text-[10px] text-[#8C847C] whitespace-nowrap ml-2">
                <?php echo date('d/m/Y · H:i', strtotime($n['created_at'])); ?>
              </span>
            </div>

            <p class="text-xs text-[#56504A] leading-relaxed mb-2">
              <?php echo htmlspecialchars($n['mensaje']); ?>
            </p>

            <div class="flex items-center gap-3">
              <?php if (!empty($n['enlace'])): ?>
                <a 
                  href="notifications.php?read=<?php echo $n['id']; ?>&redirect=<?php echo urlencode($n['enlace']); ?>" 
                  class="text-xs font-bold text-[#537A72] hover:underline flex items-center gap-1"
                >
                  <span>Ver detalle</span>
                  <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                </a>
              <?php endif; ?>

              <?php if (!$leida): ?>
                <a 
                  href="notifications.php?read=<?php echo $n['id']; ?>" 
                  class="text-[11px] text-[#8C847C] hover:text-[#2C2724]"
                >
                  Marcar leída
                </a>
              <?php endif; ?>
            </div>
          </div>

          <?php if (!$leida): ?>
            <span class="w-2.5 h-2.5 rounded-full bg-[#E79E78] shrink-0 mt-2" title="No leída"></span>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
