<?php
/**
 * Asistente para Publicar una Nueva Oferta Laboral
 * Guarda directamente en la base de datos MySQL en XAMPP
 */

$pageTitle = "Publicar Vacante | Asistente de Contratación";
require_once __DIR__ . '/includes/header.php';
?>

<div class="min-h-screen bg-[#fff8f6] py-8 text-[#1f1b16]">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs sm:text-sm text-[#737373] mb-6 font-body">
      <a href="recruiter.php" class="hover:text-[#a83301]">Panel Reclutador</a>
      <span>/</span>
      <span class="text-[#1f1b16] font-medium">Publicar un Camello</span>
    </nav>

    <!-- Header -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-[#e5e5e5] mb-8">
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <span class="text-xs font-bold uppercase tracking-wider text-[#a83301] flex items-center gap-1.5">
            <span class="material-symbols-outlined text-sm">rocket_launch</span>
            Asistente de Contratación Échele Camello
          </span>
          <h1 class="text-2xl sm:text-3xl font-extrabold text-[#1f1b16] font-headline mt-1">
            Crear & Publicar una Nueva Oferta
          </h1>
          <p class="text-xs sm:text-sm text-[#525252] mt-1 font-body">
            Las ofertas con rangos salariales transparentes y beneficios detallados reciben hasta un <strong>340% más postulaciones calificadas</strong>.
          </p>
        </div>

        <div class="flex items-center gap-2 text-xs font-semibold text-[#16a34a] bg-[#f0fdf4] px-3 py-2 rounded-2xl border border-[#bbf7d0]">
          <span class="material-symbols-outlined text-base">verified</span>
          <span>Empresa Verificada: Stark Cloud Corp</span>
        </div>
      </div>
    </div>

    <!-- GRID FORMULARIO + PREVIEW -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
      
      <!-- Formulario (8 columnas) -->
      <div class="lg:col-span-8">
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-[#e5e5e5]">
          
          <form id="create-job-form" onsubmit="handleCreateJob(event)" class="space-y-6">
            
            <!-- 1. Título & Cargo -->
            <div>
              <h2 class="font-headline font-bold text-base text-[#1f1b16] mb-3 flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-[#ffdbd0] text-[#a83301] text-xs font-bold flex items-center justify-center">1</span>
                Información del Cargo
              </h2>
              
              <div class="space-y-4">
                <div>
                  <label class="block text-xs font-bold uppercase text-[#59413a] mb-1">Título de la Vacante *</label>
                  <input
                    type="text"
                    id="job-title"
                    name="title"
                    required
                    value="Senior Fullstack Engineer (React & Go)"
                    oninput="updatePreview()"
                    class="w-full p-3 rounded-xl border border-neutral-300 text-sm font-semibold text-[#1f1b16] focus:border-[#a83301] focus:outline-none"
                    placeholder="Ej: Senior Frontend Developer (React / TypeScript)"
                  />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-xs font-bold uppercase text-[#59413a] mb-1">Empresa Contratante</label>
                    <input
                      type="text"
                      id="job-company"
                      name="company"
                      value="Stark Cloud Corp"
                      oninput="updatePreview()"
                      class="w-full p-3 rounded-xl border border-neutral-300 text-xs font-medium text-[#1f1b16] focus:border-[#a83301] focus:outline-none"
                    />
                  </div>

                  <div>
                    <label class="block text-xs font-bold uppercase text-[#59413a] mb-1">Categoría</label>
                    <select
                      id="job-category"
                      name="category"
                      onchange="updatePreview()"
                      class="w-full p-3 rounded-xl border border-neutral-300 text-xs font-medium text-[#1f1b16] focus:border-[#a83301] focus:outline-none"
                    >
                      <option value="Tecnología & Software">Tecnología & Software</option>
                      <option value="Ventas & Comercial">Ventas & Comercial</option>
                      <option value="Marketing & Diseño">Marketing & Diseño</option>
                      <option value="Operaciones & Logística">Operaciones & Logística</option>
                      <option value="Finanzas & Contabilidad">Finanzas & Contabilidad</option>
                    </select>
                  </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                  <div>
                    <label class="block text-xs font-bold uppercase text-[#59413a] mb-1">Modalidad</label>
                    <select
                      id="job-modality"
                      name="modality"
                      onchange="updatePreview()"
                      class="w-full p-3 rounded-xl border border-neutral-300 text-xs font-medium text-[#1f1b16]"
                    >
                      <option value="100% Remoto">100% Remoto</option>
                      <option value="Híbrido">Híbrido</option>
                      <option value="Presencial">Presencial</option>
                    </select>
                  </div>

                  <div>
                    <label class="block text-xs font-bold uppercase text-[#59413a] mb-1">Seniority</label>
                    <select
                      id="job-seniority"
                      name="seniority"
                      onchange="updatePreview()"
                      class="w-full p-3 rounded-xl border border-neutral-300 text-xs font-medium text-[#1f1b16]"
                    >
                      <option value="Junior">Junior</option>
                      <option value="Semi-Senior">Semi-Senior</option>
                      <option value="Senior" selected>Senior</option>
                      <option value="Lead & Staff">Lead & Staff</option>
                    </select>
                  </div>

                  <div>
                    <label class="block text-xs font-bold uppercase text-[#59413a] mb-1">Urgencia / Badge</label>
                    <select
                      id="job-urgency"
                      name="urgency"
                      onchange="updatePreview()"
                      class="w-full p-3 rounded-xl border border-neutral-300 text-xs font-medium text-[#1f1b16]"
                    >
                      <option value="Urgente" selected>Urgente</option>
                      <option value="Destacada">Destacada</option>
                      <option value="1 Clic">1 Clic</option>
                      <option value="Activa">Activa</option>
                    </select>
                  </div>
                </div>

              </div>
            </div>

            <!-- 2. Remuneración & Contrato -->
            <div class="pt-6 border-t border-neutral-100">
              <h2 class="font-headline font-bold text-base text-[#1f1b16] mb-3 flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-[#ffdbd0] text-[#a83301] text-xs font-bold flex items-center justify-center">2</span>
                Transparencia Salarial & Contrato
              </h2>

              <div class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                  <div>
                    <label class="block text-xs font-bold uppercase text-[#59413a] mb-1">Moneda</label>
                    <select
                      id="job-currency"
                      name="currency"
                      onchange="handleCurrencyChange()"
                      class="w-full p-3 rounded-xl border border-neutral-300 text-xs font-bold text-[#a83301]"
                    >
                      <option value="USD">Dólares (USD)</option>
                      <option value="COP">Pesos Colombianos (COP)</option>
                    </select>
                  </div>

                  <div>
                    <label class="block text-xs font-bold uppercase text-[#59413a] mb-1">Salario Mínimo</label>
                    <input
                      type="number"
                      id="salary-min"
                      name="salary_min"
                      value="4000"
                      oninput="updatePreview()"
                      class="w-full p-3 rounded-xl border border-neutral-300 text-xs font-semibold text-[#1f1b16]"
                    />
                  </div>

                  <div>
                    <label class="block text-xs font-bold uppercase text-[#59413a] mb-1">Salario Máximo</label>
                    <input
                      type="number"
                      id="salary-max"
                      name="salary_max"
                      value="5500"
                      oninput="updatePreview()"
                      class="w-full p-3 rounded-xl border border-neutral-300 text-xs font-semibold text-[#1f1b16]"
                    />
                  </div>
                </div>

                <div>
                  <label class="block text-xs font-bold uppercase text-[#59413a] mb-1">Tipo de Contrato</label>
                  <input
                    type="text"
                    id="job-contract"
                    name="contract_type"
                    value="Contrato Contractor Internacional / Indefinido"
                    oninput="updatePreview()"
                    class="w-full p-3 rounded-xl border border-neutral-300 text-xs font-medium text-[#1f1b16]"
                  />
                </div>
              </div>
            </div>

            <!-- 3. Perks, Tags & Descripción -->
            <div class="pt-6 border-t border-neutral-100">
              <h2 class="font-headline font-bold text-base text-[#1f1b16] mb-3 flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-[#ffdbd0] text-[#a83301] text-xs font-bold flex items-center justify-center">3</span>
                Beneficios, Habilidades & Descripción
              </h2>

              <div class="space-y-4">
                <div>
                  <label class="block text-xs font-bold uppercase text-[#59413a] mb-1">Habilidades y Etiquetas (Tags separados por coma)</label>
                  <input
                    type="text"
                    id="job-tags"
                    name="tags"
                    value="Go, React 19, TypeScript, Kubernetes, Microservicios, Inglés C1"
                    oninput="updatePreview()"
                    class="w-full p-3 rounded-xl border border-neutral-300 text-xs font-medium text-[#1f1b16]"
                  />
                </div>

                <div>
                  <label class="block text-xs font-bold uppercase text-[#59413a] mb-1">Beneficios / Perks (separados por coma)</label>
                  <input
                    type="text"
                    id="job-perks"
                    name="perks"
                    value="Salario en Moneda Fuerte, Seguro Médico Internacional, MacBook Pro Provisto, 25 Días PTO Libres"
                    oninput="updatePreview()"
                    class="w-full p-3 rounded-xl border border-neutral-300 text-xs font-medium text-[#1f1b16]"
                  />
                </div>

                <div>
                  <label class="block text-xs font-bold uppercase text-[#59413a] mb-1">Descripción del Cargo & Responsabilidades *</label>
                  <textarea
                    id="job-desc"
                    name="description"
                    rows="5"
                    required
                    oninput="updatePreview()"
                    class="w-full p-3 rounded-xl border border-neutral-300 text-xs font-medium text-[#1f1b16] focus:border-[#a83301] focus:outline-none"
                  >Buscamos un Senior Fullstack Engineer con sólida experiencia en Go y React para liderar el desarrollo de nuestras APIs de alta concurrencia y microservicios en la nube. Ofrecemos trabajo 100% remoto, autonomía técnica y compensación en dólares.</textarea>
                </div>
              </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 flex items-center justify-end gap-3">
              <a href="recruiter.php" class="px-5 py-3 rounded-xl bg-neutral-100 text-xs font-bold text-[#1f1b16]">
                Cancelar
              </a>
              <button
                type="submit"
                id="btn-publish-job"
                class="px-8 py-3.5 rounded-xl bg-[#a83301] hover:bg-[#ca4a1c] text-white font-bold font-headline text-sm shadow-md transition-all flex items-center gap-2"
              >
                <span class="material-symbols-outlined text-base">cloud_upload</span>
                <span>Publicar Vacante en MySQL</span>
              </button>
            </div>

          </form>

        </div>
      </div>

      <!-- Preview en Tiempo Real (4 columnas) -->
      <div class="lg:col-span-4 space-y-4">
        <span class="text-xs font-bold uppercase tracking-wider text-[#a83301] flex items-center gap-1.5">
          <span class="material-symbols-outlined text-sm">visibility</span>
          Vista Previa en el Portal
        </span>

        <article class="bg-white rounded-3xl p-5 border border-[#ffdbd0] shadow-md relative">
          <div class="flex items-start gap-3 mb-3">
            <div class="w-12 h-12 rounded-xl bg-[#fff4f0] border border-[#ffdbd0] p-1 flex items-center justify-center shrink-0">
              <img
                src="https://lh3.googleusercontent.com/aida-public/AB6AXuDaLtyWrvTDHSisX7_s1dOIeOQaAbH_fMviGGcxP4PscKyDvneJ1dV7L4ubRpAN3xK2UWcoOp2E0ZQCtCv5mC1CpynAUHM8_aYvTtp5vbR3ED8q2ZOtrqVuGq6bhZj5zDsvkzTKeLXiGq-hxUyEYpVA7PjPAammTB7Yb75KeJTvgMf_MrLlIUVnXYFcK4RJWYp_9UFghIkZyb4mcnku1en1_PMGJ6EVKIb94TAIa-VqEJQHxdDyA-vs"
                alt="Logo"
                class="w-full h-full object-contain"
              />
            </div>
            <div>
              <span id="prev-company" class="text-xs font-bold text-[#1f1b18] block">Stark Cloud Corp</span>
              <span id="prev-modality" class="text-[11px] text-[#737373]">100% Remoto</span>
            </div>
          </div>

          <h3 id="prev-title" class="font-headline font-bold text-base text-[#1f1b16] mb-2">
            Senior Fullstack Engineer (React & Go)
          </h3>

          <div id="prev-salary" class="inline-block px-3 py-1 rounded-lg bg-[#fff4f0] text-[#a83301] font-headline font-black text-sm mb-3">
            USD $4,000 – $5,500 / mes
          </div>

          <p id="prev-desc" class="text-xs text-[#525252] line-clamp-3 mb-4">
            Buscamos un Senior Fullstack Engineer con sólida experiencia en Go y React para liderar el desarrollo de nuestras APIs de alta concurrencia.
          </p>

          <div id="prev-tags" class="flex flex-wrap gap-1 mb-4">
            <span class="px-2 py-0.5 rounded bg-neutral-100 text-[10px] font-medium text-[#525252]">Go</span>
            <span class="px-2 py-0.5 rounded bg-neutral-100 text-[10px] font-medium text-[#525252]">React 19</span>
            <span class="px-2 py-0.5 rounded bg-neutral-100 text-[10px] font-medium text-[#525252]">TypeScript</span>
          </div>

          <div class="pt-3 border-t border-neutral-100 flex items-center justify-between text-xs">
            <span class="text-[#16a34a] font-bold">Respuesta en &lt; 24h</span>
            <span class="text-[#a83301] font-bold">Postulación en 1 Clic</span>
          </div>
        </article>

        <div class="p-4 rounded-2xl bg-[#f0fdf4] border border-[#bbf7d0] text-xs text-[#16a34a] space-y-1">
          <p class="font-bold flex items-center gap-1">
            <span class="material-symbols-outlined text-sm">security</span>
            Sincronización Inmediata con MySQL
          </p>
          <p class="text-[#525252]">
            Al hacer clic en publicar, la vacante se inserta directamente en la tabla <code>jobs</code> de XAMPP y estará disponible para miles de candidatos en el Explorador.
          </p>
        </div>

      </div>

    </div>

  </div>
</div>

<script>
function handleCurrencyChange() {
    const curr = document.getElementById('job-currency').value;
    const minInput = document.getElementById('salary-min');
    const maxInput = document.getElementById('salary-max');

    if (curr === 'USD') {
        minInput.value = '4000';
        maxInput.value = '5500';
    } else {
        minInput.value = '15000000';
        maxInput.value = '22000000';
    }
    updatePreview();
}

function updatePreview() {
    const title = document.getElementById('job-title').value || 'Título de la vacante';
    const company = document.getElementById('job-company').value || 'Empresa';
    const modality = document.getElementById('job-modality').value;
    const currency = document.getElementById('job-currency').value;
    const min = document.getElementById('salary-min').value;
    const max = document.getElementById('salary-max').value;
    const desc = document.getElementById('job-desc').value;
    const tags = document.getElementById('job-tags').value.split(',').map(t => t.trim()).filter(Boolean);

    document.getElementById('prev-title').textContent = title;
    document.getElementById('prev-company').textContent = company;
    document.getElementById('prev-modality').textContent = modality;
    document.getElementById('prev-desc').textContent = desc;

    if (currency === 'USD') {
        document.getElementById('prev-salary').textContent = `USD $${Number(min).toLocaleString()} – $${Number(max).toLocaleString()} / mes`;
    } else {
        document.getElementById('prev-salary').textContent = `$${Number(min).toLocaleString('es-CO')} – $${Number(max).toLocaleString('es-CO')} COP / mes`;
    }

    const tagsContainer = document.getElementById('prev-tags');
    tagsContainer.innerHTML = '';
    tags.slice(0, 4).forEach(tag => {
        const span = document.createElement('span');
        span.className = 'px-2 py-0.5 rounded bg-neutral-100 text-[10px] font-medium text-[#525252]';
        span.textContent = tag;
        tagsContainer.appendChild(span);
    });
}

async function handleCreateJob(e) {
    e.preventDefault();
    const btn = document.getElementById('btn-publish-job');
    btn.disabled = true;
    btn.innerHTML = '<span class="material-symbols-outlined text-sm animate-spin">progress_activity</span> Guardando en MySQL...';

    const form = document.getElementById('create-job-form');
    const formData = new FormData(form);

    try {
        const res = await fetch('api/create_job.php', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        if (data.success) {
            btn.innerHTML = '<span class="material-symbols-outlined text-sm">check_circle</span> ¡Publicada con Éxito!';
            btn.className = 'px-8 py-3.5 rounded-xl bg-[#16a34a] text-white font-bold font-headline text-sm shadow-md transition-all flex items-center gap-2';
            showToast('¡Vacante guardada exitosamente en MySQL!');

            setTimeout(() => {
                window.location.href = 'explorer.php';
            }, 1200);
        } else {
            alert(data.message || 'Error al publicar vacante');
            btn.disabled = false;
            btn.innerHTML = '<span class="material-symbols-outlined text-base">cloud_upload</span> Publicar Vacante en MySQL';
        }
    } catch (err) {
        alert('Error de conexión con el servidor PHP');
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-outlined text-base">cloud_upload</span> Publicar Vacante en MySQL';
    }
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
