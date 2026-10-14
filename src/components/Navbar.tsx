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
    { id: 'explorer', name: '2. Explorador de Camellos', tag: 'Filtros' },
    { id: 'job-detail', name: '3. Detalle de Vacante & Postulación', tag: 'Oferta' },
    { id: 'company-profile', name: '4. Perfil de Empresa (Stark Cloud)', tag: 'Cultura' },
    { id: 'salary-calculator', name: '5. Calculadora Salarial 2025', tag: 'Benchmark' },
    { id: 'candidate-portal', name: '6. Mis Postulaciones (Talento)', tag: 'Candidato' },
    { id: 'recruiter-dashboard', name: '7. Panel Reclutamiento & B2B', tag: 'Empresas' },
    { id: 'create-job', name: '8. Publicar Vacante (Wizard)', tag: 'Copiloto IA' },
  ];

  return (
    <>
      <header className="fixed top-0 left-0 right-0 z-50 bg-[#fff8f5]/90 backdrop-blur-xl border-b border-[#e1bfb5]/40 shadow-[0_1px_8px_rgba(40,36,33,0.06)]">
        <div className="h-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between gap-4">
          
          {/* Logo & Brand */}
          <div className="flex items-center gap-6">
            <button
              onClick={() => setScreen('home')}
              className="flex items-center gap-3 text-left group focus:outline-none"
            >
              <CamelLogo size="md" />
              <div className="flex flex-col leading-none">
                <span className="font-headline text-xl text-[#1f1b18] font-bold tracking-tight group-hover:text-[#a83301] transition-colors">
                  Échele Camello
                </span>
                <span className="text-[10px] text-[#545f73] tracking-widest uppercase font-semibold">
                  Colombia &amp; LATAM
                </span>
              </div>
            </button>

            {/* Desktop Navigation */}
            <nav className="hidden lg:flex items-center gap-1 bg-[#f5ece7]/60 p-1 rounded-xl">
              <button
                onClick={() => setScreen('home')}
                className={`px-3 py-1.5 rounded-lg text-sm font-semibold transition-all ${
                  currentScreen === 'home'
                    ? 'bg-[#efe6e1] text-[#a83301] shadow-xs'
                    : 'text-[#59413a] hover:text-[#1f1b18] hover:bg-[#efe6e1]/50'
                }`}
              >
                Inicio
              </button>

              <button
                onClick={() => setScreen('explorer')}
                className={`px-3 py-1.5 rounded-lg text-sm font-semibold transition-all ${
                  currentScreen === 'explorer'
                    ? 'bg-[#efe6e1] text-[#a83301] shadow-xs'
                    : 'text-[#59413a] hover:text-[#1f1b18] hover:bg-[#efe6e1]/50'
                }`}
              >
                Buscar Camello
              </button>

              <button
                onClick={() => setScreen('company-profile')}
                className={`px-3 py-1.5 rounded-lg text-sm font-semibold transition-all ${
                  currentScreen === 'company-profile'
                    ? 'bg-[#efe6e1] text-[#a83301] shadow-xs'
                    : 'text-[#59413a] hover:text-[#1f1b18] hover:bg-[#efe6e1]/50'
                }`}
              >
                Empresas
              </button>

              <button
                onClick={() => setScreen('salary-calculator')}
                className={`px-3 py-1.5 rounded-lg text-sm font-semibold transition-all ${
                  currentScreen === 'salary-calculator'
                    ? 'bg-[#efe6e1] text-[#a83301] shadow-xs'
                    : 'text-[#59413a] hover:text-[#1f1b18] hover:bg-[#efe6e1]/50'
                }`}
              >
                Salarios &amp; Calculadora
              </button>

              <button
                onClick={() => setScreen('candidate-portal')}
                className={`px-3 py-1.5 rounded-lg text-sm font-semibold transition-all ${
                  currentScreen === 'candidate-portal'
                    ? 'bg-[#efe6e1] text-[#a83301] shadow-xs'
                    : 'text-[#59413a] hover:text-[#1f1b18] hover:bg-[#efe6e1]/50'
                }`}
              >
                Mis Postulaciones
              </button>
            </nav>
          </div>

          {/* Right Action Cluster */}
          <div className="flex items-center gap-3">
            {/* Quick Screen Navigator Pill */}
            <div className="relative">
              <button
                onClick={() => setShowScreenPicker(!showScreenPicker)}
                className="hidden xl:inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-[#f5ece7] hover:bg-[#eae1dc] text-[#59413a] text-xs font-bold transition-colors"
                title="Cambiar pantalla de la demo"
              >
                <span className="material-symbols-outlined text-[16px] text-[#a83301]">apps</span>
                <span>Pantallas ({screensList.length})</span>
                <span className="material-symbols-outlined text-[14px]">expand_more</span>
              </button>

              {showScreenPicker && (
                <div className="absolute right-0 mt-2 w-72 bg-white rounded-xl shadow-xl border border-[#e1bfb5] py-2 z-50">
                  <div className="px-3 py-1.5 border-b border-[#f5ece7] flex items-center justify-between">
                    <span className="text-xs font-bold text-[#1f1b18] uppercase tracking-wider">
                      Vistas Diseñadas
                    </span>
                    <span className="text-[10px] bg-[#ffdbd0] text-[#842500] font-bold px-1.5 py-0.5 rounded">
                      8 Pantallas
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
                        className={`w-full px-3 py-2 text-left flex items-center justify-between text-xs transition-colors ${
                          currentScreen === s.id
                            ? 'bg-[#ffdbd0]/40 text-[#a83301] font-bold'
                            : 'text-[#59413a] hover:bg-[#fbf2ed] hover:text-[#1f1b18]'
                        }`}
                      >
                        <span className="truncate">{s.name}</span>
                        <span className="text-[10px] px-1.5 py-0.5 rounded bg-[#f5ece7] text-[#545f73] font-semibold">
                          {s.tag}
                        </span>
                      </button>
                    ))}
                  </div>
                </div>
              )}
            </div>

            {/* Recruiter Dashboard Shortcut */}
            <button
              onClick={() => setScreen('recruiter-dashboard')}
              className={`hidden md:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold transition-all ${
                currentScreen === 'recruiter-dashboard'
                  ? 'bg-[#34302c] text-white shadow-xs'
                  : 'bg-[#f5ece7] hover:bg-[#eae1dc] text-[#1f1b18]'
              }`}
            >
              <span className="material-symbols-outlined text-[16px] text-[#a83301]">business</span>
              <span>Portal B2B</span>
            </button>

            {/* Primary CTA: Publicar Vacante */}
            <button
              onClick={() => setScreen('create-job')}
              className="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg bg-[#a83301] hover:bg-[#ca4a1c] text-white text-sm font-semibold transition-all shadow-sm active:scale-95"
            >
              <span className="material-symbols-outlined text-[18px]">add</span>
              <span className="hidden sm:inline">Publicar Vacante</span>
            </button>

            {/* Notifications trigger */}
            <button
              onClick={onOpenNotifications}
              className="w-9 h-9 rounded-lg flex items-center justify-center text-[#59413a] hover:bg-[#f5ece7] hover:text-[#1f1b18] transition-colors relative"
              title="Notificaciones"
            >
              <span className="material-symbols-outlined text-[20px]">notifications</span>
              <span className="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-[#a83301] ring-2 ring-[#fff8f5]"></span>
            </button>

            {/* Candidate User Avatar */}
            <button
              onClick={() => setScreen('candidate-portal')}
              className="w-9 h-9 rounded-full bg-[#a83301] hover:bg-[#ca4a1c] flex items-center justify-center text-white shadow-xs transition-transform hover:scale-105"
              title="Mi Perfil de Camellador"
            >
              <span className="material-symbols-outlined text-[18px]">person</span>
            </button>

            {/* Mobile menu button */}
            <button
              onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
              className="lg:hidden p-2 rounded-lg text-[#59413a] hover:bg-[#f5ece7]"
            >
              <span className="material-symbols-outlined text-[24px]">
                {mobileMenuOpen ? 'close' : 'menu'}
              </span>
            </button>
          </div>
        </div>

        {/* Mobile menu dropdown */}
        {mobileMenuOpen && (
          <div className="lg:hidden bg-[#fff8f5] border-b border-[#e1bfb5] px-4 pt-2 pb-4 flex flex-col gap-2">
            <div className="text-xs font-bold uppercase text-[#8d7168] px-2 py-1">Navegación</div>
            <button
              onClick={() => { setScreen('home'); setMobileMenuOpen(false); }}
              className="text-left px-3 py-2 rounded-lg font-semibold text-[#1f1b18] hover:bg-[#f5ece7]"
            >
              Inicio
            </button>
            <button
              onClick={() => { setScreen('explorer'); setMobileMenuOpen(false); }}
              className="text-left px-3 py-2 rounded-lg font-semibold text-[#1f1b18] hover:bg-[#f5ece7]"
            >
              Buscar Camello (Explorador)
            </button>
            <button
              onClick={() => { setScreen('company-profile'); setMobileMenuOpen(false); }}
              className="text-left px-3 py-2 rounded-lg font-semibold text-[#1f1b18] hover:bg-[#f5ece7]"
            >
              Empresas (Stark Cloud)
            </button>
            <button
              onClick={() => { setScreen('salary-calculator'); setMobileMenuOpen(false); }}
              className="text-left px-3 py-2 rounded-lg font-semibold text-[#1f1b18] hover:bg-[#f5ece7]"
            >
              Salarios &amp; Calculadora 2025
            </button>
            <button
              onClick={() => { setScreen('candidate-portal'); setMobileMenuOpen(false); }}
              className="text-left px-3 py-2 rounded-lg font-semibold text-[#1f1b18] hover:bg-[#f5ece7]"
            >
              Mis Postulaciones (Panel de Camellos)
            </button>
            <button
              onClick={() => { setScreen('recruiter-dashboard'); setMobileMenuOpen(false); }}
              className="text-left px-3 py-2 rounded-lg font-semibold text-[#a83301] hover:bg-[#ffdbd0]"
            >
              Portal Reclutador B2B &amp; Talento
            </button>
            <button
              onClick={() => { setScreen('create-job'); setMobileMenuOpen(false); }}
              className="text-left px-3 py-2 rounded-lg font-semibold bg-[#a83301] text-white"
            >
              Publicar Nuevo Camello (Wizard)
            </button>
          </div>
        )}
      </header>
    </>
  );
};
