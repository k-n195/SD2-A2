<?php
include 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $checkEmail = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $checkEmail);

    if (mysqli_num_rows($result) > 0) {
        $message = "Email already exists.";
    } else {
        $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashedPassword')";
        if (mysqli_query($conn, $sql)) {
            $message = "Registration successful. <a href='login.php'>Login here</a>";
        } else {
            $message = "Something went wrong.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - MyPlanner</title>
  <link rel="stylesheet" href="add.css">
</head>
<body>
  <div class="container">
    <aside class="sidebar">
      <div class="logo">
        <h2>MyPlanner</h2>
      </div>

      <nav class="nav-menu">
        <a href="login.php">Login</a>
        <a href="register.php" class="active">Register</a>
      </nav>
    </aside>

    <main class="main-content">
      <header class="top-header">
        <div>
          <h1>Create Account</h1>
          <p>Register to start using your personal planner.</p>
        </div>
      </header>

      <section class="form-wrapper">
        <div class="form-card">
          <?php if (!empty($message)) : ?>
            <p style="margin-bottom: 20px; font-weight: bold; color: #2563eb;"><?php echo $message; ?></p>
          <?php endif; ?>

          <form method="POST" class="task-form">
            <div class="form-group">
              <label for="name">Full Name</label>
              <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
              <label for="email">Email Address</label>
              <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" id="password" name="password" required>
            </div>

            <div class="button-group">
              <button type="submit" class="btn primary-btn">Register</button>
            </div>
          </form>
        </div>
      </section>
    </main>
  </div>
</body>
</html>