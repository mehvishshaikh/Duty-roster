<?php include 'includes/header.php';
require('includes/PaginationHelper.php');
?>
<!-- Page content -->
<?php
if (isset($_POST['addholiday'])) {
    $err = False;
    $date = date('Y-m-d', strtotime(secure($_POST['add_date'])));
    $hname = secure($_POST['add_holiday']);
    if (!empty($date) && !empty($hname)) {

        if ($mysqli->query("SELECT * FROM `holiday` WHERE `date` = '$date'")->num_rows < 1) {
            $sql = "INSERT INTO `holiday`(`date`, `hname`) VALUES ('$date','$hname')";
            $result = $mysqli->query($sql);
            if ($result) {
                setFlash('success', "Successfully added Holiday");
                header("location:holiday");
                exit();
            } else {
                $err = True;
            }
        } else {
            setFlash('danger', "There's already holiday on $date");
            header("location:holiday");
            exit();
        }
    }
}

if (isset($_POST['editholiday'])) {
    print_r($_POST);
    $id = secure($_POST['id']);
    $date = date('Y-m-d', strtotime(secure($_POST['edit_date'])));
    $hname = secure($_POST['edit_holiday']);
    if (!empty($date) && !empty($hname)) {
        echo $sql = "UPDATE `holiday` SET `date`='$date',`hname`='$hname' WHERE `id`='$id'";
        $result = $mysqli->query($sql);
        if ($result) {
            setFlash('warning', "Successfully edited Holiday");
            header("location:holiday");
            exit();
        } else {
            $err = True;
        }
    }
}

if (isset($_POST['deleteholiday'])) {

    //print_r($_POST);
    $id = secure($_POST['id']);
    $sql = "DELETE FROM `holiday` WHERE `id` = '$id'";
    $result = $mysqli->query($sql);
    if ($result) {
        setFlash('danger', "Holiday deleted successfully!");
        header("location:holiday");
        exit();
        //header("location:employee");
        //exit();
    } else {
        $err = True;
    }
}



?>
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm mb-3">
                    <div class="card-header d-flex flex-column flex-md-row align-tiems-center justify-content-md-between">
                        <h3 class="h1 mb-md-0 mb-sm-3 text-center">Holidays</h3>
                        <?php if ($_SESSION['type'] == 'admin') { ?>
                            <div class="actions text-center">
                                <a href="#" class="btn btn-primary mr-1 mr-md-0" data-toggle="modal" data-target="#addForm"><i class="fas fa-plus"></i><span class="d-none d-lg-inline">&nbsp;Add Holiday</span></a>
                            </div>
                        <?php } ?>
                        <!-- Add Modal -->
                        <div class="modal fade" id="addForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary">
                                        <h2 id="exampleModalLabel" class="text-white">Add Holiday</h2>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true" class="text-white">&times;</span>
                                        </button>
                                    </div>

                                    <form method="POST" action="">
                                        <div class="modal-body">
                                            <div class="form-row">
                                                <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                                    <label for="add_date" class="form-control-label">Date</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                        </div>
                                                        <input class="form-control datepicker" type="text" placeholder="Select date" id="add_date" name="add_date" data-toggle="datepicker" autocomplete="off" readonly required>
                                                    </div>
                                                </div>
                                                <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                                    <label for="add_holiday" class="form-control-label">Holiday</label>
                                                    <input class="form-control" type="text" placeholder="" id="add_holiday" name="add_holiday">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary" name="addholiday"><i class="fas fa-plus"></i>&nbsp; Add Holiday</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php
                        require("includes/alerts.php");
                        getSearchBar();
                        ?>
                        <a href="printHoliday" class="btn btn-primary">Print Holidays</a><br><br>   
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover align-items-center">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Sr</th>
                                        <th>Holiday</th>
                                        <th>Date</th>
                                        <?php if ($_SESSION['type'] == 'admin') { ?>
                                            <th>Actions</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($_GET['search'])) {
                                        $search = "CONCAT(`hname`,`date`) LIKE '%" . secure($_GET['search']) . "%'";
                                    } else {
                                        $search = "1";
                                    }
                                    $content = fetchPagination("*", "holiday", "WHERE $search ORDER BY `id` DESC");
                                    $i = -1;
                                    if ($content > 0) {
                                        foreach ($content as $row) {
                                            $i++;
                                            if ($i == 0) {
                                                continue;
                                            }
                                    ?>
                                            <tr>
                                                <td><?= serial($i); ?></td>
                                                <td><?= $row['hname']; ?></td>
                                                <td><?= $row['date']; ?></td>
                                                <?php if ($_SESSION['type'] == 'admin') { ?>
                                                    <td>
                                                        <!-- Edit -->
                                                        <a href="#" class="btn btn-warning btn-sm mx-1" title="Edit" data-toggle="modal" data-target="#editForm<?= $i; ?>"><i class="fas fa-pencil-alt"></i></a>
                                                        <!-- Edit Modal -->
                                                        <div class="modal fade" id="editForm<?= $i; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-primary">
                                                                        <h2 id="exampleModalLabel" class="text-white">Edit Holiday</h2>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true" class="text-white">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <form method="POST" action="">
                                                                        <div class="modal-body">
                                                                            <div class="form-row">
                                                                                <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                                                                    <label for="edit_date" class="form-control-label">Date</label>
                                                                                    <div class="input-group">
                                                                                        <div class="input-group-prepend">
                                                                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                                                        </div>
                                                                                        <input class="form-control datepicker" type="text" placeholder="Select date" id="edit_date" name="edit_date" data-toggle="datepicker" value="<?= Date("d-m-Y", strtotime($row['date'])); ?>" readonly required>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                                                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                                                    <label for="edit_holiday" class="form-control-label">Holiday</label>
                                                                                    <input class="form-control" type="text" placeholder="Holiday Name" id="edit_holiday" name="edit_holiday" value="<?= $row['hname'] ?>">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                            <button type="submit" name="editholiday" class="btn btn-warning"><i class="fas fa-pencil-alt"></i>&nbsp; Edit Holiday</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Delete -->
                                                        <a href="#" class="btn btn-danger btn-sm mx-1" title="Delete" data-toggle="modal" data-target="#deleteModal<?= $i ?>"><i class="fas fa-trash-alt"></i></a>
                                                        <!-- Delete Modal -->
                                                        <div class="modal fade" id="deleteModal<?= $i; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                                                <form method="POST">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header bg-primary">
                                                                            <h2 id="exampleModalLabel" class="text-white">Delete Holiday <?= $row['hname']; ?>
                                                                            </h2>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true" class="text-white">&times;</span>
                                                                            </button>
                                                                        </div>

                                                                        <div class="modal-body">
                                                                            <input type="hidden" name='id' value="<?= $row['id'] ?>">
                                                                            <p>Are you sure want to delete Holiday <?= $row['hname']; ?>?</p>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                            <button type="submit" name="deleteholiday" class="btn btn-danger"><i class="fas fa-trash-alt"></i>&nbsp; Delete Holiday</button>
                                                                        </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </td>
                                                <?php } ?>
                                            </tr>
                                    <?php }
                                    } ?>
                                </tbody>
                            </table>
                            <div class="">
                                <?php pagination($i); ?>
                            </div>
                        </div>
                        <!-- <nav class="mt-4 d-flex justify-content-end">
                            <ul class="pagination">
                                <li class="page-item"><a class="page-link" href="#" tabindex="-1"><i class="fa fa-angle-left"></i></a></li>
                                <li class="page-item"><a class="page-link" href="#">1</a></li>
                                <li class="page-item active"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item"><a class="page-link" href="#"><i class="fa fa-angle-right"></i></a></li>
                            </ul>
                        </nav> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>

<script>
    $(document).ready(function() {
        $('#sortTable').sortable({
            update: function(event, ui) {
                $(this).children().each(function(index) {
                    if ($(this).attr('data-position') != (index + 1)) {
                        $(this).attr('data-position', (index + 1)).addClass('updated');
                    }
                });
            }
        });
    });
</script>