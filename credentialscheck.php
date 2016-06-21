<?php
session_start();
include('connection.php');

$username = mysql_real_escape_string($_POST['username']); 
$password = mysql_real_escape_string($_POST['password']); 

$sql = "SELECT * FROM outlets WHERE username = '$username' AND password = '$password'";
$result = mysql_query($sql) or die(mysql_error());
$numrows = mysql_num_rows($result);
if($numrows == 1){
    echo true;
}
else{
    echo false;
}
mysql_close($bd);
?>