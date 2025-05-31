<?php
include 'db.php';
session_start();

$score = 0;
$total = 0;

// Execute the query. For PDO, query() directly returns a PDOStatement object.
$result = $conn->query("SELECT * FROM questions");

// Fix: Use fetch(PDO::FETCH_ASSOC) instead of fetch_assoc() for PDO
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $qid = $row['id'];
    $correct = $row['correct_option'];
    // This assumes your form input names are like q1, q2, q3, etc.
    $selected = $_POST["q$qid"] ?? "";

    if ($selected == $correct) {
        $score++;
    }
    $total++;
}

echo "<h2>Your Score: $score / $total</h2>";
// Ensure this path is correct: dashboard.php (hyphen) or dashboard_php (underscore)?
echo '<a href="/phpproject/online_quiz_system_updated/dashboard.php">Back to Dashboard</a>';
?>