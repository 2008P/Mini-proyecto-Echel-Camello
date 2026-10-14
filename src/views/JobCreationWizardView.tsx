import React, { useState } from 'react';
import { AppScreen } from '../types';

interface JobCreationWizardViewProps {
  setScreen: (screen: AppScreen) => void;
  onJobCreated?: (newJob: any) => void;
}

export const JobCreationWizardView: React.FC<JobCreationWizardViewProps> = ({
  setScreen,
  onJobCreated,
}) => {
  const [currentStep, setCurrentStep] = useState<number>(2); // Default to Step 2 to showcase the core transparent salary step
  
  // Form state
  const [title, setTitle] = useState('Senior Frontend Developer (Next.js / TypeScript)');
  const [category, setCategory] = useState('Tecnología & Software');
  const [seniority, setSeniority] = useState('Senior');
  const [modality, setModality] = useState<'100% Remoto' | 'Híbrido' | 'Presencial'>('100% Remoto');
  const [currency, setCurrency] = useState<'USD' | 'COP'>('USD');
  const [salaryMin, setSalaryMin] = useState<number>(3500);
  const [salaryMax, setSalaryMax] = useState<number>(4800);
  const [contractType, setContractType] = useState('Contrato Contractor Internacional');
  const [urgency, setUrgency] = useState<'Normal' | 'Urgente' | 'Destacada'>('Urgente');
  const [selectedPerks, setSelectedPerks] = useState<string[]>([
    'Salario en Moneda Fuerte',
    'Seguro Médico Internacional',
    'MacBook Pro Provisto',
    '25 Días PTO Libres'
  ]);
  const [tags, setTags] = useState('Next.js 14, TypeScript, Tailwind CSS, GraphQL, Inglés C1');
  const [description, setDescription] = useState(
    'Buscamos un Senior Frontend Developer para liderar la arquitectura de nuestras aplicaciones de cara al usuario en Stark Cloud Corp...'
  );
  const [isPublished, setIsPublished] = useState(false);

  const togglePerk = (perk: string) => {
    if (selectedPerks.includes(perk)) {
      setSelectedPerks(selectedPerks.filter(p => p !== perk));
    } else {
      setSelectedPerks([...selectedPerks, perk]);
    }
  };

  const handlePublish = () => {
    setIsPublished(true);
    if (onJobCreated) {
      onJobCreated({
        id: 'job-new-' + Date.now(),
        title,
        company: 'Stark Cloud Corp',
        category,
        salaryUSD: currency === 'USD' ? `USD $${salaryMin} – $${salaryMax}` : undefined,
        salaryCOP: currency === 'COP' ? `$${(salaryMin / 1000000).toFixed(1)}M – $${(salaryMax / 1000000).toFixed(1)}M COP` : undefined,
        modality,
        contractType,
        experienceLevel: seniority,
        urgency,
        tags: tags.split(',').map(s => s.trim()),
        perks: selectedPerks,
        description,
        postedTime: 'Publicado hace unos momentos',
      });
    }
    setTimeout(() => {
      setScreen('recruiter-dashboard');
    }, 1500);
  };

  return (
    <div className="min-h-screen bg-[#fff8f6] py-8 text-[#1f1b16]">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Breadcrumb */}
        <nav className="flex items-center gap-2 text-sm text-[#737373] mb-6 font-body">
          <button onClick={() => setScreen('recruiter-dashboard')} className="hover:text-[#a83301]">
            Panel de Reclutamiento
          </button>
          <span>/</span>
          <span className="text-[#1f1b16] font-medium">Publicar un Camello</span>
        </nav>

        {/* Wizard Header */}
        <div className="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-[#e5e5e5] mb-8">
          <div className="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
              <span className="text-xs font-bold uppercase tracking-wider text-[#a83301] flex items-center gap-1">
                <span className="material-symbols-outlined text-sm">rocket_launch</span>
                Asistente de Contratación
              </span>
              <h1 className="text-2xl sm:text-3xl font-extrabold text-[#1f1b16] font-headline mt-1">
                Crear & Publicar una Nueva Oferta
              </h1>
            </div>

            <div className="flex items-center gap-2 text-xs font-semibold text-[#737373]">
              <span className="material-symbols-outlined text-[#16a34a] text-base">verified</span>
              Empresa Verificada: Stark Cloud Corp
            </div>
          </div>

          {/* Stepper Progress */}
          <div className="grid grid-cols-2 md:grid-cols-4 gap-3 pt-4 border-t border-[#f0f0f0]">
            {[
              { step: 1, label: 'Datos Básicos', icon: 'badge' },
              { step: 2, label: 'Remuneración & Modalidad', icon: 'payments' },
              { step: 3, label: 'Requisitos & Stack', icon: 'code' },
              { step: 4, label: 'Revisión & Copiloto IA', icon: 'auto_awesome' },
            ].map(s => (
              <button
                key={s.step}
                onClick={() => setCurrentStep(s.step)}
                className={`p-3 rounded-2xl border text-left transition-all flex items-center gap-3 ${
                  currentStep === s.step
                    ? 'border-[#a83301] bg-[#fff4f0] shadow-sm'
                    : currentStep > s.step
                    ? 'border-[#bbf7d0] bg-[#f0fdf4] text-[#166534]'
                    : 'border-[#e5e5e5] bg-white opacity-75 hover:opacity-100'
                }`}
              >
                <div className={`w-8 h-8 rounded-xl flex items-center justify-center font-bold text-xs shrink-0 ${
                  currentStep === s.step
                    ? 'bg-[#a83301] text-white'
                    : currentStep > s.step
                    ? 'bg-[#16a34a] text-white'
                    : 'bg-neutral-100 text-[#737373]'
                }`}>
                  {currentStep > s.step ? (
                    <span className="material-symbols-outlined text-sm">check</span>
                  ) : (
                    s.step
                  )}
                </div>
                <div className="min-w-0">
                  <p className="text-[11px] text-[#737373] uppercase font-bold">Paso {s.step}</p>
                  <p className="text-xs font-bold truncate text-[#1f1b16]">{s.label}</p>
                </div>
              </button>
            ))}
          </div>
        </div>

        {/* Wizard Form and Sidebar */}
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-12">
          {/* Main Form (8 cols) */}
          <div className="lg:col-span-8 space-y-6">
            {/* Step 1: Basic Information */}
            {currentStep === 1 && (
              <div className="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-[#e5e5e5] space-y-5 animate-fade-in">
                <h2 className="text-xl font-bold font-headline text-[#1f1b16] mb-2">
                  1. Datos Básicos del Puesto
                </h2>

                <div>
                  <label className="block text-xs font-bold uppercase text-[#525252] mb-1 font-headline">
                    Título de la Vacante *
                  </label>
                  <input
                    type="text"
                    value={title}
                    onChange={(e) => setTitle(e.target.value)}
                    placeholder="Ej. Senior Frontend Developer (Next.js / TypeScript)"
                    className="w-full p-3 rounded-xl border border-[#d4d4d4] text-sm text-[#1f1b16] focus:ring-2 focus:ring-[#a83301] focus:outline-none"
                  />
                  <p className="text-[11px] text-[#737373] mt-1">
                    Sé claro y específico. Evita palabras ambiguas como &ldquo;Ninja&rdquo; o &ldquo;Rockstar&rdquo;.
                  </p>
                </div>

                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label className="block text-xs font-bold uppercase text-[#525252] mb-1 font-headline">
                      Área o Categoría *
                    </label>
                    <select
                      value={category}
                      onChange={(e) => setCategory(e.target.value)}
                      className="w-full p-3 rounded-xl border border-[#d4d4d4] text-sm text-[#1f1b16] bg-white"
                    >
                      <option>Tecnología & Software</option>
                      <option>Ventas & Comercial</option>
                      <option>Operaciones & Logística</option>
                      <option>Marketing & Diseño</option>
                      <option>Atención al Cliente & Bilingüe</option>
                      <option>Finanzas & Contabilidad</option>
                    </select>
                  </div>

                  <div>
                    <label className="block text-xs font-bold uppercase text-[#525252] mb-1 font-headline">
                      Seniority Requerido *
                    </label>
                    <select
                      value={seniority}
                      onChange={(e) => setSeniority(e.target.value)}
                      className="w-full p-3 rounded-xl border border-[#d4d4d4] text-sm text-[#1f1b16] bg-white"
                    >
                      <option>Junior (0 - 2 años)</option>
                      <option>Semi-Senior (2 - 5 años)</option>
                      <option>Senior (5+ años)</option>
                      <option>Lead & Staff (8+ años)</option>
                    </select>
                  </div>
                </div>

                <div>
                  <label className="block text-xs font-bold uppercase text-[#525252] mb-1 font-headline">
                    Nivel de Urgencia
                  </label>
                  <div className="grid grid-cols-3 gap-3">
                    {['Normal', 'Destacada', 'Urgente'].map((urg) => (
                      <button
                        key={urg}
                        type="button"
                        onClick={() => setUrgency(urg as any)}
                        className={`p-3 rounded-xl border text-xs font-bold transition-all ${
                          urgency === urg
                            ? 'bg-[#fff4f0] border-[#a83301] text-[#a83301] shadow-sm'
                            : 'bg-white border-[#e5e5e5] text-[#525252]'
                        }`}
                      >
                        {urg === 'Urgente' && '🔥 '}
                        {urg === 'Destacada' && '⭐ '}
                        {urg}
                      </button>
                    ))}
                  </div>
                </div>
              </div>
            )}

            {/* Step 2: Compensation & Modality (The main feature screen from Image 4) */}
            {currentStep === 2 && (
              <div className="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-[#e5e5e5] space-y-6 animate-fade-in">
                {/* Mandate Notice Banner */}
                <div className="p-4 rounded-2xl bg-[#fff4f0] border border-[#f5b8a5] flex items-start gap-3">
                  <span className="material-symbols-outlined text-[#a83301] text-2xl shrink-0">
                    policy
                  </span>
                  <div>
                    <h3 className="font-bold text-sm text-[#a83301] font-headline">
                      Mandato de Transparencia Salarial Échele Camello
                    </h3>
                    <p className="text-xs text-[#525252] mt-0.5 font-body leading-relaxed">
                      En Échele Camello <strong>no permitimos</strong> frases como &ldquo;Salario a convenir&rdquo; o rangos vacíos. Las ofertas transparentes reciben 3.4 veces más postulantes calificados y generan mayor confianza en el talento colombiano.
                    </p>
                  </div>
                </div>

                {/* Currency Switcher */}
                <div>
                  <label className="block text-xs font-bold uppercase text-[#525252] mb-2 font-headline">
                    Moneda de Pago *
                  </label>
                  <div className="grid grid-cols-2 gap-3">
                    <button
                      type="button"
                      onClick={() => setCurrency('USD')}
                      className={`p-4 rounded-2xl border text-left flex items-center justify-between transition-all ${
                        currency === 'USD'
                          ? 'border-[#a83301] bg-[#fff4f0] shadow-sm'
                          : 'border-[#e5e5e5] bg-white'
                      }`}
                    >
                      <div>
                        <p className="text-sm font-bold text-[#1f1b16]">🇺🇸 Dólares (USD)</p>
                        <p className="text-xs text-[#737373]">Ideal para esquemas Contractor LATAM</p>
                      </div>
                      <span className={`w-5 h-5 rounded-full border flex items-center justify-center ${
                        currency === 'USD' ? 'border-[#a83301] bg-[#a83301] text-white' : 'border-[#d4d4d4]'
                      }`}>
                        {currency === 'USD' && <span className="material-symbols-outlined text-xs">check</span>}
                      </span>
                    </button>

                    <button
                      type="button"
                      onClick={() => setCurrency('COP')}
                      className={`p-4 rounded-2xl border text-left flex items-center justify-between transition-all ${
                        currency === 'COP'
                          ? 'border-[#a83301] bg-[#fff4f0] shadow-sm'
                          : 'border-[#e5e5e5] bg-white'
                      }`}
                    >
                      <div>
                        <p className="text-sm font-bold text-[#1f1b16]">🇨🇴 Pesos Colombianos (COP)</p>
                        <p className="text-xs text-[#737373]">Para nómina nacional y CST</p>
                      </div>
                      <span className={`w-5 h-5 rounded-full border flex items-center justify-center ${
                        currency === 'COP' ? 'border-[#a83301] bg-[#a83301] text-white' : 'border-[#d4d4d4]'
                      }`}>
                        {currency === 'COP' && <span className="material-symbols-outlined text-xs">check</span>}
                      </span>
                    </button>
                  </div>
                </div>

                {/* Salary Range */}
                <div>
                  <label className="block text-xs font-bold uppercase text-[#525252] mb-2 font-headline">
                    Rango Salarial Mensual Ofrecido ({currency}) *
                  </label>
                  <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                      <span className="text-xs text-[#737373] mb-1 block">Mínimo garantizado:</span>
                      <div className="relative">
                        <span className="absolute left-3 top-1/2 -translate-y-1/2 text-sm font-bold text-[#737373]">
                          {currency === 'USD' ? '$' : 'COP$'}
                        </span>
                        <input
                          type="number"
                          value={salaryMin}
                          onChange={(e) => setSalaryMin(Number(e.target.value))}
                          className="w-full pl-10 pr-3 py-3 rounded-xl border border-[#d4d4d4] font-bold text-sm text-[#1f1b16]"
                        />
                      </div>
                    </div>

                    <div>
                      <span className="text-xs text-[#737373] mb-1 block">Máximo presupuestado:</span>
                      <div className="relative">
                        <span className="absolute left-3 top-1/2 -translate-y-1/2 text-sm font-bold text-[#737373]">
                          {currency === 'USD' ? '$' : 'COP$'}
                        </span>
                        <input
                          type="number"
                          value={salaryMax}
                          onChange={(e) => setSalaryMax(Number(e.target.value))}
                          className="w-full pl-10 pr-3 py-3 rounded-xl border border-[#d4d4d4] font-bold text-sm text-[#1f1b16]"
                        />
                      </div>
                    </div>
                  </div>
                  <p className="text-[11px] text-[#737373] mt-1.5 font-body">
                    Vista previa para el candidato: <strong>{currency === 'USD' ? `USD $${salaryMin} – $${salaryMax} / mes` : `$${salaryMin.toLocaleString()} – $${salaryMax.toLocaleString()} COP / mes`}</strong>
                  </p>
                </div>

                {/* Modality & Contract Type */}
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label className="block text-xs font-bold uppercase text-[#525252] mb-1 font-headline">
                      Modalidad de Trabajo *
                    </label>
                    <select
                      value={modality}
                      onChange={(e) => setModality(e.target.value as any)}
                      className="w-full p-3 rounded-xl border border-[#d4d4d4] text-sm text-[#1f1b16] bg-white font-medium"
                    >
                      <option value="100% Remoto">100% Remoto (Cualquier lugar de Colombia/LATAM)</option>
                      <option value="Híbrido">Híbrido (Sede en Medellín o Bogotá)</option>
                      <option value="Presencial">Presencial en Oficina</option>
                    </select>
                  </div>

                  <div>
                    <label className="block text-xs font-bold uppercase text-[#525252] mb-1 font-headline">
                      Tipo de Contrato *
                    </label>
                    <select
                      value={contractType}
                      onChange={(e) => setContractType(e.target.value)}
                      className="w-full p-3 rounded-xl border border-[#d4d4d4] text-sm text-[#1f1b16] bg-white font-medium"
                    >
                      <option>Contrato Contractor Internacional</option>
                      <option>Término Indefinido (CST Colombia)</option>
                      <option>Término Fijo con Renovación</option>
                      <option>Prestación de Servicios</option>
                    </select>
                  </div>
                </div>

                {/* Guaranteed Perks Selection */}
                <div>
                  <label className="block text-xs font-bold uppercase text-[#525252] mb-2 font-headline">
                    Beneficios & Bienestar Garantizados
                  </label>
                  <div className="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                    {[
                      'Salario en Moneda Fuerte',
                      'Seguro Médico Internacional',
                      'MacBook Pro Provisto',
                      '25 Días PTO Libres',
                      'Bono Anual por Desempeño',
                      'Fondo de Capacitación ($1,500 USD)',
                      'Coworking Libre (WeWork)',
                      'Clases de Inglés Bilingüe',
                      'Medicina Prepagada 100%'
                    ].map(perk => {
                      const isSelected = selectedPerks.includes(perk);
                      return (
                        <button
                          key={perk}
                          type="button"
                          onClick={() => togglePerk(perk)}
                          className={`p-2.5 rounded-xl border text-xs font-semibold text-left flex items-center justify-between transition-all ${
                            isSelected
                              ? 'border-[#a83301] bg-[#fff4f0] text-[#a83301]'
                              : 'border-[#e5e5e5] bg-white text-[#525252] hover:bg-neutral-50'
                          }`}
                        >
                          <span className="truncate mr-1">{perk}</span>
                          <span className="material-symbols-outlined text-sm shrink-0">
                            {isSelected ? 'check_circle' : 'add_circle'}
                          </span>
                        </button>
                      );
                    })}
                  </div>
                </div>
              </div>
            )}

            {/* Step 3: Requirements & Tech Stack */}
            {currentStep === 3 && (
              <div className="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-[#e5e5e5] space-y-5 animate-fade-in">
                <h2 className="text-xl font-bold font-headline text-[#1f1b16] mb-2">
                  3. Requisitos & Stack Tecnológico
                </h2>

                <div>
                  <label className="block text-xs font-bold uppercase text-[#525252] mb-1 font-headline">
                    Tecnologías y Palabras Clave (Separadas por comas) *
                  </label>
                  <input
                    type="text"
                    value={tags}
                    onChange={(e) => setTags(e.target.value)}
                    placeholder="Next.js 14, TypeScript, Tailwind CSS, GraphQL, Inglés C1"
                    className="w-full p-3 rounded-xl border border-[#d4d4d4] text-sm text-[#1f1b16]"
                  />
                </div>

                <div>
                  <label className="block text-xs font-bold uppercase text-[#525252] mb-1 font-headline">
                    Descripción del Camello & Responsabilidades *
                  </label>
                  <textarea
                    rows={6}
                    value={description}
                    onChange={(e) => setDescription(e.target.value)}
                    className="w-full p-3 rounded-xl border border-[#d4d4d4] text-sm text-[#1f1b16] font-body"
                  />
                </div>
              </div>
            )}

            {/* Step 4: Review & Copilot Validation */}
            {currentStep === 4 && (
              <div className="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-[#e5e5e5] space-y-6 animate-fade-in">
                <div className="flex items-center justify-between pb-4 border-b border-[#f0f0f0]">
                  <div>
                    <h2 className="text-xl font-bold font-headline text-[#1f1b16]">
                      4. Revisión Final & Publicación Inmediata
                    </h2>
                    <p className="text-xs text-[#737373]">
                      Verifica la ficha de tu vacante antes del lanzamiento a más de 120.000 camelladores.
                    </p>
                  </div>
                  <span className="px-3 py-1 rounded-full text-xs font-bold bg-[#f0fdf4] text-[#166534] border border-[#bbf7d0]">
                    Puntuación de Calidad: 98/100
                  </span>
                </div>

                <div className="p-6 rounded-2xl bg-[#fff8f6] border border-[#f5b8a5] space-y-4">
                  <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                      <span className="text-xs font-bold text-[#a83301] uppercase tracking-wider">{category}</span>
                      <h3 className="text-xl font-bold text-[#1f1b16] font-headline">{title}</h3>
                    </div>
                    <div className="text-xl font-black text-[#a83301] font-headline">
                      {currency === 'USD' ? `USD $${salaryMin} – $${salaryMax} / mes` : `$${salaryMin.toLocaleString()} – $${salaryMax.toLocaleString()} COP`}
                    </div>
                  </div>

                  <div className="flex flex-wrap gap-3 text-xs text-[#525252]">
                    <span>📍 {modality}</span>
                    <span>•</span>
                    <span>📑 {contractType}</span>
                    <span>•</span>
                    <span>💼 {seniority}</span>
                  </div>

                  <div className="flex flex-wrap gap-1.5 pt-2 border-t border-[#f5b8a5]">
                    {selectedPerks.map((p, idx) => (
                      <span key={idx} className="px-2 py-0.5 rounded bg-white text-[#a83301] text-xs font-semibold border border-[#ffdbd0]">
                        ✓ {p}
                      </span>
                    ))}
                  </div>
                </div>

                {isPublished ? (
                  <div className="p-6 rounded-2xl bg-[#f0fdf4] border border-[#bbf7d0] text-center">
                    <span className="material-symbols-outlined text-[#16a34a] text-4xl mb-2">check_circle</span>
                    <h4 className="text-lg font-bold text-[#166534] font-headline">¡Camello Publicado con Éxito!</h4>
                    <p className="text-xs text-[#166534] mt-1">Redireccionando a tu panel de reclutamiento...</p>
                  </div>
                ) : (
                  <div className="p-4 rounded-xl bg-neutral-50 border border-neutral-200 text-xs text-[#525252] flex items-center gap-3">
                    <span className="material-symbols-outlined text-[#16a34a] text-xl">security</span>
                    <span>
                      Al publicar, aceptas el cumplimiento de los salarios anunciados y las políticas de no discriminación y transparencia laboral de Échele Camello.
                    </span>
                  </div>
                )}
              </div>
            )}

            {/* Stepper Navigation Buttons */}
            <div className="flex items-center justify-between pt-4">
              <button
                type="button"
                onClick={() => setCurrentStep(Math.max(1, currentStep - 1))}
                disabled={currentStep === 1}
                className="px-5 py-2.5 rounded-xl border border-[#d4d4d4] text-xs font-bold font-headline text-[#525252] disabled:opacity-40 disabled:cursor-not-allowed hover:bg-neutral-50 transition-colors"
              >
                Atrás
              </button>

              <div className="flex items-center gap-3">
                <button
                  type="button"
                  onClick={() => alert('Borrador guardado en tu panel')}
                  className="px-4 py-2.5 rounded-xl border border-[#e5e5e5] text-xs font-bold text-[#737373] hover:text-[#1f1b16]"
                >
                  Guardar Borrador
                </button>

                {currentStep < 4 ? (
                  <button
                    type="button"
                    onClick={() => setCurrentStep(currentStep + 1)}
                    className="px-6 py-2.5 rounded-xl bg-[#a83301] hover:bg-[#ca4a1c] text-white text-xs font-bold font-headline shadow-md transition-all flex items-center gap-1.5"
                  >
                    <span>Siguiente Paso</span>
                    <span className="material-symbols-outlined text-sm">arrow_forward</span>
                  </button>
                ) : (
                  <button
                    type="button"
                    onClick={handlePublish}
                    disabled={isPublished}
                    className="px-7 py-3 rounded-xl bg-[#16a34a] hover:bg-[#15803d] text-white text-xs font-bold font-headline shadow-lg transition-all flex items-center gap-1.5"
                  >
                    <span className="material-symbols-outlined text-sm">rocket_launch</span>
                    <span>Publicar Camello Ahora</span>
                  </button>
                )}
              </div>
            </div>
          </div>

          {/* AI Copilot & Insights Sidebar (4 cols) */}
          <div className="lg:col-span-4 space-y-6">
            <div className="bg-gradient-to-br from-[#1f1b16] to-[#2d1b14] text-white rounded-3xl p-6 shadow-md relative overflow-hidden">
              <div className="flex items-center gap-2 mb-3">
                <span className="material-symbols-outlined text-[#f5b8a5] text-xl">smart_toy</span>
                <span className="text-xs font-bold uppercase tracking-wider text-[#f5b8a5]">
                  Copiloto Reclutador IA
                </span>
              </div>

              <h3 className="text-base font-bold font-headline text-white mb-2">
                Evaluación en Tiempo Real
              </h3>
              <p className="text-xs text-white/80 font-body leading-relaxed mb-4">
                El rango salarial ingresado (<strong>{currency === 'USD' ? `$${salaryMin} – $${salaryMax} USD` : `$${salaryMin.toLocaleString()} COP`}</strong>) se encuentra en el <strong>percentil 78</strong> de remuneración para la plaza colombiana.
              </p>

              <div className="space-y-3 pt-3 border-t border-white/20 text-xs text-white/90">
                <div className="flex items-start gap-2">
                  <span className="material-symbols-outlined text-[#86efac] text-base shrink-0">check</span>
                  <span>100% de cumplimiento con el estándar de transparencia.</span>
                </div>
                <div className="flex items-start gap-2">
                  <span className="material-symbols-outlined text-[#86efac] text-base shrink-0">check</span>
                  <span>Modalidad {modality} atrae candidatos de Medellín, Bogotá y Cali simultáneamente.</span>
                </div>
              </div>
            </div>

            <div className="bg-white rounded-3xl p-6 shadow-sm border border-[#e5e5e5]">
              <h3 className="font-bold font-headline text-sm text-[#1f1b16] mb-3">
                ¿Por qué publicar en Échele Camello?
              </h3>
              <ul className="space-y-3 text-xs text-[#525252] font-body">
                <li className="flex items-start gap-2">
                  <span className="material-symbols-outlined text-[#a83301] text-base shrink-0">bolt</span>
                  <span><strong>1 Clic Apply:</strong> Candidatos pre-verificados con perfiles técnicos reales.</span>
                </li>
                <li className="flex items-start gap-2">
                  <span className="material-symbols-outlined text-[#a83301] text-base shrink-0">sentiment_satisfied</span>
                  <span><strong>Cero ofertas fantasma:</strong> Comunidad comprometida con empleos de calidad.</span>
                </li>
                <li className="flex items-start gap-2">
                  <span className="material-symbols-outlined text-[#a83301] text-base shrink-0">group</span>
                  <span><strong>+120,000 profesionales:</strong> En tech, finanzas, diseño, ventas y operaciones.</span>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};
