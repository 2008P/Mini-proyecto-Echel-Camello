import React, { useState } from 'react';
import { AppScreen, Candidate } from '../types';
import {
  STARK_CLOUD_LOGO,
  MOCK_CANDIDATES,
  MOCK_JOBS,
} from '../data/mockData';

interface RecruiterDashboardViewProps {
  setScreen: (screen: AppScreen) => void;
  onSelectJob: (jobId: string) => void;
}

export const RecruiterDashboardView: React.FC<RecruiterDashboardViewProps> = ({
  setScreen,
  onSelectJob,
}) => {
  const [candidates, setCandidates] = useState<Candidate[]>(MOCK_CANDIDATES);
  const [activeTab, setActiveTab] = useState<'pipeline' | 'candidates' | 'analytics'>('pipeline');
  const [selectedCandidate, setSelectedCandidate] = useState<Candidate | null>(null);
  const [actionModal, setActionModal] = useState<'interview' | 'offer' | null>(null);
  const [copilotActionDone, setCopilotActionDone] = useState(false);

  const handleRunCopilot = () => {
    setCopilotActionDone(true);
    setTimeout(() => {
      alert('¡Copiloto IA ejecutado! Se enviaron 3 notificaciones automáticas vía WhatsApp y correo a los candidatos prioritarios.');
    }, 400);
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
          <span className="text-[#1f1b16] font-medium">Panel de Reclutamiento</span>
        </nav>

        {/* Recruiter Header Bar */}
        <div className="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-[#e5e5e5] mb-8">
          <div className="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div className="flex items-center gap-5">
              <div className="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-[#fff4f0] border border-[#ffdbd0] p-2 flex items-center justify-center shrink-0">
                <img
                  src={STARK_CLOUD_LOGO}
                  alt="Stark Cloud"
                  className="w-full h-full object-contain"
                />
              </div>
              <div>
                <div className="flex items-center gap-2 mb-1">
                  <h1 className="text-2xl sm:text-3xl font-extrabold text-[#1f1b16] font-headline">
                    Stark Cloud Corp
                  </h1>
                  <span className="px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#ffdbd0] text-[#a83301]">
                    Plan Enterprise Reclutador
                  </span>
                </div>
                <p className="text-sm text-[#525252] font-body">
                  Gestionando 4 vacantes activas • Hub de Talento Medellín & LATAM
                </p>
              </div>
            </div>

            <div className="flex flex-wrap items-center gap-3">
              <button
                onClick={() => setScreen('company-profile')}
                className="px-4 py-2.5 rounded-xl border border-[#e5e5e5] hover:bg-neutral-50 text-xs font-bold font-headline text-[#525252] transition-colors"
              >
                Ver Perfil Público
              </button>
              <button
                onClick={() => setScreen('create-job')}
                className="px-5 py-2.5 rounded-xl bg-[#a83301] hover:bg-[#ca4a1c] text-white text-xs font-bold font-headline flex items-center gap-1.5 shadow-md transition-all"
              >
                <span className="material-symbols-outlined text-sm">add_circle</span>
                Publicar Nuevo Camello
              </button>
            </div>
          </div>
        </div>

        {/* AI Recruiter Copilot Banner */}
        <div className="bg-gradient-to-r from-[#1f1b16] to-[#3a2820] text-white rounded-3xl p-6 sm:p-7 shadow-lg mb-8 relative overflow-hidden">
          <div className="absolute right-0 top-0 w-80 h-full bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-[#ca4a1c]/40 via-transparent to-transparent pointer-events-none" />
          
          <div className="flex flex-col lg:flex-row lg:items-center justify-between gap-6 relative z-10">
            <div className="flex items-start gap-4">
              <div className="w-12 h-12 rounded-2xl bg-[#ca4a1c] text-white flex items-center justify-center shrink-0 shadow-md">
                <span className="material-symbols-outlined text-2xl">smart_toy</span>
              </div>
              <div>
                <div className="flex items-center gap-2 mb-1">
                  <span className="text-xs font-bold uppercase tracking-wider text-[#f5b8a5]">
                    Copiloto de Contratación con IA
                  </span>
                  <span className="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#16a34a] text-white">
                    Activo
                  </span>
                </div>
                <h3 className="text-base sm:text-lg font-bold font-headline text-white">
                  3 candidatos superan el 94% de match para tus vacantes abiertas
                </h3>
                <p className="text-xs text-white/80 font-body max-w-2xl mt-0.5">
                  El algoritmo de Échele Camello preseleccionó a Camilo Restrepo (Backend Go) y Valentina Gómez (Design). Además, la carta oferta para Senior Frontend expira este viernes.
                </p>
              </div>
            </div>

            <div className="flex items-center gap-3 shrink-0">
              <button
                onClick={handleRunCopilot}
                disabled={copilotActionDone}
                className={`px-5 py-2.5 rounded-xl font-bold font-headline text-xs shadow-md transition-all flex items-center gap-1.5 ${
                  copilotActionDone
                    ? 'bg-[#166534] text-white cursor-default'
                    : 'bg-[#ffdbd0] text-[#a83301] hover:bg-white'
                }`}
              >
                <span className="material-symbols-outlined text-sm">
                  {copilotActionDone ? 'check' : 'bolt'}
                </span>
                {copilotActionDone ? 'Recordatorios Enviados' : 'Disparar Recordatorios IA'}
              </button>
            </div>
          </div>
        </div>

        {/* 4 KPIs Cards */}
        <div className="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
          <div className="bg-white p-5 rounded-2xl border border-[#e5e5e5] shadow-sm">
            <span className="text-xs font-bold text-[#737373] uppercase tracking-wider">
              Camellos Abiertos
            </span>
            <div className="text-2xl sm:text-3xl font-black text-[#1f1b16] font-headline mt-1">
              4
            </div>
            <span className="text-xs text-[#a83301] font-semibold flex items-center gap-1 mt-1">
              <span className="material-symbols-outlined text-xs">local_fire_department</span> 2 con contratación urgente
            </span>
          </div>

          <div className="bg-white p-5 rounded-2xl border border-[#e5e5e5] shadow-sm">
            <span className="text-xs font-bold text-[#737373] uppercase tracking-wider">
              Postulantes en Proceso
            </span>
            <div className="text-2xl sm:text-3xl font-black text-[#0284c7] font-headline mt-1">
              86
            </div>
            <span className="text-xs text-[#16a34a] font-semibold flex items-center gap-1 mt-1">
              <span className="material-symbols-outlined text-xs">trending_up</span> +24 nuevos hoy
            </span>
          </div>

          <div className="bg-white p-5 rounded-2xl border border-[#e5e5e5] shadow-sm">
            <span className="text-xs font-bold text-[#737373] uppercase tracking-wider">
              Match Promedio Técnico
            </span>
            <div className="text-2xl sm:text-3xl font-black text-[#16a34a] font-headline mt-1">
              91.4%
            </div>
            <span className="text-xs text-[#737373] font-medium mt-1">
              Filtro estricto de stack & inglés
            </span>
          </div>

          <div className="bg-white p-5 rounded-2xl border border-[#e5e5e5] shadow-sm">
            <span className="text-xs font-bold text-[#737373] uppercase tracking-wider">
              Tiempo Promedio Contrato
            </span>
            <div className="text-2xl sm:text-3xl font-black text-[#ca4a1c] font-headline mt-1">
              14 días
            </div>
            <span className="text-xs text-[#16a34a] font-semibold mt-1">
              -6 días más rápido que el promedio
            </span>
          </div>
        </div>

        {/* Tab Switcher */}
        <div className="flex items-center gap-2 mb-6 border-b border-[#e5e5e5] pb-4">
          <button
            onClick={() => setActiveTab('pipeline')}
            className={`px-4 py-2 rounded-xl text-xs font-bold font-headline transition-all ${
              activeTab === 'pipeline'
                ? 'bg-[#a83301] text-white shadow-sm'
                : 'bg-white text-[#737373] hover:text-[#1f1b16] border border-[#e5e5e5]'
            }`}
          >
            Pipeline de Vacantes (4)
          </button>
          <button
            onClick={() => setActiveTab('candidates')}
            className={`px-4 py-2 rounded-xl text-xs font-bold font-headline transition-all ${
              activeTab === 'candidates'
                ? 'bg-[#a83301] text-white shadow-sm'
                : 'bg-white text-[#737373] hover:text-[#1f1b16] border border-[#e5e5e5]'
            }`}
          >
            Candidatos Sugeridos por IA ({candidates.length})
          </button>
        </div>

        {/* Tab 1: Pipeline of Vacancies */}
        {activeTab === 'pipeline' && (
          <div className="space-y-4 mb-12">
            {MOCK_JOBS.slice(0, 4).map((job) => (
              <div
                key={job.id}
                className="bg-white rounded-2xl p-6 border border-[#e5e5e5] shadow-sm hover:shadow-md transition-all"
              >
                <div className="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-5">
                  <div>
                    <div className="flex flex-wrap items-center gap-2 mb-1">
                      <h3 className="text-lg font-bold text-[#1f1b16] font-headline">
                        {job.title}
                      </h3>
                      <span className="px-2 py-0.5 rounded text-[11px] font-bold bg-[#ffdbd0] text-[#a83301]">
                        {job.urgency || 'Activa'}
                      </span>
                    </div>

                    <div className="flex flex-wrap items-center gap-3 text-xs text-[#525252] font-body">
                      <span>{job.location}</span>
                      <span>•</span>
                      <span className="font-bold text-[#a83301]">{job.salaryUSD || job.salaryCOP}</span>
                      <span>•</span>
                      <span>{job.contractType}</span>
                    </div>
                  </div>

                  <div className="flex items-center gap-2">
                    <button
                      onClick={() => onSelectJob(job.id)}
                      className="px-4 py-2 rounded-xl border border-[#e5e5e5] hover:bg-neutral-50 text-xs font-bold font-headline text-[#525252] transition-colors"
                    >
                      Ver Detalle
                    </button>
                    <button
                      onClick={() => {
                        setSelectedCandidate(candidates[0]);
                        setActionModal('interview');
                      }}
                      className="px-4 py-2 rounded-xl bg-[#a83301] hover:bg-[#ca4a1c] text-white text-xs font-bold font-headline shadow-sm transition-all"
                    >
                      Ver Pipeline
                    </button>
                  </div>
                </div>

                {/* Pipeline Stages Mini-Kanban Bar */}
                <div className="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-4 border-t border-[#f5f5f5]">
                  <div className="p-3 bg-neutral-50 rounded-xl border border-neutral-200">
                    <span className="text-[11px] text-[#737373] uppercase font-bold">1. Postulados</span>
                    <p className="text-xl font-bold font-headline text-[#1f1b16] mt-0.5">42</p>
                  </div>
                  <div className="p-3 bg-blue-50/60 rounded-xl border border-blue-200">
                    <span className="text-[11px] text-blue-700 uppercase font-bold">2. Entrevista Técnica</span>
                    <p className="text-xl font-bold font-headline text-blue-900 mt-0.5">6</p>
                  </div>
                  <div className="p-3 bg-amber-50/60 rounded-xl border border-amber-200">
                    <span className="text-[11px] text-amber-700 uppercase font-bold">3. En Evaluación</span>
                    <p className="text-xl font-bold font-headline text-amber-900 mt-0.5">2</p>
                  </div>
                  <div className="p-3 bg-emerald-50/60 rounded-xl border border-emerald-200">
                    <span className="text-[11px] text-emerald-700 uppercase font-bold">4. Oferta Emitida</span>
                    <p className="text-xl font-bold font-headline text-emerald-900 mt-0.5">1</p>
                  </div>
                </div>
              </div>
            ))}
          </div>
        )}

        {/* Tab 2: Matched Candidates Showcase */}
        {activeTab === 'candidates' && (
          <div className="space-y-4 mb-12">
            {candidates.map((cand) => (
              <div
                key={cand.id}
                className="bg-white rounded-2xl p-6 border border-[#e5e5e5] shadow-sm hover:shadow-md transition-all flex flex-col md:flex-row md:items-center justify-between gap-6"
              >
                <div className="flex items-start gap-4">
                  <img
                    src={cand.avatar}
                    alt={cand.name}
                    className="w-16 h-16 rounded-2xl object-cover border-2 border-[#ffdbd0] shrink-0"
                  />
                  <div>
                    <div className="flex flex-wrap items-center gap-2 mb-1">
                      <h3 className="text-lg font-bold text-[#1f1b16] font-headline">
                        {cand.name}
                      </h3>
                      <span className="px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#f0fdf4] text-[#166534] border border-[#bbf7d0]">
                        ★ {cand.matchScore}% Match Técnico
                      </span>
                    </div>

                    <p className="text-xs font-semibold text-[#a83301] mb-2 font-body">
                      {cand.role} • {cand.experience} • {cand.location}
                    </p>

                    <div className="flex flex-wrap gap-1.5 mb-2">
                      {cand.skills.map((skill, idx) => (
                        <span key={idx} className="px-2 py-0.5 bg-neutral-100 text-[#525252] rounded text-xs">
                          {skill}
                        </span>
                      ))}
                      {cand.englishLevel && (
                        <span className="px-2 py-0.5 bg-blue-50 text-blue-700 font-bold rounded text-xs">
                          {cand.englishLevel}
                        </span>
                      )}
                    </div>

                    <p className="text-xs text-[#737373]">
                      Expectativa salarial: <strong>{cand.salaryExpectation}</strong> • Calificación prueba: <strong>{cand.techTestScore}</strong>
                    </p>
                  </div>
                </div>

                <div className="flex md:flex-col items-center md:items-end gap-2 shrink-0">
                  <button
                    onClick={() => {
                      setSelectedCandidate(cand);
                      setActionModal('interview');
                    }}
                    className="w-full md:w-40 py-2 px-3 rounded-xl bg-[#a83301] hover:bg-[#ca4a1c] text-white text-xs font-bold font-headline shadow-sm flex items-center justify-center gap-1.5"
                  >
                    <span className="material-symbols-outlined text-sm">calendar_today</span>
                    Agendar Entrevista
                  </button>

                  <button
                    onClick={() => {
                      setSelectedCandidate(cand);
                      setActionModal('offer');
                    }}
                    className="w-full md:w-40 py-2 px-3 rounded-xl border border-[#a83301] text-[#a83301] hover:bg-[#fff4f0] text-xs font-bold font-headline flex items-center justify-center gap-1.5"
                  >
                    <span className="material-symbols-outlined text-sm">workspace_premium</span>
                    Extender Oferta
                  </button>
                </div>
              </div>
            ))}
          </div>
        )}
      </div>

      {/* Recruiter Action Modal */}
      {actionModal && selectedCandidate && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm animate-fade-in">
          <div className="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-[#e5e5e5] relative animate-scale-up">
            <div className="flex items-center justify-between pb-3 border-b border-[#f0f0f0] mb-4">
              <h3 className="text-base font-bold font-headline text-[#1f1b16]">
                {actionModal === 'interview' ? 'Agendar Entrevista Técnica' : 'Extender Oferta Salarial'}
              </h3>
              <button
                onClick={() => setActionModal(null)}
                className="w-8 h-8 rounded-full hover:bg-neutral-100 flex items-center justify-center text-[#737373]"
              >
                <span className="material-symbols-outlined text-lg">close</span>
              </button>
            </div>

            <div className="p-3 bg-[#fff4f0] rounded-xl border border-[#ffdbd0] mb-4 flex items-center gap-3">
              <img
                src={selectedCandidate.avatar}
                alt={selectedCandidate.name}
                className="w-10 h-10 rounded-full object-cover"
              />
              <div>
                <p className="text-xs font-bold text-[#1f1b16]">{selectedCandidate.name}</p>
                <p className="text-xs text-[#737373]">{selectedCandidate.role} • Match: {selectedCandidate.matchScore}%</p>
              </div>
            </div>

            {actionModal === 'interview' ? (
              <div className="space-y-3 text-xs font-body mb-6">
                <div>
                  <label className="block font-bold text-[#525252] mb-1">Fecha & Hora</label>
                  <input
                    type="datetime-local"
                    defaultValue="2026-10-25T15:00"
                    className="w-full p-2.5 rounded-xl border border-[#d4d4d4] text-[#1f1b16]"
                  />
                </div>
                <div>
                  <label className="block font-bold text-[#525252] mb-1">Entrevistador Principal</label>
                  <input
                    type="text"
                    defaultValue="Mateo Valencia (VP of Engineering)"
                    className="w-full p-2.5 rounded-xl border border-[#d4d4d4] text-[#1f1b16]"
                  />
                </div>
              </div>
            ) : (
              <div className="space-y-3 text-xs font-body mb-6">
                <div>
                  <label className="block font-bold text-[#525252] mb-1">Monto Ofertado</label>
                  <input
                    type="text"
                    defaultValue={selectedCandidate.salaryExpectation}
                    className="w-full p-2.5 rounded-xl border border-[#d4d4d4] text-[#1f1b16]"
                  />
                </div>
                <div>
                  <label className="block font-bold text-[#525252] mb-1">Tipo de Contrato</label>
                  <select className="w-full p-2.5 rounded-xl border border-[#d4d4d4] text-[#1f1b16]">
                    <option>Contractor Internacional USD</option>
                    <option>Término Indefinido Colombia</option>
                  </select>
                </div>
              </div>
            )}

            <div className="flex items-center justify-end gap-3 pt-4 border-t border-[#f0f0f0]">
              <button
                onClick={() => setActionModal(null)}
                className="px-4 py-2 text-xs font-bold text-[#737373]"
              >
                Cancelar
              </button>
              <button
                onClick={() => {
                  alert(
                    actionModal === 'interview'
                      ? `¡Invitación a Google Meet enviada con éxito a ${selectedCandidate.name}!`
                      : `¡Carta oferta formal enviada a ${selectedCandidate.name}!`
                  );
                  setActionModal(null);
                }}
                className="px-5 py-2.5 rounded-xl bg-[#a83301] hover:bg-[#ca4a1c] text-white font-bold font-headline text-xs shadow-md"
              >
                Confirmar y Enviar
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};
