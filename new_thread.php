<?php

$page_title = 'New Thread';
require 'includes/header.php';

// TODO: proper error handling
// (must be logged in with a valid user to post)

// Handle form submission
if (isset($_POST['post'])) {
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

    // TODO: get user_id and create post with the same sql query?
    // or just store user_id in session
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
      // TODO: redirect to thread page
      echo '<p>Added post</p>';
    }
  } else {
    // Incomplete form
    echo '<p>Enter thread topic and post body</p>';
  }
}
?>

<h1>New Thread</h1>

<form action="new_thread.php" method="post">
  <div>
    <label for="topic">Topic</label>
    <input type="text" name="topic" id="topic" />
  </div>

  <div>
    <label for="body">Body</label>
    <textarea name="body" id="body"></textarea>
  </div>

  <input type="submit" name="post" value="Post" />
</form>

<?php require 'includes/footer.php';
?>