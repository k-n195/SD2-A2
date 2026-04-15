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

$month = isset($_GET['month']) ? (int)$_GET['month'] : date('m');
$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

if ($month < 1) {
    $month = 12;
    $year--;
} elseif ($month > 12) {
    $month = 1;
    $year++;
}

$firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
$daysInMonth = date('t', $firstDayOfMonth);
$startDay = date('w', $firstDayOfMonth);

$monthName = date('F', $firstDayOfMonth);

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

$query = "SELECT id, title, due_date, priority 
          FROM tasks 
          WHERE user_id = ? 
          AND status != 'completed'
          AND due_date IS NOT NULL";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$tasks = [];

while ($row = mysqli_fetch_assoc($result)) {
    $taskDate = $row['due_date'];
    $tasks[$taskDate][] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'priority' => $row['priority']
    ];
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
    <div class="sidebar">
        <h2>MyPlanner</h2>
        <a href="index.php">Dashboard</a>
        <a href="add.php">Add Task</a>
        <a href="tasks.php">Tasks</a>
        <a href="completed.php">Completed</a>
        <a href="calendar.php" class="active">Calendar</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="main">
        <div class="top-bar">
            <div>
                <h1>Calendar</h1>
                <p class="welcome-text">Welcome back, <?php echo htmlspecialchars($user_name); ?></p>
            </div>
        </div>

        <div class="calendar-wrapper">
            <div class="calendar-header">
                <h2><?php echo $monthName . " " . $year; ?></h2>
                <div class="nav-buttons">
                    <a href="?month=<?php echo $prevMonth; ?>&year=<?php echo $prevYear; ?>">← Previous</a>
                    <a href="?month=<?php echo $nextMonth; ?>&year=<?php echo $nextYear; ?>">Next →</a>
                </div>
            </div>

            <div class="calendar-grid">
                <div class="day-name">Sunday</div>
                <div class="day-name">Monday</div>
                <div class="day-name">Tuesday</div>
                <div class="day-name">Wednesday</div>
                <div class="day-name">Thursday</div>
                <div class="day-name">Friday</div>
                <div class="day-name">Saturday</div>

                <?php
                for ($i = 0; $i < $startDay; $i++) {
                    echo '<div class="empty-cell"></div>';
                }

                $today = date('Y-m-d');

                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $date = sprintf('%04d-%02d-%02d', $year, $month, $day);
                    $isToday = ($date === $today) ? 'today' : '';

                    echo '<div class="day-cell ' . $isToday . '">';
                    echo '<div class="day-number">' . $day . '</div>';

                    if (isset($tasks[$date])) {
                        foreach ($tasks[$date] as $task) {
                            $priorityClass = strtolower($task['priority']);
                            echo '<a class="task-item ' . htmlspecialchars($priorityClass) . '" href="tasks.php">';
                            echo htmlspecialchars($task['title']);
                            echo '</a>';
                        }
                    }

                    echo '</div>';
                }

                $totalCells = $startDay + $daysInMonth;
                $remainingCells = 7 - ($totalCells % 7);

                if ($remainingCells < 7) {
                    for ($i = 0; $i < $remainingCells; $i++) {
                        echo '<div class="empty-cell"></div>';
                    }
                }
                ?>
            </div>

            <div class="legend">
                <span><span class="dot high"></span> High Priority</span>
                <span><span class="dot medium"></span> Medium Priority</span>
                <span><span class="dot low"></span> Low Priority</span>
            </div>
        </div>
    </div>
</div>

</body>
</html>
