<?php
session_start();
require '../config/db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
    $role = $_POST["role"];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format!";
    } elseif (empty($name) || empty($password)) {
        $message = "All fields are required!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$name, $email, $password, $role])) {
            header("Location: login.php?success=registered");
            exit;
        } else {
            $message = "Registration failed!";
        }
    }
}

require '../includes/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <h2 class="text-center">Register</h2>

            <?php if ($message): ?>
                <div class="alert alert-danger text-center"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <form id="registerForm" method="POST" class="border p-4 shadow-lg rounded bg-light">
                <div class="mb-3">
                    <label class="form-label">Name:</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Enter your name">
                    <div id="nameError" class="text-danger mt-2" style="display: none;">Please enter your name.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email:</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email">
                    <div id="emailError" class="text-danger mt-2" style="display: none;">Please enter a valid email.
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password:</label>
                    <input type="password" name="password" id="password" class="form-control"
                        placeholder="Enter your password">
                    <div id="passwordError" class="text-danger mt-2" style="display: none;">Password cannot be empty.
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Role:</label>
                    <select name="role" id="role" class="form-control">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary w-100">Register</button>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById("registerForm").addEventListener("submit", function (event) {
        let name = document.getElementById("name").value;
        let email = document.getElementById("email").value;
        let password = document.getElementById("password").value;
        let isValid = true;

        document.getElementById("nameError").style.display = "none";
        document.getElementById("emailError").style.display = "none";
        document.getElementById("passwordError").style.display = "none";

        if (!name) {
            document.getElementById("nameError").style.display = "block";
            isValid = false;
        }

        if (!email || !validateEmail(email)) {
            document.getElementById("emailError").style.display = "block";
            isValid = false;
        }

        if (!password) {
            document.getElementById("passwordError").style.display = "block";
            isValid = false;
        }

        if (!isValid) {
            event.preventDefault();
        }
    });

    function validateEmail(email) {
        const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        return emailPattern.test(email);
    }
</script>

<?php require '../includes/footer.php'; ?>