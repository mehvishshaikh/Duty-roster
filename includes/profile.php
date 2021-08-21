<?php

/* This program is written by Hertzsoft Technologies Pvt. Ltd.
Last Modified: 2021-03-19 00:15:40

Description:
This file helps to blocks unauthorized access, also helps to fetch admin data required after login
*/

require("config.php");

if(!isLoggedIn())
{
	setFlash("url",$_SERVER['REQUEST_URI']);
	setFlash("warning","Please Login to continue");
	header("location:login");
}
else{
	$sql = "SELECT * FROM `".$_SESSION['type']."` WHERE `email` = '$_SESSION[$aid]'";
	$res = $mysqli->query($sql);
	if($res)
	{
		while($row = $res->fetch_assoc())
		{
			$id = $row['id'];
			$fname = $row['fname'];
			$lname = $row['lname'];
		}
	}
}

?>