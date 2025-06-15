<?php
session_start(); // Start session first
include 'db.php'; // Ensure db.php establishes $conn as a PDO object

$message = ''; // Initialize message for display

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize email input (optional, but good for display/storage consistency)
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $password_input = $_POST["password"]; // Get the password entered by the user

    // 1. Use PREPARED STATEMENT for security against SQL Injection
    // 2. Select only necessary columns
    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
    $stmt->execute([$email]); // Bind the email parameter safely

    // Fetch the user data. If no user is found, $user will be false.
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if a user was found AND if the password is correct
    if ($user && password_verify($password_input, $user['password'])) {
        // Authentication successful
        $_SESSION["user_id"] = $user["id"]; // Store user ID
        $_SESSION["user_name"] = $user["name"]; // Store user name
        
        header("Location: dashboard.php"); // Redirect to dashboard
        exit(); // Crucial: Stop script execution after redirect
    } else {
        // Authentication failed (either user not found or wrong password)
        // Provide a generic message for security
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
        /* Basic styling for readability - integrate with your project's CSS */
        body { font-family: Arial, sans-serif; display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 100vh; margin: 0; background-color: #f4f4f4; }
        .container { background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center; }
        input[type="email"], input[type="password"] { width: calc(100% - 20px); padding: 10px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 4px; }
        button[type="submit"] { background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        button[type="submit"]:hover { background-color: #0056b3; }
        .error-message { color: red; margin-bottom: 15px; }
        a { color: #007bff; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>

        <?php if ($message): ?>
            <p class="error-message"><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="post">
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Login</button>
        </form>

        <p>Don't have an account? <a href="register.php">Register here Make a new account</a></p>
    </div>
</body>
</html>