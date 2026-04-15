<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: tasks.php");
    exit();
}

$taskId = (int) $_GET['id'];

$taskQuery = mysqli_query($conn, "SELECT * FROM tasks WHERE id = '$taskId' AND user_id = '$userId'");

if (!$taskQuery || mysqli_num_rows($taskQuery) == 0) {
    header("Location: tasks.php");
    exit();
}

$task = mysqli_fetch_assoc($taskQuery);
$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, trim($_POST['title']));
    $description = mysqli_real_escape_string($conn, trim($_POST['description']));
    $due_date = !empty($_POST['due_date']) ? mysqli_real_escape_string($conn, $_POST['due_date']) : NULL;
    $priority = mysqli_real_escape_string($conn, $_POST['priority']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);

    if (empty($title)) {
        $error = "Task title is required.";
    } else {
        $updateSql = "UPDATE tasks SET 
                        title = '$title',
                        description = '$description',
                        due_date = " . ($due_date ? "'$due_date'" : "NULL") . ",
                        priority = '$priority',
                        category = '$category'
                      WHERE id = '$taskId' AND user_id = '$userId'";

        if (mysqli_query($conn, $updateSql)) {
            header("Location: tasks.php");
            exit();
        } else {
            $error = "Error updating task.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Task - MyPlanner</title>
  <link rel="stylesheet" href="add.css">
</head>
<body>

  <div class="container">

    <aside class="sidebar">
      <div class="logo">
        <h2>MyPlanner</h2>
      </div>

        <nav class="nav-menu">
        <a href="index.php" class="active">Dashboard</a>
        <a href="add.php">Add Task</a>
        <a href="completed.php">Completed Tasks</a>
        <a href="tasks.php">Tasks</a>

        <hr style="margin: 15px 0; border: 0.5px solid #374151;">

        <a href="logout.php" class="logout-btn">Logout</a>
      </nav>
    </aside>

    <main class="main-content">
      <header class="top-header">
        <div>
          <h1>Edit Task</h1>
          <p>Update your task details below.</p>
        </div>
      </header>

      <section class="form-wrapper">
        <div class="form-card">

          <?php if (!empty($error)): ?>
            <p style="margin-bottom: 20px; font-weight: bold; color: #dc2626;">
              <?php echo $error; ?>
            </p>
          <?php endif; ?>

          <form method="POST" class="task-form">
            <div class="form-group">
              <label for="title">Task Title</label>
              <input
                type="text"
                id="title"
                name="title"
                value="<?php echo htmlspecialchars($task['title']); ?>"
                required
              >
            </div>

            <div class="form-group">
              <label for="description">Description</label>
              <textarea
                id="description"
                name="description"
                rows="5"
              ><?php echo htmlspecialchars($task['description']); ?></textarea>
            </div>

            <div class="form-group">
              <label for="due_date">Due Date</label>
              <input
                type="date"
                id="due_date"
                name="due_date"
                value="<?php echo !empty($task['due_date']) ? htmlspecialchars($task['due_date']) : ''; ?>"
              >
            </div>

            <div class="form-group">
              <label for="priority">Priority</label>
              <select id="priority" name="priority" required>
                <option value="low" <?php if ($task['priority'] == 'low') echo 'selected'; ?>>Low</option>
                <option value="medium" <?php if ($task['priority'] == 'medium') echo 'selected'; ?>>Medium</option>
                <option value="high" <?php if ($task['priority'] == 'high') echo 'selected'; ?>>High</option>
              </select>
            </div>

            <div class="form-group">
              <label for="category">Category</label>
              <select id="category" name="category">
                <option value="">Select Category</option>
                <option value="work" <?php if ($task['category'] == 'work') echo 'selected'; ?>>Work</option>
                <option value="personal" <?php if ($task['category'] == 'personal') echo 'selected'; ?>>Personal</option>
                <option value="study" <?php if ($task['category'] == 'study') echo 'selected'; ?>>Study</option>
                <option value="other" <?php if ($task['category'] == 'other') echo 'selected'; ?>>Other</option>
              </select>
            </div>

            <div class="button-group">
              <button type="submit" class="btn primary-btn">Update Task</button>
              <a href="tasks.php" class="btn secondary-btn">Cancel</a>
            </div>
          </form>

        </div>
      </section>
    </main>
  </div>

</body>
</html>