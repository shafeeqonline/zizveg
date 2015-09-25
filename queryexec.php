<?php
session_start();
include('connection.php');
$fname=$_POST['fname'];
$address=$_POST['address'];
$contact=$_POST['contact'];
$username=$_POST['username'];
$password=$_POST['password'];
$sql = "SELECT * FROM usersdb WHERE username = '$username'";
$result = mysql_query($sql) or die(mysql_error());
$numrows = mysql_num_rows($result);
if($numrows == 0){
	mysql_query("INSERT INTO usersdb(fname, address, contact, username, password)VALUES('$fname', '$address', '$contact', '$username', '$password')");
	echo "done";
}
else{
	echo "multipleentries";
}
mysql_close($bd);
?>