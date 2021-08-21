<?php include 'includes/header.php';
?>
<!-- Page content -->
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm mb-3">
                    <div class="card-header d-flex flex-column flex-md-row align-tiems-center justify-content-md-between">
                        <h3 class="h1 mb-md-0 mb-sm-3 text-center"><a href="index" class="mr-2"><i class="fas fa-arrow-alt-circle-left"></i></a>Shift Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table datatable table-striped table-bordered table-hover align-items-center">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Sr</th>
                                        <th>Shift Date</th>
                                        <th>Shift Emp ID</th>
                                        <th>Shift Emp Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                            <tr>
                                                <?php
                                                $stop = false;
                                                $sq="SELECT * FROM `dates`";
                                                $res1 = $mysqli->query($sq);
                                                if($res1)
                                                {
                                                while($row2 = $res1->fetch_assoc())
                                                {                        
                                                    $start1 = strtotime($row2['start_date']);   
                                                    $end1 = strtotime($row2['end_date']);  
                                                    $days_between = ceil((abs($end1 - $start1) / 86400) / 49);
                                                    
                                                $date = date("Y/m/d",strtotime($row2['start_date']));   
                                                $end = date("Y/m/d",strtotime($row2['end_date']));  
                                                $dayCount = 0;
                                                
                                                for ($k = 1; $k <= $days_between ; $k++) 
                                                {
                                                    $sql = "SELECT * FROM `employee` ORDER BY `rank`";
                                                    $res = $mysqli->query($sql);
                                                    $i = 0;
                                                    while ($row = $res->fetch_assoc()) 
                                                    {
                                                        $i++;
                                                        for ($j = 1; $j <= 5; $j++) 
                                                        {
                                                            $dayCount++;
                                                            if (isWeekend($date))
                                                            { 
                                                                  ?>

                                                            <td><?= $dayCount; ?></td>
                                                            <td><?= $date; ?></td>
                                                            <th>
                                                            <div class="media align-items-center">
                                                                    <div class="media-body text-center">
                                                                        <span class="name mb-0 text-sm text-danger"><?="HOLIDAY"?></span>
                                                                    </div>
                                                                </div>
                                                            </th>
                                                            <td></td>
                                                            <?php
                                                            $j--;
                                                            }
                                                            else {
                                                                if(array_search($date,$swap_dates_arr))
                                                                {
                                                                    $search = array_search($date,$swap_dates_arr);
                                                                    $employee =  $swap_emp_arr[$search];
                                                                    $emp_id = $swap_emp_id_arr[$search];
                                                                    // echo "<td>$employee</td>";
                                                                }
                                                                else
                                                                {
                                                                    $employee =  $row['fname'] . " " . $row['lname'];
                                                                    $emp_id = $row['emp_id'];
                                                                }
                                                            ?>
                                                                <td><?= $dayCount; ?></td>
                                                                <td><?= $date; ?></td>
                                                                <td><?= $emp_id; ?></td>
                                                                <th scope="row">
                                                                    <div class="media align-items-center">
                                                                        <div class="media-body">
                                                                            <span class="name mb-0 text-sm"><?= $employee; ?></span>
                                                                        </div>
                                                                    </div>
                                                                </th>
                                                            <?php
                                                            }   
                                                            if(Date("Y-m-d",strtotime($date)) == Date("Y-m-d",strtotime($end)))
                                                            {
                                                                $stop=true;
                                                                break;
                                                            }
                                                            $date = date("Y/m/d",strtotime("+1 day",strtotime($date)));
                                                            
                                                            ?>
                                            </tr>
                                                        <?php 
                                                        if(date("w", strtotime($date))==6)
                                                        {
                                                            break;
                                                        }
                                                        }
                                                        if($stop) break;         
                                                    }
                                                    if($stop) break;         
                                                            }
                                                        }
                                                    }
                                            
                                         ?>
                                </tbody>
                            </table>
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
    $('.datatable').DataTable({
        "oPaginate": {                       
             "sNext": "<i class='fa fa-angle-right'></i>",
             "sPrevious": "<i class='fa fa-angle-left'></i>"
     },
     language: {
    paginate: {
      next: "<i class='fa fa-angle-right'></i>", // or '→'
      previous: "<i class='fa fa-angle-left'></i>" // or '←' 
    }
  },
  dom: 'Bfrtip',
  buttons: [
            {
                extend: 'print',
                className:'btn btn-success',
            }
        ]
    });
} );

</script>