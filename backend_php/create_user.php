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

$data = json_decode(file_get_contents("php://input"));
$username = $data->username;
$email = $data->email;
$password = $data->password;
$role = $data->role;

$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Username already exists"]);
    exit();
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$sql = "INSERT INTO users (username, email, password, role_id) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $username, $email, $hashed_password, $role);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "User created successfully"]);
} else {
    echo json_encode([
        "success" => false, 
        "message" => "User creation failed: " . $conn->error . " SQL: " . $sql
    ]);
}

$conn->close();
?>
