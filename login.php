<?php
session_start();
include 'db.php'; // Ensure db.php correctly establishes $conn as a PDO object

$message = ''; // Initialize message to be empty

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input (basic example)
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $password = $_POST["password"]; // Password will be hashed, so no sanitization needed here

    // Prepare the statement to prevent SQL injection
    // Only select necessary columns (id, name, password)
    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
    $stmt->execute([$email]);

    // Fetch the user data. If no user is found, $user will be false.
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if a user was found AND if the provided password matches the hashed password
    if ($user && password_verify($password, $user['password'])) {
        // Authentication successful: Set session variables
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["user_name"] = $user["name"];

        // Redirect to dashboard
        header("Location: dashboard.php");
        exit(); // Crucial: Always call exit() after header() to prevent further script execution
    } else {
        // Authentication failed: Set error message
        // Use a generic message for security (don't reveal if email exists or password is just wrong)
        $message = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        /* Basic styling for better readability - you'd use a separate CSS file for a real project */
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        input[type="email"],
        input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button[type="submit"]:hover {
            background-color: #0056b3;
        }
        .message {
            color: red;
            margin-bottom: 15px;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>

        <?php if ($message): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="post">
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Login</button>
        </form>

        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>