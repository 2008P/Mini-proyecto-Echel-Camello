<?php
/**
 * Header común y Barra de Navegación Global
 * Échele Camello
 */
require_once __DIR__ . '/functions.php';

// Determinar página activa
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$savedCount = getSavedJobsCount();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' | Échele Camello' : 'Échele Camello | Portal de Empleo, Salarios Transparentes & Talento LATAM'; ?></title>
  <meta name="description" content="Ecosistema de movilidad laboral, remuneración transparente y empleo de alto impacto para Colombia y LATAM con conexión MySQL y XAMPP." />
  
  <!-- Google Fonts: Orbitron, Space Grotesk & Syne para estética Y2K -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600;700;800;900&family=Space+Grotesk:wght@400;500;600;700&family=Syne:wght@700;800&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet" />
  
  <!-- Tailwind CSS CDN para renderizado instantáneo -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            headline: ['Orbitron', 'Space Grotesk', 'sans-serif'],
            body: ['Space Grotesk', 'sans-serif'],
          },
          colors: {
            primary: '#ff007f',
            'primary-container': '#00f0ff',
            'primary-fixed': '#ffe0ed',
            surface: '#090714',
            'surface-dim': '#141026',
            'surface-container': '#1a1633',
            'on-surface': '#f8fafc',
            'on-surface-variant': '#94a3b8',
            cyan: {
              y2k: '#00f0ff',
            },
            pink: {
              y2k: '#ff007f',
            }
          }
        }
      }
    }
  </script>

  <!-- Favicon Camello -->
  <link rel="icon" type="image/jpeg" href="assets/images/logo-camello.jpg" />

  <!-- Estilos personalizados Y2K -->
  <link rel="stylesheet" href="assets/css/custom.css" />
</head>
<body class="bg-[#090714] text-[#f8fafc] antialiased selection:bg-[#ff007f] selection:text-white min-h-screen flex flex-col">

  <!-- BARRA DE NAVEGACIÓN GLOBAL Y2K -->
  <header class="fixed top-0 left-0 right-0 z-40 bg-[#090714]/85 backdrop-blur-md border-b border-[#00f0ff]/30 h-20 flex items-center transition-all shadow-[0_4px_30px_rgba(0,0,0,0.6)]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full flex items-center justify-between gap-4">
      
      <!-- LOGO DE ÉCHELE CAMELLO (ESTILO Y2K) -->
      <a href="index.php" class="flex items-center gap-2.5 group cursor-pointer">
        <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-[#ff007f]/20 via-[#7928ca]/30 to-[#00f0ff]/30 p-1 border border-[#00f0ff]/60 shadow-[0_0_15px_rgba(0,240,255,0.4)] flex items-center justify-center shrink-0 group-hover:scale-105 group-hover:border-[#ff007f] transition-all overflow-hidden">
          <img src="assets/images/logo-camello.jpg" alt="Échele Camello Logo" class="w-full h-full object-contain filter drop-shadow-[0_0_8px_rgba(0,240,255,0.6)]" />
        </div>
        <div class="flex flex-col">
          <span class="font-headline font-extrabold text-xl sm:text-2xl tracking-tight text-white leading-none flex items-center gap-1.5">
            Échele Camello
            <span class="text-[10px] px-1.5 py-0.5 rounded-full bg-[#ff007f] text-white font-mono shadow-[0_0_8px_#ff007f]">2000</span>
            <span class="inline-block w-2 h-2 rounded-full bg-[#00f0ff] animate-ping"></span>
          </span>
          <span class="text-[9px] uppercase font-bold tracking-widest text-[#00f0ff] font-mono mt-0.5">
            [ CYBER-TALENTO // SALARIOS REALES ]
          </span>
        </div>
      </a>

      <!-- ENLACES DE NAVEGACIÓN PRINCIPAL -->
      <nav class="hidden lg:flex items-center gap-1 bg-[#16122d]/70 p-1.5 rounded-2xl border border-[#00f0ff]/25 backdrop-blur-md shadow-[inset_0_1px_1px_rgba(255,255,255,0.1)]">
        <a href="index.php" class="px-3.5 py-1.5 rounded-xl text-xs font-bold font-headline transition-all <?php echo $currentPage === 'index' ? 'bg-gradient-to-r from-[#ff007f] to-[#7928ca] text-white shadow-[0_0_12px_rgba(255,0,127,0.5)]' : 'text-[#94a3b8] hover:text-[#00f0ff] hover:bg-white/5'; ?>">
          Inicio
        </a>
        <a href="explorer.php" class="px-3.5 py-1.5 rounded-xl text-xs font-bold font-headline transition-all <?php echo $currentPage === 'explorer' || $currentPage === 'job-detail' ? 'bg-gradient-to-r from-[#ff007f] to-[#7928ca] text-white shadow-[0_0_12px_rgba(255,0,127,0.5)]' : 'text-[#94a3b8] hover:text-[#00f0ff] hover:bg-white/5'; ?>">
          Explorar Empleos
        </a>
        <a href="salary-calculator.php" class="px-3.5 py-1.5 rounded-xl text-xs font-bold font-headline transition-all <?php echo $currentPage === 'salary-calculator' ? 'bg-gradient-to-r from-[#ff007f] to-[#7928ca] text-white shadow-[0_0_12px_rgba(255,0,127,0.5)]' : 'text-[#94a3b8] hover:text-[#00f0ff] hover:bg-white/5'; ?>">
          Calculadora Salarial
        </a>
        <a href="candidate-portal.php" class="px-3.5 py-1.5 rounded-xl text-xs font-bold font-headline transition-all <?php echo $currentPage === 'candidate-portal' ? 'bg-gradient-to-r from-[#ff007f] to-[#7928ca] text-white shadow-[0_0_12px_rgba(255,0,127,0.5)]' : 'text-[#94a3b8] hover:text-[#00f0ff] hover:bg-white/5'; ?>">
          Mis Postulaciones
        </a>
        <a href="recruiter.php" class="px-3.5 py-1.5 rounded-xl text-xs font-bold font-headline transition-all <?php echo $currentPage === 'recruiter' || $currentPage === 'company' ? 'bg-gradient-to-r from-[#ff007f] to-[#7928ca] text-white shadow-[0_0_12px_rgba(255,0,127,0.5)]' : 'text-[#94a3b8] hover:text-[#00f0ff] hover:bg-white/5'; ?>">
          Portal Empresas
        </a>
      </nav>

      <!-- ACCIONES DERECHA -->
      <div class="flex items-center gap-2 sm:gap-3">
        <!-- Notificaciones -->
        <button onclick="toggleNotifications(true)" class="relative w-10 h-10 rounded-xl bg-[#16122d] border border-[#00f0ff]/30 flex items-center justify-center text-[#00f0ff] hover:border-[#ff007f] hover:shadow-[0_0_15px_rgba(0,240,255,0.4)] transition-all">
          <span class="material-symbols-outlined text-[20px]">notifications</span>
          <span class="absolute top-2 right-2 w-2 h-2 rounded-full bg-[#ff007f] shadow-[0_0_8px_#ff007f]"></span>
        </button>

        <!-- Guardados -->
        <a href="explorer.php?saved=1" title="Vacantes guardadas" class="hidden sm:flex items-center gap-1.5 px-3 py-2 rounded-xl bg-[#16122d] border border-[#00f0ff]/30 text-xs font-bold text-[#f8fafc] hover:border-[#ff007f] transition-all">
          <span class="material-symbols-outlined text-[18px] text-[#ff007f]">bookmark</span>
          <span id="saved-jobs-counter" class="text-[#00f0ff] font-mono"><?php echo $savedCount; ?></span>
        </a>

        <!-- Botón Publicar Vacante Y2K -->
        <a href="create-job.php" class="px-4 py-2 sm:px-4 sm:py-2.5 rounded-xl bg-gradient-to-r from-[#ff007f] via-[#7928ca] to-[#00f0ff] text-white text-xs sm:text-sm font-bold font-headline flex items-center gap-1.5 shadow-[0_0_20px_rgba(0,240,255,0.4)] hover:shadow-[0_0_30px_rgba(255,0,127,0.6)] border border-white/30 transition-all active:scale-95">
          <span class="material-symbols-outlined text-[18px]">add_circle</span>
          <span class="hidden sm:inline">Publicar Vacante</span>
          <span class="sm:hidden">Publicar</span>
        </a>
      </div>

    </div>
  </header>

  <!-- CONTENIDO PRINCIPAL -->
  <main class="flex-grow pt-20 w-full">
