<?php
require 'config/db.php';
require 'includes/header.php';

$stmt = $pdo->query("SELECT * FROM events");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Upcoming Events</h1>

    <?php if (empty($events)): ?>
        <div class="alert alert-warning text-center">No upcoming events.</div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($events as $event): ?>
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($event["name"]) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($event["description"]) ?></p>
                            <button class="btn btn-primary w-100 register-btn"
                                data-event-id="<?= $event["id"] ?>">Register</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
    document.querySelectorAll(".register-btn").forEach(button => {
        button.addEventListener("click", function () {
            let eventId = this.getAttribute("data-event-id");

            fetch("events/register.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "event_id=" + eventId
            })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    if (data.status === "success") location.reload();
                });
        });
    });
</script>

<?php require 'includes/footer.php'; ?>