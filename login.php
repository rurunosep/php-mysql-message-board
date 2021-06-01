<?php

session_start();

// TODO: proper error handling
// TODO: sticky form
// TODO: client-side form validation

// Handle form submission
if (isset($_POST['login'])) {
  $username = isset($_POST['username']) ? $_POST['username'] : null;
  $password = isset($_POST['password']) ? $_POST['password'] : null;

  if ($username && $password) {
    $_SESSION['username'] = null;

    $conn = mysqli_connect('localhost', 'root', 'password', 'message_board');
    if (!$conn) {
      echo '<p>' . mysqli_connect_error() . '</p>';
    }

    $query = "SELECT password_hash FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
      [$password_hash] = mysqli_fetch_row($result);
      if (password_verify($password, $password_hash)) {
        $_SESSION['username'] = $username;
        $_SESSION['alert_text'] = 'Logged in';
        $_SESSION['alert_color'] = 'success';
        header('Location: index.php');
        exit();
      }
    }

    if ($_SESSION['username'] == null) {
      $_SESSION['alert_text'] = 'No user with that username and/or password';
      $_SESSION['alert_color'] = 'danger';
    }

    mysqli_close($conn);
  } else {
    // Incomplete form
    $_SESSION['alert_text'] = 'Enter username and password';
    $_SESSION['alert_color'] = 'danger';
  }
}

$page_title = 'Login';
require 'includes/header.php';

?>

<div style="width: 200px;">
  <h1>Login</h1>
  <form action="login.php" method="post">
    <input type="text" class="form-control mb-3" name="username" placeholder="Username" />
    <input type="password" class="form-control mb-3" name="password" placeholder="Password" />
    <button type="submit" class="btn btn-outline-dark" name="login">Login</button>
  </form>
</div>

<?php require 'includes/footer.php';
?>