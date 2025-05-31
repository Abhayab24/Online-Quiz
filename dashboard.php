<?php
session_start();

// Redirect to login page if user is not logged in
// Assuming your login page is index.php based on previous discussions.
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit(); // Always call exit() after a header redirect
}

// It's good practice to ensure user_name is also set, though user_id is the primary check
$userName = $_SESSION["user_name"] ?? 'Guest'; // Fallback to 'Guest' if for some reason user_name isn't set
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
            color: #333;
        }
        .dashboard-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            width: 90%;
            max-width: 400px;
        }
        h2 {
            color: #007bff;
            margin-bottom: 25px;
        }
        .dashboard-links a {
            display: block; /* Make links stack vertically */
            padding: 10px 15px;
            margin-bottom: 10px;
            background-color: #e9ecef;
            color: #007bff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .dashboard-links a:hover {
            background-color: #d6e0e9;
        }
        .logout-link {
            margin-top: 20px; /* Space out the logout link */
        }
        .logout-link a {
            background-color: #dc3545; /* Red color for logout */
            color: white;
        }
        .logout-link a:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h2>Welcome, <?php echo htmlspecialchars($userName); ?>!</h2>

        <div class="dashboard-links">
            <a href="quiz.php">Take Quiz</a>
            <a href="view-questions.php">Manage Questions</a>
        </div>

        <div class="logout-link">
            <a href="logout.php">Logout</a>
        </div>
    </div>
</body>
</html>