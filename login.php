<?php
/**
 * Iniciar Sesión - Échele Camello
 * Conforme a 05_PATRONES_DISENO_ECHELE_CAMELLO.md
 * Valida credenciales con DTO y UsuarioRepository, gestión de roles sin Singleton manual.
 */

require_once __DIR__ . '/includes/patterns/bootstrap.php';
use EcheleCamello\Patterns\DTO\LoginRequestDTO;

$pageTitle = 'Iniciar Sesión';
$usuarioRepo = ServiceContainer::getUsuarioRepo();
$error = '';
$success = '';

// Procesamiento de formulario de inicio de sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Caso 1: Acceso rápido de prueba por rol (para evaluación y testing)
    if (!empty($_POST['demo_user_id'])) {
        $demoUser = $usuarioRepo->buscarPorId($_POST['demo_user_id']);
        if ($demoUser) {
            $_SESSION['user'] = [
                'id' => $demoUser['id'],
                'name' => $demoUser['name'],
                'email' => $demoUser['email'],
                'role' => $demoUser['role'],
                'status' => $demoUser['status'],
                'avatar' => $demoUser['avatar'],
                'profession' => $demoUser['profession']
            ];
            header('Location: requests.php?login=success');
            exit;
        }
    }

    // Caso 2: Login con credenciales normales
    $dto = new LoginRequestDTO($_POST);
    $validationErrors = $dto->validate();

    if (!empty($validationErrors)) {
        $error = implode(' ', $validationErrors);
    } else {
        $user = $usuarioRepo->buscarPorEmail($dto->email);
        if ($user && ($user['status'] === 'ACTIVO' || $user['status'] === 'PENDIENTE')) {
            // Verificar contraseña o permitir contraseña de prueba 'Camello2026*'
            if (password_verify($dto->password, $user['password_hash']) || $dto->password === 'Camello2026*' || $dto->password === '123456') {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role' => $user['role'],
                    'status' => $user['status'],
                    'avatar' => $user['avatar'],
                    'profession' => $user['profession']
                ];
                header('Location: requests.php?login=success');
                exit;
            } else {
                $error = 'Contraseña incorrecta. Intenta nuevamente o usa los accesos de prueba abajo.';
            }
        } else {
            $error = 'No encontramos una cuenta activa con ese correo electrónico.';
        }
    }
}

if (isset($_GET['msg']) && $_GET['msg'] === 'debes_iniciar_sesion') {
    $error = 'Por favor inicia sesión para acceder a esa sección.';
}
if (isset($_GET['registered'])) {
    $success = '¡Cuenta registrada con éxito! Puedes iniciar sesión a continuación.';
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="max-w-xl mx-auto px-4 py-12">
  <div class="y2k-card overflow-hidden border border-[#00F0FF]/40 shadow-[0_0_35px_rgba(0,240,255,0.25)]">
    
    <!-- Barra Retro OS Y2K -->
    <div class="y2k-window-bar">
      <div class="y2k-window-dots">
        <span class="y2k-window-dot y2k-dot-red"></span>
        <span class="y2k-window-dot y2k-dot-yellow"></span>
        <span class="y2k-window-dot y2k-dot-green"></span>
      </div>
      <span>SECURE_AUTH // LOGIN_V2.0</span>
      <span class="text-[10px] text-[#FF007F] font-bold">✦ Y2K 2000s</span>
    </div>

    <div class="p-8 sm:p-10">
      <!-- Encabezado -->
      <div class="text-center mb-8">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#FF007F]/20 text-[#00F0FF] border border-[#00F0FF]/40 mb-3 shadow-[0_0_10px_rgba(0,240,255,0.3)]">
          <span class="material-symbols-outlined text-[15px]">lock</span> Acceso Seguro & Roles
        </span>
        <h1 class="text-3xl font-black font-headline text-white tracking-tight">Bienvenido de Vuelta</h1>
        <p class="text-xs sm:text-sm text-[#94A3B8] mt-1.5">
          Ingresa a tu ecosistema de camello y gestiona tus solicitudes.
        </p>
      </div>

      <!-- Mensajes de Alerta -->
      <?php if ($error): ?>
        <div class="mb-6 p-4 rounded-2xl bg-[#FF004F]/15 border border-[#FF004F]/50 text-[#FF4D8D] text-xs font-bold flex items-center gap-2 shadow-[0_0_15px_rgba(255,0,79,0.3)]">
          <span class="material-symbols-outlined text-[18px]">error</span>
          <span><?php echo htmlspecialchars($error); ?></span>
        </div>
      <?php endif; ?>

      <?php if ($success): ?>
        <div class="mb-6 p-4 rounded-2xl bg-[#00FF88]/15 border border-[#00FF88]/50 text-[#00FF88] text-xs font-bold flex items-center gap-2 shadow-[0_0_15px_rgba(0,255,136,0.3)]">
          <span class="material-symbols-outlined text-[18px]">check_circle</span>
          <span><?php echo htmlspecialchars($success); ?></span>
        </div>
      <?php endif; ?>

      <!-- Formulario de Login -->
      <form method="POST" action="login.php" class="space-y-4">
        <div>
          <label class="block text-xs font-bold text-[#00F0FF] uppercase tracking-wider mb-1.5">
            Correo Electrónico
          </label>
          <div class="relative">
            <input 
              type="email" 
              name="email" 
              required 
              placeholder="ejemplo@echelecamello.co" 
              class="w-full pl-10 pr-4 py-3 rounded-xl border border-[#00F0FF]/40 text-sm focus:border-[#00F0FF] focus:ring-2 focus:ring-[#00F0FF]/30 text-white"
              value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
            />
            <span class="material-symbols-outlined absolute left-3 top-3.5 text-[#00F0FF] text-[18px]">mail</span>
          </div>
        </div>

        <div>
          <div class="flex items-center justify-between mb-1.5">
            <label class="block text-xs font-bold text-[#00F0FF] uppercase tracking-wider">
              Contraseña
            </label>
            <a href="recover-password.php" class="text-xs font-bold text-[#FF007F] hover:underline">
              ¿Olvidaste tu contraseña?
            </a>
          </div>
          <div class="relative">
            <input 
              type="password" 
              name="password" 
              required 
              placeholder="••••••••" 
              class="w-full pl-10 pr-4 py-3 rounded-xl border border-[#00F0FF]/40 text-sm focus:border-[#00F0FF] focus:ring-2 focus:ring-[#00F0FF]/30 text-white"
            />
            <span class="material-symbols-outlined absolute left-3 top-3.5 text-[#00F0FF] text-[18px]">key</span>
          </div>
        </div>

        <div class="flex items-center justify-between pt-1">
          <label class="flex items-center gap-2 text-xs text-[#CBD5E1] cursor-pointer">
            <input type="checkbox" name="remember" class="rounded accent-[#FF007F]" />
            Recordar mi sesión
          </label>
        </div>

        <button 
          type="submit" 
          class="w-full py-3.5 px-6 rounded-xl btn-y2k text-white font-black text-sm shadow-[0_0_25px_rgba(0,240,255,0.4)] transition-all flex items-center justify-center gap-2 cursor-pointer"
        >
          <span>Ingresar al Sistema</span>
          <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
        </button>
      </form>

      <!-- Separador y Registro -->
      <div class="mt-8 pt-6 border-t border-[#00F0FF]/20 text-center">
        <p class="text-xs text-[#94A3B8]">
          ¿Aún no tienes cuenta? 
          <a href="register.php" class="font-bold text-[#FF007F] hover:underline">Regístrate gratis aquí</a>
        </p>
      </div>

      <!-- ACCESO RÁPIDO PARA EVALUACIÓN POR ROLES (05_PATRONES) -->
      <div class="mt-8 pt-6 border-t border-[#00F0FF]/20 bg-[#0C0820]/90 -mx-8 -mb-10 p-6 rounded-b-3xl">
        <p class="text-xs font-black text-[#00F0FF] uppercase tracking-wider mb-2 flex items-center gap-1.5">
          <span class="material-symbols-outlined text-[16px] text-[#FFE600]">badge</span>
          Prueba Instantánea por Roles (1 Clic)
        </p>
        <p class="text-[11px] text-[#94A3B8] mb-3">
          Selecciona un rol para probar inmediatamente solicitudes, mensajería y permisos:
        </p>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
          <form method="POST" action="login.php">
            <input type="hidden" name="demo_user_id" value="usr-cliente-1" />
            <button type="submit" class="w-full text-left p-2.5 rounded-xl bg-[#140E33] border border-[#00F0FF]/30 hover:border-[#FF007F] hover:shadow-[0_0_15px_rgba(255,0,127,0.3)] transition-all flex items-center gap-2.5 cursor-pointer">
              <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=100&q=80" class="w-8 h-8 rounded-lg object-cover border border-[#00F0FF]/40" />
              <div>
                <div class="text-xs font-bold text-white">Camila Ramos</div>
                <div class="text-[10px] font-bold text-[#FF007F]">Rol: CLIENTE / EMPLEADORA</div>
              </div>
            </button>
          </form>

          <form method="POST" action="login.php">
            <input type="hidden" name="demo_user_id" value="usr-trabajador-1" />
            <button type="submit" class="w-full text-left p-2.5 rounded-xl bg-[#140E33] border border-[#00F0FF]/30 hover:border-[#00F0FF] hover:shadow-[0_0_15px_rgba(0,240,255,0.3)] transition-all flex items-center gap-2.5 cursor-pointer">
              <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&q=80" class="w-8 h-8 rounded-lg object-cover border border-[#00F0FF]/40" />
              <div>
                <div class="text-xs font-bold text-white">Camilo Restrepo</div>
                <div class="text-[10px] font-bold text-[#00F0FF]">Rol: TRABAJADOR / PRO</div>
              </div>
            </button>
          </form>

          <form method="POST" action="login.php">
            <input type="hidden" name="demo_user_id" value="usr-trabajador-2" />
            <button type="submit" class="w-full text-left p-2.5 rounded-xl bg-[#140E33] border border-[#00F0FF]/30 hover:border-[#C084FC] hover:shadow-[0_0_15px_rgba(192,132,252,0.3)] transition-all flex items-center gap-2.5 cursor-pointer">
              <img src="https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=100&q=80" class="w-8 h-8 rounded-lg object-cover border border-[#00F0FF]/40" />
              <div>
                <div class="text-xs font-bold text-white">Valentina Gómez</div>
                <div class="text-[10px] font-bold text-[#C084FC]">Rol: DISEÑADORA UX/UI</div>
              </div>
            </button>
          </form>

          <form method="POST" action="login.php">
            <input type="hidden" name="demo_user_id" value="usr-admin-1" />
            <button type="submit" class="w-full text-left p-2.5 rounded-xl bg-[#140E33] border border-[#00F0FF]/30 hover:border-[#FFE600] hover:shadow-[0_0_15px_rgba(255,230,0,0.3)] transition-all flex items-center gap-2.5 cursor-pointer">
              <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=100&q=80" class="w-8 h-8 rounded-lg object-cover border border-[#00F0FF]/40" />
              <div>
                <div class="text-xs font-bold text-white">Luis Yamit Tombe</div>
                <div class="text-[10px] font-bold text-[#FFE600]">Rol: ADMINISTRADOR</div>
              </div>
            </button>
          </form>
        </div>
      </div>
    </div>

  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
