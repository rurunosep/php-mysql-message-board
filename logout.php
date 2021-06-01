<?php

session_start();

$_SESSION['username'] = null;

$_SESSION['alert_text'] = 'Logged out';
$_SESSION['alert_color'] = 'success';
header('Location: index.php');
exit();
