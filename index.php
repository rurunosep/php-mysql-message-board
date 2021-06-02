<?php

session_start();

$conn = mysqli_connect('localhost', 'root', 'password', 'message_board');

// TODO: username of the poster of the first post per thread
$query = "SELECT
    thread_id,
    topic,
    COUNT(post_id) AS num_posts,
    MIN(posted_on) AS started_date,
    MAX(posted_on) AS latest_post_date
  FROM threads
  JOIN posts USING (thread_id)
  GROUP BY (posts.thread_id)";

$result = mysqli_query($conn, $query);

$page_title = 'Home';
require 'includes/header.php';

if (mysqli_num_rows($result) > 0) { ?>

  <h1>Threads</h1>
  <table class="table">
    <thead>
      <tr>
        <th>Topic</th>
        <th>Posts</th>
        <th>Started</th>
        <th>Latest Post</th>
      </tr>
    </thead>

  <?php
  while ($r = mysqli_fetch_assoc($result)) {
    // prettier-ignore
    echo '<tr>
      <td><a href="thread.php?thread_id=' . $r['thread_id'] . '">' . $r['topic'] . '</td>
      <td>' . $r['num_posts'] . '</td>
      <td>' . $r['started_date'] . '</td>
      <td>' . $r['latest_post_date'] . '</td>
    </tr>';
  }

  echo '</table>';
} else {
  // No threads
  echo '<p class="text-muted">No threads created</p>';
}

if (isset($_SESSION['username'])) {
  echo '<a class="btn btn-outline-dark" href="new_thread.php">New Thread</a>';
} else {
  echo '<p class="text-muted">Log in to add a new thread</p>';
}

require 'includes/footer.php';
