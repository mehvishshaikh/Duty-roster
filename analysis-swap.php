<?php include 'includes/header.php';
require('includes/PaginationHelper.php');
?>
<!-- Page content -->
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm mb-3">
                    <div class="card-header d-flex flex-column flex-md-row align-tiems-center justify-content-md-between">
                        <h3 class="h1 mb-md-0 mb-sm-3 text-center">Analysis</h3>
                    </div>
                    <div class="card-body">
                    <a href="analysis-shift" type="button" class="btn btn-light mb-2"> SHIFT</a>
                    <a href="analysis-swap" type="button" class="btn btn-primary mb-2"> SWAPPED</a>
                        <?php require("includes/alerts.php");
                        getSearchBar();
                        ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover align-items-center">
                                <thead class="thead-light">
                                    <tr>
                                        <th rowspan="2">SR</th>
                                        <th colspan="2">Changed</th>
                                        <th colspan="2">Changed with</th>
                                        <th rowspan="2">Assigned Date</th>
                                        <th rowspan="2">Swapped Date</th>
                                        <th rowspan="2">Reason</th>
                                    </tr>
                                    <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>ID</th>
                                    <th>Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($_GET['search'])) {
                                        $search = "CONCAT_WS(s.`reason`, e1.`fname`, e1.`lname`,e2.`fname`, e2.`lname`, DATE_FORMAT(s.`swapper_date`, '%d-%M-%Y'), DATE_FORMAT(s.`swap_with_date`, '%d-%M-%Y')) LIKE '%" . secure($_GET['search']) . "%'";
                                    } else {
                                        $search = "1";
                                    }
                                    $content = fetchPagination("s.*,CONCAT(e1.`fname`,' ',e1.`lname`) AS `swapper`,CONCAT(e2.`fname`,' ',e2.`lname`) as `swapped_with`,e1.`emp_id` AS `swapperID`, e2.`emp_id` AS `swappedID`", "`swapdetails` AS s", "LEFT JOIN `employee` AS e1 ON s.`swapper_id` = e1.id LEFT JOIN `employee` AS e2 ON s.`swap_with_id` = e2.id WHERE s.`active`=1 AND $search");
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
                                                <td><?= $row['swapperID']; ?></td>
                                                <th scope="row">
                                                    <div class="media align-items-center">
                                                        <div class="media-body">
                                                            <span class="name mb-0 text-sm"><?= $row['swapper']; ?></span>
                                                        </div>
                                                    </div>
                                                </th>
                                                <td><?= $row['swappedID']; ?></td>
                                                <th scope="row">
                                                    <div class="media align-items-center">
                                                        <div class="media-body">
                                                            <span class="name mb-0 text-sm"><?= $row['swapped_with']; ?></span>
                                                        </div>
                                                    </div>
                                                </th>
                                                <td><?= Date("d-m-Y",strtotime($row['swapper_date'])); ?></td>
                                                <td><?= Date("d-m-Y",strtotime($row['swap_with_date'])); ?></td>
                                                <td><?= $row['reason']; ?></td>
                                                
                                            </tr>
                                    <?php  }}?>
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