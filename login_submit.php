<?php
session_start();
include 'db_connect.php';

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($userId, $hashedPassword);
    $stmt->fetch();
    $stmt->close();

    if ($hashedPassword && password_verify($password, $hashedPassword)) {
        $_SESSION['user_id'] = $userId;
        header('Location: dashboard.php');
    } else {
        echo "Invalid credentials";
    }
} else {
    echo "Username or password not provided";
}

$conn->close();
?>
