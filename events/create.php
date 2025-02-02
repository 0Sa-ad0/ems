<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "user") {
    header("Location: ../index.php");
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $description = trim($_POST["description"]);
    $max_capacity = filter_var($_POST["max_capacity"], FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
    $created_by = $_SESSION["user_id"];

    if (!$name || !$description || !$max_capacity) {
        $message = "All fields are required and capacity must be a positive number.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO events (name, description, max_capacity, created_by) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$name, $description, $max_capacity, $created_by])) {
                header("Location: list.php?success=created");
                exit;
            } else {
                $message = "Error creating event.";
            }
        } catch (PDOException $e) {
            $message = "Database error: " . htmlspecialchars($e->getMessage());
        }
    }
}

require '../includes/header.php';
?>

<div class="container mt-5">
    <h2>Create Event</h2>
    <?php if ($message): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <form id="createEventForm" method="POST" class="border p-4 shadow-lg rounded bg-light">
        <div class="mb-3">
            <label class="form-label">Event Name:</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Event Name" required>
            <div id="nameError" class="text-danger mt-2" style="display: none;">Please enter an event name.</div>
        </div>
        <div class="mb-3">
            <label class="form-label">Description:</label>
            <textarea name="description" id="description" class="form-control" placeholder="Event Description"
                required></textarea>
            <div id="descriptionError" class="text-danger mt-2" style="display: none;">Please provide a description.
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Max Capacity:</label>
            <input type="number" name="max_capacity" id="max_capacity" class="form-control" placeholder="Max Capacity"
                required min="1">
            <div id="maxCapacityError" class="text-danger mt-2" style="display: none;">Please enter a valid capacity.
            </div>
        </div>
        <button type="submit" class="btn btn-primary w-100">Create Event</button>
    </form>
</div>

<script>
    document.getElementById("createEventForm").addEventListener("submit", function (event) {
        let name = document.getElementById("name").value.trim();
        let description = document.getElementById("description").value.trim();
        let max_capacity = document.getElementById("max_capacity").value;
        let isValid = true;

        document.getElementById("nameError").style.display = "none";
        document.getElementById("descriptionError").style.display = "none";
        document.getElementById("maxCapacityError").style.display = "none";

        if (!name) {
            document.getElementById("nameError").style.display = "block";
            isValid = false;
        }

        if (!description) {
            document.getElementById("descriptionError").style.display = "block";
            isValid = false;
        }

        if (!max_capacity || max_capacity <= 0) {
            document.getElementById("maxCapacityError").style.display = "block";
            isValid = false;
        }

        if (!isValid) {
            event.preventDefault();
        }
    });
</script>

<?php require '../includes/footer.php'; ?>