<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'POST only']);
    exit;
}

$petId = (int)($_POST['pet_id'] ?? 0);
$adoptedBy = trim($_POST['adopted_by'] ?? '');
$adopterEmail = trim($_POST['adopter_email'] ?? '');
$message = trim($_POST['message'] ?? '');

if (!$petId || empty($adoptedBy)) {
    echo json_encode(['error' => 'Missing pet_id or adopted_by']);
    exit;
}

$pdo->beginTransaction();
try {
    // Get pet details
    $stmt = $pdo->prepare("SELECT * FROM pets WHERE id = ? AND is_adopted = 0");
    $stmt->execute([$petId]);
    $pet = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$pet) {
        throw new Exception('Pet not found or already adopted');
    }
    
    // Move to adopted_pets
    $stmt = $pdo->prepare("INSERT INTO adopted_pets (pet_id, pet_name, description, photos, seller_name, adopted_by, adopter_email, adoption_message) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$petId, $pet['pet_name'], $pet['description'], $pet['photos'], $pet['seller_name'], $adoptedBy, $adopterEmail, $message]);
    
    // Mark as adopted
    $stmt = $pdo->prepare("UPDATE pets SET is_adopted = 1 WHERE id = ?");
    $stmt->execute([$petId]);
    
    $pdo->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['error' => $e->getMessage()]);
}
?>

