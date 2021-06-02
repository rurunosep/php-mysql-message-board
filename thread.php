<?php

session_start();

// TODO: do it nicer

$thread_id = isset($_GET['thread_id']) ? $_GET['thread_id'] : null;

$html_body = "";
try {
  if (!$thread_id) {
    throw new Exception('Invalid thread');
  }

  $conn = mysqli_connect('localhost', 'root', 'password', 'message_board');

  // Get thread topic
  $query = "SELECT topic FROM threads WHERE thread_id=$thread_id";
  $result = mysqli_query($conn, $query);
  if (mysqli_num_rows($result) > 0) {
    [$topic] = mysqli_fetch_row($result);
  }

  if (!isset($topic))
    throw new Exception('Invalid thread');

  $page_title = "View Thread - $topic";

  // Get posts
  $query = "SELECT username, body, posted_on
  FROM posts
  JOIN users USING (user_id)
  WHERE thread_id=$thread_id";
  $result = mysqli_query($conn, $query);

  $html_body .= "<h1 class='mb-3'>$topic</h1>";

  // Render posts
  $html_body .= '<div class="card bg-light mb-3"><div class="card-body">';
  while ($post = mysqli_fetch_assoc($result)) {
    // TODO: the mb-3 on the last card is messing stuff up a bit
    $html_body .= '
    <div class="card mb-3">
        <div class="card-body">
          <h5 class="card-title">' . $post['username'] . '</h5>
          <h6 class="card-subtitle text-muted">' . $post['posted_on'] . '</h6>
          <p class="card-text">' . $post['body'] . '</p>
        </div>
    </div>';
  }
  $html_body .= '</div></div>';

  // New post form
  if (isset($_SESSION['username'])) {

    $html_body .=
      '<form action="add_post.php" method="post">
      <input type="hidden" name="thread_id" value="' . $thread_id . '">
      <textarea class="form-control mb-3" name="body" placeholder="Add a Post"></textarea>
      <button type="submit" class="btn btn-outline-dark">Post</button>
    </form>';
  } else { // Not logged in
    $html_body .= '<p class="text-muted">Log in to add a post</p>';
  }
} catch (Exception $e) {
  $_SESSION['alert_text'] = $e->getMessage();
  $_SESSION['alert_color'] = 'danger';
}

$page_title = isset($page_title) ? $page_title : 'View Thread';

require 'includes/header.php';
echo $html_body;
require 'includes/footer.php';
