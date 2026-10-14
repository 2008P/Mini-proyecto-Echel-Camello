<?php
/**
 * Detalle del Empleo - Échele Camello
 * Conexión y consulta a MySQL / XAMPP
 */

require_once __DIR__ . '/includes/functions.php';

$pdo = getDBConnection();

$jobId = trim($_GET['id'] ?? 'stark-frontend-senior');

$stmt = $pdo->prepare("SELECT * FROM jobs WHERE id = ?");
$stmt->execute([$jobId]);
$job = $stmt->fetch();

if (!$job) {
    // Si no se encuentra el ID, tomemos el primero de la base de datos
    $job = $pdo->query("SELECT * FROM jobs ORDER BY created_at DESC LIMIT 1")->fetch();
}

$pageTitle = $job['title'] . " en " . $job['company'];
require_once __DIR__ . '/includes/header.php';

$responsibilities = parseList($job['responsibilities']);
$requirements = parseList($job['requirements']);
$perks = parseList($job['perks']);
$tags = parseList($job['tags']);
$isSaved = isJobSaved($job['id']);
?>

<div class="min-h-screen bg-[#fff8f6] py-8 text-[#1f1b16]">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs sm:text-sm text-[#737373] mb-6 font-body">
      <a href="index.php" class="hover:text-[#a83301] transition-colors">Inicio</a>
      <span>/</span>
      <a href="explorer.php?category=<?php echo urlencode($job['category']); ?>" class="hover:text-[#a83301] transition-colors">
        <?php echo htmlspecialchars($job['category']); ?>
      </a>
      <span>/</span>
      <span class="text-[#1f1b16] font-medium truncate max-w-xs sm:max-w-md">
        <?php echo htmlspecialchars($job['title']); ?>
      </span>
    </nav>

    <!-- Hero Card de la Vacante -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-[#e5e5e5] mb-8 relative overflow-hidden">
      <div class="absolute top-0 right-0 w-80 h-80 bg-gradient-to-bl from-[#ffdbd0]/40 via-transparent to-transparent rounded-bl-full pointer-events-none"></div>

      <div class="flex flex-col md:flex-row md:items-start justify-between gap-6 relative z-10">
        
        <div class="flex items-start gap-4 sm:gap-6">
          <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-[#fdf2ef] border border-[#f5b8a5] p-2 flex items-center justify-center shrink-0 shadow-sm">
            <?php if (!empty($job['company_logo'])): ?>
              <img src="<?php echo htmlspecialchars($job['company_logo']); ?>" alt="<?php echo htmlspecialchars($job['company']); ?>" class="w-full h-full object-contain rounded-lg" />
            <?php else: ?>
              <span class="font-headline font-bold text-2xl text-[#a83301]"><?php echo strtoupper(substr($job['company'], 0, 2)); ?></span>
            <?php endif; ?>
          </div>

          <div>
            <div class="flex flex-wrap items-center gap-2 mb-2">
              <a href="company.php" class="text-base sm:text-lg font-bold text-[#1f1b18] hover:text-[#a83301] transition-colors font-headline flex items-center gap-1.5">
                <?php echo htmlspecialchars($job['company']); ?>
                <?php if ($job['verified_company']): ?>
                  <span class="material-symbols-outlined text-[#0284c7] text-base" title="Empresa Verificada">verified</span>
                <?php endif; ?>
              </a>
              <?php echo getUrgencyBadge($job['urgency']); ?>
              <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#f0fdf4] text-[#16a34a] border border-[#bbf7d0]">
                Respuesta garantizada en <?php echo htmlspecialchars($job['response_time'] ?? '< 48h'); ?>
              </span>
            </div>

            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#1f1b18] font-headline tracking-tight mb-3">
              <?php echo htmlspecialchars($job['title']); ?>
            </h1>

            <div class="flex flex-wrap items-center gap-4 text-xs sm:text-sm text-[#525252] font-body">
              <span class="flex items-center gap-1">
                <span class="material-symbols-outlined text-base text-[#a83301]">location_on</span>
                <?php echo htmlspecialchars($job['location']); ?>
              </span>
              <span class="flex items-center gap-1">
                <span class="material-symbols-outlined text-base text-[#ca4a1c]">schedule</span>
                <?php echo htmlspecialchars($job['posted_time'] ?? 'Reciente'); ?>
              </span>
              <span class="flex items-center gap-1">
                <span class="material-symbols-outlined text-base text-[#16a34a]">group</span>
                <?php echo (int)($job['active_candidates'] ?? 24); ?> camelladores en proceso
              </span>
            </div>
          </div>
        </div>

        <!-- Caja de Salario & Botones de Acción -->
        <div class="bg-[#fff4f0] border border-[#f5b8a5] p-5 rounded-2xl flex flex-col md:w-80 shrink-0">
          <div class="flex items-center justify-between mb-1">
            <span class="text-xs uppercase tracking-wider text-[#737373] font-semibold">
              Remuneración Neta
            </span>
            <!-- Currency selector buttons -->
            <div class="flex bg-white rounded-lg p-0.5 border border-[#e5e5e5] text-xs font-bold">
              <button id="btn-curr-usd" onclick="switchCurrency('USD')" class="px-2.5 py-0.5 rounded bg-[#a83301] text-white transition-colors">USD</button>
              <button id="btn-curr-cop" onclick="switchCurrency('COP')" class="px-2.5 py-0.5 rounded text-[#737373] hover:text-black transition-colors">COP</button>
            </div>
          </div>

          <div id="salary-display-amount" class="text-2xl sm:text-3xl font-black text-[#a83301] font-headline tracking-tight my-1">
            <?php echo htmlspecialchars($job['salary_usd'] ?? ($job['salary_cop'] ?? 'USD $3,500')); ?>
          </div>
          <p class="text-xs text-[#737373] font-body mb-4">
            <?php echo htmlspecialchars($job['contract_type']); ?> • Sin letra chica
          </p>

          <div class="flex flex-col gap-2">
            <button
              id="btn-open-apply"
              onclick="openApplyModal()"
              class="w-full py-3 px-4 rounded-xl bg-[#a83301] hover:bg-[#ca4a1c] text-white font-bold font-headline text-sm transition-all shadow-md flex items-center justify-center gap-2 active:scale-95"
            >
              <span class="material-symbols-outlined text-base">send</span>
              <span>Postularme en 1 Clic</span>
            </button>

            <button
              onclick="toggleBookmark('<?php echo $job['id']; ?>', this)"
              class="w-full py-2.5 px-4 rounded-xl border border-[#e5e5e5] hover:bg-white text-xs font-bold font-headline text-[#525252] flex items-center justify-center gap-1.5 transition-colors"
            >
              <span class="material-symbols-outlined text-sm <?php echo $isSaved ? 'text-[#a83301]' : ''; ?>">
                <?php echo $isSaved ? 'bookmark' : 'bookmark_border'; ?>
              </span>
              <span><?php echo $isSaved ? 'Guardada en Favoritos' : 'Guardar vacante'; ?></span>
            </button>
          </div>
        </div>

      </div>
    </div>

    <!-- Estructura de Contenido: 2 Columnas -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
      
      <!-- Columna Principal (8 cols) -->
      <div class="lg:col-span-8 space-y-8">
        
        <!-- Descripción -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-[#e5e5e5]">
          <h2 class="text-xl font-bold text-[#1f1b16] font-headline mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-[#a83301]">description</span>
            Descripción del Rol & Retos
          </h2>
          <p class="text-sm sm:text-base text-[#525252] leading-relaxed font-body">
            <?php echo nl2br(htmlspecialchars($job['description'])); ?>
          </p>
        </div>

        <!-- Responsabilidades -->
        <?php if (!empty($responsibilities)): ?>
          <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-[#e5e5e5]">
            <h2 class="text-xl font-bold text-[#1f1b16] font-headline mb-4 flex items-center gap-2">
              <span class="material-symbols-outlined text-[#a83301]">checklist</span>
              Responsabilidades Clave
            </h2>
            <ul class="space-y-3 font-body text-sm sm:text-base text-[#525252]">
              <?php foreach ($responsibilities as $resp): ?>
                <li class="flex items-start gap-3">
                  <span class="material-symbols-outlined text-[#a83301] text-lg shrink-0 mt-0.5">check_circle</span>
                  <span><?php echo htmlspecialchars($resp); ?></span>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <!-- Requisitos -->
        <?php if (!empty($requirements)): ?>
          <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-[#e5e5e5]">
            <h2 class="text-xl font-bold text-[#1f1b16] font-headline mb-4 flex items-center gap-2">
              <span class="material-symbols-outlined text-[#a83301]">verified_user</span>
              Requisitos del Perfil
            </h2>
            <ul class="space-y-3 font-body text-sm sm:text-base text-[#525252]">
              <?php foreach ($requirements as $req): ?>
                <li class="flex items-start gap-3">
                  <span class="material-symbols-outlined text-[#0284c7] text-lg shrink-0 mt-0.5">arrow_right_alt</span>
                  <span><?php echo htmlspecialchars($req); ?></span>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <!-- Perks & Beneficios -->
        <?php if (!empty($perks)): ?>
          <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-[#e5e5e5]">
            <h2 class="text-xl font-bold text-[#1f1b16] font-headline mb-4 flex items-center gap-2">
              <span class="material-symbols-outlined text-[#a83301]">featured_seasonal_and_gifts</span>
              Beneficios & Perks Ofrecidos
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <?php foreach ($perks as $perk): ?>
                <div class="p-3.5 rounded-2xl bg-[#fff4f0] border border-[#ffdbd0] flex items-center gap-3">
                  <span class="material-symbols-outlined text-[#a83301] text-xl">star</span>
                  <span class="text-xs sm:text-sm font-semibold text-[#1f1b16]"><?php echo htmlspecialchars($perk); ?></span>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>

      </div>

      <!-- Barra Lateral Derecha (4 cols) -->
      <div class="lg:col-span-4 space-y-6">
        
        <!-- Líder de Contratación (Hiring Manager) -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-[#e5e5e5]">
          <h3 class="font-headline font-bold text-sm text-[#1f1b16] uppercase tracking-wider mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-[#a83301] text-base">support_agent</span>
            Líder de Contratación
          </h3>

          <div class="flex items-center gap-3.5 mb-3">
            <img
              src="<?php echo htmlspecialchars($job['hiring_manager_avatar'] ?: 'https://lh3.googleusercontent.com/aida-public/AB6AXuCxhpvIhC62GScpDBqJDz_M-HTOg6puaYp1e93ppGV5mX8gIDuiuXsNBdTwNFnP6TRdojQWpFu2ye9aQRtbhzvhGXBaPFClg0FIfoMIoxbqWChig63FqopL1zedE0zUDxliqmtMaaa3qVv8yJ9tWs9idNRp1KcKRNE9whkvtDGW-zvNZm-Zexs8fxOR5gNKtxuHwQdY8KaaSGms-UXAPEYY9USuYXAOJLt-p6-GyBk00sVoy8ZA8GZx'); ?>"
              alt="Hiring Manager"
              class="w-12 h-12 rounded-2xl object-cover border border-[#e5e5e5]"
            />
            <div>
              <h4 class="font-headline font-bold text-sm text-[#1f1b16]">
                <?php echo htmlspecialchars($job['hiring_manager_name'] ?: 'Mateo Valencia'); ?>
              </h4>
              <p class="text-xs text-[#525252]">
                <?php echo htmlspecialchars($job['hiring_manager_role'] ?: 'VP of Engineering'); ?>
              </p>
            </div>
          </div>

          <p class="text-xs text-[#737373] leading-relaxed mb-4">
            "Buscamos personas autónomas con criterio arquitectónico. Revisamos cada postulación y garantizamos respuesta técnica rápida."
          </p>

          <div class="p-3 bg-[#f0fdf4] rounded-xl border border-[#bbf7d0] text-xs text-[#16a34a] font-semibold flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">schedule</span>
            <span>Responde normalmente en menos de 24 horas</span>
          </div>
        </div>

        <!-- Garantía de Transparencia Échele Camello -->
        <div class="bg-[#fff4f0] rounded-3xl p-6 border border-[#ffdbd0]">
          <div class="flex items-center gap-2 text-[#a83301] font-bold text-sm mb-2">
            <span class="material-symbols-outlined">security</span>
            <span>Garantía Anti-Cobros</span>
          </div>
          <p class="text-xs text-[#525252] leading-relaxed">
            En <strong>Échele Camello</strong> todas las vacantes publicadas son verificadas. <em>Ninguna empresa puede cobrarle por exámenes médicos, certificaciones ni capacitaciones</em>. ¡Si le piden plata, repórtela!
          </p>
        </div>

      </div>

    </div>

  </div>
</div>

<!-- MODAL INTERACTIVO DE POSTULACIÓN -->
<div id="apply-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm hidden animate-fade-in">
  <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-[#e5e5e5] relative animate-scale-up">
    
    <div class="flex items-center justify-between pb-4 border-b border-[#f0f0f0] mb-5">
      <div>
        <span class="text-[10px] font-bold uppercase tracking-wider text-[#a83301]">Postulación Inmediata</span>
        <h3 class="font-bold font-headline text-lg sm:text-xl text-[#1f1b16]">
          Confirmar Aplicación a la Vacante
        </h3>
      </div>
      <button onclick="closeApplyModal()" class="w-8 h-8 rounded-full hover:bg-neutral-100 flex items-center justify-center text-[#737373]">
        <span class="material-symbols-outlined text-lg">close</span>
      </button>
    </div>

    <!-- Perfil del Candidato Autocompletado -->
    <div class="p-4 rounded-2xl bg-[#fff8f5] border border-[#ffdbd0] mb-5 flex items-center gap-4">
      <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-[#a83301] to-[#ca4a1c] text-white flex items-center justify-center font-bold text-lg shadow-sm">
        CP
      </div>
      <div class="flex-1 min-w-0">
        <h4 class="font-headline font-bold text-sm text-[#1f1b16] flex items-center gap-1">
          Camilo Pérez
          <span class="material-symbols-outlined text-[#0284c7] text-sm">verified</span>
        </h4>
        <p class="text-xs text-[#525252] truncate">camilo.perez@echelecamello.co • +57 312 456 7890</p>
        <span class="text-[10px] text-[#16a34a] font-semibold">CV Sincronizado y Verificado con IA</span>
      </div>
    </div>

    <form id="apply-form" onsubmit="handleApplySubmit(event)" class="space-y-4">
      <input type="hidden" name="job_id" value="<?php echo htmlspecialchars($job['id']); ?>" />
      <input type="hidden" name="name" value="Camilo Pérez" />
      <input type="hidden" name="email" value="camilo.perez@echelecamello.co" />
      <input type="hidden" name="phone" value="+57 312 456 7890" />

      <div>
        <label class="block text-xs font-bold text-[#1f1b16] uppercase tracking-wider mb-1.5">
          Nota de presentación para el equipo
        </label>
        <textarea
          name="note"
          rows="3"
          class="w-full p-3 rounded-xl border border-neutral-200 text-xs text-[#1f1b16] focus:border-[#a83301] focus:outline-none"
        >¡Hola equipo de <?php echo htmlspecialchars($job['company']); ?>! Cuento con más de 5 años en producción creando arquitecturas escalables. Me interesa mucho el rol y quedo a disposición para conversar.</textarea>
      </div>

      <div class="pt-2 flex items-center gap-3">
        <button
          type="button"
          onclick="closeApplyModal()"
          class="w-1/3 py-3 rounded-xl bg-neutral-100 hover:bg-neutral-200 text-xs font-bold text-[#1f1b16] transition-colors"
        >
          Cancelar
        </button>
        <button
          type="submit"
          id="btn-confirm-apply"
          class="w-2/3 py-3 rounded-xl bg-[#a83301] hover:bg-[#ca4a1c] text-white font-bold font-headline text-xs sm:text-sm shadow-md transition-all flex items-center justify-center gap-2"
        >
          <span class="material-symbols-outlined text-sm">check_circle</span>
          <span>Enviar Postulación Ahora</span>
        </button>
      </div>
    </form>

  </div>
</div>

<script>
const salaryUSD = "<?php echo htmlspecialchars($job['salary_usd'] ?? 'USD $3,500'); ?>";
const salaryCOP = "<?php echo htmlspecialchars($job['salary_cop'] ?? '$14.000.000 COP'); ?>";

function switchCurrency(curr) {
    const display = document.getElementById('salary-display-amount');
    const btnUSD = document.getElementById('btn-curr-usd');
    const btnCOP = document.getElementById('btn-curr-cop');

    if (curr === 'USD') {
        display.textContent = salaryUSD;
        btnUSD.className = 'px-2.5 py-0.5 rounded bg-[#a83301] text-white transition-colors';
        btnCOP.className = 'px-2.5 py-0.5 rounded text-[#737373] hover:text-black transition-colors';
    } else {
        display.textContent = salaryCOP;
        btnCOP.className = 'px-2.5 py-0.5 rounded bg-[#a83301] text-white transition-colors';
        btnUSD.className = 'px-2.5 py-0.5 rounded text-[#737373] hover:text-black transition-colors';
    }
}

function openApplyModal() {
    document.getElementById('apply-modal').classList.remove('hidden');
}

function closeApplyModal() {
    document.getElementById('apply-modal').classList.add('hidden');
}

async function handleApplySubmit(e) {
    e.preventDefault();
    const btn = document.getElementById('btn-confirm-apply');
    btn.disabled = true;
    btn.innerHTML = '<span class="material-symbols-outlined text-sm animate-spin">progress_activity</span> Enviando...';

    const form = document.getElementById('apply-form');
    const formData = new FormData(form);

    try {
        const res = await fetch('api/apply.php', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        if (data.success) {
            closeApplyModal();
            const openBtn = document.getElementById('btn-open-apply');
            openBtn.className = 'w-full py-3 px-4 rounded-xl bg-[#f0fdf4] text-[#16a34a] border border-[#bbf7d0] font-bold font-headline text-sm flex items-center justify-center gap-2 cursor-default';
            openBtn.innerHTML = '<span class="material-symbols-outlined text-base">check_circle</span> ¡Postulación Enviada!';
            openBtn.disabled = true;

            showToast('¡Postulación registrada en la base de datos MySQL!');
        } else {
            alert(data.message || 'Error al postularse');
            btn.disabled = false;
            btn.innerHTML = 'Enviar Postulación Ahora';
        }
    } catch (err) {
        alert('Error de red al conectar con el servidor PHP');
        btn.disabled = false;
        btn.innerHTML = 'Enviar Postulación Ahora';
    }
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
