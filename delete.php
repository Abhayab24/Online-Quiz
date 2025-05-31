<?php
include 'db.php';
session_start();
$id = $_GET['id'];
$conn->query("DELETE FROM questions WHERE id=$id");
header("Location: view-questions.php");
?>
