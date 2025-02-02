<?php
require '../config/db.php';

$search = $_GET['query'] ?? '';
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
        fetch('search.php?query=' + query)
            .then(response => response.json())
            .then(data => {
                let results = document.getElementById("searchResults");
                results.innerHTML = "";
                data.forEach(event => {
                    let li = document.createElement("li");
                    li.textContent = event.name + " - " + event.description;
                    results.appendChild(li);
                });
            });
    });
</script>