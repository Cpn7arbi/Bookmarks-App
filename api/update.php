<?php
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: http://localhost:3000');
    header('Access-Control-Allow-Methods: PUT, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    http_response_code(200);
    exit();
}

// Headers
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

include_once '../db/Database.php';
include_once '../models/Bookmark.php';

$database = new Database();
$dbConnection = $database->connect();

$bookmark = new Bookmark($dbConnection);

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id']) || empty($data['id']) || !isset($data['title']) || !isset($data['link'])) {
    http_response_code(422);
    echo json_encode(['message' => 'Error: Missing required parameters for update.']);
    return;
}

$bookmark->setId($data['id']);
$bookmark->setTitle($data['title']);
$bookmark->setLink($data['link']); 

// Update the bookmark
if ($bookmark->update()) {
    echo json_encode(['message' => 'Bookmark updated successfully']);
} else {
    echo json_encode(['message' => 'Error: Bookmark could not be updated']);
}
?>
