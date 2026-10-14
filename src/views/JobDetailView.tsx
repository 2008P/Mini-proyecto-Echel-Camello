import React, { useState } from 'react';
import { AppScreen, Job } from '../types';
import { MOCK_JOBS, STARK_CLOUD_LOGO, STARK_OFFICE_COVER, MATEO_VALENCIA_HIRING_AVATAR } from '../data/mockData';

interface JobDetailViewProps {
  jobId: string;
  setScreen: (screen: AppScreen) => void;
  onApplySuccess?: (jobId: string) => void;
  onSelectCompany?: (companyName: string) => void;
}

export const JobDetailView: React.FC<JobDetailViewProps> = ({
  jobId,
  setScreen,
  onApplySuccess,
  onSelectCompany,
}) => {
  const job: Job = MOCK_JOBS.find(j => j.id === jobId) || MOCK_JOBS[0];
  const [applied, setApplied] = useState(false);
  const [saved, setSaved] = useState(false);
  const [copiedLink, setCopiedLink] = useState(false);
  const [selectedCurrency, setSelectedCurrency] = useState<'USD' | 'COP'>('USD');
  const [showApplyModal, setShowApplyModal] = useState(false);
  const [applyStep, setApplyStep] = useState<'form' | 'success'>('form');
  const [coverNote, setCoverNote] = useState('¡Hola equipo de Stark Cloud! Cuento con más de 5 años creando aplicaciones con Next.js 14, Tailwind y TypeScript en entornos de alta escala. Me entusiasma la visión y la cultura abierta de la compañía.');

  const handleQuickApply = () => {
    setShowApplyModal(true);
  };

  const handleConfirmApply = () => {
    setApplyStep('success');
    setTimeout(() => {
      setApplied(true);
      setShowApplyModal(false);
      setApplyStep('form');
      if (onApplySuccess) onApplySuccess(job.id);
    }, 1200);
  };

  const handleShare = () => {
    navigator.clipboard?.writeText(window.location.href);
    setCopiedLink(true);
    setTimeout(() => setCopiedLink(false), 2000);
  };

  return (
    <div className="min-h-screen bg-[#fff8f6] py-8 text-[#1f1b16]">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Breadcrumb */}
        <nav className="flex items-center gap-2 text-sm text-[#737373] mb-6 font-body">
          <button onClick={() => setScreen('home')} className="hover:text-[#a83301] transition-colors">
            Inicio
          </button>
          <span>/</span>
          <button onClick={() => setScreen('explorer')} className="hover:text-[#a83301] transition-colors">
            {job.category || 'Tecnología & Software'}
          </button>
          <span>/</span>
          <span className="text-[#1f1b16] font-medium truncate max-w-xs sm:max-w-md">{job.title}</span>
        </nav>

        {/* Hero Card */}
        <div className="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-[#e5e5e5] mb-8 relative overflow-hidden">
          <div className="absolute top-0 right-0 w-80 h-80 bg-gradient-to-bl from-[#ffdbd0]/40 via-transparent to-transparent rounded-bl-full pointer-events-none" />

          <div className="flex flex-col md:flex-row md:items-start justify-between gap-6 relative z-10">
            <div className="flex items-start gap-4 sm:gap-6">
              <div 
                onClick={() => onSelectCompany ? onSelectCompany(job.company) : setScreen('company-profile')}
                className="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-[#fdf2ef] border border-[#f5b8a5] p-2 flex items-center justify-center shrink-0 shadow-sm cursor-pointer hover:border-[#a83301] transition-colors"
              >
                <img
                  src={job.companyLogo || STARK_CLOUD_LOGO}
                  alt={job.company}
                  className="w-full h-full object-contain rounded-lg"
                />
              </div>

              <div>
                <div className="flex flex-wrap items-center gap-2 mb-2">
                  <button 
                    onClick={() => onSelectCompany ? onSelectCompany(job.company) : setScreen('company-profile')}
                    className="text-lg font-bold text-[#1f1b16] hover:text-[#a83301] transition-colors font-headline flex items-center gap-1.5"
                  >
                    {job.company}
                    <span className="material-symbols-outlined text-[#0284c7] text-base" title="Empresa Verificada">
                      verified
                    </span>
                  </button>
                  <span className="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#ffdbd0] text-[#a83301]">
                    {job.urgency || 'Urgente'}
                  </span>
                  <span className="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#f0fdf4] text-[#16a34a] border border-[#bbf7d0]">
                    Respuesta garantizada en {job.responseTime || '< 48h'}
                  </span>
                </div>

                <h1 className="text-2xl sm:text-3xl font-extrabold text-[#1f1b16] font-headline tracking-tight mb-3">
                  {job.title}
                </h1>

                <div className="flex flex-wrap items-center gap-4 text-sm text-[#525252] font-body">
                  <span className="flex items-center gap-1">
                    <span className="material-symbols-outlined text-base text-[#a83301]">location_on</span>
                    {job.location}
                  </span>
                  <span className="flex items-center gap-1">
                    <span className="material-symbols-outlined text-base text-[#ca4a1c]">schedule</span>
                    {job.postedTime}
                  </span>
                  <span className="flex items-center gap-1">
                    <span className="material-symbols-outlined text-base text-[#16a34a]">group</span>
                    {job.activeCandidates || 42} camelladores en proceso
                  </span>
                </div>
              </div>
            </div>

            {/* Salary Box & Call to Action in Top Hero */}
            <div className="bg-[#fff4f0] border border-[#f5b8a5] p-5 rounded-2xl flex flex-col md:w-80 shrink-0">
              <div className="flex items-center justify-between mb-1">
                <span className="text-xs uppercase tracking-wider text-[#737373] font-semibold">
                  Remuneración Neta
                </span>
                <div className="flex bg-white rounded-lg p-0.5 border border-[#e5e5e5] text-xs">
                  <button
                    onClick={() => setSelectedCurrency('USD')}
                    className={`px-2 py-0.5 rounded font-medium transition-colors ${
                      selectedCurrency === 'USD' ? 'bg-[#a83301] text-white' : 'text-[#737373] hover:text-black'
                    }`}
                  >
                    USD
                  </button>
                  <button
                    onClick={() => setSelectedCurrency('COP')}
                    className={`px-2 py-0.5 rounded font-medium transition-colors ${
                      selectedCurrency === 'COP' ? 'bg-[#a83301] text-white' : 'text-[#737373] hover:text-black'
                    }`}
                  >
                    COP
                  </button>
                </div>
              </div>

              <div className="text-2xl sm:text-3xl font-black text-[#a83301] font-headline tracking-tight my-1">
                {selectedCurrency === 'USD'
                  ? (job.salaryUSD || 'USD $3,500 – $4,800')
                  : (job.salaryCOP || '$14.500.000 – $19.800.000 COP')}
              </div>
              <p className="text-xs text-[#737373] font-body mb-4">
                {job.contractType} • Sin letra chica
              </p>

              <div className="flex flex-col gap-2">
                <button
                  onClick={handleQuickApply}
                  disabled={applied}
                  className={`w-full py-3 px-4 rounded-xl font-bold font-headline text-sm transition-all shadow-md flex items-center justify-center gap-2 ${
                    applied
                      ? 'bg-[#dcfce7] text-[#166534] border border-[#86efac] cursor-default'
                      : 'bg-[#a83301] text-white hover:bg-[#ca4a1c] hover:shadow-lg'
                  }`}
                >
                  <span className="material-symbols-outlined text-base">
                    {applied ? 'check_circle' : 'flash_on'}
                  </span>
                  {applied ? '¡Ya te postulaste con éxito!' : 'Postularme en 1 Clic'}
                </button>

                <div className="grid grid-cols-2 gap-2">
                  <button
                    onClick={() => setSaved(!saved)}
                    className={`py-2 px-3 rounded-lg border text-xs font-semibold flex items-center justify-center gap-1 transition-colors ${
                      saved
                        ? 'border-[#a83301] text-[#a83301] bg-[#ffdbd0]/30'
                        : 'border-[#e5e5e5] text-[#525252] hover:bg-neutral-50'
                    }`}
                  >
                    <span className="material-symbols-outlined text-sm">
                      {saved ? 'bookmark' : 'bookmark_border'}
                    </span>
                    {saved ? 'Guardado' : 'Guardar'}
                  </button>
                  <button
                    onClick={handleShare}
                    className="py-2 px-3 rounded-lg border border-[#e5e5e5] text-[#525252] hover:bg-neutral-50 text-xs font-semibold flex items-center justify-center gap-1 transition-colors"
                  >
                    <span className="material-symbols-outlined text-sm">share</span>
                    {copiedLink ? '¡Copiado!' : 'Compartir'}
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* Content Columns: Left (Details) & Right (Sidebar) */}
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
          {/* Main Details (2 cols) */}
          <div className="lg:col-span-2 space-y-8">
            {/* Description Section */}
            <div className="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-[#e5e5e5]">
              <h2 className="text-xl font-bold text-[#1f1b16] font-headline mb-4 flex items-center gap-2">
                <span className="material-symbols-outlined text-[#a83301]">description</span>
                Descripción del Camello
              </h2>
              <p className="text-[#404040] leading-relaxed font-body text-base whitespace-pre-line mb-6">
                {job.description}
              </p>

              {/* Responsibilities */}
              <h3 className="text-lg font-bold text-[#1f1b16] font-headline mb-3">
                Lo que harás en el día a día
              </h3>
              <ul className="space-y-3 mb-6 font-body text-[#404040]">
                {(job.responsibilities || [
                  'Diseñar, estructurar y mantener aplicaciones web en Next.js 14, TypeScript y Tailwind CSS.',
                  'Construir componentes reutilizables con pruebas automatizadas y altos estándares de accesibilidad.',
                  'Colaborar estrechamente con diseñadores de producto y líderes técnicos en iteraciones ágiles semanales.',
                  'Optimizar tiempos de carga, Core Web Vitals y bundles de cliente para máxima velocidad.',
                  'Participar activamente en la mentoría de ingenieros junior y revisiones de código exhaustivas.'
                ]).map((item, idx) => (
                  <li key={idx} className="flex items-start gap-3 text-sm leading-relaxed">
                    <span className="material-symbols-outlined text-[#a83301] text-base shrink-0 mt-0.5">
                      check_circle
                    </span>
                    <span>{item}</span>
                  </li>
                ))}
              </ul>

              {/* Requirements */}
              <h3 className="text-lg font-bold text-[#1f1b16] font-headline mb-3">
                Requisitos indispensables
              </h3>
              <ul className="space-y-3 mb-6 font-body text-[#404040]">
                {(job.requirements || [
                  'Más de 4 años de experiencia probada desarrollando en React y TypeScript en proyectos de producción.',
                  'Dominio sólido de Next.js (App Router, Server Components, Server Actions).',
                  'Experiencia con Tailwind CSS o arquitecturas CSS modernas orientadas a tokens.',
                  'Nivel de inglés B2+ o C1 para comunicación fluida con clientes y equipos en Norteamérica.',
                  'Compromiso con buenas prácticas de software (testing unitario con Vitest/Jest, CI/CD).'
                ]).map((req, idx) => (
                  <li key={idx} className="flex items-start gap-3 text-sm leading-relaxed">
                    <span className="material-symbols-outlined text-[#ca4a1c] text-base shrink-0 mt-0.5">
                      task_alt
                    </span>
                    <span>{req}</span>
                  </li>
                ))}
              </ul>

              {/* Tags and stack */}
              <h3 className="text-lg font-bold text-[#1f1b16] font-headline mb-3">
                Stack tecnológico y habilidades clave
              </h3>
              <div className="flex flex-wrap gap-2">
                {job.tags.map((tag, idx) => (
                  <span
                    key={idx}
                    className="px-3 py-1.5 rounded-lg bg-[#fff4f0] text-[#a83301] font-semibold text-xs border border-[#ffdbd0]"
                  >
                    {tag}
                  </span>
                ))}
              </div>
            </div>

            {/* Perks & Benefits Section */}
            <div className="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-[#e5e5e5]">
              <h2 className="text-xl font-bold text-[#1f1b16] font-headline mb-4 flex items-center gap-2">
                <span className="material-symbols-outlined text-[#a83301]">card_giftcard</span>
                Beneficios & Bienestar Garantizados
              </h2>
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 font-body">
                {job.perks.map((perk, idx) => (
                  <div
                    key={idx}
                    className="p-4 rounded-xl bg-[#fff8f6] border border-[#f5b8a5] flex items-center gap-3"
                  >
                    <div className="w-10 h-10 rounded-lg bg-[#ffdbd0] text-[#a83301] flex items-center justify-center shrink-0">
                      <span className="material-symbols-outlined text-lg">star</span>
                    </div>
                    <div>
                      <h4 className="text-sm font-bold text-[#1f1b16]">{perk}</h4>
                      <p className="text-xs text-[#737373]">Garantizado por contrato legal</p>
                    </div>
                  </div>
                ))}
              </div>
            </div>

            {/* Company Snapshot Card */}
            <div className="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-[#e5e5e5] overflow-hidden relative">
              <div className="flex items-center justify-between mb-4">
                <h2 className="text-xl font-bold text-[#1f1b16] font-headline flex items-center gap-2">
                  <span className="material-symbols-outlined text-[#a83301]">domain</span>
                  Sobre {job.company}
                </h2>
                <button
                  onClick={() => onSelectCompany ? onSelectCompany(job.company) : setScreen('company-profile')}
                  className="text-xs font-bold text-[#a83301] hover:underline flex items-center gap-1 font-headline"
                >
                  Ver perfil completo
                  <span className="material-symbols-outlined text-sm">arrow_forward</span>
                </button>
              </div>

              <div className="h-44 w-full rounded-xl overflow-hidden mb-5 relative">
                <img
                  src={STARK_OFFICE_COVER}
                  alt="Oficinas Stark Cloud"
                  className="w-full h-full object-cover"
                />
                <div className="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end p-4">
                  <div className="text-white">
                    <p className="font-bold text-sm">Stark Cloud Corp LATAM Hub</p>
                    <p className="text-xs text-white/80">Sede satélite en Medellín & Bogotá + 80% equipo remoto</p>
                  </div>
                </div>
              </div>

              <p className="text-sm text-[#525252] font-body leading-relaxed mb-4">
                Somos pioneros en plataformas de infraestructura distribuida en la nube y cómputo de baja latencia para el mercado corporativo de Norteamérica y América Latina. Nuestro equipo multicultural valora la autonomía responsable, la transparencia salarial absoluta y la excelencia en ingeniería.
              </p>

              <div className="grid grid-cols-3 gap-4 pt-4 border-t border-[#f0f0f0] text-center font-body">
                <div>
                  <p className="text-xs text-[#737373]">Tamaño</p>
                  <p className="text-sm font-bold text-[#1f1b16]">150 – 500 empleados</p>
                </div>
                <div>
                  <p className="text-xs text-[#737373]">Fundación</p>
                  <p className="text-sm font-bold text-[#1f1b16]">2020</p>
                </div>
                <div>
                  <p className="text-xs text-[#737373]">Calificación</p>
                  <p className="text-sm font-bold text-[#1f1b16] text-[#ca4a1c] flex items-center justify-center gap-1">
                    <span className="material-symbols-outlined text-xs fill-1">star</span> 4.9 / 5
                  </p>
                </div>
              </div>
            </div>
          </div>

          {/* Sticky Sidebar (1 col) */}
          <div className="space-y-6">
            {/* Vacancy Summary Card */}
            <div className="bg-white rounded-2xl p-6 shadow-sm border border-[#e5e5e5]">
              <h3 className="font-bold font-headline text-base text-[#1f1b16] mb-4">
                Ficha Técnica del Puesto
              </h3>
              <div className="space-y-3.5 text-sm font-body">
                <div className="flex items-center justify-between pb-2.5 border-b border-[#f5f5f5]">
                  <span className="text-[#737373] flex items-center gap-1.5">
                    <span className="material-symbols-outlined text-base">laptop_mac</span> Modalidad:
                  </span>
                  <span className="font-semibold text-[#1f1b16]">{job.modality}</span>
                </div>
                <div className="flex items-center justify-between pb-2.5 border-b border-[#f5f5f5]">
                  <span className="text-[#737373] flex items-center gap-1.5">
                    <span className="material-symbols-outlined text-base">badge</span> Tipo de Contrato:
                  </span>
                  <span className="font-semibold text-[#1f1b16] text-right truncate max-w-[140px]" title={job.contractType}>
                    {job.contractType}
                  </span>
                </div>
                <div className="flex items-center justify-between pb-2.5 border-b border-[#f5f5f5]">
                  <span className="text-[#737373] flex items-center gap-1.5">
                    <span className="material-symbols-outlined text-base">military_tech</span> Seniority:
                  </span>
                  <span className="font-semibold text-[#1f1b16]">{job.experienceLevel}</span>
                </div>
                <div className="flex items-center justify-between pb-2.5 border-b border-[#f5f5f5]">
                  <span className="text-[#737373] flex items-center gap-1.5">
                    <span className="material-symbols-outlined text-base">translate</span> Idioma:
                  </span>
                  <span className="font-semibold text-[#1f1b16]">Inglés Profesional (B2/C1)</span>
                </div>
                <div className="flex items-center justify-between">
                  <span className="text-[#737373] flex items-center gap-1.5">
                    <span className="material-symbols-outlined text-base">payments</span> Moneda:
                  </span>
                  <span className="font-semibold text-[#16a34a]">Dólares (USD) o COP</span>
                </div>
              </div>

              <div className="mt-6 pt-4 border-t border-[#f0f0f0]">
                <button
                  onClick={handleQuickApply}
                  disabled={applied}
                  className={`w-full py-3 px-4 rounded-xl font-bold font-headline text-sm transition-all shadow-md flex items-center justify-center gap-2 ${
                    applied
                      ? 'bg-[#dcfce7] text-[#166534] border border-[#86efac]'
                      : 'bg-[#a83301] text-white hover:bg-[#ca4a1c]'
                  }`}
                >
                  <span className="material-symbols-outlined text-base">
                    {applied ? 'check_circle' : 'flash_on'}
                  </span>
                  {applied ? 'Postulado exitosamente' : 'Postulación Rápida'}
                </button>
              </div>
            </div>

            {/* Profile Match Card */}
            <div className="bg-[#f0fdf4] border border-[#bbf7d0] rounded-2xl p-6">
              <div className="flex items-center justify-between mb-3">
                <span className="text-xs font-bold uppercase tracking-wider text-[#166534] flex items-center gap-1">
                  <span className="material-symbols-outlined text-sm">auto_awesome</span>
                  Match de tu Perfil
                </span>
                <span className="text-xl font-black text-[#166534] font-headline">96%</span>
              </div>
              <div className="w-full bg-[#dcfce7] h-2 rounded-full overflow-hidden mb-3">
                <div className="bg-[#16a34a] h-full rounded-full w-[96%]" />
              </div>
              <p className="text-xs text-[#166534] leading-relaxed font-body">
                Cumples con los 5 requisitos principales del rol (Next.js, TypeScript, Tailwind, experiencia senior y disponibilidad horaria LATAM).
              </p>
            </div>

            {/* Hiring Manager Card */}
            <div className="bg-white rounded-2xl p-6 shadow-sm border border-[#e5e5e5]">
              <h3 className="font-bold font-headline text-sm text-[#737373] uppercase tracking-wider mb-4">
                Líder de Contratación
              </h3>
              <div className="flex items-center gap-3.5 mb-4">
                <img
                  src={MATEO_VALENCIA_HIRING_AVATAR}
                  alt="Mateo Valencia"
                  className="w-12 h-12 rounded-full object-cover border border-[#ffdbd0]"
                />
                <div>
                  <h4 className="font-bold text-sm text-[#1f1b16]">Mateo Valencia</h4>
                  <p className="text-xs text-[#737373]">VP of Engineering @ Stark Cloud</p>
                </div>
              </div>
              <p className="text-xs text-[#525252] font-body italic mb-4">
                &ldquo;Buscamos ingenieros que disfruten construir con autonomía y quieran resolver desafíos de performance a escala continental.&rdquo;
              </p>
              <button 
                onClick={() => alert('Mensaje directo enviado a Mateo Valencia. Te responderá a través del panel de mensajes.')}
                className="w-full py-2 px-3 rounded-xl border border-[#a83301] text-[#a83301] hover:bg-[#fff4f0] font-headline font-bold text-xs flex items-center justify-center gap-1.5 transition-colors"
              >
                <span className="material-symbols-outlined text-sm">mail</span>
                Preguntarle algo al hiring manager
              </button>
            </div>

            {/* Salary Calculator Teaser */}
            <div className="bg-[#fff4f0] border border-[#f5b8a5] rounded-2xl p-5">
              <div className="flex items-start gap-3">
                <span className="material-symbols-outlined text-[#a83301] text-2xl">calculate</span>
                <div>
                  <h4 className="font-bold text-sm text-[#1f1b16] font-headline">
                    ¿Dudas con tu pago neto en Colombia?
                  </h4>
                  <p className="text-xs text-[#525252] font-body mt-1 mb-3">
                    Calcula cuánto te queda libre en pesos recibiendo USD por Payoneer o Wise, deduciendo aportes de salud y pensión.
                  </p>
                  <button
                    onClick={() => setScreen('salary-calculator')}
                    className="text-xs font-bold text-[#a83301] hover:underline flex items-center gap-1 font-headline"
                  >
                    Abrir Calculadora Salarial
                    <span className="material-symbols-outlined text-sm">arrow_forward</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* 1-Click Apply Modal */}
      {showApplyModal && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm animate-fade-in">
          <div className="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-[#e5e5e5] relative animate-scale-up">
            {applyStep === 'form' ? (
              <>
                <div className="flex items-center justify-between pb-4 border-b border-[#f0f0f0] mb-4">
                  <div className="flex items-center gap-2">
                    <span className="material-symbols-outlined text-[#a83301] text-2xl">bolt</span>
                    <h3 className="font-extrabold font-headline text-lg text-[#1f1b16]">
                      Postulación en 1 Clic
                    </h3>
                  </div>
                  <button
                    onClick={() => setShowApplyModal(false)}
                    className="w-8 h-8 rounded-full hover:bg-neutral-100 flex items-center justify-center text-[#737373]"
                  >
                    <span className="material-symbols-outlined text-lg">close</span>
                  </button>
                </div>

                <div className="p-3 bg-[#fff4f0] rounded-xl border border-[#ffdbd0] mb-4">
                  <p className="text-xs font-bold text-[#a83301]">{job.title}</p>
                  <p className="text-xs text-[#525252]">{job.company} • {job.salaryUSD}</p>
                </div>

                <div className="space-y-4 font-body">
                  <div>
                    <label className="block text-xs font-bold text-[#525252] uppercase mb-1">
                      Tu Perfil Verificado
                    </label>
                    <div className="p-3 bg-neutral-50 rounded-xl border border-[#e5e5e5] flex items-center justify-between">
                      <div className="flex items-center gap-3">
                        <div className="w-10 h-10 rounded-full bg-[#a83301] text-white flex items-center justify-center font-bold text-sm">
                          CP
                        </div>
                        <div>
                          <p className="text-sm font-bold text-[#1f1b16]">Camilo Pérez (Tú)</p>
                          <p className="text-xs text-[#737373]">Senior Frontend • 6 años exp • CV Actualizado</p>
                        </div>
                      </div>
                      <span className="material-symbols-outlined text-[#16a34a] text-lg">verified</span>
                    </div>
                  </div>

                  <div>
                    <label className="block text-xs font-bold text-[#525252] uppercase mb-1">
                      Nota de Presentación Rápida (Opcional)
                    </label>
                    <textarea
                      rows={3}
                      value={coverNote}
                      onChange={(e) => setCoverNote(e.target.value)}
                      className="w-full text-xs p-3 rounded-xl border border-[#d4d4d4] focus:outline-none focus:ring-2 focus:ring-[#a83301] focus:border-transparent text-[#1f1b16]"
                    />
                  </div>

                  <div className="flex items-center gap-2 text-xs text-[#737373]">
                    <span className="material-symbols-outlined text-sm text-[#16a34a]">lock</span>
                    Tus datos son compartidos únicamente con el equipo técnico de {job.company}.
                  </div>
                </div>

                <div className="mt-6 flex items-center justify-end gap-3 pt-4 border-t border-[#f0f0f0]">
                  <button
                    onClick={() => setShowApplyModal(false)}
                    className="px-4 py-2 text-xs font-bold text-[#737373] hover:text-[#1f1b16]"
                  >
                    Cancelar
                  </button>
                  <button
                    onClick={handleConfirmApply}
                    className="px-6 py-2.5 rounded-xl bg-[#a83301] hover:bg-[#ca4a1c] text-white font-bold font-headline text-xs shadow-md transition-all flex items-center gap-1.5"
                  >
                    <span className="material-symbols-outlined text-sm">send</span>
                    Confirmar Postulación
                  </button>
                </div>
              </>
            ) : (
              <div className="text-center py-8">
                <div className="w-16 h-16 rounded-full bg-[#dcfce7] text-[#166534] flex items-center justify-center mx-auto mb-4 animate-bounce">
                  <span className="material-symbols-outlined text-3xl">check</span>
                </div>
                <h3 className="text-xl font-black font-headline text-[#1f1b16] mb-2">
                  ¡Postulación Enviada con Éxito!
                </h3>
                <p className="text-xs text-[#525252] font-body max-w-sm mx-auto">
                  Tu perfil ha sido entregado en la bandeja prioritaria de Mateo Valencia. Recibirás respuesta en menos de 48 horas en tu panel de camellos.
                </p>
              </div>
            )}
          </div>
        </div>
      )}
    </div>
  );
};
