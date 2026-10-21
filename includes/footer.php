  </main>

  <!-- FOOTER GLOBAL PASTEL RELAJANTE Y PROFESIONAL -->
  <footer class="w-full bg-[#F5F1EA] text-[#56504A] mt-20 pt-16 pb-12 border-t border-[#E8E2D8] font-body">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 pb-12 border-b border-[#E8E2D8]">
        
        <!-- Columna de Marca y Propósito -->
        <div class="lg:col-span-2">
          <div class="flex items-center gap-3 mb-4">
            <div class="w-11 h-11 rounded-2xl bg-white p-1 border border-[#CCE3DE] shadow-sm flex items-center justify-center shrink-0 overflow-hidden">
              <img src="assets/images/logo-camello.jpg" alt="Logo Camello" class="w-full h-full object-contain" />
            </div>
            <div>
              <span class="font-headline font-bold text-2xl text-[#2C2724] tracking-tight flex items-center gap-2">
                Échele Camello
                <span class="text-xs px-2.5 py-0.5 rounded-full bg-[#EAF4F4] text-[#537A72] font-semibold border border-[#CCE3DE]">COLOMBIA</span>
              </span>
              <span class="text-xs text-[#8C847C] block font-medium mt-0.5">Ecosistema de Oportunidades & Salarios Transparentes</span>
            </div>
          </div>
          
          <p class="text-xs sm:text-sm text-[#7A726A] max-w-sm leading-relaxed mb-6">
            Conectamos el talento más comprometido y profesional de Colombia con oportunidades reales sin ofertas fantasma. Diseñado con arquitectura de patrones State, Strategy, Repository y Observer.
          </p>

          <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white border border-[#CCE3DE] text-xs text-[#537A72] font-semibold shadow-sm">
              <span class="w-2 h-2 rounded-full bg-[#6B9080]"></span>
              Arquitectura de Patrones Activa (05_PATRONES)
            </span>
          </div>
        </div>

        <!-- Columna 1: Para Trabajadores / Camelladores -->
        <div>
          <h4 class="font-headline text-xs font-bold text-[#2C2724] uppercase tracking-wider mb-4">
            Para Camelladores
          </h4>
          <ul class="space-y-2.5 text-xs text-[#7A726A]">
            <li><a href="explorer.php" class="hover:text-[#537A72] transition-colors">Explorar Vacantes</a></li>
            <li><a href="requests.php" class="hover:text-[#537A72] transition-colors font-medium text-[#537A72]">Gestor de Solicitudes (State)</a></li>
            <li><a href="messages.php" class="hover:text-[#537A72] transition-colors">Bandeja de Mensajería</a></li>
            <li><a href="candidate-portal.php" class="hover:text-[#537A72] transition-colors">Mi Panel de Camellos</a></li>
            <li><a href="salary-calculator.php" class="hover:text-[#537A72] transition-colors">Calculadora Salarial CST</a></li>
          </ul>
        </div>

        <!-- Columna 2: Para Clientes & Empresas -->
        <div>
          <h4 class="font-headline text-xs font-bold text-[#2C2724] uppercase tracking-wider mb-4">
            Para Empleadores
          </h4>
          <ul class="space-y-2.5 text-xs text-[#7A726A]">
            <li><a href="create-job.php" class="hover:text-[#A25844] transition-colors">Publicar una Oferta</a></li>
            <li><a href="recruiter.php" class="hover:text-[#A25844] transition-colors">Panel Reclutador Enterprise</a></li>
            <li><a href="company.php" class="hover:text-[#A25844] transition-colors">Perfil de Empresa Verificada</a></li>
            <li><a href="requests.php?tab=crear" class="hover:text-[#A25844] transition-colors">Contratar Servicio Directo</a></li>
            <li><a href="setup_db.php" class="hover:text-[#537A72] transition-colors text-[#537A72] font-semibold">Sincronizar Base de Datos</a></li>
          </ul>
        </div>

        <!-- Columna 3: Cuenta & Acceso -->
        <div>
          <h4 class="font-headline text-xs font-bold text-[#2C2724] uppercase tracking-wider mb-4">
            Seguridad & Cuenta
          </h4>
          <ul class="space-y-2.5 text-xs text-[#7A726A]">
            <li><a href="login.php" class="hover:text-[#537A72] transition-colors">Iniciar Sesión</a></li>
            <li><a href="register.php" class="hover:text-[#537A72] transition-colors">Crear Cuenta Nueva</a></li>
            <li><a href="recover-password.php" class="hover:text-[#537A72] transition-colors">Recuperar Contraseña</a></li>
            <li><a href="notifications.php" class="hover:text-[#537A72] transition-colors">Centro de Notificaciones</a></li>
          </ul>
        </div>

      </div>

      <!-- Barra Inferior -->
      <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-[#8C847C]">
        <p>© 2026 Échele Camello — Desarrollado por Luis Yamit Tombe Cabrera (ADSO Ficha 3413988).</p>
        <div class="flex items-center gap-6">
          <a href="explorer.php" class="hover:text-[#537A72] transition-colors">Términos de Servicio</a>
          <a href="salary-calculator.php" class="hover:text-[#537A72] transition-colors">Transparencia Salarial</a>
          <a href="setup_db.php" class="hover:text-[#537A72] transition-colors">MySQL / XAMPP</a>
        </div>
      </div>
    </div>
  </footer>

  <!-- SCRIPT GLOBAL JS -->
  <script src="assets/js/main.js"></script>
</body>
</html>
