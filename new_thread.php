<?php

session_start();

// TODO: proper error handling
// (must be logged in with a valid user to post)
// TODO: sticky form
// TODO: client-side form validation

// Handle form submission
if (isset($_POST['create-thread'])) {
  $topic = isset($_POST['topic']) ? $_POST['topic'] : null;
  $body = isset($_POST['body']) ? $_POST['body'] : null;

  if ($topic && $body) {
    $conn = mysqli_connect('localhost', 'root', 'password', 'message_board');
    if (!$conn) {
      echo '<p>' . mysqli_connect_error() . '</p>';
    }

    // Create thread
    $query = 'INSERT INTO threads (topic) VALUES (?)';
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 's', $topic);
    mysqli_stmt_execute($stmt);
    $thread_id = mysqli_stmt_insert_id($stmt);

    // Get user ID
    // TODO: get user_id and create post with the same sql query?
    // or just store user_id in session
    $username = $_SESSION['username'];
    $query = "SELECT user_id FROM users WHERE username='$username'";
    [$user_id] = mysqli_fetch_row(mysqli_query($conn, $query));

    // Create post
    $query = 'INSERT INTO posts (thread_id, user_id, body) VALUES (?, ?, ?)';
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'iis', $thread_id, $user_id, $body);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) == 1) {
      $_SESSION['alert_text'] = 'Created thread';
      $_SESSION['alert_color'] = 'success';
      header("Location: thread.php?thread_id=$thread_id");
      exit();
    }
  } else {
    // Incomplete form
    $_SESSION['alert_text'] = 'Enter thread topic and post';
    $_SESSION['alert_color'] = 'danger';
  }
}

$page_title = 'New Thread';
require 'includes/header.php';

?>

<h1>New Thread</h1>
<form action="new_thread.php" method="post">
  <input type="text" class="form-control mb-3" name="topic" placeholder="Topic" />
  <textarea class="form-control mb-3" name="body" placeholder="Post"></textarea>
  <button type="submit" class="btn btn-outline-dark" name="create-thread">Create Thread</button>
</form>

<?php require 'includes/footer.php';
?>