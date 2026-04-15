<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $userId = (int) $_SESSION['user_id'];

    $sql = "UPDATE tasks 
            SET status = 'completed' 
            WHERE id = $id AND user_id = $userId";

    if (mysqli_query($conn, $sql)) {
        header("Location: completed.php");
        exit();
    } else {
        die("Error completing task: " . mysqli_error($conn));
    }
} else {
    echo "No task ID provided.";
}
?>