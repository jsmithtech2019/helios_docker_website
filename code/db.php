<?php
$con=mysqli_connect("db","root","helios","hitch");
// Check connection
if (mysqli_connect_errno())
{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$result = mysqli_query($con,"SELECT * FROM CUSTOMER_DATA");

echo "<table border='3'>
<tr>
<th bgcolor='#FFFFFF'>Name</th>
<th bgcolor='#FFFFFF'>Phone</th>
<th bgcolor='#FFFFFF'>Email</th>
</tr>";

while($row = mysqli_fetch_array($result))
{
echo "<tr>";
echo "<td bgcolor='#FFFFFF'>" . $row['name'] . "</td>";
echo "<td bgcolor='#FFFFFF'>" . $row['phone'] . "</td>";
echo "<td bgcolor='#FFFFFF'>" . $row['email'] . "</td>";
echo "</tr>";
}
echo "</table>";

mysqli_close($con);
?>
