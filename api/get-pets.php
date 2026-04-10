<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require 'config.php';

try {
    $stmt = $pdo->prepare("SELECT * FROM pets WHERE is_adopted = 0 ORDER BY created_at DESC");
    $stmt->execute();
    $pets = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($pets);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}
?>

