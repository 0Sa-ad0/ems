<?php
require '../config/db.php';

$event_id = filter_input(INPUT_GET, 'event_id', FILTER_VALIDATE_INT);
if (!$event_id) {
    die("Invalid event ID");
}

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="attendees.csv"');

ob_clean();
flush();

$output = fopen("php://output", "w");
if (!$output) {
    die("Failed to open output stream");
}

fputcsv($output, ["ID", "Event Name", "Attendee Name", "Registered At"]);

$query = "SELECT attendees.id, events.name AS event_name, users.name AS user_name, attendees.registered_at 
          FROM attendees 
          JOIN events ON attendees.event_id = events.id 
          JOIN users ON attendees.user_id = users.id 
          WHERE attendees.event_id = ?";

$stmt = mysqli_prepare($conn, $query);
if (!$stmt) {
    die("Query preparation failed: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "i", $event_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    die("Query execution failed: " . mysqli_error($conn));
}

$hasData = false;
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, $row);
    $hasData = true;
}

if (!$hasData) {
    fputcsv($output, ["No attendees found for this event"]);
}

fclose($output);
mysqli_stmt_close($stmt);
mysqli_close($conn);
exit;
?>