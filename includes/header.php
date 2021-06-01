<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
  <title><?php echo $page_title; ?></title>
</head>

<body>

  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="index.php">Home</a>
      <div>
        <?php if (isset($_SESSION['username'])) : ?>
          <span class="navbar-text">Logged in as <?php echo $_SESSION['username']; ?></span>
          <a class="btn btn-outline-light" href="logout.php">Logout</a>;
        <?php else : ?>
          <a class="btn btn-outline-light" href="login.php">Login</a>;
          <a class="btn btn-outline-light" href="register.php">Register</a>;
        <?php endif; ?>
      </div>
    </div>
  </nav>

  <div class="container mt-3">

    <?php
    if (isset($_SESSION['alert_text'], $_SESSION['alert_color'])) {
      echo
      '<div class="alert alert-' . $_SESSION['alert_color'] . ' alert-dismissible fade show">'
        . $_SESSION['alert_text'] .
        '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>';
      $_SESSION['alert_text'] = null;
      $_SESSION['alert_color'] = null;
    }
    ?>