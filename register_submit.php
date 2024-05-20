<?php
include 'db_connect.php';

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header('Location: login.php');
        exit;
    } else {
        echo "Error: Unable to register user.";
    }
} else {
    echo "Error: Username or password not provided.";
}
?>
