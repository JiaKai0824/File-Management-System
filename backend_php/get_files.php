<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$host = 'localhost';
$db = 'file_manager_db';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die(json_encode(['message' => 'Database connection failed']));
}

$sql = "SELECT id, filename, filetype, filesize, uploaded_at FROM files";
$result = $conn->query($sql);

$files = [];
while ($row = $result->fetch_assoc()) {
    $files[] = [
        'id' => $row['id'],
        'name' => $row['filename'],
        'type' => $row['filetype'],
        'size' => $row['filesize'],
        'uploaded_at' => $row['uploaded_at'],
    ];
}

echo json_encode(['files' => $files]);
?>
