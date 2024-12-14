<?php
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: http://localhost:3000');
    header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    http_response_code(200);
    exit();
}
//Headers
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

    // Get the ID from the request
    if (!isset($_GET['id'])) {
        http_response_code(422);
        echo json_encode(['message' => 'Error: Missing required query parameter id.']);
        return;
    }

    $bookmark->setId($_GET['id']);

    // Try to read the bookmark
    if ($bookmark->readOne()) {
        $result = [
            'id' => $bookmark->getId(),
            'title' => $bookmark->getTitle(),
            'link' => $bookmark->getLink(),
            'dateAdded' => $bookmark->getDateAdded()
        ];
        echo json_encode($result);
    } else {
        http_response_code(404);
        echo json_encode(['message' => 'Error: Bookmark not found.']);
    }
}
?>
