<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

date_default_timezone_set('Europe/London');

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? 'User';

$month = isset($_GET['month']) ? (int)$_GET['month'] : (int)date('m');
$year = isset($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');

if ($month < 1) {
    $month = 12;
    $year--;
} elseif ($month > 12) {
    $month = 1;
    $year++;
}

$firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
$daysInMonth = (int)date('t', $firstDayOfMonth);
$startDay = (int)date('w', $firstDayOfMonth);
$monthName = date('F', $firstDayOfMonth);
$currentDate = date('Y-m-d');

$prevMonth = $month - 1;
$prevYear = $year;
if ($prevMonth < 1) {
    $prevMonth = 12;
    $prevYear--;
}

$nextMonth = $month + 1;
$nextYear = $year;
if ($nextMonth > 12) {
    $nextMonth = 1;
    $nextYear++;
}

$query = "SELECT id, title, due_date, priority, status
          FROM tasks
          WHERE user_id = ?
          AND due_date IS NOT NULL
          AND status != 'completed'
          ORDER BY due_date ASC, created_at DESC";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$tasksByDate = [];

while ($row = mysqli_fetch_assoc($result)) {
    $date = $row['due_date'];
    $tasksByDate[$date][] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar | MyPlanner</title>
    <link rel="stylesheet" href="calendar.css">
</head>
<body>

<div class="container">
    <aside class="sidebar">
        <div class="brand">
            <h2>MyPlanner</h2>
            <p>Task Manager</p>
        </div>

        <nav class="sidebar-nav">
            <a href="index.php">Dashboard</a>
            <a href="add.php">Add Task</a>
            <a href="tasks.php">Tasks</a>
            <a href="completed.php">Completed</a>
            <a href="calendar.php" class="active">Calendar</a>
        </nav>

        <a href="logout.php" class="logout-btn">Logout</a>
    </aside>

    <main class="main-content">
        <header class="topbar">
            <div>
                <h1>Calendar</h1>
                <p class="welcome-text">Welcome back, <?php echo htmlspecialchars($user_name); ?></p>
            </div>
        </header>

        <section class="calendar-section">
            <div class="calendar-header">
                <a class="month-btn" href="?month=<?php echo $prevMonth; ?>&year=<?php echo $prevYear; ?>">← Previous</a>

                <div class="calendar-title-wrap">
                    <h2><?php echo $monthName . ' ' . $year; ?></h2>
                    <p>Full monthly view of your pending tasks</p>
                </div>

                <a class="month-btn" href="?month=<?php echo $nextMonth; ?>&year=<?php echo $nextYear; ?>">Next →</a>
            </div>

            <div class="calendar-grid">
                <div class="day-label">Sun</div>
                <div class="day-label">Mon</div>
                <div class="day-label">Tue</div>
                <div class="day-label">Wed</div>
                <div class="day-label">Thu</div>
                <div class="day-label">Fri</div>
                <div class="day-label">Sat</div>

                <?php for ($i = 0; $i < $startDay; $i++): ?>
                    <div class="calendar-day empty"></div>
                <?php endfor; ?>

                <?php for ($day = 1; $day <= $daysInMonth; $day++): ?>
                    <?php
                        $fullDate = sprintf('%04d-%02d-%02d', $year, $month, $day);
                        $isToday = ($fullDate === $currentDate);
                    ?>
                    <div class="calendar-day <?php echo $isToday ? 'today' : ''; ?>">
                        <div class="day-top">
                            <span class="day-number"><?php echo $day; ?></span>

                            <?php if (!empty($tasksByDate[$fullDate])): ?>
                                <div class="task-dots">
                                    <?php
                                    $dotCount = min(count($tasksByDate[$fullDate]), 4);
                                    for ($d = 0; $d < $dotCount; $d++):
                                    ?>
                                        <span class="dot"></span>
                                    <?php endfor; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="day-tasks">
                            <?php if (!empty($tasksByDate[$fullDate])): ?>
                                <?php foreach ($tasksByDate[$fullDate] as $task): ?>
                                    <?php
                                    $priorityClass = strtolower(trim($task['priority']));
                                    if (!in_array($priorityClass, ['high', 'medium', 'low'])) {
                                        $priorityClass = 'medium';
                                    }
                                    ?>
                                    <a href="tasks.php" class="task-pill <?php echo $priorityClass; ?>">
                                        <?php echo htmlspecialchars($task['title']); ?>
                                    </a>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span class="no-task">No tasks</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endfor; ?>

                <?php
                $totalUsedCells = $startDay + $daysInMonth;
                $remainingCells = (7 - ($totalUsedCells % 7)) % 7;

                for ($i = 0; $i < $remainingCells; $i++):
                ?>
                    <div class="calendar-day empty"></div>
                <?php endfor; ?>
            </div>

            <div class="legend">
                <div class="legend-item">
                    <span class="legend-colour high"></span>
                    <span>High Priority</span>
                </div>
                <div class="legend-item">
                    <span class="legend-colour medium"></span>
                    <span>Medium Priority</span>
                </div>
                <div class="legend-item">
                    <span class="legend-colour low"></span>
                    <span>Low Priority</span>
                </div>
                <div class="legend-item">
                    <span class="legend-colour today-legend"></span>
                    <span>Today</span>
                </div>
            </div>
        </section>
    </main>
</div>

</body>
</html>
