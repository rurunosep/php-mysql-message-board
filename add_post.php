<?php

// We're assuming that we have a valid thread and user

session_start();

$thread_id = isset($_POST['thread_id']) ? $_POST['thread_id'] : null;
$body = isset($_POST['body']) ? $_POST['body'] : null;

try {
  if (!$body)
    throw new Exception('Enter post body');

  require 'mysql.php';

  // Get user ID
  if (isset($_SESSION['username'])) {
    $query = "SELECT user_id FROM users WHERE username='" . $_SESSION['username'] . "'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
      [$user_id] = mysqli_fetch_row($result);
    }
  }

  // Create post
  $query = 'INSERT INTO posts (thread_id, user_id, body) VALUES (?, ?, ?)';
  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, 'iis', $thread_id, $user_id, $body);
  mysqli_stmt_execute($stmt);

  $_SESSION['alert_text'] = 'Added post';
  $_SESSION['alert_color'] = 'success';
} catch (Exception $e) {
  $_SESSION['alert_text'] = $e->getMessage();
  $_SESSION['alert_color'] = 'danger';
}

header("Location: thread.php?thread_id=$thread_id");
exit();
