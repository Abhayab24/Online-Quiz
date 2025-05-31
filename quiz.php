<?php
session_start(); // Start session at the very top
include 'db.php'; // Ensure db.php establishes $conn as a PDO object

// Check if the user is logged in
// Be consistent with the session variable name from your login script (e.g., user_id or user_name)
if (!isset($_SESSION["user_id"])) { // Assuming user_id is set upon successful login
    header("Location: index.php"); // Redirect to login page if not logged in
    exit(); // Stop script execution after redirect
}

$questions = []; // Initialize an empty array to hold questions

try {
    // Fetch all questions from the 'questions' table
    // Using prepare/execute even for simple SELECT * is good practice
    // although for no user input, query() is technically safe with PDO here.
    // However, fetchAll() is cleaner for multiple rows.
    $stmt = $conn->prepare("SELECT id, question, option_a, option_b, option_c, option_d FROM questions ORDER BY id ASC");
    $stmt->execute();
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Handle database errors gracefully
    error_log("Error fetching questions: " . $e->getMessage()); // Log the error
    // Display a user-friendly message, but don't expose sensitive details
    die("An error occurred while loading the quiz. Please try again later.");
}

// Check if any questions were found
if (empty($questions)) {
    // No questions found, display a message or redirect
    echo "<h2>No quiz questions available at the moment.</h2>";
    exit(); // Stop execution as there's no quiz to display
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Quiz - Take Quiz</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 20px;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .quiz-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 80%;
            max-width: 700px;
            margin-top: 20px;
        }
        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }
        p b {
            font-size: 1.1em;
            color: #555;
            margin-bottom: 10px;
            display: block;
        }
        input[type="radio"] {
            margin-right: 8px;
            margin-bottom: 10px; /* Space between options */
        }
        label {
            display: inline-block;
            margin-bottom: 5px;
            color: #666;
        }
        br + br { /* Adjust space between questions */
            margin-top: 20px;
            display: block;
            content: " ";
        }
        button[type="submit"] {
            background-color: #28a745;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.1em;
            margin-top: 20px;
            width: 100%;
        }
        button[type="submit"]:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="quiz-container">
        <h2>Quiz</h2>
        <form action="result.php" method="post">
           <?php foreach ($questions as $row): ?>
                <p><b><?php echo htmlspecialchars($row['question']); ?></b></p>
                <label><input type="radio" name="q<?php echo $row['id']; ?>" value="A" required> <?php echo htmlspecialchars($row['option_a']); ?></label><br>
                <label><input type="radio" name="q<?php echo $row['id']; ?>" value="B"> <?php echo htmlspecialchars($row['option_b']); ?></label><br>
                <label><input type="radio" name="q<?php echo $row['id']; ?>" value="C"> <?php echo htmlspecialchars($row['option_c']); ?></label><br>
                <label><input type="radio" name="q<?php echo $row['id']; ?>" value="D"> <?php echo htmlspecialchars($row['option_d']); ?></label><br><br>
            <?php endforeach; ?> // <-- This was likely missing or misplaced

            <button type="submit">Submit Quiz</button>
        </form>
    </div>
</body>
</html> // <-- Ensure all HTML tags are closed properly