<?php

// TODO: clean
// TODO: redirect to thread instead of index

session_start();

$post_id = isset($_GET['post_id']) ? $_GET['post_id'] : null;
$is_admin = isset($_SESSION['is_admin']) ? $_SESSION['is_admin'] : null;

try {
  if (!($post_id))
    throw new Exception('No post ID');

  if (!$is_admin)
    throw new Exception('Must be an admin to delete posts');

  require 'mysql.php';

  $query = 'DELETE FROM posts WHERE post_id=' . $post_id;
  $result = mysqli_query($conn, $query);

  $_SESSION['alert_text'] = "Deleted post";
  $_SESSION['alert_color'] = 'success';
} catch (Exception $e) {
  $_SESSION['alert_text'] = $e->getMessage();
  $_SESSION['alert_color'] = 'danger';
}

header('Location: index.php');
exit();
