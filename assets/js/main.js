/**
 * Script de interactividad global para Échele Camello
 */

// Toggle Notificaciones Drawer
function toggleNotifications(show) {
    const drawer = document.getElementById('notifications-modal');
    if (!drawer) return;
    if (show) {
        drawer.classList.remove('hidden');
    } else {
        drawer.classList.add('hidden');
    }
}

// Función para guardar / marcar vacante favorita vía AJAX
async function toggleBookmark(jobId, btnElement) {
    try {
        const formData = new FormData();
        formData.append('job_id', jobId);

        const res = await fetch('api/toggle_bookmark.php', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        if (data.success) {
            const icon = btnElement.querySelector('.material-symbols-outlined');
            if (icon) {
                if (data.saved) {
                    icon.textContent = 'bookmark';
                    icon.classList.add('text-[#a83301]');
                } else {
                    icon.textContent = 'bookmark_border';
                    icon.classList.remove('text-[#a83301]');
                }
            }

            // Actualizar badge de contador en el navbar
            const counter = document.getElementById('saved-jobs-counter');
            if (counter && data.totalSaved !== undefined) {
                counter.textContent = data.totalSaved;
            }
        }
    } catch (err) {
        console.error('Error al guardar vacante:', err);
    }
}

// Postulación rápida en 1 Clic
async function fastApply(jobId, btnElement) {
    if (!confirm('¿Desea enviar su postulación inmediata con su perfil verificado de Camilo Pérez?')) {
        return;
    }

    const originalText = btnElement.innerHTML;
    btnElement.disabled = true;
    btnElement.innerHTML = '<span class="material-symbols-outlined text-[16px] animate-spin">progress_activity</span> Enviando...';

    try {
        const formData = new FormData();
        formData.append('job_id', jobId);
        formData.append('name', 'Camilo Pérez');
        formData.append('email', 'camilo.perez@echelecamello.co');
        formData.append('phone', '+57 312 456 7890');
        formData.append('note', 'Postulación rápida con perfil verificado Échele Camello');

        const res = await fetch('api/apply.php', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        if (data.success) {
            btnElement.className = 'w-full py-2.5 px-3 rounded-lg bg-[#f0fdf4] text-[#16a34a] border border-[#bbf7d0] text-xs font-bold font-headline flex items-center justify-center gap-1.5 cursor-default';
            btnElement.innerHTML = '<span class="material-symbols-outlined text-[16px]">check_circle</span> ¡Postulado!';
            
            showToast('¡Postulación enviada exitosamente! Revisa tu panel de camellos.');
        } else {
            alert(data.message || 'Error al postularse');
            btnElement.disabled = false;
            btnElement.innerHTML = originalText;
        }
    } catch (e) {
        alert('Error al conectar con el servidor.');
        btnElement.disabled = false;
        btnElement.innerHTML = originalText;
    }
}

// Mini Toast Notification
function showToast(message) {
    let toast = document.getElementById('global-toast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'global-toast';
        toast.className = 'fixed bottom-6 right-6 z-50 bg-white text-[#2C2724] px-5 py-3 rounded-2xl shadow-xl border border-[#CCE3DE] text-sm font-semibold flex items-center gap-3 animate-scale-up';
        document.body.appendChild(toast);
    }
    toast.innerHTML = `<span class="material-symbols-outlined text-[#6B9080]">check_circle</span> <span>${message}</span>`;
    toast.classList.remove('hidden');

    setTimeout(() => {
        toast.classList.add('hidden');
    }, 4000);
}

// Cerrar modales con Escape
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        toggleNotifications(false);
        const modal = document.getElementById('apply-modal');
        if (modal) modal.classList.add('hidden');
    }
});
