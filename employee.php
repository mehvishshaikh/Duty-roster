<?php include 'includes/header.php';
require('includes/PaginationHelper.php');
if (isset($_POST['addEmployee'])) {
    $err = False;
    $emp_id = secure($_POST['emp_id']);
    $fname = secure($_POST['fname']);
    $lname = secure($_POST['lname']);
    $email = secure($_POST['email']);
    $phone = secure($_POST['phone']);
    $dob = date('Y-m-d', strtotime(secure($_POST['dob'])));
    $password = Encrypt(secure($_POST['password']));
    $joining = date('Y-m-d', strtotime(secure($_POST['joining'])));
    $color = secure($_POST['color']);
    $sql1 = "SELECT MAX(rank) As `max` FROM `employee`";
    $res1 = $mysqli->query($sql1)->fetch_assoc();
    $rank1 = $res1['max'];
    $rank = $rank1 + 1;
    $imageName = "";

    if ($_POST['emp_id'] != "" && $_POST['fname'] != "" && $_POST['lname'] != "" && $_POST['email'] != "" && $_POST['phone'] != "" && $_POST['password'] != "") {
        if (!(!file_exists($_FILES['profile_pic']['tmp_name']) || !is_uploaded_file($_FILES['profile_pic']['tmp_name']))) {
            $imgFile = $_FILES['profile_pic']['name'];
            $tmp_dir = $_FILES['profile_pic']['tmp_name'];
            $imgSize = $_FILES['profile_pic']['size'];
            $upload_dir = 'assets/img/employee/'; // upload directory  
            $imgExt = strtolower(pathinfo($imgFile, PATHINFO_EXTENSION)); // get image extension
            $imageName = "$fname-$lname.png";

            // valid image extensions
            $valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions

            // Uploading Original Image
            if (in_array($imgExt, $valid_extensions) && $imgSize < 50000000) {
                move_uploaded_file($tmp_dir, $upload_dir . $imageName);
            } else {
                $err = True;
            }
        }
        if (!$err) {
            $sql = "INSERT INTO `employee`(`emp_id`, `fname`, `lname`, `email`, `phone`, `dob`, `profile_pic`, `password`, `joining`,`color`,`rank`,`date`) 
            VALUES ('$emp_id','$fname','$lname','$email','$phone','$dob','$imageName','$password','$joining','$color','$rank',CURDATE())";
            $res = $mysqli->query($sql);
            if ($res) {
                setFlash('success', "Employee $fname $lname added successfully!");
                header("location:employee");
                exit();
            } else {
                $err = True;
            }
        }
    } else {
        $err = True;
    }

    if ($err) {
        setFlash('danger', "Data not inserted! Please enter all details correctly");
        // header("location:employee");
        // exit();
    }
}
if (isset($_POST['editEmployee'])) {
    //print_r($_POST);
    $err = False;
    $id = secure($_POST['id']);
    $emp_id = secure($_POST['edit_emp_id']);
    $fname = secure($_POST['edit_fname']);
    $lname = secure($_POST['edit_lname']);
    $email = secure($_POST['edit_email']);
    $phone = secure($_POST['edit_phone']);
    $dob = date('Y-m-d', strtotime(secure($_POST['edit_dob'])));
    $password = ($_POST['edit_password'] != "") ? ",`password`='" . Encrypt(secure($_POST['edit_password'])) . "'" : "";
    $joining = date('Y-m-d', strtotime(secure($_POST['edit_joining'])));
    $color = secure($_POST['color']);
    $imageName = "";
    $imgSQL = "";

    if (!(!file_exists($_FILES['profile_pic']['tmp_name']) || !is_uploaded_file($_FILES['profile_pic']['tmp_name']))) {
        $imgFile = $_FILES['profile_pic']['name'];
        $tmp_dir = $_FILES['profile_pic']['tmp_name'];
        $imgSize = $_FILES['profile_pic']['size'];
        $upload_dir = 'assets/img/employee/'; // upload directory  
        $imgExt = strtolower(pathinfo($imgFile, PATHINFO_EXTENSION)); // get image extension
        $imageName = "$fname-$lname.png";

        // valid image extensions
        $valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions

        // Uploading Original Image
        if (in_array($imgExt, $valid_extensions) && $imgSize < 50000000) {
            move_uploaded_file($tmp_dir, $upload_dir . $imageName);
            $imgSQL = ", `profile_pic` = '$imageName'";
        } else {
            $err = True;
        }
    }
    if (!empty($id)) {
        $sql = "UPDATE `employee` SET `emp_id`='$emp_id',`fname`='$fname',`lname`='$lname',`email`='$email' $password, `phone`='$phone',`dob`='$dob' $imgSQL, `joining`='$joining',`color` = '$color' WHERE `id` = '$id'";
        $result = $mysqli->query($sql);
        if ($result) {
            setFlash('warning', "Employee $fname $lname edited successfully!");
        } else {
            $err = True;
        }
    }
    header("location:employee");
    exit();
}


if (isset($_POST['deleteEmployee'])) {

    print_r($_POST);
    $id = secure($_POST['id']);
    $sql = "DELETE FROM `employee` WHERE id = $id";
    $result = $mysqli->query($sql);
    if ($result) {
        setFlash('danger', "Employee deleted successfully!");
        header("location:employee");
        exit();
    } else {
        $err = True;
    }
}


?>
<!-- Page content -->
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm mb-3">
                    <div class="card-header d-flex flex-column flex-md-row align-tiems-center justify-content-md-between">
                        <h3 class="h1 mb-md-0 mb-sm-3 text-center">Employees</h3>
                        <div class="actions text-center">
                            <a href="#" class="btn btn-primary mr-1 mr-md-0" data-toggle="modal" data-target="#addForm"><i class="fas fa-plus"></i><span class="d-none d-lg-inline">&nbsp;Add Employee</span></a>
                        </div>
                        <!-- Add Modal -->
                        <div class="modal fade" id="addForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary">
                                        <h2 id="exampleModalLabel" class="text-white">Add Employee</h2>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true" class="text-white">&times;</span>
                                        </button>
                                    </div>
                                    <form method="POST" enctype="multipart/form-data">
                                        <div class="modal-body">
                                            <div class="form-row">
                                                <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                                    <label for="add_emp_id" class="form-control-label">Employee
                                                        ID</label>
                                                    <input class="form-control" type="text" placeholder="202103" id="add_emp_id" name="emp_id" pattern="^\S+$" required>
                                                </div>
                                                <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                                    <label for="add_fname" class="form-control-label">First
                                                        Name</label>
                                                    <input class="form-control" type="text" placeholder="John" id="add_fname" name="fname" pattern="^\S+$" required>
                                                </div>
                                                <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                                    <label for="add_lname" class="form-control-label">Last
                                                        Name</label>
                                                    <input class="form-control" type="text" placeholder="Snow" id="add_lname" name="lname" pattern="^\S+$" required>
                                                </div>
                                                <div class="form-group col-lg-4 col-md-6 col-sm-12 emailValidation">
                                                    <label for="add_email" class="form-control-label">Email <span class="text-danger errorMessage">(Already Exists)</span> <span class="text-success okMessage"><i class="fas fa-check-circle"></i> </span> </label>
                                                    <input class="form-control emailField" type="email" placeholder="@example.com" id="add_email" name="email" pattern="^\S+$" required>
                                                </div>
                                                <div class="form-group col-lg-4 col-md-6 col-sm-12 phoneValidation">
                                                    <label for="add_phone" class="form-control-label">Phone <span class="text-danger errorMessage">(Already Exists)</span> <span class="text-success okMessage"><i class="fas fa-check-circle"></i> </span> </label>
                                                    <input class="form-control phoneField" type="number" placeholder="40-(770)-888-444" id="add_phone" name="phone" pattern="^\S+$" required>
                                                </div>
                                                <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                                    <label for="add_phone" class="form-control-label">Color</label>
                                                    <select name="color" id="add_color" class="form-control" required>
                                                        <option>Select Colour</option>
                                                        <?php
                                                        $sql = "SELECT * FROM `colour` WHERE `color` NOT IN (SELECT c.`color` FROM `colour` AS c inner JOIN `employee` AS e ON c.`color` = e.`color`)";
                                                        $res = $mysqli->query($sql);
                                                        while ($row = $res->fetch_assoc()) {
                                                        ?>
                                                            <option value=<?= $row['color'] ?>><?= $row['colour_name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                                    <label for="add_dob" class="form-control-label">Date of Birth</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                        </div>
                                                        <input class="form-control datepicker" type="text" placeholder="Select date" id="add_dob" name="dob" data-toggle="datepicker" readonly required>
                                                    </div>
                                                </div>
                                                <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                                    <label for="add_pic" class="form-control-label">Image</label>
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" id="add_pic" name="profile_pic">
                                                        <label class="custom-file-label" for="add_pic">Choose
                                                            file</label>
                                                    </div>
                                                </div>
                                                <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                                    <label for="add_password" class="form-control-label">Password</label>
                                                    <input class="form-control" type="password" placeholder="password" id="add_password" name="password" pattern="^\S+$" required>
                                                </div>
                                                <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                                    <label for="add_joining" class="form-control-label">Joining
                                                        Date</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                        </div>
                                                        <input class="form-control datepicker" type="text" placeholder="Select date" id="add_joining" data-toggle="datepicker" name="joining" readonly required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="addEmployee" class="btn btn-primary"><i class="fas fa-plus"></i>&nbsp; Add Employee</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php require("includes/alerts.php");
                        getSearchBar();
                        ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover align-items-center">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Sr</th>
                                        <th>Employee ID</th>
                                        <th>Name</th>
                                        <th>Contact No.</th>
                                        <th>Email Address</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($_GET['search'])) {
                                        $search = "CONCAT_WS(`fname`, `lname`, `email`,DATE_FORMAT(`date`, '%d-%M-%Y')) LIKE '%" . secure($_GET['search']) . "%'";
                                    } else {
                                        $search = "1";
                                    }
                                    $content = fetchPagination("*", "employee", "WHERE $search ORDER BY `rank`");
                                    $i = -1;
                                    if ($content > 0) {
                                        foreach ($content as $row) {
                                            $i++;
                                            if ($i == 0) {
                                                continue;
                                            }

                                            $img_name = ($row['profile_pic']!="")? BASE_URL . "assets/img/employee/" . $row['profile_pic'] : "assets/img/avatar.png";
                                            
                                    ?>
                                            <tr>
                                                <td><?= serial($i); ?></td>
                                                <td><?= $row['emp_id']; ?></td>
                                                <th scope="row">
                                                    <div class="media align-items-center">
                                                        <a href="<?= $img_name; ?>" class="avatar rounded-circle mr-3 popup-image">
                                                            <img alt="Image placeholder" src="<?= $img_name; ?>">
                                                        </a>
                                                        <div class="media-body">
                                                            <span class="name mb-0 text-sm"><?= $row['fname'] . " " . $row['lname']; ?></span>
                                                        </div>
                                                    </div>
                                                </th>
                                                <td><?= $row['phone']; ?></td>
                                                <td><?= $row['email']; ?></td>
                                                <td>
                                                    <!-- View -->
                                                    <a href="#" class="btn btn-info btn-sm mx-1" title="View" data-toggle="modal" data-target="#viewData<?= $i; ?>"><i class="fas fa-expand"></i></a>
                                                    <!-- View Modal -->
                                                    <div class="modal fade" id="viewData<?= $i; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-primary">
                                                                    <h2 id="exampleModalLabel" class="text-white">Employee: <?= $row['fname'] . " " . $row['lname']; ?>
                                                                    </h2>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true" class="text-white">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-lg-3 col-md-12 col-sm-12">
                                                                            <img src="<?= $img_name; ?>" alt="" class="img-fluid mb-3">
                                                                        </div>
                                                                        <div class="col-lg-9 col-md-12 col-sm-12">
                                                                            <div class="row">
                                                                                <div class="col-lg-3 col-md-12 col-sm-12">
                                                                                    <div class="profile-item">
                                                                                        <div class="profile-item-label">Employee ID</div>
                                                                                        <div class="profile-item-value"><?= $row['emp_id']; ?></div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-4 col-md-12 col-sm-12">
                                                                                    <div class="profile-item">
                                                                                        <div class="profile-item-label">Name</div>
                                                                                        <div class="profile-item-value"><?= $row['fname'] . " " . $row['lname']; ?></div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-6 col-md-12 col-sm-12">
                                                                                    <div class="profile-item">
                                                                                        <div class="profile-item-label">Email Address</div>
                                                                                        <div class="profile-item-value"><?= $row['email']; ?></div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-6 col-md-12 col-sm-12">
                                                                                    <div class="profile-item">
                                                                                        <div class="profile-item-label">Phone</div>
                                                                                        <div class="profile-item-value">+91 <?= $row['phone']; ?></div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-4 col-md-12 col-sm-12">
                                                                                    <div class="profile-item">
                                                                                        <div class="profile-item-label">Joining Date</div>
                                                                                        <div class="profile-item-value"><?= $row['joining']; ?></div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Edit -->
                                                    <a href="#" class="btn btn-warning btn-sm mx-1" title="Edit" data-toggle="modal" data-target="#editForm<?= $i; ?>"><i class="fas fa-pencil-alt"></i></a>
                                                    <!-- Edit Modal -->
                                                    <div class="modal fade" id="editForm<?= $i; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-primary">
                                                                    <h2 id="exampleModalLabel" class="text-white">Edit: <?= $row['fname'] . " " . $row['lname']; ?></h2>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true" class="text-white">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form method="POST" enctype="multipart/form-data">
                                                                    <div class="modal-body">
                                                                        <div class="form-row">
                                                                            <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                                                                <input name="id" value="<?= $row['id'] ?>" hidden>
                                                                                <label for="edit_emp_id" class="form-control-label">Employee ID</label>
                                                                                <input class="form-control" type="text" placeholder="202103" id="edit_emp_id" name="edit_emp_id" value="<?= $row['emp_id']; ?>" pattern="^\S+$" required>
                                                                            </div>
                                                                            <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                                                                <label for="edit_fname" class="form-control-label">First
                                                                                    Name</label>
                                                                                <input class="form-control" type="text" placeholder="John" id="edit_fname" name="edit_fname" value="<?= $row['fname']; ?>" pattern="^\S+$" required>
                                                                            </div>
                                                                            <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                                                                <label for="edit_lname" class="form-control-label">Last
                                                                                    Name</label>
                                                                                <input class="form-control" type="text" placeholder="Snow" id="edit_lname" name="edit_lname" value="<?= $row['lname']; ?>" pattern="^\S+$" required>
                                                                            </div>
                                                                            <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                                                                <label for="edit_email" class="form-control-label">Email</label>
                                                                                <input class="form-control" type="email" placeholder="@example.com" id="edit_email" name="edit_email" value="<?= $row['email']; ?>" pattern="^\S+$" required>
                                                                            </div>
                                                                            <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                                                                <label for="edit_phone" class="form-control-label">Phone</label>
                                                                                <input class="form-control" type="number" placeholder="40-(770)-888-444" id="edit_phone" name="edit_phone" value="<?= $row['phone']; ?>" pattern="^\S+$" required>
                                                                            </div>
                                                                            <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                                                                <label for="add_phone" class="form-control-label">Color</label>
                                                                                <select name="color" id="add_color" class="form-control" required>
                                                                                    <option>Select Colour</option>
                                                                                    <?php
                                                                                    $sql = "SELECT * FROM `colour` WHERE `color` NOT IN (SELECT c.`color` FROM `colour` AS c inner JOIN `employee` AS e ON c.color = e.color AND e.id != " . $row['id'] . ")";
                                                                                    $res = $mysqli->query($sql);
                                                                                    while ($row_color = $res->fetch_assoc()) {
                                                                                    ?>
                                                                                        <option value=<?= "'" . $row_color['color'] . "' ";
                                                                                                        echo ($row_color['color'] == $row['color']) ? "selected" : null; ?>><?= $row_color['colour_name'] ?></option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                            </div>
                                                                            <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                                                                <label for="edit_dob" class="form-control-label">Date of Birth</label>
                                                                                <div class="input-group">
                                                                                    <div class="input-group-prepend">
                                                                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                                                    </div>
                                                                                    <input class="form-control datepicker" type="text" placeholder="Select date" id="edit_dob" name="edit_dob" data-toggle="datepicker" value="<?= Date("d-m-Y", strtotime($row['dob'])); ?>" readonly required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                                                                <label for="edit_pic" class="form-control-label">Image</label>
                                                                                <div class="custom-file">
                                                                                    <input type="file" class="custom-file-input" id="edit_pic">
                                                                                    <label class="custom-file-label" for="edit_pic" name="edit_pic" value="<?= $row['profile_pic']; ?>">Choose
                                                                                        file</label>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                                                                <label for="edit_password" class="form-control-label">Password</label>
                                                                                <input class="form-control" type="password" placeholder="password" id="edit_password" name="edit_password" pattern="^\S+$" required>
                                                                            </div>
                                                                            <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                                                                <label for="edit_joining" class="form-control-label">Joining
                                                                                    Date</label>
                                                                                <div class="input-group">
                                                                                    <div class="input-group-prepend">
                                                                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                                                    </div>
                                                                                    <input class="form-control datepicker" type="text" placeholder="Select date" id="edit_joining" data-toggle="datepicker" name="edit_joining" value="<?= Date("d-m-Y", strtotime($row['joining'])); ?>" readonly required>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                        <button type="submit" class="btn btn-warning" name="editEmployee"><i class="fas fa-pencil-alt"></i>&nbsp; Edit
                                                                            Employee</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Delete -->
                                                    <a href="#" class="btn btn-danger btn-sm mx-1" title="Delete" data-toggle="modal" data-target="#deleteModal<?= $i; ?>"><i class="fas fa-trash-alt"></i></a>
                                                    <!-- Edit Modal -->
                                                    <div class="modal fade" id="deleteModal<?= $i; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                                            <form method="POST" action="">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-primary">
                                                                        <h2 id="exampleModalLabel" class="text-white">Delete
                                                                            <?= $row['fname'] . " " . $row['lname'] ?>
                                                                        </h2>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true" class="text-white">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <form action="">
                                                                        <div class="modal-body">
                                                                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                                            <p>Are you sure want to delete <?= $row['fname'] . " " . $row['lname'] ?> ?</p>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                            <button type="submit" class="btn btn-danger" name="deleteEmployee"><i class="fas fa-trash-alt"></i>&nbsp; Delete
                                                                                Employee</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                    <?php }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="">
                            <?php pagination($i); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
<script>
    $(document).ready(function() {
        $emailErr = false;
        $phoneErr = false;
        $(".errorMessage").hide();
        $(".okMessage").hide();
        $('.emailField').change(function() {
            $okMessage = $(this).parent().find(".okMessage")
            $errorMessage = $(this).parent().find(".errorMessage")
            if ($(this).val() != '') {
                $.ajax({
                    type: "post",
                    data: {
                        email: $(this).val(),
                    },
                    url: "backend",
                    success: function(result) {
                        if (result == 'true') {
                            $okMessage.show();
                            $errorMessage.hide();
                            $emailErr = false;
                        } else {
                            $okMessage.hide();
                            $errorMessage.show();
                            $emailErr = true;
                        }
                    }
                });
            }
            // if ($emailErr == false && $phoneErr == false) {
            //     $('button[name="addEmployee"]').prop('disabled', false);
            // } else {
            //     $('button[name="addEmployee"]').prop('disabled', true);
            // }
        })

        $('.phoneField').change(function() {
            $okMessage = $(this).parent().find(".okMessage")
            $errorMessage = $(this).parent().find(".errorMessage")
            if ($(this).val() != '') {
                $.ajax({
                    type: "post",
                    data: {
                        phone: $(this).val(),
                    },
                    url: "backend",
                    success: function(result) {
                        if (result == 'true') {
                            $okMessage.show();
                            $errorMessage.hide();
                            $phoneErr = false;
                        } else {
                            $okMessage.hide();
                            $errorMessage.show();
                            $phoneErr = true;
                        }
                    }
                });
            }
            // if ($emailErr == false && $phoneErr == false) {
            //     $('button[name="addEmployee"]').prop('disabled', false);
            // } else {
            //     $('button[name="addEmployee"]').prop('disabled', true);
            // }
        })
    })
</script>