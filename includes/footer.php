<a href="index.php">Home</a></br>

<?php if (isset($_SESSION['username'])): ?>
  <a href="logout.php">Logout</a></br>
<?php else: ?>
  <a href="login.php">Login</a></br>
  <a href="register.php">Register</a></br>
<?php endif; ?>

</body>
</html>