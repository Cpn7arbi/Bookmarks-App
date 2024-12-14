<?php
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: http://localhost:3000');
    header('Access-Control-Allow-Methods: DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    http_response_code(200);
    exit();
}

// Headers
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    http_response_code(422);
    echo json_encode(['message' => 'Error: Missing required query parameter id.']);
    return;
}

include_once '../db/Database.php';
include_once '../models/Bookmark.php';

$database = new Database();
$dbConnection = $database->connect();

$bookmark = new Bookmark($dbConnection);
$id = $_GET['id'];

$bookmark->setId($id);

if ($bookmark->delete()) {
    echo json_encode(['message' => 'A bookmark was deleted']);
} else {
    echo json_encode(['message' => 'Error: A bookmark was not deleted']);
}
