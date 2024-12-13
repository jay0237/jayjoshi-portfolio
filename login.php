<?php
// Database connection details
include 'db_connect.php';

// Start a session
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get user input and sanitize
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);




    // Prepare the SQL query
    $sql = "SELECT * FROM login_info WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("SQL error: " . $conn->error);
    }

    $stmt->bind_param("s", $email);

    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch user data
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];

            // Redirect to the portfolio dashboard or home page
            header("Location: index.html");
            exit;
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "No account found with this email.";
    }

    // Close the connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Portfolio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #0f2027, #203a43, #2c5364);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.6);
        }

        .logo img {
            display: block;
            margin: 0 auto 20px;
            width: 80px;
        }

        .form-title {
            font-size: 28px;
            text-align: center;
            margin-bottom: 20px;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 10px;
            padding: 15px;
            color: #fff;
        }

        .form-control:focus {
            box-shadow: 0 0 10px #00c6ff;
            outline: none;
        }

        .btn-primary {
            background: linear-gradient(45deg, #ff7eb3, #ff758c);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            transition: transform 0.2s ease, background-color 0.3s ease;
        }

        .btn-primary:hover {
            transform: scale(1.05);
            background: linear-gradient(45deg, #ff758c, #ff7eb3);
        }

        .text-link {
            font-size: 14px;
            text-decoration: none;
            color: #00c6ff;
        }

        .text-link:hover {
            text-decoration: underline;
        }

        .mt-3.text-danger {
            color: #ff4d4d !important;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <img src="./assets/images/logo.png" alt="Logo">
        </div>
        <h2 class="form-title">Login</h2>
        <form method="POST">
            <div class="mb-3">
                <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
            <?php if (isset($error)) { ?>
                <div class="mt-3 text-center text-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php } ?>
            <div class="mt-3 text-center">
                <a href="register.php" class="text-link">for new user?</a>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
