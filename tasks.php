<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

$sql = "SELECT * FROM tasks 
        WHERE user_id = '$userId' 
        AND status != 'completed'
        ORDER BY 
          CASE 
            WHEN due_date IS NULL THEN 1 
            ELSE 0 
          END,
          due_date ASC,
          created_at DESC";

$result = mysqli_query($conn, $sql);
$totalActiveTasks = mysqli_num_rows($result);

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
  <title>All Tasks - MyPlanner</title>
  <link rel="stylesheet" href="tasks.css">
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
        <a href="completed.php">Completed Tasks</a>
        <a href="tasks.php" class="active">Tasks</a>

        <hr style="margin: 15px 0; border: 0.5px solid #374151;">

        <a href="logout.php" class="logout-btn">Logout</a>
      </nav>
    </aside>

    <main class="main-content">
      <header class="top-header">
        <div>
          <h1>All Active Tasks</h1>
          <p>View and manage all current tasks in one place.</p>
        </div>
        <div class="header-date">
          <span><?php echo $totalActiveTasks; ?> Active</span>
        </div>
      </header>

      <section class="tasks-page-section">
        <div class="card tasks-page-card">
          <div class="section-header">
            <h2>Your Tasks</h2>
            <a href="add.php" class="btn">+ Add New Task</a>
          </div>

          <?php if ($totalActiveTasks > 0): ?>
            <div class="task-list">
              <?php $lastDueDate = null; ?>

              <?php while ($task = mysqli_fetch_assoc($result)): ?>
                <?php
                  $currentDueDate = !empty($task['due_date']) ? $task['due_date'] : 'no-date';

                  if ($currentDueDate !== $lastDueDate):
                ?>
                  <div class="task-date-heading" style="margin: 24px 0 12px; font-size: 1.1rem; font-weight: 700; color: #1f2937;">
                    <?php
                      if ($currentDueDate === 'no-date') {
                          echo 'No Due Date';
                      } else {
                          echo date('j F Y', strtotime($currentDueDate));
                      }
                    ?>
                  </div>
                <?php
                    $lastDueDate = $currentDueDate;
                  endif;
                ?>

                <div class="task-item">
                  <div class="task-main">
                    <div class="task-top-row">
                      <h3><?php echo htmlspecialchars($task['title']); ?></h3>
                      <span class="priority <?php echo priorityBadgeClass($task['priority']); ?>">
                        <?php echo htmlspecialchars(ucfirst($task['priority'])); ?>
                      </span>
                    </div>

                    <p class="task-description">
                      <?php echo !empty($task['description']) ? htmlspecialchars($task['description']) : 'No description provided.'; ?>
                    </p>

                    <div class="task-meta">
                      <span><strong>Due Date:</strong> <?php echo !empty($task['due_date']) ? date('j F Y', strtotime($task['due_date'])) : 'Not set'; ?></span>
                      <span><strong>Category:</strong> <?php echo !empty($task['category']) ? htmlspecialchars(ucfirst($task['category'])) : 'Not set'; ?></span>
                      <span><strong>Status:</strong> <?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $task['status']))); ?></span>
                    </div>
                  </div>

                  <div class="task-actions">
                    <a href="edit.php?id=<?php echo $task['id']; ?>" class="action-btn edit-btn">Edit</a>
                    <a href="complete.php?id=<?php echo $task['id']; ?>" class="action-btn complete-btn">Mark Complete</a>
                    <a href="delete.php?id=<?php echo $task['id']; ?>&from=tasks"
                       class="action-btn delete-btn"
                       onclick="return confirm('Are you sure you want to delete this task?');">
                       Delete
                    </a>
                  </div>
                </div>
              <?php endwhile; ?>
            </div>
          <?php else: ?>
            <div class="empty-state">
              <h3>No active tasks found</h3>
              <p>Add a new task to start managing your planner.</p>
              <a href="add.php" class="btn">Create Task</a>
            </div>
          <?php endif; ?>
        </div>
      </section>
    </main>
  </div>

</body>
</html>
