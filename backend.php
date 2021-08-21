<?php
require('includes/profile.php');


if(isset($_POST['email'])) {
    $email = secure($_POST['email']);
    echo ($mysqli->query("SELECT * FROM `employee` WHERE `email` = '$email'")->num_rows>0)? "false": "true";
}

elseif(isset($_POST['phone'])) {
    $phone = secure($_POST['phone']);
    echo ($mysqli->query("SELECT * FROM `employee` WHERE `phone` = '$phone'")->num_rows>0)? "false": "true";
}
else {
    $data = $_POST['positions'];

    foreach ($data as $index => $value) {
        $id = $data[$index][0];
        $rank = $data[$index][1];
        $sql = "UPDATE `employee` SET `rank`= '$rank' WHERE `id`= '$id'";
        $res = $mysqli->query($sql);
    }
}