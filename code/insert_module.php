<?php

// Return to admin page after insertion
header("Location: {$_SERVER['HTTP_REFERER']}");

$con=mysqli_connect("db","root","helios","hitch");

// Check connection
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$email = $module = "";

$email = mysqli_real_escape_string($con, $_POST['employee_email']);
$module = mysqli_real_escape_string($con, $_POST['module']);

$query = "UPDATE ADMIN_DATA SET module_uuid=NULL WHERE module_uuid=UUID_TO_BIN('$module')";

mysqli_query($con, $query);

$inserted = mysqli_query($con, "UPDATE ADMIN_DATA SET module_uuid=UUID_TO_BIN('$module') WHERE email='$email'");

if ($inserted === TRUE) {
    echo "Record updated successfully.";
} else {
    echo "Error: " . $query . "<br>" . $conn->error;
}

mysqli_close($con);

exit;

?>