<?php
$mysql_hostname = "mysql1003.mochahost.com";
$mysql_user = "shafeeq_zizveg";
$mysql_password = "ZuhairZIZ786";
$mysql_database = "shafeeq_zizvegdb";
$prefix = "";
$bd = mysql_connect($mysql_hostname, $mysql_user, $mysql_password) or die("Could not connect database");
mysql_select_db($mysql_database, $bd) or die("Could not select database");
?>