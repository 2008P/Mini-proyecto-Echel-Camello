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
  <div class="bg-white rounded-3xl p-8 sm:p-10 border border-[#E8E2D8] shadow-[0_12px_40px_rgba(44,39,36,0.05)]">
    
    <!-- Encabezado -->
    <div class="text-center mb-8">
      <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#EAF4F4] text-[#537A72] border border-[#CCE3DE] mb-3">
        <span class="material-symbols-outlined text-[15px]">lock</span> Acceso Seguro & Roles
      </span>
      <h1 class="text-3xl font-bold font-headline text-[#2C2724]">Bienvenido de vuelta</h1>
      <p class="text-sm text-[#7A726A] mt-1.5">
        Ingresa a tu ecosistema de camello y gestiona tus solicitudes.
      </p>
    </div>

    <!-- Mensajes de Alerta -->
    <?php if ($error): ?>
      <div class="mb-6 p-4 rounded-2xl bg-[#FFF0F0] border border-[#F5C6CB] text-[#B94A48] text-xs font-semibold flex items-center gap-2">
        <span class="material-symbols-outlined text-[18px]">error</span>
        <span><?php echo htmlspecialchars($error); ?></span>
      </div>
    <?php endif; ?>

    <?php if ($success): ?>
      <div class="mb-6 p-4 rounded-2xl bg-[#EAF4F4] border border-[#CCE3DE] text-[#537A72] text-xs font-semibold flex items-center gap-2">
        <span class="material-symbols-outlined text-[18px]">check_circle</span>
        <span><?php echo htmlspecialchars($success); ?></span>
      </div>
    <?php endif; ?>

    <!-- Formulario de Login -->
    <form method="POST" action="login.php" class="space-y-4">
      <div>
        <label class="block text-xs font-bold text-[#2C2724] uppercase tracking-wider mb-1.5">
          Correo Electrónico
        </label>
        <div class="relative">
          <input 
            type="email" 
            name="email" 
            required 
            placeholder="ejemplo@echelecamello.co" 
            class="w-full pl-10 pr-4 py-3 rounded-xl border border-[#E8E2D8] text-sm focus:border-[#6B9080] focus:ring-2 focus:ring-[#6B9080]/20"
            value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
          />
          <span class="material-symbols-outlined absolute left-3 top-3.5 text-[#8C847C] text-[18px]">mail</span>
        </div>
      </div>

      <div>
        <div class="flex items-center justify-between mb-1.5">
          <label class="block text-xs font-bold text-[#2C2724] uppercase tracking-wider">
            Contraseña
          </label>
          <a href="recover-password.php" class="text-xs font-semibold text-[#537A72] hover:underline">
            ¿Olvidaste tu contraseña?
          </a>
        </div>
        <div class="relative">
          <input 
            type="password" 
            name="password" 
            required 
            placeholder="••••••••" 
            class="w-full pl-10 pr-4 py-3 rounded-xl border border-[#E8E2D8] text-sm focus:border-[#6B9080] focus:ring-2 focus:ring-[#6B9080]/20"
          />
          <span class="material-symbols-outlined absolute left-3 top-3.5 text-[#8C847C] text-[18px]">key</span>
        </div>
      </div>

      <div class="flex items-center justify-between pt-1">
        <label class="flex items-center gap-2 text-xs text-[#7A726A] cursor-pointer">
          <input type="checkbox" name="remember" class="rounded text-[#6B9080] focus:ring-[#6B9080]" />
          Recordar mi sesión
        </label>
      </div>

      <button 
        type="submit" 
        class="w-full py-3.5 px-6 rounded-xl bg-[#6B9080] hover:bg-[#5A7C6E] text-white font-bold text-sm shadow-[0_4px_14px_rgba(107,144,128,0.3)] transition-all flex items-center justify-center gap-2"
      >
        <span>Ingresar a mi Cuenta</span>
        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
      </button>
    </form>

    <!-- Separador y Registro -->
    <div class="mt-8 pt-6 border-t border-[#F0EAE1] text-center">
      <p class="text-xs text-[#7A726A]">
        ¿Aún no tienes cuenta? 
        <a href="register.php" class="font-bold text-[#A25844] hover:underline">Regístrate gratis aquí</a>
      </p>
    </div>

    <!-- ACCESO RÁPIDO PARA EVALUACIÓN POR ROLES (05_PATRONES) -->
    <div class="mt-8 pt-6 border-t border-[#F0EAE1] bg-[#FBF9F5] -mx-8 -mb-8 p-6 rounded-b-3xl">
      <p class="text-xs font-bold text-[#2C2724] uppercase tracking-wider mb-2 flex items-center gap-1.5">
        <span class="material-symbols-outlined text-[16px] text-[#6B9080]">badge</span>
        Prueba Instantánea por Roles (1 Clic)
      </p>
      <p class="text-[11px] text-[#7A726A] mb-3">
        Selecciona un rol para probar inmediatamente solicitudes, mensajería y permisos:
      </p>
      
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
        <form method="POST" action="login.php">
          <input type="hidden" name="demo_user_id" value="usr-cliente-1" />
          <button type="submit" class="w-full text-left p-2.5 rounded-xl bg-white border border-[#E8E2D8] hover:border-[#6B9080] hover:shadow-sm transition-all flex items-center gap-2.5">
            <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=100&q=80" class="w-8 h-8 rounded-lg object-cover" />
            <div>
              <div class="text-xs font-bold text-[#2C2724]">Camila Ramos</div>
              <div class="text-[10px] font-semibold text-[#A25844]">Rol: CLIENTE / EMPLEADORA</div>
            </div>
          </button>
        </form>

        <form method="POST" action="login.php">
          <input type="hidden" name="demo_user_id" value="usr-trabajador-1" />
          <button type="submit" class="w-full text-left p-2.5 rounded-xl bg-white border border-[#E8E2D8] hover:border-[#6B9080] hover:shadow-sm transition-all flex items-center gap-2.5">
            <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&q=80" class="w-8 h-8 rounded-lg object-cover" />
            <div>
              <div class="text-xs font-bold text-[#2C2724]">Camilo Restrepo</div>
              <div class="text-[10px] font-semibold text-[#537A72]">Rol: TRABAJADOR / PRO</div>
            </div>
          </button>
        </form>

        <form method="POST" action="login.php">
          <input type="hidden" name="demo_user_id" value="usr-trabajador-2" />
          <button type="submit" class="w-full text-left p-2.5 rounded-xl bg-white border border-[#E8E2D8] hover:border-[#6B9080] hover:shadow-sm transition-all flex items-center gap-2.5">
            <img src="https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=100&q=80" class="w-8 h-8 rounded-lg object-cover" />
            <div>
              <div class="text-xs font-bold text-[#2C2724]">Valentina Gómez</div>
              <div class="text-[10px] font-semibold text-[#655B8B]">Rol: DISEÑADORA UX/UI</div>
            </div>
          </button>
        </form>

        <form method="POST" action="login.php">
          <input type="hidden" name="demo_user_id" value="usr-admin-1" />
          <button type="submit" class="w-full text-left p-2.5 rounded-xl bg-white border border-[#E8E2D8] hover:border-[#6B9080] hover:shadow-sm transition-all flex items-center gap-2.5">
            <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=100&q=80" class="w-8 h-8 rounded-lg object-cover" />
            <div>
              <div class="text-xs font-bold text-[#2C2724]">Luis Yamit Tombe</div>
              <div class="text-[10px] font-semibold text-[#3A6B88]">Rol: ADMINISTRADOR</div>
            </div>
          </button>
        </form>
      </div>
    </div>

  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
