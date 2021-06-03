<?php

session_start();

require 'mysql.php';

// TODO: username of the poster of the first post per thread
$query = "SELECT
    thread_id,
    topic,
    COUNT(post_id) AS num_posts,
    DATE_FORMAT(MIN(posted_on), '%b %e, %Y %r') AS started_date,
    DATE_FORMAT(MAX(posted_on), '%b %e, %Y %r') AS latest_post_date
  FROM threads
  LEFT JOIN posts USING (thread_id)
  GROUP BY (posts.thread_id)
  ORDER BY latest_post_date DESC";

$result = mysqli_query($conn, $query);

$page_title = 'RuruBoard';
require 'header.php';

if (mysqli_num_rows($result) > 0) { ?>

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

if (isset($_SESSION['user_id'])) {
  echo '<a class="btn btn-outline-dark" href="new_thread.php">New Thread</a>';
} else {
  echo '<p class="text-muted">Log in to add a new thread</p>';
}

require 'footer.php';
