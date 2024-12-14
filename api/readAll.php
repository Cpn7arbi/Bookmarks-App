<?php
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: http://localhost:3000');
    header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    http_response_code(200);
    exit();
}

header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    include_once '../db/Database.php';
    include_once '../models/Bookmark.php';

    // Instantiate Database and Bookmark
    $database = new Database();
    $dbConnection = $database->connect();
    $bookmark = new Bookmark($dbConnection);

    // Try to get all bookmarks
    $result = $bookmark->readAll();
    if (!empty($result)) {
        echo json_encode($result);
    } else {
        echo json_encode(['message' => 'No bookmarks found.']);
    }
}
?>
