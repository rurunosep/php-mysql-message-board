<?php

session_start();

// TODO: Figure out how to do all this html body stuff better
// TODO: clean up that dirty admin post/thread delete stuff

$thread_id = isset($_GET['thread_id']) ? $_GET['thread_id'] : null;

$html_body = "";
try {
  if (!$thread_id) {
    throw new Exception('Invalid thread');
  }

  require 'mysql.php';

  // Get thread topic
  $query = "SELECT topic FROM threads WHERE thread_id=$thread_id";
  $result = mysqli_query($conn, $query);
  if (mysqli_num_rows($result) > 0) {
    [$topic] = mysqli_fetch_row($result);
  }

  if (!isset($topic))
    throw new Exception('Invalid thread');

  $page_title = "$topic - RuruBoard";

  // Get posts
  $query = "SELECT post_id, username, body, DATE_FORMAT(posted_on, '%b %e, %Y %r') AS posted_on
  FROM posts
  JOIN users USING (user_id)
  WHERE thread_id=$thread_id";
  $result = mysqli_query($conn, $query);

  $html_body .= '
  <div class="d-flex justify-content-between align-items-center">
    <h1 class="mb-3">' . $topic . '</h1>' .
    ($_SESSION['is_admin']
      ? '<a class="btn btn-outline-danger" href="delete_thread.php?thread_id=' . $thread_id . '">Delete</a>'
      : '')
    . '
  </div>';

  // Render posts
  $html_body .= '<div class="card bg-light mb-3"><div class="card-body pb-0">';
  while ($post = mysqli_fetch_assoc($result)) {
    $html_body .= '
    <div class="card mb-3">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <h5 class="card-title">' . $post['username'] . '</h5>
            <div>
              <small class="card-text text-muted pt-none">' . $post['posted_on'] . '</small>' .
      ($_SESSION['is_admin']
        ? '<a class="btn btn-outline-danger ms-2" href="delete_post.php?post_id=' . $post['post_id'] . '">Delete</a>'
        : '')
      . '
            </div>
          </div>
          <p class="card-text">' . $post['body'] . '</p>
        </div>
    </div>';
  }
  $html_body .= '</div></div>';

  // New post form
  if (isset($_SESSION['user_id'])) {
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

require 'header.php';
echo $html_body;
require 'footer.php';
