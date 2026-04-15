<?php
session_start();
date_default_timezone_set('Europe/London');
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

$realCurrentMonth = (int) date('n');
$realCurrentYear = (int) date('Y');
$realCurrentDay = (int) date('j');
$todayDate = date('Y-m-d');
$currentFullDate = date('j F Y, H:i');

$currentMonth = isset($_GET['month']) ? (int) $_GET['month'] : $realCurrentMonth;
$currentYear = isset($_GET['year']) ? (int) $_GET['year'] : $realCurrentYear;

if ($currentMonth < 1 || $currentMonth > 12) {
    $currentMonth = $realCurrentMonth;
}
if ($currentYear < 2020 || $currentYear > 2100) {
    $currentYear = $realCurrentYear;
}

$displayMonthTimestamp = strtotime(sprintf('%04d-%02d-01', $currentYear, $currentMonth));
$currentMonthName = date('F', $displayMonthTimestamp);
$daysInMonth = (int) date('t', $displayMonthTimestamp);
$firstWeekday = (int) date('N', $displayMonthTimestamp);
$leadingEmptyCells = $firstWeekday - 1;

$prevMonthTimestamp = strtotime('-1 month', $displayMonthTimestamp);
$nextMonthTimestamp = strtotime('+1 month', $displayMonthTimestamp);

$prevMonth = (int) date('n', $prevMonthTimestamp);
$prevYear = (int) date('Y', $prevMonthTimestamp);
$nextMonth = (int) date('n', $nextMonthTimestamp);
$nextYear = (int) date('Y', $nextMonthTimestamp);

$defaultSelectedDay = ($currentMonth === $realCurrentMonth && $currentYear === $realCurrentYear) ? $realCurrentDay : 1;
$selectedDay = isset($_GET['day']) ? (int) $_GET['day'] : $defaultSelectedDay;

if ($selectedDay < 1 || $selectedDay > $daysInMonth) {
    $selectedDay = $defaultSelectedDay;
}

$selectedDate = sprintf('%04d-%02d-%02d', $currentYear, $currentMonth, $selectedDay);
$selectedHeading = date('j F Y', strtotime($selectedDate));

$totalTasksQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM tasks WHERE user_id = '$userId'");
$totalTasks = mysqli_fetch_assoc($totalTasksQuery)['total'];

$completedTasksQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM tasks WHERE user_id = '$userId' AND status = 'completed'");
$completedTasks = mysqli_fetch_assoc($completedTasksQuery)['total'];

$dueTodayQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM tasks WHERE user_id = '$userId' AND due_date = '$todayDate' AND status != 'completed'");
$dueToday = mysqli_fetch_assoc($dueTodayQuery)['total'];

$tasksQuery = mysqli_query($conn, "
    SELECT * FROM tasks
    WHERE user_id = '$userId'
      AND due_date = '$selectedDate'
      AND status != 'completed'
    ORDER BY created_at DESC
");

$monthTaskDates = [];
$monthStart = sprintf('%04d-%02d-01', $currentYear, $currentMonth);
$monthEnd = date('Y-m-t', strtotime($monthStart));

$monthDatesQuery = mysqli_query($conn, "
    SELECT DISTINCT DAY(due_date) AS task_day
    FROM tasks
    WHERE user_id = '$userId'
      AND due_date IS NOT NULL
      AND due_date BETWEEN '$monthStart' AND '$monthEnd'
      AND status != 'completed'
");

while ($row = mysqli_fetch_assoc($monthDatesQuery)) {
    $monthTaskDates[] = (int) $row['task_day'];
}

$todayTasksQuery = mysqli_query($conn, "
    SELECT * FROM tasks
    WHERE user_id = '$userId'
      AND due_date = '$todayDate'
      AND status != 'completed'
    ORDER BY created_at DESC
    LIMIT 4
");

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
  <title>Personal Planner Dashboard</title>
  <link rel="stylesheet" href="index.css">
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
          <h1>Welcome back, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
          <p>Here’s an overview of your tasks and schedule for today.</p>
        </div>
        <div class="header-date">
          <span><?php echo htmlspecialchars($currentFullDate); ?></span>
        </div>
      </header>

      <section class="summary-cards">
        <a href="tasks.php" class="summary-link">
          <div class="card summary-card">
            <h3><?php echo $totalTasks; ?></h3>
            <p>Total Tasks</p>
          </div>
        </a>

        <a href="completed.php" class="summary-link">
          <div class="card summary-card">
            <h3><?php echo $completedTasks; ?></h3>
            <p>Completed</p>
          </div>
        </a>

        <a href="index.php?month=<?php echo $realCurrentMonth; ?>&year=<?php echo $realCurrentYear; ?>&day=<?php echo $realCurrentDay; ?>#calendar" class="summary-link">
          <div class="card summary-card">
            <h3><?php echo $dueToday; ?></h3>
            <p>Due Today</p>
          </div>
        </a>
      </section>

      <section class="dashboard-grid">

        <div class="card tasks-panel" id="taskContainer">
          <div class="section-header">
            <h2 id="taskHeading">Tasks for <?php echo htmlspecialchars($selectedHeading); ?></h2>
            <a href="add.php" class="btn">+ New Task</a>
          </div>

          <?php if (mysqli_num_rows($tasksQuery) > 0): ?>
            <?php while ($task = mysqli_fetch_assoc($tasksQuery)): ?>
              <a href="tasks.php" class="task-link">
                <div class="task-item">
                  <div>
                    <h4><?php echo htmlspecialchars($task['title']); ?></h4>
                    <p>
                      Due: <?php echo !empty($task['due_date']) ? htmlspecialchars(date('j F Y', strtotime($task['due_date']))) : 'No date'; ?>
                      <?php if (!empty($task['category'])): ?>
                        | Category: <?php echo htmlspecialchars(ucfirst($task['category'])); ?>
                      <?php endif; ?>
                    </p>
                  </div>
                  <span class="priority <?php echo priorityBadgeClass($task['priority']); ?>">
                    <?php echo htmlspecialchars(ucfirst($task['priority'])); ?>
                  </span>
                </div>
              </a>
            <?php endwhile; ?>
          <?php else: ?>
            <div class="no-tasks">No tasks for this day.</div>
          <?php endif; ?>
        </div>

        <div class="right-panels">

          <div class="card schedule-panel">
            <h2>Today’s Schedule</h2>
            <ul>
              <?php if (mysqli_num_rows($todayTasksQuery) > 0): ?>
                <?php while ($todayTask = mysqli_fetch_assoc($todayTasksQuery)): ?>
                  <li>
                    <strong><?php echo htmlspecialchars(ucfirst($todayTask['priority'])); ?></strong>
                    - <?php echo htmlspecialchars($todayTask['title']); ?>
                  </li>
                <?php endwhile; ?>
              <?php else: ?>
                <li>No tasks scheduled for today.</li>
              <?php endif; ?>
            </ul>
          </div>

          <div class="card calendar-panel" id="calendar">
            <div class="calendar-header">
              <h2>Mini Calendar</h2>
              <div class="calendar-nav">
                <a href="index.php?month=<?php echo $prevMonth; ?>&year=<?php echo $prevYear; ?>&day=1#calendar" class="calendar-nav-btn">&#10094;</a>
                <span class="calendar-month-label"><?php echo htmlspecialchars($currentMonthName . ' ' . $currentYear); ?></span>
                <a href="index.php?month=<?php echo $nextMonth; ?>&year=<?php echo $nextYear; ?>&day=1#calendar" class="calendar-nav-btn">&#10095;</a>
              </div>
            </div>

            <div class="calendar-box">
              <div class="day-name">Mon</div>
              <div class="day-name">Tue</div>
              <div class="day-name">Wed</div>
              <div class="day-name">Thu</div>
              <div class="day-name">Fri</div>
              <div class="day-name">Sat</div>
              <div class="day-name">Sun</div>

              <?php for ($i = 0; $i < $leadingEmptyCells; $i++): ?>
                <div class="empty"></div>
              <?php endfor; ?>

              <?php for ($day = 1; $day <= $daysInMonth; $day++): ?>
                <?php
                  $classes = ['day'];

                  if ($day === $selectedDay) {
                      $classes[] = 'active-day';
                  } elseif (
                      $day === $realCurrentDay &&
                      $currentMonth === $realCurrentMonth &&
                      $currentYear === $realCurrentYear
                  ) {
                      $classes[] = 'today';
                  }

                  if (in_array($day, $monthTaskDates)) {
                      $classes[] = 'has-task';
                  }
                ?>
                <a href="index.php?month=<?php echo $currentMonth; ?>&year=<?php echo $currentYear; ?>&day=<?php echo $day; ?>#calendar" class="<?php echo implode(' ', $classes); ?>">
                  <?php echo $day; ?>
                </a>
              <?php endfor; ?>
            </div>
          </div>

        </div>
      </section>

    </main>
  </div>

</body>
</html>