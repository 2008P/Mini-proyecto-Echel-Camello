import React, { useState } from 'react';
import { AppScreen } from '../types';
import {
  STARK_CLOUD_LOGO,
  STARK_OFFICE_COVER,
  MOCK_JOBS,
  MATEO_VALENCIA_HIRING_AVATAR,
  ANDREA_RINCON_AVATAR
} from '../data/mockData';

interface CompanyProfileViewProps {
  companyName?: string;
  setScreen: (screen: AppScreen) => void;
  onSelectJob: (jobId: string) => void;
}

export const CompanyProfileView: React.FC<CompanyProfileViewProps> = ({
  companyName = 'Stark Cloud Corp',
  setScreen,
  onSelectJob,
}) => {
  const [activeTab, setActiveTab] = useState<'about' | 'jobs' | 'benefits' | 'team' | 'reviews'>('about');
  const [following, setFollowing] = useState(false);
  const [openSpontaneousModal, setOpenSpontaneousModal] = useState(false);

  const companyJobs = MOCK_JOBS.filter(j => 
    j.company.toLowerCase().includes(companyName.toLowerCase()) || 
    companyName.toLowerCase().includes(j.company.toLowerCase())
  );

  return (
    <div className="min-h-screen bg-[#fff8f6] py-6 sm:py-8 text-[#1f1b16]">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Navigation Breadcrumb */}
        <nav className="flex items-center gap-2 text-sm text-[#737373] mb-6 font-body">
          <button onClick={() => setScreen('home')} className="hover:text-[#a83301]">
            Inicio
          </button>
          <span>/</span>
          <button onClick={() => setScreen('explorer')} className="hover:text-[#a83301]">
            Empresas
          </button>
          <span>/</span>
          <span className="text-[#1f1b16] font-medium">{companyName}</span>
        </nav>

        {/* Company Header Banner */}
        <div className="bg-white rounded-3xl shadow-sm border border-[#e5e5e5] overflow-hidden mb-8">
          {/* Cover photo */}
          <div className="h-52 sm:h-72 w-full relative overflow-hidden bg-neutral-900">
            <img
              src={STARK_OFFICE_COVER}
              alt="Stark Cloud Office"
              className="w-full h-full object-cover opacity-90"
            />
            <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent" />
            <div className="absolute top-4 right-4 bg-white/20 backdrop-blur-md px-3 py-1.5 rounded-full text-xs text-white font-medium flex items-center gap-1.5 border border-white/30">
              <span className="material-symbols-outlined text-sm text-[#86efac]">verified_user</span>
              Empresa Verificada Échele Camello
            </div>
          </div>

          {/* Profile metadata info bar */}
          <div className="p-6 sm:p-8 relative">
            <div className="flex flex-col md:flex-row md:items-end justify-between gap-6 -mt-20 md:-mt-24 mb-6">
              <div className="flex items-end gap-5">
                <div className="w-24 h-24 sm:w-32 sm:h-32 rounded-3xl bg-white border-4 border-white shadow-xl p-3 flex items-center justify-center shrink-0 z-10">
                  <img
                    src={STARK_CLOUD_LOGO}
                    alt={companyName}
                    className="w-full h-full object-contain"
                  />
                </div>
                <div className="pt-2 z-10">
                  <div className="flex flex-wrap items-center gap-2 mb-1">
                    <h1 className="text-2xl sm:text-3xl font-extrabold text-[#1f1b16] font-headline">
                      {companyName}
                    </h1>
                    <span className="material-symbols-outlined text-[#0284c7] text-xl" title="Verificada">
                      verified
                    </span>
                  </div>
                  <p className="text-sm font-medium text-[#737373] font-body">
                    Infraestructura Multi-Cloud, Edge Computing & SaaS de Alta Frecuencia
                  </p>
                </div>
              </div>

              {/* Action Buttons */}
              <div className="flex items-center gap-3">
                <button
                  onClick={() => setFollowing(!following)}
                  className={`px-4 py-2.5 rounded-xl text-xs font-bold font-headline flex items-center gap-1.5 transition-all ${
                    following
                      ? 'bg-[#f5f5f5] text-[#525252] border border-[#d4d4d4]'
                      : 'bg-[#fff4f0] text-[#a83301] border border-[#ffdbd0] hover:bg-[#ffdbd0]'
                  }`}
                >
                  <span className="material-symbols-outlined text-sm">
                    {following ? 'notifications_active' : 'person_add'}
                  </span>
                  {following ? 'Siguiendo vacantes' : 'Seguir empresa'}
                </button>

                <button
                  onClick={() => setOpenSpontaneousModal(true)}
                  className="px-5 py-2.5 rounded-xl bg-[#a83301] hover:bg-[#ca4a1c] text-white font-bold font-headline text-xs shadow-md transition-all flex items-center gap-1.5"
                >
                  <span className="material-symbols-outlined text-sm">send</span>
                  Postulación Espontánea
                </button>
              </div>
            </div>

            {/* Meta Tags Row */}
            <div className="flex flex-wrap items-center gap-y-3 gap-x-6 text-xs text-[#525252] font-body pt-4 border-t border-[#f0f0f0]">
              <span className="flex items-center gap-1.5">
                <span className="material-symbols-outlined text-base text-[#a83301]">location_city</span>
                Medellín • Bogotá • 100% Remoto LATAM
              </span>
              <span className="flex items-center gap-1.5">
                <span className="material-symbols-outlined text-base text-[#a83301]">group</span>
                150 – 500 empleados
              </span>
              <span className="flex items-center gap-1.5">
                <span className="material-symbols-outlined text-base text-[#16a34a]">language</span>
                starkcloud.io
              </span>
              <span className="flex items-center gap-1.5 text-[#ca4a1c] font-bold">
                <span className="material-symbols-outlined text-base fill-1 text-amber-500">star</span>
                4.9 / 5 (86 evaluaciones de camelladores)
              </span>
              <span className="flex items-center gap-1.5 font-semibold text-[#16a34a]">
                <span className="material-symbols-outlined text-base">payments</span>
                Salarios en USD & Contrato Legal
              </span>
            </div>

            {/* Navigation Tabs */}
            <div className="flex items-center gap-2 overflow-x-auto mt-8 border-b border-[#f0f0f0] pb-px">
              {[
                { id: 'about', label: 'Acerca de & Cultura', icon: 'info' },
                { id: 'jobs', label: `Camellos Abiertos (${companyJobs.length || 3})`, icon: 'work' },
                { id: 'benefits', label: 'Beneficios & Salarios', icon: 'volunteer_activism' },
                { id: 'team', label: 'Equipo & Líderes', icon: 'diversity_3' },
                { id: 'reviews', label: 'Reseñas & Salarios', icon: 'rate_review' },
              ].map(tab => (
                <button
                  key={tab.id}
                  onClick={() => setActiveTab(tab.id as any)}
                  className={`px-4 py-3 text-xs sm:text-sm font-bold font-headline flex items-center gap-2 border-b-2 transition-all whitespace-nowrap ${
                    activeTab === tab.id
                      ? 'border-[#a83301] text-[#a83301]'
                      : 'border-transparent text-[#737373] hover:text-[#1f1b16]'
                  }`}
                >
                  <span className="material-symbols-outlined text-base">{tab.icon}</span>
                  {tab.label}
                </button>
              ))}
            </div>
          </div>
        </div>

        {/* Tab Content & Sidebar */}
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
          {/* Main Column */}
          <div className="lg:col-span-2 space-y-6">
            {activeTab === 'about' && (
              <>
                {/* About Pitch */}
                <div className="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-[#e5e5e5]">
                  <h2 className="text-xl font-bold font-headline text-[#1f1b16] mb-4">
                    Nuestra Misión
                  </h2>
                  <p className="text-[#525252] font-body leading-relaxed text-sm mb-4">
                    Stark Cloud Corp desarrolla la próxima generación de orquestación de nube distribuida y entrega de cómputo en el borde. Creamos herramientas que permiten a equipos de ingeniería en todo el mundo desplegar microservicios globales con latencias sub-milisegundo.
                  </p>
                  <p className="text-[#525252] font-body leading-relaxed text-sm mb-6">
                    Creemos firmemente en el talento de Colombia y Latinoamérica como semillero de innovación mundial. Por ello, pagamos en dólares competitivos con el mercado de San Francisco y Nueva York, fomentamos esquemas 100% remotos o híbridos con oficinas de clase mundial en Medellín (El Poblado) y Bogotá (Parque de la 93).
                  </p>

                  <h3 className="text-base font-bold font-headline text-[#1f1b16] mb-3">
                    Pilares de Nuestra Cultura
                  </h3>
                  <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div className="p-4 rounded-xl bg-[#fff8f6] border border-[#f5b8a5]">
                      <div className="flex items-center gap-2 text-[#a83301] font-bold text-sm mb-1 font-headline">
                        <span className="material-symbols-outlined text-base">visibility</span>
                        Transparencia Radical
                      </div>
                      <p className="text-xs text-[#525252] font-body">
                        Bandas salariales públicas y contratos sin cláusulas ocultas ni exclusividades abusivas.
                      </p>
                    </div>

                    <div className="p-4 rounded-xl bg-[#fff8f6] border border-[#f5b8a5]">
                      <div className="flex items-center gap-2 text-[#a83301] font-bold text-sm mb-1 font-headline">
                        <span className="material-symbols-outlined text-base">schedule</span>
                        Horarios Asíncronos
                      </div>
                      <p className="text-xs text-[#525252] font-body">
                        Medimos impacto y entregables, no horas frente a la silla ni micromanagement.
                      </p>
                    </div>

                    <div className="p-4 rounded-xl bg-[#fff8f6] border border-[#f5b8a5]">
                      <div className="flex items-center gap-2 text-[#a83301] font-bold text-sm mb-1 font-headline">
                        <span className="material-symbols-outlined text-base">rocket_launch</span>
                        Crecimiento Acelerado
                      </div>
                      <p className="text-xs text-[#525252] font-body">
                        Presupuesto anual de $1,500 USD por persona para cursos, libros, conferencias y certificaciones.
                      </p>
                    </div>

                    <div className="p-4 rounded-xl bg-[#fff8f6] border border-[#f5b8a5]">
                      <div className="flex items-center gap-2 text-[#a83301] font-bold text-sm mb-1 font-headline">
                        <span className="material-symbols-outlined text-base">favorite</span>
                        Bienestar Real
                      </div>
                      <p className="text-xs text-[#525252] font-body">
                        Seguro médico internacional integral, salud mental con psicólogos en línea y 25 días PTO.
                      </p>
                    </div>
                  </div>
                </div>

                {/* Tech Stack Matrix */}
                <div className="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-[#e5e5e5]">
                  <h2 className="text-xl font-bold font-headline text-[#1f1b16] mb-4">
                    Stack Tecnológico Central
                  </h2>
                  <div className="grid grid-cols-2 sm:grid-cols-4 gap-3 text-center">
                    {['Next.js 14', 'TypeScript', 'Go / Rust', 'Kubernetes', 'Tailwind CSS', 'GraphQL', 'AWS / GCP', 'Kafka / Redis'].map((tech, idx) => (
                      <div key={idx} className="p-3 bg-neutral-50 rounded-xl border border-neutral-200">
                        <span className="text-xs font-bold text-[#1f1b16]">{tech}</span>
                      </div>
                    ))}
                  </div>
                </div>
              </>
            )}

            {/* Jobs Tab */}
            {activeTab === 'jobs' && (
              <div className="space-y-4">
                <div className="bg-white rounded-2xl p-6 shadow-sm border border-[#e5e5e5]">
                  <h2 className="text-xl font-bold font-headline text-[#1f1b16] mb-1">
                    Vacantes Disponibles en {companyName}
                  </h2>
                  <p className="text-xs text-[#737373] font-body mb-6">
                    Todas las ofertas cuentan con salario explícito y proceso de selección ágil (&lt; 2 semanas).
                  </p>

                  <div className="space-y-4">
                    {(companyJobs.length > 0 ? companyJobs : MOCK_JOBS.slice(0, 3)).map(job => (
                      <div
                        key={job.id}
                        onClick={() => onSelectJob(job.id)}
                        className="p-5 rounded-2xl border border-[#e5e5e5] hover:border-[#a83301] hover:shadow-md transition-all cursor-pointer bg-white"
                      >
                        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-2">
                          <div>
                            <span className="text-xs font-bold text-[#a83301] uppercase tracking-wider">
                              {job.category}
                            </span>
                            <h3 className="text-lg font-bold text-[#1f1b16] font-headline">
                              {job.title}
                            </h3>
                          </div>
                          <span className="text-lg font-extrabold text-[#a83301] font-headline">
                            {job.salaryUSD || job.salaryCOP}
                          </span>
                        </div>

                        <div className="flex flex-wrap items-center gap-3 text-xs text-[#525252] font-body mb-3">
                          <span className="flex items-center gap-1">
                            <span className="material-symbols-outlined text-sm">laptop_mac</span>
                            {job.modality}
                          </span>
                          <span className="flex items-center gap-1">
                            <span className="material-symbols-outlined text-sm">military_tech</span>
                            {job.experienceLevel}
                          </span>
                          <span className="flex items-center gap-1">
                            <span className="material-symbols-outlined text-sm">badge</span>
                            {job.contractType}
                          </span>
                        </div>

                        <div className="flex items-center justify-between pt-3 border-t border-[#f5f5f5]">
                          <div className="flex flex-wrap gap-1.5">
                            {job.tags.slice(0, 3).map((t, idx) => (
                              <span key={idx} className="px-2 py-0.5 rounded bg-neutral-100 text-[#525252] text-xs font-medium">
                                {t}
                              </span>
                            ))}
                          </div>
                          <button
                            onClick={(e) => {
                              e.stopPropagation();
                              onSelectJob(job.id);
                            }}
                            className="px-4 py-1.5 bg-[#a83301] hover:bg-[#ca4a1c] text-white text-xs font-bold rounded-lg transition-colors font-headline"
                          >
                            Ver Vacante
                          </button>
                        </div>
                      </div>
                    ))}
                  </div>
                </div>
              </div>
            )}

            {/* Benefits Tab */}
            {activeTab === 'benefits' && (
              <div className="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-[#e5e5e5] space-y-6">
                <div>
                  <h2 className="text-xl font-bold font-headline text-[#1f1b16] mb-2">
                    Compensación & Beneficios Corporativos
                  </h2>
                  <p className="text-xs text-[#737373] font-body">
                    Beneficios diseñados para retener el mejor talento de Colombia y LATAM.
                  </p>
                </div>

                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  {[
                    { title: 'Salario en USD', desc: 'Depósitos directos por Payoneer, Deel, Wise o banco colombiano en dólares.', icon: 'attach_money' },
                    { title: 'Seguro Médico de Alto Nivel', desc: 'Póliza internacional con cobertura para ti y tu cónyuge o hijos.', icon: 'health_and_safety' },
                    { title: 'Equipo Hardware Premium', desc: 'MacBook Pro Apple Silicon M3 + monitor de 27" 4K a tu casa.', icon: 'laptop' },
                    { title: '25 Días PTO Libres', desc: 'Días de descanso remunerados sin burocracia.', icon: 'beach_access' },
                    { title: 'Coworking Pass', desc: 'Membresía ilimitada en WeWork y WeWork On Demand en toda la región.', icon: 'business' },
                    { title: 'Fondo de Cursos & Idiomas', desc: 'Acceso pagado a Coursera Plus, O’Reilly Learning y clases de inglés 1 a 1.', icon: 'school' },
                  ].map((b, idx) => (
                    <div key={idx} className="p-4 rounded-xl bg-[#fff8f6] border border-[#f5b8a5] flex items-start gap-3">
                      <span className="material-symbols-outlined text-[#a83301] text-2xl">{b.icon}</span>
                      <div>
                        <h4 className="text-sm font-bold text-[#1f1b16]">{b.title}</h4>
                        <p className="text-xs text-[#525252] mt-0.5">{b.desc}</p>
                      </div>
                    </div>
                  ))}
                </div>
              </div>
            )}

            {/* Team Tab */}
            {activeTab === 'team' && (
              <div className="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-[#e5e5e5]">
                <h2 className="text-xl font-bold font-headline text-[#1f1b16] mb-4">
                  Líderes de Ingeniería & Talento
                </h2>
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div className="p-4 rounded-2xl border border-[#e5e5e5] flex items-center gap-4">
                    <img
                      src={MATEO_VALENCIA_HIRING_AVATAR}
                      alt="Mateo"
                      className="w-14 h-14 rounded-full object-cover border-2 border-[#ffdbd0]"
                    />
                    <div>
                      <h4 className="font-bold text-sm text-[#1f1b16]">Mateo Valencia</h4>
                      <p className="text-xs text-[#a83301] font-semibold">VP of Engineering</p>
                      <p className="text-xs text-[#737373] mt-1">Ex-MercadoLibre, 12 años liderando equipos distribuidos.</p>
                    </div>
                  </div>

                  <div className="p-4 rounded-2xl border border-[#e5e5e5] flex items-center gap-4">
                    <img
                      src={ANDREA_RINCON_AVATAR}
                      alt="Andrea"
                      className="w-14 h-14 rounded-full object-cover border-2 border-[#ffdbd0]"
                    />
                    <div>
                      <h4 className="font-bold text-sm text-[#1f1b16]">Andrea Rincón</h4>
                      <p className="text-xs text-[#a83301] font-semibold">Head of People & Culture</p>
                      <p className="text-xs text-[#737373] mt-1">Especialista en compensación remota y bienestar laboral.</p>
                    </div>
                  </div>
                </div>
              </div>
            )}

            {/* Reviews Tab */}
            {activeTab === 'reviews' && (
              <div className="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-[#e5e5e5] space-y-6">
                <div className="flex items-center justify-between pb-4 border-b border-[#f0f0f0]">
                  <div>
                    <h2 className="text-xl font-bold font-headline text-[#1f1b16]">
                      Evaluaciones de Camelladores
                    </h2>
                    <p className="text-xs text-[#737373] font-body">
                      Opiniones anónimas y verificadas por contrato laboral.
                    </p>
                  </div>
                  <div className="text-right">
                    <div className="text-2xl font-black text-[#ca4a1c] font-headline flex items-center gap-1 justify-end">
                      <span className="material-symbols-outlined fill-1 text-amber-500">star</span>
                      4.9 / 5.0
                    </div>
                    <span className="text-xs text-[#737373]">86 evaluaciones</span>
                  </div>
                </div>

                <div className="space-y-4">
                  {[
                    {
                      role: 'Senior Backend Go Developer',
                      period: 'Trabaja aquí desde 2022',
                      rating: 5,
                      title: 'Excelente autonomía y el salario en USD sin intermediarios',
                      body: 'El proceso de selección fue súper directo y respetuoso. Cumplen al pie de la letra con los pagos quincenales en USD. El equipo es de altísimo nivel técnico.',
                    },
                    {
                      role: 'UI/UX Product Designer',
                      period: 'Trabajó 1 año',
                      rating: 4.8,
                      title: 'Cultura transparente y horarios verdaderamente flexibles',
                      body: 'Pude compaginar mi maestría sin ningún problema. La empresa fomenta la documentación asíncrona en Notion en vez de pasar el día en reuniones de Zoom.',
                    }
                  ].map((rev, idx) => (
                    <div key={idx} className="p-4 rounded-xl bg-[#fff8f6] border border-[#f5b8a5]">
                      <div className="flex items-center justify-between mb-2">
                        <div>
                          <p className="text-sm font-bold text-[#1f1b16]">{rev.title}</p>
                          <p className="text-xs text-[#737373]">{rev.role} • {rev.period}</p>
                        </div>
                        <span className="px-2 py-0.5 rounded bg-amber-100 text-amber-800 text-xs font-bold">
                          ★ {rev.rating}
                        </span>
                      </div>
                      <p className="text-xs text-[#525252] font-body leading-relaxed">
                        {rev.body}
                      </p>
                    </div>
                  ))}
                </div>
              </div>
            )}
          </div>

          {/* Sidebar */}
          <div className="space-y-6">
            {/* Quick Metrics Snapshot */}
            <div className="bg-white rounded-2xl p-6 shadow-sm border border-[#e5e5e5]">
              <h3 className="font-bold font-headline text-base text-[#1f1b16] mb-4">
                Indicadores de Contratación
              </h3>
              <div className="space-y-4 font-body">
                <div className="flex items-center justify-between">
                  <span className="text-xs text-[#737373]">Tiempo medio contratación:</span>
                  <span className="text-xs font-bold text-[#1f1b16]">14 días hábiles</span>
                </div>
                <div className="flex items-center justify-between">
                  <span className="text-xs text-[#737373]">Tasa de respuesta a CVs:</span>
                  <span className="text-xs font-bold text-[#16a34a]">97.4%</span>
                </div>
                <div className="flex items-center justify-between">
                  <span className="text-xs text-[#737373]">Satisfacción de empleados:</span>
                  <span className="text-xs font-bold text-[#ca4a1c]">98% recomienda</span>
                </div>
                <div className="flex items-center justify-between">
                  <span className="text-xs text-[#737373]">Rotación de personal:</span>
                  <span className="text-xs font-bold text-[#1f1b16]">Baja (&lt; 4% anual)</span>
                </div>
              </div>

              <div className="mt-6 pt-4 border-t border-[#f0f0f0]">
                <button
                  onClick={() => setOpenSpontaneousModal(true)}
                  className="w-full py-2.5 rounded-xl bg-[#a83301] hover:bg-[#ca4a1c] text-white font-bold font-headline text-xs shadow-md transition-all flex items-center justify-center gap-1.5"
                >
                  <span className="material-symbols-outlined text-sm">send</span>
                  Postularme a la empresa
                </button>
              </div>
            </div>

            {/* Hubs / Locations */}
            <div className="bg-white rounded-2xl p-6 shadow-sm border border-[#e5e5e5]">
              <h3 className="font-bold font-headline text-sm text-[#737373] uppercase tracking-wider mb-3">
                Sedes en Colombia
              </h3>
              <div className="space-y-3 text-xs font-body">
                <div className="p-3 rounded-xl bg-neutral-50 border border-neutral-200">
                  <p className="font-bold text-[#1f1b16] flex items-center gap-1.5">
                    <span className="material-symbols-outlined text-sm text-[#a83301]">place</span>
                    Hub Medellín
                  </p>
                  <p className="text-[#737373] mt-0.5">Cra 43A # 1-50, San Fernando Plaza, El Poblado</p>
                </div>

                <div className="p-3 rounded-xl bg-neutral-50 border border-neutral-200">
                  <p className="font-bold text-[#1f1b16] flex items-center gap-1.5">
                    <span className="material-symbols-outlined text-sm text-[#a83301]">place</span>
                    Hub Bogotá
                  </p>
                  <p className="text-[#737373] mt-0.5">Calle 93 # 12-40, Chicó Norte</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Spontaneous Application Modal */}
      {openSpontaneousModal && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm animate-fade-in">
          <div className="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-[#e5e5e5] relative animate-scale-up">
            <div className="flex items-center justify-between pb-3 border-b border-[#f0f0f0] mb-4">
              <h3 className="font-bold font-headline text-base text-[#1f1b16]">
                Postulación Espontánea a {companyName}
              </h3>
              <button
                onClick={() => setOpenSpontaneousModal(false)}
                className="w-8 h-8 rounded-full hover:bg-neutral-100 flex items-center justify-center text-[#737373]"
              >
                <span className="material-symbols-outlined text-lg">close</span>
              </button>
            </div>

            <p className="text-xs text-[#525252] font-body mb-4">
              ¿No ves una vacante que calce exactamente con tu especialidad? Envía tu perfil verificado para que los reclutadores de {companyName} te consideren para futuras aperturas.
            </p>

            <div className="space-y-3 text-xs font-body mb-6">
              <div>
                <label className="block font-bold text-[#525252] mb-1">Área de interés</label>
                <select className="w-full p-2.5 rounded-xl border border-[#d4d4d4] text-[#1f1b16]">
                  <option>Frontend / Fullstack Engineering</option>
                  <option>Backend & Cloud Infrastructure</option>
                  <option>UI/UX Product Design</option>
                  <option>Data & Machine Learning</option>
                  <option>DevOps / SRE</option>
                </select>
              </div>

              <div>
                <label className="block font-bold text-[#525252] mb-1">Aspiración salarial mínima (USD)</label>
                <input
                  type="text"
                  defaultValue="$3,500 USD / mes"
                  className="w-full p-2.5 rounded-xl border border-[#d4d4d4] text-[#1f1b16]"
                />
              </div>
            </div>

            <div className="flex items-center justify-end gap-3 pt-4 border-t border-[#f0f0f0]">
              <button
                onClick={() => setOpenSpontaneousModal(false)}
                className="px-4 py-2 text-xs font-bold text-[#737373]"
              >
                Cancelar
              </button>
              <button
                onClick={() => {
                  alert('¡Tu postulación espontánea ha sido guardada en la base de talento de ' + companyName + '!');
                  setOpenSpontaneousModal(false);
                }}
                className="px-5 py-2.5 bg-[#a83301] hover:bg-[#ca4a1c] text-white font-bold font-headline text-xs rounded-xl shadow-md"
              >
                Enviar Perfil
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};
