<?php

$page_title = 'Logout';
require 'includes/header.php';

$_SESSION['username'] = null;

$_SESSION['redirect_message'] = 'Logged out';
header('Location: index.php');
exit();

require 'includes/footer.php';
?>
