<?php
/**
 * API: Guardar postulación a una vacante en MySQL
 */
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$jobId = trim($_POST['job_id'] ?? '');
$name = trim($_POST['name'] ?? 'Camilo Pérez');
$email = trim($_POST['email'] ?? 'camilo.perez@echelecamello.co');
$phone = trim($_POST['phone'] ?? '+57 312 456 7890');
$coverNote = trim($_POST['note'] ?? '');

if (empty($jobId)) {
    echo json_encode(['success' => false, 'message' => 'ID de vacante requerido']);
    exit;
}

try {
    $pdo = getDBConnection();
    
    // Obtener datos del empleo
    $stmtJob = $pdo->prepare("SELECT * FROM jobs WHERE id = ?");
    $stmtJob->execute([$jobId]);
    $job = $stmtJob->fetch();

    if (!$job) {
        echo json_encode(['success' => false, 'message' => 'Vacante no encontrada']);
        exit;
    }

    // Verificar si ya se postuló
    $stmtCheck = $pdo->prepare("SELECT id FROM applications WHERE job_id = ? AND applicant_email = ?");
    $stmtCheck->execute([$jobId, $email]);
    if ($stmtCheck->fetch()) {
        echo json_encode(['success' => true, 'message' => 'Ya te encuentras postulado a esta vacante', 'alreadyApplied' => true]);
        exit;
    }

    // Insertar nueva postulación
    $appId = 'app-' . time() . '-' . rand(100, 999);
    $salaryDisplay = $job['currency'] === 'USD' ? ($job['salary_usd'] ?? 'USD $3,500') : ($job['salary_cop'] ?? '$10,000,000 COP');

    $sql = "INSERT INTO applications (
        id, job_id, title, company, company_logo, location, status, status_label, status_color, 
        salary_display, contract_type, applied_days_ago, stage_progress, progress_percent, tags, 
        applicant_name, applicant_email, applicant_phone, cover_note
    ) VALUES (
        :id, :job_id, :title, :company, :company_logo, :location, 'review', 'En Revisión por Talento', 'gray',
        :salary_display, :contract_type, 0, 'Fase 1 de 4', 25, :tags,
        :name, :email, :phone, :cover_note
    )";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id' => $appId,
        ':job_id' => $job['id'],
        ':title' => $job['title'],
        ':company' => $job['company'],
        ':company_logo' => $job['company_logo'],
        ':location' => $job['location'],
        ':salary_display' => $salaryDisplay,
        ':contract_type' => $job['contract_type'],
        ':tags' => $job['tags'],
        ':name' => $name,
        ':email' => $email,
        ':phone' => $phone,
        ':cover_note' => $coverNote,
    ]);

    // Incrementar conteo de candidatos activos en la vacante
    $pdo->prepare("UPDATE jobs SET active_candidates = active_candidates + 1 WHERE id = ?")->execute([$jobId]);

    echo json_encode([
        'success' => true,
        'message' => '¡Postulación enviada exitosamente!',
        'application_id' => $appId
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error en base de datos: ' . $e->getMessage()]);
}
