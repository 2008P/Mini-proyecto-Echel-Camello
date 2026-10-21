import React from 'react';
import { AppScreen } from '../types';
import { CamelLogo } from './CamelLogo';

interface FooterProps {
  setScreen: (screen: AppScreen) => void;
}

export const Footer: React.FC<FooterProps> = ({ setScreen }) => {
  return (
    <footer className="w-full bg-[#F5F1EA] text-[#56504A] border-t border-[#E8E2D8] mt-16 sm:mt-24 font-body">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-12">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 pb-12 border-b border-[#E8E2D8]">
          
          {/* Brand Info */}
          <div className="lg:col-span-2 flex flex-col gap-4">
            <div className="flex items-center gap-3">
              <CamelLogo size="md" />
              <span className="font-headline text-xl text-[#2C2724] font-bold tracking-tight">
                Échele Camello
              </span>
            </div>
            
            <p className="text-sm text-[#7A726A] max-w-sm leading-relaxed">
              El ecosistema digital líder para conectar el talento más calificado con oportunidades laborales reales en Colombia y LATAM. Dignidad, salarios claros y arquitectura de software orientada a patrones de diseño.
            </p>

            <div className="flex items-center gap-3 text-[#537A72]">
              <div className="flex items-center gap-2 mt-2 px-3 py-1.5 bg-white border border-[#CCE3DE] rounded-xl w-fit shadow-xs">
                <span className="material-symbols-outlined text-[#6B9080] text-[18px]">verified</span>
                <span className="text-xs text-[#2C2724] font-bold uppercase tracking-wider">
                  Patrones 05_PATRONES Activos
                </span>
              </div>
            </div>
          </div>

          {/* Oportunidades Column */}
          <div className="flex flex-col gap-2.5">
            <h3 className="font-headline text-base text-[#2C2724] font-bold">Oportunidades</h3>
            <button
              onClick={() => setScreen('explorer')}
              className="text-left text-sm text-[#7A726A] hover:text-[#537A72] transition-colors cursor-pointer"
            >
              Explorar Vacantes (Strategy)
            </button>
            <button
              onClick={() => setScreen('requests')}
              className="text-left text-sm text-[#537A72] font-semibold hover:underline transition-colors cursor-pointer"
            >
              Gestor Solicitudes (State)
            </button>
            <button
              onClick={() => setScreen('messages')}
              className="text-left text-sm text-[#655B8B] font-semibold hover:underline transition-colors cursor-pointer"
            >
              Mensajería &amp; Chat
            </button>
            <button
              onClick={() => setScreen('salary-calculator')}
              className="text-left text-sm text-[#7A726A] hover:text-[#537A72] transition-colors cursor-pointer"
            >
              Calculadora Salarial CST
            </button>
          </div>

          {/* Categorías Column */}
          <div className="flex flex-col gap-2.5">
            <h3 className="font-headline text-base text-[#2C2724] font-bold">Categorías</h3>
            <button
              onClick={() => setScreen('explorer')}
              className="text-left text-sm text-[#7A726A] hover:text-[#537A72] transition-colors cursor-pointer"
            >
              Tecnología &amp; Software
            </button>
            <button
              onClick={() => setScreen('explorer')}
              className="text-left text-sm text-[#7A726A] hover:text-[#537A72] transition-colors cursor-pointer"
            >
              Ventas &amp; Comercial
            </button>
            <button
              onClick={() => setScreen('explorer')}
              className="text-left text-sm text-[#7A726A] hover:text-[#537A72] transition-colors cursor-pointer"
            >
              Operaciones &amp; Logística
            </button>
            <button
              onClick={() => setScreen('explorer')}
              className="text-left text-sm text-[#7A726A] hover:text-[#537A72] transition-colors cursor-pointer"
            >
              Diseño &amp; Creatividad
            </button>
          </div>

          {/* Institucional & Herramientas */}
          <div className="flex flex-col gap-2.5">
            <h3 className="font-headline text-base text-[#2C2724] font-bold">Seguridad &amp; Cuenta</h3>
            <button
              onClick={() => setScreen('login')}
              className="text-left text-sm text-[#7A726A] hover:text-[#537A72] transition-colors cursor-pointer"
            >
              Iniciar Sesión
            </button>
            <button
              onClick={() => setScreen('register')}
              className="text-left text-sm text-[#7A726A] hover:text-[#537A72] transition-colors cursor-pointer"
            >
              Registrarse (Roles)
            </button>
            <button
              onClick={() => setScreen('recover-password')}
              className="text-left text-sm text-[#7A726A] hover:text-[#537A72] transition-colors cursor-pointer"
            >
              Recuperar Contraseña
            </button>
            <button
              onClick={() => setScreen('recruiter-dashboard')}
              className="text-left text-sm text-[#7A726A] hover:text-[#537A72] transition-colors cursor-pointer"
            >
              Portal Empresas B2B
            </button>
          </div>

        </div>

        {/* Bottom Bar */}
        <div className="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-[#8C847C]">
          <p>© 2026 Échele Camello — Desarrollado por Luis Yamit Tombe Cabrera (ADSO Ficha 3413988).</p>
          <div className="flex items-center gap-6">
            <button onClick={() => setScreen('explorer')} className="hover:text-[#537A72] transition-colors cursor-pointer">
              Términos
            </button>
            <button onClick={() => setScreen('salary-calculator')} className="hover:text-[#537A72] transition-colors cursor-pointer">
              Transparencia Salarial
            </button>
          </div>
        </div>

      </div>
    </footer>
  );
};
