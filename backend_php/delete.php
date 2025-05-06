<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");
$host = 'localhost';
$db = 'file_manager_db';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
  die(json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]));
}
  
$input = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($input['id'])) {
  $file_id = intval($input['id']);
  $stmt = $conn->prepare("SELECT * FROM files WHERE id = ?");
  $stmt->bind_param("i", $file_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $file = $result->fetch_assoc();

  if ($file) {
    $filename = $file['filename'];
    $file_path = "uploads/" . $filename;

    if (file_exists($file_path)) {
      unlink($file_path);
    }
    $stmt = $conn->prepare("DELETE FROM files WHERE id = ?");
    $stmt->bind_param("i", $file_id);
    $stmt->execute();

    echo json_encode(["success" => true, "message" => "File deleted successfully."]);
  } else {
    echo json_encode(["success" => false, "message" => "File not found."]);
  }
} else {
  echo json_encode(["success" => false, "message" => "Invalid request."]);
}
?>
