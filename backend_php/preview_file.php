<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization"); 

$host = 'localhost';
$db = 'file_manager_db';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
  $id = intval($_GET['id']);

  $stmt = $conn->prepare("SELECT filename, filetype, filedata FROM files WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $stmt->store_result();
  $stmt->bind_result($filename, $filetype, $filedata);
  $stmt->fetch();

  header("Content-Type: $filetype");
  header("Content-Disposition: inline; filename=\"$filename\"");
  echo $filedata;
} else {
  http_response_code(400);
  echo "Invalid request";
}
?>
