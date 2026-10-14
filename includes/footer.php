  </main>

  <  <!-- FOOTER GLOBAL Y2K -->
  <footer class="w-full bg-[#05040a] text-[#f8fafc] mt-20 pt-16 pb-12 border-t border-[#00f0ff]/30 shadow-[0_-10px_40px_rgba(0,0,0,0.8)]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 pb-12 border-b border-white/10">
        
        <!-- Brand & Mission Column -->
        <div class="lg:col-span-2">
          <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 rounded-2xl bg-[#16122d] p-1 border border-[#00f0ff]/50 shadow-[0_0_15px_rgba(0,240,255,0.4)] flex items-center justify-center shrink-0 overflow-hidden">
              <img src="assets/images/logo-camello.jpg" alt="Logo Camello" class="w-full h-full object-contain filter drop-shadow-[0_0_6px_rgba(0,240,255,0.5)]" />
            </div>
            <div>
              <span class="font-headline font-extrabold text-2xl text-white tracking-tight flex items-center gap-2">
                Échele Camello
                <span class="text-xs px-2 py-0.5 rounded-full bg-[#ff007f] text-white font-mono">2000</span>
              </span>
              <span class="text-xs text-[#00f0ff] block font-mono mt-0.5">✦ CYBER-TALENTO & SALARIOS LATAM ✦</span>
            </div>
          </div>
          
          <p class="text-xs sm:text-sm text-neutral-400 max-w-sm leading-relaxed mb-6 font-body">
            Ecosistema de movilidad laboral retro-futurista para Colombia y LATAM. Cero ofertas fantasma, rangos salariales transparentes y conexión directa con el futuro del trabajo.
          </p>

          <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#16122d] border border-[#00f0ff]/30 text-xs text-[#00f0ff] font-mono">
              <span class="w-2 h-2 rounded-full bg-[#00ff88] animate-pulse"></span>
              MYSQL_XAMPP // ONLINE ✦
            </span>
          </div>
        </div>

        <!-- Columna 1: Para Quienes Buscan Camello -->
        <div>
          <h4 class="font-headline text-xs font-bold text-[#00f0ff] uppercase tracking-wider mb-4 font-mono">
            [ 01_CANDIDATOS ]
          </h4>
          <ul class="space-y-2.5 text-xs text-neutral-400 font-body">
            <li><a href="explorer.php" class="hover:text-[#00f0ff] transition-colors">Explorar Vacantes</a></li>
            <li><a href="explorer.php?modality=remoto" class="hover:text-[#00f0ff] transition-colors">Trabajo 100% Remoto</a></li>
            <li><a href="salary-calculator.php" class="hover:text-[#00f0ff] transition-colors">Calculadora Salarial CST</a></li>
            <li><a href="candidate-portal.php" class="hover:text-[#00f0ff] transition-colors">Mi Panel de Camellos</a></li>
            <li><a href="explorer.php?category=tech" class="hover:text-[#00f0ff] transition-colors">Vacantes Tech & Software</a></li>
          </ul>
        </div>

        <!-- Columna 2: Para Empresas & Reclutadores -->
        <div>
          <h4 class="font-headline text-xs font-bold text-[#ff007f] uppercase tracking-wider mb-4 font-mono">
            [ 02_EMPRESAS ]
          </h4>
          <ul class="space-y-2.5 text-xs text-neutral-400 font-body">
            <li><a href="create-job.php" class="hover:text-[#ff007f] transition-colors">Publicar una Oferta</a></li>
            <li><a href="recruiter.php" class="hover:text-[#ff007f] transition-colors">Panel Reclutador Enterprise</a></li>
            <li><a href="company.php" class="hover:text-[#ff007f] transition-colors">Página de Empresa Verificada</a></li>
            <li><a href="recruiter.php#copilot" class="hover:text-[#ff007f] transition-colors">Copiloto IA de Contratación</a></li>
            <li><a href="setup_db.php" class="hover:text-[#00f0ff] transition-colors text-[#00f0ff]">Sincronizar BD MySQL</a></li>
          </ul>
        </div>

        <!-- Columna 3: Información & Legal -->
        <div>
          <h4 class="font-headline text-xs font-bold text-[#00ff88] uppercase tracking-wider mb-4 font-mono">
            [ 03_RED_LATAM ]
          </h4>
          <p class="text-xs text-neutral-400 mb-3 leading-relaxed">
            Conectando el talento más berraco y pilo de Colombia con oportunidades remotas globales.
          </p>
          <div class="flex items-center gap-2 text-xs font-mono text-[#e2e8f0]">
            <span>🇨🇴 COLOMBIA</span>
            <span>•</span>
            <span>🌎 LATAM</span>
            <span>•</span>
            <span class="text-[#00f0ff]">⚡ 2000</span>
          </div>
        </div>

      </div>

      <!-- Bottom Bar -->
      <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-neutral-500 font-mono">
        <p>© 2000-<?php echo date('Y'); ?> Échele Camello S.A.S. // Todos los derechos reservados.</p>
        <div class="flex items-center gap-6">
          <a href="explorer.php" class="hover:text-[#00f0ff] transition-colors">Términos Cyber</a>
          <a href="salary-calculator.php" class="hover:text-[#00f0ff] transition-colors">Transparencia CST</a>
          <a href="setup_db.php" class="hover:text-[#ff007f] transition-colors">Estado XAMPP / MySQL</a>
        </div>
      </div>
    </div>
  </footer>

  <!-- MODAL / DRAWER DE NOTIFICACIONES Y2K -->
  <div id="notifications-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75 backdrop-blur-md hidden animate-fade-in">
    <div class="bg-[#120e26] rounded-3xl max-w-md w-full p-6 shadow-[0_0_40px_rgba(0,240,255,0.3)] border border-[#00f0ff]/40 relative animate-scale-up">
      
      <!-- Retro OS Window Bar -->
      <div class="y2k-window-bar -mx-6 -mt-6 mb-4 rounded-t-3xl">
        <div class="y2k-window-dots">
          <div class="y2k-window-dot y2k-dot-red"></div>
          <div class="y2k-window-dot y2k-dot-yellow"></div>
          <div class="y2k-window-dot y2k-dot-green"></div>
        </div>
        <span>[ NOTIFICACIONES_SYSTEM_v2.0 ]</span>
        <button onclick="toggleNotifications(false)" class="text-neutral-400 hover:text-white">✕</button>
      </div>

      <div class="flex items-center justify-between pb-3 border-b border-white/10 mb-4">
        <div class="flex items-center gap-2">
          <span class="material-symbols-outlined text-[#ff007f]">notifications_active</span>
          <h3 class="font-bold font-headline text-base text-white">
            Notificaciones de Camello
          </h3>
        </div>
      </div>

      <div class="space-y-3 font-body text-xs mb-6">
        <a href="candidate-portal.php" class="block p-3 rounded-2xl bg-[#ff007f]/10 border border-[#ff007f]/40 hover:border-[#ff007f] transition-colors">
          <p class="font-bold text-[#ff007f] flex items-center gap-1 font-headline">
            <span class="material-symbols-outlined text-sm">workspace_premium</span>
            ¡Oferta formal de Stark Cloud Corp!
          </p>
          <p class="text-neutral-300 mt-0.5">
            USD $4,200 / mes lista para tu revisión y firma digital antes del viernes.
          </p>
          <span class="text-[10px] text-[#00f0ff] font-mono mt-1 block">✦ HACE 2 HORAS</span>
        </a>

        <a href="candidate-portal.php" class="block p-3 rounded-2xl bg-[#00f0ff]/10 border border-[#00f0ff]/30 hover:border-[#00f0ff] transition-colors">
          <p class="font-bold text-[#00f0ff] flex items-center gap-1 font-headline">
            <span class="material-symbols-outlined text-sm">video_call</span>
            Entrevista agendada con Nubank
          </p>
          <p class="text-neutral-300 mt-0.5">
            Jueves 24 Oct, 3:00 PM (45 min) con Tomás Restrepo (VP Engineering).
          </p>
          <span class="text-[10px] text-[#00f0ff] font-mono mt-1 block">✦ AYER</span>
        </a>
      </div>

      <button onclick="toggleNotifications(false)" class="w-full py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-xs font-bold text-white transition-colors border border-white/20 font-mono">
        [ CERRAR VENTANA ]
      </button>
    </div>
  </div>

  <!-- SCRIPT GLOBAL JS -->
  <script src="assets/js/main.js"></script>
</body>
</html>
