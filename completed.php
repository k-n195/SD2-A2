<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = (int) $_SESSION['user_id'];

$sql = "SELECT * FROM tasks 
        WHERE user_id = $userId 
        AND status = 'completed' 
        ORDER BY id DESC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}

function priorityBadgeClass($priority) {
    switch ($priority) {
        case 'high':
            return 'high-badge';
        case 'medium':
            return 'medium-badge';
        default:
            return 'low-badge';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Completed Tasks</title>
    <link rel="stylesheet" href="completed.css">
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div class="logo">
                <h2>MyPlanner</h2>
            </div>

            <nav class="nav-menu">
                <a href="index.php">Dashboard</a>
                <a href="add.php">Add Task</a>
                <a href="completed.php" class="active">Completed Tasks</a>
                <a href="tasks.php">Tasks</a>

                <hr class="nav-divider">

                <a href="logout.php" class="logout-btn">Logout</a>
            </nav>
        </aside>

        <main class="main-content">
            <header class="top-header">
                <div>
                    <h1>Completed Tasks</h1>
                    <p>Here are all the tasks you have finished.</p>
                </div>
            </header>

            <section class="tasks-list">
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <div class="task-card">
                            <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                            <p><?php echo htmlspecialchars($row['description']); ?></p>
                            <p><strong>Due Date:</strong> <?php echo htmlspecialchars($row['due_date']); ?></p>
                            <p><strong>Priority:</strong> 
                                <span class="priority <?php echo priorityBadgeClass($row['priority']); ?>">
                                    <?php echo htmlspecialchars(ucfirst($row['priority'])); ?>
                                </span>
                            </p>

                            <div class="task-actions">
                                <a href="uncomplete.php?id=<?php echo $row['id']; ?>" class="action-btn restore-btn">
                                    Restore
                                </a>

                                <a href="delete.php?id=<?php echo $row['id']; ?>&from=completed"
                                   class="action-btn delete-btn"
                                   onclick="return confirm('Are you sure you want to delete this task?');">
                                    Delete
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No completed tasks yet.</p>
                <?php endif; ?>
            </section>
        </main>
    </div>
</body>
</html>