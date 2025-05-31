<?php
session_start();
include 'db.php'; // Ensure db.php correctly establishes $conn as a PDO object

// Optional: Admin Check - Highly Recommended!
// For a real system, you'd check if the logged-in user has admin privileges.
// Example:
// if (!isset($_SESSION["user_id"]) || !$_SESSION["is_admin"]) {
//     header("Location: /phpproject/online_quiz_system_updated/unauthorized.php"); // Assuming unauthorized.php is hyphenated or adjust
//     exit();
// }

$message = ''; // Initialize message for user feedback
$message_type = ''; // 'success' or 'error'

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Sanitize and Trim Inputs
    $q = trim($_POST['question']);
    $a = trim($_POST['option_a']);
    $b = trim($_POST['option_b']);
    $c = trim($_POST['option_c']);
    $d = trim($_POST['option_d']);
    $correct = strtoupper(trim($_POST['correct_option'])); // Convert to uppercase for consistent validation

    // 2. Input Validation
    if (empty($q) || empty($a) || empty($b) || empty($c) || empty($d) || empty($correct)) {
        $message = "All fields are required.";
        $message_type = 'error';
    } elseif (!in_array($correct, ['A', 'B', 'C', 'D'])) {
        $message = "Correct option must be A, B, C, or D.";
        $message_type = 'error';
    } else {
        try {
            // 3. Use PDO Prepared Statement for INSERT (CRITICAL for security)
            $stmt = $conn->prepare("INSERT INTO questions (question, option_a, option_b, option_c, option_d, correct_option) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$q, $a, $b, $c, $d, $correct]);

            // Success! Set success message and redirect
            // --- CRITICAL PATH CORRECTION: Now targeting view_question.php (singular) ---
            header("Location: /phpproject/online_quiz_system_updated/view_question.php?status=success&msg=" . urlencode("Question added successfully!"));
            exit();

        } catch (PDOException $e) {
            // Log the error for debugging (don't show detailed error to user in production)
            error_log("Add Question Error: " . $e->getMessage());
            $message = "An error occurred while adding the question. Please try again later.";
            $message_type = 'error';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Quiz Question</title>
    <style>
        /* Your CSS here */
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
            max-width: 600px;
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        textarea,
        input[type="text"] { /* Applies to all text inputs by default */
            width: calc(100% - 22px); /* Adjust for padding and border */
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box; /* Include padding and border in the element's total width and height */
        }
        textarea {
            resize: vertical; /* Allow vertical resizing */
            min-height: 80px;
        }
        button[type="submit"] {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            margin-top: 10px;
        }
        button[type="submit"]:hover {
            background-color: #218838;
        }
        .message {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 4px;
            font-weight: bold;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        p {
            margin-top: 20px;
        }
        a {
            color: #007bff;
            text-decoration: none;
            margin-top: 15px;
            display: inline-block;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add New Question</h2>

        <?php if ($message): ?>
            <p class="message <?php echo $message_type; ?>">
                <?php echo htmlspecialchars($message); ?>
            </p>
        <?php endif; ?>

        <form method="post">
            <textarea name="question" placeholder="Question" required><?php echo htmlspecialchars($_POST['question'] ?? ''); ?></textarea><br>
            <input type="text" name="option_a" placeholder="Option A" required value="<?php echo htmlspecialchars($_POST['option_a'] ?? ''); ?>"><br>
            <input type="text" name="option_b" placeholder="Option B" required value="<?php echo htmlspecialchars($_POST['option_b'] ?? ''); ?>"><br>
            <input type="text" name="option_c" placeholder="Option C" required value="<?php echo htmlspecialchars($_POST['option_c'] ?? ''); ?>"><br>
            <input type="text" name="option_d" placeholder="Option D" required value="<?php echo htmlspecialchars($_POST['option_d'] ?? ''); ?>"><br>
            Correct Option (A/B/C/D):
            <input type="text" name="correct_option" maxlength="1" required style="width: 50px; text-align: center;" value="<?php echo htmlspecialchars($_POST['correct_option'] ?? ''); ?>"><br>
            <button type="submit">Add Question</button>
        </form>
        <a href="/phpproject/online_quiz_system_updated/view_question.php">Back to Questions List</a>
    </div>
</body>
</html>