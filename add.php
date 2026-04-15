<?php
session_start();
include 'db.php';
date_default_timezone_set('Europe/London');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = "";
$today = date('Y-m-d');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];

    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $due_date = mysqli_real_escape_string($conn, $_POST['due_date']);
    $priority = mysqli_real_escape_string($conn, $_POST['priority']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    if (!empty($due_date) && $due_date < $today) {
        $message = "You cannot add a task in the past. Please select today or a future date.";
    } else {
        $sql = "INSERT INTO tasks (user_id, title, description, due_date, priority, category, status)
                VALUES ('$user_id', '$title', '$description', '$due_date', '$priority', '$category', '$status')";

        if (mysqli_query($conn, $sql)) {
            $message = "Task added successfully.";
        } else {
            $message = "Error: " . mysqli_error($conn);
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
          <h1>Add New Task</h1>
          <p>Create a new task and organise your schedule more effectively.</p>
        </div>
      </header>

      <section class="form-wrapper">
        <div class="form-card">

          <?php if (!empty($message)) : ?>
            <p style="margin-bottom: 20px; font-weight: bold; color: <?php echo (strpos($message, 'successfully') !== false) ? '#16a34a' : '#dc2626'; ?>;">
              <?php echo htmlspecialchars($message); ?>
            </p>
          <?php endif; ?>

          <form action="" method="POST" class="task-form">
            
            <div class="form-group">
              <label for="title">Task Title</label>
              <input type="text" id="title" name="title" placeholder="Enter task title" required>
            </div>

            <div class="form-group">
              <label for="description">Description</label>
              <textarea id="description" name="description" rows="5" placeholder="Enter task description"></textarea>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label for="due_date">Due Date</label>
                <input type="date" id="due_date" name="due_date" min="<?php echo $today; ?>">
              </div>

              <div class="form-group">
                <label for="priority">Priority</label>
                <select id="priority" name="priority" required>
                  <option value="">Select priority</option>
                  <option value="high">High</option>
                  <option value="medium">Medium</option>
                  <option value="low">Low</option>
                </select>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category" required>
                  <option value="">Select category</option>
                  <option value="study">Study</option>
                  <option value="work">Work</option>
                  <option value="personal">Personal</option>
                  <option value="health">Health</option>
                </select>
              </div>

              <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                  <option value="">Select status</option>
                  <option value="pending">Pending</option>
                  <option value="in_progress">In Progress</option>
                  <option value="completed">Completed</option>
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
