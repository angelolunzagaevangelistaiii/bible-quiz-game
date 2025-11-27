<?php
session_start();
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/functions.php';
requireLogin();
requireAdmin();

$msg = '';

if(isset($_POST['import'])) {
    $file = $_FILES['csv_file']['tmp_name'];
    if ($file) {
        $handle = fopen($file, 'r');
        $row = 0;
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if ($row == 0) { $row++; continue; } // skip header row
            // Expected CSV order: scripture_ref, question, option_a, option_b, option_c, option_d, correct
            $stmt = $mysqli->prepare("INSERT INTO questions 
                (scripture_ref, question, option_a, option_b, option_c, option_d, correct)
                VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6]);
            $stmt->execute();
            $row++;
        }
        fclose($handle);
        $msg = "CSV Imported successfully!";
    } else {
        $msg = "Please select a CSV file.";
    }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Import Quiz CSV</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="content">
    <h2>Import Quiz Questions via CSV</h2>
    <?php if($msg) echo "<p class='success'>$msg</p>"; ?>

    <form method="post" enctype="multipart/form-data">
        <label>Select CSV File:</label>
        <input type="file" name="csv_file" accept=".csv" required>
        <button type="submit" name="import">Import</button>
    </form>

    <p><a href="index.php">Back to Admin Dashboard</a></p>
</div>
</body>
</html>
