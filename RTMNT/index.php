<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RTMNT</title>
    <link rel="stylesheet" href="https://www.omega-prime.pictures/html_/minos.css">
</head>
<body>
    <h1>RTMNT Application</h1>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = $_POST['input'] ?? '';
    $escapedInput = escapeshellarg($input);
    $command = "./RTMNT $escapedInput";
    $output = shell_exec($command . ' 2>&1');
    echo "<div class='output'>";
    echo "<h2>Output:</h2>";
    echo "<pre>" . htmlspecialchars($output) . "</pre>";
    echo "</div>";
}
?>

    <form method="POST" action="">
        <label for="input">Input:</label>
        <input type="text" id="input" name="input" required>
        <button type="submit">Run RTMNT</button>
    </form>
</body>
</html>
