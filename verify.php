<?php
/**
 * Verificación de Cuenta - Échele Camello
 * Conforme a 05_PATRONES_DISENO_ECHELE_CAMELLO.md (RF-01 al 08)
 * Valida el token de verificación y activa la cuenta del usuario.
 */

require_once __DIR__ . '/includes/patterns/bootstrap.php';

$pageTitle = 'Verificación de Cuenta';
$usuarioRepo = ServiceContainer::getUsuarioRepo();
$token = $_GET['token'] ?? '';
$error = '';
$success = false;
$usuario = null;

if (!empty($token)) {
    $usuario = $usuarioRepo->buscarPorTokenVerificacion($token);
    if ($usuario) {
        $usuarioRepo->activarCuenta($usuario['id']);
        $success = true;
        // Iniciar sesión automáticamente
        $_SESSION['user'] = [
            'id' => $usuario['id'],
            'name' => $usuario['name'],
            'email' => $usuario['email'],
            'role' => $usuario['role'],
            'status' => 'ACTIVO',
            'avatar' => $usuario['avatar'],
            'profession' => $usuario['profession']
        ];
    } else {
        $error = 'El token de activación es inválido o la cuenta ya ha sido verificada previamente.';
    }
} else {
    $error = 'No se proporcionó ningún token de activación.';
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="max-w-xl mx-auto px-4 py-16 text-center">
  <div class="bg-white rounded-3xl p-8 sm:p-12 border border-[#E8E2D8] shadow-[0_12px_40px_rgba(44,39,36,0.05)]">
    
    <?php if ($success): ?>
      <div class="w-16 h-16 rounded-full bg-[#EAF4F4] text-[#537A72] flex items-center justify-center mx-auto mb-4 border border-[#CCE3DE]">
        <span class="material-symbols-outlined text-3xl">verified</span>
      </div>
      <span class="text-xs font-semibold px-3 py-1 rounded-full bg-[#EAF4F4] text-[#537A72] border border-[#CCE3DE]">
        Cuenta Verificada
      </span>
      <h1 class="text-3xl font-bold font-headline text-[#2C2724] mt-3">¡Bienvenido a Échele Camello!</h1>
      <p class="text-sm text-[#7A726A] mt-2 mb-6">
        Hola <strong><?php echo htmlspecialchars($usuario['name']); ?></strong>, tu cuenta con rol de <strong><?php echo htmlspecialchars($usuario['role']); ?></strong> ha sido activada exitosamente.
      </p>

      <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
        <a href="requests.php" class="w-full sm:w-auto py-3 px-6 rounded-xl bg-[#6B9080] hover:bg-[#5A7C6E] text-white font-bold text-sm shadow-sm transition-all flex items-center justify-center gap-2">
          <span class="material-symbols-outlined text-[18px]">sync_alt</span>
          <span>Ver Gestor de Solicitudes</span>
        </a>
        <a href="explorer.php" class="w-full sm:w-auto py-3 px-6 rounded-xl bg-[#F6F3EC] hover:bg-[#EAE4D9] text-[#2C2724] font-bold text-sm transition-all flex items-center justify-center gap-2">
          <span>Explorar Vacantes</span>
        </a>
      </div>

    <?php else: ?>
      <div class="w-16 h-16 rounded-full bg-[#FFF0F0] text-[#B94A48] flex items-center justify-center mx-auto mb-4 border border-[#F5C6CB]">
        <span class="material-symbols-outlined text-3xl">gpp_maybe</span>
      </div>
      <h1 class="text-2xl font-bold font-headline text-[#2C2724] mb-2">No pudimos verificar la cuenta</h1>
      <p class="text-sm text-[#7A726A] mb-6">
        <?php echo htmlspecialchars($error); ?>
      </p>
      <a href="login.php" class="inline-flex items-center gap-2 py-3 px-6 rounded-xl bg-[#6B9080] text-white font-bold text-sm shadow-sm hover:bg-[#5A7C6E] transition-all">
        <span>Ir al Inicio de Sesión</span>
      </a>
    <?php endif; ?>

  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
