  </main>

  <!-- FOOTER GLOBAL -->
  <footer class="w-full bg-[#1f1b18] text-[#f8efea] mt-20 pt-16 pb-12 border-t border-neutral-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 pb-12 border-b border-neutral-800">
        
        <!-- Brand & Mission Column -->
        <div class="lg:col-span-2">
          <div class="flex items-center gap-3 mb-4">
            <div class="w-11 h-11 rounded-2xl bg-white p-1 border border-neutral-700 shadow-md flex items-center justify-center shrink-0 overflow-hidden">
              <img src="assets/images/logo-camello.jpg" alt="Logo Camello" class="w-full h-full object-contain" />
            </div>
            <div>
              <span class="font-headline font-extrabold text-2xl text-white tracking-tight">Échele Camello</span>
              <span class="text-xs text-[#ca4a1c] block font-semibold">Salarios Transparentes & Empleo Digno</span>
            </div>
          </div>
          
          <p className="text-sm text-neutral-400 max-w-sm leading-relaxed mb-6">
            La plataforma líder de movilidad laboral en Colombia y LATAM. Cero ofertas fantasma, rangos salariales transparentes y conexión directa con las empresas de mayor crecimiento.
          </p>

          <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-neutral-900 border border-neutral-800 text-xs text-neutral-300">
              <span class="w-2 h-2 rounded-full bg-[#16a34a]"></span>
              Base de Datos MySQL Sincronizada en XAMPP
            </span>
          </div>
        </div>

        <!-- Columna 1: Para Quienes Buscan Camello -->
        <div>
          <h4 class="font-headline text-sm font-bold text-white uppercase tracking-wider mb-4">
            Candidatos
          </h4>
          <ul class="space-y-2.5 text-xs text-neutral-400 font-body">
            <li><a href="explorer.php" class="hover:text-white transition-colors">Explorar Vacantes</a></li>
            <li><a href="explorer.php?modality=remoto" class="hover:text-white transition-colors">Trabajo 100% Remoto</a></li>
            <li><a href="salary-calculator.php" class="hover:text-white transition-colors">Calculadora Salarial CST</a></li>
            <li><a href="candidate-portal.php" class="hover:text-white transition-colors">Mi Panel de Camellos</a></li>
            <li><a href="explorer.php?category=tech" class="hover:text-white transition-colors">Vacantes Tech & Software</a></li>
          </ul>
        </div>

        <!-- Columna 2: Para Empresas & Reclutadores -->
        <div>
          <h4 class="font-headline text-sm font-bold text-white uppercase tracking-wider mb-4">
            Empresas
          </h4>
          <ul class="space-y-2.5 text-xs text-neutral-400 font-body">
            <li><a href="create-job.php" class="hover:text-white transition-colors">Publicar una Oferta</a></li>
            <li><a href="recruiter.php" class="hover:text-white transition-colors">Panel Reclutador Enterprise</a></li>
            <li><a href="company.php" class="hover:text-white transition-colors">Página de Empresa Verificada</a></li>
            <li><a href="recruiter.php#copilot" class="hover:text-white transition-colors">Copiloto IA de Contratación</a></li>
            <li><a href="setup_db.php" class="hover:text-white transition-colors text-[#ffb59d]">Sincronizar BD MySQL</a></li>
          </ul>
        </div>

        <!-- Columna 3: Información & Legal -->
        <div>
          <h4 class="font-headline text-sm font-bold text-white uppercase tracking-wider mb-4">
            Comunidad LATAM
          </h4>
          <p class="text-xs text-neutral-400 mb-3 leading-relaxed">
            Hecho con orgullo y berraquera para conectar el talento colombiano con el mundo.
          </p>
          <div class="flex items-center gap-2 text-xs font-semibold text-neutral-300">
            <span>🇨🇴 Colombia</span>
            <span>•</span>
            <span>🌎 LATAM</span>
            <span>•</span>
            <span>⚡ Remoto</span>
          </div>
        </div>

      </div>

      <!-- Bottom Bar -->
      <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-neutral-500">
        <p>© <?php echo date('Y'); ?> Échele Camello S.A.S. Todos los derechos reservados.</p>
        <div class="flex items-center gap-6">
          <a href="explorer.php" class="hover:text-neutral-300 transition-colors">Términos de Servicio</a>
          <a href="salary-calculator.php" class="hover:text-neutral-300 transition-colors">Transparencia Salarial</a>
          <a href="setup_db.php" class="hover:text-[#ca4a1c] transition-colors">Estado XAMPP / MySQL</a>
        </div>
      </div>
    </div>
  </footer>

  <!-- MODAL / DRAWER DE NOTIFICACIONES -->
  <div id="notifications-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm hidden animate-fade-in">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-[#e5e5e5] relative animate-scale-up">
      <div class="flex items-center justify-between pb-3 border-b border-[#f0f0f0] mb-4">
        <div class="flex items-center gap-2">
          <span class="material-symbols-outlined text-[#a83301]">notifications</span>
          <h3 class="font-bold font-headline text-base text-[#1f1b16]">
            Notificaciones de Camello
          </h3>
        </div>
        <button onclick="toggleNotifications(false)" class="w-8 h-8 rounded-full hover:bg-neutral-100 flex items-center justify-center text-[#737373] transition-colors">
          <span class="material-symbols-outlined text-lg">close</span>
        </button>
      </div>

      <div class="space-y-3 font-body text-xs mb-6">
        <a href="candidate-portal.php" class="block p-3 rounded-xl bg-[#fff4f0] border border-[#ffdbd0] hover:border-[#a83301] transition-colors">
          <p class="font-bold text-[#a83301] flex items-center gap-1">
            <span class="material-symbols-outlined text-sm">workspace_premium</span>
            ¡Oferta formal de Stark Cloud Corp!
          </p>
          <p class="text-[#525252] mt-0.5">
            USD $4,200 / mes lista para tu revisión y firma digital antes del viernes.
          </p>
          <span class="text-[10px] text-[#737373] mt-1 block">Hace 2 horas</span>
        </a>

        <a href="candidate-portal.php" class="block p-3 rounded-xl bg-neutral-50 border border-neutral-200 hover:border-[#0284c7] transition-colors">
          <p class="font-bold text-[#1f1b16] flex items-center gap-1">
            <span class="material-symbols-outlined text-sm text-[#0284c7]">video_call</span>
            Entrevista agendada con Nubank
          </p>
          <p class="text-[#525252] mt-0.5">
            Jueves 24 Oct, 3:00 PM (45 min) con Tomás Restrepo (VP Engineering).
          </p>
          <span class="text-[10px] text-[#737373] mt-1 block">Ayer</span>
        </a>

        <a href="explorer.php" class="block p-3 rounded-xl bg-neutral-50 border border-neutral-200 hover:border-[#16a34a] transition-colors">
          <p class="font-bold text-[#1f1b16] flex items-center gap-1">
            <span class="material-symbols-outlined text-sm text-[#16a34a]">campaign</span>
            8 nuevas vacantes en Tecnología
          </p>
          <p class="text-[#525252] mt-0.5">
            Empresas verificadas han publicado vacantes 100% remotas acordes a tu perfil.
          </p>
          <span class="text-[10px] text-[#737373] mt-1 block">Hace 2 días</span>
        </a>
      </div>

      <button onclick="toggleNotifications(false)" class="w-full py-2.5 rounded-xl bg-neutral-100 hover:bg-neutral-200 text-xs font-bold text-[#1f1b16] transition-colors">
        Cerrar
      </button>
    </div>
  </div>

  <!-- SCRIPT GLOBAL JS -->
  <script src="assets/js/main.js"></script>
</body>
</html>
