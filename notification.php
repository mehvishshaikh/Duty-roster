<?php include 'includes/header.php';
require('includes/PaginationHelper.php');

if(isset($_POST['approveRequest'])) {
    $requestID = secure($_POST['requestID']);
    $sql = "UPDATE `swapdetails` SET `active` = '1' WHERE `id` = '$requestID'";
    $res = $mysqli->query($sql);    
    $result = $mysqli->query("SELECT * FROM `employee` AS e,`swapdetails` AS s WHERE e.`id`=s.`swapper_id` AND s.`id`='$requestID'");
    while($row = $result->fetch_assoc())
    {
        $to_email = $row['email'];
        $subject = "SHIFT SCHEDULE";
        $body = "Dear " . $row['fname'] . " " . $row['lname'] . ", your shift request have been accepted for: ".$row['swap_with_date'];
        $headers = "From: " . notificationEmail;
        mail($to_email, $subject, $body, $headers);
    }
    setFlash("success","Request Approved Successfully!");
    header("location:notification.php");
    exit();

}
if(isset($_POST['denyRequest'])) {
    $requestID = secure($_POST['requestID']);
    $result = $mysqli->query("SELECT * FROM `employee` AS e,`swapdetails` AS s WHERE e.`id`=s.`swapper_id` AND s.`id`='$requestID'");
    while($row = $result->fetch_assoc())
    {
        $to_email = $row['email'];
        $subject = "SHIFT SCHEDULE";
        $body = "Dear " . $row['fname'] . " " . $row['lname'] . ", We are sorry to inform you that your shift request has been denied of date".$row['swap_with_date'];
        $headers = "From: " . notificationEmail;
        mail($to_email, $subject, $body, $headers);
    }
    $sq1 ="DELETE FROM `swapdetails` WHERE `id` = '$requestID'";
    $res1 = $mysqli->query($sq1);
    setFlash("warning","Request Denied!");
    header("location:notification.php");
    exit();
}
?>
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm mb-3">
                    <div class="card-header d-flex flex-column flex-md-row align-tiems-center justify-content-md-between">
                        <h3 class="h1 mb-md-0 mb-sm-3 text-center">Notification</h3>
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
                                        <th>Swapped Date</th>
                                        <th>Swap with date</th>
                                        <th>Requested By</th>
                                        <th>Reason</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($_GET['search'])) {
                                        $search = "CONCAT_WS(s.`reason`, e1.`fname`, e1.`lname`,e2.`fname`, e2.`lname`, DATE_FORMAT(s.`swapper_date`, '%d-%M-%Y'), DATE_FORMAT(s.`swap_with_date`, '%d-%M-%Y')) LIKE '%" . secure($_GET['search']) . "%'";
                                    } else {
                                        $search = "1";
                                    }
                                    $content = fetchPagination("s.*,CONCAT(e1.`fname`,' ',e1.`lname`) AS `swapper`,CONCAT(e2.`fname`,' ',e2.`lname`) AS `swapped_with`,e1.`emp_id` AS `swapperID`, e2.`emp_id` AS `swappedID`", "`swapdetails` AS s", "LEFT JOIN `employee` AS e1 ON s.`swapper_id` = e1.id LEFT JOIN `employee` AS e2 ON s.`swap_with_id` = e2.id WHERE s.`active`= 0 AND `swap_with_id`= $id AND $search");
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
                                                <td><?= date("d-m-Y", strtotime($row['swapper_date'])); ?></td>
                                                <td><?= date("d-m-Y", strtotime($row['swap_with_date'])); ?></td>
                                                <td><?= $row['swapper']; ?></td>
                                                <td><?= $row['reason']; ?></td>
                                                <td>
                                                    <form method="POST">
                                                        <input type="hidden" name="requestID" value="<?= $row['id']; ?>">
                                                        <button type="submit" class="btn btn-success btn-sm mx-1" name="approveRequest" type="submit">Approve</button>
                                                        <button type="submit" class="btn btn-danger btn-sm mx-1"  name="denyRequest" type="submit">Deny</button>
                                                    </form>
                                                </td>
                                            <?php } ?>
                                            </tr>
                                        <?php
                                    } ?>
                                </tbody>
                            </table>
                            <div class="">
                                <?php pagination($i); ?>
                            </div>
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
        $('#sortTable').sortable({
            update: function(event, ui) {
                $(this).children().each(function(index) {
                    if ($(this).attr('data-position') != (index + 1)) {
                        $(this).attr('data-position', (index + 1)).addClass('updated');
                    }
                });

                //    saveNewPositions();
            }
        });
    });

</script>