<?php

// TODO: errors
// no thread ID, invalid thread ID, must be logged in to post

// TODO: connect to mysql *before* header and get the thread topic to put in page title
$page_title = 'View Thread';
require 'includes/header.php';

$thread_id = isset($_GET['thread_id']) ? $_GET['thread_id'] : null;
if ($thread_id) {
  $conn = mysqli_connect('localhost', 'root', 'password', 'message_board');
  if (!$conn) {
    echo '<p>' . mysqli_connect_error() . '</p>';
  }

  // Get thread topic
  $query = "SELECT topic FROM threads WHERE thread_id=$thread_id";
  $topic = mysqli_fetch_assoc(mysqli_query($conn, $query))['topic'];

  echo "<h1>$topic</h1>";

  // Get posts
  $query = "SELECT username, body, posted_on
  FROM posts
  JOIN users USING (user_id)
  WHERE thread_id=$thread_id";
  $result = mysqli_query($conn, $query);

  while ($post = mysqli_fetch_assoc($result)) {
    echo '<p>' . $post['username'] . ' (' . $post['posted_on'] . ')<br />' . $post['body'] . '</p>';
  }

  // New post form
  if (isset($_SESSION['username'])) {
?>

    <h2>Add a Post</h2>
    <form action='add_post.php' method='post'>
      <input type="hidden" name="thread_id" value="<?php echo $thread_id; ?>">
      <div>
        <textarea name="body"></textarea>
      </div>
      <input type="submit" value="Post">
    </form>

<?php
  } else {
    // Not logged in
    echo '<p>Must be logged in to add post</p>';
  }
} else {
  // No thread ID in request
}

require 'includes/footer.php';

?>