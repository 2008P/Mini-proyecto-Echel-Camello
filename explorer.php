<?php
/**
 * Explorador de Camellos - Filtros y Búsqueda
 * Échele Camello - MySQL / XAMPP
 */

$pageTitle = "Explorador de Camellos | Vacantes Verificadas";
require_once __DIR__ . '/includes/header.php';

$pdo = getDBConnection();

// Filtros desde GET
$searchQuery = trim($_GET['q'] ?? '');
$location = trim($_GET['location'] ?? 'all');
$modality = trim($_GET['modality'] ?? '');
$salaryFilter = trim($_GET['salary'] ?? 'all');
$category = trim($_GET['category'] ?? '');
$seniority = trim($_GET['seniority'] ?? '');
$savedOnly = isset($_GET['saved']) && $_GET['saved'] == '1';
$sort = trim($_GET['sort'] ?? 'recent');

// Construcción de consulta dinámica SQL
$sql = "SELECT j.*, (SELECT COUNT(*) FROM saved_jobs sj WHERE sj.job_id = j.id) AS is_saved FROM jobs j WHERE 1=1";
$params = [];

if (!empty($searchQuery)) {
    $sql .= " AND (j.title LIKE :q OR j.company LIKE :q OR j.tags LIKE :q OR j.category LIKE :q)";
    $params[':q'] = '%' . $searchQuery . '%';
}

if ($location !== 'all' && !empty($location)) {
    if ($location === 'remoto') {
        $sql .= " AND (j.modality = '100% Remoto' OR j.location LIKE '%Remoto%')";
    } else {
        $sql .= " AND j.location LIKE :loc";
        $params[':loc'] = '%' . $location . '%';
    }
}

if (!empty($modality)) {
    $sql .= " AND j.modality = :modality";
    $params[':modality'] = $modality;
}

if (!empty($category)) {
    $sql .= " AND j.category LIKE :cat";
    $params[':cat'] = '%' . $category . '%';
}

if (!empty($seniority)) {
    $sql .= " AND j.experience_level = :seniority";
    $params[':seniority'] = $seniority;
}

if ($savedOnly) {
    $sql .= " AND j.id IN (SELECT job_id FROM saved_jobs)";
}

if ($salaryFilter === 'cop-2-5') {
    $sql .= " AND (j.currency = 'COP' AND j.salary_min <= 5000000)";
} elseif ($salaryFilter === 'cop-5-10') {
    $sql .= " AND (j.currency = 'COP' AND j.salary_min >= 5000000 AND j.salary_min <= 10000000)";
} elseif ($salaryFilter === 'cop-10m') {
    $sql .= " AND (j.currency = 'COP' AND j.salary_max >= 10000000)";
} elseif ($salaryFilter === 'usd') {
    $sql .= " AND j.currency = 'USD'";
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rawJobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Aplicar Patrón Strategy para ordenamiento determinista (05_PATRONES - Sección 7)
use EcheleCamello\Patterns\Strategy\BusquedaContext;
$buscador = new BusquedaContext();
$estrategia = BusquedaContext::resolverEstrategia($sort);
$buscador->setEstrategia($estrategia);

$jobs = $buscador->ejecutar($rawJobs, [
    'user_lat' => 4.7110, // Coordenadas de referencia Bogotá (Haversine)
    'user_lng' => -74.0721
]);
$totalResults = count($jobs);
?>

<div class="flex flex-col w-full">
  
  <!-- BARRA DE BÚSQUEDA Y TÍTULO -->
  <section class="w-full bg-[#fbf2ed] py-8 border-b border-[#e1bfb5]/40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      
      <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6">
        <div>
          <span class="inline-flex items-center gap-1.5 text-[#a83301] text-xs font-bold uppercase tracking-wider mb-1">
            <span class="material-symbols-outlined text-[16px]">work_history</span>
            Directorio Oficial · Conexión de Talento
          </span>
          <h1 class="font-headline text-3xl sm:text-4xl text-[#1f1b18] font-bold tracking-tight">
            Explorador de Camellos
          </h1>
          <p class="text-sm text-[#59413a] mt-1">
            Encuentra oportunidades reales con salarios transparentes y empresas verificadas.
          </p>
        </div>

        <div class="flex items-center gap-2 bg-[#f5ece7] px-4 py-2 rounded-xl self-start md:self-auto border border-[#e1bfb5]/30">
          <span class="w-2.5 h-2.5 rounded-full bg-[#a83301] animate-pulse"></span>
          <span class="text-xs font-bold text-[#1f1b18]">
            <?php echo $totalResults; ?> vacantes encontradas
          </span>
        </div>
      </div>

      <!-- Buscador principal -->
      <div class="bg-white p-3 rounded-2xl shadow-md border border-[#e1bfb5]/40">
        <form action="explorer.php" method="GET" class="grid grid-cols-1 lg:grid-cols-12 gap-2.5 items-center">
          
          <div class="lg:col-span-5 flex items-center gap-2.5 px-3 py-2 bg-[#fbf2ed] rounded-xl border border-transparent focus-within:border-[#a83301]">
            <span class="material-symbols-outlined text-[#a83301] text-[22px]">search</span>
            <div class="flex flex-col flex-1 min-w-0">
              <label class="text-[10px] text-[#59413a] uppercase tracking-wider font-bold">
                Cargo o palabra clave
              </label>
              <input
                type="text"
                name="q"
                value="<?php echo htmlspecialchars($searchQuery); ?>"
                placeholder="Ej: Frontend, Tech Lead, Ventas..."
                class="w-full bg-transparent text-sm text-[#1f1b18] font-medium placeholder:text-[#8d7168] focus:outline-none truncate"
              />
            </div>
          </div>

          <div class="lg:col-span-3 flex items-center gap-2.5 px-3 py-2 bg-[#fbf2ed] rounded-xl border border-transparent focus-within:border-[#a83301]">
            <span class="material-symbols-outlined text-[#545f73] text-[22px]">location_on</span>
            <div class="flex flex-col flex-1 min-w-0">
              <label class="text-[10px] text-[#59413a] uppercase tracking-wider font-bold">
                Ubicación
              </label>
              <select name="location" class="w-full bg-transparent text-sm text-[#1f1b18] font-medium focus:outline-none cursor-pointer">
                <option value="all" <?php echo $location === 'all' ? 'selected' : ''; ?>>Todas las ubicaciones</option>
                <option value="remoto" <?php echo $location === 'remoto' ? 'selected' : ''; ?>>100% Remoto (LATAM/Global)</option>
                <option value="bogota" <?php echo $location === 'bogota' ? 'selected' : ''; ?>>Bogotá, D.C.</option>
                <option value="medellin" <?php echo $location === 'medellin' ? 'selected' : ''; ?>>Medellín, Antioquia</option>
                <option value="cali" <?php echo $location === 'cali' ? 'selected' : ''; ?>>Cali, Valle</option>
              </select>
            </div>
          </div>

          <div class="lg:col-span-2 flex items-center gap-2.5 px-3 py-2 bg-[#fbf2ed] rounded-xl border border-transparent focus-within:border-[#a83301]">
            <span class="material-symbols-outlined text-[#82511e] text-[22px]">sort</span>
            <div class="flex flex-col flex-1 min-w-0">
              <label class="text-[10px] text-[#59413a] uppercase tracking-wider font-bold">
                Ordenar por
              </label>
              <select name="sort" class="w-full bg-transparent text-sm text-[#1f1b18] font-medium focus:outline-none cursor-pointer">
                <option value="recent" <?php echo $sort === 'recent' ? 'selected' : ''; ?>>Más recientes (Strategy)</option>
                <option value="salary" <?php echo $sort === 'salary' ? 'selected' : ''; ?>>Mayor salario (Strategy)</option>
                <option value="cercania" <?php echo $sort === 'cercania' ? 'selected' : ''; ?>>Cercanía Haversine (Strategy)</option>
                <option value="calificacion" <?php echo $sort === 'calificacion' ? 'selected' : ''; ?>>Mejor calificación (Strategy)</option>
              </select>
            </div>
          </div>

          <div class="lg:col-span-2">
            <button
              type="submit"
              class="w-full h-[50px] flex items-center justify-center gap-2 rounded-xl bg-[#a83301] hover:bg-[#ca4a1c] text-white font-headline text-sm font-bold shadow-sm transition-all"
            >
              <span>Filtrar</span>
              <span class="material-symbols-outlined text-[18px]">filter_list</span>
            </button>
          </div>

        </form>
      </div>

    </div>
  </section>

  <!-- CONTENIDO PRINCIPAL CON SIDEBAR DE FILTROS -->
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 w-full">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
      
      <!-- BARRA LATERAL DE FILTROS (3 COLUMNAS) -->
      <aside class="lg:col-span-3 space-y-6">
        
        <div class="bg-white rounded-2xl p-5 border border-[#e1bfb5]/40 shadow-xs">
          <div class="flex items-center justify-between pb-3 border-b border-[#f5ece7] mb-4">
            <h3 class="font-headline font-bold text-sm text-[#1f1b18] flex items-center gap-1.5">
              <span class="material-symbols-outlined text-[18px] text-[#a83301]">tune</span>
              Filtros Avanzados
            </h3>
            <a href="explorer.php" class="text-xs text-[#a83301] hover:underline font-semibold">
              Limpiar
            </a>
          </div>

          <!-- Filtro Modalidad -->
          <div class="mb-5">
            <label class="block text-xs font-bold text-[#59413a] uppercase tracking-wider mb-2.5">
              Modalidad de Trabajo
            </label>
            <div class="space-y-1.5 text-xs">
              <a href="explorer.php?<?php echo http_build_query(array_merge($_GET, ['modality' => '100% Remoto'])); ?>" class="flex items-center justify-between px-2.5 py-1.5 rounded-lg hover:bg-[#fbf2ed] <?php echo $modality === '100% Remoto' ? 'bg-[#ffdbd0] text-[#a83301] font-bold' : 'text-[#525252]'; ?>">
                <span>100% Remoto</span>
                <span class="material-symbols-outlined text-[16px]">bolt</span>
              </a>
              <a href="explorer.php?<?php echo http_build_query(array_merge($_GET, ['modality' => 'Híbrido'])); ?>" class="flex items-center justify-between px-2.5 py-1.5 rounded-lg hover:bg-[#fbf2ed] <?php echo $modality === 'Híbrido' ? 'bg-[#ffdbd0] text-[#a83301] font-bold' : 'text-[#525252]'; ?>">
                <span>Híbrido</span>
                <span class="material-symbols-outlined text-[16px]">domain</span>
              </a>
              <a href="explorer.php?<?php echo http_build_query(array_merge($_GET, ['modality' => 'Presencial'])); ?>" class="flex items-center justify-between px-2.5 py-1.5 rounded-lg hover:bg-[#fbf2ed] <?php echo $modality === 'Presencial' ? 'bg-[#ffdbd0] text-[#a83301] font-bold' : 'text-[#525252]'; ?>">
                <span>Presencial</span>
                <span class="material-symbols-outlined text-[16px]">location_city</span>
              </a>
            </div>
          </div>

          <!-- Filtro Rango Salarial -->
          <div class="mb-5">
            <label class="block text-xs font-bold text-[#59413a] uppercase tracking-wider mb-2.5">
              Rango Salarial
            </label>
            <div class="space-y-1.5 text-xs">
              <a href="explorer.php?<?php echo http_build_query(array_merge($_GET, ['salary' => 'cop-2-5'])); ?>" class="flex items-center justify-between px-2.5 py-1.5 rounded-lg hover:bg-[#fbf2ed] <?php echo $salaryFilter === 'cop-2-5' ? 'bg-[#ffdbd0] text-[#a83301] font-bold' : 'text-[#525252]'; ?>">
                <span>$2.5M - $5M COP</span>
              </a>
              <a href="explorer.php?<?php echo http_build_query(array_merge($_GET, ['salary' => 'cop-5-10'])); ?>" class="flex items-center justify-between px-2.5 py-1.5 rounded-lg hover:bg-[#fbf2ed] <?php echo $salaryFilter === 'cop-5-10' ? 'bg-[#ffdbd0] text-[#a83301] font-bold' : 'text-[#525252]'; ?>">
                <span>$5M - $10M COP</span>
              </a>
              <a href="explorer.php?<?php echo http_build_query(array_merge($_GET, ['salary' => 'cop-10m'])); ?>" class="flex items-center justify-between px-2.5 py-1.5 rounded-lg hover:bg-[#fbf2ed] <?php echo $salaryFilter === 'cop-10m' ? 'bg-[#ffdbd0] text-[#a83301] font-bold' : 'text-[#525252]'; ?>">
                <span>+$10M COP</span>
              </a>
              <a href="explorer.php?<?php echo http_build_query(array_merge($_GET, ['salary' => 'usd'])); ?>" class="flex items-center justify-between px-2.5 py-1.5 rounded-lg hover:bg-[#fbf2ed] <?php echo $salaryFilter === 'usd' ? 'bg-[#ffdbd0] text-[#a83301] font-bold' : 'text-[#525252]'; ?>">
                <span>En Dólares (USD)</span>
              </a>
            </div>
          </div>

          <!-- Seniority -->
          <div>
            <label class="block text-xs font-bold text-[#59413a] uppercase tracking-wider mb-2.5">
              Nivel de Experiencia
            </label>
            <div class="space-y-1.5 text-xs">
              <?php foreach (['Junior', 'Semi-Senior', 'Senior', 'Lead & Staff'] as $lvl): ?>
                <a href="explorer.php?<?php echo http_build_query(array_merge($_GET, ['seniority' => $lvl])); ?>" class="flex items-center justify-between px-2.5 py-1.5 rounded-lg hover:bg-[#fbf2ed] <?php echo $seniority === $lvl ? 'bg-[#ffdbd0] text-[#a83301] font-bold' : 'text-[#525252]'; ?>">
                  <span><?php echo $lvl; ?></span>
                </a>
              <?php endforeach; ?>
            </div>
          </div>

        </div>

        <!-- Banner de Calculadora Salarial en Sidebar -->
        <div class="bg-gradient-to-br from-[#1f1b18] to-[#3a2820] text-white p-5 rounded-2xl shadow-sm border border-neutral-800">
          <div class="w-8 h-8 rounded-lg bg-[#a83301] text-white flex items-center justify-center mb-3">
            <span class="material-symbols-outlined text-[18px]">calculate</span>
          </div>
          <h4 class="font-headline font-bold text-sm text-white mb-1">
            Calculadora CST
          </h4>
          <p class="text-[11px] text-neutral-300 mb-3 leading-relaxed">
            Calcule deducciones legales colombianas (salud, pensión y retefuente) en tiempo real.
          </p>
          <a href="salary-calculator.php" class="inline-block w-full py-2 bg-[#a83301] hover:bg-[#ca4a1c] text-center text-xs font-bold rounded-lg transition-colors">
            Calcular Salario Neto
          </a>
        </div>

      </aside>

      <!-- LISTADO DE VANCANTES (9 COLUMNAS) -->
      <main class="lg:col-span-9 space-y-4">
        
        <?php if (empty($jobs)): ?>
          <div class="bg-white rounded-2xl p-12 text-center border border-[#e1bfb5]/40 shadow-xs">
            <div class="w-16 h-16 rounded-full bg-[#fff4f0] text-[#a83301] flex items-center justify-center mx-auto mb-4">
              <span class="material-symbols-outlined text-3xl">search_off</span>
            </div>
            <h3 class="font-headline text-xl font-bold text-[#1f1b18] mb-2">
              No se encontraron vacantes con estos filtros
            </h3>
            <p class="text-xs sm:text-sm text-[#59413a] max-w-md mx-auto mb-6">
              Intente limpiar los filtros de búsqueda o explorar todas las oportunidades abiertas en Colombia y LATAM.
            </p>
            <a href="explorer.php" class="px-5 py-2.5 bg-[#a83301] text-white text-xs font-bold rounded-xl shadow-md">
              Restablecer Búsqueda
            </a>
          </div>
        <?php else: ?>
          <?php foreach ($jobs as $job): ?>
            <?php 
              $tags = parseList($job['tags']);
              $perks = parseList($job['perks']);
              $isSaved = (bool)$job['is_saved'];
            ?>
            <article class="bg-white rounded-2xl p-5 sm:p-6 border border-[#e1bfb5]/40 shadow-xs hover:shadow-md hover:border-[#a83301]/30 transition-all flex flex-col justify-between group">
              <div>
                
                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-3">
                  <div class="flex items-start gap-4">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-[#f5ece7] border border-[#e1bfb5]/40 p-1 flex items-center justify-center shrink-0">
                      <?php if (!empty($job['company_logo'])): ?>
                        <img src="<?php echo htmlspecialchars($job['company_logo']); ?>" alt="<?php echo htmlspecialchars($job['company']); ?>" class="w-full h-full object-contain rounded-lg" />
                      <?php else: ?>
                        <span class="font-headline font-bold text-lg text-[#a83301]"><?php echo strtoupper(substr($job['company'], 0, 2)); ?></span>
                      <?php endif; ?>
                    </div>
                    <div>
                      <div class="flex flex-wrap items-center gap-2 mb-1">
                        <span class="text-xs font-bold text-[#1f1b18]"><?php echo htmlspecialchars($job['company']); ?></span>
                        <?php if ($job['verified_company']): ?>
                          <span class="material-symbols-outlined text-[#0284c7] text-[16px]" title="Empresa Verificada">verified</span>
                        <?php endif; ?>
                        <?php echo getUrgencyBadge($job['urgency'] ?? 'Activa'); ?>
                      </div>
                      <a href="job-detail.php?id=<?php echo urlencode($job['id']); ?>">
                        <h2 class="font-headline text-lg sm:text-xl font-bold text-[#1f1b18] group-hover:text-[#a83301] transition-colors">
                          <?php echo htmlspecialchars($job['title']); ?>
                        </h2>
                      </a>
                    </div>
                  </div>

                  <!-- Botón Guardar -->
                  <button 
                    onclick="toggleBookmark('<?php echo $job['id']; ?>', this)"
                    class="w-9 h-9 rounded-full hover:bg-neutral-100 flex items-center justify-center text-[#8d7168] self-end sm:self-auto transition-colors"
                    title="Guardar empleo"
                  >
                    <span class="material-symbols-outlined text-[20px] <?php echo $isSaved ? 'text-[#a83301]' : ''; ?>">
                      <?php echo $isSaved ? 'bookmark' : 'bookmark_border'; ?>
                    </span>
                  </button>
                </div>

                <!-- Detalles y Badges -->
                <div class="flex flex-wrap items-center gap-y-2 gap-x-4 text-xs text-[#525252] mb-3 font-body">
                  <span class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-[15px] text-[#a83301]">location_on</span>
                    <?php echo htmlspecialchars($job['location']); ?>
                  </span>
                  <span class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-[15px] text-[#545f73]">schedule</span>
                    <?php echo htmlspecialchars($job['posted_time'] ?? 'Reciente'); ?>
                  </span>
                  <span class="flex items-center gap-1 text-[#16a34a] font-semibold">
                    <span class="material-symbols-outlined text-[15px]">timer</span>
                    Respuesta en <?php echo htmlspecialchars($job['response_time'] ?? '< 48h'); ?>
                  </span>
                </div>

                <!-- Descripción corta -->
                <p class="text-xs sm:text-sm text-[#59413a] line-clamp-2 leading-relaxed mb-4">
                  <?php echo htmlspecialchars($job['description']); ?>
                </p>

                <!-- Tags / Skills -->
                <div class="flex flex-wrap gap-1.5 mb-5">
                  <?php foreach ($tags as $tag): ?>
                    <span class="px-2.5 py-0.5 rounded-lg bg-[#f5ece7] text-xs font-medium text-[#59413a]">
                      <?php echo htmlspecialchars($tag); ?>
                    </span>
                  <?php endforeach; ?>
                </div>

              </div>

              <!-- Footer de la card: Salario & Acciones -->
              <div class="pt-4 border-t border-[#f5ece7] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                  <div class="text-lg sm:text-xl font-black text-[#a83301] font-headline">
                    <?php echo htmlspecialchars($job['currency'] === 'USD' ? ($job['salary_usd'] ?? '') : ($job['salary_cop'] ?? '')); ?>
                  </div>
                  <span class="text-xs text-[#59413a] font-normal"><?php echo htmlspecialchars($job['salary_period'] ?? '/ mes'); ?></span>
                </div>

                <div class="flex items-center gap-2">
                  <button
                    onclick="fastApply('<?php echo $job['id']; ?>', this)"
                    class="px-4 py-2 rounded-xl bg-[#fff4f0] hover:bg-[#ffdbd0] text-[#a83301] text-xs font-bold font-headline border border-[#ffdbd0] flex items-center gap-1.5 transition-colors"
                  >
                    <span class="material-symbols-outlined text-[16px]">touch_app</span>
                    <span>Postulación 1 Clic</span>
                  </button>
                  <a
                    href="job-detail.php?id=<?php echo urlencode($job['id']); ?>"
                    class="px-5 py-2 rounded-xl bg-[#a83301] hover:bg-[#ca4a1c] text-white text-xs font-bold font-headline transition-colors shadow-xs"
                  >
                    Ver Vacante
                  </a>
                </div>
              </div>

            </article>
          <?php endforeach; ?>
        <?php endif; ?>

      </main>

    </div>
  </section>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
