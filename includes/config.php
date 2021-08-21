<?php

/* This program is written by Hertzsoft Technologies Pvt. Ltd.
Last Modified: 2021-03-19 00:16:05

Description:
This file contains all website required configurations and important functions
*/

require("db.php");
ob_start();

// ------------ MySQL Connection ------------
$mysqli = new mysqli(SERVER,USERNAME,PASSWORD,DATABASE);		// Localhost

if ($mysqli->connect_errno)
	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;

// ------------ Email Configuration ------------
define("notificationEmail","contact@tifr.com");

// ------------ BASE URL Configuration ------------
// define("BASE_URL","http://localhost/dutyroaster/");
define("BASE_URL","http://trialtest.xyz/dutyroaster/");

// ------------ RECORDS PER PAGE ------------
define("RECORDS_PER_PAGE",10);

// ------------ Start Session ------------
if (session_status() == PHP_SESSION_NONE) {
	session_start();
}

// ------------ Array ------------
$gender_value = array("","Male","Female","Others");

// ------------ FUNCTIONS TO MAKE PHP CODING EASY ------------
$aid = Encrypt('aid');

// Function 1: isLoggedIn() to check whether the user is logged in or not or redirect to login page
function isLoggedIn()
{
	global $aid;
	if(isset($_SESSION[$aid]) || isset($_COOKIE[$aid]))
	{	if(isset($_COOKIE[$aid]))
			$_SESSION[$aid]=$_COOKIE[$aid];
		return true;
	}
	else
		return false;
}

// Function 2: Security function secure() to prevent SQL Injection and Cross Site Scripting (XSS)
function secure($strToSecure)
{
	global $mysqli;
	$strToSecure = mysqli_real_escape_string($mysqli, $strToSecure);
	$strToSecure = strip_tags($strToSecure);
	$strToSecure = htmlentities($strToSecure);
	$strToSecure = trim($strToSecure);
	return $strToSecure;
}

// Function 2.1: Security function wsi_secure() to prevent Cross Site Scripting (XSS) in WSI
function wsi_secure($strToSecure)
{
	global $mysqli;
	$strToSecure = mysqli_real_escape_string($mysqli, $strToSecure);
	$strToSecure = str_replace("<script>","",$strToSecure);
	$strToSecure = str_replace("</script>","",$strToSecure);
	$strToSecure = trim($strToSecure);
	return $strToSecure;
}
// Function 3: Security function Encrypt() to encrypt a data using MD5 Algorithm
function Encrypt($data)
{
	$cipher = md5(md5("zeeshan") . secure($data) . md5("shaikh"));
	return $cipher;
}

// Function 4: Mailing function mailSender() to send email with server compatible mode
function mailSender($to,$from,$subject,$message)
{
	$headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: <$from>";
    mail($to, $subject, $message, $headers);
}

// Function 5: Setting Flash Data for temporary Messages
function setFlash($name,$value,$insert=false)
{
	global $mysqli;
	global $aid;
	$_SESSION[Encrypt("Hertz" . $name)] = $value;
	if($insert) {
		$uid = isLoggedIn()? $_SESSION[$aid] : "";
		$mysqli->query("INSERT INTO `notification` (`user`,`notification`,`date`) VALUES ('$uid','$value',NOW())");
	}
}

// Function 6: Get Flash Data for temporary Messages
function isFlashSet($name)
{
	if(isset($_SESSION[Encrypt("Hertz" . $name)]))
	{
		return true;
	}
	else {
		return false;
	}
}

// Function 7: Get Flash But Don't Delete the data temporary Messages
function getFlash($name)
{
	if(isset($_SESSION[Encrypt("Hertz" . $name)]))
	{
		$value = $_SESSION[Encrypt("Hertz" . $name)];
		return $value;
	}
	else {
		return NULL;
	}
}

// Function 8: Get Flash Data for temporary Messages
function getFlashDelete($name)
{
	if(isset($_SESSION[Encrypt("Hertz" . $name)]))
	{
		$value = $_SESSION[Encrypt("Hertz" . $name)];
		unset($_SESSION[Encrypt("Hertz" . $name)]);
		return $value;
	}
	else {
		return NULL;
	}
}

// Function 9: Function generate_string() to generate Random Strings (Can be used for Forgot Password & Email Verification)
function generate_string($strength = 32) {
	$input = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    return substr(str_shuffle($input),0,$strength);
}

// Function 10: Function redirect() to redirect to some other page using JavaScript
function redirect($url)
{
	echo "<script>window.location.assign('$url');</script>";
}

// Function 11: Testing function alert() to alert content using PHP
function alert($data)
{
	echo "<script>alert('$data');</script>";
}

$sql = "SELECT * FROM `holiday` ORDER BY `date`";
$res = $mysqli->query($sql);
$holidays_array = [];
$holidays_reason_array = [];
array_push($holidays_array,"buffer");
array_push($holidays_reason_array,"buffer");
while($row = $res->fetch_assoc())
{
	array_push($holidays_array,Date("Y/m/d",strtotime($row['date'])));
	array_push($holidays_reason_array,$row['hname']);
}

function isWeekend($date) {
    global $holidays_array;
	if(array_search($date,$holidays_array))
	{
		return 1;
	}
	return (date('N', strtotime($date)) >= 6);
}

function getHolidayReason($date)
{
	global $holidays_array;
	global $holidays_reason_array;
	$search = array_search($date,$holidays_array);
	if($search)
	{
		return $holidays_reason_array[$search];
	}
	else if(date('N', strtotime($date)) >= 6)
	{
		return "holiday";
	}
}

$sql3 ="SELECT s.*,CONCAT(e1.fname,' ',e1.lname) AS `swapper`,e1.`emp_id` AS `swapperID`, CONCAT(e2.fname,' ',e2.lname) AS `swapper_with`,e2.`emp_id` AS `swapwithID` FROM `swapdetails` As s LEFT JOIN `employee` AS e1 on s.swapper_id = e1.id LEFT JOIN `employee` as e2 ON s.swap_with_id = e2.id WHERE s.`active`=1";
$res3 = $mysqli->query($sql3);
$swap_dates_arr = ["buffer"];
$swap_emp_arr = ["buffer"];
$swap_emp_id_arr = ["buffer"];

while($row3 = $res3->fetch_assoc())
{
	array_push($swap_dates_arr,date("Y/m/d",strtotime($row3['swapper_date'])));
	array_push($swap_emp_arr,$row3['swapper_with']);
	array_push($swap_emp_id_arr,$row3['swapwithID']);

	array_push($swap_dates_arr,date("Y/m/d",strtotime($row3['swap_with_date'])));
	array_push($swap_emp_arr,$row3['swapper']);
	array_push($swap_emp_id_arr,$row3['swapperID']);
}

?>