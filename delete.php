<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id']) || !isset($_GET['from'])) {
    header("Location: tasks.php");
    exit();
}

$id = (int) $_GET['id'];
$userId = (int) $_SESSION['user_id'];
$from = $_GET['from'] === 'completed' ? 'completed' : 'tasks';

$sql = "DELETE FROM tasks WHERE id = $id AND user_id = $userId";

if (mysqli_query($conn, $sql)) {
    if ($from === 'completed') {
        header("Location: completed.php");
    } else {
        header("Location: tasks.php");
    }
    exit();
} else {
    die("Error deleting task: " . mysqli_error($conn));
}
?>