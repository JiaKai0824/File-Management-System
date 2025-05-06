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
$password = $data->password;

// Modify SQL query to include role_id
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {
    // Include role_id in the response so the frontend can use it
    $user_data = [
        "id" => $user['id'],
        "username" => $user['username'],
        "email" => $user['email'],
        "role_id" => $user['role_id']  // Add role_id here
    ];
    echo json_encode(["success" => true, "message" => "Login successful", "user" => $user_data]);
} else {
    echo json_encode(["success" => false, "message" => "Invalid username or password"]);
}

$conn->close();
?>
