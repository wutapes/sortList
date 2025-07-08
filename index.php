<?php
session_start();

$sortedLines = [];
$downloadReady = false;
$downloadFilename = '';

function generateTempFilename() {
    return sys_get_temp_dir() . '/sorted_' . session_id() . '_' . time() . '.txt';
}

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['textfile'])) {
    $file = $_FILES['textfile']['tmp_name'];

    if (is_uploaded_file($file)) {
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        sort($lines, SORT_NATURAL | SORT_FLAG_CASE);
        $sortedLines = $lines;

        $tempFile = generateTempFilename();
        file_put_contents($tempFile, implode(PHP_EOL, $sortedLines));

        $_SESSION['download_file'] = $tempFile;
        $_SESSION['download_name'] = 'sorted_output.txt';
        $downloadReady = true;
    }
}

// Handle file download
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['download'])) {
    if (isset($_SESSION['download_file']) && file_exists($_SESSION['download_file'])) {
        $filepath = $_SESSION['download_file'];
        $filename = $_SESSION['download_name'] ?? basename($filepath);

        header('Content-Description: File Transfer');
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($filepath));
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: 0');
        readfile($filepath);

        // Cleanup
        unlink($filepath);
        unset($_SESSION['download_file'], $_SESSION['download_name']);
        exit;
    } else {
        echo "No file available for download.";
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload, Sort, Download Text File</title>
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
    <?php endif; ?>

    <?php if ($downloadReady): ?>
        <form method="POST">
            <input type="hidden" name="download" value="1">
            <button type="submit">Download Sorted File</button>
        </form>
    <?php endif; ?>
</body>
</html>