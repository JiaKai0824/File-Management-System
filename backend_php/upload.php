<?php
header("Access-Control-Allow-Origin: *"); // Allow requests from any origin (adjust as needed)
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Allow specific HTTP methods
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Allow specific headers

$host = 'localhost';
$db = 'file_manager_db';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
  $file = $_FILES['file'];
  $filename = $file['name'];
  $filetype = $file['type'];
  $filesize = $file['size'];
  $filedata = file_get_contents($file['tmp_name']);
  $stmt = $conn->prepare("INSERT INTO files (filename, filetype, filesize, filedata) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("ssis", $filename, $filetype, $filesize, $filedata);
  $stmt->send_long_data(3, $filedata);
  $stmt->execute();

  $target_path = $upload_dir . basename($filename);
  if (move_uploaded_file($tmp_path, $target_path)) {
    $stmt = $conn->prepare("INSERT INTO files (filename, filetype, filesize) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $filename, $filetype, $filesize);
    $stmt->execute();

    echo json_encode(["success" => true, "message" => "File uploaded successfully."]);
  } else {
    echo json_encode(["success" => false, "message" => "Failed to upload file."]);
  }
} else {
  echo json_encode(["success" => false, "message" => "Invalid request."]);
}
?>
