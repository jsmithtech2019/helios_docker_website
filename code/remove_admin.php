<?php

// Return to admin page after deletion
header("Location: {$_SERVER['HTTP_REFERER']}");

// Create connection
$con=mysqli_connect("db","root","helios","hitch");

// Check connection
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// Clear variable values
$email = $uuid = "";

// Sanitize and get information from user input
$email = mysqli_real_escape_string($con, $_POST['email']);
$uuid = mysqli_real_escape_string($con, $_POST['employee_uuid']);

// Update admin data
$query = "DELETE FROM ADMIN_DATA WHERE (email='$email') AND (employee_uuid=UUID_TO_BIN('$uuid'))";

// Get boolean result of query
$inserted = mysqli_query($con, $query);

if ($inserted === TRUE) {
    echo "Record updated successfully.";
} else {
    echo "Error: " . $query . "<br>" . $conn->error;
}

// Close connection
mysqli_close($con);

// Exit script
exit;

?>