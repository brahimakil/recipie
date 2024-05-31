<?php

session_start();

session_unset();
// Destroy the session
session_destroy();

// Redirect to the index page
header("Location: index.php");
exit();
