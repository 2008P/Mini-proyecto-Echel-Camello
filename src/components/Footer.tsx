import React from 'react';
import { AppScreen } from '../types';
import { CamelLogo } from './CamelLogo';

interface FooterProps {
  setScreen: (screen: AppScreen) => void;
}

export const Footer: React.FC<FooterProps> = ({ setScreen }) => {
  return (
    <footer className="w-full bg-[#fbf2ed] text-[#59413a] border-t border-[#e1bfb5]/40 mt-16 sm:mt-24">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-12">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 pb-12 border-b border-[#e1bfb5]/40">
          
          {/* Brand Info */}
          <div className="lg:col-span-2 flex flex-col gap-4">
            <div className="flex items-center gap-3">
              <CamelLogo size="md" />
              <span className="font-headline text-xl text-[#1f1b18] font-bold tracking-tight">
                Échele Camello
              </span>
            </div>
            
            <p className="text-sm text-[#59413a] max-w-sm leading-relaxed">
              El ecosistema digital líder para conectar el talento más calificado con oportunidades laborales reales en toda la región. Dignidad, empuje y crecimiento profesional.
            </p>

            <div className="flex items-center gap-3 text-[#59413a]">
              <a
                href="#comunidad"
                aria-label="Comunidad"
                className="w-8 h-8 rounded-full bg-[#f5ece7] flex items-center justify-center hover:bg-[#eae1dc] hover:text-[#1f1b18] transition-colors"
              >
                <span className="material-symbols-outlined text-[18px]">hub</span>
              </a>
              <a
                href="#difusion"
                aria-label="Canal de difusión"
                className="w-8 h-8 rounded-full bg-[#f5ece7] flex items-center justify-center hover:bg-[#eae1dc] hover:text-[#1f1b18] transition-colors"
              >
                <span className="material-symbols-outlined text-[18px]">share</span>
              </a>
              <a
                href="#contacto"
                aria-label="Contacto directo"
                className="w-8 h-8 rounded-full bg-[#f5ece7] flex items-center justify-center hover:bg-[#eae1dc] hover:text-[#1f1b18] transition-colors"
              >
                <span className="material-symbols-outlined text-[18px]">alternate_email</span>
              </a>
            </div>

            <div className="flex items-center gap-2 mt-2 px-3 py-1.5 bg-[#f5ece7] rounded-lg w-fit">
              <span className="material-symbols-outlined text-[#a83301] text-[18px]">verified</span>
              <span className="text-xs text-[#1f1b18] font-bold uppercase tracking-wider">
                Ecosistema Laboral Verificado
              </span>
            </div>
          </div>

          {/* Oportunidades Column */}
          <div className="flex flex-col gap-2.5">
            <h3 className="font-headline text-base text-[#1f1b18] font-bold">Oportunidades</h3>
            <button
              onClick={() => setScreen('explorer')}
              className="text-left text-sm text-[#59413a] hover:text-[#a83301] transition-colors"
            >
              Vacantes Recientes
            </button>
            <button
              onClick={() => setScreen('explorer')}
              className="text-left text-sm text-[#59413a] hover:text-[#a83301] transition-colors"
            >
              Camello Remoto (LATAM / USD)
            </button>
            <button
              onClick={() => setScreen('salary-calculator')}
              className="text-left text-sm text-[#59413a] hover:text-[#a83301] transition-colors"
            >
              Calculadora Salarial
            </button>
            <button
              onClick={() => setScreen('company-profile')}
              className="text-left text-sm text-[#59413a] hover:text-[#a83301] transition-colors"
            >
              Top Empleadores Verificados
            </button>
          </div>

          {/* Categorías Column */}
          <div className="flex flex-col gap-2.5">
            <h3 className="font-headline text-base text-[#1f1b18] font-bold">Categorías</h3>
            <button
              onClick={() => setScreen('explorer')}
              className="text-left text-sm text-[#59413a] hover:text-[#a83301] transition-colors"
            >
              Tecnología &amp; Software
            </button>
            <button
              onClick={() => setScreen('explorer')}
              className="text-left text-sm text-[#59413a] hover:text-[#a83301] transition-colors"
            >
              Ventas &amp; Comercial
            </button>
            <button
              onClick={() => setScreen('explorer')}
              className="text-left text-sm text-[#59413a] hover:text-[#a83301] transition-colors"
            >
              Operaciones &amp; Logística
            </button>
            <button
              onClick={() => setScreen('explorer')}
              className="text-left text-sm text-[#59413a] hover:text-[#a83301] transition-colors"
            >
              Diseño &amp; Creatividad
            </button>
          </div>

          {/* Institucional & Herramientas */}
          <div className="flex flex-col gap-2.5">
            <h3 className="font-headline text-base text-[#1f1b18] font-bold">Herramientas B2B</h3>
            <button
              onClick={() => setScreen('recruiter-dashboard')}
              className="text-left text-sm text-[#59413a] hover:text-[#a83301] transition-colors"
            >
              Portal Corporativo Reclutador
            </button>
            <button
              onClick={() => setScreen('create-job')}
              className="text-left text-sm text-[#59413a] hover:text-[#a83301] transition-colors"
            >
              Publicar Nuevo Camello
            </button>
            <button
              onClick={() => setScreen('candidate-portal')}
              className="text-left text-sm text-[#59413a] hover:text-[#a83301] transition-colors"
            >
              Seguimiento de Postulación
            </button>
            <button
              onClick={() => setScreen('salary-calculator')}
              className="text-left text-sm text-[#59413a] hover:text-[#a83301] transition-colors"
            >
              Transparencia Salarial Radical
            </button>
          </div>

        </div>

        {/* Bottom Rights Bar */}
        <div className="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-[#59413a]">
          <p>© 2025 Échele Camello LATAM S.A.S. Todos los derechos reservados.</p>
          <div className="flex items-center gap-4">
            <span className="flex items-center gap-1">
              <span className="material-symbols-outlined text-[16px] text-[#a83301]">location_on</span>
              Bogotá • Medellín • Cali • Remoto LATAM
            </span>
            <span>•</span>
            <span className="flex items-center gap-1 font-semibold text-[#1f1b18]">
              <span className="material-symbols-outlined text-[15px] text-[#a83301]">verified</span>
              MinTrabajo &amp; Registro Mercantil
            </span>
          </div>
        </div>
      </div>
    </footer>
  );
};
