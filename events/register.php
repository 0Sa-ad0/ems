<?php
session_start();
require '../config/db.php';

$response = ["status" => "error", "message" => ""];

if (!isset($_SESSION["user_id"])) {
    echo json_encode($response);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_id = $_POST["event_id"];
    $user_id = $_SESSION["user_id"];

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM attendees WHERE event_id = ?");
    $stmt->execute([$event_id]);
    $attendee_count = $stmt->fetchColumn();

    $stmt = $pdo->prepare("SELECT max_capacity FROM events WHERE id = ?");
    $stmt->execute([$event_id]);
    $max_capacity = $stmt->fetchColumn();

    if ($attendee_count < $max_capacity) {
        $stmt = $pdo->prepare("INSERT INTO attendees (event_id, user_id) VALUES (?, ?)");
        if ($stmt->execute([$event_id, $user_id])) {
            $response = ["status" => "success", "message" => "Registered successfully!"];
        }
    } else {
        $response = ["status" => "error", "message" => "Event is full!"];
    }
}

echo json_encode($response);
?>

<script>
    document.addEventListener("click", function (event) {
        if (event.target.classList.contains("register-btn")) {
            let eventId = event.target.getAttribute("data-event-id");

            fetch("register.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "event_id=" + eventId
            })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    if (data.status === "success") {
                        event.target.textContent = "Registered";
                        event.target.disabled = true;
                    }
                });
        }
    });
</script>