<?php

// TODO: clean

session_start();

$thread_id = isset($_GET['thread_id']) ? $_GET['thread_id'] : null;
$is_admin = isset($_SESSION['is_admin']) ? $_SESSION['is_admin'] : null;

try {
  if (!($thread_id))
    throw new Exception('No thread ID');

  if (!$is_admin)
    throw new Exception('Must be an admin to delete threads');

  require 'mysql.php';

  // Delete posts
  $query = 'DELETE FROM posts WHERE thread_id=' . $thread_id;
  $result = mysqli_query($conn, $query);

  // Delete thread
  $query = 'DELETE FROM threads WHERE thread_id=' . $thread_id;
  $result = mysqli_query($conn, $query);

  $_SESSION['alert_text'] = "Deleted thread";
  $_SESSION['alert_color'] = 'success';
} catch (Exception $e) {
  $_SESSION['alert_text'] = $e->getMessage();
  $_SESSION['alert_color'] = 'danger';
}

header('Location: index.php');
exit();
