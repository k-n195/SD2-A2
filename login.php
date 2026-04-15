<?php
session_start();
include 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];

            header("Location: index.php");
            exit();
        } else {
            $message = "Incorrect password.";
        }
    } else {
        $message = "No account found with that email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - MyPlanner</title>
  <link rel="stylesheet" href="add.css">
</head>
<body>
  <div class="container">
    <aside class="sidebar">
      <div class="logo">
        <h2>MyPlanner</h2>
      </div>

      <nav class="nav-menu">
        <a href="login.php" class="active">Login</a>
        <a href="register.php">Register</a>
      </nav>
    </aside>

    <main class="main-content">
      <header class="top-header">
        <div>
          <h1>Welcome Back</h1>
          <p>Login to access your planner and tasks.</p>
        </div>
      </header>

      <section class="form-wrapper">
        <div class="form-card">
          <?php if (!empty($message)): ?>
            <p style="margin-bottom: 20px; font-weight: bold; color: #dc2626;">
              <?php echo htmlspecialchars($message); ?>
            </p>
          <?php endif; ?>

          <form method="POST" class="task-form">
            <div class="form-group">
              <label for="email">Email Address</label>
              <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" id="password" name="password" required>
            </div>

            <div class="button-group">
              <button type="submit" class="btn primary-btn">Login</button>
            </div>
          </form>
        </div>
      </section>
    </main>
  </div>
</body>
</html>