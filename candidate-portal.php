<?php
/**
 * Mi Panel de Camellos - Candidato
 * Postulaciones activas sincronizadas con MySQL / XAMPP
 */

$pageTitle = "Mi Panel de Camellos | Postulaciones & Ofertas";
require_once __DIR__ . '/includes/header.php';

$pdo = getDBConnection();

$filterStatus = trim($_GET['status'] ?? 'all');

$sql = "SELECT * FROM applications WHERE 1=1";
$params = [];

if ($filterStatus !== 'all' && !empty($filterStatus)) {
    $sql .= " AND status = :status";
    $params[':status'] = $filterStatus;
}
$sql .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$applications = $stmt->fetchAll();

// Conteo por estado
$totalApps = $pdo->query("SELECT COUNT(*) FROM applications")->fetchColumn();
$offerCount = $pdo->query("SELECT COUNT(*) FROM applications WHERE status = 'offer'")->fetchColumn();
$interviewCount = $pdo->query("SELECT COUNT(*) FROM applications WHERE status = 'interview'")->fetchColumn();
$testCount = $pdo->query("SELECT COUNT(*) FROM applications WHERE status = 'tech_test'")->fetchColumn();
$reviewCount = $pdo->query("SELECT COUNT(*) FROM applications WHERE status = 'review'")->fetchColumn();
?>

<div class="min-h-screen bg-[#fff8f6] py-8 text-[#1f1b16]">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs sm:text-sm text-[#737373] mb-6 font-body">
      <a href="index.php" class="hover:text-[#a83301]">Inicio</a>
      <span>/</span>
      <span class="text-[#1f1b16] font-medium">Mi Panel de Camellos</span>
    </nav>

    <!-- User Profile Header -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-[#e5e5e5] mb-8">
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-5">
          <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-gradient-to-tr from-[#a83301] to-[#ca4a1c] text-white flex items-center justify-center font-bold text-2xl shadow-md shrink-0">
            CP
          </div>
          <div>
            <div class="flex items-center gap-2 mb-1">
              <h1 class="text-2xl sm:text-3xl font-extrabold text-[#1f1b16] font-headline">
                Camilo Pérez
              </h1>
              <span class="material-symbols-outlined text-[#0284c7] text-xl" title="Perfil Verificado">
                verified
              </span>
            </div>
            <p class="text-xs sm:text-sm text-[#525252] font-body">
              Senior Frontend Developer • Medellín, Colombia (Disponible Inmediato)
            </p>
            <div class="flex flex-wrap items-center gap-3 mt-2">
              <span class="inline-flex items-center gap-1 text-xs font-semibold text-[#16a34a] bg-[#f0fdf4] px-2.5 py-0.5 rounded-full border border-[#bbf7d0]">
                <span class="w-2 h-2 rounded-full bg-[#16a34a] animate-pulse"></span>
                Buscando camello activamente
              </span>
              <span class="text-xs text-[#737373]">
                CV sincronizado con IA • Base de Datos MySQL
              </span>
            </div>
          </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
          <a
            href="salary-calculator.php"
            class="px-4 py-2.5 rounded-xl border border-[#e5e5e5] hover:bg-neutral-50 text-xs font-bold font-headline text-[#525252] flex items-center gap-1.5 transition-colors"
          >
            <span class="material-symbols-outlined text-sm text-[#a83301]">calculate</span>
            Calculadora Salarial
          </a>
          <a
            href="explorer.php"
            class="px-5 py-2.5 rounded-xl bg-[#a83301] hover:bg-[#ca4a1c] text-white text-xs font-bold font-headline flex items-center gap-1.5 shadow-md transition-all"
          >
            <span class="material-symbols-outlined text-sm">search</span>
            Explorar Nuevas Vacantes
          </a>
        </div>
      </div>
    </div>

    <!-- Pestañas de Filtros de Postulaciones -->
    <div class="flex items-center gap-2 overflow-x-auto pb-4 mb-6">
      <a href="candidate-portal.php" class="px-4 py-2 rounded-xl text-xs font-bold font-headline transition-all whitespace-nowrap <?php echo $filterStatus === 'all' ? 'bg-[#a83301] text-white shadow-xs' : 'bg-white text-[#525252] border border-[#e5e5e5] hover:bg-neutral-50'; ?>">
        Todas (<?php echo $totalApps; ?>)
      </a>
      <a href="candidate-portal.php?status=offer" class="px-4 py-2 rounded-xl text-xs font-bold font-headline transition-all whitespace-nowrap <?php echo $filterStatus === 'offer' ? 'bg-[#a83301] text-white shadow-xs' : 'bg-white text-[#525252] border border-[#e5e5e5] hover:bg-neutral-50'; ?>">
        Ofertas Formales (<?php echo $offerCount; ?>)
      </a>
      <a href="candidate-portal.php?status=interview" class="px-4 py-2 rounded-xl text-xs font-bold font-headline transition-all whitespace-nowrap <?php echo $filterStatus === 'interview' ? 'bg-[#a83301] text-white shadow-xs' : 'bg-white text-[#525252] border border-[#e5e5e5] hover:bg-neutral-50'; ?>">
        Entrevistas (<?php echo $interviewCount; ?>)
      </a>
      <a href="candidate-portal.php?status=tech_test" class="px-4 py-2 rounded-xl text-xs font-bold font-headline transition-all whitespace-nowrap <?php echo $filterStatus === 'tech_test' ? 'bg-[#a83301] text-white shadow-xs' : 'bg-white text-[#525252] border border-[#e5e5e5] hover:bg-neutral-50'; ?>">
        Pruebas Técnicas (<?php echo $testCount; ?>)
      </a>
      <a href="candidate-portal.php?status=review" class="px-4 py-2 rounded-xl text-xs font-bold font-headline transition-all whitespace-nowrap <?php echo $filterStatus === 'review' ? 'bg-[#a83301] text-white shadow-xs' : 'bg-white text-[#525252] border border-[#e5e5e5] hover:bg-neutral-50'; ?>">
        En Revisión (<?php echo $reviewCount; ?>)
      </a>
    </div>

    <!-- Listado de Postulaciones -->
    <div class="space-y-6">
      <?php if (empty($applications)): ?>
        <div class="bg-white rounded-3xl p-12 text-center border border-[#e5e5e5]">
          <p class="text-sm text-[#737373] mb-4">No hay postulaciones registradas en esta categoría.</p>
          <a href="explorer.php" class="px-5 py-2.5 bg-[#a83301] text-white text-xs font-bold rounded-xl">Explorar Vacantes</a>
        </div>
      <?php else: ?>
        <?php foreach ($applications as $app): ?>
          <?php 
            $tags = parseList($app['tags']);
            $percent = (int)($app['progress_percent'] ?? 25);
          ?>
          <div class="bg-white rounded-3xl p-6 sm:p-7 shadow-sm border border-[#e5e5e5] transition-all hover:border-[#a83301]/30">
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
              
              <!-- Info del empleo -->
              <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-2xl bg-[#fff4f0] border border-[#ffdbd0] p-1.5 flex items-center justify-center shrink-0">
                  <?php if (!empty($app['company_logo'])): ?>
                    <img src="<?php echo htmlspecialchars($app['company_logo']); ?>" alt="<?php echo htmlspecialchars($app['company']); ?>" class="w-full h-full object-contain rounded-lg" />
                  <?php else: ?>
                    <span class="font-headline font-bold text-lg text-[#a83301]"><?php echo strtoupper(substr($app['company'], 0, 2)); ?></span>
                  <?php endif; ?>
                </div>

                <div>
                  <div class="flex flex-wrap items-center gap-2 mb-1.5">
                    <span class="text-xs font-bold text-[#1f1b18]"><?php echo htmlspecialchars($app['company']); ?></span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold <?php echo $app['status'] === 'offer' ? 'bg-[#f0fdf4] text-[#16a34a] border border-[#bbf7d0]' : ($app['status'] === 'interview' ? 'bg-[#eff6ff] text-[#0284c7] border border-[#bfdbfe]' : 'bg-[#fff4f0] text-[#a83301] border border-[#ffdbd0]'); ?>">
                      <?php echo htmlspecialchars($app['status_label']); ?>
                    </span>
                  </div>

                  <a href="job-detail.php?id=<?php echo urlencode($app['job_id']); ?>">
                    <h3 class="font-headline text-lg sm:text-xl font-bold text-[#1f1b16] hover:text-[#a83301] transition-colors">
                      <?php echo htmlspecialchars($app['title']); ?>
                    </h3>
                  </a>

                  <div class="flex flex-wrap items-center gap-4 text-xs text-[#737373] mt-1 font-body">
                    <span><?php echo htmlspecialchars($app['location']); ?></span>
                    <span>•</span>
                    <span class="font-bold text-[#a83301]"><?php echo htmlspecialchars($app['salary_display']); ?></span>
                    <span>•</span>
                    <span>Postulado hace <?php echo (int)$app['applied_days_ago']; ?> días</span>
                  </div>

                  <!-- Tags -->
                  <div class="flex flex-wrap gap-1.5 mt-3">
                    <?php foreach ($tags as $t): ?>
                      <span class="px-2 py-0.5 rounded bg-neutral-100 text-[11px] font-medium text-[#525252]">
                        <?php echo htmlspecialchars($t); ?>
                      </span>
                    <?php endforeach; ?>
                  </div>
                </div>
              </div>

              <!-- Barra de Progreso y Estado -->
              <div class="md:w-64 shrink-0 bg-neutral-50 p-4 rounded-2xl border border-neutral-200">
                <div class="flex items-center justify-between text-xs font-bold mb-1.5">
                  <span class="text-[#1f1b16]"><?php echo htmlspecialchars($app['stage_progress']); ?></span>
                  <span class="text-[#a83301]"><?php echo $percent; ?>%</span>
                </div>
                <div class="w-full h-2 rounded-full bg-neutral-200 overflow-hidden">
                  <div class="h-full bg-gradient-to-r from-[#ca4a1c] to-[#a83301] rounded-full" style="width: <?php echo $percent; ?>%;"></div>
                </div>
              </div>

            </div>

            <!-- Banner de Acción Requerida (Si Aplica) -->
            <?php if (!empty($app['action_title']) || $app['status'] === 'offer'): ?>
              <div class="mt-5 p-4 rounded-2xl <?php echo $app['status'] === 'offer' ? 'bg-[#fff4f0] border border-[#ffdbd0]' : 'bg-neutral-50 border border-neutral-200'; ?> flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-start gap-3">
                  <span class="material-symbols-outlined <?php echo $app['status'] === 'offer' ? 'text-[#a83301]' : 'text-[#0284c7]'; ?> text-xl shrink-0 mt-0.5">
                    <?php echo $app['status'] === 'offer' ? 'workspace_premium' : 'info'; ?>
                  </span>
                  <div>
                    <h4 class="font-headline font-bold text-xs sm:text-sm text-[#1f1b16]">
                      <?php echo htmlspecialchars($app['action_title'] ?? 'Carta de oferta formal lista'); ?>
                    </h4>
                    <p class="text-xs text-[#525252] mt-0.5">
                      <?php echo htmlspecialchars($app['action_description'] ?? 'Revisa las condiciones legales y salario antes de la fecha límite.'); ?>
                    </p>
                  </div>
                </div>

                <?php if ($app['status'] === 'offer'): ?>
                  <button
                    onclick="openOfferModal('<?php echo $app['id']; ?>', '<?php echo htmlspecialchars($app['company']); ?>', '<?php echo htmlspecialchars($app['salary_display']); ?>')"
                    class="px-4 py-2 rounded-xl bg-[#a83301] hover:bg-[#ca4a1c] text-white text-xs font-bold font-headline shrink-0 shadow-sm transition-all"
                  >
                    Revisar & Firmar Oferta
                  </button>
                <?php elseif ($app['status'] === 'interview'): ?>
                  <button
                    onclick="alert('Conectando con Google Meet: https://meet.google.com/cam-nubank-latam')"
                    class="px-4 py-2 rounded-xl bg-[#0284c7] hover:bg-[#0369a1] text-white text-xs font-bold font-headline shrink-0 shadow-sm transition-all flex items-center gap-1.5"
                  >
                    <span class="material-symbols-outlined text-sm">video_call</span>
                    <span>Unirme a la Videollamada</span>
                  </button>
                <?php endif; ?>
              </div>
            <?php endif; ?>

          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

  </div>
</div>

<!-- MODAL PARA REVISAR Y FIRMAR OFERTA -->
<div id="offer-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm hidden animate-fade-in">
  <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-[#e5e5e5] relative animate-scale-up">
    
    <div class="flex items-center justify-between pb-4 border-b border-[#f0f0f0] mb-5">
      <div class="flex items-center gap-2">
        <span class="material-symbols-outlined text-[#a83301] text-2xl">verified</span>
        <h3 class="font-bold font-headline text-lg sm:text-xl text-[#1f1b16]">
          Carta de Oferta Laboral Formal
        </h3>
      </div>
      <button onclick="closeOfferModal()" class="w-8 h-8 rounded-full hover:bg-neutral-100 flex items-center justify-center text-[#737373]">
        <span class="material-symbols-outlined text-lg">close</span>
      </button>
    </div>

    <div class="space-y-4 font-body text-xs sm:text-sm text-[#525252] mb-6">
      <p>
        Estimado/a <strong>Camilo Pérez</strong>, nos complace extenderle formalmente esta oferta para unirse a <span id="modal-offer-company" class="font-bold text-[#1f1b16]">Stark Cloud Corp</span>.
      </p>

      <div class="p-4 rounded-2xl bg-[#fff8f5] border border-[#ffdbd0] space-y-2">
        <div class="flex justify-between">
          <span class="text-[#737373]">Compensación Neta:</span>
          <span id="modal-offer-salary" class="font-bold text-[#a83301]">USD $4,200 / mes</span>
        </div>
        <div class="flex justify-between">
          <span class="text-[#737373]">Modalidad:</span>
          <span class="font-bold text-[#1f1b16]">100% Remoto (LATAM)</span>
        </div>
        <div class="flex justify-between">
          <span class="text-[#737373]">Vacaciones:</span>
          <span class="font-bold text-[#1f1b16]">25 Días PTO Libres</span>
        </div>
        <div class="flex justify-between">
          <span class="text-[#737373]">Equipo provisto:</span>
          <span class="font-bold text-[#1f1b16]">Apple MacBook Pro M3 Max</span>
        </div>
      </div>

      <p class="text-[11px] text-[#737373] italic">
        Al hacer clic en "Aceptar y Firmar Electrónicamente", se registrará la firma criptográfica en la base de datos MySQL y se iniciará su proceso de onboarding.
      </p>
    </div>

    <div class="flex items-center gap-3">
      <button onclick="closeOfferModal()" class="w-1/3 py-3 rounded-xl bg-neutral-100 text-xs font-bold text-[#1f1b16]">
        Cerrar
      </button>
      <button
        id="btn-sign-offer"
        onclick="signOfferConfirm()"
        class="w-2/3 py-3 rounded-xl bg-[#16a34a] hover:bg-[#15803d] text-white font-bold font-headline text-xs sm:text-sm shadow-md transition-all flex items-center justify-center gap-2"
      >
        <span class="material-symbols-outlined text-sm">draw</span>
        <span>Aceptar y Firmar Oferta</span>
      </button>
    </div>

  </div>
</div>

<script>
let currentAppId = '';

function openOfferModal(appId, company, salary) {
    currentAppId = appId;
    document.getElementById('modal-offer-company').textContent = company;
    document.getElementById('modal-offer-salary').textContent = salary;
    document.getElementById('offer-modal').classList.remove('hidden');
}

function closeOfferModal() {
    document.getElementById('offer-modal').classList.add('hidden');
}

async function signOfferConfirm() {
    const btn = document.getElementById('btn-sign-offer');
    btn.disabled = true;
    btn.innerHTML = '<span class="material-symbols-outlined text-sm animate-spin">progress_activity</span> Firmando...';

    try {
        const formData = new FormData();
        formData.append('app_id', currentAppId);
        formData.append('action', 'sign_offer');

        const res = await fetch('api/update_app.php', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        if (data.success) {
            closeOfferModal();
            showToast('¡Oferta firmada con éxito en la base de datos MySQL!');
            setTimeout(() => {
                window.location.reload();
            }, 1200);
        } else {
            alert(data.message || 'Error al firmar la oferta');
            btn.disabled = false;
            btn.innerHTML = 'Aceptar y Firmar Oferta';
        }
    } catch (err) {
        alert('Error al conectar con la base de datos MySQL.');
        btn.disabled = false;
        btn.innerHTML = 'Aceptar y Firmar Oferta';
    }
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
