<?php
// Start the session
session_start();

// Destroy the session to log the user out
session_destroy();

// Redirect to the portal page
header("Location: ./../index.php");
exit();
