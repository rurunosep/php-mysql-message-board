<?php

session_start();

// Handle form submission
if (isset($_POST['register'])) {
  $username = isset($_POST['username']) ? $_POST['username'] : null;
  $password = isset($_POST['password']) ? $_POST['password'] : null;
  $password_confirm = isset($_POST['password_confirm']) ? $_POST['password_confirm'] : null;

  try {
    if (!($username && $password && $password_confirm))
      throw new Exception('Enter username and password');
    if ($password != $password_confirm)
      throw new Exception('Passwords do not match');

    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    require 'mysql.php';

    $query = 'INSERT INTO users (username, password_hash) VALUES (?, ?)';
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ss', $username, $password_hash);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_errno($stmt) == 1062) // Error 1062: Duplicate unique key
      throw new Exception('A user with that username already exists');

    $_SESSION['alert_text'] = 'Registered user';
    $_SESSION['alert_color'] = 'success';
    header('Location: index.php');
    exit();
  } catch (Exception $e) {
    $_SESSION['alert_text'] = $e->getMessage();
    $_SESSION['alert_color'] = 'danger';
  }
}

$page_title = 'Register';
require 'header.php';

?>

<div style="width: 200px;">
  <h1>Register</h1>
  <form action="register.php" method="post">
    <input type="text" class="form-control mb-3" name="username" placeholder="Username" />
    <input type="password" class="form-control mb-3" name="password" placeholder="Password" />
    <input type="password" class="form-control mb-3" name="password_confirm" placeholder="Confirm Password" />
    <button type="submit" class="btn btn-outline-dark" name="register">Register</button>
  </form>
</div>

<?php require 'footer.php';
?>