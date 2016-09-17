<?php
$mysql_server_name='biophy.hust.edu.cn';
$mysql_username='jian';
$mysql_password='31j0n8i0a1';
$mysql_database='3drna';
$conn = mysql_connect($mysql_server_name, $mysql_username, $mysql_password);  
mysql_select_db($mysql_database,$conn);
?>
