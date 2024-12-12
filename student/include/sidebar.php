<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>
    .sidebar {
        width: 250px;
        background: linear-gradient(150deg, #285260, #b4d7d8);
        color: #fff;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .sidebar.collapsed {
        width: 70px;
    }

    .sidebar .nav-link {
        color: #fff;
        font-weight: 500;
        padding: 10px 15px;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
    }

    .sidebar .nav-link i {
        font-size: 20px;
    }

    .sidebar .nav-link:hover {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 4px;
    }

    .sidebar .nav-link.active {
        background: rgba(255, 255, 255, 0.2);
    }

    .sidebar .toggle-btn {
        cursor: pointer;
        margin: 10px;
        color: #fff;
        font-size: 20px;
        text-align: center;
    }

    .sidebar .badge {
        background: #ff0000;
        color: #fff;
        font-size: 12px;
        border-radius: 50%;
        padding: 3px 7px;
        animation: pulse 1.5s infinite;
    }

    .sidebar.collapsed .nav-link span {
        display: none;
    }

    body {
        background-color: #E0D7CF;
        font-family: 'Poppins', sans-serif;
    }

    @keyframes pulse {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.2);
        }
    }
</style>
<nav class="sidebar" id="sidebar">
    <div class="toggle-btn" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </div>
    <a href="student.php"
        class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'student.php' ? 'active' : ''; ?>">
        <i class="fas fa-home"></i>
        <span>Dashboard</span>
    </a>
    <a href="notifications.php"
        class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'notifications.php' ? 'active' : '' ?>">
        <i class="fas fa-bell"></i>
        <span>Notifications</span>
        <?php if ($notification_count > 0): ?>
            <span class="badge"><?= $notification_count ?></span>
        <?php endif; ?>
    </a>
    <a href="messages.php"
        class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'messages.php' ? 'active' : ''; ?>">
        <i class="fas fa-envelope"></i>
        <span>Messages</span>
    </a>
    <a href="../logout.php"
        class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'logout.php' ? 'active' : ''; ?>">
        <i class="fas fa-sign-out-alt"></i>
        <span>Logout</span>
    </a>
</nav>