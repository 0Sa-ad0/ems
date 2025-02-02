<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "user") {
    header("Location: ../index.php");
    exit;
}

$response = ["status" => "error", "message" => ""];

if (!isset($_SESSION["user_id"])) {
    echo json_encode($response);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["event_id"])) {
    $event_id = filter_var($_POST["event_id"], FILTER_SANITIZE_NUMBER_INT);
    $user_id = $_SESSION["user_id"];

    if (empty($event_id) || !filter_var($event_id, FILTER_VALIDATE_INT)) {
        $response = ["status" => "error", "message" => "Invalid event ID"];
        echo json_encode($response);
        exit;
    }

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

            if (!eventId || isNaN(eventId)) {
                alert("Invalid event ID");
                return;
            }

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
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("An error occurred. Please try again.");
                });
        }
    });
</script>