<?php
include 'db.php';
session_start();
$id = $_GET['id'];
$res = $conn->query("SELECT * FROM questions WHERE id=$id");
$row = $res->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $q = $_POST['question'];
    $a = $_POST['option_a'];
    $b = $_POST['option_b'];
    $c = $_POST['option_c'];
    $d = $_POST['option_d'];
    $correct = $_POST['correct_option'];

    $conn->query("UPDATE questions SET 
        question='$q', option_a='$a', option_b='$b', option_c='$c', option_d='$d', correct_option='$correct' 
        WHERE id=$id");
    header("Location: view-questions.php");
}
?>

<h2>Edit Question</h2>
<form method="post">
    <textarea name="question" required><?php echo $row['question']; ?></textarea><br>
    <input name="option_a" value="<?php echo $row['option_a']; ?>" required><br>
    <input name="option_b" value="<?php echo $row['option_b']; ?>" required><br>
    <input name="option_c" value="<?php echo $row['option_c']; ?>" required><br>
    <input name="option_d" value="<?php echo $row['option_d']; ?>" required><br>
    Correct Option: <input name="correct_option" value="<?php echo $row['correct_option']; ?>" maxlength="1" required><br>
    <button type="submit">Update</button>
</form>
<a href="view-questions.php">Back</a>
