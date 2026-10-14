<?php
/**
 * API: Crear una nueva vacante y guardarla en MySQL
 */
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$title = trim($_POST['title'] ?? '');
$company = trim($_POST['company'] ?? 'Stark Cloud Corp');
$category = trim($_POST['category'] ?? 'Tecnología & Software');
$modality = trim($_POST['modality'] ?? '100% Remoto');
$currency = trim($_POST['currency'] ?? 'USD');
$salaryMin = (int)($_POST['salary_min'] ?? 3500);
$salaryMax = (int)($_POST['salary_max'] ?? 4800);
$contractType = trim($_POST['contract_type'] ?? 'Contrato Contractor Internacional');
$experienceLevel = trim($_POST['seniority'] ?? 'Senior');
$urgency = trim($_POST['urgency'] ?? 'Urgente');
$tags = trim($_POST['tags'] ?? '');
$perks = trim($_POST['perks'] ?? '');
$description = trim($_POST['description'] ?? '');

if (empty($title) || empty($description)) {
    echo json_encode(['success' => false, 'message' => 'Título y descripción son obligatorios']);
    exit;
}

try {
    $pdo = getDBConnection();

    $jobId = 'job-' . time() . '-' . rand(100, 999);
    
    // Formatear salarios
    if ($currency === 'USD') {
        $salaryUSD = "USD $" . number_format($salaryMin) . " – $" . number_format($salaryMax);
        $salaryCOP = "$" . number_format($salaryMin * 4200, 0, ',', '.') . " COP";
    } else {
        $salaryCOP = "$" . number_format($salaryMin, 0, ',', '.') . " – $" . number_format($salaryMax, 0, ',', '.') . " COP";
        $salaryUSD = "USD $" . number_format(round($salaryMin / 4200));
    }

    $sql = "INSERT INTO jobs (
        id, title, company_id, company, company_logo, location, modality,
        salary_cop, salary_usd, salary_min, salary_max, currency, salary_period,
        contract_type, experience_level, category, tags, perks, posted_time,
        urgency, verified_company, response_time, active_candidates, description
    ) VALUES (
        :id, :title, 'stark-cloud', :company, 
        'https://lh3.googleusercontent.com/aida-public/AB6AXuDaLtyWrvTDHSisX7_s1dOIeOQaAbH_fMviGGcxP4PscKyDvneJ1dV7L4ubRpAN3xK2UWcoOp2E0ZQCtCv5mC1CpynAUHM8_aYvTtp5vbR3ED8q2ZOtrqVuGq6bhZj5zDsvkzTKeLXiGq-hxUyEYpVA7PjPAammTB7Yb75KeJTvgMf_MrLlIUVnXYFcK4RJWYp_9UFghIkZyb4mcnku1en1_PMGJ6EVKIb94TAIa-VqEJQHxdDyA-vs',
        :location, :modality, :salary_cop, :salary_usd, :salary_min, :salary_max, :currency,
        '/ mes', :contract_type, :experience_level, :category, :tags, :perks,
        'Publicado hace unos momentos', :urgency, 1, '< 24h', 1, :description
    )";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id' => $jobId,
        ':title' => $title,
        ':company' => $company,
        ':location' => ($modality === '100% Remoto' ? '100% Remoto (Colombia / LATAM)' : 'Bogotá / Híbrido'),
        ':modality' => $modality,
        ':salary_cop' => $salaryCOP,
        ':salary_usd' => $salaryUSD,
        ':salary_min' => $salaryMin,
        ':salary_max' => $salaryMax,
        ':currency' => $currency,
        ':contract_type' => $contractType,
        ':experience_level' => $experienceLevel,
        ':category' => $category,
        ':tags' => $tags,
        ':perks' => $perks,
        ':urgency' => $urgency,
        ':description' => $description,
    ]);

    echo json_encode([
        'success' => true,
        'message' => '¡Vacante publicada exitosamente en MySQL!',
        'job_id' => $jobId
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error al guardar vacante: ' . $e->getMessage()]);
}
