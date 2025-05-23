<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
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