import React, { useState } from 'react';
import { AppScreen, UserApplication } from '../types';
import { MOCK_APPLICATIONS, MOCK_JOBS } from '../data/mockData';

interface CandidatePortalViewProps {
  setScreen: (screen: AppScreen) => void;
  onSelectJob: (jobId: string) => void;
}

export const CandidatePortalView: React.FC<CandidatePortalViewProps> = ({
  setScreen,
  onSelectJob,
}) => {
  const [applications, setApplications] = useState<UserApplication[]>(MOCK_APPLICATIONS);
  const [filterStatus, setFilterStatus] = useState<string>('all');
  const [activeModal, setActiveModal] = useState<{
    type: 'offer' | 'interview' | 'test' | null;
    app: UserApplication | null;
  }>({ type: null, app: null });
  const [offerSigned, setOfferSigned] = useState(false);

  const filteredApps = applications.filter(app => {
    if (filterStatus === 'all') return true;
    return app.status === filterStatus;
  });

  const handleOpenAction = (app: UserApplication) => {
    if (app.status === 'offer') {
      setActiveModal({ type: 'offer', app });
    } else if (app.status === 'interview') {
      setActiveModal({ type: 'interview', app });
    } else if (app.status === 'tech_test') {
      setActiveModal({ type: 'test', app });
    } else {
      onSelectJob(app.jobId);
    }
  };

  return (
    <div className="min-h-screen bg-[#fff8f6] py-8 text-[#1f1b16]">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Breadcrumb */}
        <nav className="flex items-center gap-2 text-sm text-[#737373] mb-6 font-body">
          <button onClick={() => setScreen('home')} className="hover:text-[#a83301]">
            Inicio
          </button>
          <span>/</span>
          <span className="text-[#1f1b16] font-medium">Mi Panel de Camellos</span>
        </nav>

        {/* User Profile Header */}
        <div className="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-[#e5e5e5] mb-8">
          <div className="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div className="flex items-center gap-5">
              <div className="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-gradient-to-tr from-[#a83301] to-[#ca4a1c] text-white flex items-center justify-center font-bold text-2xl shadow-md shrink-0">
                CP
              </div>
              <div>
                <div className="flex items-center gap-2 mb-1">
                  <h1 className="text-2xl sm:text-3xl font-extrabold text-[#1f1b16] font-headline">
                    Camilo Pérez
                  </h1>
                  <span className="material-symbols-outlined text-[#0284c7] text-xl" title="Perfil Verificado">
                    verified
                  </span>
                </div>
                <p className="text-sm text-[#525252] font-body">
                  Senior Frontend Developer • Medellín, Colombia (Disponible Inmediato)
                </p>
                <div className="flex items-center gap-3 mt-2">
                  <span className="inline-flex items-center gap-1 text-xs font-semibold text-[#16a34a] bg-[#f0fdf4] px-2.5 py-0.5 rounded-full border border-[#bbf7d0]">
                    <span className="w-2 h-2 rounded-full bg-[#16a34a] animate-pulse" />
                    Buscando camello activamente
                  </span>
                  <span className="text-xs text-[#737373]">
                    CV sincronizado con IA • 100% Completo
                  </span>
                </div>
              </div>
            </div>

            <div className="flex flex-wrap items-center gap-3">
              <button
                onClick={() => setScreen('salary-calculator')}
                className="px-4 py-2.5 rounded-xl border border-[#e5e5e5] hover:bg-neutral-50 text-xs font-bold font-headline text-[#525252] flex items-center gap-1.5 transition-colors"
              >
                <span className="material-symbols-outlined text-sm text-[#a83301]">calculate</span>
                Calculadora Salarial
              </button>
              <button
                onClick={() => setScreen('explorer')}
                className="px-5 py-2.5 rounded-xl bg-[#a83301] hover:bg-[#ca4a1c] text-white text-xs font-bold font-headline flex items-center gap-1.5 shadow-md transition-all"
              >
                <span className="material-symbols-outlined text-sm">search</span>
                Explorar Nuevas Vacantes
              </button>
            </div>
          </div>
        </div>

        {/* KPI Cards Row */}
        <div className="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
          <div className="bg-white p-5 rounded-2xl border border-[#e5e5e5] shadow-sm">
            <span className="text-xs font-bold text-[#737373] uppercase tracking-wider">
              Postulaciones Activas
            </span>
            <div className="text-2xl sm:text-3xl font-black text-[#1f1b16] font-headline mt-1">
              4
            </div>
            <span className="text-xs text-[#16a34a] font-medium flex items-center gap-1 mt-1">
              <span className="material-symbols-outlined text-xs">trending_up</span> 2 avanzadas esta semana
            </span>
          </div>

          <div className="bg-white p-5 rounded-2xl border border-[#bbf7d0] shadow-sm bg-gradient-to-br from-white to-[#f0fdf4]">
            <span className="text-xs font-bold text-[#166534] uppercase tracking-wider flex items-center gap-1">
              <span className="material-symbols-outlined text-sm">stars</span> Oferta Recibida
            </span>
            <div className="text-2xl sm:text-3xl font-black text-[#166534] font-headline mt-1">
              1
            </div>
            <span className="text-xs text-[#166534] font-medium flex items-center gap-1 mt-1">
              Stark Cloud Corp ($4,200 USD)
            </span>
          </div>

          <div className="bg-white p-5 rounded-2xl border border-[#e5e5e5] shadow-sm">
            <span className="text-xs font-bold text-[#737373] uppercase tracking-wider">
              Entrevistas Próximas
            </span>
            <div className="text-2xl sm:text-3xl font-black text-[#0284c7] font-headline mt-1">
              1
            </div>
            <span className="text-xs text-[#525252] font-medium flex items-center gap-1 mt-1">
              Jueves 24 Oct, 3:00 PM (Nubank)
            </span>
          </div>

          <div className="bg-white p-5 rounded-2xl border border-[#e5e5e5] shadow-sm">
            <span className="text-xs font-bold text-[#737373] uppercase tracking-wider">
              Visualizaciones de Perfil
            </span>
            <div className="text-2xl sm:text-3xl font-black text-[#a83301] font-headline mt-1">
              128
            </div>
            <span className="text-xs text-[#16a34a] font-medium flex items-center gap-1 mt-1">
              +34% vs. mes anterior
            </span>
          </div>
        </div>

        {/* Priority Action Card (Offer Available) */}
        {!offerSigned && (
          <div className="bg-gradient-to-r from-[#fff4f0] via-[#ffdbd0]/60 to-[#fff4f0] border-2 border-[#f5b8a5] rounded-3xl p-6 sm:p-7 shadow-md mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div className="flex items-start gap-4">
              <div className="w-12 h-12 rounded-2xl bg-[#a83301] text-white flex items-center justify-center shrink-0 shadow-md">
                <span className="material-symbols-outlined text-2xl">workspace_premium</span>
              </div>
              <div>
                <span className="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#a83301] text-white mb-1.5">
                  ¡Carta de Oferta Disponible!
                </span>
                <h3 className="text-lg font-black text-[#1f1b16] font-headline">
                  Stark Cloud Corp te ha seleccionado para el rol de Senior Frontend Developer
                </h3>
                <p className="text-xs text-[#525252] font-body mt-1">
                  Propuesta económica formal: <strong>USD $4,200 / mes</strong> (Contrato Contractor Internacional). Tienes plazo para responder y firmar electrónicamente hasta el <strong>Viernes 5:00 PM COT</strong>.
                </p>
              </div>
            </div>

            <button
              onClick={() => setActiveModal({ type: 'offer', app: applications[0] })}
              className="px-6 py-3 rounded-xl bg-[#a83301] hover:bg-[#ca4a1c] text-white font-bold font-headline text-xs shadow-lg transition-all flex items-center justify-center gap-2 shrink-0 animate-pulse"
            >
              <span className="material-symbols-outlined text-base">draw</span>
              Revisar Carta Oferta
            </button>
          </div>
        )}

        {/* Filter Tabs */}
        <div className="flex items-center justify-between gap-4 mb-6 border-b border-[#e5e5e5] pb-4">
          <div className="flex flex-wrap items-center gap-2">
            {[
              { id: 'all', label: 'Todas las Postulaciones', count: applications.length },
              { id: 'offer', label: 'Ofertas Recibidas', count: applications.filter(a => a.status === 'offer').length },
              { id: 'interview', label: 'En Entrevistas', count: applications.filter(a => a.status === 'interview').length },
              { id: 'tech_test', label: 'Pruebas Técnicas', count: applications.filter(a => a.status === 'tech_test').length },
              { id: 'review', label: 'En Revisión', count: applications.filter(a => a.status === 'review').length },
            ].map(tab => (
              <button
                key={tab.id}
                onClick={() => setFilterStatus(tab.id)}
                className={`px-4 py-2 rounded-xl text-xs font-bold font-headline transition-all ${
                  filterStatus === tab.id
                    ? 'bg-[#a83301] text-white shadow-sm'
                    : 'bg-white text-[#737373] hover:text-[#1f1b16] border border-[#e5e5e5]'
                }`}
              >
                {tab.label} ({tab.count})
              </button>
            ))}
          </div>

          <div className="text-xs text-[#737373] hidden sm:block font-body">
            Mostrando {filteredApps.length} vacantes en seguimiento
          </div>
        </div>

        {/* Applications List */}
        <div className="space-y-4 mb-12">
          {filteredApps.map((app) => (
            <div
              key={app.id}
              className="bg-white rounded-2xl p-6 border border-[#e5e5e5] shadow-sm hover:shadow-md transition-all"
            >
              <div className="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-4">
                <div className="flex items-start gap-4">
                  <div className="w-14 h-14 rounded-2xl bg-[#fff8f6] border border-[#f5b8a5] p-2 flex items-center justify-center shrink-0">
                    <img
                      src={app.companyLogo}
                      alt={app.company}
                      className="w-full h-full object-contain"
                    />
                  </div>
                  <div>
                    <div className="flex flex-wrap items-center gap-2 mb-1">
                      <h3 className="text-base sm:text-lg font-bold text-[#1f1b16] font-headline">
                        {app.title}
                      </h3>
                      <span className="text-sm font-semibold text-[#737373]">
                        @ {app.company}
                      </span>
                    </div>

                    <div className="flex flex-wrap items-center gap-3 text-xs text-[#525252] font-body">
                      <span className="flex items-center gap-1">
                        <span className="material-symbols-outlined text-sm">location_on</span>
                        {app.location}
                      </span>
                      <span className="font-bold text-[#a83301]">
                        {app.salaryDisplay}
                      </span>
                      <span className="text-[#737373]">
                        {app.contractType}
                      </span>
                      <span className="text-[#737373]">
                        Aplicaste hace {app.appliedDaysAgo} días
                      </span>
                    </div>
                  </div>
                </div>

                {/* Status Badge & Actions */}
                <div className="flex flex-wrap items-center gap-3">
                  <span className={`px-3 py-1 rounded-full text-xs font-bold ${
                    app.status === 'offer'
                      ? 'bg-[#dcfce7] text-[#166534] border border-[#86efac]'
                      : app.status === 'interview'
                      ? 'bg-[#e0f2fe] text-[#0284c7] border border-[#bae6fd]'
                      : app.status === 'tech_test'
                      ? 'bg-[#fef3c7] text-[#b45309] border border-[#fde68a]'
                      : 'bg-neutral-100 text-[#525252] border border-neutral-200'
                  }`}>
                    {app.statusLabel}
                  </span>

                  <button
                    onClick={() => handleOpenAction(app)}
                    className="px-4 py-2 rounded-xl bg-[#a83301] hover:bg-[#ca4a1c] text-white text-xs font-bold font-headline shadow-sm transition-all"
                  >
                    {app.status === 'offer'
                      ? 'Revisar Oferta'
                      : app.status === 'interview'
                      ? 'Enlace Google Meet'
                      : app.status === 'tech_test'
                      ? 'Continuar Prueba'
                      : 'Ver Vacante'}
                  </button>
                </div>
              </div>

              {/* Progress Bar & Contextual Info */}
              <div className="pt-4 border-t border-[#f5f5f5]">
                <div className="flex items-center justify-between text-xs text-[#737373] mb-1.5 font-body">
                  <span>Progreso del proceso: <strong>{app.stageProgress}</strong></span>
                  <span>{app.progressPercent}% completado</span>
                </div>
                <div className="w-full h-2 rounded-full bg-neutral-100 overflow-hidden mb-3">
                  <div
                    className={`h-full rounded-full transition-all duration-500 ${
                      app.status === 'offer'
                        ? 'bg-[#16a34a]'
                        : app.status === 'interview'
                        ? 'bg-[#0284c7]'
                        : app.status === 'tech_test'
                        ? 'bg-[#d97706]'
                        : 'bg-[#a83301]'
                    }`}
                    style={{ width: `${app.progressPercent}%` }}
                  />
                </div>

                {/* Specific details */}
                {app.interviewDetails && (
                  <div className="p-3 bg-[#e0f2fe]/40 rounded-xl border border-[#bae6fd] text-xs text-[#0369a1] flex items-center justify-between">
                    <span className="flex items-center gap-2">
                      <span className="material-symbols-outlined text-base">video_call</span>
                      <strong>{app.interviewDetails.datetime}</strong> • {app.interviewDetails.topic} ({app.interviewDetails.interviewer})
                    </span>
                    <button
                      onClick={() => setActiveModal({ type: 'interview', app })}
                      className="font-bold underline text-xs"
                    >
                      Abrir link
                    </button>
                  </div>
                )}

                {app.testDetails && (
                  <div className="p-3 bg-[#fef3c7]/50 rounded-xl border border-[#fde68a] text-xs text-[#92400e] flex items-center justify-between">
                    <span className="flex items-center gap-2">
                      <span className="material-symbols-outlined text-base">assignment</span>
                      {app.testDetails.topic} • <strong>{app.testDetails.timeRemaining}</strong>
                    </span>
                    <button
                      onClick={() => setActiveModal({ type: 'test', app })}
                      className="font-bold underline text-xs"
                    >
                      Continuar entrega
                    </button>
                  </div>
                )}
              </div>
            </div>
          ))}
        </div>
      </div>

      {/* Offer Review Modal */}
      {activeModal.type === 'offer' && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm animate-fade-in">
          <div className="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-8 shadow-2xl border border-[#e5e5e5] relative animate-scale-up max-h-[90vh] overflow-y-auto">
            <div className="flex items-center justify-between pb-4 border-b border-[#f0f0f0] mb-6">
              <div className="flex items-center gap-3">
                <span className="material-symbols-outlined text-[#a83301] text-3xl">verified</span>
                <div>
                  <h3 className="text-xl font-black font-headline text-[#1f1b16]">
                    Carta de Oferta Laboral Formal
                  </h3>
                  <p className="text-xs text-[#737373]">Stark Cloud Corp • Emitida el 22 de Octubre</p>
                </div>
              </div>
              <button
                onClick={() => setActiveModal({ type: null, app: null })}
                className="w-8 h-8 rounded-full hover:bg-neutral-100 flex items-center justify-center text-[#737373]"
              >
                <span className="material-symbols-outlined text-lg">close</span>
              </button>
            </div>

            <div className="space-y-5 text-sm font-body text-[#404040]">
              <div className="p-4 rounded-2xl bg-[#fff8f6] border border-[#f5b8a5]">
                <span className="text-xs uppercase font-bold text-[#a83301] tracking-wider">Compensación Acordada</span>
                <div className="text-3xl font-black font-headline text-[#a83301] mt-1">
                  USD $4,200 / mes
                </div>
                <p className="text-xs text-[#525252] mt-1">
                  Aproximadamente <strong>$17.640.000 COP netos</strong> pagados mediante depósito directo o Payoneer/Wise el día 30 de cada mes.
                </p>
              </div>

              <div className="space-y-2 text-xs">
                <p><strong>Cargo:</strong> Senior Frontend Developer (Next.js / TypeScript)</p>
                <p><strong>Modalidad:</strong> 100% Remoto (Horario de Colombia GMT-5)</p>
                <p><strong>Tipo de contrato:</strong> Contractor Internacional por tiempo indefinido</p>
                <p><strong>Vacaciones:</strong> 25 días PTO pagos al año</p>
                <p><strong>Equipo:</strong> MacBook Pro Apple Silicon M3 provisto por la compañía</p>
                <p><strong>Fecha estimada de inicio:</strong> 4 de Noviembre</p>
              </div>

              <div className="p-4 bg-neutral-50 rounded-2xl border border-neutral-200 text-xs">
                <p className="font-bold text-[#1f1b16] mb-1">Cláusula de Confidencialidad y Transparencia</p>
                <p className="text-[#737373]">
                  Stark Cloud garantiza el respeto a la propiedad intelectual y los estándares salariales acordados sin cláusulas de exclusividad abusivas fuera de la jornada convenida.
                </p>
              </div>
            </div>

            <div className="mt-8 pt-5 border-t border-[#f0f0f0] flex flex-col sm:flex-row items-center justify-between gap-4">
              <span className="text-xs text-[#737373]">
                Firma electrónica legal vinculante bajo marco internacional
              </span>
              <div className="flex items-center gap-3">
                <button
                  onClick={() => setActiveModal({ type: null, app: null })}
                  className="px-4 py-2 text-xs font-bold text-[#737373]"
                >
                  Cerrar
                </button>
                <button
                  onClick={() => {
                    setOfferSigned(true);
                    setActiveModal({ type: null, app: null });
                    alert('¡Felicitaciones! Has firmado y aceptado la oferta formal con Stark Cloud Corp.');
                  }}
                  className="px-6 py-2.5 rounded-xl bg-[#16a34a] hover:bg-[#15803d] text-white font-bold font-headline text-xs shadow-md flex items-center gap-1.5 transition-all"
                >
                  <span className="material-symbols-outlined text-sm">draw</span>
                  Aceptar y Firmar Oferta
                </button>
              </div>
            </div>
          </div>
        </div>
      )}

      {/* Interview Modal */}
      {activeModal.type === 'interview' && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm animate-fade-in">
          <div className="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-[#e5e5e5] relative animate-scale-up">
            <div className="flex items-center justify-between pb-3 border-b border-[#f0f0f0] mb-4">
              <h3 className="text-base font-bold font-headline text-[#1f1b16] flex items-center gap-2">
                <span className="material-symbols-outlined text-[#0284c7]">video_call</span>
                Entrevista con Nubank
              </h3>
              <button
                onClick={() => setActiveModal({ type: null, app: null })}
                className="w-8 h-8 rounded-full hover:bg-neutral-100 flex items-center justify-center text-[#737373]"
              >
                <span className="material-symbols-outlined text-lg">close</span>
              </button>
            </div>

            <div className="space-y-3 text-xs font-body mb-6">
              <p><strong>Sesión:</strong> Entrevista de Arquitectura & Liderazgo Técnico (45 min)</p>
              <p><strong>Entrevistador:</strong> Tomás Restrepo (VP Engineering Nubank)</p>
              <p><strong>Fecha:</strong> Jueves 24 de Octubre a las 3:00 PM COT</p>
              <div className="p-3 bg-[#e0f2fe] rounded-xl text-[#0369a1] font-semibold">
                Plataforma: Google Meet (Sala protegida por SSO)
              </div>
            </div>

            <div className="flex items-center justify-end gap-3 pt-4 border-t border-[#f0f0f0]">
              <button
                onClick={() => setActiveModal({ type: null, app: null })}
                className="px-4 py-2 text-xs font-bold text-[#737373]"
              >
                Cerrar
              </button>
              <button
                onClick={() => {
                  alert('Abriendo sala virtual de Google Meet...');
                  setActiveModal({ type: null, app: null });
                }}
                className="px-5 py-2.5 rounded-xl bg-[#0284c7] hover:bg-[#0369a1] text-white font-bold font-headline text-xs shadow-md flex items-center gap-1.5"
              >
                <span className="material-symbols-outlined text-sm">open_in_new</span>
                Unirme a la Videollamada
              </button>
            </div>
          </div>
        </div>
      )}

      {/* Tech Test Modal */}
      {activeModal.type === 'test' && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm animate-fade-in">
          <div className="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-[#e5e5e5] relative animate-scale-up">
            <div className="flex items-center justify-between pb-3 border-b border-[#f0f0f0] mb-4">
              <h3 className="text-base font-bold font-headline text-[#1f1b16] flex items-center gap-2">
                <span className="material-symbols-outlined text-[#d97706]">assignment</span>
                Prueba Técnica: Rappi Turbo
              </h3>
              <button
                onClick={() => setActiveModal({ type: null, app: null })}
                className="w-8 h-8 rounded-full hover:bg-neutral-100 flex items-center justify-center text-[#737373]"
              >
                <span className="material-symbols-outlined text-lg">close</span>
              </button>
            </div>

            <div className="space-y-3 text-xs font-body mb-6">
              <p><strong>Caso Práctico:</strong> Algoritmo de Adquisición & LTV para Dark Stores</p>
              <p><strong>Progreso guardado:</strong> 70% completado en Notion / Jupyter</p>
              <p><strong>Plazo restante:</strong> 48 horas antes del cierre del sistema</p>
            </div>

            <div className="flex items-center justify-end gap-3 pt-4 border-t border-[#f0f0f0]">
              <button
                onClick={() => setActiveModal({ type: null, app: null })}
                className="px-4 py-2 text-xs font-bold text-[#737373]"
              >
                Guardar y salir
              </button>
              <button
                onClick={() => {
                  alert('Continuando prueba técnica en el sandbox de evaluación.');
                  setActiveModal({ type: null, app: null });
                }}
                className="px-5 py-2.5 rounded-xl bg-[#d97706] hover:bg-[#b45309] text-white font-bold font-headline text-xs shadow-md flex items-center gap-1.5"
              >
                Continuar Desarrollo
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};
