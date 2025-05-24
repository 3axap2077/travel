<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Assume you have a PDO or mysqli connection already established as $conn
$isAdmin = false;
if (isset($_SESSION['username'])) {
    // Replace with your actual DB connection
    $conn = new mysqli('localhost', 'root', '', 'project');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $username = $conn->real_escape_string($_SESSION['username']);
    $result = $conn->query("SELECT admin FROM users WHERE username = '$username' LIMIT 1");

    if ($result && $row = $result->fetch_assoc()) {
        $isAdmin = (int)$row['admin'] === 1;
    }

    $conn->close();
}
?>
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div class="single-page-nav sticky-wrapper" id="tmNavbar">
            <ul class="nav navbar-nav">
                <li><a href="index.php#section1">Homepage</a></li>
                <li><a href="index.php#section2">About Us</a></li>
                <li><a href="index.php#section3">Services</a></li>
                <li><a href="index.php#section4">Contact</a></li>
                <?php if (isset($_SESSION['username'])): ?>
                    <li><a href="logout.php" class="login">Logout</a></li>
                    <li><a href="?admin_check=1">Admin only</a></li>
                <?php else: ?>
                    <li><a href="login.php" class="login">Login</a></li>
                <?php endif; ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php if (isset($_SESSION['username'])): ?>
                    <li><a href="#"><strong>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></strong></a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<?php
// Handle "Admin only" link click
if (isset($_GET['admin_check'])) {
    if ($isAdmin) {
        header("Location: admin.php");
        exit();
    } else {
        echo "<script>alert('You are not an admin');</script>";
    }
}
?>
