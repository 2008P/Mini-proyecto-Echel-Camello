<?php
/**
 * Funciones auxiliares y lógica común para Échele Camello
 */

require_once __DIR__ . '/../config/db.php';

/**
 * Formatear número a pesos colombianos (COP)
 */
function formatCOP($amount) {
    if (!$amount) return '$0 COP';
    return '$' . number_format($amount, 0, ',', '.') . ' COP';
}

/**
 * Formatear número a dólares (USD)
 */
function formatUSD($amount) {
    if (!$amount) return '$0 USD';
    return '$' . number_format($amount, 0, '.', ',') . ' USD';
}

/**
 * Obtener cantidad de vacantes guardadas
 */
function getSavedJobsCount() {
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->query("SELECT COUNT(*) FROM saved_jobs");
        return (int)$stmt->fetchColumn();
    } catch (Exception $e) {
        return 3;
    }
}

/**
 * Verificar si una vacante está guardada
 */
function isJobSaved($jobId) {
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM saved_jobs WHERE job_id = ?");
        $stmt->execute([$jobId]);
        return $stmt->fetchColumn() > 0;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Obtener badge de urgencia estilizado
 */
function getUrgencyBadge($urgency) {
    $urgency = trim($urgency ?? 'Activa');
    switch (strtolower($urgency)) {
        case 'urgente':
            return '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#ffdbd0] text-[#a83301] border border-[#ffb59d]"><span class="material-symbols-outlined text-[13px]">bolt</span> Urgente</span>';
        case 'destacada':
            return '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#fff4f0] text-[#a83301] border border-[#ffdbd0]"><span class="material-symbols-outlined text-[13px]">star</span> Destacada</span>';
        case '1 clic':
            return '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#f0fdf4] text-[#16a34a] border border-[#bbf7d0]"><span class="material-symbols-outlined text-[13px]">touch_app</span> 1 Clic</span>';
        case 'rápido':
            return '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#eff6ff] text-[#0284c7] border border-[#bfdbfe]"><span class="material-symbols-outlined text-[13px]">speed</span> Rápido</span>';
        case 'verificado':
            return '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#f5f3ff] text-[#7c3aed] border border-[#ddd6fe]"><span class="material-symbols-outlined text-[13px]">verified</span> Verificado</span>';
        default:
            return '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#f5ece7] text-[#59413a]"><span class="material-symbols-outlined text-[13px]">schedule</span> Activa</span>';
    }
}

/**
 * Parsear tags o perks separados por comas o punto y coma
 */
function parseList($text) {
    if (!$text) return [];
    if (strpos($text, ';') !== false) {
        $items = explode(';', $text);
    } else {
        $items = explode(',', $text);
    }
    return array_values(array_filter(array_map('trim', $items)));
}
