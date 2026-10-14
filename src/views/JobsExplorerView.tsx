import React, { useState, useMemo } from 'react';
import { AppScreen, Job } from '../types';
import { MOCK_JOBS } from '../data/mockData';

interface JobsExplorerViewProps {
  setScreen: (screen: AppScreen) => void;
  onSelectJob: (jobId: string) => void;
  initialSearchQuery?: string;
  initialLocation?: string;
  initialSalary?: string;
}

export const JobsExplorerView: React.FC<JobsExplorerViewProps> = ({
  setScreen,
  onSelectJob,
  initialSearchQuery = 'Senior Fullstack Developer',
  initialLocation = 'remoto-colombia',
  initialSalary = 'cop-8m',
}) => {
  const [searchQuery, setSearchQuery] = useState(initialSearchQuery);
  const [selectedLocation, setSelectedLocation] = useState(initialLocation);
  const [selectedSalaryMin, setSelectedSalaryMin] = useState(initialSalary);

  // Filters
  const [modalities, setModalities] = useState<string[]>(['100% Remoto', 'Híbrido']);
  const [salaryBands, setSalaryBands] = useState<string[]>(['$6M - $10M COP', '+$10M COP']);
  const [contractTypes, setContractTypes] = useState<string[]>(['Término Indefinido (Leyes COP)']);
  const [seniorities, setSeniorities] = useState<string[]>(['Senior (5a+)', 'Lead & Staff']);
  const [perks, setPerks] = useState<string[]>(['Medicina Prepagada 100%', 'Equipos Apple Pro Provistos']);
  const [recency, setRecency] = useState<string>('semana');

  // Controls
  const [fastApplyOnly, setFastApplyOnly] = useState(true);
  const [sortBy, setSortBy] = useState('salary');
  const [savedJobs, setSavedJobs] = useState<Record<string, boolean>>({});
  const [appliedJobs, setAppliedJobs] = useState<Record<string, boolean>>({});
  const [currentPage, setCurrentPage] = useState(1);
  const [alertEmail, setAlertEmail] = useState('');
  const [alertSuccess, setAlertSuccess] = useState(false);

  const toggleModalities = (val: string) => {
    setModalities((prev) =>
      prev.includes(val) ? prev.filter((m) => m !== val) : [...prev, val]
    );
  };

  const toggleSalaryBand = (val: string) => {
    setSalaryBands((prev) =>
      prev.includes(val) ? prev.filter((s) => s !== val) : [...prev, val]
    );
  };

  const toggleContractType = (val: string) => {
    setContractTypes((prev) =>
      prev.includes(val) ? prev.filter((c) => c !== val) : [...prev, val]
    );
  };

  const toggleSeniority = (val: string) => {
    setSeniorities((prev) =>
      prev.includes(val) ? prev.filter((s) => s !== val) : [...prev, val]
    );
  };

  const togglePerk = (val: string) => {
    setPerks((prev) =>
      prev.includes(val) ? prev.filter((p) => p !== val) : [...prev, val]
    );
  };

  const resetAllFilters = () => {
    setModalities([]);
    setSalaryBands([]);
    setContractTypes([]);
    setSeniorities([]);
    setPerks([]);
    setSearchQuery('');
  };

  const toggleBookmark = (jobId: string, e: React.MouseEvent) => {
    e.stopPropagation();
    setSavedJobs((prev) => ({ ...prev, [jobId]: !prev[jobId] }));
  };

  const handleFastApply = (jobId: string, e: React.MouseEvent) => {
    e.stopPropagation();
    setAppliedJobs((prev) => ({ ...prev, [jobId]: true }));
    setTimeout(() => {
      alert('¡Postulación enviada en 1 Clic con tu perfil de Échele Camello!');
    }, 150);
  };

  // Filtered Jobs
  const filteredJobs = useMemo(() => {
    let list = [...MOCK_JOBS];

    if (searchQuery.trim()) {
      const q = searchQuery.toLowerCase();
      list = list.filter(
        (j) =>
          j.title.toLowerCase().includes(q) ||
          j.company.toLowerCase().includes(q) ||
          j.tags.some((t) => t.toLowerCase().includes(q))
      );
    }

    if (modalities.length > 0) {
      list = list.filter((j) =>
        modalities.some((m) => j.modality.toLowerCase().includes(m.toLowerCase().replace('100% ', '')))
      );
    }

    if (sortBy === 'salary') {
      list.sort((a, b) => (b.salaryMax || 0) - (a.salaryMax || 0));
    }

    return list.length > 0 ? list : MOCK_JOBS;
  }, [searchQuery, modalities, sortBy]);

  const activeFiltersCount =
    modalities.length +
    salaryBands.length +
    contractTypes.length +
    seniorities.length +
    perks.length;

  return (
    <div className="flex flex-col w-full">
      {/* Top Search Console Bar */}
      <section className="w-full bg-[#fbf2ed] py-8 border-b border-[#e1bfb5]/40">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          
          <div className="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6">
            <div>
              <span className="inline-flex items-center gap-1.5 text-[#a83301] text-xs font-bold uppercase tracking-wider mb-1">
                <span className="material-symbols-outlined text-[16px]">work_history</span>
                Directorio Oficial · Conexión de Talento
              </span>
              <h1 className="font-headline text-3xl sm:text-4xl text-[#1f1b18] font-bold tracking-tight">
                Explorador de Camellos
              </h1>
              <p className="text-sm text-[#59413a] mt-1">
                Encuentra oportunidades reales con salarios transparentes y empresas verificadas.
              </p>
            </div>

            <div className="flex items-center gap-2 bg-[#f5ece7] px-4 py-2 rounded-xl self-start md:self-auto border border-[#e1bfb5]/30">
              <span className="w-2.5 h-2.5 rounded-full bg-[#a83301] animate-pulse"></span>
              <span className="text-xs font-bold text-[#1f1b18]">184 vacantes nuevas hoy</span>
            </div>
          </div>

          {/* Unified 3-Zone Search Console */}
          <div className="bg-white p-3 rounded-2xl shadow-xl border border-[#e1bfb5]/40">
            <form onSubmit={(e) => e.preventDefault()} className="grid grid-cols-1 lg:grid-cols-12 gap-2.5 items-center">
              
              {/* Zone 1: Keyword */}
              <div className="lg:col-span-4 flex items-center gap-2.5 px-3 py-2 bg-[#fbf2ed] rounded-xl border border-transparent focus-within:border-[#a83301]">
                <span className="material-symbols-outlined text-[#a83301] text-[22px]">search</span>
                <div className="flex flex-col flex-1 min-w-0">
                  <label className="text-[10px] text-[#59413a] font-bold uppercase">Cargo o habilidad</label>
                  <input
                    type="text"
                    value={searchQuery}
                    onChange={(e) => setSearchQuery(e.target.value)}
                    placeholder="Ej. Frontend React, Tech Lead..."
                    className="bg-transparent border-0 p-0 text-[#1f1b18] text-sm font-medium focus:outline-none w-full"
                  />
                </div>
              </div>

              {/* Zone 2: Location */}
              <div className="lg:col-span-3 flex items-center gap-2.5 px-3 py-2 bg-[#fbf2ed] rounded-xl border border-transparent focus-within:border-[#a83301]">
                <span className="material-symbols-outlined text-[#82511e] text-[22px]">location_on</span>
                <div className="flex flex-col flex-1 min-w-0">
                  <label className="text-[10px] text-[#59413a] font-bold uppercase">Ubicación</label>
                  <select
                    value={selectedLocation}
                    onChange={(e) => setSelectedLocation(e.target.value)}
                    className="bg-transparent border-0 p-0 text-[#1f1b18] text-sm font-medium focus:outline-none cursor-pointer w-full"
                  >
                    <option value="all">Todas las ubicaciones</option>
                    <option value="remoto-colombia">Remoto (Colombia)</option>
                    <option value="bogota">Bogotá D.C.</option>
                    <option value="medellin">Medellín, Antioquia</option>
                    <option value="remoto-latam">Remoto (LATAM / USD)</option>
                    <option value="cali">Cali · Valle</option>
                  </select>
                </div>
              </div>

              {/* Zone 3: Salary */}
              <div className="lg:col-span-3 flex items-center gap-2.5 px-3 py-2 bg-[#fbf2ed] rounded-xl border border-transparent focus-within:border-[#a83301]">
                <span className="material-symbols-outlined text-[#ca4a1c] text-[22px]">payments</span>
                <div className="flex flex-col flex-1 min-w-0">
                  <label className="text-[10px] text-[#59413a] font-bold uppercase">Rango Salarial Mínimo</label>
                  <select
                    value={selectedSalaryMin}
                    onChange={(e) => setSelectedSalaryMin(e.target.value)}
                    className="bg-transparent border-0 p-0 text-[#1f1b18] text-sm font-medium focus:outline-none cursor-pointer w-full"
                  >
                    <option value="any">Cualquier Salario</option>
                    <option value="cop-4m">Mínimo $4,000,000 COP</option>
                    <option value="cop-8m">Mínimo $8,000,000 COP</option>
                    <option value="cop-12m">Mínimo $12,000,000 COP</option>
                    <option value="usd-2k">Mínimo USD $2,500/mes</option>
                    <option value="usd-4k">Mínimo USD $4,000/mes</option>
                  </select>
                </div>
              </div>

              {/* Action CTA */}
              <div className="lg:col-span-2">
                <button
                  type="button"
                  className="w-full flex items-center justify-center gap-2 bg-[#a83301] hover:bg-[#ca4a1c] text-white py-3 px-4 rounded-xl font-headline text-sm font-bold transition-all shadow-md active:scale-95 cursor-pointer"
                >
                  <span className="material-symbols-outlined text-[18px]">bolt</span>
                  <span>Buscar camellos</span>
                </button>
              </div>

            </form>

            {/* Quick Pills */}
            <div className="flex flex-wrap items-center gap-2 pt-3 px-2 border-t border-[#f5ece7] mt-2">
              <span className="text-xs text-[#59413a] font-bold">Búsquedas calientes:</span>
              {[
                'Tech Lead Node',
                'Diseñador Product UI',
                'DevOps AWS USD',
                'Ejecutivo Comercial B2B',
                'Data Analyst Mid',
              ].map((pill) => (
                <button
                  key={pill}
                  onClick={() => setSearchQuery(pill)}
                  className="px-2.5 py-1 bg-[#efe6e1] hover:bg-[#eae1dc] text-[#1f1b18] text-xs font-medium rounded-full transition-colors cursor-pointer"
                >
                  {pill}
                </button>
              ))}
            </div>
          </div>

        </div>
      </section>

      {/* Two-Column Master Workspace */}
      <section className="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
          
          {/* LEFT COLUMN: Faceted Filters (4 Cols) */}
          <aside className="lg:col-span-4 flex flex-col gap-5">
            {/* Filter Header Box */}
            <div className="bg-white p-4 rounded-xl shadow-xs border border-[#e1bfb5]/40 flex items-center justify-between">
              <div className="flex items-center gap-2">
                <span className="material-symbols-outlined text-[#a83301] text-[20px]">tune</span>
                <h2 className="font-headline text-base text-[#1f1b18] font-bold">Filtros Facetados</h2>
              </div>
              <button
                onClick={resetAllFilters}
                className="text-[#a83301] hover:text-[#ca4a1c] text-xs font-bold underline cursor-pointer transition-colors"
              >
                Limpiar todo
              </button>
            </div>

            {/* Facets Box */}
            <div className="bg-white p-5 rounded-xl shadow-xs border border-[#e1bfb5]/40 flex flex-col gap-6">
              
              {/* Modalidad */}
              <div className="flex flex-col gap-2">
                <div className="flex items-center justify-between">
                  <span className="text-sm font-bold text-[#1f1b18]">Modalidad</span>
                  <span className="text-xs font-bold bg-[#efe6e1] px-2 py-0.5 rounded text-[#59413a]">
                    {modalities.length} selecc.
                  </span>
                </div>
                <div className="flex flex-col gap-1.5">
                  {[
                    { label: '100% Remoto', count: 892 },
                    { label: 'Híbrido (1-2 días oficina)', count: 364 },
                    { label: 'Presencial', count: 172 },
                  ].map((item) => (
                    <label
                      key={item.label}
                      className="flex items-center justify-between p-2 rounded-lg hover:bg-[#fbf2ed] cursor-pointer transition-colors"
                    >
                      <div className="flex items-center gap-2.5">
                        <input
                          type="checkbox"
                          checked={modalities.includes(item.label.split(' (')[0])}
                          onChange={() => toggleModalities(item.label.split(' (')[0])}
                          className="w-4 h-4 rounded text-[#a83301] accent-[#a83301]"
                        />
                        <span className="text-xs text-[#1f1b18] font-medium">{item.label}</span>
                      </div>
                      <span className="text-xs text-[#8d7168]">{item.count}</span>
                    </label>
                  ))}
                </div>
              </div>

              <div className="h-px bg-[#f5ece7]"></div>

              {/* Rango Salarial */}
              <div className="flex flex-col gap-2">
                <div className="flex items-center justify-between">
                  <span className="text-sm font-bold text-[#1f1b18]">Rango Salarial Mensual</span>
                  <span className="text-xs text-[#a83301] font-bold">100% Explícito</span>
                </div>
                <div className="flex flex-col gap-1.5">
                  {[
                    { label: '$3M - $6M COP', count: 420 },
                    { label: '$6M - $10M COP', count: 518 },
                    { label: '+$10M COP', count: 285 },
                    { label: 'USD $2,500 - $5,000+', count: 205 },
                  ].map((item) => (
                    <label
                      key={item.label}
                      className="flex items-center justify-between p-2 rounded-lg hover:bg-[#fbf2ed] cursor-pointer transition-colors"
                    >
                      <div className="flex items-center gap-2.5">
                        <input
                          type="checkbox"
                          checked={salaryBands.includes(item.label)}
                          onChange={() => toggleSalaryBand(item.label)}
                          className="w-4 h-4 rounded text-[#a83301] accent-[#a83301]"
                        />
                        <span className="text-xs text-[#1f1b18] font-medium">{item.label}</span>
                      </div>
                      <span className="text-xs text-[#8d7168]">{item.count}</span>
                    </label>
                  ))}
                </div>
              </div>

              <div className="h-px bg-[#f5ece7]"></div>

              {/* Tipo de Vinculación */}
              <div className="flex flex-col gap-2">
                <span className="text-sm font-bold text-[#1f1b18]">Tipo de Vinculación</span>
                <div className="flex flex-col gap-1.5">
                  {[
                    { label: 'Término Indefinido (Leyes COP)', count: 930 },
                    { label: 'Prestación de Servicios', count: 312 },
                    { label: 'Contractor Internacional (USD)', count: 186 },
                  ].map((item) => (
                    <label
                      key={item.label}
                      className="flex items-center justify-between p-2 rounded-lg hover:bg-[#fbf2ed] cursor-pointer transition-colors"
                    >
                      <div className="flex items-center gap-2.5">
                        <input
                          type="checkbox"
                          checked={contractTypes.includes(item.label)}
                          onChange={() => toggleContractType(item.label)}
                          className="w-4 h-4 rounded text-[#a83301] accent-[#a83301]"
                        />
                        <span className="text-xs text-[#1f1b18] font-medium">{item.label}</span>
                      </div>
                      <span className="text-xs text-[#8d7168]">{item.count}</span>
                    </label>
                  ))}
                </div>
              </div>

              <div className="h-px bg-[#f5ece7]"></div>

              {/* Nivel de Experiencia */}
              <div className="flex flex-col gap-2">
                <span className="text-sm font-bold text-[#1f1b18]">Nivel de Experiencia</span>
                <div className="grid grid-cols-2 gap-2">
                  {['Junior (0-2a)', 'Semi-Senior', 'Senior (5a+)', 'Lead & Staff'].map((lvl) => (
                    <label
                      key={lvl}
                      className={`p-2 rounded-lg border flex items-center gap-2 cursor-pointer transition-colors text-xs ${
                        seniorities.includes(lvl)
                          ? 'bg-[#f5ece7] border-[#a83301] text-[#a83301] font-bold'
                          : 'bg-[#fbf2ed] border-transparent text-[#1f1b18]'
                      }`}
                    >
                      <input
                        type="checkbox"
                        checked={seniorities.includes(lvl)}
                        onChange={() => toggleSeniority(lvl)}
                        className="rounded text-[#a83301] accent-[#a83301]"
                      />
                      <span>{lvl}</span>
                    </label>
                  ))}
                </div>
              </div>

              <div className="h-px bg-[#f5ece7]"></div>

              {/* Beneficios Garantizados */}
              <div className="flex flex-col gap-2">
                <span className="text-sm font-bold text-[#1f1b18]">Beneficios Garantizados</span>
                <div className="flex flex-col gap-1.5">
                  {[
                    { label: 'Medicina Prepagada 100%', icon: 'medical_services' },
                    { label: 'Equipos Apple Pro Provistos', icon: 'laptop_mac' },
                    { label: 'Bono Adecuación Home Office', icon: 'home' },
                    { label: 'Vacaciones Ilimitadas', icon: 'beach_access' },
                  ].map((b) => (
                    <label
                      key={b.label}
                      className="flex items-center gap-2 p-2 rounded-lg hover:bg-[#fbf2ed] cursor-pointer"
                    >
                      <input
                        type="checkbox"
                        checked={perks.includes(b.label)}
                        onChange={() => togglePerk(b.label)}
                        className="w-4 h-4 rounded text-[#a83301] accent-[#a83301]"
                      />
                      <span className="material-symbols-outlined text-[18px] text-[#82511e]">{b.icon}</span>
                      <span className="text-xs text-[#1f1b18]">{b.label}</span>
                    </label>
                  ))}
                </div>
              </div>

              <div className="h-px bg-[#f5ece7]"></div>

              {/* Antigüedad */}
              <div className="flex flex-col gap-2">
                <span className="text-sm font-bold text-[#1f1b18]">Antigüedad</span>
                <div className="flex items-center gap-1.5">
                  {[
                    { id: '24h', label: 'Últimas 24h' },
                    { id: 'semana', label: 'Última semana' },
                    { id: 'cualquiera', label: 'Cualquiera' },
                  ].map((r) => (
                    <button
                      key={r.id}
                      onClick={() => setRecency(r.id)}
                      className={`flex-1 py-1.5 px-2 rounded-lg text-xs font-bold transition-all ${
                        recency === r.id
                          ? 'bg-[#a83301] text-white shadow-xs'
                          : 'bg-[#efe6e1] text-[#1f1b18] hover:bg-[#eae1dc]'
                      }`}
                    >
                      {r.label}
                    </button>
                  ))}
                </div>
              </div>

            </div>

            {/* Sidebar Promo Card: Trust */}
            <div className="bg-[#efe6e1] p-5 rounded-2xl relative overflow-hidden flex flex-col gap-2 border border-[#e1bfb5]/40">
              <div className="flex items-center gap-1.5 text-[#a83301] text-xs font-bold uppercase tracking-wider">
                <span className="material-symbols-outlined text-[18px]">verified_user</span>
                <span>Transparencia Radical</span>
              </div>
              <h3 className="font-headline text-lg font-bold text-[#1f1b18]">
                0% vacantes sin salario
              </h3>
              <p className="text-xs text-[#59413a] leading-relaxed">
                En Échele Camello sancionamos ofertas fantasma o salarios "a convenir". Tu tiempo y talento valen plata desde el día cero.
              </p>
              <div className="pt-2 flex items-center gap-4 text-xs font-semibold text-[#59413a]">
                <span className="flex items-center gap-1">
                  <span className="material-symbols-outlined text-[#a83301] text-[15px]">check</span> Validado por IA
                </span>
                <span className="flex items-center gap-1">
                  <span className="material-symbols-outlined text-[#a83301] text-[15px]">check</span> Contratos Legales
                </span>
              </div>
            </div>

          </aside>

          {/* RIGHT COLUMN: Results Feed & Job Cards (8 Cols) */}
          <main className="lg:col-span-8 flex flex-col gap-5">
            {/* Live Results Control Bar */}
            <div className="bg-white p-4 rounded-xl shadow-xs border border-[#e1bfb5]/40 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
              <div className="flex items-center gap-2">
                <span className="font-headline text-lg font-bold text-[#1f1b18]">1,428</span>
                <span className="text-xs text-[#59413a]">vacantes encontradas</span>
                <span className="w-1.5 h-1.5 rounded-full bg-[#8d7168]/40"></span>
                <span className="text-[11px] font-bold text-[#82511e] bg-[#ffdcc0] px-2 py-0.5 rounded">
                  Filtros Activos: {activeFiltersCount}
                </span>
              </div>

              <div className="flex items-center flex-wrap gap-4">
                {/* 1-Click Apply Toggle Switch */}
                <label className="flex items-center gap-2 cursor-pointer select-none">
                  <div
                    onClick={() => setFastApplyOnly(!fastApplyOnly)}
                    className={`w-10 h-6 rounded-full transition-colors p-0.5 flex items-center ${
                      fastApplyOnly ? 'bg-[#a83301]' : 'bg-[#eae1dc]'
                    }`}
                  >
                    <div
                      className={`w-5 h-5 rounded-full bg-white transition-transform ${
                        fastApplyOnly ? 'translate-x-4' : 'translate-x-0'
                      }`}
                    ></div>
                  </div>
                  <span className="text-xs text-[#1f1b18] font-bold flex items-center gap-1">
                    <span className="material-symbols-outlined text-[16px] text-[#a83301]">offline_bolt</span>
                    1-Clic
                  </span>
                </label>

                {/* Sort By Select */}
                <div className="flex items-center gap-1.5 bg-[#fbf2ed] px-3 py-1.5 rounded-lg border border-[#e1bfb5]/40">
                  <span className="material-symbols-outlined text-[#59413a] text-[18px]">sort</span>
                  <select
                    value={sortBy}
                    onChange={(e) => setSortBy(e.target.value)}
                    className="bg-transparent border-0 text-xs font-semibold text-[#1f1b18] focus:outline-none cursor-pointer"
                  >
                    <option value="salary">Mayor salario</option>
                    <option value="recent">Más recientes</option>
                    <option value="compatibility">Mayor compatibilidad</option>
                    <option value="fastest">Respuesta rápida (&lt;24h)</option>
                  </select>
                </div>
              </div>
            </div>

            {/* JOB CARDS LIST */}
            <div className="flex flex-col gap-4">
              {filteredJobs.map((job) => {
                const isBookmarked = !!savedJobs[job.id];
                const isApplied = !!appliedJobs[job.id];

                return (
                  <article
                    key={job.id}
                    className="bg-white p-5 rounded-2xl shadow-xs border border-[#e1bfb5]/40 hover:shadow-md hover:border-[#a83301]/40 transition-all flex flex-col gap-4 group"
                  >
                    {/* Top Row: Badge + Company Info */}
                    <div className="flex items-start justify-between gap-4">
                      <div className="flex items-start gap-3.5 min-w-0">
                        <div className="w-13 h-13 rounded-xl bg-[#f5ece7] flex items-center justify-center p-1 border border-[#e1bfb5]/40 shrink-0 overflow-hidden">
                          {job.companyLogo ? (
                            <img src={job.companyLogo} alt={job.company} className="w-full h-full object-contain" />
                          ) : (
                            <span className="font-headline font-bold text-sm text-[#a83301]">
                              {job.company.substring(0, 2).toUpperCase()}
                            </span>
                          )}
                        </div>

                        <div className="flex flex-col min-w-0">
                          <div className="flex items-center flex-wrap gap-2 mb-1">
                            <h3
                              onClick={() => {
                                onSelectJob(job.id);
                                setScreen('job-detail');
                              }}
                              className="font-headline text-base sm:text-lg text-[#1f1b18] font-bold group-hover:text-[#a83301] transition-colors cursor-pointer leading-snug"
                            >
                              {job.title}
                            </h3>
                            {job.urgency && (
                              <span className="inline-flex items-center gap-1 bg-[#ffdbd0] text-[#842500] px-2 py-0.5 rounded text-[10px] font-bold uppercase">
                                {job.urgency}
                              </span>
                            )}
                          </div>

                          <div className="flex items-center flex-wrap gap-2 text-xs text-[#59413a]">
                            <span
                              onClick={() => setScreen('company-profile')}
                              className="font-bold text-[#1f1b18] hover:text-[#a83301] cursor-pointer"
                            >
                              {job.company}
                            </span>
                            <span>•</span>
                            <span className="flex items-center gap-1 text-[#82511e] font-semibold">
                              <span className="material-symbols-outlined text-[15px]">verified</span>
                              Empresa Verificada
                            </span>
                            <span>•</span>
                            <span className="flex items-center gap-1 text-[#545f73]">
                              <span className="material-symbols-outlined text-[15px]">schedule</span>
                              Respuesta &lt; 24h
                            </span>
                          </div>
                        </div>
                      </div>

                      {/* Bookmark button */}
                      <button
                        onClick={(e) => toggleBookmark(job.id, e)}
                        className={`p-2 rounded-lg transition-colors shrink-0 ${
                          isBookmarked
                            ? 'bg-[#ffdbd0] text-[#a83301]'
                            : 'text-[#8d7168] hover:text-[#a83301] hover:bg-[#f5ece7]'
                        }`}
                        title={isBookmarked ? 'Guardada' : 'Guardar oferta'}
                      >
                        <span
                          className="material-symbols-outlined text-[22px]"
                          style={{ fontVariationSettings: isBookmarked ? "'FILL' 1" : "'FILL' 0" }}
                        >
                          bookmark
                        </span>
                      </button>
                    </div>

                    {/* Middle Row: Salary Banner */}
                    <div className="flex flex-wrap items-baseline justify-between gap-3 p-3 rounded-xl bg-[#fbf2ed] border border-[#e1bfb5]/30">
                      <div className="flex flex-wrap items-baseline gap-2">
                        <span className="font-headline text-lg sm:text-xl text-[#a83301] font-bold tracking-tight">
                          {job.salaryCOP || job.salaryUSD}
                        </span>
                        <span className="text-xs text-[#59413a] font-semibold">{job.salaryPeriod}</span>
                        <span className="text-[11px] bg-[#f5ece7] px-2 py-0.5 rounded font-semibold text-[#1f1b18]">
                          {job.contractType}
                        </span>
                      </div>

                      <div className="flex items-center gap-1 text-xs text-[#59413a] font-medium">
                        <span className="material-symbols-outlined text-[#a83301] text-[16px]">public</span>
                        <span>{job.location}</span>
                      </div>
                    </div>

                    {/* Description Snippet */}
                    <p className="text-xs text-[#59413a] line-clamp-2 leading-relaxed">
                      {job.description}
                    </p>

                    {/* Technical Chips & Perks */}
                    <div className="flex flex-wrap items-center gap-1.5">
                      {job.tags.slice(0, 5).map((t) => (
                        <span
                          key={t}
                          className="px-2.5 py-1 bg-[#f5ece7] text-[#1f1b18] text-xs font-semibold rounded-md"
                        >
                          {t}
                        </span>
                      ))}
                      {job.perks.slice(0, 2).map((p) => (
                        <span
                          key={p}
                          className="px-2.5 py-1 bg-[#ffdcc0] text-[#2d1600] text-xs font-semibold rounded-md flex items-center gap-1"
                        >
                          <span className="material-symbols-outlined text-[13px]">verified</span>
                          {p}
                        </span>
                      ))}
                    </div>

                    {/* Footer Action Bar */}
                    <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-2 border-t border-[#f5ece7]">
                      <div className="flex items-center gap-2 text-xs text-[#8d7168]">
                        <span className="flex items-center gap-1">
                          <span className="material-symbols-outlined text-[15px]">visibility</span>
                          {job.activeCandidates || 35} candidatos activos
                        </span>
                        <span>·</span>
                        <span>{job.postedTime}</span>
                      </div>

                      <div className="flex items-center gap-2">
                        <button
                          onClick={() => {
                            onSelectJob(job.id);
                            setScreen('job-detail');
                          }}
                          className="px-4 py-2 bg-[#efe6e1] hover:bg-[#eae1dc] text-[#1f1b18] text-xs font-bold rounded-lg transition-colors cursor-pointer"
                        >
                          Ver detalle
                        </button>

                        <button
                          onClick={(e) => handleFastApply(job.id, e)}
                          disabled={isApplied}
                          className={`flex items-center gap-1.5 px-4 py-2 text-xs font-bold rounded-lg transition-all shadow-xs cursor-pointer ${
                            isApplied
                              ? 'bg-emerald-600 text-white'
                              : 'bg-[#a83301] hover:bg-[#ca4a1c] text-white active:scale-95'
                          }`}
                        >
                          <span className="material-symbols-outlined text-[16px]">
                            {isApplied ? 'check_circle' : 'bolt'}
                          </span>
                          <span>{isApplied ? '¡Postulado!' : 'Postularme en 1-Clic'}</span>
                        </button>
                      </div>
                    </div>
                  </article>
                );
              })}
            </div>

            {/* Pagination Bar */}
            <nav className="flex items-center justify-between bg-white p-3.5 rounded-xl shadow-xs border border-[#e1bfb5]/40 mt-2">
              <button
                disabled={currentPage === 1}
                onClick={() => setCurrentPage((p) => Math.max(1, p - 1))}
                className="flex items-center gap-1 px-3 py-1.5 rounded-lg bg-[#f5ece7] text-[#1f1b18] hover:bg-[#eae1dc] text-xs font-bold transition-colors disabled:opacity-50 cursor-pointer"
              >
                <span className="material-symbols-outlined text-[16px]">chevron_left</span> Anterior
              </button>

              <div className="hidden sm:flex items-center gap-1.5">
                {[1, 2, 3].map((num) => (
                  <button
                    key={num}
                    onClick={() => setCurrentPage(num)}
                    className={`w-8 h-8 rounded-lg text-xs font-bold transition-all cursor-pointer ${
                      currentPage === num
                        ? 'bg-[#a83301] text-white shadow-xs'
                        : 'bg-[#f5ece7] hover:bg-[#eae1dc] text-[#1f1b18]'
                    }`}
                  >
                    {num}
                  </button>
                ))}
                <span className="px-1 text-xs text-[#8d7168]">…</span>
                <button
                  onClick={() => setCurrentPage(48)}
                  className="w-8 h-8 rounded-lg bg-[#f5ece7] hover:bg-[#eae1dc] text-[#1f1b18] text-xs font-bold transition-colors cursor-pointer"
                >
                  48
                </button>
              </div>

              <button
                onClick={() => setCurrentPage((p) => p + 1)}
                className="flex items-center gap-1 px-3 py-1.5 rounded-lg bg-[#f5ece7] text-[#1f1b18] hover:bg-[#eae1dc] text-xs font-bold transition-colors cursor-pointer"
              >
                Siguiente <span className="material-symbols-outlined text-[16px]">chevron_right</span>
              </button>
            </nav>

            {/* Smart Alert / Omnichannel Subscription Box */}
            <section className="bg-gradient-to-br from-[#efe6e1] via-[#f5ece7] to-[#efe6e1] p-6 rounded-2xl shadow-sm border border-[#e1bfb5]/40 mt-4 relative overflow-hidden">
              <div className="absolute -right-10 -bottom-10 w-44 h-44 rounded-full bg-[#a83301]/10 blur-2xl pointer-events-none"></div>

              <div className="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
                <div className="flex flex-col gap-1.5 max-w-xl">
                  <div className="flex items-center gap-1.5 text-[#a83301] text-xs font-bold uppercase tracking-wider">
                    <span className="material-symbols-outlined text-[18px]">notifications_active</span>
                    <span>Alerta Inteligente Personalizada</span>
                  </div>

                  <h3 className="font-headline text-xl text-[#1f1b18] font-bold">
                    ¿No encontraste el camello exacto hoy?
                  </h3>

                  <p className="text-xs text-[#59413a] leading-relaxed">
                    Guardamos tus parámetros (<span className="font-bold text-[#1f1b18]">Senior Developer, Remoto, &gt;$8M COP</span>) y te enviamos un ping al instante cuando una empresa verificada publique una vacante compatible. Cero spam.
                  </p>
                </div>

                <div className="flex flex-col gap-2 min-w-[260px]">
                  <div className="flex items-center bg-white px-3 py-1.5 rounded-xl border border-[#e1bfb5]/50 shadow-xs">
                    <span className="material-symbols-outlined text-[#8d7168] mr-2 text-[18px]">mark_email_read</span>
                    <input
                      type="email"
                      value={alertEmail}
                      onChange={(e) => setAlertEmail(e.target.value)}
                      placeholder="tucorreo@ejemplo.com"
                      className="w-full bg-transparent text-xs text-[#1f1b18] focus:outline-none placeholder:text-[#8d7168]"
                    />
                  </div>

                  <div className="flex gap-2">
                    <button
                      onClick={() => {
                        if (alertEmail) {
                          setAlertSuccess(true);
                          setTimeout(() => setAlertSuccess(false), 4000);
                        }
                      }}
                      className="flex-1 flex items-center justify-center gap-1.5 bg-[#a83301] hover:bg-[#ca4a1c] text-white py-2 px-3 rounded-lg text-xs font-bold transition-all shadow-xs cursor-pointer"
                    >
                      <span className="material-symbols-outlined text-[16px]">mail</span>
                      <span>Alerta Correo</span>
                    </button>

                    <button
                      onClick={() => alert('¡Alerta vinculada con WhatsApp! Te enviaremos un mensaje de confirmación.')}
                      className="flex items-center justify-center gap-1.5 bg-[#25D366] hover:bg-[#1EBE5D] text-white py-2 px-3 rounded-lg text-xs font-bold transition-all shadow-xs cursor-pointer"
                      title="Recibir por WhatsApp"
                    >
                      <span className="material-symbols-outlined text-[16px]">chat</span>
                      <span>WhatsApp</span>
                    </button>
                  </div>

                  {alertSuccess && (
                    <span className="text-[11px] text-emerald-700 font-bold text-center">
                      ✓ ¡Alerta creada! Te notificaremos de inmediato.
                    </span>
                  )}

                  <span className="text-[10px] text-[#8d7168] text-center">
                    Puedes pausar las alertas con 1 clic en cualquier momento.
                  </span>
                </div>
              </div>
            </section>

          </main>

        </div>
      </section>
    </div>
  );
};
