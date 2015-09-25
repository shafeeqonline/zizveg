<?php
session_start();
include('connection.php');
$mailsend = '<html><head><style>td{text-align:center;padding: 5px 10px;}</style></head><body><table><tr><td>Name</td><td>Rs/Unit</td><td>Quantity</td><td>Amount</td></tr>';
$username = $_POST['myData']["username"];
$order = $_POST['myData']["order"];
for ($i = 0; $i < count($order); ++$i) {
    $mailsend .= "<tr><td>" . $order[$i]["name"] . "</td><td>" . $order[$i]["costText"] . "</td><td>" . $order[$i]["quantity"] . "</td><td>" . (float)$order[$i]["quantity"]*(float)$order[$i]["cost"] . "</td></tr>";
}

$sql = "SELECT * FROM usersdb WHERE username = '$username' LIMIT 1";
$result = mysql_query($sql);
$row = mysql_fetch_assoc($result);
$to = "shafeeq.rahman01@gmail.com";
$subject = "Test mail from veggies"; 
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
$mailsend .= "</table><table><tr><td>Name</td><td>Address</td><td>Contact</td></tr>";
if(count($row)){
	$mailsend .= "<tr><td>".$row['fname'] . "</td><td>" . $row['address'] . "</td><td>" . $row['contact'] . "</td><tr></table></body></html>";
	mail($to, $subject, $mailsend, $headers);
	echo "success";
}
else{
	echo "error";
}
//Send mail after cleaning it
mysql_close($bd);
?>