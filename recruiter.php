<?php
/**
 * Panel de Reclutamiento & Gestión de Talento
 * Échele Camello - MySQL / XAMPP
 */

$pageTitle = "Panel de Reclutamiento Enterprise | Stark Cloud Corp";
require_once __DIR__ . '/includes/header.php';

$pdo = getDBConnection();

// Obtener candidatos desde MySQL
$stmtCand = $pdo->query("SELECT * FROM candidates ORDER BY match_score DESC");
$candidates = $stmtCand->fetchAll();

// Obtener vacantes de la empresa desde MySQL
$stmtJobs = $pdo->query("SELECT * FROM jobs WHERE company LIKE '%Stark%' OR company_id = 'stark-cloud' ORDER BY created_at DESC");
$recruiterJobs = $stmtJobs->fetchAll();
if (empty($recruiterJobs)) {
    $recruiterJobs = $pdo->query("SELECT * FROM jobs LIMIT 3")->fetchAll();
}
?>

<div class="min-h-screen bg-[#fff8f6] py-8 text-[#1f1b16]">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs sm:text-sm text-[#737373] mb-6 font-body">
      <a href="index.php" class="hover:text-[#a83301]">Inicio</a>
      <span>/</span>
      <span class="text-[#1f1b16] font-medium">Panel de Reclutamiento</span>
    </nav>

    <!-- Recruiter Header Bar -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-[#e5e5e5] mb-8">
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-5">
          <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-[#fff4f0] border border-[#ffdbd0] p-2 flex items-center justify-center shrink-0">
            <img
              src="https://lh3.googleusercontent.com/aida-public/AB6AXuDaLtyWrvTDHSisX7_s1dOIeOQaAbH_fMviGGcxP4PscKyDvneJ1dV7L4ubRpAN3xK2UWcoOp2E0ZQCtCv5mC1CpynAUHM8_aYvTtp5vbR3ED8q2ZOtrqVuGq6bhZj5zDsvkzTKeLXiGq-hxUyEYpVA7PjPAammTB7Yb75KeJTvgMf_MrLlIUVnXYFcK4RJWYp_9UFghIkZyb4mcnku1en1_PMGJ6EVKIb94TAIa-VqEJQHxdDyA-vs"
              alt="Stark Cloud"
              class="w-full h-full object-contain"
            />
          </div>
          <div>
            <div class="flex flex-wrap items-center gap-2 mb-1">
              <h1 class="text-2xl sm:text-3xl font-extrabold text-[#1f1b16] font-headline">
                Stark Cloud Corp
              </h1>
              <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#ffdbd0] text-[#a83301]">
                Plan Enterprise Reclutador
              </span>
            </div>
            <p class="text-xs sm:text-sm text-[#525252] font-body">
              Gestionando vacantes activas • Hub de Talento Medellín & LATAM • Conexión MySQL Activa
            </p>
          </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
          <a
            href="company.php"
            class="px-4 py-2.5 rounded-xl border border-[#e5e5e5] hover:bg-neutral-50 text-xs font-bold font-headline text-[#525252] transition-colors"
          >
            Ver Perfil Público
          </a>
          <a
            href="create-job.php"
            class="px-5 py-2.5 rounded-xl bg-[#a83301] hover:bg-[#ca4a1c] text-white text-xs font-bold font-headline flex items-center gap-1.5 shadow-md transition-all"
          >
            <span class="material-symbols-outlined text-sm">add_circle</span>
            Publicar Nuevo Camello
          </a>
        </div>
      </div>
    </div>

    <!-- AI Recruiter Copilot Banner -->
    <div id="copilot" class="bg-gradient-to-r from-[#1f1b16] to-[#3a2820] text-white rounded-3xl p-6 sm:p-7 shadow-lg mb-8 relative overflow-hidden">
      <div class="absolute right-0 top-0 w-80 h-full bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-[#ca4a1c]/40 via-transparent to-transparent pointer-events-none"></div>
      
      <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 relative z-10">
        <div class="flex items-start gap-4">
          <div class="w-12 h-12 rounded-2xl bg-[#ca4a1c] text-white flex items-center justify-center shrink-0 shadow-md">
            <span class="material-symbols-outlined text-2xl">smart_toy</span>
          </div>
          <div>
            <div class="flex items-center gap-2 mb-1">
              <span class="text-xs font-bold uppercase tracking-wider text-[#f5b8a5]">
                Copiloto de Contratación con IA
              </span>
              <span class="w-2 h-2 rounded-full bg-[#16a34a] animate-pulse"></span>
            </div>
            <h2 class="text-lg sm:text-xl font-bold font-headline text-white">
              3 Candidatos de alto match identificados automáticamente
            </h2>
            <p class="text-xs sm:text-sm text-neutral-300 mt-1 max-w-2xl font-body">
              Nuestro algoritmo analizó más de 400 postulaciones cruzando experiencia en TypeScript, pruebas técnicas y expectativas salariales.
            </p>
          </div>
        </div>

        <button
          onclick="runCopilotAI(this)"
          class="px-5 py-3 rounded-xl bg-[#a83301] hover:bg-[#ca4a1c] text-white font-headline text-xs sm:text-sm font-bold shadow-md transition-all flex items-center justify-center gap-2 shrink-0 active:scale-95"
        >
          <span class="material-symbols-outlined text-base">bolt</span>
          <span>Contactar Candidatos Top con IA</span>
        </button>
      </div>
    </div>

    <!-- SECCIÓN: TALENT POOL & CANDIDATOS -->
    <div class="space-y-6">
      
      <div class="flex items-center justify-between pb-3 border-b border-[#e5e5e5]">
        <div>
          <h2 class="font-headline font-bold text-xl text-[#1f1b16] flex items-center gap-2">
            <span class="material-symbols-outlined text-[#a83301]">group</span>
            Candidatos Evaluados & Match IA
          </h2>
          <p class="text-xs text-[#737373]">Base de datos de talento sincronizada en MySQL</p>
        </div>
        <span class="px-3 py-1 rounded-full bg-[#f0fdf4] text-[#16a34a] text-xs font-bold border border-[#bbf7d0]">
          <?php echo count($candidates); ?> Candidatos Prioritarios
        </span>
      </div>

      <!-- Grid de Candidatos -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php foreach ($candidates as $cand): ?>
          <?php $skills = parseList($cand['skills']); ?>
          <div class="bg-white rounded-3xl p-6 shadow-sm border border-[#e5e5e5] flex flex-col justify-between hover:border-[#a83301]/40 transition-all">
            <div>
              
              <div class="flex items-start justify-between gap-3 mb-4">
                <div class="flex items-center gap-3">
                  <img
                    src="<?php echo htmlspecialchars($cand['avatar']); ?>"
                    alt="<?php echo htmlspecialchars($cand['name']); ?>"
                    class="w-14 h-14 rounded-2xl object-cover border border-[#ffdbd0]"
                  />
                  <div>
                    <h3 class="font-headline font-bold text-base text-[#1f1b16]">
                      <?php echo htmlspecialchars($cand['name']); ?>
                    </h3>
                    <p class="text-xs text-[#525252]"><?php echo htmlspecialchars($cand['role']); ?></p>
                    <span class="text-[11px] text-[#737373]"><?php echo htmlspecialchars($cand['location']); ?></span>
                  </div>
                </div>

                <div class="px-2.5 py-1 rounded-xl bg-[#f0fdf4] text-[#16a34a] border border-[#bbf7d0] text-center shrink-0">
                  <span class="block font-black font-headline text-base leading-none"><?php echo $cand['match_score']; ?>%</span>
                  <span class="text-[9px] uppercase font-bold tracking-tight">Match</span>
                </div>
              </div>

              <!-- Expectativa & Prueba Técnica -->
              <div class="p-3 rounded-2xl bg-[#fff8f5] border border-[#ffdbd0] space-y-1.5 mb-4 text-xs font-body">
                <div class="flex justify-between">
                  <span class="text-[#737373]">Aspiración:</span>
                  <span class="font-bold text-[#a83301]"><?php echo htmlspecialchars($cand['salary_expectation']); ?></span>
                </div>
                <div class="flex justify-between">
                  <span class="text-[#737373]">Prueba Técnica:</span>
                  <span class="font-bold text-[#16a34a]"><?php echo htmlspecialchars($cand['tech_test_score']); ?></span>
                </div>
                <?php if (!empty($cand['english_level'])): ?>
                  <div class="flex justify-between">
                    <span class="text-[#737373]">Inglés:</span>
                    <span class="font-semibold text-[#1f1b16]"><?php echo htmlspecialchars($cand['english_level']); ?></span>
                  </div>
                <?php endif; ?>
              </div>

              <!-- Skills -->
              <div class="flex flex-wrap gap-1.5 mb-5">
                <?php foreach ($skills as $s): ?>
                  <span class="px-2 py-0.5 rounded-md bg-neutral-100 text-[11px] font-medium text-[#525252]">
                    <?php echo htmlspecialchars($s); ?>
                  </span>
                <?php endforeach; ?>
              </div>

            </div>

            <!-- Botones de Acción -->
            <div class="pt-4 border-t border-neutral-100 flex items-center gap-2">
              <button
                onclick="alert('Agendando entrevista técnica con <?php echo htmlspecialchars($cand['name']); ?> vía Google Calendar.')"
                class="w-full py-2.5 rounded-xl bg-[#a83301] hover:bg-[#ca4a1c] text-white text-xs font-bold font-headline transition-colors shadow-xs"
              >
                Agendar Entrevista
              </button>
            </div>

          </div>
        <?php endforeach; ?>
      </div>

      <!-- SECCIÓN: VACANTES PUBLICADAS POR LA EMPRESA -->
      <div class="pt-8 mt-12 border-t border-[#e5e5e5]">
        <div class="flex items-center justify-between mb-6">
          <h2 class="font-headline font-bold text-xl text-[#1f1b16] flex items-center gap-2">
            <span class="material-symbols-outlined text-[#a83301]">campaign</span>
            Vacantes Activas de Stark Cloud Corp en MySQL
          </h2>
          <a href="create-job.php" class="px-4 py-2 rounded-xl bg-[#a83301] text-white text-xs font-bold font-headline">
            + Publicar Otra Vacante
          </a>
        </div>

        <div class="space-y-4">
          <?php foreach ($recruiterJobs as $rjob): ?>
            <div class="bg-white rounded-2xl p-5 border border-[#e5e5e5] shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
              <div>
                <div class="flex items-center gap-2 mb-1">
                  <?php echo getUrgencyBadge($rjob['urgency']); ?>
                  <span class="text-xs text-[#737373]"><?php echo htmlspecialchars($rjob['modality']); ?> • <?php echo htmlspecialchars($rjob['posted_time']); ?></span>
                </div>
                <h3 class="font-headline font-bold text-base text-[#1f1b16]">
                  <?php echo htmlspecialchars($rjob['title']); ?>
                </h3>
                <span class="text-xs font-bold text-[#a83301]">
                  <?php echo htmlspecialchars($rjob['currency'] === 'USD' ? ($rjob['salary_usd'] ?? '') : ($rjob['salary_cop'] ?? '')); ?>
                </span>
              </div>

              <div class="flex items-center gap-3">
                <span class="text-xs font-semibold text-[#16a34a] bg-[#f0fdf4] px-3 py-1.5 rounded-xl border border-[#bbf7d0]">
                  <?php echo (int)($rjob['active_candidates'] ?? 15); ?> candidatos postulados
                </span>
                <a
                  href="job-detail.php?id=<?php echo urlencode($rjob['id']); ?>"
                  class="px-4 py-2 rounded-xl border border-neutral-300 hover:bg-neutral-50 text-xs font-bold text-[#1f1b16] transition-colors"
                >
                  Ver Vacante
                </a>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

    </div>

  </div>
</div>

<script>
function runCopilotAI(btn) {
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="material-symbols-outlined text-sm animate-spin">progress_activity</span> Analizando perfiles...';

    setTimeout(() => {
        btn.innerHTML = '<span class="material-symbols-outlined text-sm">check_circle</span> ¡Copiloto Ejecutado!';
        btn.className = 'px-5 py-3 rounded-xl bg-[#16a34a] text-white font-headline text-xs sm:text-sm font-bold shadow-md transition-all flex items-center justify-center gap-2 shrink-0';
        showToast('¡Copiloto IA ejecutado! Se notificó a los 3 candidatos con mayor match técnico.');
    }, 1000);
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
