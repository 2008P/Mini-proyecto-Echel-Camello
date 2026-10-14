<?php
/**
 * API: Guardar o quitar empleo de favoritos en MySQL
 */
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$jobId = trim($_POST['job_id'] ?? '');

if (empty($jobId)) {
    echo json_encode(['success' => false, 'message' => 'ID de vacante requerido']);
    exit;
}

try {
    $pdo = getDBConnection();
    
    // Verificar si ya existe
    $stmtCheck = $pdo->prepare("SELECT id FROM saved_jobs WHERE job_id = ?");
    $stmtCheck->execute([$jobId]);
    $existing = $stmtCheck->fetch();

    if ($existing) {
        $pdo->prepare("DELETE FROM saved_jobs WHERE job_id = ?")->execute([$jobId]);
        $saved = false;
    } else {
        $pdo->prepare("INSERT INTO saved_jobs (job_id) VALUES (?)")->execute([$jobId]);
        $saved = true;
    }

    $totalSaved = (int)$pdo->query("SELECT COUNT(*) FROM saved_jobs")->fetchColumn();

    echo json_encode([
        'success' => true,
        'saved' => $saved,
        'totalSaved' => $totalSaved
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
