<?php
include 'db.php'; // Ensure db.php establishes $conn as a PDO object

$message = ''; // Initialize a message variable for user feedback

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]); // Trim whitespace
    $email = trim($_POST["email"]); // Trim whitespace
    $password_input = $_POST["password"]; // Store the raw password input

    // 1. Basic Validation
    if (empty($name) || empty($email) || empty($password_input)) {
        $message = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
    } elseif (strlen($password_input) < 6) { // Example: enforce minimum password length
        $message = "Password must be at least 6 characters long.";
    } else {
        // 2. Hash the password ONLY after validation
        $hashed_password = password_hash($password_input, PASSWORD_DEFAULT);

        try {
            // 3. Check for Duplicate Email
            $stmt_check = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
            $stmt_check->execute([$email]);
            if ($stmt_check->fetchColumn() > 0) {
                $message = "Email already registered. Please login or use a different email.";
            } else {
                // 4. Insert new user if email is unique
                $stmt_insert = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
                $stmt_insert->execute([$name, $email, $hashed_password]);

                // Success! Redirect to login page
                header("Location: index.php?registered=true"); // Add a flag for success message on login page
                exit();
            }
        } catch (PDOException $e) {
            // Log the error for debugging (don't show detailed error to user in production)
            error_log("Registration Error: " . $e->getMessage());
            $message = "An error occurred during registration. Please try again later.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            width: 90%;
            max-width: 400px;
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        input[type="text"],
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
            width: 100%;
        }
        button[type="submit"]:hover {
            background-color: #0056b3;
        }
        .message {
            color: red;
            margin-bottom: 15px;
        }
        .success-message {
            color: green;
            margin-bottom: 15px;
        }
        p {
            margin-top: 20px;
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
        <h2>Register</h2>

        <?php if ($message): // Display error messages ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form method="post">
            <input type="text" name="name" placeholder="Name" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"><br>
            <input type="email" name="email" placeholder="Email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Register</button>
        </form>

        <p>Already have an account? <a href="index.php">Login here</a></p>
    </div>
</body>
</html>