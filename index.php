<?php
/**
 * Página Principal - Échele Camello
 * Conexión dinámica a MySQL / XAMPP
 */

$pageTitle = "Portal de Empleo, Salarios Transparentes & Talento LATAM";
require_once __DIR__ . '/includes/header.php';

// Conexión y consulta a la Base de Datos
$pdo = getDBConnection();

// Obtener categorías desde MySQL
$stmtCategories = $pdo->query("SELECT * FROM categories ORDER BY id ASC");
$categories = $stmtCategories->fetchAll();

// Obtener empleos destacados desde MySQL
$stmtJobs = $pdo->query("SELECT * FROM jobs ORDER BY created_at DESC LIMIT 6");
$featuredJobs = $stmtJobs->fetchAll();

// Obtener testimonios desde MySQL
$stmtTestimonials = $pdo->query("SELECT * FROM testimonials ORDER BY id ASC");
$testimonials = $stmtTestimonials->fetchAll();

// Conteo total de empleos activos en la BD
$totalJobsCount = $pdo->query("SELECT COUNT(*) FROM jobs")->fetchColumn();
?>

<div class="flex flex-col w-full">
  
  <!-- HERO CONSOLE SECTION -->
  <section class="relative w-full overflow-hidden bg-[#fff8f5] pt-10 pb-20 lg:pt-14 lg:pb-24">
    <!-- Ambient Warm Glows -->
    <div class="absolute -top-32 right-[-5%] w-[520px] h-[520px] rounded-full bg-[#a83301]/5 blur-3xl pointer-events-none"></div>
    <div class="absolute top-1/2 left-[-10%] w-[420px] h-[420px] rounded-full bg-[#ffdcc0]/35 blur-2xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 flex flex-col items-center text-center">
      
      <!-- Top Brand & Live Badge -->
      <div class="flex flex-wrap items-center justify-center gap-3 mb-6">
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white shadow-sm border border-[#e1bfb5]/60 hover:scale-105 transition-transform">
          <div class="w-6 h-6 rounded-lg bg-[#fff4f0] border border-[#ffdbd0] overflow-hidden flex items-center justify-center shrink-0">
            <img src="assets/images/logo-camello.jpg" alt="Camello" class="w-full h-full object-contain" />
          </div>
          <span class="text-xs font-bold text-[#1f1b18] font-headline tracking-tight">
            Échele Camello
          </span>
        </div>
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#f5ece7] shadow-xs border border-[#e1bfb5]/40">
          <span class="w-2 h-2 rounded-full bg-[#a83301] animate-pulse"></span>
          <span class="text-xs font-bold text-[#59413a] uppercase tracking-wider">
            +840 Nuevas oportunidades añadidas hoy
          </span>
        </div>
      </div>

      <!-- Main Headline Y2K -->
      <h1 class="font-headline text-4xl sm:text-5xl lg:text-6xl text-white max-w-4xl tracking-tight leading-[1.15] font-black uppercase">
        ✦ ¡Échele Camello 2000! ✦ <br class="hidden sm:inline" />
        <span class="text-[#00f0ff] font-extrabold italic">El trabajo del futuro</span> está aquí.
      </h1>

      <p class="mt-4 text-base sm:text-lg text-neutral-300 max-w-2xl font-body">
        Conectamos al talento más “pilo”, trabajador y berraco con las mejores empresas de Colombia y oportunidades remotas globales con remuneración justa.
      </p>

      <!-- Unified Level-3 Search Console with Retro OS Header -->
      <div class="w-full max-w-5xl mt-8 rounded-3xl shadow-[0_0_35px_rgba(0,240,255,0.25)] overflow-hidden text-left p-3 sm:p-4 border border-[#00f0ff]/40 bg-[#120e26]/85 backdrop-blur-xl relative">
        <div class="y2k-window-bar -mx-3 -mt-3 sm:-mx-4 sm:-mt-4 mb-4 rounded-t-3xl">
          <div class="y2k-window-dots">
            <div class="y2k-window-dot y2k-dot-red"></div>
            <div class="y2k-window-dot y2k-dot-yellow"></div>
            <div class="y2k-window-dot y2k-dot-green"></div>
          </div>
          <span>[ SEARCH_CONSOLE // TALENTO_LATAM_v2.0 ]</span>
          <span class="text-[#ff007f] font-mono">● LIVE</span>
        </div>
        
        <form action="explorer.php" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-2 sm:gap-3 items-center">
          
          <!-- Keyword Zone -->
          <div class="md:col-span-4 flex items-center gap-2.5 px-3 py-2.5 bg-[#090714]/80 rounded-xl border border-[#00f0ff]/30 focus-within:border-[#00f0ff] focus-within:shadow-[0_0_12px_rgba(0,240,255,0.4)] transition-all">
            <span class="material-symbols-outlined text-[#00f0ff] text-[22px]">search</span>
            <div class="flex flex-col w-full min-w-0">
              <label class="text-[9px] text-[#00f0ff] font-mono uppercase tracking-wider font-bold">
                Cargo o palabra clave
              </label>
              <input
                type="text"
                name="q"
                placeholder="Ej: Frontend, Asesor, Ventas, Java..."
                class="w-full bg-transparent text-sm text-white font-medium placeholder:text-neutral-500 focus:outline-none truncate"
              />
            </div>
          </div>

          <!-- Location Zone -->
          <div class="md:col-span-3 flex items-center gap-2.5 px-3 py-2.5 bg-[#090714]/80 rounded-xl border border-[#00f0ff]/30 focus-within:border-[#00f0ff] focus-within:shadow-[0_0_12px_rgba(0,240,255,0.4)] transition-all">
            <span class="material-symbols-outlined text-[#ff007f] text-[22px]">location_on</span>
            <div class="flex flex-col w-full min-w-0">
              <label class="text-[9px] text-[#ff007f] font-mono uppercase tracking-wider font-bold">
                Ubicación
              </label>
              <select
                name="location"
                class="w-full bg-transparent text-sm text-white font-medium focus:outline-none cursor-pointer"
              >
                <option value="all" class="bg-[#090714] text-white">Todas las ubicaciones</option>
                <option value="remoto" class="bg-[#090714] text-white">100% Remoto (Global/LATAM)</option>
                <option value="bogota" class="bg-[#090714] text-white">Bogotá, D.C.</option>
                <option value="medellin" class="bg-[#090714] text-white">Medellín, Antioquia</option>
                <option value="cali" class="bg-[#090714] text-white">Cali, Valle</option>
                <option value="barranquilla" class="bg-[#090714] text-white">Barranquilla, Atlántico</option>
              </select>
            </div>
          </div>

          <!-- Salary Category Zone -->
          <div class="md:col-span-3 flex items-center gap-2.5 px-3 py-2.5 bg-[#090714]/80 rounded-xl border border-[#00f0ff]/30 focus-within:border-[#00f0ff] focus-within:shadow-[0_0_12px_rgba(0,240,255,0.4)] transition-all">
            <span class="material-symbols-outlined text-[#00ff88] text-[22px]">payments</span>
            <div class="flex flex-col w-full min-w-0">
              <label class="text-[9px] text-[#00ff88] font-mono uppercase tracking-wider font-bold">
                Rango Salarial
              </label>
              <select
                name="salary"
                class="w-full bg-transparent text-sm text-white font-medium focus:outline-none cursor-pointer"
              >
                <option value="all" class="bg-[#090714] text-white">Cualquier salario</option>
                <option value="cop-2-5" class="bg-[#090714] text-white">COP $2.5M - $5M</option>
                <option value="cop-5-10" class="bg-[#090714] text-white">COP $5M - $10M</option>
                <option value="cop-10m" class="bg-[#090714] text-white">COP +$10M COP</option>
                <option value="usd" class="bg-[#090714] text-white">En Dólares (USD)</option>
              </select>
            </div>
          </div>

          <!-- Primary CTA Submit -->
          <div class="md:col-span-2">
            <button
              type="submit"
              class="w-full h-[52px] flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#ff007f] via-[#7928ca] to-[#00f0ff] text-white font-headline text-sm font-bold shadow-[0_0_20px_rgba(0,240,255,0.4)] hover:shadow-[0_0_30px_rgba(255,0,127,0.6)] border border-white/30 transition-all active:scale-95 cursor-pointer uppercase font-mono"
            >
              <span>Camellar</span>
              <span class="material-symbols-outlined text-[18px]">bolt</span>
            </button>
          </div>
        </form>
      </div>

      <!-- Quick Search Pills -->
      <div class="mt-5 flex flex-wrap items-center justify-center gap-2 max-w-3xl">
        <span class="text-xs text-[#59413a] font-bold mr-1">Búsquedas top:</span>
        <a href="explorer.php?modality=remoto" class="px-3 py-1 rounded-full bg-[#efe6e1] hover:bg-[#eae1dc] text-[#1f1b18] text-xs font-semibold transition-colors flex items-center gap-1">
          <span class="material-symbols-outlined text-[14px] text-[#a83301]">bolt</span>
          <span>100% Remoto</span>
        </a>
        <a href="explorer.php?q=Software" class="px-3 py-1 rounded-full bg-[#efe6e1] hover:bg-[#eae1dc] text-[#1f1b18] text-xs font-semibold transition-colors">
          <span>Desarrollo Software</span>
        </a>
        <a href="explorer.php?q=Bilingüe" class="px-3 py-1 rounded-full bg-[#efe6e1] hover:bg-[#eae1dc] text-[#1f1b18] text-xs font-semibold transition-colors">
          <span>Servicio al Cliente Bilingüe</span>
        </a>
        <a href="explorer.php?q=Ventas" class="px-3 py-1 rounded-full bg-[#efe6e1] hover:bg-[#eae1dc] text-[#1f1b18] text-xs font-semibold transition-colors">
          <span>Ventas B2B</span>
        </a>
        <a href="explorer.php?q=Diseño" class="px-3 py-1 rounded-full bg-[#efe6e1] hover:bg-[#eae1dc] text-[#1f1b18] text-xs font-semibold transition-colors">
          <span>Diseño Product / UI</span>
        </a>
      </div>

      <!-- Real-time Live Counters Y2K -->
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 w-full max-w-4xl mt-12 pt-6">
        <div class="flex items-center gap-4 p-4 rounded-2xl border border-[#00f0ff]/30 text-left bg-[#120e26]/70 shadow-[0_0_20px_rgba(0,240,255,0.15)]">
          <div class="w-12 h-12 rounded-xl bg-[#00f0ff]/20 flex items-center justify-center text-[#00f0ff] border border-[#00f0ff]/40 shadow-[0_0_12px_rgba(0,240,255,0.4)]">
            <span class="material-symbols-outlined text-[26px]">terminal</span>
          </div>
          <div>
            <span class="font-headline text-2xl font-black text-[#00f0ff] block leading-none drop-shadow-[0_0_10px_rgba(0,240,255,0.6)]">
              +18,540
            </span>
            <span class="text-xs text-neutral-400 mt-1 block font-mono">✦ Vacantes verificadas activas</span>
          </div>
        </div>

        <div class="flex items-center gap-4 p-4 rounded-2xl border border-[#00ff88]/30 text-left bg-[#120e26]/70 shadow-[0_0_20px_rgba(0,255,136,0.15)]">
          <div class="w-12 h-12 rounded-xl bg-[#00ff88]/20 flex items-center justify-center text-[#00ff88] border border-[#00ff88]/40 shadow-[0_0_12px_rgba(0,255,136,0.4)]">
            <span class="material-symbols-outlined text-[26px]">verified</span>
          </div>
          <div>
            <span class="font-headline text-2xl font-black text-[#00ff88] block leading-none drop-shadow-[0_0_10px_rgba(0,255,136,0.6)]">
              94.2%
            </span>
            <span class="text-xs text-neutral-400 mt-1 block font-mono">✦ Tasa de respuesta directa</span>
          </div>
        </div>

        <div class="flex items-center gap-4 p-4 rounded-2xl border border-[#ff007f]/30 text-left bg-[#120e26]/70 shadow-[0_0_20px_rgba(255,0,127,0.15)]">
          <div class="w-12 h-12 rounded-xl bg-[#ff007f]/20 flex items-center justify-center text-[#ff007f] border border-[#ff007f]/40 shadow-[0_0_12px_rgba(255,0,127,0.4)]">
            <span class="material-symbols-outlined text-[26px]">corporate_fare</span>
          </div>
          <div>
            <span class="font-headline text-2xl font-black text-[#ff007f] block leading-none drop-shadow-[0_0_10px_rgba(255,0,127,0.6)]">
              +3,200
            </span>
            <span class="text-xs text-neutral-400 mt-1 block font-mono">✦ Empresas contratando ya</span>
          </div>
        </div>
      </div>

    </div>
  </section>

  <!-- CATEGORIES SECTION (MYSQL DYNAMIC) -->
  <section class="w-full bg-[#fbf2ed] py-16 sm:py-20 border-y border-[#e1bfb5]/40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-10">
        <div>
          <div class="flex items-center gap-1.5 text-[#a83301] mb-1 font-bold">
            <span class="material-symbols-outlined text-[18px]">explore</span>
            <span class="text-xs uppercase tracking-wider">Sectores con Mayor Demanda</span>
          </div>
          <h2 class="font-headline text-3xl text-[#1f1b18] font-bold tracking-tight">
            Explore Camello por Categoría
          </h2>
        </div>
        <p class="text-sm text-[#59413a] max-w-md">
          Sectores dinámicos con salarios transparentes y oportunidades escalables desde cargos junior hasta liderazgo directivo.
        </p>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <?php foreach ($categories as $cat): ?>
          <a
            href="explorer.php?category=<?php echo urlencode($cat['name']); ?>"
            class="group p-5 bg-white rounded-xl shadow-xs border border-[#e1bfb5]/40 hover:shadow-md hover:border-[#a83301]/40 transition-all hover:-translate-y-0.5 flex flex-col justify-between"
          >
            <div>
              <div class="flex items-start justify-between mb-4">
                <div class="w-11 h-11 rounded-lg bg-[#f5ece7] flex items-center justify-center text-[#a83301] group-hover:bg-[#a83301] group-hover:text-white transition-colors">
                  <span class="material-symbols-outlined text-[24px]"><?php echo htmlspecialchars($cat['icon'] ?? 'work'); ?></span>
                </div>
                <span class="text-xs font-bold px-2 py-0.5 rounded bg-[#f5ece7] text-[#59413a]">
                  <?php echo htmlspecialchars($cat['quota'] ?? ''); ?>
                </span>
              </div>

              <h3 class="font-headline text-lg text-[#1f1b18] font-bold group-hover:text-[#a83301] transition-colors">
                <?php echo htmlspecialchars($cat['name']); ?>
              </h3>
              <p class="text-xs text-[#59413a] mt-1.5 line-clamp-2 leading-relaxed">
                <?php echo htmlspecialchars($cat['description']); ?>
              </p>
            </div>

            <div class="mt-4 pt-3 border-t border-[#f5ece7] flex items-center gap-1 text-[#a83301] text-xs font-bold">
              <span>Ver vacantes</span>
              <span class="material-symbols-outlined text-[16px] group-hover:translate-x-1 transition-transform">
                arrow_forward
              </span>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- FEATURED JOBS SECTION (MYSQL DYNAMIC) -->
  <section class="w-full bg-[#fff8f5] py-16 sm:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-10">
        <div>
          <div class="flex items-center gap-1.5 text-[#a83301] mb-1 font-bold">
            <span class="material-symbols-outlined text-[18px]">stars</span>
            <span class="text-xs uppercase tracking-wider">Selección Especial</span>
          </div>
          <h2 class="font-headline text-3xl text-[#1f1b18] font-bold tracking-tight">
            Camellos Destacados de la Semana
          </h2>
        </div>
        <a
          href="explorer.php"
          class="px-4 py-2 rounded-lg bg-[#a83301] hover:bg-[#ca4a1c] text-white text-sm font-semibold transition-colors self-start md:self-auto"
        >
          Ver todas las <?php echo $totalJobsCount; ?> ofertas
        </a>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        <?php foreach ($featuredJobs as $job): ?>
          <?php 
            $tags = parseList($job['tags']);
            $isSaved = isJobSaved($job['id']);
          ?>
          <article class="bg-white rounded-xl p-5 shadow-xs border border-[#e1bfb5]/40 hover:shadow-md hover:border-[#a83301]/30 transition-all flex flex-col justify-between relative group">
            <div>
              <div class="flex items-start justify-between gap-3 mb-3">
                <div class="flex items-center gap-3">
                  <div class="w-11 h-11 rounded-lg bg-[#f5ece7] flex items-center justify-center font-headline text-base font-bold text-[#a83301] overflow-hidden shrink-0 border border-[#e1bfb5]/40">
                    <?php if (!empty($job['company_logo'])): ?>
                      <img src="<?php echo htmlspecialchars($job['company_logo']); ?>" alt="<?php echo htmlspecialchars($job['company']); ?>" class="w-full h-full object-contain p-1" />
                    <?php else: ?>
                      <?php echo strtoupper(substr($job['company'], 0, 2)); ?>
                    <?php endif; ?>
                  </div>
                  <div>
                    <h4 class="text-xs font-bold text-[#1f1b18]"><?php echo htmlspecialchars($job['company']); ?></h4>
                    <span class="text-[11px] text-[#59413a] flex items-center gap-1 mt-0.5">
                      <span class="material-symbols-outlined text-[13px] text-[#545f73]">public</span>
                      <?php echo htmlspecialchars($job['location']); ?>
                    </span>
                  </div>
                </div>

                <button 
                  onclick="toggleBookmark('<?php echo $job['id']; ?>', this)"
                  class="w-8 h-8 rounded-full hover:bg-neutral-100 flex items-center justify-center text-[#8d7168] transition-colors"
                  title="Guardar empleo"
                >
                  <span class="material-symbols-outlined text-[18px] <?php echo $isSaved ? 'text-[#a83301]' : ''; ?>">
                    <?php echo $isSaved ? 'bookmark' : 'bookmark_border'; ?>
                  </span>
                </button>
              </div>

              <a href="job-detail.php?id=<?php echo urlencode($job['id']); ?>">
                <h3 class="font-headline text-base font-bold text-[#1f1b18] group-hover:text-[#a83301] transition-colors line-clamp-2 mb-2">
                  <?php echo htmlspecialchars($job['title']); ?>
                </h3>
              </a>

              <!-- Salary highlight -->
              <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-[#fff4f0] border border-[#ffdbd0] text-sm font-black text-[#a83301] font-headline mb-3">
                <span class="material-symbols-outlined text-[16px]">payments</span>
                <span>
                  <?php echo htmlspecialchars($job['currency'] === 'USD' ? ($job['salary_usd'] ?? '') : ($job['salary_cop'] ?? '')); ?>
                </span>
                <span class="text-[10px] font-normal text-[#59413a]"><?php echo htmlspecialchars($job['salary_period'] ?? ''); ?></span>
              </div>

              <!-- Tags -->
              <div class="flex flex-wrap gap-1.5 mb-4">
                <?php foreach (array_slice($tags, 0, 3) as $t): ?>
                  <span class="px-2 py-0.5 rounded-md bg-[#f5ece7] text-[11px] font-medium text-[#59413a]">
                    <?php echo htmlspecialchars($t); ?>
                  </span>
                <?php endforeach; ?>
              </div>
            </div>

            <!-- Bottom meta & CTA -->
            <div class="pt-3 border-t border-[#f5ece7] flex items-center justify-between">
              <div>
                <?php echo getUrgencyBadge($job['urgency'] ?? 'Activa'); ?>
              </div>
              <a
                href="job-detail.php?id=<?php echo urlencode($job['id']); ?>"
                class="px-3.5 py-1.5 rounded-lg bg-[#a83301] hover:bg-[#ca4a1c] text-white text-xs font-bold transition-all shadow-xs"
              >
                Ver Detalle
              </a>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- SALARY TRANSPARENCY PROMO BANNER -->
  <section class="w-full bg-[#fbf2ed] py-16 border-y border-[#e1bfb5]/40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="bg-gradient-to-r from-[#1f1b18] to-[#3a2820] text-white rounded-3xl p-8 sm:p-12 relative overflow-hidden shadow-xl">
        <div class="absolute right-0 top-0 w-96 h-full bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-[#ca4a1c]/30 via-transparent to-transparent pointer-events-none"></div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center relative z-10">
          <div class="lg:col-span-8">
            <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-[#ffb59d] mb-2">
              <span class="material-symbols-outlined text-[16px]">calculate</span>
              Innovación Laboral Colombiana
            </span>
            <h2 class="font-headline text-3xl sm:text-4xl font-extrabold tracking-tight">
              ¿Cuánto debería ganar según el mercado real de Colombia?
            </h2>
            <p class="text-sm sm:text-base text-neutral-300 mt-3 max-w-xl leading-relaxed">
              Utilice nuestra <strong>Calculadora Salarial con normativa CST</strong>. Conozca su salario neto después de deducciones de salud, pensión, retención en la fuente y descubra la comparativa entre contrato laboral e independiente.
            </p>
          </div>

          <div class="lg:col-span-4 flex flex-col sm:flex-row lg:flex-col gap-3">
            <a
              href="salary-calculator.php"
              class="w-full py-3.5 px-6 rounded-xl bg-[#a83301] hover:bg-[#ca4a1c] text-white font-headline text-center font-bold text-sm shadow-lg transition-all flex items-center justify-center gap-2"
            >
              <span class="material-symbols-outlined text-[20px]">calculate</span>
              <span>Abrir Calculadora Salarial</span>
            </a>
            <a
              href="candidate-portal.php"
              class="w-full py-3 px-6 rounded-xl bg-white/10 hover:bg-white/20 text-white font-headline text-center font-bold text-xs backdrop-blur-sm border border-white/20 transition-all flex items-center justify-center gap-2"
            >
              <span>Ver Mi Panel de Postulaciones</span>
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- TESTIMONIALS SECTION (MYSQL DYNAMIC) -->
  <section class="w-full bg-[#fff8f5] py-16 sm:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center max-w-2xl mx-auto mb-12">
        <span class="text-xs font-bold uppercase tracking-wider text-[#a83301] flex items-center justify-center gap-1">
          <span class="material-symbols-outlined text-[16px]">sentiment_very_satisfied</span>
          Historias de Éxito
        </span>
        <h2 class="font-headline text-3xl font-bold text-[#1f1b18] tracking-tight mt-1">
          Camelladores que transformaron su vida laboral
        </h2>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php foreach ($testimonials as $t): ?>
          <div class="bg-white rounded-2xl p-6 shadow-xs border border-[#e1bfb5]/40 flex flex-col justify-between">
            <p class="text-xs sm:text-sm text-[#59413a] leading-relaxed italic mb-6">
              "<?php echo htmlspecialchars($t['quote']); ?>"
            </p>

            <div class="flex items-center gap-3 pt-4 border-t border-[#f5ece7]">
              <img
                src="<?php echo htmlspecialchars($t['avatar']); ?>"
                alt="<?php echo htmlspecialchars($t['author']); ?>"
                class="w-11 h-11 rounded-full object-cover border border-[#e1bfb5]/60"
              />
              <div>
                <h4 class="font-headline text-sm font-bold text-[#1f1b18]"><?php echo htmlspecialchars($t['author']); ?></h4>
                <p class="text-xs text-[#59413a]"><?php echo htmlspecialchars($t['role']); ?></p>
                <span class="text-[10px] text-[#16a34a] font-semibold flex items-center gap-1">
                  <span class="material-symbols-outlined text-[12px]">verified</span>
                  <?php echo htmlspecialchars($t['hired_in']); ?>
                </span>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
