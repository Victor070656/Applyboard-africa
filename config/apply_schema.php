<?php
// Custom connection for CLI to avoid socket issues
$conn = mysqli_connect("127.0.0.1", "root", "", "sdtravels");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = file_get_contents('student_loans_migration.sql');

if (mysqli_multi_query($conn, $sql)) {
    do {
        if ($result = mysqli_store_result($conn)) {
            while ($row = mysqli_fetch_row($result)) {
            }
            mysqli_free_result($result);
        }
    } while (mysqli_next_result($conn));
    echo "Schema applied successfully.\n";
} else {
    echo "Error applying schema: " . mysqli_error($conn) . "\n";
}
?>
