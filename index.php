<?php

$page_title = 'Home';
require 'includes/header.php';

$conn = mysqli_connect('localhost', 'root', 'password', 'message_board');
if (!$conn) {
  echo '<p>' . mysqli_connect_error() . '</p>';
}

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

if (mysqli_num_rows($result) > 0) {
  echo '<table>
  <thead>
    <tr>
      <th>Topic</th>
      <th>Posts</th>
      <th>Started</th>
      <th>Latest Post</th>
    </tr>
  </thead>';

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
}

require 'includes/footer.php';
