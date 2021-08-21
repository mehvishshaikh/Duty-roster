<?php include 'includes/header.php'; 
require('includes/PaginationHelper.php');

if(isset($_POST['addColour']))
{
    $colour_name = secure($_POST['colourName']); 
    $colour = secure($_POST['colour']);
    $sql = "INSERT INTO `colour` (`colour_name`,`color`) Values ('$colour_name','$colour')";
    $res = $mysqli->query($sql);
    if($res){
        setFlash("success","Colour Added Successfully");
        header("location:color");
        exit();
    }
    else{
        $err = false;
    }
}

if (isset($_POST['deleteColour'])) {

    //print_r($_POST);
    $colorid = secure($_POST['colorid']);
    $sql = "DELETE FROM `colour` WHERE `id` = '$colorid'";
    $result = $mysqli->query($sql);
    if ($result) {
        setFlash('danger', "Color deleted successfully!");
        header("location:color");
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
                        <h3 class="h1 mb-md-0 mb-sm-3 text-center">Colours</h3>
                        <div class="actions text-center">
                            <a href="#" class="btn btn-primary mr-1 mr-md-0" data-toggle="modal" data-target="#addcolour"><i class="fas fa-plus"></i><span class="d-none d-lg-inline">&nbsp;Add Colours</span></a>
                            <!-- <a href="#" class="btn btn-slack mr-1 mr-md-0"><i class="fas fa-file-csv"></i><span
                                    class="d-none d-lg-inline">&nbsp;Upload CSV</span></a> -->
                        </div>
                        <div class="modal fade" id="addcolour" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary p-3">
                                        <h2 id="exampleModalLabel" class="text-white">Add Colours</h2>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true" class="text-white">&times;</span>
                                        </button>
                                    </div>
                                    <form method="POST">
                                        <div class="modal-body">
                                            <div class="form-row">
                                                <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                                    <label for="example-text-input" class="form-control-label">Colour Name</label>
                                                    <input class="form-control" name="colourName" type="text" placeholder="Red" id="example-text-input">
                                                </div>
                                                <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                                    <label for="example-text-input" class="form-control-label">Color</label>
                                                    <input class="form-control" name ="colour" type="color" id="example-text-input">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="addColour" class="btn btn-primary"><i class="fas fa-plus"></i>&nbsp; Add Colour</button>
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
                                        <th>Colour Name</th>
                                        <th>Colour</th>
                                        <th>Used By</th>
                                        <th>Actions</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($_GET['search'])) {
                                        $search = "CONCAT(c.`colour_name`,c.`color`) LIKE '%" . secure($_GET['search']) . "%'";
                                    } else {
                                        $search = "1";
                                    }
                                    $content = fetchPagination("c.*,e.fname,e.lname", "`employee` AS e", "RIGHT JOIN `colour` As c ON c.color = e.color WHERE $search ORDER BY c.`id` DESC");
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
                                                <td><?= $row['colour_name']; ?></td>
                                                <td> <div style="width:100%;border-radius:5px;height:15px;border:1px solid #ddd;background-color:<?= $row['color']; ?>;"></div></td>
                                                <td><?= $row['fname']." ".$row['lname']; ?></td>
                                                <td>

                                                    <!-- Delete -->
                                                    <a href="#" class="btn btn-danger btn-sm mx-1" title="Delete" data-toggle="modal" data-target="#deleteModal<?=$i?>"><i class="fas fa-trash-alt"></i></a>
                                                    <!-- Edit Modal -->
                                                    <div class="modal fade" id="deleteModal<?=$i?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-primary">
                                                                    <h2 id="exampleModalLabel" class="text-white">Delete <?= $row['colour_name']; ?> Colour
                                                                    </h2>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true" class="text-white">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form method="POST">
                                                                    <div class="modal-body">
                                                                        <input type="hidden" name='colorid' value="<?= $row['id'] ?>">
                                                                        <p>Are you sure want to delete <?= $row['colour_name']; ?> Colour ?</p>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                        <button type="submit" name="deleteColour" class="btn btn-danger"><i class="fas fa-trash-alt"></i>&nbsp; Delete Colour</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <?php } ?>
                                            </tr>
                                    <?php }
                                     ?>
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