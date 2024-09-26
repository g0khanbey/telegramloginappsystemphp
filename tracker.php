<?php
session_start();

// Check if username is provided
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = session_id();
}

$username = isset($_GET['username']) ? $_GET['username'] : 'Guest'; // Get username from GET request

$session_id = $_SESSION['user_id'];
$filename = 'user_activity_log.txt';

// Update user's last activity
logUserActivity($filename, $session_id, $username);

/**
 * Logs the user's activity into a file.
 *
 * @param string $filename  The log file name.
 * @param string $userId    The session ID.
 * @param string $username  The username.
 */
function logUserActivity($filename, $userId, $username) {
    $current_time = time();
    $log_entry = "{$userId},{$username},{$current_time}\n";

    // Append the log entry to the file
    file_put_contents($filename, $log_entry, FILE_APPEND);
}

echo "User {$username} logged successfully.";
?>
