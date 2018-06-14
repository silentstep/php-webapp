<?php
# Start a session to access $_SESSION vars
session_start();
# Unset all $_SESSION vars
session_unset();
# Destroy the session
session_destroy();
# Point to home page;
header("location: /")
?>
