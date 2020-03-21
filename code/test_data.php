<?php

$con=mysqli_connect("db","root","helios","hitch");
// Check connection
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$customer = mysqli_query($con, mysqli_real_escape_string($con, "SELECT name, phone, email, truckplate, trailerplate FROM CUSTOMER_DATA"));

while($row = mysqli_fetch_array($customer))
{
    // Create Customer Name Header
    echo "<h2 align='left'>" . $row['name'] . "</h2><br>";

    // Create customer information table
    echo "<p align='left'><b>Customer Email:</b> {$row['email']} | <b>Customer Phone Number:</b> {$row['phone']} | <b>Truck License Plate:</b> {$row['truckplate']} | <b>Trailer License Plate:</b> {$row['trailerplate']}</p>";

    //$truck = mysqli_query($con,"SELECT * FROM TRUCK_TEST_DATA WHERE (cust_phone='{$row['phone']}') OR (cust_email='{$row['email']}') AND (truckplate='{$row['truckplate']}') AND (trailerplate='{$row['trailerplate']}')");
    $truck = mysqli_query($con,"SELECT timestamp, BIN_TO_UUID(module_uuid) module_uuid, test1_current, test1_result, test2_current, test2_result, test3_current, test3_result, test4_current, test4_result FROM TRUCK_TEST_DATA WHERE (cust_phone='{$row['phone']}') OR (cust_email='{$row['email']}')");
    $truckrow = mysqli_fetch_array($truck);

    $employee = mysqli_query($con, "SELECT name FROM ADMIN_DATA WHERE module_uuid=UUID_TO_BIN('{$truckrow['module_uuid']}')");
    $employeerow = mysqli_fetch_array($employee);

    echo "<p align='left'><b>Employee Name:</b> {$employeerow['name']} | <b>Module UUID:</b> {$truckrow['module_uuid']} | <b>Timestamp:</b> {$truckrow['timestamp']}</p><br>";

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

    echo "<th scope='row' style='background-color: #696969;'>Truck</th>";
    foreach ($truckrow as $key => $value) {
        if (strpos($key, 'result')){
            if ($value > 0){
                echo "<td align='center' style='font-size: 14px;' bgcolor='#FFFFFF'>Pass</td>";
            } else {
                echo "<td align='center' style='font-size: 14px; color: #FFFFFF;' bgcolor='#CD5C5C'>Fail</td>";
            }
        } elseif (strpos($key, 'current')) {
            echo "<td align='center' style='font-size: 14px;' bgcolor='#FFFFFF'>" . $truckrow['test1_current'] . " Amps</td>";
        }
    }

    echo "</tr>";

    $trailer = mysqli_query($con,"SELECT test1_current, test1_result, test2_current, test2_result, test3_current, test3_result, test4_current, test4_result FROM TRAILER_TEST_DATA WHERE (cust_phone='{$row['phone']}') OR (cust_email='{$row['email']}')");

    while($trailerrow = mysqli_fetch_array($trailer))
    {
        echo "<th scope='row' style='background-color: #696969;'>Trailer</th>";
        foreach ($trailerrow as $key => $value) {
            if (strpos($key, 'result')){
                if ($value > 0){
                    echo "<td align='center' style='font-size: 14px;' bgcolor='#FFFFFF'>Pass</td>";
                } else {
                    echo "<td align='center' style='font-size: 14px; color: #FFFFFF;' bgcolor='#CD5C5C'>Fail</td>";
                }
            } elseif (strpos($key, 'current')) {
                echo "<td align='center' style='font-size: 14px;' bgcolor='#FFFFFF'>" . $trailerrow['test1_current'] . " Amps</td>";
            }
        }
    }

    echo "</tr></table><br><hr><br>";
}



mysqli_close($con);
?>
