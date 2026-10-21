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

  <!-- ENCABEZADO Y2K OBSERVER -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 mb-6 border-b border-[#00F0FF]/20">
    <div>
      <div class="flex items-center gap-2 mb-1.5">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#00F0FF]/20 text-[#00F0FF] border border-[#00F0FF]/50 shadow-[0_0_10px_rgba(0,240,255,0.3)]">
          <span class="material-symbols-outlined text-[15px]">notifications_active</span>
          ✦ Observer / Event Subscriber
        </span>
        <span class="text-xs text-[#94A3B8]">Reacción automática a transiciones de solicitudes y mensajes</span>
      </div>
      <h1 class="text-3xl font-black font-headline text-white tracking-tight" style="background: linear-gradient(180deg,#FFFFFF 0%,#D8E6FF 30%,#00F0FF 75%,#FFFFFF 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;filter:drop-shadow(0 0 16px rgba(0,240,255,0.45));">
        Centro de Notificaciones
      </h1>
    </div>

    <?php if (!empty($todasNotifs)): ?>
      <div>
        <a 
          href="notifications.php?action=read_all" 
          class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#140E33] border border-[#00F0FF]/40 hover:border-[#00F0FF] text-xs font-bold text-[#00F0FF] shadow-[0_0_10px_rgba(0,240,255,0.2)] transition-all"
        >
          <span class="material-symbols-outlined text-[16px]">done_all</span>
          <span>Marcar todas como leídas</span>
        </a>
      </div>
    <?php endif; ?>
  </div>

  <!-- FILTROS POR TIPO DE NOTIFICACIÓN Y2K -->
  <div class="flex items-center gap-2 mb-6 overflow-x-auto pb-2 text-xs font-bold">
    <?php
    $tipos = [
      'todas'       => 'Todas',
      'solicitud'   => '⚡ Solicitudes & Ciclo State',
      'mensaje'     => '💬 Mensajes & Chat',
      'calificacion'=> '⭐ Calificaciones',
      'sistema'     => '🖥 Sistema'
    ];
    foreach ($tipos as $k => $label):
      $active = ($filtroTipo === $k);
    ?>
      <a 
        href="notifications.php?tipo=<?php echo $k; ?>" 
        class="px-3.5 py-1.5 rounded-xl transition-all whitespace-nowrap <?php echo $active ? 'bg-gradient-to-r from-[#FF007F] to-[#7928CA] text-white shadow-[0_0_12px_rgba(255,0,127,0.4)] font-black' : 'bg-[#140E33] text-[#94A3B8] border border-[#00F0FF]/30 hover:border-[#00F0FF] hover:text-[#00F0FF]'; ?>"
      >
        <?php echo $label; ?>
      </a>
    <?php endforeach; ?>
  </div>

  <!-- LISTA DE NOTIFICACIONES Y2K -->
  <?php if (empty($notificaciones)): ?>
    <div class="text-center py-16 y2k-card border border-[#00F0FF]/30 p-8 shadow-[0_0_30px_rgba(0,240,255,0.15)]">
      <div class="w-16 h-16 rounded-full bg-[#00F0FF]/15 text-[#00F0FF] flex items-center justify-center mx-auto mb-3 border border-[#00F0FF]/40 shadow-[0_0_15px_rgba(0,240,255,0.3)]">
        <span class="material-symbols-outlined text-3xl">notifications_off</span>
      </div>
      <h3 class="text-lg font-black font-headline text-white">No tienes notificaciones en este filtro</h3>
      <p class="text-xs text-[#94A3B8] mt-1">
        Las notificaciones se crearán automáticamente cada vez que cambie el estado de tus solicitudes o recibas mensajes.
      </p>
    </div>
  <?php else: ?>
    <div class="space-y-3">
      <?php foreach ($notificaciones as $n): 
        $leida = (bool)$n['leida'];
        $tipo = $n['tipo'];
        $icono = 'notifications';
        $colorIcon = 'text-[#00F0FF]';
        $colorBorder = $leida ? 'border-[#00F0FF]/15' : 'border-[#00F0FF]/40';
        $bgCard = $leida ? 'bg-[#0C0820]/60' : 'bg-[#140E33]';
        $glowEffect = $leida ? '' : 'shadow-[0_0_20px_rgba(0,240,255,0.15)]';

        if ($tipo === 'solicitud') {
            $icono = 'sync_alt';
            $colorIcon = 'text-[#FF007F]';
            $colorBorder = $leida ? 'border-[#FF007F]/15' : 'border-[#FF007F]/40';
            $glowEffect = $leida ? '' : 'shadow-[0_0_20px_rgba(255,0,127,0.15)]';
        } elseif ($tipo === 'mensaje') {
            $icono = 'chat_bubble';
            $colorIcon = 'text-[#C084FC]';
            $colorBorder = $leida ? 'border-[#C084FC]/15' : 'border-[#C084FC]/40';
            $glowEffect = $leida ? '' : 'shadow-[0_0_20px_rgba(192,132,252,0.15)]';
        } elseif ($tipo === 'calificacion') {
            $icono = 'star';
            $colorIcon = 'text-[#FFE600]';
            $colorBorder = $leida ? 'border-[#FFE600]/15' : 'border-[#FFE600]/40';
            $glowEffect = $leida ? '' : 'shadow-[0_0_20px_rgba(255,230,0,0.15)]';
        }
      ?>
        <div class="p-4 rounded-2xl border transition-all flex items-start gap-4 <?php echo $bgCard . ' ' . $colorBorder . ' ' . $glowEffect; ?>">
          <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 border border-[#00F0FF]/20 bg-[#0C0820] <?php echo $colorIcon; ?>">
            <span class="material-symbols-outlined text-[20px]"><?php echo $icono; ?></span>
          </div>

          <div class="flex-grow min-w-0">
            <div class="flex items-center justify-between mb-1">
              <h4 class="font-bold font-headline text-sm text-white truncate">
                <?php echo htmlspecialchars($n['titulo']); ?>
              </h4>
              <span class="text-[10px] text-[#64748B] font-mono whitespace-nowrap ml-2">
                <?php echo date('d/m/Y · H:i', strtotime($n['created_at'])); ?>
              </span>
            </div>

            <p class="text-xs text-[#CBD5E1] leading-relaxed mb-2">
              <?php echo htmlspecialchars($n['mensaje']); ?>
            </p>

            <div class="flex items-center gap-3">
              <?php if (!empty($n['enlace'])): ?>
                <a 
                  href="notifications.php?read=<?php echo $n['id']; ?>&redirect=<?php echo urlencode($n['enlace']); ?>" 
                  class="text-xs font-bold text-[#00F0FF] hover:underline flex items-center gap-1"
                >
                  <span>Ver detalle</span>
                  <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                </a>
              <?php endif; ?>

              <?php if (!$leida): ?>
                <a 
                  href="notifications.php?read=<?php echo $n['id']; ?>" 
                  class="text-[11px] text-[#64748B] hover:text-[#00F0FF] transition-colors"
                >
                  Marcar leída
                </a>
              <?php endif; ?>
            </div>
          </div>

          <?php if (!$leida): ?>
            <span class="w-2.5 h-2.5 rounded-full bg-[#FF007F] shrink-0 mt-2 shadow-[0_0_8px_#FF007F]" title="No leída"></span>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

