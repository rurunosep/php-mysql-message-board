<?php

session_start();

// TODO: proper error handling
// TODO: confirm password
// TODO: sticky form
// TODO: client-side form validation

// Handle form submission
if (isset($_POST['register'])) {
  $username = isset($_POST['username']) ? $_POST['username'] : null;
  $password = isset($_POST['password']) ? $_POST['password'] : null;

  if ($username && $password) {
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    $conn = mysqli_connect('localhost', 'root', 'password', 'message_board');
    if (!$conn) {
      echo '<p>' . mysqli_connect_error() . '</p>';
    }

    $query = 'INSERT INTO users (username, password_hash) VALUES (?, ?)';
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ss', $username, $password_hash);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) == 1) {
      $_SESSION['alert_text'] = 'Registered user';
      $_SESSION['alert_color'] = 'success';
      header('Location: index.php');
      exit();
    } elseif (mysqli_stmt_errno($stmt) == 1062) {
      // Error 1062: Duplicate unique key
      $_SESSION['alert_text'] = 'A user with that username already exists';
      $_SESSION['alert_color'] = 'danger';
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
  } else {
    // Incomplete form
    $_SESSION['alert_text'] = 'Enter username and password';
    $_SESSION['alert_color'] = 'danger';
  }
}

$page_title = 'Register';
require 'includes/header.php';

?>

<div style="width: 200px;">
  <h1>Register</h1>
  <form action="register.php" method="post">
    <input type="text" class="form-control mb-3" name="username" placeholder="Username" />
    <input type="password" class="form-control mb-3" name="password" placeholder="Password" />
    <button type="submit" class="btn btn-outline-dark" name="register">Register</button>
  </form>
</div>

<?php require 'includes/footer.php';
?>