<?php

// Create mysql connection
$con=mysqli_connect("db","root","helios","hitch");

// Check connection
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// Get customer information from database
$customer = mysqli_query($con, mysqli_real_escape_string($con, "SELECT name, phone, email, truckplate, trailerplate FROM CUSTOMER_DATA"));

// For every distinct customer (with same truck/trailer) print their name
while($row = mysqli_fetch_array($customer))
{
    // Create Customer Name Header
    echo "<h2 align='left'>" . $row['name'] . "</h2><br>";

    // Get truck test information
    $query = "SELECT truck_plate, trailer_plate, timestamp, BIN_TO_UUID(module_uuid) module_uuid, BIN_TO_UUID(employee_uuid) employee_uuid, test1_current, test1_result, test2_current, test2_result, test3_current, test3_result, test4_current, test4_result FROM TRUCK_TEST_DATA WHERE (truck_plate='{$row['truckplate']}') AND (trailer_plate='{$row['trailerplate']}') AND (cust_phone='{$row['phone']}') AND (cust_email='{$row['email']}')";
    $truck = mysqli_query($con, $query);

    // Get trailer information
    $query = "SELECT test1_current, test1_result, test2_current, test2_result, test3_current, test3_result, test4_current, test4_result FROM TRAILER_TEST_DATA WHERE (cust_phone='{$row['phone']}') OR (cust_email='{$row['email']}')";
    $trailer = mysqli_query($con, $query);

    // Print info & tables for every test run on this truck/trailer combo
    while(($truckrow = mysqli_fetch_array($truck)) && ($trailerrow = mysqli_fetch_array($trailer))) {
        // Create customer information table
        echo "<p align='left'><b>Customer Email:</b> {$row['email']} | <b>Customer Phone Number:</b> {$row['phone']} | <b>Truck License Plate:</b> {$truckrow['truck_plate']} | <b>Trailer License Plate:</b> {$truckrow['trailer_plate']}</p>";

        // Get employee name that ran the test
        $query = "SELECT name FROM ADMIN_DATA WHERE employee_uuid=UUID_TO_BIN('{$truckrow['employee_uuid']}')";
        $employee = mysqli_query($con, $query);
        $employeerow = mysqli_fetch_array($employee);

        // Print the employee information
        echo "<p align='left'><b>Employee Name:</b> {$employeerow['name']} | <b>Module UUID:</b> {$truckrow['module_uuid']} | <b>Timestamp:</b> {$truckrow['timestamp']}</p><br>";

        // Create the table information for test run
        echo "<table align='center' style='width: 90%' border='0' id='prettytable'>
            <colgroup span='2'></colgroup>
            <tr>
            <td rowspan='2' style='border: 0px;'></td>
            <th colspan='2' style='background-color: #696969;' scope='colgroup'>Left Turn Signal</th>
            <th colspan='2' style='background-color: #696969;' scope='colgroup'>Right Turn Signal</th>
            <th colspan='2' style='background-color: #696969;' scope='colgroup'>Tail Lights</th>
            <th colspan='2' style='background-color: #696969;' scope='colgroup'>Reverse Lights</th>
            </tr>
            <tr>
                <th class='text-center' style='font-size: 14px; background-color: #C0C0C0; color: #000000' scope='colgroup'>Reading</th>
                <th class='text-center' style='font-size: 14px; background-color: #C0C0C0; color: #000000' scope='colgroup'>Result</th>
                <th class='text-center' style='font-size: 14px; background-color: #C0C0C0; color: #000000' scope='colgroup'>Reading</th>
                <th class='text-center' style='font-size: 14px; background-color: #C0C0C0; color: #000000' scope='colgroup'>Result</th>
                <th class='text-center' style='font-size: 14px; background-color: #C0C0C0; color: #000000' scope='colgroup'>Reading</th>
                <th class='text-center' style='font-size: 14px; background-color: #C0C0C0; color: #000000' scope='colgroup'>Result</th>
                <th class='text-center' style='font-size: 14px; background-color: #C0C0C0; color: #000000' scope='colgroup'>Reading</th>
                <th class='text-center' style='font-size: 14px; background-color: #C0C0C0; color: #000000' scope='colgroup'>Result</th>
            </tr>";

        // Create truck results row
        echo "<th scope='row' style='background-color: #696969;'>Truck</th>";

        // Print each result of truck results
        // Needed as arrays have weird key pairing in PHP (specifically index 0)
        foreach ($truckrow as $key => $value) {
            if (strpos($key, 'result')){
                if ($value > 0){
                    // If test passed, color normal and print Pass
                    echo "<td align='center' style='font-size: 14px;' bgcolor='#FFFFFF'>Pass</td>";
                } else {
                    // If test failed, color red and print Fail
                    echo "<td align='center' style='font-size: 14px; color: #FFFFFF;' bgcolor='#CD5C5C'>Fail</td>";
                }
            } elseif (strpos($key, 'current')) {
                // Print current value for each test
                echo "<td align='center' style='font-size: 14px;' bgcolor='#FFFFFF'>$value Amps</td>";
            }
        }

        // End truck row
        echo "</tr>";

        // Print each result of trailer results
        echo "<th scope='row' style='background-color: #696969;'>Trailer</th>";
        foreach ($trailerrow as $key => $value) {
            if (strpos($key, 'result')){
                if ($value > 0){
                    // If test passed, color normal and print Pass
                    echo "<td align='center' style='font-size: 14px;' bgcolor='#FFFFFF'>Pass</td>";
                } else {
                    // If test failed, color red and print Fail
                    echo "<td align='center' style='font-size: 14px; color: #FFFFFF;' bgcolor='#CD5C5C'>Fail</td>";
                }
            } elseif (strpos($key, 'current')) {
                // Print current value for each test
                echo "<td align='center' style='font-size: 14px;' bgcolor='#FFFFFF'>$value Amps</td>";
            }
        }

        // End row and table, add spacing
        echo "</tr></table><br><br>";
    }

    // Print spacing line after each distinct customer-truck/trailer pairing
    echo "<hr>";
}

// Close connection
mysqli_close($con);

?>