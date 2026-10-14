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
  
  <!-- Google Fonts: Outfit & Manrope -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet" />
  
  <!-- Material Symbols Outlined -->
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
  
  <!-- Tailwind CSS CDN para renderizado instantáneo -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            headline: ['Outfit', 'sans-serif'],
            body: ['Manrope', 'sans-serif'],
          },
          colors: {
            primary: '#a83301',
            'primary-container': '#ca4a1c',
            'primary-fixed': '#ffdbd0',
            'primary-fixed-dim': '#ffb59d',
            surface: '#fff8f5',
            'surface-dim': '#e1d8d3',
            'surface-container': '#f5ece7',
            'surface-container-low': '#fbf2ed',
            'surface-container-high': '#efe6e1',
            'on-surface': '#1f1b18',
            'on-surface-variant': '#59413a',
            outline: '#8d7168',
          }
        }
      }
    }
  </script>

  <!-- Favicon Camello -->
  <link rel="icon" type="image/jpeg" href="assets/images/logo-camello.jpg" />

  <!-- Estilos personalizados -->
  <link rel="stylesheet" href="assets/css/custom.css" />
</head>
<body class="bg-[#fff8f5] text-[#1f1b18] antialiased selection:bg-[#ffdbd0] selection:text-[#a83301] min-h-screen flex flex-col">

  <!-- BARRA DE NAVEGACIÓN GLOBAL -->
  <header class="fixed top-0 left-0 right-0 z-40 bg-[#fff8f5]/90 backdrop-blur-md border-b border-[#e1bfb5]/40 h-20 flex items-center transition-all">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full flex items-center justify-between gap-4">
      
      <!-- LOGO DE ÉCHELE CAMELLO -->
      <a href="index.php" class="flex items-center gap-2.5 group cursor-pointer">
        <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-[#fff4f0] via-[#ffdbd0] to-[#f5b8a5] p-1 border border-[#ffdbd0] shadow-sm flex items-center justify-center shrink-0 group-hover:scale-105 transition-all overflow-hidden">
          <img src="assets/images/logo-camello.jpg" alt="Échele Camello Logo" class="w-full h-full object-contain mix-blend-multiply" />
        </div>
        <div class="flex flex-col">
          <span class="font-headline font-extrabold text-xl sm:text-2xl tracking-tight text-[#1f1b18] leading-none flex items-center gap-1.5">
            Échele Camello
            <span class="inline-block w-2 h-2 rounded-full bg-[#a83301] animate-ping"></span>
          </span>
          <span class="text-[10px] uppercase font-bold tracking-widest text-[#a83301]">
            Talento & Salarios Reales
          </span>
        </div>
      </a>

      <!-- ENLACES DE NAVEGACIÓN PRINCIPAL -->
      <nav class="hidden lg:flex items-center gap-1 bg-[#f5ece7]/60 p-1.5 rounded-2xl border border-[#e1bfb5]/30">
        <a href="index.php" class="px-3.5 py-1.5 rounded-xl text-xs font-bold font-headline transition-all <?php echo $currentPage === 'index' ? 'bg-[#a83301] text-white shadow-xs' : 'text-[#59413a] hover:text-[#1f1b18] hover:bg-white/60'; ?>">
          Inicio
        </a>
        <a href="explorer.php" class="px-3.5 py-1.5 rounded-xl text-xs font-bold font-headline transition-all <?php echo $currentPage === 'explorer' || $currentPage === 'job-detail' ? 'bg-[#a83301] text-white shadow-xs' : 'text-[#59413a] hover:text-[#1f1b18] hover:bg-white/60'; ?>">
          Explorar Empleos
        </a>
        <a href="salary-calculator.php" class="px-3.5 py-1.5 rounded-xl text-xs font-bold font-headline transition-all <?php echo $currentPage === 'salary-calculator' ? 'bg-[#a83301] text-white shadow-xs' : 'text-[#59413a] hover:text-[#1f1b18] hover:bg-white/60'; ?>">
          Calculadora Salarial
        </a>
        <a href="candidate-portal.php" class="px-3.5 py-1.5 rounded-xl text-xs font-bold font-headline transition-all <?php echo $currentPage === 'candidate-portal' ? 'bg-[#a83301] text-white shadow-xs' : 'text-[#59413a] hover:text-[#1f1b18] hover:bg-white/60'; ?>">
          Mis Postulaciones
        </a>
        <a href="recruiter.php" class="px-3.5 py-1.5 rounded-xl text-xs font-bold font-headline transition-all <?php echo $currentPage === 'recruiter' || $currentPage === 'company' ? 'bg-[#a83301] text-white shadow-xs' : 'text-[#59413a] hover:text-[#1f1b18] hover:bg-white/60'; ?>">
          Portal Empresas
        </a>
      </nav>

      <!-- ACCIONES DERECHA -->
      <div class="flex items-center gap-2 sm:gap-3">
        <!-- Notificaciones -->
        <button onclick="toggleNotifications(true)" class="relative w-10 h-10 rounded-xl bg-white border border-[#e1bfb5]/40 flex items-center justify-center text-[#59413a] hover:text-[#a83301] hover:border-[#a83301]/40 transition-colors shadow-xs">
          <span class="material-symbols-outlined text-[20px]">notifications</span>
          <span class="absolute top-2 right-2 w-2 h-2 rounded-full bg-[#a83301]"></span>
        </button>

        <!-- Guardados -->
        <a href="explorer.php?saved=1" title="Vacantes guardadas" class="hidden sm:flex items-center gap-1.5 px-3 py-2 rounded-xl bg-white border border-[#e1bfb5]/40 text-xs font-bold text-[#59413a] hover:border-[#a83301]/40 transition-colors shadow-xs">
          <span class="material-symbols-outlined text-[18px] text-[#a83301]">bookmark</span>
          <span id="saved-jobs-counter"><?php echo $savedCount; ?></span>
        </a>

        <!-- Botón Publicar Vacante -->
        <a href="create-job.php" class="px-4 py-2 sm:px-4 sm:py-2.5 rounded-xl bg-[#a83301] hover:bg-[#ca4a1c] text-white text-xs sm:text-sm font-bold font-headline flex items-center gap-1.5 shadow-md hover:shadow-lg transition-all active:scale-95">
          <span class="material-symbols-outlined text-[18px]">add_circle</span>
          <span class="hidden sm:inline">Publicar Vacante</span>
          <span class="sm:hidden">Publicar</span>
        </a>
      </div>

    </div>
  </header>

  <!-- CONTENIDO PRINCIPAL -->
  <main class="flex-grow pt-20 w-full">
