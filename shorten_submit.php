<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include 'db_connect.php';

$longUrl = $_POST['long_url'];
$shortAlias = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
$userId = $_SESSION['user_id'];

// Check for unique short alias
$stmt = $conn->prepare("SELECT id FROM urls WHERE short_alias = ?");
$stmt->bind_param("s", $shortAlias);
$stmt->execute();
$stmt->store_result();
while ($stmt->num_rows > 0) {
    $shortAlias = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
    $stmt->bind_param("s", $shortAlias);
    $stmt->execute();
    $stmt->store_result();
}
$stmt->close();

// Initialize referral sources as an empty JSON object
$referralSources = json_encode([]);

// Insert the new short URL
$stmt = $conn->prepare("INSERT INTO urls (long_url, short_alias, user_id, referral_sources) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssis", $longUrl, $shortAlias, $userId, $referralSources);
$stmt->execute();
$stmt->close();
$conn->close();

header('Location: dashboard.php');
?>
