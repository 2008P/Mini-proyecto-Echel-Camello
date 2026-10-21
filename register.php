<?php
/**
 * Registro de Usuario y Asignación de Roles - Échele Camello
 * Conforme a 05_PATRONES_DISENO_ECHELE_CAMELLO.md (RF-01 al 08)
 * Implementa Repository + DTO + Adapter SMTP para verificación de cuenta.
 */

require_once __DIR__ . '/includes/patterns/bootstrap.php';
use EcheleCamello\Patterns\DTO\RegisterRequestDTO;

$pageTitle = 'Registro de Cuenta';
$usuarioRepo = ServiceContainer::getUsuarioRepo();
$correoAdapter = ServiceContainer::getCorreoAdapter();
$error = '';
$simulatedEmail = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dto = new RegisterRequestDTO($_POST);
    $validationErrors = $dto->validate();

    if ($_POST['password'] !== ($_POST['password_confirm'] ?? '')) {
        $validationErrors[] = 'Las contraseñas ingresadas no coinciden.';
    }

    if (!empty($validationErrors)) {
        $error = implode(' ', $validationErrors);
    } else {
        // Verificar si el correo ya existe
        $existente = $usuarioRepo->buscarPorEmail($dto->email);
        if ($existente) {
            $error = 'Ya existe una cuenta registrada con este correo electrónico.';
        } else {
            // Generar token de verificación
            $verificationToken = bin2hex(random_bytes(16));
            
            $nuevoId = $usuarioRepo->registrar([
                'name' => $dto->name,
                'email' => $dto->email,
                'password' => $dto->password,
                'role' => $dto->role,
                'status' => 'PENDIENTE',
                'phone' => $dto->phone,
                'location' => $dto->location,
                'profession' => $dto->profession,
                'verification_token' => $verificationToken
            ]);

            // Enviar correo de verificación mediante el Adapter de Correo
            $activacionUrl = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/verify.php?token=" . $verificationToken;
            $contenidoHtml = "
                <h2>¡Bienvenido a Échele Camello, {$dto->name}!</h2>
                <p>Tu cuenta ha sido creada exitosamente con el rol de <strong>{$dto->role}</strong>.</p>
                <p>Para activar tu cuenta y comenzar a postularte o publicar solicitudes, haz clic en el siguiente enlace:</p>
                <p><a href='{$activacionUrl}' style='padding:10px 18px;background:#6B9080;color:white;text-decoration:none;border-radius:10px;'>Activar mi Cuenta Ahora</a></p>
                <p>O copia y pega este enlace en tu navegador:<br>{$activacionUrl}</p>
            ";
            
            $correoAdapter->enviar($dto->email, 'Activa tu cuenta en Échele Camello', $contenidoHtml);

            $simulatedEmail = [
                'destinatario' => $dto->email,
                'nombre' => $dto->name,
                'rol' => $dto->role,
                'token' => $verificationToken,
                'url' => "verify.php?token=" . $verificationToken
            ];
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="max-w-2xl mx-auto px-4 py-12">
  <div class="y2k-card overflow-hidden border border-[#00F0FF]/40 shadow-[0_0_35px_rgba(0,240,255,0.25)]">
    
    <!-- Barra Retro OS Y2K -->
    <div class="y2k-window-bar">
      <div class="y2k-window-dots">
        <span class="y2k-window-dot y2k-dot-red"></span>
        <span class="y2k-window-dot y2k-dot-yellow"></span>
        <span class="y2k-window-dot y2k-dot-green"></span>
      </div>
      <span>USER_REGISTRATION // IDENTITY_CREATOR_V2</span>
      <span class="text-[10px] text-[#00F0FF] font-bold">✦ Y2K NETWORK</span>
    </div>

    <div class="p-8 sm:p-10">
      <?php if ($simulatedEmail): ?>
        <!-- PANTALLA DE ÉXITO Y ENVÍO DE CORREO (ADAPTER SMTP) -->
        <div class="text-center py-6">
          <div class="w-16 h-16 rounded-full bg-[#00F0FF]/20 text-[#00F0FF] flex items-center justify-center mx-auto mb-4 border border-[#00F0FF]/60 shadow-[0_0_20px_rgba(0,240,255,0.4)]">
            <span class="material-symbols-outlined text-3xl">mark_email_read</span>
          </div>
          <span class="text-xs font-bold px-3 py-1 rounded-full bg-[#00FF88]/20 text-[#00FF88] border border-[#00FF88]/50 shadow-[0_0_10px_rgba(0,255,136,0.3)]">
            Adapter SMTP Activado
          </span>
          <h2 class="text-2xl font-black font-headline text-white mt-3">¡Revisa tu correo para activar tu cuenta!</h2>
          <p class="text-xs sm:text-sm text-[#94A3B8] max-w-md mx-auto mt-2">
            Hemos enviado un correo de verificación a <strong class="text-[#00F0FF]"><?php echo htmlspecialchars($simulatedEmail['destinatario']); ?></strong>.
          </p>

          <!-- Tarjeta de Demostración del Adapter SMTP -->
          <div class="mt-6 p-5 rounded-2xl bg-[#140E33] border border-[#00F0FF]/40 text-left shadow-[0_0_20px_rgba(0,240,255,0.2)]">
            <div class="flex items-center justify-between pb-2 mb-3 border-b border-[#00F0FF]/20">
              <span class="text-xs font-bold text-[#00F0FF] uppercase flex items-center gap-1">
                <span class="material-symbols-outlined text-[16px]">outgoing_mail</span>
                Bandeja de Salida Simulada (Local)
              </span>
              <span class="text-[10px] text-[#FF007F] font-mono">Token: <?php echo substr($simulatedEmail['token'], 0, 8); ?>...</span>
            </div>
            <p class="text-xs text-[#CBD5E1] mb-3">
              Para tu comodidad en el entorno de pruebas, puedes activar tu cuenta directamente haciendo clic en el botón siguiente:
            </p>
            <a href="<?php echo htmlspecialchars($simulatedEmail['url']); ?>" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl btn-y2k text-white text-xs font-black shadow-[0_0_15px_rgba(0,240,255,0.4)] transition-all">
              <span class="material-symbols-outlined text-[16px]">verified</span>
              <span>Verificar y Activar Cuenta Inmediatamente</span>
            </a>
          </div>

          <div class="mt-6">
            <a href="login.php" class="text-xs font-bold text-[#FF007F] hover:underline">
              ← Regresar al Inicio de Sesión
            </a>
          </div>
        </div>

      <?php else: ?>

        <!-- ENCABEZADO REGISTRO -->
        <div class="text-center mb-8">
          <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#FF007F]/20 text-[#00F0FF] border border-[#00F0FF]/40 mb-3 shadow-[0_0_10px_rgba(0,240,255,0.3)]">
            <span class="material-symbols-outlined text-[15px]">person_add</span> Registro & Roles
          </span>
          <h1 class="text-3xl font-black font-headline text-white tracking-tight">Crea tu Cuenta en Échele Camello</h1>
          <p class="text-xs sm:text-sm text-[#94A3B8] mt-1.5">
            Elige tu rol para comenzar a solicitar o prestar servicios profesionales.
          </p>
        </div>

        <?php if ($error): ?>
          <div class="mb-6 p-4 rounded-2xl bg-[#FF004F]/15 border border-[#FF004F]/50 text-[#FF4D8D] text-xs font-bold flex items-center gap-2 shadow-[0_0_15px_rgba(255,0,79,0.3)]">
            <span class="material-symbols-outlined text-[18px]">error</span>
            <span><?php echo htmlspecialchars($error); ?></span>
          </div>
        <?php endif; ?>

        <!-- FORMULARIO DE REGISTRO -->
        <form method="POST" action="register.php" class="space-y-5">
          
          <!-- SELECCIÓN DE ROL (05_PATRONES: Cliente o Trabajador) -->
          <div>
            <label class="block text-xs font-bold text-[#00F0FF] uppercase tracking-wider mb-2">
              1. Selecciona tu Rol Principal
            </label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <label class="relative flex items-start gap-3 p-4 rounded-2xl border cursor-pointer transition-all bg-[#140E33] border-[#00F0FF]/30 has-[:checked]:border-[#FF007F] has-[:checked]:bg-[#1E1244] has-[:checked]:shadow-[0_0_15px_rgba(255,0,127,0.4)]">
                <input type="radio" name="role" value="CLIENTE" class="mt-1 accent-[#FF007F]" <?php echo (!isset($_POST['role']) || $_POST['role'] === 'CLIENTE') ? 'checked' : ''; ?> />
                <div>
                  <div class="font-bold text-xs text-white flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm text-[#FF007F]">business_center</span>
                    Cliente / Empleador
                  </div>
                  <div class="text-[11px] text-[#94A3B8] mt-0.5">
                    Busco contratar servicios, publicar vacantes y cotizar trabajos.
                  </div>
                </div>
              </label>

              <label class="relative flex items-start gap-3 p-4 rounded-2xl border cursor-pointer transition-all bg-[#140E33] border-[#00F0FF]/30 has-[:checked]:border-[#00F0FF] has-[:checked]:bg-[#0E1D3E] has-[:checked]:shadow-[0_0_15px_rgba(0,240,255,0.4)]">
                <input type="radio" name="role" value="TRABAJADOR" class="mt-1 accent-[#00F0FF]" <?php echo (isset($_POST['role']) && $_POST['role'] === 'TRABAJADOR') ? 'checked' : ''; ?> />
                <div>
                  <div class="font-bold text-xs text-white flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm text-[#00F0FF]">engineering</span>
                    Camellador / Trabajador
                  </div>
                  <div class="text-[11px] text-[#94A3B8] mt-0.5">
                    Ofrezco mis habilidades, recibo solicitudes y presto servicios.
                  </div>
                </div>
              </label>
            </div>
          </div>

          <!-- DATOS PERSONALES -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-[#00F0FF] uppercase tracking-wider mb-1.5">
                Nombre Completo
              </label>
              <input 
                type="text" 
                name="name" 
                required 
                placeholder="Ej. Mateo Gómez" 
                class="w-full py-2.5 px-3.5 rounded-xl border border-[#00F0FF]/40 text-sm text-white"
                value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>"
              />
            </div>

            <div>
              <label class="block text-xs font-bold text-[#00F0FF] uppercase tracking-wider mb-1.5">
                Correo Electrónico
              </label>
              <input 
                type="email" 
                name="email" 
                required 
                placeholder="mateo@empresa.com" 
                class="w-full py-2.5 px-3.5 rounded-xl border border-[#00F0FF]/40 text-sm text-white"
                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
              />
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-[#00F0FF] uppercase tracking-wider mb-1.5">
                Teléfono de Contacto
              </label>
              <input 
                type="tel" 
                name="phone" 
                placeholder="+57 312 000 0000" 
                class="w-full py-2.5 px-3.5 rounded-xl border border-[#00F0FF]/40 text-sm text-white"
                value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>"
              />
            </div>

            <div>
              <label class="block text-xs font-bold text-[#00F0FF] uppercase tracking-wider mb-1.5">
                Ciudad / Ubicación
              </label>
              <input 
                type="text" 
                name="location" 
                placeholder="Bogotá, Medellín, Remoto..." 
                class="w-full py-2.5 px-3.5 rounded-xl border border-[#00F0FF]/40 text-sm text-white"
                value="<?php echo isset($_POST['location']) ? htmlspecialchars($_POST['location']) : 'Medellín, Colombia'; ?>"
              />
            </div>
          </div>

          <div>
            <label class="block text-xs font-bold text-[#00F0FF] uppercase tracking-wider mb-1.5">
              Profesión / Especialidad
            </label>
            <input 
              type="text" 
              name="profession" 
              placeholder="Ej. Diseñador UI/UX, Desarrollador Web, Constructor, Electricista..." 
              class="w-full py-2.5 px-3.5 rounded-xl border border-[#00F0FF]/40 text-sm text-white"
              value="<?php echo isset($_POST['profession']) ? htmlspecialchars($_POST['profession']) : ''; ?>"
            />
          </div>

          <!-- CONTRASEÑAS -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-[#00F0FF] uppercase tracking-wider mb-1.5">
                Contraseña
              </label>
              <input 
                type="password" 
                name="password" 
                required 
                placeholder="Mínimo 6 caracteres" 
                class="w-full py-2.5 px-3.5 rounded-xl border border-[#00F0FF]/40 text-sm text-white"
              />
            </div>

            <div>
              <label class="block text-xs font-bold text-[#00F0FF] uppercase tracking-wider mb-1.5">
                Confirmar Contraseña
              </label>
              <input 
                type="password" 
                name="password_confirm" 
                required 
                placeholder="Repite tu contraseña" 
                class="w-full py-2.5 px-3.5 rounded-xl border border-[#00F0FF]/40 text-sm text-white"
              />
            </div>
          </div>

          <button 
            type="submit" 
            class="w-full py-3.5 px-6 rounded-xl btn-y2k text-white font-black text-sm shadow-[0_0_25px_rgba(0,240,255,0.4)] transition-all flex items-center justify-center gap-2 mt-4 cursor-pointer"
          >
            <span class="material-symbols-outlined text-[18px]">how_to_reg</span>
            <span>Registrar mi Cuenta</span>
          </button>
        </form>

        <div class="mt-8 pt-6 border-t border-[#00F0FF]/20 text-center">
          <p class="text-xs text-[#94A3B8]">
            ¿Ya tienes cuenta activa? 
            <a href="login.php" class="font-bold text-[#00F0FF] hover:underline">Inicia sesión aquí</a>
          </p>
        </div>

      <?php endif; ?>

    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
