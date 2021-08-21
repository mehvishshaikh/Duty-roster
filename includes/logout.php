<?php

/* This program is written by Hertzsoft Technologies Pvt. Ltd.
Last Modified: 2021-01-14 20:39:01

Description:
This file is useful to logout user from system
*/

require("config.php");

session_destroy();

session_start();
setcookie("aid" , "" , time()-60*5);
setFlash("success","Logged Out Succesfully");
header("Location:../login");

exit();
?>
