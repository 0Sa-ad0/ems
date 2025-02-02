<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Event Management</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="index.php">Event Management</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <?php if (isset($_SESSION["user_id"])): ?>
                    <?php if ($_SESSION["role"] === "admin"): ?>
                        <li class="nav-item"><a class="nav-link" href="admin/dashboard.php">Admin Dashboard</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link" href="auth/logout.php">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="auth/login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="auth/register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    <div class="container mt-4">