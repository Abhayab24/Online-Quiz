<?php
session_start();
include 'db.php';

// Check if the user is logged in (and optionally, if they are an admin)
if (!isset($_SESSION["user_id"])) {
    // Assuming index.php uses a hyphen, adjust if it's index_php
    header("Location: /phpproject/online_quiz_system_updated/index.php");
    exit();
}
// Add admin check if you have a role system
/*
if (!$_SESSION["is_admin"]) {
    // Assuming unauthorized.php uses a hyphen, adjust if it's unauthorized_php
    header("Location: /phpproject/online_quiz_system_updated/unauthorized.php");
    exit();
}
*/

$message = '';
$message_type = '';

// Check for success message from add_question.php or other pages
if (isset($_GET['status']) && isset($_GET['msg'])) {
    $message = htmlspecialchars($_GET['msg']);
    $message_type = htmlspecialchars($_GET['status']); // 'success' or 'error'
}

$questions = [];
try {
    $stmt = $conn->prepare("SELECT id, question, option_a, option_b, option_c, option_d, correct_option FROM questions ORDER BY id ASC");
    $stmt->execute();
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching questions for view: " . $e->getMessage());
    $message = "An error occurred while loading questions.";
    $message_type = 'error';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Quiz Questions</title>
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
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 90%;
            max-width: 900px;
            margin-top: 20px;
        }
        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }
        .message {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 4px;
            font-weight: bold;
            text-align: center;
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
        .action-links {
            text-align: center;
            margin-bottom: 20px;
        }
        .action-links a {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            margin: 0 5px;
            transition: background-color 0.3s ease;
        }
        .action-links a:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            color: #555;
        }
        .question-col {
            width: 30%; /* Adjust width as needed */
        }
        .option-col {
            width: 15%;
        }
        .correct-col {
            width: 10%;
            text-align: center;
        }
        .actions-col {
            width: 15%;
            text-align: center;
        }
        .actions-col a {
            margin: 0 5px;
            color: #007bff;
            text-decoration: none;
        }
        .actions-col a:hover {
            text-decoration: underline;
        }
        .actions-col form {
            display: inline;
        }
        .actions-col button {
            background: none;
            border: none;
            color: #dc3545;
            cursor: pointer;
            font-size: 1em;
            padding: 0;
            text-decoration: underline;
        }
        .actions-col button:hover {
            color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Quiz Questions</h2>

        <?php if ($message): ?>
            <p class="message <?php echo $message_type; ?>">
                <?php echo htmlspecialchars($message); ?>
            </p>
        <?php endif; ?>

        <div class="action-links">
            <a href="/phpproject/online_quiz_system_updated/add_question.php">Add New Question</a>
            <a href="/phpproject/online_quiz_system_updated/dashboard.php">Back to Dashboard</a>
        </div>

        <?php if (empty($questions)): ?>
            <p>No questions found. Add some questions to get started!</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th class="question-col">Question</th>
                        <th class="option-col">Option A</th>
                        <th class="option-col">Option B</th>
                        <th class="option-col">Option C</th>
                        <th class="option-col">Option D</th>
                        <th class="correct-col">Correct</th>
                        <th class="actions-col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($questions as $question): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($question['id']); ?></td>
                            <td><?php echo htmlspecialchars($question['question']); ?></td>
                            <td><?php echo htmlspecialchars($question['option_a']); ?></td>
                            <td><?php echo htmlspecialchars($question['option_b']); ?></td>
                            <td><?php echo htmlspecialchars($question['option_c']); ?></td>
                            <td><?php echo htmlspecialchars($question['option_d']); ?></td>
                            <td><?php echo htmlspecialchars($question['correct_option']); ?></td>
                            <td>
                                <a href="/phpproject/online_quiz_system_updated/edit-question.php?id=<?php echo $question['id']; ?>">Edit</a> |
                                <form action="/phpproject/online_quiz_system_updated/delete-question.php" method="post" onsubmit="return confirm('Are you sure you want to delete this question?');">
                                    <input type="hidden" name="id" value="<?php echo $question['id']; ?>">
                                    <button type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>