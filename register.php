<?php

$page_title = 'Register';
require 'includes/header.php';

// TODO: confirm password

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
      $_SESSION['redirect_message'] = 'Registered user';
      header('Location: index.php');
      exit();
    } elseif (mysqli_stmt_errno($stmt) == 1062) {
      // Error 1062: Duplicate unique key
      echo '<p>A user with that username already exists</p>';
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
  } else {
    // Incomplete form
    echo '<p>Enter username and password</p>';
  }
}
?>

<h1>Register</h1>
<form action="register.php" method="post">
  <div>
    <label for="username">Username</label>
    <input type="text" name="username" />
  </div>

  <div>
    <label for="password">Password</label>
    <input type="password" name="password" />
  </div>

  <input type="submit" name="register" value="Register" />
</form>

<?php require 'includes/footer.php';
?>
