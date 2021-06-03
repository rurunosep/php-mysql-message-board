<?php

session_start();

// Handle form submission
if (isset($_POST['login'])) {
  $username = isset($_POST['username']) ? $_POST['username'] : null;
  $password = isset($_POST['password']) ? $_POST['password'] : null;

  try {
    if (!($username && $password))
      throw new Exception('Enter username and password');

    $_SESSION['user_id'] = null;
    $_SESSION['username'] = null;
    $_SESSION['is_admin'] = false;

    require 'mysql.php';

    $query = "SELECT user_id, password_hash, is_admin FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
      [$user_id, $password_hash, $is_admin] = mysqli_fetch_row($result);
      if (password_verify($password, $password_hash)) {
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username;
        $_SESSION['is_admin'] = $is_admin == '1' ? true : false;
        $_SESSION['alert_text'] = "Logged in $username";
        $_SESSION['alert_color'] = 'success';
        header('Location: index.php');
        exit();
      }
    }

    if ($_SESSION['user_id'] == null)
      throw new Exception('No user with that username and/or password');
  } catch (Exception $e) {
    $_SESSION['alert_text'] = $e->getMessage();
    $_SESSION['alert_color'] = 'danger';
  }
}

$page_title = 'Login - RuruBoard';
require 'header.php';

?>

<div style="width: 200px;">
  <h1>Login</h1>
  <form action="login.php" method="post">
    <input type="text" class="form-control mb-3" name="username" placeholder="Username" />
    <input type="password" class="form-control mb-3" name="password" placeholder="Password" />
    <button type="submit" class="btn btn-outline-dark" name="login">Login</button>
  </form>
</div>

<?php require 'footer.php';
?>