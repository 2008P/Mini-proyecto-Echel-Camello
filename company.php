<?php
/**
 * Perfil Público de Empresa - Stark Cloud Corp
 * Échele Camello - MySQL / XAMPP
 */

$pageTitle = "Stark Cloud Corp | Empresa Verificada";
require_once __DIR__ . '/includes/header.php';

$pdo = getDBConnection();

$stmtCompany = $pdo->prepare("SELECT * FROM companies WHERE id = 'stark-cloud' LIMIT 1");
$stmtCompany->execute();
$company = $stmtCompany->fetch();

if (!$company) {
    $company = [
        'name' => 'Stark Cloud Corp',
        'industry' => 'Cloud Computing & AI Infrastructure',
        'location' => 'Medellín / San Francisco (100% Remoto)',
        'website' => 'https://starkcloud.io',
        'employees' => '250 - 500 colaboradores',
        'about' => 'En Stark Cloud Corp aceleramos la orquestación multi-cloud, el despliegue serverless perimetral y la infraestructura para modelos de Inteligencia Artificial generativa. Creemos en el trabajo asíncrono, los salarios en moneda dura y la autonomía técnica.',
        'culture' => 'Cultura remota global con horarios flexibles, enfoque en resultados claros, sin micromanagement y con presupuesto anual para educación y conferencias internacionales.',
        'perks' => 'Salario en USD directo vía Deel/Wise, MacBook Pro M3 Max, 25 días PTO libres, Seguro médico internacional 100%, Bono de Home Office de USD $1,000'
    ];
}

$stmtJobs = $pdo->query("SELECT * FROM jobs WHERE company LIKE '%Stark%' OR company_id = 'stark-cloud' ORDER BY created_at DESC");
$companyJobs = $stmtJobs->fetchAll();
$companyPerks = parseList($company['perks'] ?? '');
?>

<div class="min-h-screen bg-[#fff8f6] py-8 text-[#1f1b16]">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs sm:text-sm text-[#737373] mb-6 font-body">
      <a href="index.php" class="hover:text-[#a83301]">Inicio</a>
      <span>/</span>
      <a href="explorer.php" class="hover:text-[#a83301]">Empresas Verificadas</a>
      <span>/</span>
      <span class="text-[#1f1b16] font-medium"><?php echo htmlspecialchars($company['name']); ?></span>
    </nav>

    <!-- Company Cover & Header Card -->
    <div class="bg-white rounded-3xl overflow-hidden shadow-sm border border-[#e5e5e5] mb-8">
      
      <!-- Banner -->
      <div class="h-44 sm:h-56 bg-gradient-to-r from-[#1f1b16] via-[#3a2820] to-[#ca4a1c] relative">
        <div class="absolute inset-0 bg-black/20"></div>
      </div>

      <!-- Profile Info Bar -->
      <div class="p-6 sm:p-8 relative">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6 -mt-16 sm:-mt-20 mb-6">
          
          <div class="flex items-end gap-5">
            <div class="w-20 h-20 sm:w-28 sm:h-28 rounded-3xl bg-white p-2.5 shadow-xl border border-[#e5e5e5] shrink-0">
              <img
                src="https://lh3.googleusercontent.com/aida-public/AB6AXuDaLtyWrvTDHSisX7_s1dOIeOQaAbH_fMviGGcxP4PscKyDvneJ1dV7L4ubRpAN3xK2UWcoOp2E0ZQCtCv5mC1CpynAUHM8_aYvTtp5vbR3ED8q2ZOtrqVuGq6bhZj5zDsvkzTKeLXiGq-hxUyEYpVA7PjPAammTB7Yb75KeJTvgMf_MrLlIUVnXYFcK4RJWYp_9UFghIkZyb4mcnku1en1_PMGJ6EVKIb94TAIa-VqEJQHxdDyA-vs"
                alt="Logo"
                class="w-full h-full object-contain"
              />
            </div>
            <div class="mb-1">
              <div class="flex items-center gap-2">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#1f1b16] font-headline">
                  <?php echo htmlspecialchars($company['name']); ?>
                </h1>
                <span class="material-symbols-outlined text-[#0284c7] text-2xl" title="Empresa Verificada">verified</span>
              </div>
              <p class="text-xs sm:text-sm text-[#525252] font-body mt-0.5">
                <?php echo htmlspecialchars($company['industry']); ?>
              </p>
            </div>
          </div>

          <div class="flex items-center gap-3">
            <a
              href="recruiter.php"
              class="px-4 py-2.5 rounded-xl border border-[#e5e5e5] hover:bg-neutral-50 text-xs font-bold text-[#525252] transition-colors"
            >
              Gestionar como Reclutador
            </a>
            <a
              href="#vacantes"
              class="px-5 py-2.5 rounded-xl bg-[#a83301] hover:bg-[#ca4a1c] text-white text-xs font-bold font-headline shadow-md transition-all"
            >
              Ver <?php echo count($companyJobs); ?> Vacantes
            </a>
          </div>

        </div>

        <!-- Badges y Metadatos -->
        <div class="flex flex-wrap items-center gap-6 text-xs text-[#525252] font-body pt-4 border-t border-neutral-100">
          <span class="flex items-center gap-1.5">
            <span class="material-symbols-outlined text-base text-[#a83301]">location_on</span>
            <?php echo htmlspecialchars($company['location']); ?>
          </span>
          <span class="flex items-center gap-1.5">
            <span class="material-symbols-outlined text-base text-[#545f73]">group</span>
            <?php echo htmlspecialchars($company['employees']); ?>
          </span>
          <a href="<?php echo htmlspecialchars($company['website']); ?>" target="_blank" class="flex items-center gap-1.5 text-[#0284c7] hover:underline font-semibold">
            <span class="material-symbols-outlined text-base">link</span>
            Sitio Web Oficial
          </a>
        </div>

      </div>

    </div>

    <!-- Contenido en 2 Columnas -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
      
      <!-- Columna Izquierda (8 cols) -->
      <div class="lg:col-span-8 space-y-8">
        
        <!-- Sobre la empresa -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-[#e5e5e5]">
          <h2 class="font-headline font-bold text-xl text-[#1f1b16] mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-[#a83301]">business</span>
            Sobre la Compañía & Misión
          </h2>
          <p class="text-sm sm:text-base text-[#525252] leading-relaxed font-body mb-6">
            <?php echo htmlspecialchars($company['about']); ?>
          </p>

          <h3 class="font-headline font-bold text-base text-[#1f1b16] mb-3">
            Cultura de Trabajo
          </h3>
          <p class="text-xs sm:text-sm text-[#525252] leading-relaxed font-body">
            <?php echo htmlspecialchars($company['culture']); ?>
          </p>
        </div>

        <!-- Vacantes Abiertas -->
        <div id="vacantes" class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-[#e5e5e5]">
          <div class="flex items-center justify-between mb-6">
            <h2 class="font-headline font-bold text-xl text-[#1f1b16] flex items-center gap-2">
              <span class="material-symbols-outlined text-[#a83301]">work</span>
              Vacantes Abiertas en Stark Cloud (<?php echo count($companyJobs); ?>)
            </h2>
            <span class="text-xs text-[#16a34a] font-bold bg-[#f0fdf4] px-2.5 py-1 rounded-full border border-[#bbf7d0]">
              Postulaciones Abiertas
            </span>
          </div>

          <div class="space-y-4">
            <?php foreach ($companyJobs as $j): ?>
              <div class="p-5 rounded-2xl bg-[#fff8f5] border border-[#ffdbd0] hover:border-[#a83301] transition-all flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                  <div class="flex items-center gap-2 mb-1">
                    <?php echo getUrgencyBadge($j['urgency']); ?>
                    <span class="text-xs text-[#737373]"><?php echo htmlspecialchars($j['modality']); ?></span>
                  </div>
                  <a href="job-detail.php?id=<?php echo urlencode($j['id']); ?>">
                    <h3 class="font-headline font-bold text-base text-[#1f1b16] hover:text-[#a83301] transition-colors">
                      <?php echo htmlspecialchars($j['title']); ?>
                    </h3>
                  </a>
                  <span class="text-xs font-bold text-[#a83301] block mt-0.5">
                    <?php echo htmlspecialchars($j['currency'] === 'USD' ? ($j['salary_usd'] ?? '') : ($j['salary_cop'] ?? '')); ?>
                  </span>
                </div>

                <a
                  href="job-detail.php?id=<?php echo urlencode($j['id']); ?>"
                  class="px-4 py-2 rounded-xl bg-[#a83301] hover:bg-[#ca4a1c] text-white text-xs font-bold font-headline transition-colors shadow-xs self-start sm:self-auto"
                >
                  Ver Oferta
                </a>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

      </div>

      <!-- Columna Derecha: Beneficios & Stack (4 cols) -->
      <div class="lg:col-span-4 space-y-6">
        
        <!-- Beneficios -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-[#e5e5e5]">
          <h3 class="font-headline font-bold text-sm text-[#1f1b16] uppercase tracking-wider mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-[#a83301] text-base">card_giftcard</span>
            Beneficios Destacados
          </h3>
          <div class="space-y-2.5">
            <?php foreach ($companyPerks as $cp): ?>
              <div class="p-3 rounded-xl bg-[#fff4f0] border border-[#ffdbd0] flex items-center gap-2.5 text-xs font-semibold text-[#1f1b16]">
                <span class="material-symbols-outlined text-[#a83301] text-sm">star</span>
                <span><?php echo htmlspecialchars($cp); ?></span>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Tech Stack -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-[#e5e5e5]">
          <h3 class="font-headline font-bold text-sm text-[#1f1b16] uppercase tracking-wider mb-3 flex items-center gap-2">
            <span class="material-symbols-outlined text-[#a83301] text-base">code</span>
            Tecnologías Principales
          </h3>
          <div class="flex flex-wrap gap-1.5">
            <?php foreach (['Next.js 14', 'TypeScript', 'Tailwind CSS', 'Go', 'Kubernetes', 'AWS', 'GraphQL', 'PostgreSQL'] as $tech): ?>
              <span class="px-2.5 py-1 rounded-lg bg-neutral-100 text-xs font-semibold text-[#525252]">
                <?php echo $tech; ?>
              </span>
            <?php endforeach; ?>
          </div>
        </div>

      </div>

    </div>

  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
