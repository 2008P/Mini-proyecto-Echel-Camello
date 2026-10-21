import React, { useState } from 'react';
import { AppScreen } from '../types';
import { CamelLogo } from './CamelLogo';

interface NavbarProps {
  currentScreen: AppScreen;
  setScreen: (screen: AppScreen) => void;
  onOpenNotifications?: () => void;
  savedJobsCount?: number;
}

export const Navbar: React.FC<NavbarProps> = ({
  currentScreen,
  setScreen,
  onOpenNotifications,
  savedJobsCount = 3,
}) => {
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
  const [showScreenPicker, setShowScreenPicker] = useState(false);

  const screensList: { id: AppScreen; name: string; tag: string }[] = [
    { id: 'home', name: '1. Inicio / Landing', tag: 'Principal' },
    { id: 'explorer', name: '2. Explorador de Camellos', tag: 'Strategy' },
    { id: 'requests', name: '3. Gestor de Solicitudes', tag: 'State Pattern' },
    { id: 'messages', name: '4. Mensajería en Vivo', tag: 'Chat & Archivos' },
    { id: 'job-detail', name: '5. Detalle de Vacante', tag: 'Oferta' },
    { id: 'company-profile', name: '6. Perfil de Empresa', tag: 'Cultura' },
    { id: 'salary-calculator', name: '7. Calculadora Salarial', tag: 'Benchmark' },
    { id: 'candidate-portal', name: '8. Mis Postulaciones', tag: 'Candidato' },
    { id: 'recruiter-dashboard', name: '9. Portal Reclutador', tag: 'Empresas' },
    { id: 'create-job', name: '10. Publicar Vacante', tag: 'Wizard' },
    { id: 'login', name: '11. Iniciar Sesión & Roles', tag: 'Seguridad' },
    { id: 'register', name: '12. Registro de Usuario', tag: 'Adapter SMTP' },
    { id: 'recover-password', name: '13. Recuperar Clave', tag: 'Adapter SMTP' }
  ];

  return (
    <>
      <header className="fixed top-0 left-0 right-0 z-50 bg-white/90 backdrop-blur-xl border-b border-[#E8E2D8] shadow-[0_2px_12px_rgba(44,39,36,0.03)] font-body">
        <div className="h-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between gap-4">
          
          {/* Logo & Brand */}
          <div className="flex items-center gap-5">
            <button
              onClick={() => setScreen('home')}
              className="flex items-center gap-3 text-left group focus:outline-none cursor-pointer"
            >
              <CamelLogo size="md" />
              <div className="flex flex-col leading-none">
                <span className="font-headline text-xl text-[#2C2724] font-bold tracking-tight group-hover:text-[#6B9080] transition-colors flex items-center gap-1.5">
                  Échele Camello
                  <span className="text-[10px] px-2 py-0.5 rounded-full bg-[#EAF4F4] text-[#537A72] border border-[#CCE3DE] font-semibold">
                    Pastel
                  </span>
                </span>
                <span className="text-[10px] text-[#8C847C] tracking-wider uppercase font-semibold mt-0.5">
                  COLOMBIA &amp; LATAM
                </span>
              </div>
            </button>

            {/* Desktop Navigation */}
            <nav className="hidden lg:flex items-center gap-1 bg-[#F6F3EC]/80 p-1.5 rounded-2xl border border-[#E8E2D8]">
              <button
                onClick={() => setScreen('home')}
                className={`px-3 py-1.5 rounded-xl text-xs font-semibold font-headline transition-all cursor-pointer ${
                  currentScreen === 'home'
                    ? 'bg-white text-[#2C2724] shadow-xs font-bold'
                    : 'text-[#7A726A] hover:text-[#2C2724] hover:bg-white/60'
                }`}
              >
                Inicio
              </button>

              <button
                onClick={() => setScreen('explorer')}
                className={`px-3 py-1.5 rounded-xl text-xs font-semibold font-headline transition-all cursor-pointer ${
                  currentScreen === 'explorer'
                    ? 'bg-white text-[#2C2724] shadow-xs font-bold'
                    : 'text-[#7A726A] hover:text-[#2C2724] hover:bg-white/60'
                }`}
              >
                Explorar
              </button>

              <button
                onClick={() => setScreen('requests')}
                className={`px-3 py-1.5 rounded-xl text-xs font-semibold font-headline transition-all flex items-center gap-1 cursor-pointer ${
                  currentScreen === 'requests'
                    ? 'bg-white text-[#2C2724] shadow-xs font-bold'
                    : 'text-[#7A726A] hover:text-[#2C2724] hover:bg-white/60'
                }`}
              >
                <span className="material-symbols-outlined text-[15px] text-[#E79E78]">sync_alt</span>
                <span>Solicitudes</span>
              </button>

              <button
                onClick={() => setScreen('messages')}
                className={`px-3 py-1.5 rounded-xl text-xs font-semibold font-headline transition-all flex items-center gap-1 cursor-pointer ${
                  currentScreen === 'messages'
                    ? 'bg-white text-[#2C2724] shadow-xs font-bold'
                    : 'text-[#7A726A] hover:text-[#2C2724] hover:bg-white/60'
                }`}
              >
                <span className="material-symbols-outlined text-[15px] text-[#655B8B]">chat_bubble</span>
                <span>Mensajes</span>
              </button>

              <button
                onClick={() => setScreen('salary-calculator')}
                className={`px-3 py-1.5 rounded-xl text-xs font-semibold font-headline transition-all cursor-pointer ${
                  currentScreen === 'salary-calculator'
                    ? 'bg-white text-[#2C2724] shadow-xs font-bold'
                    : 'text-[#7A726A] hover:text-[#2C2724] hover:bg-white/60'
                }`}
              >
                Calculadora
              </button>
            </nav>
          </div>

          {/* Right Action Cluster */}
          <div className="flex items-center gap-2.5">
            {/* Quick Screen Navigator Pill */}
            <div className="relative">
              <button
                onClick={() => setShowScreenPicker(!showScreenPicker)}
                className="hidden xl:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white border border-[#E8E2D8] hover:border-[#CCE3DE] text-[#56504A] text-xs font-semibold shadow-xs transition-colors cursor-pointer"
                title="Cambiar pantalla de la demo"
              >
                <span className="material-symbols-outlined text-[16px] text-[#6B9080]">auto_stories</span>
                <span>Módulos ({screensList.length})</span>
                <span className="material-symbols-outlined text-[14px]">expand_more</span>
              </button>

              {showScreenPicker && (
                <div className="absolute right-0 mt-2 w-72 bg-white rounded-2xl shadow-xl border border-[#E8E2D8] py-2 z-50 animate-fade-in">
                  <div className="px-3.5 py-2 border-b border-[#F0EAE1] flex items-center justify-between">
                    <span className="text-xs font-bold text-[#2C2724] uppercase tracking-wider">
                      Arquitectura de Patrones
                    </span>
                    <span className="text-[10px] bg-[#EAF4F4] text-[#537A72] font-bold px-2 py-0.5 rounded-full border border-[#CCE3DE]">
                      05_PATRONES
                    </span>
                  </div>
                  <div className="max-h-80 overflow-y-auto py-1">
                    {screensList.map((s) => (
                      <button
                        key={s.id}
                        onClick={() => {
                          setScreen(s.id);
                          setShowScreenPicker(false);
                        }}
                        className={`w-full px-3.5 py-2 text-left flex items-center justify-between text-xs transition-colors cursor-pointer ${
                          currentScreen === s.id
                            ? 'bg-[#EAF4F4] text-[#537A72] font-bold'
                            : 'text-[#56504A] hover:bg-[#F6F3EC]'
                        }`}
                      >
                        <span className="truncate">{s.name}</span>
                        <span className="text-[10px] px-2 py-0.5 rounded-full bg-white border border-[#E8E2D8] text-[#7A726A] font-semibold">
                          {s.tag}
                        </span>
                      </button>
                    ))}
                  </div>
                </div>
              )}
            </div>

            {/* Auth / Login Button */}
            <button
              onClick={() => setScreen('login')}
              className={`px-3 py-1.5 rounded-xl text-xs font-bold transition-all cursor-pointer ${
                currentScreen === 'login' || currentScreen === 'register'
                  ? 'bg-[#EAF4F4] text-[#537A72] border border-[#CCE3DE]'
                  : 'text-[#56504A] hover:bg-[#F6F3EC]'
              }`}
            >
              Iniciar Sesión
            </button>

            {/* Primary CTA: Publicar Vacante */}
            <button
              onClick={() => setScreen('create-job')}
              className="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 rounded-xl bg-[#6B9080] hover:bg-[#5A7C6E] text-white text-xs font-bold transition-all shadow-sm active:scale-95 cursor-pointer"
            >
              <span className="material-symbols-outlined text-[17px]">add_circle</span>
              <span className="hidden sm:inline">Publicar</span>
            </button>

            {/* Notifications trigger */}
            <button
              onClick={onOpenNotifications}
              className="w-9 h-9 rounded-xl border border-[#E8E2D8] bg-white flex items-center justify-center text-[#537A72] hover:bg-[#EAF4F4] transition-colors relative cursor-pointer shadow-xs"
              title="Notificaciones"
            >
              <span className="material-symbols-outlined text-[19px]">notifications</span>
              <span className="absolute -top-0.5 -right-0.5 w-2.5 h-2.5 rounded-full bg-[#E79E78] ring-2 ring-white"></span>
            </button>

            {/* Candidate User Avatar */}
            <button
              onClick={() => setScreen('candidate-portal')}
              className="w-9 h-9 rounded-xl bg-[#F6F3EC] border border-[#CCE3DE] hover:bg-[#EAF4F4] flex items-center justify-center text-[#537A72] shadow-xs transition-transform hover:scale-105 cursor-pointer"
              title="Mi Perfil de Camellador"
            >
              <span className="material-symbols-outlined text-[18px]">person</span>
            </button>

            {/* Mobile menu button */}
            <button
              onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
              className="lg:hidden p-2 rounded-xl text-[#56504A] hover:bg-[#F6F3EC] cursor-pointer"
            >
              <span className="material-symbols-outlined text-[22px]">
                {mobileMenuOpen ? 'close' : 'menu'}
              </span>
            </button>
          </div>
        </div>

        {/* Mobile menu dropdown */}
        {mobileMenuOpen && (
          <div className="lg:hidden bg-white border-b border-[#E8E2D8] px-4 pt-2 pb-4 flex flex-col gap-2">
            <div className="text-xs font-bold uppercase text-[#8C847C] px-2 py-1">Navegación Pastel</div>
            <button
              onClick={() => { setScreen('home'); setMobileMenuOpen(false); }}
              className="text-left px-3 py-2 rounded-xl font-semibold text-[#2C2724] hover:bg-[#F6F3EC]"
            >
              Inicio
            </button>
            <button
              onClick={() => { setScreen('explorer'); setMobileMenuOpen(false); }}
              className="text-left px-3 py-2 rounded-xl font-semibold text-[#2C2724] hover:bg-[#F6F3EC]"
            >
              Buscar Camello (Explorador)
            </button>
            <button
              onClick={() => { setScreen('requests'); setMobileMenuOpen(false); }}
              className="text-left px-3 py-2 rounded-xl font-semibold text-[#537A72] bg-[#EAF4F4] hover:bg-[#CCE3DE]"
            >
              Solicitudes (State Pattern)
            </button>
            <button
              onClick={() => { setScreen('messages'); setMobileMenuOpen(false); }}
              className="text-left px-3 py-2 rounded-xl font-semibold text-[#655B8B] hover:bg-[#F2F4FB]"
            >
              Mensajería en Vivo
            </button>
            <button
              onClick={() => { setScreen('login'); setMobileMenuOpen(false); }}
              className="text-left px-3 py-2 rounded-xl font-semibold text-[#A25844] hover:bg-[#FDF5F2]"
            >
              Iniciar Sesión / Roles
            </button>
            <button
              onClick={() => { setScreen('create-job'); setMobileMenuOpen(false); }}
              className="text-left px-3 py-2 rounded-xl font-semibold bg-[#6B9080] text-white"
            >
              Publicar Nuevo Camello
            </button>
          </div>
        )}
      </header>
    </>
  );
};
