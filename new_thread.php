<?php

session_start();

// Handle form submission
if (isset($_POST['create-thread'])) {
  $topic = isset($_POST['topic']) ? $_POST['topic'] : null;
  $body = isset($_POST['body']) ? $_POST['body'] : null;

  try {

    if (!($topic && $body))
      throw new Exception('Enter thread topic and post');

    if (!isset($_SESSION['user_id']))
      throw new Exception('Must be logged in to create a thread');

    require 'mysql.php';

    // Create thread
    $query = 'INSERT INTO threads (topic) VALUES (?)';
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 's', $topic);
    mysqli_stmt_execute($stmt);
    $thread_id = mysqli_stmt_insert_id($stmt);

    // Create post
    $query = 'INSERT INTO posts (thread_id, user_id, body) VALUES (?, ?, ?)';
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'iis', $thread_id, $_SESSION['user_id'], $body);
    mysqli_stmt_execute($stmt);

    $_SESSION['alert_text'] = 'Created thread';
    $_SESSION['alert_color'] = 'success';
    header("Location: thread.php?thread_id=$thread_id");
    exit();
  } catch (Exception $e) {
    $_SESSION['alert_text'] = $e->getMessage();
    $_SESSION['alert_color'] = 'danger';
  }
}

$page_title = 'New Thread - RuruBoard';
require 'header.php';

?>

<h1>New Thread</h1>
<form action="new_thread.php" method="post">
  <input type="text" class="form-control mb-3" name="topic" placeholder="Topic" />
  <textarea class="form-control mb-3" name="body" placeholder="Post"></textarea>
  <button type="submit" class="btn btn-outline-dark" name="create-thread">Create Thread</button>
</form>

<?php require 'footer.php';
?>