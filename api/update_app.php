<?php
/**
 * API: Actualizar estado de postulación (ej. Firmar oferta legal)
 */
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$appId = trim($_POST['app_id'] ?? '');
$action = trim($_POST['action'] ?? '');

if (empty($appId)) {
    echo json_encode(['success' => false, 'message' => 'ID de postulación requerido']);
    exit;
}

try {
    $pdo = getDBConnection();

    if ($action === 'sign_offer') {
        $stmt = $pdo->prepare("UPDATE applications SET status = 'offer', status_label = '¡Oferta Aceptada & Firmada! 🎉', status_color = 'emerald', action_title = '¡Contratación Exitosa!', action_description = 'Has firmado electrónicamente el contrato. Tu onboarding inicia el próximo lunes.', action_btn_text = 'Ver Contrato Firmado' WHERE id = ?");
        $stmt->execute([$appId]);

        echo json_encode([
            'success' => true,
            'message' => '¡Felicitaciones! Oferta firmada exitosamente. Bienvenido a tu nuevo camello.'
        ]);
        exit;
    }

    echo json_encode(['success' => false, 'message' => 'Acción no soportada']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
