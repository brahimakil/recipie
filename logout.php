<?php
// Start session if needed (not needed for cookie-based logout)
// session_start();

// Unset or expire the cookies
setcookie('email', '', time() - 3600, '/');
setcookie('username', '', time() - 3600, '/');
setcookie('user_image', '', time() - 3600, '/');
setcookie('user_id', '', time() - 3600, '/');

// Redirect to the login page
header("Location: index.php");
exit();
?>
