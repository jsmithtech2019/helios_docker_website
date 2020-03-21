<?php

// Return to admin page after insertion
header("Location: {$_SERVER['HTTP_REFERER']}");

$con=mysqli_connect("db","root","helios","hitch");

// Check connection
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$email = $uuid = "";

$email = mysqli_real_escape_string($con, $_POST['email']);
$uuid = mysqli_real_escape_string($con, $_POST['employee_uuid']);

$query = "DELETE FROM ADMIN_DATA WHERE (email='$email') AND (employee_uuid=UUID_TO_BIN('$uuid'))";

$inserted = mysqli_query($con, $query);

if ($inserted === TRUE) {
    echo "Record updated successfully.";
} else {
    echo "Error: " . $query . "<br>" . $conn->error;
}

mysqli_close($con);

exit;

?>