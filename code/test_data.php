<?php
$con=mysqli_connect("db","root","helios","hitch");
// Check connection
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

echo "<table style='width: 100%' border='3'>
        <tr>
            <th class='text-center' bgcolor='#FFFFFF' style='font-size: 12px;'>Customer Name</th>
            <th class='text-center' bgcolor='#FFFFFF' style='font-size: 12px;'>Customer Email</th>
            <th class='text-center' bgcolor='#FFFFFF' style='font-size: 12px;'>Customer Phone</th>
            <th class='text-center' bgcolor='#FFFFFF' style='font-size: 12px;'>Truck Result 1</th>
            <th class='text-center' bgcolor='#FFFFFF' style='font-size: 12px;'>Truck Current 1</th>
            <th class='text-center' bgcolor='#FFFFFF' style='font-size: 12px;'>Truck Result 2</th>
            <th class='text-center' bgcolor='#FFFFFF' style='font-size: 12px;'>Truck Current 2</th>
            <th class='text-center' bgcolor='#FFFFFF' style='font-size: 12px;'>Truck Result 3</th>
            <th class='text-center' bgcolor='#FFFFFF' style='font-size: 12px;'>Truck Current 3</th>
            <th class='text-center' bgcolor='#FFFFFF' style='font-size: 12px;'>Truck Result 3</th>
            <th class='text-center' bgcolor='#FFFFFF' style='font-size: 12px;'>Truck Current 3</th>
            <th class='text-center' bgcolor='#FFFFFF' style='font-size: 12px;'>Trailer Result 1</th>
            <th class='text-center' bgcolor='#FFFFFF' style='font-size: 12px;'>Trailer Current 1</th>
            <th class='text-center' bgcolor='#FFFFFF' style='font-size: 12px;'>Trailer Result 2</th>
            <th class='text-center' bgcolor='#FFFFFF' style='font-size: 12px;'>Trailer Current 2</th>
            <th class='text-center' bgcolor='#FFFFFF' style='font-size: 12px;'>Trailer Result 3</th>
            <th class='text-center' bgcolor='#FFFFFF' style='font-size: 12px;'>Trailer Current 3</th>
            <th class='text-center' bgcolor='#FFFFFF' style='font-size: 12px;'>Trailer Result 3</th>
            <th class='text-center' bgcolor='#FFFFFF' style='font-size: 12px;'>Trailer Current 3</th>
            <th class='text-center' bgcolor='#FFFFFF' style='font-size: 12px;'>Timestamp</th>
        </tr>";

$customer = mysqli_query($con, "SELECT name, phone, email FROM CUSTOMER_DATA");

while($row = mysqli_fetch_array($customer))
{
    echo "<tr>";
    echo "<td align='center' style='font-size: 12px;' bgcolor='#FFFFFF'>" . $row['name'] . "</td>";
    echo "<td align='center' style='font-size: 12px;' bgcolor='#FFFFFF'>" . $row['email'] . "</td>";
    echo "<td align='center' style='font-size: 12px;' bgcolor='#FFFFFF'>" . $row['phone'] . "</td>";
    $truck = mysqli_query($con,"SELECT * FROM TRUCK_TEST_DATA WHERE (cust_phone='{$row['phone']}') OR (cust_email='{$row['email']}')");

    while($truckrow = mysqli_fetch_array($truck))
    {
        echo "<td align='center' style='font-size: 12px;' bgcolor='#FFFFFF'>" . $truckrow['test1_result'] . "</td>";
        echo "<td align='center' style='font-size: 12px;' bgcolor='#FFFFFF'>" . $truckrow['test1_current'] . "</td>";
        echo "<td align='center' style='font-size: 12px;' bgcolor='#FFFFFF'>" . $truckrow['test2_result'] . "</td>";
        echo "<td align='center' style='font-size: 12px;' bgcolor='#FFFFFF'>" . $truckrow['test2_current'] . "</td>";
        echo "<td align='center' style='font-size: 12px;' bgcolor='#FFFFFF'>" . $truckrow['test3_result'] . "</td>";
        echo "<td align='center' style='font-size: 12px;' bgcolor='#FFFFFF'>" . $truckrow['test3_current'] . "</td>";
        echo "<td align='center' style='font-size: 12px;' bgcolor='#FFFFFF'>" . $truckrow['test4_result'] . "</td>";
        echo "<td align='center' style='font-size: 12px;' bgcolor='#FFFFFF'>" . $truckrow['test4_current'] . "</td>";
    }


    $trailer = mysqli_query($con,"SELECT * FROM TRAILER_TEST_DATA WHERE (cust_phone='{$row['phone']}') OR (cust_email='{$row['email']}')");

    while($trailerrow = mysqli_fetch_array($trailer))
    {
        echo "<td align='center' style='font-size: 12px;' bgcolor='#FFFFFF'>" . $trailerrow['test1_result'] . "</td>";
        echo "<td align='center' style='font-size: 12px;' bgcolor='#FFFFFF'>" . $trailerrow['test1_current'] . "</td>";
        echo "<td align='center' style='font-size: 12px;' bgcolor='#FFFFFF'>" . $trailerrow['test2_result'] . "</td>";
        echo "<td align='center' style='font-size: 12px;' bgcolor='#FFFFFF'>" . $trailerrow['test2_current'] . "</td>";
        echo "<td align='center' style='font-size: 12px;' bgcolor='#FFFFFF'>" . $trailerrow['test3_result'] . "</td>";
        echo "<td align='center' style='font-size: 12px;' bgcolor='#FFFFFF'>" . $trailerrow['test3_current'] . "</td>";
        echo "<td align='center' style='font-size: 12px;' bgcolor='#FFFFFF'>" . $trailerrow['test4_result'] . "</td>";
        echo "<td align='center' style='font-size: 12px;' bgcolor='#FFFFFF'>" . $trailerrow['test4_current'] . "</td>";
        echo "<td align='center' style='font-size: 12px;' bgcolor='#FFFFFF'>" . $trailerrow['timestamp'] . "</td>";
    }

    echo "</tr>";
}

echo "</table>";

mysqli_close($con);
?>
