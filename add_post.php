<?php

// We're assuming that we have a valid thread and user

session_start();

$thread_id = isset($_POST['thread_id']) ? $_POST['thread_id'] : null;
$body = isset($_POST['body']) ? $_POST['body'] : null;

try {
  if (!$body)
    throw new Exception('Enter post body');

  require 'mysql.php';

  // Create post
  $query = 'INSERT INTO posts (thread_id, user_id, body) VALUES (?, ?, ?)';
  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, 'iis', $thread_id, $_SESSION['user_id'], $body);
  mysqli_stmt_execute($stmt);

  $_SESSION['alert_text'] = 'Added post';
  $_SESSION['alert_color'] = 'success';
} catch (Exception $e) {
  $_SESSION['alert_text'] = $e->getMessage();
  $_SESSION['alert_color'] = 'danger';
}

header("Location: thread.php?thread_id=$thread_id");
exit();
