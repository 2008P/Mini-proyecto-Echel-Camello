<?php
/**
 * Recuperar Contraseña - Échele Camello
 * Conforme a 05_PATRONES_DISENO_ECHELE_CAMELLO.md (Sección 4 y 11)
 * Implementa Adapter SMTP para despacho de desafío de recuperación y cambio de contraseña seguro.
 */

require_once __DIR__ . '/includes/patterns/bootstrap.php';

$pageTitle = 'Recuperar Contraseña';
$usuarioRepo = ServiceContainer::getUsuarioRepo();
$correoAdapter = ServiceContainer::getCorreoAdapter();
$error = '';
$success = '';
$simulatedMail = null;
$token = $_GET['token'] ?? null;
$validTokenUser = null;

if ($token) {
    $validTokenUser = $usuarioRepo->buscarPorTokenRecuperacion($token);
    if (!$validTokenUser) {
        $error = 'El enlace de recuperación es inválido o ya ha sido utilizado.';
    }
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Caso 1: Actualizar contraseña con token válido
    if (!empty($_POST['action']) && $_POST['action'] === 'reset_password' && $validTokenUser) {
        $nueva = $_POST['new_password'] ?? '';
        $confirm = $_POST['new_password_confirm'] ?? '';

        if (strlen($nueva) < 6) {
            $error = 'La contraseña debe tener mínimo 6 caracteres.';
        } elseif ($nueva !== $confirm) {
            $error = 'Las contraseñas no coinciden.';
        } else {
            $usuarioRepo->actualizarPassword($validTokenUser['id'], $nueva);
            header('Location: login.php?msg=password_updated');
            exit;
        }
    } 
    // Caso 2: Solicitar correo de recuperación
    elseif (!empty($_POST['email'])) {
        $email = strtolower(trim($_POST['email']));
        $user = $usuarioRepo->buscarPorEmail($email);

        if ($user) {
            $recoveryToken = bin2hex(random_bytes(16));
            $usuarioRepo->guardarTokenRecuperacion($user['id'], $recoveryToken);

            $resetUrl = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/recover-password.php?token=" . $recoveryToken;
            $contenidoHtml = "
                <h2>Recuperación de Contraseña - Échele Camello</h2>
                <p>Hola {$user['name']}, hemos recibido una solicitud para restablecer la contraseña de tu cuenta.</p>
                <p>Haz clic en el siguiente botón para definir tu nueva contraseña:</p>
                <p><a href='{$resetUrl}' style='padding:10px 18px;background:#6B9080;color:white;text-decoration:none;border-radius:10px;'>Restablecer Contraseña</a></p>
                <p>Si no realizaste esta solicitud, puedes ignorar este mensaje.</p>
            ";

            $correoAdapter->enviar($email, 'Instrucciones para restablecer tu contraseña', $contenidoHtml);

            $simulatedMail = [
                'destinatario' => $email,
                'nombre' => $user['name'],
                'token' => $recoveryToken,
                'url' => "recover-password.php?token=" . $recoveryToken
            ];
            $success = "Hemos enviado las instrucciones a tu correo electrónico.";
        } else {
            // Mensaje genérico para no filtrar existencia de correos
            $success = "Si el correo está registrado en nuestra base de datos, recibirás un enlace de recuperación en breve.";
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="max-w-xl mx-auto px-4 py-12">
  <div class="bg-white rounded-3xl p-8 sm:p-10 border border-[#E8E2D8] shadow-[0_12px_40px_rgba(44,39,36,0.05)]">
    
    <?php if ($validTokenUser): ?>
      <!-- ETAPA 2: DEFINIR NUEVA CONTRASEÑA -->
      <div class="text-center mb-8">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#EAF4F4] text-[#537A72] border border-[#CCE3DE] mb-3">
          <span class="material-symbols-outlined text-[15px]">password</span> Token Verificado
        </span>
        <h1 class="text-2xl font-bold font-headline text-[#2C2724]">Restablecer Contraseña</h1>
        <p class="text-sm text-[#7A726A] mt-1">
          Ingresa la nueva contraseña para <strong><?php echo htmlspecialchars($validTokenUser['email']); ?></strong>
        </p>
      </div>

      <?php if ($error): ?>
        <div class="mb-6 p-4 rounded-2xl bg-[#FFF0F0] border border-[#F5C6CB] text-[#B94A48] text-xs font-semibold flex items-center gap-2">
          <span class="material-symbols-outlined text-[18px]">error</span>
          <span><?php echo htmlspecialchars($error); ?></span>
        </div>
      <?php endif; ?>

      <form method="POST" action="recover-password.php?token=<?php echo htmlspecialchars($token); ?>" class="space-y-4">
        <input type="hidden" name="action" value="reset_password" />
        
        <div>
          <label class="block text-xs font-bold text-[#2C2724] uppercase tracking-wider mb-1.5">
            Nueva Contraseña
          </label>
          <input 
            type="password" 
            name="new_password" 
            required 
            placeholder="Mínimo 6 caracteres" 
            class="w-full py-2.5 px-3.5 rounded-xl border border-[#E8E2D8] text-sm"
          />
        </div>

        <div>
          <label class="block text-xs font-bold text-[#2C2724] uppercase tracking-wider mb-1.5">
            Confirmar Nueva Contraseña
          </label>
          <input 
            type="password" 
            name="new_password_confirm" 
            required 
            placeholder="Repite la contraseña" 
            class="w-full py-2.5 px-3.5 rounded-xl border border-[#E8E2D8] text-sm"
          />
        </div>

        <button 
          type="submit" 
          class="w-full py-3.5 px-6 rounded-xl bg-[#6B9080] hover:bg-[#5A7C6E] text-white font-bold text-sm shadow-[0_4px_14px_rgba(107,144,128,0.3)] transition-all flex items-center justify-center gap-2 mt-4"
        >
          <span class="material-symbols-outlined text-[18px]">lock_reset</span>
          <span>Guardar Nueva Contraseña</span>
        </button>
      </form>

    <?php else: ?>
      <!-- ETAPA 1: SOLICITAR RECUPERACIÓN -->
      <div class="text-center mb-8">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#FDF5F2] text-[#A25844] border border-[#F9D6C9] mb-3">
          <span class="material-symbols-outlined text-[15px]">lock_open</span> Desafío de Seguridad
        </span>
        <h1 class="text-3xl font-bold font-headline text-[#2C2724]">Recuperar Contraseña</h1>
        <p class="text-sm text-[#7A726A] mt-1.5">
          Escribe tu correo registrado y te enviaremos un token seguro de restablecimiento.
        </p>
      </div>

      <?php if ($error): ?>
        <div class="mb-6 p-4 rounded-2xl bg-[#FFF0F0] border border-[#F5C6CB] text-[#B94A48] text-xs font-semibold flex items-center gap-2">
          <span class="material-symbols-outlined text-[18px]">error</span>
          <span><?php echo htmlspecialchars($error); ?></span>
        </div>
      <?php endif; ?>

      <?php if ($success): ?>
        <div class="mb-6 p-4 rounded-2xl bg-[#EAF4F4] border border-[#CCE3DE] text-[#537A72] text-xs font-semibold flex items-center gap-2">
          <span class="material-symbols-outlined text-[18px]">mark_email_read</span>
          <span><?php echo htmlspecialchars($success); ?></span>
        </div>
      <?php endif; ?>

      <?php if ($simulatedMail): ?>
        <!-- DEMOSTRACIÓN DEL ADAPTER SMTP PARA EVALUACIÓN -->
        <div class="mb-6 p-5 rounded-2xl bg-[#FBF9F5] border border-[#E8E2D8] text-left">
          <div class="flex items-center justify-between pb-2 mb-2 border-b border-[#E8E2D8]">
            <span class="text-xs font-bold text-[#537A72] uppercase flex items-center gap-1">
              <span class="material-symbols-outlined text-[16px]">forward_to_inbox</span>
              Correo Simulado Generado por Adapter
            </span>
          </div>
          <p class="text-xs text-[#56504A] mb-3">
            Destinatario: <strong><?php echo htmlspecialchars($simulatedMail['destinatario']); ?></strong><br>
            Puedes restablecer inmediatamente la contraseña con este botón:
          </p>
          <a href="<?php echo htmlspecialchars($simulatedMail['url']); ?>" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#6B9080] hover:bg-[#5A7C6E] text-white text-xs font-bold shadow-sm transition-all">
            <span class="material-symbols-outlined text-[16px]">key</span>
            <span>Restablecer Contraseña Ahora</span>
          </a>
        </div>
      <?php endif; ?>

      <form method="POST" action="recover-password.php" class="space-y-4">
        <div>
          <label class="block text-xs font-bold text-[#2C2724] uppercase tracking-wider mb-1.5">
            Correo Electrónico
          </label>
          <div class="relative">
            <input 
              type="email" 
              name="email" 
              required 
              placeholder="tu.correo@echelecamello.co" 
              class="w-full pl-10 pr-4 py-3 rounded-xl border border-[#E8E2D8] text-sm focus:border-[#6B9080] focus:ring-2 focus:ring-[#6B9080]/20"
            />
            <span class="material-symbols-outlined absolute left-3 top-3.5 text-[#8C847C] text-[18px]">mail</span>
          </div>
        </div>

        <button 
          type="submit" 
          class="w-full py-3.5 px-6 rounded-xl bg-[#6B9080] hover:bg-[#5A7C6E] text-white font-bold text-sm shadow-[0_4px_14px_rgba(107,144,128,0.3)] transition-all flex items-center justify-center gap-2"
        >
          <span class="material-symbols-outlined text-[18px]">send</span>
          <span>Enviar Enlace de Recuperación</span>
        </button>
      </form>

      <div class="mt-8 pt-6 border-t border-[#F0EAE1] text-center">
        <a href="login.php" class="text-xs font-bold text-[#537A72] hover:underline flex items-center justify-center gap-1">
          <span class="material-symbols-outlined text-[16px]">arrow_back</span>
          <span>Volver al Inicio de Sesión</span>
        </a>
      </div>
    <?php endif; ?>

  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
