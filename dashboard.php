<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];

include 'db_connect.php';

$stmt = $conn->prepare("SELECT long_url, short_alias, clicks, referral_sources FROM urls WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($longUrl, $shortAlias, $clicks, $referralSources);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px 12px;
            border: 1px solid #ccc;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <h1>User Dashboard</h1>
    <table>
        <thead>
            <tr>
                <th>Long URL</th>
                <th>Short URL</th>
                <th>Clicks</th>
                <th>Referral Sources</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($stmt->fetch()): ?>
            <tr>
                <td><?= htmlspecialchars($longUrl) ?></td>
                <td><a href="<?= htmlspecialchars($shortAlias) ?>" target="_blank">http://localhost/<?= htmlspecialchars($shortAlias) ?></a></td>
                <td><?= htmlspecialchars($clicks) ?></td>
                <td>
                    <?php 
                    $referralSourcesArray = json_decode($referralSources, true);
                    if (is_array($referralSourcesArray) && !empty($referralSourcesArray)) {
                        foreach ($referralSourcesArray as $source => $count) {
                            echo htmlspecialchars($source) . ": " . htmlspecialchars($count) . "<br>";
                        }
                    } else {
                        echo "No referral data available";
                    }
                    ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

<?php
$stmt->close();
$conn->close();
?>
</body>
</html>
