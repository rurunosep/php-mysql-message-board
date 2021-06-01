<?php

session_start();

// TODO: proper error handling
// must be logged in with valid user to post
// also need valid thread id

$thread_id = isset($_POST['thread_id']) ? $_POST['thread_id'] : null;
$body = isset($_POST['body']) ? $_POST['body'] : null;

if ($thread_id && $body) {

  $conn = mysqli_connect('localhost', 'root', 'password', 'message_board');
  if (!$conn) {
    echo '<p>' . mysqli_connect_error() . '</p>';
  }

  // TODO: same as in new_thread
  // Get user ID
  $username = $_SESSION['username'];
  $query = "SELECT user_id FROM users WHERE username='$username'";
  [$user_id] = mysqli_fetch_row(mysqli_query($conn, $query));

  // Create post
  $query = 'INSERT INTO posts (thread_id, user_id, body) VALUES (?, ?, ?)';
  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, 'iis', $thread_id, $user_id, $body);
  mysqli_stmt_execute($stmt);

  if (mysqli_stmt_affected_rows($stmt) == 1) {
    $_SESSION['alert_text'] = 'Added post';
    $_SESSION['alert_color'] = 'success';
    header("Location: thread.php?thread_id=$thread_id");
    exit();
  }
}
