<?php

if (getenv('ENVIRONMENT') == 'production') {
  $db_url = parse_url(getenv('CLEARDB_DATABASE_URL'));
  $db_host = $db_url['host'];
  $db_username = $db_url['user'];
  $db_password = $db_url['pass'];
  $db_database = substr($db_url['path'], 1);
} else {
  require('config.php');
}

$conn = mysqli_connect($db_host, $db_username, $db_password, $db_database);
