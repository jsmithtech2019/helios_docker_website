<?php

// Return to admin page after insertion
header("Location: {$_SERVER['HTTP_REFERER']}");

// Only allow access from admin page
if ($_SERVER['HTTP_REFERER'] !== "http://www.helioscapstone.com/pages/admin.php"){
    exit;
}

$con=mysqli_connect("db","root","helios","hitch");

// Check connection
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// define variables and set to empty values
$nameErr = $emailErr = $phoneErr = $passErr = "";
$name = $email = $phone = $pass = $uuid = $dealership = $dealershipuuid = "";

// Get dealership information
$dealerinfo = mysqli_query($con, "SELECT dealership, BIN_TO_UUID(dealership_uuid) dealership_uuid FROM ADMIN_DATA LIMIT 1");

while($row = mysqli_fetch_array($dealerinfo))
{
    $dealership = $row['dealership'];
    $dealershipuuid = $row['dealership_uuid'];
}

$name = mysqli_real_escape_string($con, $_POST['name']);
$email = mysqli_real_escape_string($con, $_POST['email']);
$phone = mysqli_real_escape_string($con, $_POST['phone']);
$pass = mysqli_real_escape_string($con, $_POST['password']);
$module_uuid = mysqli_real_escape_string($con, $_POST['module_uuid']);
$uuid = uuidv4();

if (isset($module_uuid) && !empty($module_uuid)) {
    $query = "INSERT INTO ADMIN_DATA (dealership, dealership_uuid, name, phone, email, pass, employee_uuid, module_uuid) VALUES ('$dealership', UUID_TO_BIN('$dealershipuuid'), '$name', '$phone', '$email', '$pass', UUID_TO_BIN('$uuid'), UUID_TO_BIN('$module_uuid'))";
} else {
    $query = "INSERT INTO ADMIN_DATA (dealership, dealership_uuid, name, phone, email, pass, employee_uuid) VALUES ('$dealership', UUID_TO_BIN('$dealershipuuid'), '$name', '$phone', '$email', '$pass', UUID_TO_BIN('$uuid'))";
}

//$query = "INSERT INTO ADMIN_DATA (dealership, dealership_uuid, name, phone, email, pass, employee_uuid, module_uuid) VALUES ('$dealership', UUID_TO_BIN('$dealershipuuid'), '$name', '$phone', '$email', '$pass', UUID_TO_BIN('$uuid'), UUID_TO_BIN('$module_uuid'))";

$inserted = mysqli_query($con, $query);

mysqli_close($con);

if ($inserted === TRUE) {
    echo "New record created successfully";
    exit;
} else {
    echo "Error: " . $query . "<br>" . $conn->error;
}

function uuidv4()
{
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),

        // 16 bits for "time_mid"
        mt_rand(0, 0xffff),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand(0, 0x0fff) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand(0, 0x3fff) | 0x8000,

        // 48 bits for "node"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}
?>
