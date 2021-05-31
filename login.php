<?php

$page_title = 'Login';
require 'includes/header.php';

// TODO: proper error handling

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
        $_SESSION['redirect_message'] = 'Logged in';
        header('Location: index.php');
        exit();
      }
    }

    if ($_SESSION['username'] == null) {
      echo '<p>No user with that username and/or password</p>';
    }

    mysqli_close($conn);
  } else {
    // Incomplete form
    echo '<p>Enter username and password</p>';
  }
}
?>

<h1>Login</h1>
<form action="login.php" method="post">
  <div>
    <label for="username">Username</label>
    <input type="text" name="username" id="username"/>
  </div>

  <div>
    <label for="password">Password</label>
    <input type="password" name="password" id="password"/>
  </div>

  <input type="submit" name="login" value="Login" />
</form>

<?php require 'includes/footer.php';
?>
