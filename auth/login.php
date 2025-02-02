<?php
session_start();
require '../config/db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format!";
    } elseif (empty($password)) {
        $message = "Password cannot be empty!";
    } else {
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if ($user && password_verify($password, $user["password"])) {
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["role"] = $user["role"];

            if ($user["role"] == "admin") {
                header("Location: ../admin/dashboard.php");
            } else {
                header("Location: ../dashboard.php");
            }
            exit;
        } else {
            $message = "Invalid email or password!";
        }
    }
}

require '../includes/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <h2 class="text-center">Login</h2>

            <?php if ($message): ?>
                <div class="alert alert-danger text-center"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <form id="loginForm" method="POST" class="border p-4 shadow-lg rounded bg-light">
                <div class="mb-3">
                    <label class="form-label">Email:</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email">
                    <div id="emailError" class="text-danger mt-2" style="display: none;">Please enter a valid email
                        address.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password:</label>
                    <input type="password" name="password" id="password" class="form-control"
                        placeholder="Enter your password">
                    <div id="passwordError" class="text-danger mt-2" style="display: none;">Password cannot be empty.
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById("loginForm").addEventListener("submit", function (event) {
        let email = document.getElementById("email").value;
        let password = document.getElementById("password").value;
        let isValid = true;

        document.getElementById("emailError").style.display = "none";
        document.getElementById("passwordError").style.display = "none";

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