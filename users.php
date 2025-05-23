<?php
session_start();
$conn = new mysqli("localhost", "root", "", "project");

$response = ['success' => false, 'message' => ''];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['username'], $_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $dbPassword);
            $stmt->fetch();

            if ($password === $dbPassword) {
                $response['success'] = true;
                $response['message'] = "Login successful!";
                $_SESSION['username'] = $username;
                $_SESSION['user_id'] = $id;
            } else {
                $response['message'] = "Incorrect password.";
            }
        } else {
            $response['message'] = "User not found.";
        }
        $stmt->close();
}

$conn->close();


?>