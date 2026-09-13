<?php
session_start();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $message = 'Please enter both username and password.';
    } else {
        $_SESSION['username'] = $username;
        $_SESSION['is_logged_in'] = true;
        // Replace this with database authentication in a real application.
        $message = 'Login submitted successfully.';
        if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
            header('Location: dashboard.php');
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .login-form { max-width: 350px; margin: 80px auto; padding: 24px; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #ccc; }
        h1 { text-align: center; }
        label { display: block; margin-top: 14px; }
        input { box-sizing: border-box; width: 100%; padding: 10px; margin-top: 6px; }
        button { width: 100%; padding: 10px; margin-top: 20px; color: #fff; background: #007bff; border: 0; border-radius: 4px; cursor: pointer; }
        .message { margin-top: 16px; text-align: center; color: #333; }
    </style>
</head>
<body>
    <form class="login-form" method="post" action="">
        <h1>User Login Test</h1>

        <label for="username">Username</label>
        <input type="text" id="username" name="username" required autocomplete="username">

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required autocomplete="current-password">

        <button type="submit">Log In</button>

        <?php if ($message !== ''): ?>
            <p class="message"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
    </form>
</body>
</html>