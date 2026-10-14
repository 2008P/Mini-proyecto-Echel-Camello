import React, { useState } from 'react';
import { AppScreen } from '../types';
import { CATEGORIES_LIST, MOCK_JOBS, MOCK_TESTIMONIALS } from '../data/mockData';
import { CamelLogo } from '../components/CamelLogo';

interface HomeViewProps {
  setScreen: (screen: AppScreen) => void;
  onSelectJob: (jobId: string) => void;
  onSearch: (query: string, location: string, salary: string) => void;
}

export const HomeView: React.FC<HomeViewProps> = ({
  setScreen,
  onSelectJob,
  onSearch,
}) => {
  const [roleQuery, setRoleQuery] = useState('');
  const [locationQuery, setLocationQuery] = useState('bogota');
  const [salaryQuery, setSalaryQuery] = useState('cualquiera');
  const [whatsappPhone, setWhatsappPhone] = useState('');
  const [whatsappSent, setWhatsappSent] = useState(false);

  const handleSearchSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    onSearch(roleQuery, locationQuery, salaryQuery);
    setScreen('explorer');
  };

  const handleCategoryClick = (categoryName: string) => {
    onSearch(categoryName, 'all', 'all');
    setScreen('explorer');
  };

  const handleWhatsAppAlert = (e: React.FormEvent) => {
    e.preventDefault();
    if (whatsappPhone.trim()) {
      setWhatsappSent(true);
      setTimeout(() => setWhatsappSent(false), 5000);
    }
  };

  return (
    <div className="flex flex-col w-full">
      {/* HERO CONSOLE SECTION */}
      <section className="relative w-full overflow-hidden bg-[#fff8f5] pt-10 pb-20 lg:pt-14 lg:pb-24">
        {/* Ambient Warm Glows */}
        <div className="absolute -top-32 right-[-5%] w-[520px] h-[520px] rounded-full bg-[#a83301]/5 blur-3xl pointer-events-none"></div>
        <div className="absolute top-1/2 left-[-10%] w-[420px] h-[420px] rounded-full bg-[#ffdcc0]/35 blur-2xl pointer-events-none"></div>

        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 flex flex-col items-center text-center">
          {/* Top Brand & Live Badge */}
          <div className="flex flex-wrap items-center justify-center gap-3 mb-6">
            <div className="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white shadow-sm border border-[#e1bfb5]/60 hover:scale-105 transition-transform">
              <CamelLogo size="sm" showBadge={false} />
              <span className="text-xs font-bold text-[#1f1b18] font-headline tracking-tight">
                Échele Camello
              </span>
            </div>
            <div className="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#f5ece7] shadow-xs border border-[#e1bfb5]/40">
              <span className="w-2 h-2 rounded-full bg-[#a83301] animate-pulse"></span>
              <span className="text-xs font-bold text-[#59413a] uppercase tracking-wider">
                +840 Nuevas oportunidades añadidas hoy
              </span>
            </div>
          </div>

          {/* Main Headline */}
          <h1 className="font-headline text-4xl sm:text-5xl lg:text-6xl text-[#1f1b18] max-w-4xl tracking-tight leading-[1.15] font-extrabold">
            ¡Échele Camello a su futuro! <br className="hidden sm:inline" />
            <span className="text-[#a83301] italic font-serif">El trabajo que busca</span> está aquí.
          </h1>

          <p className="mt-4 text-lg text-[#59413a] max-w-2xl text-balance">
            Conectamos al talento más “pilo”, trabajador y berraco con las mejores empresas de Colombia y oportunidades de trabajo remoto global con remuneración justa.
          </p>

          {/* Unified Level-3 Search Console */}
          <div className="w-full max-w-5xl mt-8 bg-white rounded-2xl shadow-xl overflow-hidden text-left p-2 sm:p-3 border border-[#e1bfb5]/40 relative">
            <div className="h-1.5 bg-[#a83301] w-full -mt-2 -mx-2 sm:-mt-3 sm:-mx-3 mb-3 rounded-t-2xl"></div>
            
            <form onSubmit={handleSearchSubmit} className="grid grid-cols-1 md:grid-cols-12 gap-2 sm:gap-3 items-center">
              
              {/* Keyword Zone */}
              <div className="md:col-span-4 flex items-center gap-2.5 px-3 py-2 bg-[#fbf2ed] rounded-xl border border-transparent focus-within:border-[#a83301] transition-colors">
                <span className="material-symbols-outlined text-[#a83301] text-[22px]">search</span>
                <div className="flex flex-col w-full min-w-0">
                  <label className="text-[10px] text-[#59413a] uppercase tracking-wider font-bold">
                    Cargo o palabra clave
                  </label>
                  <input
                    type="text"
                    value={roleQuery}
                    onChange={(e) => setRoleQuery(e.target.value)}
                    placeholder="Ej: Frontend, Asesor, Contador..."
                    className="w-full bg-transparent text-sm text-[#1f1b18] font-medium placeholder:text-[#8d7168] focus:outline-none truncate"
                  />
                </div>
              </div>

              {/* Location Zone */}
              <div className="md:col-span-3 flex items-center gap-2.5 px-3 py-2 bg-[#fbf2ed] rounded-xl border border-transparent focus-within:border-[#a83301] transition-colors">
                <span className="material-symbols-outlined text-[#545f73] text-[22px]">location_on</span>
                <div className="flex flex-col w-full min-w-0">
                  <label className="text-[10px] text-[#59413a] uppercase tracking-wider font-bold">
                    Ubicación
                  </label>
                  <select
                    value={locationQuery}
                    onChange={(e) => setLocationQuery(e.target.value)}
                    className="w-full bg-transparent text-sm text-[#1f1b18] font-medium focus:outline-none cursor-pointer"
                  >
                    <option value="remoto">100% Remoto (Global/LATAM)</option>
                    <option value="bogota">Bogotá, D.C.</option>
                    <option value="medellin">Medellín, Antioquia</option>
                    <option value="cali">Cali, Valle</option>
                    <option value="barranquilla">Barranquilla, Atlántico</option>
                    <option value="all">Todas las ciudades</option>
                  </select>
                </div>
              </div>

              {/* Salary Category Zone */}
              <div className="md:col-span-3 flex items-center gap-2.5 px-3 py-2 bg-[#fbf2ed] rounded-xl border border-transparent focus-within:border-[#a83301] transition-colors">
                <span className="material-symbols-outlined text-[#82511e] text-[22px]">payments</span>
                <div className="flex flex-col w-full min-w-0">
                  <label className="text-[10px] text-[#59413a] uppercase tracking-wider font-bold">
                    Rango Estimado
                  </label>
                  <select
                    value={salaryQuery}
                    onChange={(e) => setSalaryQuery(e.target.value)}
                    className="w-full bg-transparent text-sm text-[#1f1b18] font-medium focus:outline-none cursor-pointer"
                  >
                    <option value="cualquiera">Cualquier salario</option>
                    <option value="cop-2-4">COP $2.5M - $4.5M</option>
                    <option value="cop-4-8">COP $4.5M - $8M</option>
                    <option value="cop-8-15">COP $8M - $15M+</option>
                    <option value="usd">USD $1,800 - $4,500+</option>
                  </select>
                </div>
              </div>

              {/* Primary CTA Submit */}
              <div className="md:col-span-2">
                <button
                  type="submit"
                  className="w-full h-[52px] flex items-center justify-center gap-2 rounded-xl bg-[#a83301] hover:bg-[#ca4a1c] text-white font-headline text-base font-bold transition-all shadow-md active:scale-95 cursor-pointer"
                >
                  <span>Camellar</span>
                  <span className="material-symbols-outlined text-[20px]">arrow_forward</span>
                </button>
              </div>
            </form>
          </div>

          {/* Quick Search Pills */}
          <div className="mt-5 flex flex-wrap items-center justify-center gap-2 max-w-3xl">
            <span className="text-xs text-[#59413a] font-bold mr-1">Búsquedas top:</span>
            {[
              { label: '100% Remoto', icon: 'bolt' },
              { label: 'Desarrollo Software' },
              { label: 'Servicio al Cliente Bilingüe' },
              { label: 'Ventas B2B' },
              { label: 'Diseño Product / UI' },
              { label: 'Logística & Operaciones' },
            ].map((tag) => (
              <button
                key={tag.label}
                onClick={() => {
                  setRoleQuery(tag.label);
                  onSearch(tag.label, 'all', 'all');
                  setScreen('explorer');
                }}
                className="px-3 py-1 rounded-full bg-[#efe6e1] hover:bg-[#eae1dc] text-[#1f1b18] text-xs font-semibold transition-colors flex items-center gap-1 cursor-pointer"
              >
                {tag.icon && <span className="material-symbols-outlined text-[14px] text-[#a83301]">{tag.icon}</span>}
                <span>{tag.label}</span>
              </button>
            ))}
          </div>

          {/* Real-time Live Counters */}
          <div className="grid grid-cols-1 sm:grid-cols-3 gap-4 w-full max-w-4xl mt-12 pt-6">
            <div className="flex items-center gap-4 p-4 bg-white rounded-xl shadow-xs border border-[#e1bfb5]/40 text-left">
              <div className="w-12 h-12 rounded-xl bg-[#ffdbd0] flex items-center justify-center text-[#a83301]">
                <span className="material-symbols-outlined text-[26px]">work</span>
              </div>
              <div>
                <span className="font-headline text-2xl font-bold text-[#1f1b18] block leading-none">
                  +18,540
                </span>
                <span className="text-xs text-[#59413a] mt-1 block">Vacantes verificadas activas</span>
              </div>
            </div>

            <div className="flex items-center gap-4 p-4 bg-white rounded-xl shadow-xs border border-[#e1bfb5]/40 text-left">
              <div className="w-12 h-12 rounded-xl bg-[#d5e0f8] flex items-center justify-center text-[#545f73]">
                <span className="material-symbols-outlined text-[26px]">check_circle</span>
              </div>
              <div>
                <span className="font-headline text-2xl font-bold text-[#1f1b18] block leading-none">
                  94.2%
                </span>
                <span className="text-xs text-[#59413a] mt-1 block">Tasa de respuesta de empresas</span>
              </div>
            </div>

            <div className="flex items-center gap-4 p-4 bg-white rounded-xl shadow-xs border border-[#e1bfb5]/40 text-left">
              <div className="w-12 h-12 rounded-xl bg-[#ffdcc0] flex items-center justify-center text-[#82511e]">
                <span className="material-symbols-outlined text-[26px]">domain</span>
              </div>
              <div>
                <span className="font-headline text-2xl font-bold text-[#1f1b18] block leading-none">
                  +3,200
                </span>
                <span className="text-xs text-[#59413a] mt-1 block">Empresas contratando ya</span>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* CATEGORIES SECTION */}
      <section className="w-full bg-[#fbf2ed] py-16 sm:py-20 border-y border-[#e1bfb5]/40">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-10">
            <div>
              <div className="flex items-center gap-1.5 text-[#a83301] mb-1 font-bold">
                <span className="material-symbols-outlined text-[18px]">explore</span>
                <span className="text-xs uppercase tracking-wider">Sectores con Mayor Demanda</span>
              </div>
              <h2 className="font-headline text-3xl text-[#1f1b18] font-bold tracking-tight">
                Explore Camello por Categoría
              </h2>
            </div>
            <p className="text-sm text-[#59413a] max-w-md">
              Sectores dinámicos con salarios transparentes y oportunidades escalables desde cargos junior hasta liderazgo directivo.
            </p>
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {CATEGORIES_LIST.map((cat) => (
              <div
                key={cat.id}
                onClick={() => handleCategoryClick(cat.name)}
                className="group p-5 bg-white rounded-xl shadow-xs border border-[#e1bfb5]/40 hover:shadow-md hover:border-[#a83301]/40 transition-all hover:-translate-y-0.5 flex flex-col justify-between cursor-pointer"
              >
                <div className="flex items-start justify-between mb-4">
                  <div className="w-11 h-11 rounded-lg bg-[#f5ece7] flex items-center justify-center text-[#a83301] group-hover:bg-[#a83301] group-hover:text-white transition-colors">
                    <span className="material-symbols-outlined text-[24px]">{cat.icon}</span>
                  </div>
                  <span className="text-xs font-bold px-2 py-0.5 rounded bg-[#f5ece7] text-[#59413a]">
                    {cat.quota}
                  </span>
                </div>

                <div>
                  <h3 className="font-headline text-lg text-[#1f1b18] font-bold group-hover:text-[#a83301] transition-colors">
                    {cat.name}
                  </h3>
                  <p className="text-xs text-[#59413a] mt-1.5 line-clamp-2 leading-relaxed">
                    {cat.desc}
                  </p>
                </div>

                <div className="mt-4 pt-3 border-t border-[#f5ece7] flex items-center gap-1 text-[#a83301] text-xs font-bold">
                  <span>Ver vacantes</span>
                  <span className="material-symbols-outlined text-[16px] group-hover:translate-x-1 transition-transform">
                    arrow_forward
                  </span>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* FEATURED JOBS SECTION */}
      <section className="w-full bg-[#fff8f5] py-16 sm:py-20">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-10">
            <div>
              <div className="flex items-center gap-1.5 text-[#a83301] mb-1 font-bold">
                <span className="material-symbols-outlined text-[18px]">stars</span>
                <span className="text-xs uppercase tracking-wider">Selección Especial</span>
              </div>
              <h2 className="font-headline text-3xl text-[#1f1b18] font-bold tracking-tight">
                Camellos Destacados de la Semana
              </h2>
            </div>
            <button
              onClick={() => setScreen('explorer')}
              className="px-4 py-2 rounded-lg bg-[#a83301] hover:bg-[#ca4a1c] text-white text-sm font-semibold transition-colors self-start md:self-auto"
            >
              Ver las 18,540 ofertas
            </button>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            {MOCK_JOBS.slice(0, 6).map((job) => (
              <article
                key={job.id}
                className="bg-white rounded-xl p-5 shadow-xs border border-[#e1bfb5]/40 hover:shadow-md hover:border-[#a83301]/30 transition-all flex flex-col justify-between relative group"
              >
                <div>
                  <div className="flex items-start justify-between gap-3 mb-3">
                    <div className="flex items-center gap-3">
                      <div className="w-11 h-11 rounded-lg bg-[#f5ece7] flex items-center justify-center font-headline text-base font-bold text-[#a83301] overflow-hidden shrink-0 border border-[#e1bfb5]/40">
                        {job.companyLogo ? (
                          <img src={job.companyLogo} alt={job.company} className="w-full h-full object-contain p-1" />
                        ) : (
                          job.company.substring(0, 2).toUpperCase()
                        )}
                      </div>
                      <div>
                        <h4 className="text-xs font-bold text-[#1f1b18]">{job.company}</h4>
                        <span className="text-[11px] text-[#59413a] flex items-center gap-1 mt-0.5">
                          <span className="material-symbols-outlined text-[13px] text-[#545f73]">public</span>
                          {job.location}
                        </span>
                      </div>
                    </div>

                    {job.urgency && (
                      <span className="px-2 py-0.5 rounded bg-[#ffdbd0] text-[#842500] text-[10px] font-bold uppercase tracking-wider">
                        {job.urgency}
                      </span>
                    )}
                  </div>

                  <h3
                    onClick={() => {
                      onSelectJob(job.id);
                      setScreen('job-detail');
                    }}
                    className="font-headline text-base text-[#1f1b18] font-bold group-hover:text-[#a83301] transition-colors cursor-pointer leading-snug"
                  >
                    {job.title}
                  </h3>

                  <p className="text-xs text-[#59413a] mt-2 line-clamp-2 leading-relaxed">
                    {job.description}
                  </p>

                  <div className="mt-3 flex flex-wrap gap-1.5">
                    {job.tags.slice(0, 3).map((t) => (
                      <span
                        key={t}
                        className="px-2 py-0.5 rounded bg-[#f5ece7] text-[#59413a] text-[10px] font-semibold"
                      >
                        {t}
                      </span>
                    ))}
                  </div>
                </div>

                <div className="mt-5 pt-3 border-t border-[#f5ece7] flex items-center justify-between">
                  <div>
                    <span className="text-[10px] text-[#59413a] uppercase font-bold block">
                      Compensación
                    </span>
                    <span className="font-headline text-sm text-[#a83301] font-bold">
                      {job.salaryUSD || job.salaryCOP}
                    </span>
                  </div>

                  <button
                    onClick={() => {
                      onSelectJob(job.id);
                      setScreen('job-detail');
                    }}
                    className="px-3 py-1.5 rounded-lg bg-[#efe6e1] hover:bg-[#a83301] hover:text-white text-[#1f1b18] text-xs font-bold transition-colors flex items-center gap-1 cursor-pointer"
                  >
                    <span>Postular</span>
                    <span className="material-symbols-outlined text-[14px]">touch_app</span>
                  </button>
                </div>
              </article>
            ))}
          </div>
        </div>
      </section>

      {/* FOR RECRUITERS & B2B ENTERPRISE */}
      <section className="w-full bg-[#1f1b18] text-[#f8efea] py-16 sm:py-20 relative overflow-hidden">
        <div className="absolute -right-20 -bottom-20 w-96 h-96 rounded-full bg-[#a83301]/20 blur-3xl pointer-events-none"></div>

        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
          <div className="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
            
            <div className="lg:col-span-6 flex flex-col gap-4">
              <div className="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 w-fit text-[#ffdbd0]">
                <span className="material-symbols-outlined text-[16px]">business_center</span>
                <span className="text-xs uppercase tracking-wider font-bold">Para Empresas y Reclutadores</span>
              </div>

              <h2 className="font-headline text-3xl sm:text-4xl text-white font-bold leading-tight">
                Encuentre el talento que <span className="text-[#ffb59d] italic">no le saca el cuerpo al trabajo.</span>
              </h2>

              <p className="text-base text-white/80 leading-relaxed">
                Filtre hojas de vida en segundos con nuestra IA entrenada con jerga y perfiles latinoamericanos. Reduzca su costo de contratación hasta un 45% y acceda a postulantes evaluados técnicamente.
              </p>

              <div className="grid grid-cols-1 sm:grid-cols-3 gap-3 mt-2">
                <div className="p-3.5 rounded-xl bg-white/5 border border-white/10">
                  <span className="material-symbols-outlined text-[#ffb59d] text-[24px] mb-1">psychology</span>
                  <h4 className="font-headline text-sm text-white font-bold">Filtro IA</h4>
                  <p className="text-xs text-white/70 mt-1">Ranking automático por afinidad real de habilidades.</p>
                </div>
                <div className="p-3.5 rounded-xl bg-white/5 border border-white/10">
                  <span className="material-symbols-outlined text-[#ffb59d] text-[24px] mb-1">quiz</span>
                  <h4 className="font-headline text-sm text-white font-bold">Pruebas</h4>
                  <p className="text-xs text-white/70 mt-1">Evaluaciones técnicas y de inglés pre-configuradas.</p>
                </div>
                <div className="p-3.5 rounded-xl bg-white/5 border border-white/10">
                  <span className="material-symbols-outlined text-[#ffb59d] text-[24px] mb-1">speed</span>
                  <h4 className="font-headline text-sm text-white font-bold">Exprés</h4>
                  <p className="text-xs text-white/70 mt-1">Publique en 3 minutos y reciba CVs el mismo día.</p>
                </div>
              </div>

              <div className="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 mt-4">
                <button
                  onClick={() => setScreen('create-job')}
                  className="px-6 py-3 rounded-lg bg-[#a83301] hover:bg-[#ca4a1c] text-white font-headline text-sm font-bold transition-all shadow-md text-center cursor-pointer"
                >
                  Publicar Vacante Ahora
                </button>
                <button
                  onClick={() => setScreen('recruiter-dashboard')}
                  className="text-xs font-bold text-[#ffdbd0] hover:underline flex items-center justify-center gap-1"
                >
                  <span>Explorar Panel Reclutador B2B</span>
                  <span className="material-symbols-outlined text-[16px]">arrow_outward</span>
                </button>
              </div>
            </div>

            {/* Recruiter Simulation Bento Card */}
            <div className="lg:col-span-6">
              <div className="bg-white text-[#1f1b18] p-5 sm:p-6 rounded-2xl shadow-2xl border border-[#e1bfb5]/40">
                <div className="flex items-center justify-between pb-3 mb-3 bg-[#fbf2ed] p-3 rounded-xl border border-[#e1bfb5]/30">
                  <div className="flex items-center gap-3">
                    <div className="w-9 h-9 rounded-full bg-[#ffdbd0] flex items-center justify-center text-[#a83301] font-bold text-sm">
                      EC
                    </div>
                    <div>
                      <h4 className="text-xs font-bold text-[#1f1b18]">Panel de Reclutador Pro</h4>
                      <span className="text-[11px] text-[#59413a]">Convocatoria: Diseñador UX Senior</span>
                    </div>
                  </div>
                  <span className="px-2 py-0.5 rounded bg-[#a83301] text-white text-[10px] font-bold">
                    Activa • 72 Postulaciones
                  </span>
                </div>

                <div className="flex flex-col gap-2.5">
                  <div className="p-3 rounded-xl bg-[#fbf2ed] flex items-center justify-between">
                    <div className="flex items-center gap-2.5">
                      <div className="w-8 h-8 rounded-full bg-[#efe6e1] flex items-center justify-center font-bold text-xs text-[#1f1b18]">
                        MC
                      </div>
                      <div>
                        <h5 className="text-xs font-bold text-[#1f1b18]">Mariana Cárdenas</h5>
                        <span className="text-[11px] text-[#59413a]">6 años exp • Inglés C1 • Bogotá</span>
                      </div>
                    </div>
                    <div className="text-right">
                      <span className="font-headline text-sm font-bold text-[#a83301]">98% Match</span>
                      <span className="text-[10px] text-[#545f73] block">Prueba técnica: 10/10</span>
                    </div>
                  </div>

                  <div className="p-3 rounded-xl bg-[#fbf2ed] flex items-center justify-between">
                    <div className="flex items-center gap-2.5">
                      <div className="w-8 h-8 rounded-full bg-[#efe6e1] flex items-center justify-center font-bold text-xs text-[#1f1b18]">
                        DR
                      </div>
                      <div>
                        <h5 className="text-xs font-bold text-[#1f1b18]">Daniel Restrepo</h5>
                        <span className="text-[11px] text-[#59413a]">4 años exp • Design Systems • Medellín</span>
                      </div>
                    </div>
                    <div className="text-right">
                      <span className="font-headline text-sm font-bold text-[#a83301]">93% Match</span>
                      <span className="text-[10px] text-[#545f73] block">Prueba técnica: 9.2/10</span>
                    </div>
                  </div>

                  <div className="p-3 rounded-xl bg-[#fbf2ed] flex items-center justify-between">
                    <div className="flex items-center gap-2.5">
                      <div className="w-8 h-8 rounded-full bg-[#efe6e1] flex items-center justify-center font-bold text-xs text-[#1f1b18]">
                        LA
                      </div>
                      <div>
                        <h5 className="text-xs font-bold text-[#1f1b18]">Laura Aristizábal</h5>
                        <span className="text-[11px] text-[#59413a]">5 años exp • Remoto Colombia</span>
                      </div>
                    </div>
                    <div className="text-right">
                      <span className="font-headline text-sm font-bold text-[#a83301]">91% Match</span>
                      <span className="text-[10px] text-[#545f73] block">Prueba técnica: 9.0/10</span>
                    </div>
                  </div>
                </div>

                <div className="mt-4 p-3 bg-[#f5ece7] rounded-xl flex items-center justify-between text-xs text-[#59413a]">
                  <div className="flex items-center gap-1.5">
                    <span className="material-symbols-outlined text-[16px] text-[#a83301]">schedule</span>
                    <span>Tiempo promedio: <strong>9 días hábiles</strong></span>
                  </div>
                  <span className="text-[11px] font-bold text-[#a83301]">vs 35 días media LATAM</span>
                </div>
              </div>
            </div>

          </div>
        </div>
      </section>

      {/* TESTIMONIALS SECTION */}
      <section className="w-full bg-[#fbf2ed] py-16 sm:py-20 border-b border-[#e1bfb5]/40">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center max-w-2xl mx-auto mb-12">
            <div className="inline-flex items-center gap-1.5 text-[#a83301] mb-1 font-bold">
              <span className="material-symbols-outlined text-[18px]">thumb_up</span>
              <span className="text-xs uppercase tracking-wider">Casos Reales</span>
            </div>
            <h2 className="font-headline text-3xl text-[#1f1b18] font-bold tracking-tight">
              Testimonios de Camelladores Exitosos
            </h2>
            <p className="text-sm text-[#59413a] mt-1.5">
              Historias de personas con empuje y determinación que encontraron el empleo ideal a través de Échele Camello.
            </p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
            {MOCK_TESTIMONIALS.map((t) => (
              <div
                key={t.id}
                className="bg-white p-6 rounded-2xl shadow-xs border border-[#e1bfb5]/40 flex flex-col justify-between"
              >
                <div>
                  <div className="flex items-center gap-1 text-[#ca4a1c] mb-3">
                    {[1, 2, 3, 4, 5].map((star) => (
                      <span
                        key={star}
                        className="material-symbols-outlined text-[18px]"
                        style={{ fontVariationSettings: "'FILL' 1" }}
                      >
                        star
                      </span>
                    ))}
                  </div>
                  <p className="text-sm text-[#1f1b18] italic leading-relaxed">
                    “{t.quote}”
                  </p>
                </div>

                <div className="mt-6 pt-4 border-t border-[#f5ece7] flex items-center gap-3">
                  <img
                    src={t.avatar}
                    alt={t.author}
                    className="w-11 h-11 rounded-full object-cover shadow-xs border border-[#e1bfb5]"
                  />
                  <div>
                    <h4 className="font-headline text-sm font-bold text-[#1f1b18]">{t.author}</h4>
                    <span className="text-[11px] text-[#59413a]">
                      {t.role} • {t.hiredIn}
                    </span>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* WHATSAPP ALERTS & MOBILE APP DOWNLOAD */}
      <section className="w-full bg-[#fff8f5] py-16 sm:py-20">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="bg-[#a83301] rounded-3xl p-6 sm:p-10 lg:p-12 text-white relative overflow-hidden shadow-2xl">
            <div className="absolute -right-16 -top-16 w-80 h-80 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
            <div className="absolute -left-16 -bottom-16 w-80 h-80 rounded-full bg-black/15 blur-2xl pointer-events-none"></div>

            <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center relative z-10">
              <div className="lg:col-span-7 flex flex-col gap-4">
                <div className="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 w-fit text-[#ffdbd0]">
                  <span className="material-symbols-outlined text-[16px]">notifications_active</span>
                  <span className="text-xs uppercase tracking-wider font-bold">Alertas Instantáneas</span>
                </div>

                <h2 className="font-headline text-3xl sm:text-4xl text-white font-bold leading-tight">
                  ¡No se quede atrás! Reciba ofertas personalizadas en su WhatsApp o celular.
                </h2>

                <p className="text-sm text-white/90 max-w-xl leading-relaxed">
                  Configure sus preferencias de cargo y ciudad. Le enviaremos únicamente vacantes verificadas que coincidan exactamente con su perfil en el momento en que se abran.
                </p>

                <form onSubmit={handleWhatsAppAlert} className="mt-2 flex flex-col sm:flex-row gap-2 max-w-md">
                  <div className="flex-1 flex items-center bg-white rounded-xl px-3 py-2 text-[#1f1b18]">
                    <span className="material-symbols-outlined text-[#545f73] mr-2 text-[20px]">phone_android</span>
                    <input
                      type="tel"
                      value={whatsappPhone}
                      onChange={(e) => setWhatsappPhone(e.target.value)}
                      placeholder="Número celular / WhatsApp"
                      required
                      className="w-full bg-transparent text-sm focus:outline-none placeholder:text-[#8d7168]"
                    />
                  </div>
                  <button
                    type="submit"
                    className="px-5 py-2.5 rounded-xl bg-white text-[#a83301] hover:bg-[#fbf2ed] font-headline text-sm font-bold transition-all shadow-md active:scale-95 cursor-pointer"
                  >
                    Activar Alertas
                  </button>
                </form>

                {whatsappSent && (
                  <div className="p-3 bg-white/20 rounded-xl text-xs font-bold flex items-center gap-2">
                    <span className="material-symbols-outlined text-[18px]">check_circle</span>
                    <span>¡Listo! Te avisaremos por WhatsApp con las mejores vacantes verificadas.</span>
                  </div>
                )}

                <div className="flex flex-wrap items-center gap-4 text-xs text-white/80 mt-1">
                  <span className="flex items-center gap-1">
                    <span className="material-symbols-outlined text-[15px]">lock</span>
                    Sin spam ni publicidad
                  </span>
                  <span className="flex items-center gap-1">
                    <span className="material-symbols-outlined text-[15px]">cancel</span>
                    Cancele en cualquier momento
                  </span>
                </div>
              </div>

              {/* App download card */}
              <div className="lg:col-span-5 flex flex-col items-center justify-center">
                <div className="bg-white text-[#1f1b18] p-6 rounded-2xl shadow-xl w-full max-w-sm flex flex-col items-center text-center border border-[#e1bfb5]/40">
                  <div className="w-14 h-14 rounded-2xl bg-[#ffdbd0] flex items-center justify-center text-[#a83301] mb-3">
                    <span className="material-symbols-outlined text-[32px]">install_mobile</span>
                  </div>
                  
                  <h3 className="font-headline text-base font-bold text-[#1f1b18]">
                    Descargue la App Oficial
                  </h3>
                  
                  <p className="text-xs text-[#59413a] mt-1 mb-4">
                    Postulaciones rápidas con un toque, chat directo con reclutadores y seguimiento del estado de su hoja de vida.
                  </p>

                  <div className="flex flex-col w-full gap-2">
                    <button
                      onClick={() => alert('La App para Android está en fase beta en Google Play Store.')}
                      className="w-full flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-[#1f1b18] text-white text-xs font-bold hover:bg-[#34302c] transition-colors cursor-pointer"
                    >
                      <span className="material-symbols-outlined text-[18px]">shop</span>
                      <span>Disponible en Google Play</span>
                    </button>
                    <button
                      onClick={() => alert('La App para iOS está en fase TestFlight en Apple App Store.')}
                      className="w-full flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-[#f5ece7] text-[#1f1b18] text-xs font-bold hover:bg-[#eae1dc] transition-colors cursor-pointer"
                    >
                      <span className="material-symbols-outlined text-[18px]">file_download</span>
                      <span>Consíguela en App Store</span>
                    </button>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </section>
    </div>
  );
};
