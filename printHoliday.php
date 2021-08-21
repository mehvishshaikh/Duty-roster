<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Holidays</title>
    <?php require("includes/profile.php"); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css" />
</head>

<body>
    <h1 class="text-center">LIST OF HOLIDAYS</h1>
    <div id="printableArea">
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>Sr</th>
                    <th>Date</th>
                    <th>Holiday Reason</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM `holiday`";
                $result = $mysqli->query($sql);
                $i = 1;
                while ($row = $result->fetch_assoc()) {  ?>
                    <tr>
                        <th><?= $i++; ?></th>
                        <th><?= Date("d-M-Y", strtotime($row['date'])) ?></th>
                        <th><?= $row['hname'] ?></th>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript">
        window.print();
        window.onafterprint = back;

        function back() {
            window.history.back();
        }
    </script>
</body>

</html>