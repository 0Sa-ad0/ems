<?php
session_start();
if ($_SESSION["role"] !== "admin") {
    header("Location: ../auth/login.php");
    exit;
}

require '../config/db.php';
require '../includes/header.php';

$query = "SELECT events.id, events.name, COUNT(attendees.id) AS total_attendees 
          FROM events 
          LEFT JOIN attendees ON events.id = attendees.event_id 
          GROUP BY events.id, events.name";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
?>

<h1 class="text-center my-4">Admin Dashboard - Events</h1>

<div class="container">
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>Event Name</th>
                <th>Total Attendees</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($event = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($event["name"]) ?></td>
                    <td><?= $event["total_attendees"] ?></td>
                    <td>
                        <a href="download_csv.php?event_id=<?= $event['id'] ?>" class="btn btn-success btn-sm">
                            📥 Download CSV
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
            <?php if (mysqli_num_rows($result) === 0): ?>
                <tr>
                    <td colspan="3">No events available.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require '../includes/footer.php'; ?>