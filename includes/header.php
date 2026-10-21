<?php
/**
 * Header común y Barra de Navegación Global
 * Échele Camello - Estilo Y2K Cyber-Retro (Años 90 y 2000)
 * Conforme a 05_PATRONES_DISENO_ECHELE_CAMELLO.md
 */
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/patterns/bootstrap.php';

// Determinar página activa
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$savedCount = getSavedJobsCount();

$authUser = getAuthenticatedUser();
$notifRepo = ServiceContainer::getNotificacionRepo();
$unreadNotifsCount = $authUser ? $notifRepo->contarNoLeidas($authUser['id']) : 0;
$userRole = getCurrentUserRole();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' | Échele Camello [Y2K]' : 'Échele Camello | Portal de Empleo & Talento LATAM [Y2K]'; ?></title>
  <meta name="description" content="Ecosistema de movilidad laboral, remuneración transparente y empleo de alto impacto con estética Y2K y arquitectura de patrones de diseño State, Strategy, Repository y Observer." />
  
  <!-- Google Fonts: Orbitron, Space Grotesk, Syne (Estilo Y2K 90s-2000s Cyber-Retro) -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;600;700;800;900&family=Space+Grotesk:wght@400;500;600;700&family=Syne:wght@700;800&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet" />
  
  <!-- Tailwind CSS CDN para renderizado instantáneo con paleta Y2K -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            headline: ['Orbitron', 'Space Grotesk', 'sans-serif'],
            body: ['Space Grotesk', 'sans-serif'],
            cyber: ['Orbitron', 'monospace'],
          },
          colors: {
            y2k: {
              bg: '#070512',
              surface: 'rgba(18, 13, 41, 0.85)',
              border: 'rgba(0, 240, 255, 0.35)',
              pink: '#FF007F',
              cyan: '#00F0FF',
              purple: '#8B5CF6',
              violet: '#7928CA',
              lime: '#00FF88',
              yellow: '#FFE600',
              silver: '#E2E8F0',
              muted: '#94A3B8'
            }
          }
        }
      }
    }
  </script>

  <!-- Favicon Camello -->
  <link rel="icon" type="image/jpeg" href="assets/images/logo-camello.jpg" />

  <!-- Estilos personalizados Y2K Cyber-Retro -->
  <link rel="stylesheet" href="assets/css/custom.css" />
</head>
<body class="bg-[#070512] text-white antialiased selection:bg-[#FF007F] selection:text-white min-h-screen flex flex-col font-body">

  <!-- BARRA DE NAVEGACIÓN GLOBAL Y2K CYBER DECK -->
  <header class="fixed top-0 left-0 right-0 z-40 bg-[#090616]/90 backdrop-blur-xl border-b border-[#00F0FF]/30 h-20 flex items-center transition-all shadow-[0_4px_30px_rgba(0,0,0,0.8),0_0_20px_rgba(0,240,255,0.15)]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full flex items-center justify-between gap-4">
      
      <!-- LOGO DE ÉCHELE CAMELLO CON BRILLO Y2K -->
      <a href="index.php" class="flex items-center gap-3 group cursor-pointer">
        <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-[#FF007F]/30 to-[#00F0FF]/30 border border-[#00F0FF]/60 p-1 shadow-[0_0_15px_rgba(0,240,255,0.4)] flex items-center justify-center shrink-0 group-hover:scale-105 group-hover:border-[#FF007F] transition-all overflow-hidden">
          <img src="assets/images/logo-camello.jpg" alt="Échele Camello Logo" class="w-full h-full object-contain rounded-xl" />
        </div>
        <div class="flex flex-col">
          <span class="font-headline font-black text-xl sm:text-2xl tracking-tight leading-none flex items-center gap-2">
            <span class="bg-gradient-to-r from-white via-[#00F0FF] to-[#FF007F] bg-clip-text text-transparent filter drop-shadow-[0_0_12px_rgba(0,240,255,0.6)]">
              Échele Camello
            </span>
            <span class="text-[10px] px-2 py-0.5 rounded-full bg-[#FF007F]/20 text-[#00F0FF] font-black border border-[#00F0FF]/50 shadow-[0_0_10px_rgba(0,240,255,0.4)] font-cyber">
              ✦ Y2K
            </span>
          </span>
          <span class="text-[9px] font-mono font-bold tracking-widest text-[#00F0FF] mt-0.5 uppercase drop-shadow-[0_0_6px_rgba(0,240,255,0.5)]">
            TALENTO &bull; SALARIOS &bull; LATAM 2000s
          </span>
        </div>
      </a>

      <!-- ENLACES DE NAVEGACIÓN PRINCIPAL CYBER DOCK -->
      <nav class="hidden lg:flex items-center gap-1 bg-[#100B26]/80 p-1.5 rounded-2xl border border-[#00F0FF]/30 shadow-[inset_0_1px_3px_rgba(0,0,0,0.5)]">
        <a href="index.php" class="px-3.5 py-1.5 rounded-xl text-xs font-bold font-headline transition-all <?php echo $currentPage === 'index' ? 'bg-gradient-to-r from-[#FF007F] to-[#7928CA] text-white shadow-[0_0_15px_rgba(255,0,127,0.5)]' : 'text-[#94A3B8] hover:text-[#00F0FF] hover:bg-[#1A1238]'; ?>">
          Inicio
        </a>
        <a href="explorer.php" class="px-3.5 py-1.5 rounded-xl text-xs font-bold font-headline transition-all <?php echo $currentPage === 'explorer' || $currentPage === 'job-detail' ? 'bg-gradient-to-r from-[#FF007F] to-[#7928CA] text-white shadow-[0_0_15px_rgba(255,0,127,0.5)]' : 'text-[#94A3B8] hover:text-[#00F0FF] hover:bg-[#1A1238]'; ?>">
          Explorar
        </a>
        <a href="requests.php" class="px-3.5 py-1.5 rounded-xl text-xs font-bold font-headline transition-all flex items-center gap-1.5 <?php echo $currentPage === 'requests' ? 'bg-gradient-to-r from-[#FF007F] to-[#7928CA] text-white shadow-[0_0_15px_rgba(255,0,127,0.5)]' : 'text-[#94A3B8] hover:text-[#00F0FF] hover:bg-[#1A1238]'; ?>">
          <span class="material-symbols-outlined text-[16px] text-[#00F0FF]">sync_alt</span>
          Solicitudes
        </a>
        <a href="messages.php" class="px-3.5 py-1.5 rounded-xl text-xs font-bold font-headline transition-all flex items-center gap-1.5 <?php echo $currentPage === 'messages' ? 'bg-gradient-to-r from-[#FF007F] to-[#7928CA] text-white shadow-[0_0_15px_rgba(255,0,127,0.5)]' : 'text-[#94A3B8] hover:text-[#00F0FF] hover:bg-[#1A1238]'; ?>">
          <span class="material-symbols-outlined text-[16px] text-[#FF007F]">chat_bubble</span>
          Mensajes
        </a>
        <a href="salary-calculator.php" class="px-3.5 py-1.5 rounded-xl text-xs font-bold font-headline transition-all <?php echo $currentPage === 'salary-calculator' ? 'bg-gradient-to-r from-[#FF007F] to-[#7928CA] text-white shadow-[0_0_15px_rgba(255,0,127,0.5)]' : 'text-[#94A3B8] hover:text-[#00F0FF] hover:bg-[#1A1238]'; ?>">
          Calculadora
        </a>
        <a href="recruiter.php" class="px-3.5 py-1.5 rounded-xl text-xs font-bold font-headline transition-all <?php echo $currentPage === 'recruiter' || $currentPage === 'company' ? 'bg-gradient-to-r from-[#FF007F] to-[#7928CA] text-white shadow-[0_0_15px_rgba(255,0,127,0.5)]' : 'text-[#94A3B8] hover:text-[#00F0FF] hover:bg-[#1A1238]'; ?>">
          Empresas
        </a>
      </nav>

      <!-- ACCIONES DERECHA -->
      <div class="flex items-center gap-2 sm:gap-3">
        <!-- Notificaciones Y2K Neón -->
        <a href="notifications.php" class="relative w-10 h-10 rounded-xl bg-[#140E33] border border-[#00F0FF]/40 flex items-center justify-center text-[#00F0FF] hover:border-[#FF007F] hover:bg-[#1E144B] transition-all shadow-[0_0_12px_rgba(0,240,255,0.25)]">
          <span class="material-symbols-outlined text-[20px]">notifications</span>
          <?php if ($unreadNotifsCount > 0): ?>
            <span class="absolute -top-1 -right-1 w-5 h-5 rounded-full bg-[#FF007F] text-white text-[10px] font-bold flex items-center justify-center shadow-[0_0_10px_#FF007F]">
              <?php echo $unreadNotifsCount; ?>
            </span>
          <?php endif; ?>
        </a>

        <!-- Guardados -->
        <a href="explorer.php?saved=1" title="Vacantes guardadas" class="hidden sm:flex items-center gap-1.5 px-3 py-2 rounded-xl bg-[#140E33] border border-[#00F0FF]/40 text-xs font-bold text-white hover:border-[#00F0FF] transition-all shadow-[0_0_10px_rgba(0,240,255,0.2)]">
          <span class="material-symbols-outlined text-[18px] text-[#FFE600]">bookmark</span>
          <span id="saved-jobs-counter" class="text-[#00F0FF] font-bold"><?php echo $savedCount; ?></span>
        </a>

        <!-- Panel de Usuario / Autenticación -->
        <?php if ($authUser): ?>
          <div class="relative group">
            <a href="candidate-portal.php" class="flex items-center gap-2 pl-2 pr-3 py-1.5 rounded-xl bg-[#140E33] border border-[#00F0FF]/40 hover:border-[#FF007F] transition-all shadow-[0_0_12px_rgba(0,240,255,0.2)]">
              <img src="<?php echo htmlspecialchars($authUser['avatar'] ?? 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=150&q=80'); ?>" alt="Avatar" class="w-7 h-7 rounded-lg object-cover border border-[#00F0FF]/50" />
              <div class="hidden md:flex flex-col text-left">
                <span class="text-xs font-bold text-white leading-tight truncate max-w-[110px]">
                  <?php echo htmlspecialchars($authUser['name']); ?>
                </span>
                <span class="text-[10px] font-bold text-[#00FF88] flex items-center gap-1">
                  <span class="w-1.5 h-1.5 rounded-full bg-[#00FF88] shadow-[0_0_6px_#00FF88]"></span>
                  <?php echo htmlspecialchars($userRole); ?>
                </span>
              </div>
            </a>
            <!-- Dropdown Menú de Usuario Y2K -->
            <div class="absolute right-0 mt-2 w-52 bg-[#100B26]/95 backdrop-blur-2xl rounded-2xl shadow-[0_10px_35px_rgba(0,0,0,0.9),0_0_20px_rgba(0,240,255,0.3)] border border-[#00F0FF]/40 py-2 hidden group-hover:block z-50">
              <div class="px-4 py-2 border-b border-[#00F0FF]/20">
                <p class="text-xs font-bold text-white"><?php echo htmlspecialchars($authUser['name']); ?></p>
                <p class="text-[11px] text-[#94A3B8] truncate"><?php echo htmlspecialchars($authUser['email']); ?></p>
              </div>
              <a href="requests.php" class="block px-4 py-2 text-xs font-bold text-[#E2E8F0] hover:bg-[#FF007F]/20 hover:text-[#00F0FF] transition-colors">
                Mis Solicitudes (State)
              </a>
              <a href="messages.php" class="block px-4 py-2 text-xs font-bold text-[#E2E8F0] hover:bg-[#FF007F]/20 hover:text-[#00F0FF] transition-colors">
                Bandeja de Mensajes
              </a>
              <a href="candidate-portal.php" class="block px-4 py-2 text-xs font-bold text-[#E2E8F0] hover:bg-[#FF007F]/20 hover:text-[#00F0FF] transition-colors">
                Perfil de Camello
              </a>
              <div class="border-t border-[#00F0FF]/20 mt-1 pt-1">
                <a href="logout.php" class="block px-4 py-2 text-xs font-bold text-[#FF007F] hover:bg-[#FF007F]/20 transition-colors">
                  Cerrar Sesión
                </a>
              </div>
            </div>
          </div>
        <?php else: ?>
          <a href="login.php" class="text-xs font-bold text-[#00F0FF] px-3 py-2 rounded-xl border border-[#00F0FF]/30 hover:border-[#00F0FF] hover:bg-[#00F0FF]/10 transition-all">
            Iniciar Sesión
          </a>
          <a href="register.php" class="text-xs font-bold btn-y2k px-3.5 py-2 rounded-xl transition-all">
            Registrarse
          </a>
        <?php endif; ?>

        <!-- Botón Publicar Vacante / Solicitud Y2K -->
        <a href="create-job.php" class="hidden sm:flex items-center gap-1.5 px-3.5 py-2 rounded-xl btn-y2k text-white text-xs font-bold transition-all">
          <span class="material-symbols-outlined text-[17px]">add_circle</span>
          <span>Publicar</span>
        </a>
      </div>

    </div>
  </header>

  <!-- CONTENIDO PRINCIPAL -->
  <main class="flex-grow pt-20 w-full">

