<?php

$con=mysqli_connect("db","root","helios","hitch");

// Check connection
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

echo "<hr><h2 align='left'>Dealership Information:</h2><br>";

echo "<table style='width: 45%' align='center' border='3' id='prettytable'>
        <tr>
            <th class='text-center' bgcolor='#FFFFFF' style='font-size: 14px;'>Dealership Name</th>
            <th class='text-center' bgcolor='#FFFFFF' style='font-size: 14px;'>Dealership UUID</th>
        </tr>";

$dealershipinfo = mysqli_query($con, "SELECT BIN_TO_UUID(dealership_uuid) dealership_uuid, dealership FROM ADMIN_DATA LIMIT 1");

while($row = mysqli_fetch_array($dealershipinfo))
{
    echo "<tr>";
    echo "<td align='center' style='font-size: 14px;' bgcolor='#FFFFFF'>" . $row['dealership'] . "</td>";
    echo "<td align='center' style='font-size: 14px;' bgcolor='#FFFFFF'>" . $row['dealership_uuid'] . "</td>";
    echo "</tr>";
}
echo "</table>";

// Create current employees table
echo "<hr><h2 align='left'>Current Employees:</h2><br>";

echo "<table style='width: 90%' border='3' align='center' id='prettytable'>
        <tr>
            <th bgcolor='#FFFFFF' style='font-size: 14px;'>Employee Name</th>
            <th bgcolor='#FFFFFF' style='font-size: 14px;'>Employee Phone</th>
            <th bgcolor='#FFFFFF' style='font-size: 14px;'>Employee Email</th>
            <th bgcolor='#FFFFFF' style='font-size: 14px;'>Employee UUID</th>
        </tr>";

// Get values from MySQL database
$employee = mysqli_query($con, mysqli_real_escape_string($con, "SELECT name, phone, email, BIN_TO_UUID(employee_uuid) employee_uuid FROM ADMIN_DATA"));

while($row = mysqli_fetch_array($employee))
{
    echo "<tr>";
    echo "<td align='center' style='font-size: 14px;' bgcolor='#FFFFFF'>" . $row['name'] . "</td>";
    echo "<td align='center' style='font-size: 14px;' bgcolor='#FFFFFF'>" . $row['phone'] . "</td>";
    echo "<td align='center' style='font-size: 14px;' bgcolor='#FFFFFF'>" . $row['email'] . "</td>";
    echo "<td align='center' style='font-size: 14px;' bgcolor='#FFFFFF'>" . $row['employee_uuid'] . "</td>";

    echo "</tr>";
}

echo "</table>";

// Create assigned modules table
echo "<hr><h2 align='left'>Assigned Modules:</h2><br>";
echo "<table style='width: 45%' align='center' border='3' id='prettytable'>
        <tr>
        <th class='text-center' bgcolor='#FFFFFF' style='font-size: 14px;'>Employee</th>
        <th class='text-center' bgcolor='#FFFFFF' style='font-size: 14px;'>HITCH Module</th>
        </tr>";

// Get values from MySQL database
$modules = mysqli_query($con, "SELECT name, BIN_TO_UUID(module_uuid) module_uuid FROM ADMIN_DATA WHERE module_uuid IS NOT NULL");

while($row = mysqli_fetch_array($modules))
{
    echo "<tr>";
    echo "<td align='center' style='font-size: 14px;' bgcolor='#FFFFFF'>" . $row['name'] . "</td>";
    echo "<td align='center' style='font-size: 14px;' bgcolor='#FFFFFF'>" . $row['module_uuid'] . "</td>";
    echo "</tr>";
}
echo "</table>";

// Create update assignments
echo "<hr><h2 align='left'>Update Module Assignment:</h2><br>";

echo '<form action="../insert_module.php" method="post">';
    echo 'New Employee Email: &nbsp;<input style="color: black" type="text" name="employee_email">';
    echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
    echo 'Module UUID: &nbsp;<input style="color: black" type="text" name="module"><br><br>';
    echo '<input type="submit" style="color: black">';
echo '</form>';

// Exit MySQL connection
mysqli_close($con);

?>