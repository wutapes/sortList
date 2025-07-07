<?php
$sortedLines = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['textfile'])) {
    $file = $_FILES['textfile']['tmp_name'];

    if (is_uploaded_file($file)) {
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        sort($lines, SORT_NATURAL | SORT_FLAG_CASE); // Alphabetical sort
        $sortedLines = $lines;

        // Save to a temp file for download
        $downloadFile = 'sorted_output.txt';
        file_put_contents($downloadFile, implode(PHP_EOL, $lines));
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload and Sort Text File</title>
</head>
<body>
    <h1>Upload and Sort Text File Alphabetically</h1>

    <form method="POST" enctype="multipart/form-data">
        <label>Select .txt file:
            <input type="file" name="textfile" accept=".txt" required>
        </label>
        <button type="submit">Upload & Sort</button>
    </form>

    <?php if (!empty($sortedLines)): ?>
        <h2>Sorted Lines:</h2>
        <pre><?php echo htmlspecialchars(implode(PHP_EOL, $sortedLines)); ?></pre>

        <form method="get" action="sorted_output.txt">
            <button type="submit">Download Sorted File</button>
        </form>
    <?php endif; ?>
</body>
</html>