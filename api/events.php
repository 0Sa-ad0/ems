<?php
require '../config/db.php';

header("Content-Type: application/json");

$response = ["status" => "error", "message" => "Invalid request"];

try {
    $event_id = filter_input(INPUT_GET, 'event_id', FILTER_VALIDATE_INT);

    if ($event_id) {
        $stmt = $pdo->prepare("SELECT id, name, description, max_capacity, created_by FROM events WHERE id = ?");
        $stmt->execute([$event_id]);
        $event = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($event) {
            $response = ["status" => "success", "event" => $event];
        } else {
            $response["message"] = "Event not found";
        }
    } else {
        $stmt = $pdo->query("SELECT id, name, description, max_capacity, created_by FROM events");
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($events) {
            $response = ["status" => "success", "events" => $events];
        } else {
            $response["message"] = "No events found";
        }
    }
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage()); 
    $response["message"] = "An internal error occurred. Please try again later.";
}

echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
?>