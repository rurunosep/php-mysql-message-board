<?php

session_start();

// TODO: errors
// no thread ID, invalid thread ID, must be logged in to post
// TODO: client-side form validation

$thread_id = isset($_GET['thread_id']) ? $_GET['thread_id'] : null;

if ($thread_id) {
  $conn = mysqli_connect('localhost', 'root', 'password', 'message_board');
  if (!$conn) {
    echo '<p>' . mysqli_connect_error() . '</p>';
  }

  // Get thread topic
  $query = "SELECT topic FROM threads WHERE thread_id=$thread_id";
  $topic = mysqli_fetch_assoc(mysqli_query($conn, $query))['topic'];

  // Get posts
  $query = "SELECT username, body, posted_on
  FROM posts
  JOIN users USING (user_id)
  WHERE thread_id=$thread_id";
  $result = mysqli_query($conn, $query);

  $page_title = "View Thread - $topic";
  require 'includes/header.php';

  echo "<h1 class='mb-3'>$topic</h1>";

  // Render posts
  echo '<div class="card bg-light mb-3"><div class="card-body">';
  while ($post = mysqli_fetch_assoc($result)) {
    // TODO: the mb-3 on the last card is messing stuff up a bit
    echo '
    <div class="card mb-3">
        <div class="card-body">
          <h5 class="card-title">' . $post['username'] . '</h5>
          <h6 class="card-subtitle text-muted">' . $post['posted_on'] . '</h6>
          <p class="card-text">' . $post['body'] . '</p>
        </div>
    </div>';
  }
  echo '</div></div>';

  // New post form
  if (isset($_SESSION['username'])) {
?>

    <form action='add_post.php' method='post'>
      <input type="hidden" name="thread_id" value="<?php echo $thread_id; ?>">
      <textarea class="form-control mb-3" name="body" placeholder="Add a Post"></textarea>
      <button type="submit" class="btn btn-outline-dark">Post</button>
    </form>

<?php
  } else {
    // Not logged in
    echo '<p class="text-muted">Log in to add a post</p>';
  }
} else {
  // No thread ID in request
}

require 'includes/footer.php';

?>