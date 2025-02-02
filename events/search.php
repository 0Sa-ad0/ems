<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "user") {
    header("Location: ../index.php");
    exit;
}

$search = $_GET['query'] ?? '';

$search = filter_var($search, FILTER_SANITIZE_STRING);

$stmt = $pdo->prepare("SELECT * FROM events WHERE name LIKE ?");
$stmt->execute(["%$search%"]);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($events);
?>

<form method="GET" class="mb-4">
    <input type="text" name="query" placeholder="Search events..." class="form-control"
        value="<?= htmlspecialchars($search) ?>">
    <ul id="searchResults"></ul>
    <button type="submit" class="btn btn-primary mt-2">Search</button>
</form>

<script>
    document.querySelector('input[name="query"]').addEventListener("input", function () {
        let query = this.value;

        if (!query) {
            document.getElementById("searchResults").innerHTML = "";
            return;
        }

        fetch('search.php?query=' + encodeURIComponent(query))
            .then(response => response.json())
            .then(data => {
                let results = document.getElementById("searchResults");
                results.innerHTML = "";
                if (data.length === 0) {
                    results.innerHTML = "<li>No events found</li>";
                } else {
                    data.forEach(event => {
                        let li = document.createElement("li");
                        li.textContent = event.name + " - " + event.description;
                        results.appendChild(li);
                    });
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("An error occurred while searching for events.");
            });
    });
</script>