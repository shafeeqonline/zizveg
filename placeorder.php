<?php
session_start();
include('connection.php');
$username = $_POST['myData']["username"];
$order = $_POST['myData']["order"];

$mailsend = '<html xmlns="http://www.w3.org/1999/xhtml">
<head>
</head>
<body itemscope itemtype="http://schema.org/EmailMessage">

<table class="body-wrap">
  <tr>
    <td></td>
    <td class="container" width="600">
      <div class="content">
        <table class="main" width="100%" cellpadding="0" cellspacing="0">
          <tr><td width:100%;><table class="main2" width="100%" cellpadding="0" cellspacing="0">
            <tr>
              <td width:100%;>
                <table>
                  <tr><td><h2 style="margin:0;">ZIZ Veggies</h2></td></tr>
                  <tr><td>Fraser town, Bangalore.</td></tr>
                  <tr><td>orders@zizveg.com</td></tr>
                  <tr><td>www.zizveg.com/</td></tr>
                </table>
              </td>
            <td><img style="width:100px;float:right;" src="http://zizveg.com/zizlogo.jpg"></td></tr>
          </table></td></tr>
          <tr><td><hr></td></tr>
          <tr><td><table width="100%"><tr><td width="80%">ZIZ Veggies</td><td align="right" style="float:right;">Date : '.date("d/m/Y").'</td><tr><td>'.$username.'</td></tr></tr></table></td></tr>
          <tr>
          <table  cellpadding="0" cellspacing="0" style="width:100%;text-align:center;">
          <tr style="background:gray">
          	<td style="width:50%;padding:10px;text-align:left;">Description</td>
          	<td>Rate</td>
          	<td>QTY</td>
          	<td>Amount</td>
          </tr>';
for ($i = 0; $i < count($order); ++$i) {
    $mailsend .= "<tr><td>" . $order[$i]["name"] . "</td><td>" . $order[$i]["costText"] . "</td><td>" . $order[$i]["quantity"] . "</td><td>" . (float)$order[$i]["quantity"]*(float)$order[$i]["cost"] . "</td></tr>";
}
for ($i = 0; $i < count($order); ++$i) {
    $totalbill += (float)$order[$i]["quantity"]*(float)$order[$i]["cost"];
}
$mailsend .= "<tr><td><hr></td><td><hr></td><td><hr></td><td><hr></td></tr><tr><td style='width:50%;padding:10px;'></td><td></td><td>Total</td><td>" . $totalbill . "</td></tr>";
$mailsend .= '
  <tr><br><br></tr>
  <tr><td></td><td></td><td></td><td style="border-top: 1px dotted black;text-align: center;padding-top: 5px;" align="right">'.date("d/m/Y").'</td></tr></table></tr>
        </table></div>
    </td>
    <td></td>
  </tr>
</table>

</body>
</html>';

$sql = "SELECT * FROM outlets WHERE username = '$username' LIMIT 1";
$result = mysql_query($sql);
$row = mysql_fetch_assoc($result);
$subject = "Order recieved and confirmed by ZIZ Veggies";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
$header = "From: zizveg@gmail.com\r\n"; 
$mailsend .= "<br><br><br><br><br><br></table><table><tr><td>Delivery details</td></tr><tr><td>Name</td><td>Address</td><td>Contact</td></tr>";
if(count($row)){
  $to = $row['fname'] .", shafeeqline@gmail.com, zizveg@gmail.com";
	$mailsend .= "<tr><td>".$row['username'] . "</td><td>" . $row['address'] . "</td><td>" . $row['contact'] . "</td><tr></table></body></html>";
	mail($to, $subject, $mailsend, $headers);
	echo "success";
}
else{
	echo "error";
}
//Send mail after cleaning it
mysql_close($bd);
?>
