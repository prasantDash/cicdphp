<?php
session_start();

if (isset($_GET['logout'])) {
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }

    session_destroy();
    header('Location: index.php');
    exit;
}

$pageTitle = 'Dashboard';
$userName = $_SESSION['username'] ?? 'Guest';
$stats = [
    ['label' => 'Total Users', 'value' => '1,248'],
    ['label' => 'Orders', 'value' => '356'],
    ['label' => 'Revenue', 'value' => '$12,480'],
    ['label' => 'Pending Tasks', 'value' => '18'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Arial, sans-serif; background: #f4f6f9; color: #1f2937; }
        .layout { display: flex; min-height: 100vh; }
        .sidebar { width: 230px; padding: 24px 16px; background: #1e3a8a; color: white; }
        .sidebar h2 { margin: 0 0 32px; font-size: 22px; }
        .sidebar a { display: block; padding: 12px; margin: 6px 0; border-radius: 6px; color: #dbeafe; text-decoration: none; }
        .sidebar a:hover, .sidebar a.active { background: #2563eb; color: white; }
        .content { flex: 1; padding: 28px; }
        header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; }
        header h1 { margin: 0 0 6px; font-size: 28px; }
        header p { margin: 0; color: #6b7280; }
        .profile { padding: 10px 16px; border-radius: 20px; background: white; box-shadow: 0 2px 8px #0000000d; }
        .stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; }
        .card, .panel { padding: 22px; background: white; border-radius: 10px; box-shadow: 0 2px 8px #0000000d; }
        .card h3 { margin: 0 0 12px; color: #6b7280; font-size: 14px; font-weight: normal; }
        .card strong { font-size: 27px; }
        .panel { margin-top: 24px; }
        .panel h2 { margin-top: 0; font-size: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 13px 8px; border-bottom: 1px solid #e5e7eb; text-align: left; }
        th { color: #6b7280; font-size: 13px; }
        .status { color: #15803d; font-weight: bold; }
        @media (max-width: 800px) { .sidebar { width: 180px; } .stats { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 550px) { .layout { display: block; } .sidebar { width: 100%; } .stats { grid-template-columns: 1fr; } header { align-items: flex-start; gap: 12px; flex-direction: column; } }
    </style>
</head>
<body>
    <div class="layout">
        <aside class="sidebar">
            <h2>My Dashboard</h2>
            <a class="active" href="dashboard.php">Dashboard</a>
            <a href="#users">Users</a>
            <a href="#orders">Orders</a>
            <a href="#settings">Settings</a>
            <a href="dashboard.php?logout=1">Logout</a>
        </aside>

        <main class="content">
            <header>
                <div>
                    <h1>Dashboard</h1>
                    <p>Welcome back, <?= htmlspecialchars($userName) ?>.</p>
                </div>
                <div class="profile">👤 <?= htmlspecialchars($userName) ?></div>
            </header>

            <section class="stats">
                <?php foreach ($stats as $stat): ?>
                    <div class="card">
                        <h3><?= htmlspecialchars($stat['label']) ?></h3>
                        <strong><?= htmlspecialchars($stat['value']) ?></strong>
                    </div>
                <?php endforeach; ?>
            </section>

            <section class="panel">
                <h2>Recent Activity</h2>
                <table>
                    <thead><tr><th>Activity</th><th>Date</th><th>Status</th></tr></thead>
                    <tbody>
                        <tr><td>New user registered</td><td>Today</td><td class="status">Complete</td></tr>
                        <tr><td>Order #1042 processed</td><td>Yesterday</td><td class="status">Complete</td></tr>
                        <tr><td>Monthly report generated</td><td>Yesterday</td><td class="status">Complete</td></tr>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
</body>
</html>