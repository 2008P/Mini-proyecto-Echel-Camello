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
  <div class="bg-white rounded-3xl p-8 sm:p-10 border border-[#E8E2D8] shadow-[0_12px_40px_rgba(44,39,36,0.05)]">
    
    <?php if ($simulatedEmail): ?>
      <!-- PANTALLA DE ÉXITO Y ENVÍO DE CORREO (ADAPTER SMTP) -->
      <div class="text-center py-6">
        <div class="w-16 h-16 rounded-full bg-[#EAF4F4] text-[#537A72] flex items-center justify-center mx-auto mb-4 border border-[#CCE3DE]">
          <span class="material-symbols-outlined text-3xl">mark_email_read</span>
        </div>
        <span class="text-xs font-semibold px-3 py-1 rounded-full bg-[#EAF4F4] text-[#537A72] border border-[#CCE3DE]">
          Adapter SMTP Ejecutado
        </span>
        <h2 class="text-2xl font-bold font-headline text-[#2C2724] mt-2">¡Revisa tu correo para activar tu cuenta!</h2>
        <p class="text-sm text-[#7A726A] max-w-md mx-auto mt-2">
          Hemos enviado un correo de verificación a <strong><?php echo htmlspecialchars($simulatedEmail['destinatario']); ?></strong>.
        </p>

        <!-- Tarjeta de Demostración del Adapter SMTP -->
        <div class="mt-6 p-5 rounded-2xl bg-[#FBF9F5] border border-[#E8E2D8] text-left">
          <div class="flex items-center justify-between pb-2 mb-3 border-b border-[#E8E2D8]">
            <span class="text-xs font-bold text-[#537A72] uppercase flex items-center gap-1">
              <span class="material-symbols-outlined text-[16px]">outgoing_mail</span>
              Bandeja de Salida Simulada (Local)
            </span>
            <span class="text-[10px] text-[#8C847C] font-mono">Token: <?php echo substr($simulatedEmail['token'], 0, 8); ?>...</span>
          </div>
          <p class="text-xs text-[#56504A] mb-3">
            Para tu comodidad en el entorno de pruebas, puedes activar tu cuenta directamente haciendo clic en el botón siguiente:
          </p>
          <a href="<?php echo htmlspecialchars($simulatedEmail['url']); ?>" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#6B9080] hover:bg-[#5A7C6E] text-white text-xs font-bold shadow-sm transition-all">
            <span class="material-symbols-outlined text-[16px]">verified</span>
            <span>Verificar y Activar Cuenta Inmediatamente</span>
          </a>
        </div>

        <div class="mt-6">
          <a href="login.php" class="text-xs font-bold text-[#A25844] hover:underline">
            ← Regresar al Inicio de Sesión
          </a>
        </div>
      </div>

    <?php else: ?>

      <!-- ENCABEZADO REGISTRO -->
      <div class="text-center mb-8">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#FDF5F2] text-[#A25844] border border-[#F9D6C9] mb-3">
          <span class="material-symbols-outlined text-[15px]">person_add</span> Registro & Roles
        </span>
        <h1 class="text-3xl font-bold font-headline text-[#2C2724]">Crea tu cuenta en Échele Camello</h1>
        <p class="text-sm text-[#7A726A] mt-1.5">
          Elige tu rol para comenzar a solicitar o prestar servicios profesionales.
        </p>
      </div>

      <?php if ($error): ?>
        <div class="mb-6 p-4 rounded-2xl bg-[#FFF0F0] border border-[#F5C6CB] text-[#B94A48] text-xs font-semibold flex items-center gap-2">
          <span class="material-symbols-outlined text-[18px]">error</span>
          <span><?php echo htmlspecialchars($error); ?></span>
        </div>
      <?php endif; ?>

      <!-- FORMULARIO DE REGISTRO -->
      <form method="POST" action="register.php" class="space-y-5">
        
        <!-- SELECCIÓN DE ROL (05_PATRONES: Cliente o Trabajador) -->
        <div>
          <label class="block text-xs font-bold text-[#2C2724] uppercase tracking-wider mb-2">
            1. Selecciona tu Rol Principal
          </label>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <label class="relative flex items-start gap-3 p-4 rounded-2xl border-2 cursor-pointer transition-all hover:border-[#6B9080] bg-[#FBF9F5] has-[:checked]:border-[#6B9080] has-[:checked]:bg-[#EAF4F4]">
              <input type="radio" name="role" value="CLIENTE" class="mt-1 text-[#6B9080] focus:ring-[#6B9080]" <?php echo (!isset($_POST['role']) || $_POST['role'] === 'CLIENTE') ? 'checked' : ''; ?> />
              <div>
                <div class="font-bold text-xs text-[#2C2724] flex items-center gap-1">
                  <span class="material-symbols-outlined text-sm text-[#E79E78]">business_center</span>
                  Cliente / Empleador
                </div>
                <div class="text-[11px] text-[#7A726A] mt-0.5">
                  Busco contratar servicios, publicar vacantes y cotizar trabajos.
                </div>
              </div>
            </label>

            <label class="relative flex items-start gap-3 p-4 rounded-2xl border-2 cursor-pointer transition-all hover:border-[#6B9080] bg-[#FBF9F5] has-[:checked]:border-[#6B9080] has-[:checked]:bg-[#EAF4F4]">
              <input type="radio" name="role" value="TRABAJADOR" class="mt-1 text-[#6B9080] focus:ring-[#6B9080]" <?php echo (isset($_POST['role']) && $_POST['role'] === 'TRABAJADOR') ? 'checked' : ''; ?> />
              <div>
                <div class="font-bold text-xs text-[#2C2724] flex items-center gap-1">
                  <span class="material-symbols-outlined text-sm text-[#6B9080]">engineering</span>
                  Camellador / Trabajador
                </div>
                <div class="text-[11px] text-[#7A726A] mt-0.5">
                  Ofrezco mis habilidades, recibo solicitudes y presto servicios.
                </div>
              </div>
            </label>
          </div>
        </div>

        <!-- DATOS PERSONALES -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-bold text-[#2C2724] uppercase tracking-wider mb-1.5">
              Nombre Completo
            </label>
            <input 
              type="text" 
              name="name" 
              required 
              placeholder="Ej. Mateo Gómez" 
              class="w-full py-2.5 px-3.5 rounded-xl border border-[#E8E2D8] text-sm"
              value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>"
            />
          </div>

          <div>
            <label class="block text-xs font-bold text-[#2C2724] uppercase tracking-wider mb-1.5">
              Correo Electrónico
            </label>
            <input 
              type="email" 
              name="email" 
              required 
              placeholder="mateo@empresa.com" 
              class="w-full py-2.5 px-3.5 rounded-xl border border-[#E8E2D8] text-sm"
              value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
            />
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-bold text-[#2C2724] uppercase tracking-wider mb-1.5">
              Teléfono de Contacto
            </label>
            <input 
              type="tel" 
              name="phone" 
              placeholder="+57 312 000 0000" 
              class="w-full py-2.5 px-3.5 rounded-xl border border-[#E8E2D8] text-sm"
              value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>"
            />
          </div>

          <div>
            <label class="block text-xs font-bold text-[#2C2724] uppercase tracking-wider mb-1.5">
              Ciudad / Ubicación
            </label>
            <input 
              type="text" 
              name="location" 
              placeholder="Bogotá, Medellín, Remoto..." 
              class="w-full py-2.5 px-3.5 rounded-xl border border-[#E8E2D8] text-sm"
              value="<?php echo isset($_POST['location']) ? htmlspecialchars($_POST['location']) : 'Medellín, Colombia'; ?>"
            />
          </div>
        </div>

        <div>
          <label class="block text-xs font-bold text-[#2C2724] uppercase tracking-wider mb-1.5">
            Profesión / Especialidad
          </label>
          <input 
            type="text" 
            name="profession" 
            placeholder="Ej. Diseñador UI/UX, Desarrollador Web, Constructor, Electricista..." 
            class="w-full py-2.5 px-3.5 rounded-xl border border-[#E8E2D8] text-sm"
            value="<?php echo isset($_POST['profession']) ? htmlspecialchars($_POST['profession']) : ''; ?>"
          />
        </div>

        <!-- CONTRASEÑAS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-bold text-[#2C2724] uppercase tracking-wider mb-1.5">
              Contraseña
            </label>
            <input 
              type="password" 
              name="password" 
              required 
              placeholder="Mínimo 6 caracteres" 
              class="w-full py-2.5 px-3.5 rounded-xl border border-[#E8E2D8] text-sm"
            />
          </div>

          <div>
            <label class="block text-xs font-bold text-[#2C2724] uppercase tracking-wider mb-1.5">
              Confirmar Contraseña
            </label>
            <input 
              type="password" 
              name="password_confirm" 
              required 
              placeholder="Repite tu contraseña" 
              class="w-full py-2.5 px-3.5 rounded-xl border border-[#E8E2D8] text-sm"
            />
          </div>
        </div>

        <button 
          type="submit" 
          class="w-full py-3.5 px-6 rounded-xl bg-[#6B9080] hover:bg-[#5A7C6E] text-white font-bold text-sm shadow-[0_4px_14px_rgba(107,144,128,0.3)] transition-all flex items-center justify-center gap-2 mt-4"
        >
          <span class="material-symbols-outlined text-[18px]">how_to_reg</span>
          <span>Registrar mi Cuenta</span>
        </button>
      </form>

      <div class="mt-8 pt-6 border-t border-[#F0EAE1] text-center">
        <p class="text-xs text-[#7A726A]">
          ¿Ya tienes cuenta activa? 
          <a href="login.php" class="font-bold text-[#537A72] hover:underline">Inicia sesión aquí</a>
        </p>
      </div>

    <?php endif; ?>

  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
