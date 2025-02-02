<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: auth/login.php");
    exit;
}

require 'config/db.php';
require 'includes/header.php';

$stmt = $pdo->prepare("SELECT * FROM events WHERE created_by = ?");
$stmt->execute([$_SESSION["user_id"]]);
$events = $stmt->fetchAll();
?>

<div class="container mt-5">
    <h1 class="text-center">Your Events</h1>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <p class="text-muted">Manage your created events</p>
        <a href="events/create.php" class="btn btn-primary">+ Create Event</a>
    </div>

    <?php if (count($events) === 0): ?>
        <div class="alert alert-warning text-center">No events created yet.</div>
    <?php else: ?>
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Event Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $event): ?>
                    <tr>
                        <td><?= htmlspecialchars($event["name"]) ?></td>
                        <td><?= htmlspecialchars($event["description"]) ?></td>
                        <td>
                            <a href="events/edit.php?id=<?= $event['id'] ?>" class="btn btn-sm btn-warning">✏️ Edit</a>
                            <button class="btn btn-sm btn-danger delete-btn" data-event-id="<?= $event['id'] ?>">🗑
                                Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<script>
    document.querySelectorAll(".delete-btn").forEach(button => {
        button.addEventListener("click", function () {
            if (confirm("Are you sure you want to delete this event?")) {
                let eventId = this.getAttribute("data-event-id");

                fetch("events/delete.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: "id=" + eventId
                })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        if (data.status === "success") location.reload();
                    });
            }
        });
    });
</script>

<?php require 'includes/footer.php'; ?>