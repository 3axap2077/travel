<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "project");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle actions if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_user'])) {
        $username = $_POST['username'];
        $stmt = $conn->prepare("DELETE FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
    } elseif (isset($_POST['toggle_admin'])) {
        $username = $_POST['username'];
        // Get current admin status
        $stmt = $conn->prepare("SELECT admin FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($current_admin);
        $stmt->fetch();
        $stmt->close();

        // Toggle admin status
        $new_admin = $current_admin ? 0 : 1;
        $stmt = $conn->prepare("UPDATE users SET admin = ? WHERE username = ?");
        $stmt->bind_param("is", $new_admin, $username);
        $stmt->execute();
    }

    // Refresh the page to show updated data
    header("Location: admin.php");
    exit();
}

// Fetch all users
$users = $conn->query("SELECT username, email, admin FROM users");
if (!$users) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>User Management</h2>
    <form method="post">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Admin Status</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php while($user = $users->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= $user['admin'] ? 'Yes' : 'No' ?></td>
                    <td>
                        <button type="submit" name="toggle_admin" value="1" class="btn btn-sm <?= $user['admin'] ? 'btn-warning' : 'btn-success' ?>">
                            <?= $user['admin'] ? 'Remove Admin' : 'Make Admin' ?>
                        </button>
                        <button type="submit" name="delete_user" value="1" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">
                            Delete User
                        </button>
                        <input type="hidden" name="username" value="<?= htmlspecialchars($user['username']) ?>">
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </form>
</div>
</body>
</html>