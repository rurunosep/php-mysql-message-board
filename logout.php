<?php

session_start();

$_SESSION['user_id'] = null;
$_SESSION['username'] = null;
$_SESSION['is_admin'] = false;

$_SESSION['alert_text'] = 'Logged out';
$_SESSION['alert_color'] = 'success';
header('Location: index.php');
exit();
