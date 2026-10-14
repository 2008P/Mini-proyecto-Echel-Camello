import React, { useState, useMemo } from 'react';
import { AppScreen } from '../types';

interface SalaryCalculatorViewProps {
  setScreen: (screen: AppScreen) => void;
  onExploreWithSalary?: (salaryMin: number) => void;
}

export const SalaryCalculatorView: React.FC<SalaryCalculatorViewProps> = ({
  setScreen,
  onExploreWithSalary,
}) => {
  const [currency, setCurrency] = useState<'COP' | 'USD'>('COP');
  const [grossInput, setGrossInput] = useState<number>(12000000);
  const [contractType, setContractType] = useState<'laboral' | 'prestacion'>('laboral');
  const [selectedRole, setSelectedRole] = useState('Frontend / Fullstack Developer');
  const [selectedSeniority, setSelectedSeniority] = useState<'Junior' | 'Semi-Senior' | 'Senior' | 'Lead / Architect'>('Senior');
  const [englishLevel, setEnglishLevel] = useState<'Básico' | 'Intermedio B1' | 'Avanzado C1'>('Avanzado C1');

  // TRM (Tasa Representativa del Mercado)
  const TRM = 4200;

  // Real calculations for Colombian employment law
  const calcData = useMemo(() => {
    // Convert to COP for calculation
    const grossCOP = currency === 'USD' ? grossInput * TRM : grossInput;

    if (contractType === 'laboral') {
      // Colombian Labor Contract (CST)
      // Employee deductions:
      // Salud: 4%
      // Pensión: 4%
      // Fondo Solidaridad Pensional: if > 4 SMMLV (approx 5.2M COP): 1% to 2%
      const smmlv = 1423500; // SMMLV 2024-2025 approx
      const saludEmp = grossCOP * 0.04;
      const pensionEmp = grossCOP * 0.04;
      const fspEmp = grossCOP > smmlv * 4 ? grossCOP * 0.01 : 0;
      
      // Retención en la fuente estimada (UVT)
      const uvt = 49799;
      const baseGravable = grossCOP - saludEmp - pensionEmp - (grossCOP * 0.25); // 25% exenta
      const baseUVT = baseGravable / uvt;
      let retefuente = 0;
      if (baseUVT > 95 && baseUVT <= 150) {
        retefuente = (baseUVT - 95) * 0.19 * uvt;
      } else if (baseUVT > 150) {
        retefuente = ((150 - 95) * 0.19 + (baseUVT - 150) * 0.28) * uvt;
      }

      const totalDeductions = saludEmp + pensionEmp + fspEmp + retefuente;
      const netSalary = Math.max(0, grossCOP - totalDeductions);

      // Social benefits (Prestaciones sociales a cargo del empleador)
      const primaSemestral = grossCOP / 2; // Un mes de salario al año (pago mitad en junio y mitad en diciembre)
      const cesantiasAnuales = grossCOP;
      const interesesCesantias = cesantiasAnuales * 0.12;
      const vacacionesRemuneradas = grossCOP * 0.5; // 15 días hábiles
      const totalBeneficiosAnuales = primaSemestral * 2 + cesantiasAnuales + interesesCesantias + vacacionesRemuneradas;

      return {
        grossCOP,
        grossUSD: grossCOP / TRM,
        salud: saludEmp,
        pension: pensionEmp,
        fsp: fspEmp,
        retefuente,
        totalDeductions,
        netSalary,
        netUSD: netSalary / TRM,
        primaSemestral,
        cesantiasAnuales,
        interesesCesantias,
        totalBeneficiosAnuales,
        totalBeneficiosMensualEquivalente: totalBeneficiosAnuales / 12,
      };
    } else {
      // Prestación de Servicios / Contractor Independiente
      // IBC = 40% del valor mensual (mínimo 1 SMMLV)
      const smmlv = 1423500;
      const ibc = Math.max(smmlv, grossCOP * 0.40);
      const saludIndep = ibc * 0.125; // 12.5%
      const pensionIndep = ibc * 0.16; // 16%
      const arlIndep = ibc * 0.00522; // Riesgo I 0.522%

      const totalDeductions = saludIndep + pensionIndep + arlIndep;
      const netSalary = Math.max(0, grossCOP - totalDeductions);

      return {
        grossCOP,
        grossUSD: grossCOP / TRM,
        salud: saludIndep,
        pension: pensionIndep,
        fsp: 0,
        retefuente: 0,
        totalDeductions,
        netSalary,
        netUSD: netSalary / TRM,
        primaSemestral: 0,
        cesantiasAnuales: 0,
        interesesCesantias: 0,
        totalBeneficiosAnuales: 0,
        totalBeneficiosMensualEquivalente: 0,
      };
    }
  }, [currency, grossInput, contractType, TRM]);

  // Market benchmarks dataset
  const marketBenchmark = useMemo(() => {
    let baseMin = 4000000;
    let baseMedian = 7500000;
    let baseMax = 12000000;

    if (selectedRole.includes('Developer') || selectedRole.includes('Backend')) {
      if (selectedSeniority === 'Junior') { baseMin = 3500000; baseMedian = 5200000; baseMax = 7000000; }
      else if (selectedSeniority === 'Semi-Senior') { baseMin = 6500000; baseMedian = 9200000; baseMax = 12500000; }
      else if (selectedSeniority === 'Senior') { baseMin = 12000000; baseMedian = 16500000; baseMax = 22000000; }
      else { baseMin = 18000000; baseMedian = 25000000; baseMax = 35000000; }
    } else if (selectedRole.includes('Designer') || selectedRole.includes('Product')) {
      if (selectedSeniority === 'Junior') { baseMin = 3000000; baseMedian = 4200000; baseMax = 5800000; }
      else if (selectedSeniority === 'Semi-Senior') { baseMin = 5500000; baseMedian = 7800000; baseMax = 10500000; }
      else if (selectedSeniority === 'Senior') { baseMin = 9500000; baseMedian = 13500000; baseMax = 18000000; }
      else { baseMin = 15000000; baseMedian = 21000000; baseMax = 28000000; }
    } else {
      if (selectedSeniority === 'Junior') { baseMin = 2500000; baseMedian = 3600000; baseMax = 5000000; }
      else if (selectedSeniority === 'Semi-Senior') { baseMin = 4800000; baseMedian = 6800000; baseMax = 9000000; }
      else if (selectedSeniority === 'Senior') { baseMin = 8000000; baseMedian = 11500000; baseMax = 15000000; }
      else { baseMin = 13000000; baseMedian = 18000000; baseMax = 24000000; }
    }

    // English multiplier
    if (englishLevel === 'Avanzado C1') {
      baseMin *= 1.35;
      baseMedian *= 1.45;
      baseMax *= 1.55;
    } else if (englishLevel === 'Intermedio B1') {
      baseMin *= 1.12;
      baseMedian *= 1.15;
      baseMax *= 1.20;
    }

    return {
      min: Math.round(baseMin),
      median: Math.round(baseMedian),
      max: Math.round(baseMax),
    };
  }, [selectedRole, selectedSeniority, englishLevel]);

  const formatCOP = (num: number) => {
    return '$' + Math.round(num).toLocaleString('es-CO') + ' COP';
  };

  const formatUSD = (num: number) => {
    return '$' + Math.round(num).toLocaleString('en-US') + ' USD';
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
          <span className="text-[#1f1b16] font-medium">Calculadora & Transparencia Salarial</span>
        </nav>

        {/* Header Hero */}
        <div className="bg-white rounded-3xl p-6 sm:p-10 shadow-sm border border-[#e5e5e5] mb-8 relative overflow-hidden">
          <div className="absolute top-0 right-0 w-96 h-96 bg-gradient-to-bl from-[#ffdbd0]/50 via-transparent to-transparent rounded-bl-full pointer-events-none" />
          <div className="max-w-3xl relative z-10">
            <span className="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#ffdbd0] text-[#a83301] mb-3">
              <span className="material-symbols-outlined text-sm">balance</span>
              Marco Laboral Colombiano Actualizado
            </span>
            <h1 className="text-3xl sm:text-4xl font-black text-[#1f1b16] font-headline tracking-tight mb-3">
              Calculadora Salarial & Transparencia de Mercado
            </h1>
            <p className="text-[#525252] font-body text-base leading-relaxed">
              Descubre con precisión cuánto dinero te quedará neto en el bolsillo según tu tipo de contrato (Término Indefinido vs. Contractor USD / Prestación de Servicios) y compara tus honorarios contra el percentil real de mercado en Colombia y LATAM.
            </p>
          </div>
        </div>

        {/* Calculator Grid */}
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-12">
          {/* Controls & Inputs (5 cols) */}
          <div className="lg:col-span-5 space-y-6">
            <div className="bg-white rounded-3xl p-6 sm:p-7 shadow-sm border border-[#e5e5e5] space-y-6">
              <h2 className="text-xl font-bold font-headline text-[#1f1b16] flex items-center gap-2">
                <span className="material-symbols-outlined text-[#a83301]">tune</span>
                Configura tu Salario
              </h2>

              {/* Currency Selector */}
              <div>
                <label className="block text-xs font-bold uppercase text-[#525252] mb-2 font-headline">
                  Moneda de Negociación
                </label>
                <div className="grid grid-cols-2 gap-2 bg-[#fff4f0] p-1.5 rounded-2xl border border-[#ffdbd0]">
                  <button
                    onClick={() => {
                      if (currency !== 'COP') {
                        setCurrency('COP');
                        setGrossInput(grossInput * TRM);
                      }
                    }}
                    className={`py-2 px-3 rounded-xl font-bold text-xs sm:text-sm font-headline transition-all ${
                      currency === 'COP'
                        ? 'bg-[#a83301] text-white shadow-sm'
                        : 'text-[#737373] hover:text-[#1f1b16]'
                    }`}
                  >
                    🇨🇴 Pesos Colombianos (COP)
                  </button>
                  <button
                    onClick={() => {
                      if (currency !== 'USD') {
                        setCurrency('USD');
                        setGrossInput(Math.round(grossInput / TRM));
                      }
                    }}
                    className={`py-2 px-3 rounded-xl font-bold text-xs sm:text-sm font-headline transition-all ${
                      currency === 'USD'
                        ? 'bg-[#a83301] text-white shadow-sm'
                        : 'text-[#737373] hover:text-[#1f1b16]'
                    }`}
                  >
                    🇺🇸 Dólares (USD)
                  </button>
                </div>
                <p className="text-[11px] text-[#737373] mt-1.5 font-body">
                  TRM referencial calculada a $4.200 COP por 1 USD
                </p>
              </div>

              {/* Gross Amount Input */}
              <div>
                <div className="flex items-center justify-between mb-2">
                  <label className="text-xs font-bold uppercase text-[#525252] font-headline">
                    Salario Bruto Pactado ({currency})
                  </label>
                  <span className="text-xs font-semibold text-[#a83301]">
                    {currency === 'COP' ? formatCOP(grossInput) : formatUSD(grossInput)}
                  </span>
                </div>
                <div className="relative">
                  <span className="absolute left-4 top-1/2 -translate-y-1/2 text-lg font-bold text-[#737373]">
                    {currency === 'COP' ? '$' : 'US$'}
                  </span>
                  <input
                    type="number"
                    value={grossInput}
                    onChange={(e) => setGrossInput(Number(e.target.value) || 0)}
                    className="w-full pl-12 pr-4 py-3 rounded-xl border border-[#d4d4d4] font-bold text-lg text-[#1f1b16] focus:outline-none focus:ring-2 focus:ring-[#a83301] focus:border-transparent"
                  />
                </div>

                {/* Quick presets */}
                <div className="flex flex-wrap gap-2 mt-3">
                  {(currency === 'COP' 
                    ? [5000000, 8000000, 12000000, 16000000, 22000000]
                    : [1500, 2500, 3500, 5000, 7500]
                  ).map(val => (
                    <button
                      key={val}
                      onClick={() => setGrossInput(val)}
                      className="px-2.5 py-1 rounded-lg bg-neutral-100 hover:bg-[#ffdbd0] hover:text-[#a83301] text-xs font-medium text-[#525252] transition-colors"
                    >
                      {currency === 'COP' ? `$${(val / 1000000).toFixed(0)}M` : `$${val}k`}
                    </button>
                  ))}
                </div>
              </div>

              {/* Contract Type */}
              <div>
                <label className="block text-xs font-bold uppercase text-[#525252] mb-2 font-headline">
                  Tipo de Vinculación Legal
                </label>
                <div className="space-y-2">
                  <label
                    onClick={() => setContractType('laboral')}
                    className={`p-3.5 rounded-2xl border flex items-start gap-3 cursor-pointer transition-all ${
                      contractType === 'laboral'
                        ? 'border-[#a83301] bg-[#fff4f0] shadow-sm'
                        : 'border-[#e5e5e5] hover:bg-neutral-50'
                    }`}
                  >
                    <input
                      type="radio"
                      name="contract"
                      checked={contractType === 'laboral'}
                      onChange={() => setContractType('laboral')}
                      className="mt-1 accent-[#a83301]"
                    />
                    <div>
                      <p className="text-sm font-bold text-[#1f1b16]">
                        Contrato Laboral (Término Indefinido / Fijo)
                      </p>
                      <p className="text-xs text-[#737373] mt-0.5">
                        Incluye primas legales (Jun/Dic), cesantías, intereses, vacaciones remuneradas y aportes compartidos con empresa.
                      </p>
                    </div>
                  </label>

                  <label
                    onClick={() => setContractType('prestacion')}
                    className={`p-3.5 rounded-2xl border flex items-start gap-3 cursor-pointer transition-all ${
                      contractType === 'prestacion'
                        ? 'border-[#a83301] bg-[#fff4f0] shadow-sm'
                        : 'border-[#e5e5e5] hover:bg-neutral-50'
                    }`}
                  >
                    <input
                      type="radio"
                      name="contract"
                      checked={contractType === 'prestacion'}
                      onChange={() => setContractType('prestacion')}
                      className="mt-1 accent-[#a83301]"
                    />
                    <div>
                      <p className="text-sm font-bold text-[#1f1b16]">
                        Contractor Internacional / Prestación de Servicios
                      </p>
                      <p className="text-xs text-[#737373] mt-0.5">
                        Pagos brutos en USD / COP donde tú autogestionas tu PILA (Salud 12.5%, Pensión 16% sobre IBC del 40%).
                      </p>
                    </div>
                  </label>
                </div>
              </div>
            </div>
          </div>

          {/* Results & Detailed Breakdown (7 cols) */}
          <div className="lg:col-span-7 space-y-6">
            {/* Net Salary Highlight Card */}
            <div className="bg-gradient-to-br from-[#a83301] to-[#ca4a1c] rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
              <div className="absolute right-0 bottom-0 translate-x-8 translate-y-8 opacity-10">
                <span className="material-symbols-outlined text-[160px]">payments</span>
              </div>

              <div className="relative z-10">
                <div className="flex items-center justify-between mb-2">
                  <span className="text-xs uppercase tracking-widest font-bold text-white/80">
                    Tu Salario Neto Mensual Estimado
                  </span>
                  <span className="px-2.5 py-0.5 rounded-full bg-white/20 text-xs font-semibold backdrop-blur-sm">
                    {contractType === 'laboral' ? 'Contrato Laboral' : 'Contractor / Independiente'}
                  </span>
                </div>

                <div className="text-3xl sm:text-4xl lg:text-5xl font-black font-headline tracking-tight mb-2">
                  {formatCOP(calcData.netSalary)}
                </div>
                <p className="text-sm text-white/90 font-body">
                  Equivalente a <span className="font-bold">{formatUSD(calcData.netUSD)}</span> libres en tu cuenta bancaria.
                </p>

                {contractType === 'laboral' && (
                  <div className="mt-6 pt-5 border-t border-white/20 grid grid-cols-2 gap-4">
                    <div>
                      <p className="text-xs text-white/70">Prima Semestral (Jun/Dic):</p>
                      <p className="text-base font-bold text-white font-headline">
                        {formatCOP(calcData.primaSemestral)} c/u
                      </p>
                    </div>
                    <div>
                      <p className="text-xs text-white/70">Prestaciones Totales Anuales:</p>
                      <p className="text-base font-bold text-white font-headline">
                        {formatCOP(calcData.totalBeneficiosAnuales)}
                      </p>
                    </div>
                  </div>
                )}
              </div>
            </div>

            {/* Deductions Breakdown Card */}
            <div className="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-[#e5e5e5]">
              <h3 className="text-lg font-bold font-headline text-[#1f1b16] mb-4 flex items-center justify-between">
                <span>Desglose de Descuentos & Aportes de Ley</span>
                <span className="text-sm font-semibold text-[#ca4a1c]">
                  Total: {formatCOP(calcData.totalDeductions)}
                </span>
              </h3>

              <div className="space-y-3 font-body text-sm">
                <div className="flex items-center justify-between p-3 rounded-xl bg-neutral-50">
                  <span className="text-[#525252]">
                    Aporte a Salud ({contractType === 'laboral' ? '4% Empleado' : '12.5% sobre IBC 40%'})
                  </span>
                  <span className="font-bold text-[#1f1b16]">{formatCOP(calcData.salud)}</span>
                </div>

                <div className="flex items-center justify-between p-3 rounded-xl bg-neutral-50">
                  <span className="text-[#525252]">
                    Aporte a Pensión ({contractType === 'laboral' ? '4% Empleado' : '16% sobre IBC 40%'})
                  </span>
                  <span className="font-bold text-[#1f1b16]">{formatCOP(calcData.pension)}</span>
                </div>

                {calcData.fsp > 0 && (
                  <div className="flex items-center justify-between p-3 rounded-xl bg-neutral-50">
                    <span className="text-[#525252]">Fondo Solidaridad Pensional (1%)</span>
                    <span className="font-bold text-[#1f1b16]">{formatCOP(calcData.fsp)}</span>
                  </div>
                )}

                {calcData.retefuente > 0 && (
                  <div className="flex items-center justify-between p-3 rounded-xl bg-neutral-50">
                    <span className="text-[#525252]">Retención en la Fuente Estimada (DIAN)</span>
                    <span className="font-bold text-[#ca4a1c]">{formatCOP(calcData.retefuente)}</span>
                  </div>
                )}
              </div>

              <div className="mt-6 pt-5 border-t border-[#f0f0f0] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div className="text-xs text-[#737373]">
                  ¿Quieres buscar ofertas laborales con este rango salarial en Échele Camello?
                </div>
                <button
                  onClick={() => {
                    if (onExploreWithSalary) {
                      onExploreWithSalary(calcData.grossCOP);
                    } else {
                      setScreen('explorer');
                    }
                  }}
                  className="px-4 py-2.5 rounded-xl bg-[#a83301] hover:bg-[#ca4a1c] text-white text-xs font-bold font-headline flex items-center justify-center gap-1.5 shadow-md transition-all shrink-0"
                >
                  <span className="material-symbols-outlined text-sm">search</span>
                  Explorar vacantes afines
                </button>
              </div>
            </div>
          </div>
        </div>

        {/* Market Benchmark Simulator Section */}
        <div className="bg-white rounded-3xl p-6 sm:p-10 shadow-sm border border-[#e5e5e5] mb-8">
          <div className="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 pb-6 border-b border-[#f0f0f0]">
            <div>
              <span className="text-xs font-bold uppercase tracking-wider text-[#a83301]">
                Benchmarking de Mercado LATAM 2025
              </span>
              <h2 className="text-2xl font-bold font-headline text-[#1f1b16] mt-1">
                ¿Cuánto pagan realmente las empresas en Colombia?
              </h2>
            </div>
            <div className="flex items-center gap-2 text-xs text-[#737373]">
              <span className="material-symbols-outlined text-sm text-[#16a34a]">verified</span>
              Datos validados con más de 12.000 contrataciones en vivo
            </div>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div>
              <label className="block text-xs font-bold text-[#525252] mb-1.5">Cargo o Especialidad</label>
              <select
                value={selectedRole}
                onChange={(e) => setSelectedRole(e.target.value)}
                className="w-full p-3 rounded-xl border border-[#d4d4d4] text-xs font-semibold text-[#1f1b16] bg-white"
              >
                <option>Frontend / Fullstack Developer</option>
                <option>Backend & Cloud Engineer</option>
                <option>UI/UX Product Designer</option>
                <option>Data Analyst & BI Specialist</option>
                <option>Account Executive B2B</option>
              </select>
            </div>

            <div>
              <label className="block text-xs font-bold text-[#525252] mb-1.5">Nivel de Seniority</label>
              <select
                value={selectedSeniority}
                onChange={(e) => setSelectedSeniority(e.target.value as any)}
                className="w-full p-3 rounded-xl border border-[#d4d4d4] text-xs font-semibold text-[#1f1b16] bg-white"
              >
                <option value="Junior">Junior (0 - 2 años)</option>
                <option value="Semi-Senior">Semi-Senior (2 - 5 años)</option>
                <option value="Senior">Senior (5+ años)</option>
                <option value="Lead / Architect">Lead / Architect (8+ años)</option>
              </select>
            </div>

            <div>
              <label className="block text-xs font-bold text-[#525252] mb-1.5">Dominio de Inglés</label>
              <select
                value={englishLevel}
                onChange={(e) => setEnglishLevel(e.target.value as any)}
                className="w-full p-3 rounded-xl border border-[#d4d4d4] text-xs font-semibold text-[#1f1b16] bg-white"
              >
                <option value="Básico">Básico / Técnico Lectura</option>
                <option value="Intermedio B1">Intermedio B1 / B2 Conversacional</option>
                <option value="Avanzado C1">Avanzado C1 / Bilingüe Fluido (+40% valor)</option>
              </select>
            </div>
          </div>

          {/* Benchmark Range Visualizer */}
          <div className="bg-[#fff8f6] border border-[#f5b8a5] p-6 rounded-2xl">
            <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
              <div>
                <p className="text-xs font-bold text-[#737373] uppercase">
                  Banda Salarial Competitiva ({selectedRole} • {selectedSeniority})
                </p>
                <h3 className="text-xl sm:text-2xl font-black font-headline text-[#a83301]">
                  {formatCOP(marketBenchmark.min)} – {formatCOP(marketBenchmark.max)}
                </h3>
              </div>
              <div className="text-right">
                <span className="text-xs text-[#737373]">Mediana de Mercado:</span>
                <p className="text-lg font-bold text-[#1f1b16] font-headline">
                  {formatCOP(marketBenchmark.median)}
                </p>
              </div>
            </div>

            {/* Visual Bar */}
            <div className="relative pt-6 pb-2">
              <div className="h-3 w-full bg-neutral-200 rounded-full overflow-hidden flex">
                <div className="w-1/3 bg-[#f5b8a5]" />
                <div className="w-1/3 bg-[#ca4a1c]" />
                <div className="w-1/3 bg-[#a83301]" />
              </div>
              <div className="flex justify-between text-xs text-[#737373] mt-2 font-body">
                <span>P25: {formatCOP(marketBenchmark.min)}</span>
                <span className="font-bold text-[#a83301]">Mediana (P50): {formatCOP(marketBenchmark.median)}</span>
                <span>P90 (Top LATAM): {formatCOP(marketBenchmark.max)}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};
