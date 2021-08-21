<?php
require "includes/config.php";
            $stop = false;
            $arr= [];
            $arrname =[];
            $key = "";
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
                        if (isWeekend($date)) {
                            $j--;
                        } else {
                        $sql1 = "SELECT * FROM `swapdetails` WHERE (`swapper_date`='$date' AND `swapper_id`=" . $row['id'] . ") OR (`swap_with_date`='$date' AND `swap_with_id`=" . $row['id'] . ") ORDER BY `id` DESC LIMIT 1";
                        $result1 = $mysqli->query($sql1);
                        if ($result1->num_rows > 0) {
                        $row1 = $result1->fetch_assoc();
                        if (Date("Y/m/d", strtotime($row1['swapper_date'])) == $date) {
                                $date_swap = Date("Y/m/d", strtotime($row1['swap_with_date']));   
                        }
                        if (Date("Y/m/d", strtotime($row1['swap_with_date'])) == $date) {
                            $date_swap = Date("Y/m/d", strtotime($row1['swapper_date']));   
                        }
                        } 
                        else {
                            $date_swap = $date;
                        }
                        $email = $row['email'];
                        $name = $row['fname']." ".$row['lname'];
                        if(array_key_exists($email,$arr))
                        {
                            array_push($arr[$email],$date_swap);
                            array_push($arrname[$name],$date_swap);
                        }
                        else {
                            $arr[$email] = array($date_swap);
                            $arrname[$name] = array($date_swap);
                        }
                    }
                        if(Date("Y-m-d",strtotime($date)) == Date("Y-m-d",strtotime($end)))
                        {
                            $stop=true;
                            break;
                        }
                        $date = date("Y/m/d",strtotime("+1 day",strtotime($date)));
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
    // echo "<pre>";
    // print_r($arrname[$name]);
    // echo "</pre>";
    
    foreach($arrname as $name => $dates)
    {
        echo $name . "<br>";       
    }

    // foreach($arr as $email => $dates)
    // {
    //     echo implode(", ",$dates) . "<br>";       
    // }
?>