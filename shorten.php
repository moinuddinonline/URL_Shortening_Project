<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shorten URL</title>
</head>
<body>
    <h1>Shorten URL</h1>
    <nav>
        <a href="dashboard.php">Dashboard</a> |
        <a href="logout.php">Logout</a>
    </nav>
    <br>
    <form action="shorten_submit.php" method="POST">
        <label for="long_url">Enter Long URL:</label>
        <input type="url" id="long_url" name="long_url" required>
        <button type="submit">Shorten</button>
    </form>
</body>
</html>
