<?php
session_start();
session_unset();
session_destroy();

// Prevent caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Logging out...</title>
</head>
<body>
    <script>
        // Clear localStorage keys related to mood recommendations
        localStorage.removeItem('selectedMood');
        localStorage.removeItem('recommendedBooks');
        localStorage.removeItem('moodSentence');

        // Redirect after clearing storage
        window.location.href = 'signin.php';
    </script>
    <p>Logging out, please wait...</p>
</body>
</html>
