<?php include 'includes/header.php'; ?>
<!-- Page content -->
<?php
if (isset($_POST['generate'])) {
    $id = secure($_POST['id']);
    $start_date = date("Y-m-d", strtotime($_POST['start_date']));
    $end_date = date("Y-m-d", strtotime($_POST['end_date']));
    $sql = "UPDATE `dates` SET `start_date`='$start_date',`end_date`='$end_date' WHERE `id`= '$id'";
    $res = $mysqli->query($sql);
    if ($res) {
        setFlash('success', "Shift Generated");
    } else {
        setFlash('danger', "Something went wrong");
    }



    $stop = false;
    $arr = [];
    $arrname = [];
    $key = "";
    $sq = "SELECT * FROM `dates`";
    $res1 = $mysqli->query($sq);
    if ($res1) {
        while ($row2 = $res1->fetch_assoc()) {
            $start1 = strtotime($row2['start_date']);
            $end1 = strtotime($row2['end_date']);
            $days_between = ceil((abs($end1 - $start1) / 86400) / 49);

            $date = date("Y/m/d", strtotime($row2['start_date']));
            $end = date("Y/m/d", strtotime($row2['end_date']));
            $dayCount = 0;

            for ($k = 1; $k <= $days_between; $k++) {
                $sql = "SELECT * FROM `employee` ORDER BY `rank`";
                $res = $mysqli->query($sql);
                $i = 0;
                while ($row = $res->fetch_assoc()) {
                    $i++;
                    for ($j = 1; $j <= 5; $j++) {
                        $dayCount++;
                        if (isWeekend($date)) {
                            $j--;
                        } else {
                            $sql1 = "SELECT * FROM `swapdetails` WHERE ((`swapper_date`='$date' AND `swapper_id`=" . $row['id'] . ") OR (`swap_with_date`='$date' AND `swap_with_id`=" . $row['id'] . ")) AND `active`=1 ORDER BY `id` DESC LIMIT 1";
                            $result1 = $mysqli->query($sql1);
                            if ($result1->num_rows > 0) {
                                $row1 = $result1->fetch_assoc();
                                if (Date("Y/m/d", strtotime($row1['swapper_date'])) == $date) {
                                    $date_swap = Date("Y/m/d", strtotime($row1['swap_with_date']));
                                }
                                if (Date("Y/m/d", strtotime($row1['swap_with_date'])) == $date) {
                                    $date_swap = Date("Y/m/d", strtotime($row1['swapper_date']));
                                }
                            } else {
                                $date_swap = $date;
                            }
                            $email = $row['email'];
                            $name = $row['fname'] . " " . $row['lname'];
                            if (array_key_exists($email, $arr)) {
                                array_push($arr[$email], $date_swap);
                            } else {
                                $arr[$email] = array($date_swap);
                                $arrname[$name] = array($date_swap);
                            }
                        }
                        if (Date("Y-m-d", strtotime($date)) == Date("Y-m-d", strtotime($end))) {
                            $stop = true;
                            break;
                        }
                        $date = date("Y/m/d", strtotime("+1 day", strtotime($date)));
                        if(date("w", strtotime($date))==6)
                        {
                            break;
                        }
                    }
                    if ($stop) break;
                }
                if ($stop) break;
                foreach ($arr as $email => $dates) {
                    $tarik = implode(", ", $dates);

                    $row = $mysqli->query("SELECT `fname`,`lname` FROM `employee` WHERE `email`='$email'")->fetch_assoc();

                    $to_email = $email;
                    $subject = "SHIFT SCHEDULE";
                    $body = "Dear " . $row['fname'] . " " . $row['lname'] . ", Working Shifts for TIFR has been generated. Your working days are as follow $tarik";

                    $headers = "From: " . notificationEmail;
                    mail($to_email, $subject, $body, $headers);
                }
            }
        }
    }
    setFlash("success", "Great! Employee shifts have been generated");
    header("location:index");
    exit();
}
?>
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card mb-3">
                    <div class="card-header">
                        <div class="d-flex flex-column flex-md-row align-tiems-center justify-content-md-between m-0">
                            <h3 class="h1 mb-md-0 mb-sm-3 text-center">Calendar</h3>
                            <div class="actions text-center d-inline-block">
                                <?php if ($_SESSION['type'] == 'admin') { ?>
                                    <!-- <a href="print.php" class="btn btn-slack mr-1 mr-md-0"><i class="fas fa-file-csv"></i><span class="d-none d-lg-inline">&nbsp;Print</span></a> -->
                                    <a href="#" class="btn btn-default mr-2 mr-md-0" data-toggle="modal" data-target="#sortData"><i class="fas fa-sort"></i><span class="d-none d-lg-inline">&nbsp;Sort Employees</span></a>
                                    <a href="#" class="btn btn-primary mr-1 mr-md-0" data-toggle="modal" data-target="#formName"><i class="fas fa-plus"></i><span class="d-none d-lg-inline">&nbsp;Setting</span></a>
                                <?php } else { ?>
                                    <div class="row">
                                        <div class="col-6">
                                            <select class="form-control pl-1 switch" name="switch">
                                                <option <?= isset($_GET['all']) ? "selected" : null ?> value="All">All Employee</option>
                                                <option <?= !isset($_GET['all']) ? "selected" : null ?> value="My">Self</option>
                                            </select>
                                        </div>
                                        <div class="col-6 m-0">
                                            <a href="print.php" class="btn btn-slack mr-1 px-1 mr-md-0"><i class="fas fa-file-csv"></i><span class="d-none d-lg-inline">&nbsp;Print</span></a>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="modal fade" id="formName" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary p-3">
                                        <h2 id="exampleModalLabel" class="text-white">Setting</h2>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true" class="text-white">&times;</span>
                                        </button>
                                    </div>
                                    <?php
                                    $sql = "SELECT * FROM `dates` WHERE `id`= 1";
                                    $res = $mysqli->query($sql);
                                    if ($res) {
                                        while ($row = $res->fetch_assoc()) {
                                    ?>
                                            <form method="POST">
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                                            <input type="hidden" name=id value="1">
                                                            <label for="start_date" class="form-control-label">Start Date</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                                </div>
                                                                <input class="form-control datepicker" name="start_date" value="<?= Date("d-m-Y", strtotime($row['start_date'])); ?>" type="text" id="start_date" placeholder="Select date" data-toggle="datepicker" autocomplete="off" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                                            <label for="end_date" class="form-control-label">End Date</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                                </div>
                                                                <input class="form-control datepicker" name="end_date" value="<?= Date("d-m-Y", strtotime($row['end_date'])); ?>" type="text" id="end_date" placeholder="Select date" data-toggle="datepicker" autocomplete="off" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" name="generate" class="btn btn-primary"> Generate Shift</button>
                                                </div>
                                            </form>
                                    <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php
                        if (isset($_POST['sort'])) {
                            setFlash("success", "Employee Sorted");
                            header("Location:index");
                            exit();
                        }
                        ?>
                        <!-- Sort Data Modal -->

                        <div class="modal fade" id="sortData" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary">
                                        <h2 id="exampleModalLabel" class="text-white">Sort Data</h2>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true" class="text-white">&times;</span>
                                        </button>
                                    </div>
                                    <form method="POST" action="">
                                        <div class="modal-body">
                                            <table class="table table-stripped table-hover table-bordered">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>Employees</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="sortTable">
                                                    <?php
                                                    $sql = "SELECT * FROM `employee` ORDER BY `rank`";
                                                    $result = $mysqli->query($sql);
                                                    if ($result) {
                                                        while ($row = $result->fetch_assoc()) {
                                                    ?>
                                                            <tr data-index="<?= $row['id']; ?>" data-position="<?= $row['rank']; ?>">
                                                                <td><?= $row['fname'] . " " . $row['lname'] ?></td>
                                                            </tr>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="sort" class="btn btn-default"> Save Positions</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php include("includes/alerts.php"); ?>
                        <div class="card-calendar">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <!-- Title -->
                                <div class="title">
                                    <h6 class="fullcalendar-title h2 mb-0">Fullcalendar</h6>
                                </div>
                                <div class="buttons">
                                    <a href="#" class="fullcalendar-btn-prev btn btn-sm btn-info">
                                        <i class="fas fa-angle-left"></i>
                                    </a>
                                    <a href="#" class="fullcalendar-btn-next btn btn-sm btn-info">
                                        <i class="fas fa-angle-right"></i>
                                    </a>

                                    <a href="#" class="btn btn-sm btn-info" data-calendar-view="month">Month</a>
                                    <a href="#" class="btn btn-sm btn-info" data-calendar-view="basicWeek">Week</a>
                                    <a href="#" class="btn btn-sm btn-info" data-calendar-view="basicDay">Day</a>
                                </div>
                            </div>
                            <div class="calendar border" data-toggle="calendar" id="calendar">
                            </div>
                            <!-- Edit calendar event -->
                            <!--* Modal body *-->
                            <!--* Modal footer *-->
                            <!--* Modal init *-->
                            <?php
                            if (isset($_POST['swapshift'])) {
                                if ($_POST['swapby']=='swapbyemployee') {
                                    $swapper_id = secure($_POST['empid']);
                                    $swap_with_id = secure($_POST['empname']);
                                    $swapper_date = date('Y-m-d', strtotime(secure($_POST['swapper_date'])));
                                    $swap_with_date = date('Y-m-d', strtotime(secure($_POST['empDate'])));
                                    $previous = date('Y-m-d', strtotime('-20 day', strtotime($swapper_date)));
                                    $next = date('Y-m-d', strtotime('+20 day', strtotime($swapper_date)));
                                    $permitted = true;
                                    if ($_SESSION['type'] == 'employee') {
                                        $sql2 = "SELECT * FROM `swapdetails` WHERE `swapper_id`='$swapper_id' AND `swapper_date`>='$previous' AND `swapper_date`<='$next' AND `active`=1";
                                        $res2 = $mysqli->query($sql2);
                                        if ($res2->num_rows > 3) {
                                            $permitted = false;
                                        }
                                    }

                                    if ($permitted) {
                                        $reason = secure($_POST['reason']);
                                        if (!isWeekend($swap_with_date)) {

                                            $rowSP = $mysqli->query("SELECT * FROM `employee` WHERE `id`='$swapper_id'")->fetch_assoc();
                                            $rowSW = $mysqli->query("SELECT * FROM `employee` WHERE `id`='$swap_with_id'")->fetch_assoc();

                                            if ($_SESSION['type'] == 'admin') {
                                                $active = 1;
                                                $body = "Dear " . $rowSW['fname'] . " " . $rowSW['lname'] . ",This is to inform a change in your shift schedule. The employee " . $rowSP['fname'] . " " . $rowSP['lname'] . " has some problem/reason. So your shift is shifted on this date $swapper_date and " . $rowSP['fname'] . " " . $rowSP['lname'] . " is shift on date  $swap_with_date";
                                                $body_admin = "Dear " . $rowSP['fname'] . " " . $rowSP['lname'] . ",This is to inform a change in your shift schedule. Your shift is shifted on this date $swap_with_date and " . $rowSW['fname'] . " " . $rowSW['lname'] . " shift is on date $swapper_date";
                                                $flashData = "Swapped Successfull!";
                                            } else {
                                                $active = 0;
                                                $body = "Dear " . $rowSW['fname'] . " " . $rowSW['lname'] . ",This letter to request a change in your shift schedule. The employee " . $rowSP['fname'] . " " . $rowSP['lname'] . " has some problem/reason. So your shift is shifted on this date $swapper_date and " . $rowSP['fname'] . " " . $rowSP['lname'] . " is shift on date  $swap_with_date. To accept this change your approval is required. Kindly login to your TIFR Account and respond to this request.";
                                                $flashData = "Swap Request has been sent";
                                            }

                                            $sql = "INSERT INTO `swapdetails` (`swapper_id`, `swapper_date`, `swap_with_id`, `swap_with_date`, `reason`,`active`, `date`) VALUES ('$swapper_id','$swapper_date','$swap_with_id','$swap_with_date','$reason',$active,CURDATE())";
                                            $res = $mysqli->query($sql);

                                            $to_email = $rowSW['email'];
                                            $subject = "SHIFT SWAP";
                                            $headers = "From: " . notificationEmail;
                                            mail($rowSW['email'], $subject, $body, $headers);
                                            if ($_SESSION['type'] == 'admin') {
                                                mail($rowSP['email'], $subject, $body_admin, $headers);
                                            }
                                            setFlash("success", $flashData);
                                            header("location:index");
                                            exit();
                                        } else {
                                            setFlash("danger", $flashData);
                                            header("location:index");
                                            exit();
                                        }
                                    } else {
                                        setFlash("danger", "You Cannot take more Leaves");
                                        header("location:index");
                                        exit();
                                    }
                                } else {
                                    $swapper_id = secure($_POST['empid']);
                                    $swap_with_id = secure($_POST['emp_with_id']);
                                    $swapper_date = date('Y-m-d', strtotime(secure($_POST['swapper_date'])));
                                    $swap_with_date = date('Y-m-d', strtotime(secure($_POST['swap_with_date'])));
                                    $previous = date('Y-m-d', strtotime('-20 day', strtotime($swapper_date)));
                                    $next = date('Y-m-d', strtotime('+20 day', strtotime($swapper_date)));
                                    $permitted = true;
                                    if ($_SESSION['type'] == 'employee') {
                                        $sql2 = "SELECT * FROM `swapdetails` WHERE `swapper_id`='$swapper_id' AND `swapper_date`>='$previous' AND `swapper_date`<='$next'";
                                        $res2 = $mysqli->query($sql2);
                                        if ($res2->num_rows > 3) {
                                            $permitted = false;
                                        }
                                    }

                                    if ($permitted) {
                                        $reason = secure($_POST['reason']);
                                        if (!isWeekend($swap_with_date)) {
                                            $rowSP = $mysqli->query("SELECT * FROM `employee` WHERE `id`='$swapper_id'")->fetch_assoc();
                                            $rowSW = $mysqli->query("SELECT * FROM `employee` WHERE `id`='$swap_with_id'")->fetch_assoc();

                                            if ($_SESSION['type'] == 'admin') {
                                                $active = 1;
                                                $body = "Dear " . $rowSW['fname'] . " " . $rowSW['lname'] . ",This is to inform a change in your shift schedule. The employee " . $rowSP['fname'] . " " . $rowSP['lname'] . " has some problem/reason. So your shift is shifted on this date $swapper_date and " . $rowSP['fname'] . " " . $rowSP['lname'] . " is shift on date  $swap_with_date";
                                                $body_admin = "Dear " . $rowSP['fname'] . " " . $rowSP['lname'] . ",This is to inform a change in your shift schedule. Your shift is shifted on this date $swap_with_date and " . $rowSW['fname'] . " " . $rowSW['lname'] . " shift is on date $swapper_date";
                                                $flashData = "Swapped Successfull!";
                                            } else {
                                                $active = 0;
                                                $body = "Dear " . $rowSW['fname'] . " " . $rowSW['lname'] . ",This letter to request a change in your shift schedule. The employee " . $rowSP['fname'] . " " . $rowSP['lname'] . " has some problem/reason. So your shift is shifted on this date $swapper_date and " . $rowSP['fname'] . " " . $rowSP['lname'] . " is shift on date  $swap_with_date. To accept this change your approval is required. Kindly login to your TIFR Account and respond to this request.";
                                                $flashData = "Swap Request has been sent";
                                            }

                                            $sql = "INSERT INTO `swapdetails` (`swapper_id`, `swapper_date`, `swap_with_id`, `swap_with_date`, `reason`,`active`, `date`) VALUES('$swapper_id','$swapper_date','$swap_with_id','$swap_with_date','$reason','$active',CURDATE())";
                                            $res = $mysqli->query($sql);

                                            $subject = "SHIFT SWAP";
                                            $headers = "From: " . notificationEmail;
                                            mail($rowSW['email'], $subject, $body, $headers);
                                            if ($_SESSION['type'] == 'admin') {
                                                mail($rowSP['email'], $subject, $body_admin, $headers);
                                            }
                                            setFlash("success", $flashData);
                                            header("location:index");
                                            exit();
                                        } else {
                                            setFlash("danger", "You Cannot Swap Shift On Holiday");
                                            header("location:index");
                                            exit();
                                        }
                                    } else {
                                        setFlash("danger", "You Cannot take more Leaves");
                                        header("location:index");
                                        exit();
                                    }
                                }
                            }

                            ?>
                            <?php if ($_SESSION['type'] == 'employee' && !isset($_GET['all'])) { ?>
                                <div class="modal fade" id="edit-event" tabindex="-1" role="dialog" aria-labelledby="edit-event-label" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary">
                                                <h2 id="exampleModalLabel" class="text-white">Kindly Change View <?= $_SESSION['type']; ?> </h2>
                                            </div>
                                            <!-- Modal body -->
                                            <form method="POST">
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-12 text-center">
                                                            Kindly Change View to <b>All Employee</b> to Swap Shifts.
                                                        </div>
                                                    </div>
                                                    <div class="row d-none">
                                                        <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                                            <input type="hidden" class="empid" name="empid">
                                                            <label for="datepicker" class="form-control-label">Swap date</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                                </div>
                                                                <input class="form-control swapper-date" name="swapper_date" type="text" placeholder="Select date" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                                            <label for="" class="form-control-label">Swap With</label>
                                                            <select class="form-control empname" name="empname" required>
                                                                <option value="">Select Employee</option>
                                                                <?php
                                                                $sql = "SELECT * FROM `employee`";
                                                                $res = $mysqli->query($sql);
                                                                while ($row = $res->fetch_assoc()) { ?>
                                                                    <option value="<?= $row['id']; ?>"><?= $row['fname'] . " " . $row['lname'] ?></option>
                                                                <?php } ?>
                                                            </select>

                                                            <input type="hidden" class="empwithid" name="empwithid">
                                                        </div>
                                                        <div class="form-group col-12 swapWithSection">
                                                            <label for="swap_with_date" class="form-control-label">Swap With Date</label>
                                                            <select class="form-control empDates" name="empDate" required>
                                                                <option>Select Date</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                                            <label for="" class="form-control-label">Reason</label>
                                                            <textarea name="reason" rows="2" class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Modal footer -->
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <a href="index?all" name="swapshift" class="btn btn-primary"> Change View</a>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="modal fade" id="edit-event" tabindex="-1" role="dialog" aria-labelledby="edit-event-label" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary">
                                                <h2 id="exampleModalLabel" class="text-white">Swap Shift</h2>
                                            </div>
                                            <!-- Modal body -->
                                            <form method="POST">
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                                            <input type="hidden" class="empid" name="empid">
                                                            <label for="datepicker" class="form-control-label">Swap date</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                                </div>
                                                                <input class="form-control swapper-date" name="swapper_date" type="text" placeholder="Select date" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                                            <div class="form-check">
                                                                <input class="form-check-input withempradio" type="radio" name="swapby" id="exampleRadios1" value="swapbyemployee" checked>
                                                                <label for="exampleRadios1">With Employee</label>
                                                                <input class="form-check-input ml-4 withdateradio" type="radio" name="swapby" id="exampleRadios" value="swapbydate">
                                                                <label class="ml-5" for="exampleRadios">With Date</label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-lg-12 col-md-12 col-sm-12 withdate">
                                                            <label for="swap_with_date" class="form-control-label">Swap With</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                                </div>
                                                                <input class="form-control swap-with-date" name="swap_with_date" type="text" id="swap_with_date" placeholder="Select date" data-toggle="datepicker" autocomplete="off" readonly>
                                                            </div>
                                                            <input type="hidden" class="emp_with_id" name="emp_with_id">
                                                        </div>
                                                        <div class="form-group col-lg-12 col-md-12 col-sm-12 withemp">
                                                            <label for="" class="form-control-label">Swap With</label>
                                                            <select class="form-control empname" name="empname" >
                                                                <option value="">Select Employee</option>
                                                                <?php
                                                                $sql = "SELECT * FROM `employee`";
                                                                $res = $mysqli->query($sql);
                                                                while ($row = $res->fetch_assoc()) { ?>
                                                                    <option value="<?= $row['id']; ?>"><?= $row['fname'] . " " . $row['lname'] ?></option>
                                                                <?php } ?>
                                                            </select>

                                                            <!-- <input type="hidden" class="empwithid" name="empwithid"> -->
                                                        </div>
                                                        <div class="form-group col-12 swapWithSection">
                                                            <label for="swap_with_date" class="form-control-label">Swap With Date</label>
                                                            <select class="form-control empDates" name="empDate" required>
                                                                <option>Select Date</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                                            <label for="" class="form-control-label">Reason</label>
                                                            <textarea name="reason" rows="2" class="form-control" required></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Modal footer -->
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" name="swapshift" class="btn btn-primary"> Swap Shift</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>

<script>
    $(".swapWithSection").hide();

    Date.prototype.addDays = function(days) {
        var date = new Date(this.valueOf());
        date.setDate(date.getDate() + days);
        return date;
    }
    // Sort Employees
    $(document).ready(function() {
        $('#sortTable').sortable({
            update: function(event, ui) {
                $(this).children().each(function(index) {
                    if ($(this).attr('data-position') != (index + 1)) {
                        $(this).attr('data-position', (index + 1)).addClass('updated');
                    }
                });
                saveNewPositions();
            }
        });
    });

    function saveNewPositions() {
        var positions = [];
        $('.updated').each(function() {
            positions.push([$(this).attr('data-index'), $(this).attr('data-position')]);
            $(this).removeClass('updated');
        });

        $.ajax({
            url: 'backend.php',
            method: 'POST',
            dataType: 'text',
            data: {
                update: 1,
                positions: positions
            },
            success: function(response) {
                // console.log(response);
            }
        });
    }


    //
    // Fullcalendar
    //

    'use strict';

    var Fullcalendar = (function() {

        // Variables

        var $calendar = $('[data-toggle="calendar"]');

        //
        // Methods
        //

        // Init
        function init($this) {

            //Calendar events
            var events = [
                    <?php
                    $stop = false;
                    $sq = "SELECT * FROM `dates`";
                    $res1 = $mysqli->query($sq);
                    if ($res1) {
                        while ($row2 = $res1->fetch_assoc()) {
                            $start1 = strtotime($row2['start_date']);
                            $end1 = strtotime($row2['end_date']);
                            $days_between = ceil((abs($end1 - $start1) / 86400) / 49);

                            $date = date("Y/m/d", strtotime($row2['start_date']));
                            $end = date("Y/m/d", strtotime($row2['end_date']));
                            $dayCount = 0;

                            for ($k = 1; $k <= $days_between; $k++) {
                                $sql = "SELECT * FROM `employee` ORDER BY `rank`";
                                $res = $mysqli->query($sql);
                                $i = 0;
                                while ($row = $res->fetch_assoc()) {
                                    $i++;
                                    for ($j = 1; $j <= 5; $j++) {
                                        $dayCount++;

                                        if (isWeekend($date)) {
                    ?> {
                                                id: '<?= "$i _ $j _ $dayCount"; ?>',
                                                title: '<?= getHolidayReason($date); ?>',
                                                start: '<?= $date; ?>',
                                                allDay: true,
                                                className: 'bg-danger',
                                                rendering: "background",
                                            },
                                            <?php
                                            $j--;
                                        } else {

                                            $sql1 = "SELECT * FROM `swapdetails` WHERE ((`swapper_date`='$date' AND `swapper_id`=" . $row['id'] . ") OR (`swap_with_date`='$date' AND `swap_with_id`=" . $row['id'] . ")) AND `active`=1 ORDER BY `id` DESC LIMIT 1";
                                            $result1 = $mysqli->query($sql1);
                                            if ($result1->num_rows > 0) {
                                                $row1 = $result1->fetch_assoc();
                                                if (Date("Y/m/d", strtotime($row1['swapper_date'])) == $date) {
                                                    $date_swap = Date("Y/m/d", strtotime($row1['swap_with_date']));
                                                }
                                                if (Date("Y/m/d", strtotime($row1['swap_with_date'])) == $date) {
                                                    $date_swap = Date("Y/m/d", strtotime($row1['swapper_date']));
                                                }
                                            } else {
                                                $date_swap = $date;
                                            }
                                            $show = true;
                                            if ($_SESSION['type'] == 'employee' && !isset($_GET['all']) && $row['id'] != $id) {
                                                $show = false;
                                            }
                                            if ($show) {
                                            ?> {
                                                    id: '<?= "$i _ $j _ $dayCount"; ?>',
                                                    title: '<?= $row['fname'] . " " . $row['lname']; ?>',
                                                    start: '<?= $date_swap; ?>',
                                                    allDay: true,
                                                    color: '<?= $row['color']; ?>',
                                                    description: '<?= $row['id']; ?>',
                                                },
                    <?php                   }
                                        }
                                        if (Date("Y-m-d", strtotime($date)) == Date("Y-m-d", strtotime($end))) {
                                            $stop = true;
                                            break;
                                        }
                                        $date = date("Y/m/d", strtotime("+1 day", strtotime($date)));
                                        if(date("w", strtotime($date))==6)
                                        {
                                            break;
                                        }
                                    }
                                    if ($stop) break;
                                }
                                if ($stop) break;
                            }
                        }
                    }
                    ?>
                ],

                // Full calendar options
                // For more options read the official docs: https://fullcalendar.io/docs
                options = {
                    header: {
                        right: '',
                        center: '',
                        left: ''
                    },
                    buttonIcons: {
                        prev: 'calendar--prev',
                        next: 'calendar--next'
                    },
                    weekends: true,
                    theme: false,
                    selectable: true,
                    selectHelper: true,
                    editable: false,
                    events: events,

                    select: (start, end, allDay) => {
                        var startDate = moment(start),
                            endDate = moment(end),
                            date = startDate.clone(),
                            isWeekend = false;

                        while (date.isBefore(endDate)) {
                            if (date.isoWeekday() == 6 || date.isoWeekday() == 7) {
                                isWeekend = true;
                            }
                            date.add(1, 'day');
                        }

                        if (isWeekend) {
                            alert('can\'t add event - weekend');

                            return false;
                        }

                        this.startDate = startDate.format("YYYY-MM-DD");
                        this.endDate = endDate.format("YYYY-MM-DD");

                        //$('.first.modal').modal('show');
                    },

                    //                     dayClick: function(date) {
                    // var isoDate = moment(date).toISOString();
                    // $('#new-event').modal('show');
                    // $('.new-event--title').val('');
                    // $('.new-event--start').val(isoDate);
                    // $('.new-event--end').val(isoDate);
                    // },

                    viewRender: function(view) {
                        var calendarDate = $this.fullCalendar('getDate');
                        var calendarMonth = calendarDate.month();

                        //Set data attribute for header. This is used to switch header images using css
                        // $this.find('.fc-toolbar').attr('data-calendar-month', calendarMonth);
                        //Set title in page header
                        $('.fullcalendar-title').html(view.title);
                    },
                    eventRender: function(event, element) {
                        if (event.title == 'holiday') {
                            element.append('');
                        } else if (event.rendering == 'background') {
                            element.append(`<span>${event.title}</span>`);
                        }
                    },
                    // Edit calendar event action
                    eventClick: function(event, element) {
                        $('.empid').val(event.description);
                        var dArr1 = event.start._i.split('/');
                        var swapperDate = new Date(parseInt(dArr1[0]), parseInt(dArr1[1]) - 1, parseInt(dArr1[2]));
                        var new1 = `${dArr1[2]}-${dArr1[1]}-${dArr1[0]}`;
                        $('.swapper-date').val(new1);
                        $(".empname").children('option').show();
                        $(".empname").children("option[value=" + event.description + "]").hide();
                        var today = new Date();
                        <?php
                        if ($_SESSION['type'] == 'employee') {
                        ?>
                            if ($('.empid').val() == <?= $id; ?> && swapperDate.getTime() >= today.getTime()) {
                                $('#edit-event').modal('show');
                            }
                        <?php } else { ?>
                            if (swapperDate.getTime() >= today.getTime()) {
                                $('#edit-event').modal('show');
                            }
                        <?php } ?>
                        $('.swap-with-date').change(function() {
                            var dArr = $(this).val().split('-');
                            var finDate = `${dArr[2]}/${dArr[1]}/${dArr[0]}`;
                            var item = events.find(item => item.start === finDate);
                            $('.emp_with_id').val(item.description);
                        });
                        $('.empname').change(function() {
                            var name = $('.empname option:selected').text();
                            if (name != "") {
                                $(".swapWithSection").show();
                            } else {
                                $(".swapWithSection").hide();
                            }
                            $(".empDates").html("<option value=''>Select Date</option>")

                            var today = new Date();
                            <?php $end1 = $mysqli->query("SELECT * FROM `dates`")->fetch_assoc(); ?>
                            var endDate = new Date(<?= Date("Y,m-1,d", strtotime($end1['end_date'])); ?>)
                            var startDate = new Date(<?= Date("Y,m-1,d", strtotime($end1['start_date'])); ?>)
                            today = (Math.ceil(Math.abs(today - startDate) / (1000 * 60 * 60 * 24)) < 0) ? today : startDate;
                            const diffDays = Math.ceil(Math.abs(endDate - today) / (1000 * 60 * 60 * 24));

                            var finDate;
                            var items;
                            for (i = 0; i <= diffDays; i++) {
                                finDate = `${today.getFullYear()}/${(today.getMonth()+1).toLocaleString('en-US', {minimumIntegerDigits: 2, useGrouping:false})}/${today.getDate().toLocaleString('en-US', {minimumIntegerDigits: 2, useGrouping:false})}`;
                                items = events.find(item => item.start === finDate);
                                $('.empwithid').val(items.description);
                                if (name == items.title) {
                                    finDate = `${today.getDate().toLocaleString('en-US', {minimumIntegerDigits: 2, useGrouping:false})}-${(today.getMonth()+1).toLocaleString('en-US', {minimumIntegerDigits: 2, useGrouping:false})}-${today.getFullYear()}`;
                                    $(".empDates").append("<option value='" + finDate + "'>" + finDate + "</option>")
                                }

                                today = new Date(today.getTime() + 86400000); // + 1 day in ms
                            }
                        });
                    },
                };
            // Initalize the calendar plugin
            $this.fullCalendar(options);
            // console.table(events);   

            // console.log(events); 

            //
            // Calendar actions
            //


            //Add new Event

            $('body').on('click', '.new-event--add', function() {
                var eventTitle = $('.new-event--title').val();

                // Generate ID
                var GenRandom = {
                    Stored: [],
                    Job: function() {
                        var newId = Date.now().toString().substr(
                            6); // or use any method that you want to achieve this string

                        if (!this.Check(newId)) {
                            this.Stored.push(newId);
                            return newId;
                        }
                        return this.Job();
                    },
                    Check: function(id) {
                        for (var i = 0; i < this.Stored.length; i++) {
                            if (this.Stored[i] == id) return true;
                        }
                        return false;
                    }
                };

                if (eventTitle != '') {
                    $this.fullCalendar('renderEvent', {
                        id: GenRandom.Job(),
                        title: eventTitle,
                        start: $('.new-event--start').val(),
                        end: $('.new-event--end').val(),
                        allDay: true,
                        className: $('.event-tag input:checked').val()
                    }, true);

                    $('.new-event--form')[0].reset();
                    $('.new-event--title').closest('.form-group').removeClass('has-danger');
                    $('#new-event').modal('hide');
                } else {
                    $('.new-event--title').closest('.form-group').addClass('has-danger');
                    $('.new-event--title').focus();
                }
            });


            //Update/Delete an Event
            $('body').on('click', '[data-calendar]', function() {
                var calendarAction = $(this).data('calendar');
                var currentId = $('.edit-event--id').val();
                var currentTitle = $('.edit-event--title').val();
                var currentDesc = $('.edit-event--description').val();
                var currentClass = $('#edit-event .event-tag input:checked').val();
                var currentEvent = $this.fullCalendar('clientEvents', currentId);

                //Update
                if (calendarAction === 'update') {
                    if (currentTitle != '') {
                        currentEvent[0].title = currentTitle;
                        currentEvent[0].description = currentDesc;
                        currentEvent[0].className = [currentClass];

                        console.log(currentClass);
                        $this.fullCalendar('updateEvent', currentEvent[0]);
                        $('#edit-event').modal('hide');
                    } else {
                        $('.edit-event--title').closest('.form-group').addClass('has-error');
                        $('.edit-event--title').focus();
                    }
                }

                //Delete
                if (calendarAction === 'delete') {
                    $('#edit-event').modal('hide');

                    // Show confirm dialog
                    setTimeout(function() {
                        swal({
                            title: 'Are you sure?',
                            text: "You won't be able to revert this!",
                            type: 'warning',
                            showCancelButton: true,
                            buttonsStyling: false,
                            confirmButtonClass: 'btn btn-danger',
                            confirmButtonText: 'Yes, delete it!',
                            cancelButtonClass: 'btn btn-secondary'
                        }).then((result) => {
                            if (result.value) {
                                // Delete event
                                $this.fullCalendar('removeEvents', currentId);

                                // Show confirmation
                                swal({
                                    title: 'Deleted!',
                                    text: 'The event has been deleted.',
                                    type: 'success',
                                    buttonsStyling: false,
                                    confirmButtonClass: 'btn btn-primary'
                                });
                            }
                        })
                    }, 200);
                }
            });


            //Calendar views switch
            $('body').on('click', '[data-calendar-view]', function(e) {
                e.preventDefault();

                $('[data-calendar-view]').removeClass('active');
                $(this).addClass('active');

                var calendarView = $(this).attr('data-calendar-view');
                $this.fullCalendar('changeView', calendarView);
            });


            //Calendar Next
            $('body').on('click', '.fullcalendar-btn-next', function(e) {
                e.preventDefault();
                $this.fullCalendar('next');
            });


            //Calendar Prev
            $('body').on('click', '.fullcalendar-btn-prev', function(e) {
                e.preventDefault();
                $this.fullCalendar('prev');
            });
        }



        //
        // Events
        //
        // Init
        if ($calendar.length) {
            init($calendar);
        }
    })();
    $('.switch').change(function() {
        $a = $(this).val();
        if ($a == "My") {
            window.location.assign('index');
        } else {
            window.location.assign('index?all');
        }
    });
    $(document).ready(function() {
        $('.withdate').hide();
    });
    $('.withempradio').change(function() {
        $('.withdate').hide();
        $('.withemp').show();
    });
    $('.withdateradio').change(function() {
        $('.withemp').hide();
        $('.swapWithSection').hide();
        $('.withdate').show();
    });
</script>