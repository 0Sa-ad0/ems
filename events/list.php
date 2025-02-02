<?php
session_start();
require '../config/db.php';
require '../includes/header.php';

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "user") {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['query'])) {
    $query = "%" . $_GET['query'] . "%";
    $stmt = $pdo->prepare("SELECT * FROM events WHERE name LIKE :query OR description LIKE :query");
    $stmt->execute(['query' => $query]);
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $stmt = $pdo->query("SELECT * FROM events");
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<div class="container mt-5">
    <h1 class="text-center">Available Events</h1>
    <input type="text" id="searchBox" class="form-control mb-3" placeholder="Search events...">

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>Event Name</th>
                <th>Description</th>
                <th>Max Capacity</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="eventsTableBody">
            <?php if (empty($events)): ?>
                <tr>
                    <td colspan="4" class="text-center">No events found</td>
                </tr>
            <?php else: ?>
                <?php foreach ($events as $event): ?>
                    <tr>
                        <td><?= htmlspecialchars($event['name']) ?></td>
                        <td><?= htmlspecialchars($event['description']) ?></td>
                        <td><?= htmlspecialchars($event['max_capacity']) ?></td>
                        <td><button class="btn btn-primary btn-sm register-btn"
                                data-event-id="<?= $event['id'] ?>">Register</button></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    function fetchEvents(query = '') {
        fetch('events.php?query=' + query)
            .then(response => response.json())
            .then(data => {
                let tableBody = document.getElementById("eventsTableBody");
                tableBody.innerHTML = "";

                if (data.length === 0) {
                    tableBody.innerHTML = "<tr><td colspan='4' class='text-center'>No events found</td></tr>";
                } else {
                    data.forEach(event => {
                        let row = `<tr>
                            <td>${event.name}</td>
                            <td>${event.description}</td>
                            <td>${event.max_capacity}</td>
                            <td><button class="btn btn-primary btn-sm register-btn" data-event-id="${event.id}">Register</button></td>
                        </tr>`;
                        tableBody.innerHTML += row;
                    });
                }
            });
    }

    document.getElementById("searchBox").addEventListener("keyup", function () {
        fetchEvents(this.value);
    });

    fetchEvents();
</script>

<?php require '../includes/footer.php'; ?>