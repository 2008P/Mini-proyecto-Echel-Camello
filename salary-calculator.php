<?php
/**
 * Calculadora Salarial Colombiana (Normativa CST y Contractor)
 * Échele Camello - Salarios Transparentes
 */

$pageTitle = "Calculadora Salarial Colombia CST & Remoto | Transparencia";
require_once __DIR__ . '/includes/header.php';
?>

<div class="min-h-screen bg-[#fff8f6] py-8 text-[#1f1b16]">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs sm:text-sm text-[#737373] mb-6 font-body">
      <a href="index.php" class="hover:text-[#a83301]">Inicio</a>
      <span>/</span>
      <span class="text-[#1f1b16] font-medium">Calculadora Salarial Colombia CST</span>
    </nav>

    <!-- Header Banner -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-[#e5e5e5] mb-8">
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
          <span class="text-xs font-bold uppercase tracking-wider text-[#a83301] flex items-center gap-1.5">
            <span class="material-symbols-outlined text-base">verified</span>
            Herramienta Oficial de Transparencia Laboral
          </span>
          <h1 class="text-2xl sm:text-4xl font-extrabold text-[#1f1b18] font-headline mt-1 tracking-tight">
            Calculadora de Salario Neto Colombia
          </h1>
          <p class="text-xs sm:text-sm text-[#525252] mt-1 max-w-2xl font-body">
            Calcule con precisión matemática su compensación neta según la ley laboral colombiana (CST), aportes a seguridad social, retención en la fuente (UVT) y prestaciones sociales.
          </p>
        </div>

        <div class="flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-[#fff4f0] border border-[#ffdbd0] shrink-0">
          <span class="material-symbols-outlined text-[#a83301]">currency_exchange</span>
          <div class="text-xs">
            <span class="font-bold text-[#1f1b18] block">TRM Referencia</span>
            <span class="text-[#a83301] font-extrabold">1 USD = $4.200 COP</span>
          </div>
        </div>
      </div>
    </div>

    <!-- CALCULADORA INTERACTIVA: GRID DE 2 COLUMNAS -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
      
      <!-- Panel de Controles & Entradas (5 Columnas) -->
      <div class="lg:col-span-5 space-y-6">
        
        <div class="bg-white rounded-3xl p-6 sm:p-7 shadow-sm border border-[#e5e5e5]">
          <h2 class="font-headline font-bold text-lg text-[#1f1b16] mb-5 flex items-center gap-2">
            <span class="material-symbols-outlined text-[#a83301]">tune</span>
            Configurar Salario & Tipo de Contrato
          </h2>

          <!-- Selector de Divisa -->
          <div class="mb-5">
            <label class="block text-xs font-bold uppercase text-[#59413a] mb-2">Moneda del Salario</label>
            <div class="grid grid-cols-2 gap-2">
              <button
                type="button"
                id="curr-cop"
                onclick="setCurrency('COP')"
                class="py-2.5 px-3 rounded-xl border font-bold font-headline text-xs flex items-center justify-center gap-1.5 transition-all bg-[#a83301] text-white border-[#a83301]"
              >
                <span>🇨🇴 Pesos (COP)</span>
              </button>
              <button
                type="button"
                id="curr-usd"
                onclick="setCurrency('USD')"
                class="py-2.5 px-3 rounded-xl border font-bold font-headline text-xs flex items-center justify-center gap-1.5 transition-all bg-neutral-50 text-[#525252] border-neutral-200"
              >
                <span>🇺🇸 Dólares (USD)</span>
              </button>
            </div>
          </div>

          <!-- Monto Bruto -->
          <div class="mb-5">
            <label class="block text-xs font-bold uppercase text-[#59413a] mb-2">
              Salario o Ingreso Bruto Mensual
            </label>
            <div class="relative">
              <span id="gross-prefix" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm font-bold text-[#a83301]">$</span>
              <input
                type="number"
                id="gross-input"
                value="12000000"
                step="500000"
                oninput="calculateSalary()"
                class="w-full pl-8 pr-4 py-3 rounded-xl border border-neutral-300 text-lg font-black font-headline text-[#1f1b16] focus:border-[#a83301] focus:outline-none"
              />
            </div>
            <!-- Slider rápido -->
            <input
              type="range"
              id="gross-slider"
              min="2000000"
              max="35000000"
              step="500000"
              value="12000000"
              oninput="syncSlider(this.value)"
              class="w-full mt-3 accent-[#a83301] cursor-pointer"
            />
          </div>

          <!-- Tipo de Contrato -->
          <div class="mb-6">
            <label class="block text-xs font-bold uppercase text-[#59413a] mb-2">Tipo de Contrato</label>
            <div class="space-y-2">
              <label class="flex items-start gap-3 p-3 rounded-xl border border-[#ffdbd0] bg-[#fff8f5] cursor-pointer transition-all">
                <input
                  type="radio"
                  name="contract_type"
                  value="laboral"
                  checked
                  onchange="setContractType('laboral')"
                  class="mt-1 accent-[#a83301]"
                />
                <div>
                  <span class="block text-xs font-bold text-[#1f1b16]">Contrato Laboral (CST)</span>
                  <span class="block text-[11px] text-[#525252]">
                    Término Indefinido / Fijo. Incluye prima, cesantías, intereses y vacaciones pagadas.
                  </span>
                </div>
              </label>

              <label class="flex items-start gap-3 p-3 rounded-xl border border-neutral-200 bg-white hover:bg-neutral-50 cursor-pointer transition-all">
                <input
                  type="radio"
                  name="contract_type"
                  value="prestacion"
                  onchange="setContractType('prestacion')"
                  class="mt-1 accent-[#a83301]"
                />
                <div>
                  <span class="block text-xs font-bold text-[#1f1b16]">Prestación de Servicios / Contractor</span>
                  <span class="block text-[11px] text-[#525252]">
                    Independiente / W-8BEN Internacional. IBC al 40% para salud y pensión por cuenta propia.
                  </span>
                </div>
              </label>
            </div>
          </div>

          <!-- Benchmark de Mercado: Cargo y Nivel de Inglés -->
          <div class="pt-5 border-t border-neutral-100 space-y-4">
            <h3 class="font-headline font-bold text-xs uppercase tracking-wider text-[#a83301]">
              Comparativa de Mercado por Rol
            </h3>

            <div>
              <label class="block text-xs font-bold text-[#59413a] mb-1">Especialidad</label>
              <select id="role-select" onchange="calculateSalary()" class="w-full p-2.5 rounded-xl border border-neutral-200 text-xs font-medium text-[#1f1b16]">
                <option value="dev">Frontend / Fullstack Developer</option>
                <option value="backend">Backend & Cloud Engineer</option>
                <option value="design">Product Designer UI/UX</option>
                <option value="sales">Ventas & Account Executive B2B</option>
                <option value="ops">Operaciones & Logística</option>
              </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="block text-xs font-bold text-[#59413a] mb-1">Seniority</label>
                <select id="seniority-select" onchange="calculateSalary()" class="w-full p-2.5 rounded-xl border border-neutral-200 text-xs font-medium text-[#1f1b16]">
                  <option value="Junior">Junior (1-2 años)</option>
                  <option value="Semi-Senior">Semi-Senior (3-4 años)</option>
                  <option value="Senior" selected>Senior (5+ años)</option>
                  <option value="Lead">Lead & Staff</option>
                </select>
              </div>

              <div>
                <label class="block text-xs font-bold text-[#59413a] mb-1">Nivel de Inglés</label>
                <select id="english-select" onchange="calculateSalary()" class="w-full p-2.5 rounded-xl border border-neutral-200 text-xs font-medium text-[#1f1b16]">
                  <option value="basico">Básico / A2</option>
                  <option value="b1">Intermedio / B1-B2</option>
                  <option value="c1" selected>Avanzado C1 / Bilingüe (+45%)</option>
                </select>
              </div>
            </div>

          </div>

        </div>

      </div>

      <!-- Panel de Resultados (7 Columnas) -->
      <div class="lg:col-span-7 space-y-6">
        
        <!-- Salario Neto Result Card -->
        <div class="bg-gradient-to-br from-[#1f1b18] to-[#3a2820] text-white rounded-3xl p-6 sm:p-8 shadow-xl relative overflow-hidden">
          <div class="absolute right-0 top-0 w-72 h-72 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-[#ca4a1c]/40 via-transparent to-transparent pointer-events-none"></div>

          <div class="relative z-10">
            <span class="text-xs uppercase tracking-wider font-bold text-[#ffb59d] flex items-center gap-1.5">
              <span class="material-symbols-outlined text-sm">account_balance_wallet</span>
              Salario Neto Mensual Estimado
            </span>

            <div class="mt-2 flex flex-col sm:flex-row sm:items-baseline gap-2 sm:gap-4">
              <div id="result-net-cop" class="text-3xl sm:text-5xl font-black font-headline text-white tracking-tight">
                $10.743.000 COP
              </div>
              <div id="result-net-usd" class="text-base sm:text-lg font-bold text-[#f5b8a5]">
                ~ USD $2,557
              </div>
            </div>

            <p class="text-xs text-neutral-300 mt-2 font-body">
              Ingreso líquido que recibe directamente en su cuenta bancaria tras todas las deducciones de ley.
            </p>

            <!-- Comparador con el mercado -->
            <div class="mt-6 pt-5 border-t border-neutral-700">
              <div class="flex items-center justify-between text-xs text-neutral-300 mb-2">
                <span>Rango Mercado LATAM para tu perfil:</span>
                <span id="market-range-text" class="font-bold text-[#f5b8a5]">$16.2M - $29.7M COP</span>
              </div>
              <div class="w-full h-2 rounded-full bg-neutral-800 overflow-hidden">
                <div class="h-full bg-gradient-to-r from-[#a83301] to-[#16a34a] rounded-full w-3/4"></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Desglose Detallado de Deducciones -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-[#e5e5e5]">
          <h3 class="font-headline font-bold text-base text-[#1f1b16] mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-[#a83301]">receipt_long</span>
            Desglose de Deducciones Legales Mensuales
          </h3>

          <div class="space-y-3 font-body text-xs sm:text-sm">
            <div class="flex items-center justify-between p-3 rounded-xl bg-neutral-50 border border-neutral-200">
              <div>
                <span class="font-bold text-[#1f1b16] block">Aporte a Salud</span>
                <span id="salud-desc" class="text-[11px] text-[#737373]">4% a cargo del trabajador</span>
              </div>
              <span id="result-salud" class="font-bold text-[#ba1a1a]">-$480.000 COP</span>
            </div>

            <div class="flex items-center justify-between p-3 rounded-xl bg-neutral-50 border border-neutral-200">
              <div>
                <span class="font-bold text-[#1f1b16] block">Aporte a Pensión</span>
                <span id="pension-desc" class="text-[11px] text-[#737373]">4% a cargo del trabajador</span>
              </div>
              <span id="result-pension" class="font-bold text-[#ba1a1a]">-$480.000 COP</span>
            </div>

            <div id="row-fsp" class="flex items-center justify-between p-3 rounded-xl bg-neutral-50 border border-neutral-200">
              <div>
                <span class="font-bold text-[#1f1b16] block">Fondo de Solidaridad Pensional (FSP)</span>
                <span class="text-[11px] text-[#737373]">1% para salarios superiores a 4 SMMLV</span>
              </div>
              <span id="result-fsp" class="font-bold text-[#ba1a1a]">-$120.000 COP</span>
            </div>

            <div id="row-rete" class="flex items-center justify-between p-3 rounded-xl bg-neutral-50 border border-neutral-200">
              <div>
                <span class="font-bold text-[#1f1b16] block">Retención en la Fuente Estimada</span>
                <span class="text-[11px] text-[#737373]">Tabla UVT DIAN con 25% de renta exenta</span>
              </div>
              <span id="result-rete" class="font-bold text-[#ba1a1a]">-$177.000 COP</span>
            </div>

            <div class="flex items-center justify-between pt-3 border-t border-neutral-200 text-sm font-bold">
              <span class="text-[#1f1b16]">Total Deducciones:</span>
              <span id="result-total-deductions" class="text-[#ba1a1a]">-$1.257.000 COP</span>
            </div>
          </div>
        </div>

        <!-- Prestaciones Sociales (Beneficios anuales pagados por la empresa en CST) -->
        <div id="box-benefits" class="bg-[#f0fdf4] rounded-3xl p-6 sm:p-7 border border-[#bbf7d0]">
          <h3 class="font-headline font-bold text-sm text-[#16a34a] uppercase tracking-wider mb-3 flex items-center gap-2">
            <span class="material-symbols-outlined text-base">redeem</span>
            Prestaciones Sociales Anuales a Cargo del Empleador (CST)
          </h3>
          <p class="text-xs text-[#525252] leading-relaxed mb-4">
            En un contrato laboral legal en Colombia, usted recibe pagos adicionales obligatorios:
          </p>

          <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-center">
            <div class="bg-white p-3 rounded-xl border border-[#bbf7d0]">
              <span class="block text-[11px] text-[#737373] font-semibold">Prima Legal</span>
              <span id="result-prima" class="font-headline font-bold text-xs sm:text-sm text-[#1f1b16] mt-0.5 block">$12.000.000</span>
              <span class="text-[9px] text-[#16a34a]">1 mes / año</span>
            </div>
            <div class="bg-white p-3 rounded-xl border border-[#bbf7d0]">
              <span class="block text-[11px] text-[#737373] font-semibold">Cesantías</span>
              <span id="result-cesantias" class="font-headline font-bold text-xs sm:text-sm text-[#1f1b16] mt-0.5 block">$12.000.000</span>
              <span class="text-[9px] text-[#16a34a]">Ahorro obligatorio</span>
            </div>
            <div class="bg-white p-3 rounded-xl border border-[#bbf7d0]">
              <span class="block text-[11px] text-[#737373] font-semibold">Intereses Ces.</span>
              <span id="result-intereses" class="font-headline font-bold text-xs sm:text-sm text-[#1f1b16] mt-0.5 block">$1.440.000</span>
              <span class="text-[9px] text-[#16a34a]">12% anual</span>
            </div>
            <div class="bg-white p-3 rounded-xl border border-[#bbf7d0]">
              <span class="block text-[11px] text-[#737373] font-semibold">Vacaciones</span>
              <span id="result-vacaciones" class="font-headline font-bold text-xs sm:text-sm text-[#1f1b16] mt-0.5 block">$6.000.000</span>
              <span class="text-[9px] text-[#16a34a]">15 días hábiles</span>
            </div>
          </div>
        </div>

        <!-- CTA para ir al explorador -->
        <a
          id="btn-explore-salary"
          href="explorer.php?salary=cop-10m"
          class="w-full py-4 px-6 rounded-2xl bg-[#a83301] hover:bg-[#ca4a1c] text-white font-headline text-center font-bold text-sm sm:text-base shadow-md transition-all flex items-center justify-center gap-2"
        >
          <span class="material-symbols-outlined">search</span>
          <span>Explorar vacantes con este nivel salarial</span>
        </a>

      </div>

    </div>

  </div>
</div>

<script>
let currentCurrency = 'COP';
let currentContract = 'laboral';
const TRM = 4200;
const SMMLV = 1423500;
const UVT = 49799;

function setCurrency(curr) {
    currentCurrency = curr;
    const btnCOP = document.getElementById('curr-cop');
    const btnUSD = document.getElementById('curr-usd');
    const prefix = document.getElementById('gross-prefix');
    const input = document.getElementById('gross-input');
    const slider = document.getElementById('gross-slider');

    if (curr === 'USD') {
        btnUSD.className = 'py-2.5 px-3 rounded-xl border font-bold font-headline text-xs flex items-center justify-center gap-1.5 transition-all bg-[#a83301] text-white border-[#a83301]';
        btnCOP.className = 'py-2.5 px-3 rounded-xl border font-bold font-headline text-xs flex items-center justify-center gap-1.5 transition-all bg-neutral-50 text-[#525252] border-neutral-200';
        prefix.textContent = 'USD $';
        input.value = '3500';
        input.step = '200';
        slider.min = '500';
        slider.max = '10000';
        slider.step = '200';
        slider.value = '3500';
    } else {
        btnCOP.className = 'py-2.5 px-3 rounded-xl border font-bold font-headline text-xs flex items-center justify-center gap-1.5 transition-all bg-[#a83301] text-white border-[#a83301]';
        btnUSD.className = 'py-2.5 px-3 rounded-xl border font-bold font-headline text-xs flex items-center justify-center gap-1.5 transition-all bg-neutral-50 text-[#525252] border-neutral-200';
        prefix.textContent = '$';
        input.value = '12000000';
        input.step = '500000';
        slider.min = '2000000';
        slider.max = '35000000';
        slider.step = '500000';
        slider.value = '12000000';
    }
    calculateSalary();
}

function syncSlider(val) {
    document.getElementById('gross-input').value = val;
    calculateSalary();
}

function setContractType(type) {
    currentContract = type;
    calculateSalary();
}

function formatNumberCOP(num) {
    return '$' + Math.round(num).toLocaleString('es-CO') + ' COP';
}

function formatNumberUSD(num) {
    return 'USD $' + Math.round(num).toLocaleString('en-US');
}

function calculateSalary() {
    let grossVal = parseFloat(document.getElementById('gross-input').value) || 0;
    let grossCOP = currentCurrency === 'USD' ? grossVal * TRM : grossVal;

    let salud = 0;
    let pension = 0;
    let fsp = 0;
    let rete = 0;

    const rowFSP = document.getElementById('row-fsp');
    const rowRete = document.getElementById('row-rete');
    const boxBenefits = document.getElementById('box-benefits');
    const saludDesc = document.getElementById('salud-desc');
    const pensionDesc = document.getElementById('pension-desc');

    if (currentContract === 'laboral') {
        salud = grossCOP * 0.04;
        pension = grossCOP * 0.04;
        fsp = grossCOP > (SMMLV * 4) ? grossCOP * 0.01 : 0;

        // Retención en la fuente estimada UVT
        const baseGravable = grossCOP - salud - pension - (grossCOP * 0.25);
        const baseUVT = baseGravable / UVT;
        if (baseUVT > 95 && baseUVT <= 150) {
            rete = (baseUVT - 95) * 0.19 * UVT;
        } else if (baseUVT > 150) {
            rete = ((150 - 95) * 0.19 + (baseUVT - 150) * 0.28) * UVT;
        }

        rowFSP.style.display = 'flex';
        rowRete.style.display = 'flex';
        boxBenefits.style.display = 'block';
        saludDesc.textContent = '4% a cargo del trabajador';
        pensionDesc.textContent = '4% a cargo del trabajador';

        // Beneficios
        const prima = grossCOP;
        const cesantias = grossCOP;
        const intereses = cesantias * 0.12;
        const vacaciones = grossCOP * 0.5;

        document.getElementById('result-prima').textContent = '$' + Math.round(prima).toLocaleString('es-CO');
        document.getElementById('result-cesantias').textContent = '$' + Math.round(cesantias).toLocaleString('es-CO');
        document.getElementById('result-intereses').textContent = '$' + Math.round(intereses).toLocaleString('es-CO');
        document.getElementById('result-vacaciones').textContent = '$' + Math.round(vacaciones).toLocaleString('es-CO');
    } else {
        // Independiente / Prestación de Servicios
        const ibc = Math.max(SMMLV, grossCOP * 0.40);
        salud = ibc * 0.125;
        pension = ibc * 0.16;
        fsp = 0;
        rete = 0;

        rowFSP.style.display = 'none';
        rowRete.style.display = 'none';
        boxBenefits.style.display = 'none';
        saludDesc.textContent = '12.5% sobre IBC (40% de honorarios)';
        pensionDesc.textContent = '16% sobre IBC (40% de honorarios)';
    }

    const totalDeductions = salud + pension + fsp + rete;
    const netSalary = Math.max(0, grossCOP - totalDeductions);

    document.getElementById('result-salud').textContent = '-' + formatNumberCOP(salud);
    document.getElementById('result-pension').textContent = '-' + formatNumberCOP(pension);
    document.getElementById('result-fsp').textContent = '-' + formatNumberCOP(fsp);
    document.getElementById('result-rete').textContent = '-' + formatNumberCOP(rete);
    document.getElementById('result-total-deductions').textContent = '-' + formatNumberCOP(totalDeductions);

    document.getElementById('result-net-cop').textContent = formatNumberCOP(netSalary);
    document.getElementById('result-net-usd').textContent = '~ ' + formatNumberUSD(netSalary / TRM);

    // Benchmarks por rol
    const role = document.getElementById('role-select').value;
    const seniority = document.getElementById('seniority-select').value;
    const english = document.getElementById('english-select').value;

    let baseMin = 4000000;
    let baseMax = 12000000;

    if (role === 'dev' || role === 'backend') {
        if (seniority === 'Junior') { baseMin = 3500000; baseMax = 7000000; }
        else if (seniority === 'Semi-Senior') { baseMin = 6500000; baseMax = 12500000; }
        else if (seniority === 'Senior') { baseMin = 12000000; baseMax = 22000000; }
        else { baseMin = 18000000; baseMax = 35000000; }
    } else if (role === 'design') {
        if (seniority === 'Junior') { baseMin = 3000000; baseMax = 5800000; }
        else if (seniority === 'Semi-Senior') { baseMin = 5500000; baseMax = 10500000; }
        else if (seniority === 'Senior') { baseMin = 9500000; baseMax = 18000000; }
        else { baseMin = 15000000; baseMax = 28000000; }
    } else {
        if (seniority === 'Junior') { baseMin = 2500000; baseMax = 5000000; }
        else if (seniority === 'Semi-Senior') { baseMin = 4800000; baseMax = 9000000; }
        else if (seniority === 'Senior') { baseMin = 8000000; baseMax = 15000000; }
        else { baseMin = 13000000; baseMax = 24000000; }
    }

    if (english === 'c1') {
        baseMin *= 1.45;
        baseMax *= 1.55;
    } else if (english === 'b1') {
        baseMin *= 1.15;
        baseMax *= 1.20;
    }

    document.getElementById('market-range-text').textContent = 
        '$' + (baseMin / 1000000).toFixed(1) + 'M - $' + (baseMax / 1000000).toFixed(1) + 'M COP';
}

// Inicializar al cargar
document.addEventListener('DOMContentLoaded', calculateSalary);
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
