<?php
require_once '../includes/config.php';

// Destroy session and logout
session_destroy();
session_start();

set_message('You have been logged out successfully.', 'success');
redirect('../index.php');
?>
