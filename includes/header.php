<?php
/**
 * Header común y Barra de Navegación Global
 * Échele Camello - Estilo Pastel Relajante & Profesional
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
  <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' | Échele Camello' : 'Échele Camello | Portal de Empleo, Salarios Transparentes & Talento LATAM'; ?></title>
  <meta name="description" content="Ecosistema de movilidad laboral, remuneración transparente y empleo de alto impacto para Colombia y LATAM. Arquitectura con patrones de diseño State, Strategy, Repository y Observer." />
  
  <!-- Google Fonts: Plus Jakarta Sans & Outfit (Estilo Pastel Relajante y Profesional) -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet" />
  
  <!-- Tailwind CSS CDN para renderizado instantáneo con paleta Pastel -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            headline: ['Outfit', 'sans-serif'],
            body: ['Plus Jakarta Sans', 'sans-serif'],
          },
          colors: {
            pastel: {
              bg: '#FBF9F5',
              surface: '#FFFFFF',
              border: '#E8E2D8',
              sage: '#537A72',
              'sage-btn': '#6B9080',
              'sage-light': '#EAF4F4',
              'sage-border': '#CCE3DE',
              peach: '#A25844',
              'peach-btn': '#E79E78',
              'peach-light': '#FDF5F2',
              'peach-border': '#F9D6C9',
              lavender: '#655B8B',
              'lavender-light': '#F2F4FB',
              'lavender-border': '#DCD4F0',
              sky: '#3A6B88',
              'sky-light': '#EBF3F8',
              butter: '#8C7738',
              'butter-light': '#FEFAF0',
              text: '#2C2724',
              muted: '#7A726A'
            }
          }
        }
      }
    }
  </script>

  <!-- Favicon Camello -->
  <link rel="icon" type="image/jpeg" href="assets/images/logo-camello.jpg" />

  <!-- Estilos personalizados Pastel -->
  <link rel="stylesheet" href="assets/css/custom.css" />
</head>
<body class="bg-[#FBF9F5] text-[#56504A] antialiased selection:bg-[#EAF4F4] selection:text-[#537A72] min-h-screen flex flex-col font-body">

  <!-- BARRA DE NAVEGACIÓN GLOBAL PASTEL -->
  <header class="fixed top-0 left-0 right-0 z-40 bg-white/90 backdrop-blur-md border-b border-[#E8E2D8] h-20 flex items-center transition-all shadow-[0_4px_20px_rgba(44,39,36,0.03)]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full flex items-center justify-between gap-4">
      
      <!-- LOGO DE ÉCHELE CAMELLO -->
      <a href="index.php" class="flex items-center gap-3 group cursor-pointer">
        <div class="w-11 h-11 rounded-2xl bg-[#FDF5F2] border border-[#F9D6C9] p-1 shadow-sm flex items-center justify-center shrink-0 group-hover:scale-105 group-hover:border-[#E79E78] transition-all overflow-hidden">
          <img src="assets/images/logo-camello.jpg" alt="Échele Camello Logo" class="w-full h-full object-contain" />
        </div>
        <div class="flex flex-col">
          <span class="font-headline font-bold text-xl sm:text-2xl tracking-tight text-[#2C2724] leading-none flex items-center gap-2">
            Échele Camello
            <span class="text-[11px] px-2 py-0.5 rounded-full bg-[#EAF4F4] text-[#537A72] font-semibold border border-[#CCE3DE]">Pastel</span>
          </span>
          <span class="text-[10px] font-semibold tracking-wider text-[#8C847C] mt-0.5">
            MOVILIDAD LABORAL & SALARIOS CLAROS
          </span>
        </div>
      </a>

      <!-- ENLACES DE NAVEGACIÓN PRINCIPAL -->
      <nav class="hidden lg:flex items-center gap-1 bg-[#F6F3EC]/80 p-1.5 rounded-2xl border border-[#E8E2D8]">
        <a href="index.php" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold font-headline transition-all <?php echo $currentPage === 'index' ? 'bg-white text-[#2C2724] shadow-sm font-bold' : 'text-[#7A726A] hover:text-[#2C2724] hover:bg-white/60'; ?>">
          Inicio
        </a>
        <a href="explorer.php" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold font-headline transition-all <?php echo $currentPage === 'explorer' || $currentPage === 'job-detail' ? 'bg-white text-[#2C2724] shadow-sm font-bold' : 'text-[#7A726A] hover:text-[#2C2724] hover:bg-white/60'; ?>">
          Explorar
        </a>
        <a href="requests.php" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold font-headline transition-all flex items-center gap-1 <?php echo $currentPage === 'requests' ? 'bg-white text-[#2C2724] shadow-sm font-bold' : 'text-[#7A726A] hover:text-[#2C2724] hover:bg-white/60'; ?>">
          <span class="material-symbols-outlined text-[16px] text-[#E79E78]">sync_alt</span>
          Solicitudes
        </a>
        <a href="messages.php" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold font-headline transition-all flex items-center gap-1 <?php echo $currentPage === 'messages' ? 'bg-white text-[#2C2724] shadow-sm font-bold' : 'text-[#7A726A] hover:text-[#2C2724] hover:bg-white/60'; ?>">
          <span class="material-symbols-outlined text-[16px] text-[#655B8B]">chat_bubble</span>
          Mensajes
        </a>
        <a href="salary-calculator.php" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold font-headline transition-all <?php echo $currentPage === 'salary-calculator' ? 'bg-white text-[#2C2724] shadow-sm font-bold' : 'text-[#7A726A] hover:text-[#2C2724] hover:bg-white/60'; ?>">
          Calculadora
        </a>
        <a href="recruiter.php" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold font-headline transition-all <?php echo $currentPage === 'recruiter' || $currentPage === 'company' ? 'bg-white text-[#2C2724] shadow-sm font-bold' : 'text-[#7A726A] hover:text-[#2C2724] hover:bg-white/60'; ?>">
          Empresas
        </a>
      </nav>

      <!-- ACCIONES DERECHA -->
      <div class="flex items-center gap-2 sm:gap-3">
        <!-- Notificaciones -->
        <a href="notifications.php" class="relative w-10 h-10 rounded-xl bg-white border border-[#E8E2D8] flex items-center justify-center text-[#537A72] hover:border-[#6B9080] hover:bg-[#EAF4F4] transition-all shadow-sm">
          <span class="material-symbols-outlined text-[20px]">notifications</span>
          <?php if ($unreadNotifsCount > 0): ?>
            <span class="absolute -top-1 -right-1 w-5 h-5 rounded-full bg-[#E79E78] text-white text-[10px] font-bold flex items-center justify-center shadow-sm">
              <?php echo $unreadNotifsCount; ?>
            </span>
          <?php endif; ?>
        </a>

        <!-- Guardados -->
        <a href="explorer.php?saved=1" title="Vacantes guardadas" class="hidden sm:flex items-center gap-1.5 px-3 py-2 rounded-xl bg-white border border-[#E8E2D8] text-xs font-semibold text-[#2C2724] hover:border-[#CCE3DE] transition-all shadow-sm">
          <span class="material-symbols-outlined text-[18px] text-[#E79E78]">bookmark</span>
          <span id="saved-jobs-counter" class="text-[#537A72] font-bold"><?php echo $savedCount; ?></span>
        </a>

        <!-- Panel de Usuario / Autenticación -->
        <?php if ($authUser): ?>
          <div class="relative group">
            <a href="candidate-portal.php" class="flex items-center gap-2 pl-2 pr-3 py-1.5 rounded-xl bg-white border border-[#E8E2D8] hover:border-[#CCE3DE] transition-all shadow-sm">
              <img src="<?php echo htmlspecialchars($authUser['avatar'] ?? 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=150&q=80'); ?>" alt="Avatar" class="w-7 h-7 rounded-lg object-cover" />
              <div class="hidden md:flex flex-col text-left">
                <span class="text-xs font-bold text-[#2C2724] leading-tight truncate max-w-[110px]">
                  <?php echo htmlspecialchars($authUser['name']); ?>
                </span>
                <span class="text-[10px] font-semibold text-[#537A72] flex items-center gap-0.5">
                  <span class="w-1.5 h-1.5 rounded-full bg-[#6B9080]"></span>
                  <?php echo htmlspecialchars($userRole); ?>
                </span>
              </div>
            </a>
            <!-- Dropdown Menú de Usuario -->
            <div class="absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-xl border border-[#E8E2D8] py-2 hidden group-hover:block z-50 animate-fade-in">
              <div class="px-4 py-2 border-b border-[#F0EAE1]">
                <p class="text-xs font-bold text-[#2C2724]"><?php echo htmlspecialchars($authUser['name']); ?></p>
                <p class="text-[11px] text-[#8C847C] truncate"><?php echo htmlspecialchars($authUser['email']); ?></p>
              </div>
              <a href="requests.php" class="block px-4 py-2 text-xs font-semibold text-[#56504A] hover:bg-[#FDF5F2] hover:text-[#A25844] transition-colors">
                Mis Solicitudes
              </a>
              <a href="messages.php" class="block px-4 py-2 text-xs font-semibold text-[#56504A] hover:bg-[#F2F4FB] hover:text-[#655B8B] transition-colors">
                Bandeja de Mensajes
              </a>
              <a href="candidate-portal.php" class="block px-4 py-2 text-xs font-semibold text-[#56504A] hover:bg-[#F4F8F7] hover:text-[#537A72] transition-colors">
                Perfil de Camello
              </a>
              <div class="border-t border-[#F0EAE1] mt-1 pt-1">
                <a href="logout.php" class="block px-4 py-2 text-xs font-bold text-[#ba1a1a] hover:bg-[#FFF0F0] transition-colors">
                  Cerrar Sesión
                </a>
              </div>
            </div>
          </div>
        <?php else: ?>
          <a href="login.php" class="text-xs font-bold text-[#537A72] px-3 py-2 rounded-xl hover:bg-[#EAF4F4] transition-colors">
            Iniciar Sesión
          </a>
          <a href="register.php" class="text-xs font-bold bg-[#6B9080] text-white px-3.5 py-2 rounded-xl shadow-sm hover:bg-[#5A7C6E] transition-all">
            Registrarse
          </a>
        <?php endif; ?>

        <!-- Botón Publicar Vacante / Solicitud -->
        <a href="create-job.php" class="hidden sm:flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-[#6B9080] text-white text-xs font-bold hover:bg-[#5A7C6E] transition-all shadow-[0_4px_14px_rgba(107,144,128,0.25)]">
          <span class="material-symbols-outlined text-[17px]">add_circle</span>
          <span>Publicar</span>
        </a>
      </div>

    </div>
  </header>

  <!-- CONTENIDO PRINCIPAL -->
  <main class="flex-grow pt-20 w-full">
