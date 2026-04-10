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

$petName = trim($_POST['pet_name'] ?? '');
$description = trim($_POST['description'] ?? '');
$sellerName = trim($_POST['seller_name'] ?? '');
$phone = trim($_POST['phone_number'] ?? '');
$address = trim($_POST['address'] ?? '');

if (empty($petName) || empty($sellerName)) {
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

// Handle file uploads
$photos = [];
if (!empty($_FILES['photos']['name'][0])) {
    $uploadDir = '../uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
    
    foreach ($_FILES['photos']['name'] as $key => $name) {
        $tmpName = $_FILES['photos']['tmp_name'][$key];
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png','gif']) && $_FILES['photos']['size'][$key] < 5*1024*1024) {
            $newName = uniqid() . '.' . $ext;
            $dest = $uploadDir . $newName;
            if (move_uploaded_file($tmpName, $dest)) {
                $photos[] = 'uploads/' . $newName;
            }
        }
    }
}

if (empty($photos)) {
    echo json_encode(['error' => 'No valid photos uploaded']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO pets (pet_name, description, photos, seller_name, phone_number, address) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$petName, $description, json_encode($photos), $sellerName, $phone, $address]);
    echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error']);
}
?>

