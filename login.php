<?php
session_start();
$login_message = '';
$register_message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $conn = new mysqli("localhost", "root", "", "project");

    if ($conn->connect_error) {
        if (isset($_POST['login_submit'])) {
            $login_message = "Database connection failed.";
        } elseif (isset($_POST['register_submit'])) {
            $register_message = "Database connection failed.";
        }
    } else {
        if (isset($_POST['login_submit'])) {
            if (isset($_POST['username'], $_POST['password'])) {
                $username = $_POST['username'];
                $password = $_POST['password'];

                $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    $stmt->bind_result( $dbPassword);
                    $stmt->fetch();

                    if ($password === $dbPassword) {
                        $_SESSION['username'] = $username;
                        $_SESSION['user_id'] = $id;
                        header("Location: index.php");
                        exit();
                    } else {
                        $login_message = "Incorrect password.";
                    }
                } else {
                    $login_message = "User not found.";
                }
                $stmt->close();
            } else {
                $login_message = "Username and password are required.";
            }
        } elseif (isset($_POST['register_submit'])) {
            if (isset($_POST['username'], $_POST['email'], $_POST['password'])) {
                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = $_POST['password'];

                if (empty($username) || empty($email) || empty($password)) {
                    $register_message = "All fields are required for registration.";
                } else {
                    $stmt_check = $conn->prepare("SELECT username FROM users WHERE username = ? OR email = ?");
                    $stmt_check->bind_param("ss", $username, $email);
                    $stmt_check->execute();
                    $stmt_check->store_result();

                    if ($stmt_check->num_rows > 0) {
                        $register_message = "Username or email already exists.";
                    } else {
                        $stmt_insert = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                        $stmt_insert->bind_param("sss", $username, $email, $password);

                        if ($stmt_insert->execute()) {
                            $register_message = "Registration successful! You can now login.";
                        } else {
                            $register_message = "Registration failed. Please try again.";
                        }
                        $stmt_insert->close();
                    }
                    $stmt_check->close();
                }
            } else {
                $register_message = "Please fill in all registration fields.";
            }
        }
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login & Registration</title>
    <link rel="stylesheet" href="css/login.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div class="form-container">
    <h2>Login</h2>
    <form id="login-form" method="POST" action="login.php">
        <label for="login-username">Username</label>
        <input type="text" id="login-username" name="username" required>

        <label for="login-password">Password</label>
        <input type="password" id="login-password" name="password" required>

        <input type="submit" name="login_submit" value="Login">
        <div id="login-message" class="message"><?php if (!empty($login_message)) { echo htmlspecialchars($login_message); } ?></div>
    </form>
</div>

<div class="form-container">
    <h2>Register</h2>
    <form id="register-form" method="POST" action="login.php">
        <label for="reg-username">Username</label>
        <input type="text" id="reg-username" name="username" required>

        <label for="reg-email">Email</label>
        <input type="email" id="reg-email" name="email" required>

        <label for="reg-password">Password</label>
        <input type="password" id="reg-password" name="password" required>

        <input type="submit" name="register_submit" value="Register">
        <div id="register-message" class="message"><?php if (!empty($register_message)) { echo htmlspecialchars($register_message); } ?></div>
    </form>
</div>

<a href="index.php" class="back-button">← Back</a>

</body>
</html>