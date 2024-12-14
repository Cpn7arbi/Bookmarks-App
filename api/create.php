<?php
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: http://localhost:3000'); 
    header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, OPTIONS'); 
    header('Access-Control-Allow-Headers: Content-Type, Authorization'); 
    http_response_code(200);
    exit();
}
// Check Request Method
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Allow: POST');
    http_response_code(405);
    echo json_encode('Method Not Allowed');
    return;
}

// Headers
header('Access-Control-Allow-Origin: http://localhost:3000'); 
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, OPTIONS'); 
header('Access-Control-Allow-Headers: Content-Type, Authorization'); 

include_once '../db/Database.php';
include_once '../models/Bookmark.php';

// Instantiate Database object & connect
$database = new Database();
$dbConnection = $database->connect();

// Instantiate Bookmark object
$bookmark = new Bookmark($dbConnection);

// Get the HTTP POST request JSON body
$data = json_decode(file_get_contents("php://input"), true);
if(!$data || !isset($data['title']) || !isset($data['link'])){
    http_response_code(422);
    echo json_encode(
        array('message' => 'Error: Missing required parameters title or link in the JSON body.')
    );
    return;
}

$bookmark->setTitle($data['title']);
$bookmark->setLink($data['link']);

// Create Bookmark
if ($bookmark->create()) {
    echo json_encode(
        array('message' => 'A bookmark was created')
    );
} else {
    echo json_encode(
        array('message' => 'Error: a bookmark was not created')
    );
}
