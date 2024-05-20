<?php
include 'db_connect.php';

if (isset($_GET['alias'])) {
    // Add debugging statement here
    echo "Short Alias: " . $_GET['alias'];

    $shortAlias = $_GET['alias'];
    $stmt = $conn->prepare("SELECT long_url, referral_sources FROM urls WHERE short_alias = ?");
    $stmt->bind_param("s", $shortAlias);
    $stmt->execute();
    $stmt->bind_result($longUrl, $referralSources);
    $stmt->fetch();

    if ($longUrl) {
        // Update click count and referral source
        $stmt->close();

        $referralSource = isset($_SERVER['HTTP_REFERER']) ? parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) : 'direct';

        // Decode the current referral sources JSON
        $referralSourcesArray = json_decode($referralSources, true);
        if (isset($referralSourcesArray[$referralSource])) {
            $referralSourcesArray[$referralSource]++;
        } else {
            $referralSourcesArray[$referralSource] = 1;
        }
        $updatedReferralSources = json_encode($referralSourcesArray);

        $stmt = $conn->prepare("UPDATE urls SET clicks = clicks + 1, referral_sources = ? WHERE short_alias = ?");
        $stmt->bind_param("ss", $updatedReferralSources, $shortAlias);
        $stmt->execute();

        // Redirect to the long URL
        header("Location: $longUrl");
        exit;
    } else {
        echo "URL not found.";
    }

    $stmt->close();
} else {
    echo "Invalid URL.";
}

$conn->close();
?>
