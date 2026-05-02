<?php
session_start();
include 'db.php';
date_default_timezone_set('Europe/London');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = "";
$messageType = "";
$today = date('Y-m-d');

$title = "";
$description = "";
$due_date = "";
$priority = "";
$category = "";
$status = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];

    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $due_date = trim($_POST['due_date'] ?? '');
    $priority = trim($_POST['priority'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $status = trim($_POST['status'] ?? '');

    if (empty($title) || empty($priority) || empty($category) || empty($status)) {
        $message = "Please fill in all required fields.";
        $messageType = "error";
    } elseif (!empty($due_date) && $due_date < $today) {
        $message = "You cannot add a task in the past. Please select today or a future date.";
        $messageType = "error";
    } else {
        $sql = "INSERT INTO tasks (user_id, title, description, due_date, priority, category, status)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param(
                $stmt,
                "issssss",
                $user_id,
                $title,
                $description,
                $due_date,
                $priority,
                $category,
                $status
            );

            if (mysqli_stmt_execute($stmt)) {
                $message = "Task added successfully.";
                $messageType = "success";

                // Clear form after success
                $title = "";
                $description = "";
                $due_date = "";
                $priority = "";
                $category = "";
                $status = "";
            } else {
                $message = "Error: " . mysqli_stmt_error($stmt);
                $messageType = "error";
            }

            mysqli_stmt_close($stmt);
        } else {
            $message = "Error preparing statement: " . mysqli_error($conn);
            $messageType = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Task - MyPlanner</title>
  <link rel="stylesheet" href="add.css">
</head>
<body>

  <div class="container">

    <aside class="sidebar">
      <div class="logo">
        <h2>MyPlanner</h2>
      </div>

      <nav class="nav-menu">
        <a href="index.php">Dashboard</a>
        <a href="add.php" class="active">Add Task</a>
        <a href="completed.php">Completed Tasks</a>
        <a href="tasks.php">Tasks</a>
        <a href="calendar.php">Calendar</a>

        <hr style="margin: 15px 0; border: 0.5px solid #374151;">

        <a href="logout.php" class="logout-btn">Logout</a>
      </nav>
    </aside>

    <main class="main-content">
      <header class="top-header">
        <div>
          <h1>Add New Task</h1>
          <p>Create a new task and organise your schedule more effectively.</p>
        </div>
      </header>

      <section class="form-wrapper">
        <div class="form-card">

          <?php if (!empty($message)) : ?>
            <p style="margin-bottom: 20px; font-weight: bold; color: <?php echo ($messageType === 'success') ? '#16a34a' : '#dc2626'; ?>;">
              <?php echo htmlspecialchars($message); ?>
            </p>
          <?php endif; ?>

          <form action="" method="POST" class="task-form">
            
            <div class="form-group">
              <label for="title">Task Title</label>
              <input
                type="text"
                id="title"
                name="title"
                placeholder="Enter task title"
                value="<?php echo htmlspecialchars($title); ?>"
                required
              >
            </div>

            <div class="form-group">
              <label for="description">Description</label>
              <textarea
                id="description"
                name="description"
                rows="5"
                placeholder="Enter task description"
              ><?php echo htmlspecialchars($description); ?></textarea>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label for="due_date">Due Date</label>
                <input
                  type="date"
                  id="due_date"
                  name="due_date"
                  min="<?php echo $today; ?>"
                  value="<?php echo htmlspecialchars($due_date); ?>"
                >
              </div>

              <div class="form-group">
                <label for="priority">Priority</label>
                <select id="priority" name="priority" required>
                  <option value="">Select priority</option>
                  <option value="high" <?php if ($priority === 'high') echo 'selected'; ?>>High</option>
                  <option value="medium" <?php if ($priority === 'medium') echo 'selected'; ?>>Medium</option>
                  <option value="low" <?php if ($priority === 'low') echo 'selected'; ?>>Low</option>
                </select>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category" required>
                  <option value="">Select category</option>
                  <option value="study" <?php if ($category === 'study') echo 'selected'; ?>>Study</option>
                  <option value="work" <?php if ($category === 'work') echo 'selected'; ?>>Work</option>
                  <option value="personal" <?php if ($category === 'personal') echo 'selected'; ?>>Personal</option>
                  <option value="health" <?php if ($category === 'health') echo 'selected'; ?>>Health</option>
                </select>
              </div>

              <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                  <option value="">Select status</option>
                  <option value="pending" <?php if ($status === 'pending') echo 'selected'; ?>>Pending</option>
                  <option value="in_progress" <?php if ($status === 'in_progress') echo 'selected'; ?>>In Progress</option>
                  <option value="completed" <?php if ($status === 'completed') echo 'selected'; ?>>Completed</option>
                </select>
              </div>
            </div>

            <div class="button-group">
              <a href="index.php" class="btn secondary-btn">Cancel</a>
              <button type="submit" class="btn primary-btn">Save Task</button>
            </div>

          </form>
        </div>
      </section>
    </main>

  </div>

</body>
</html>
