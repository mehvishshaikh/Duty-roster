<?php

// THESE ARE GLOBAL VARIABLE DECLARATION. PLEASE DO NOT CHANGE IT.
$catchURL = "";
$page = "";
$startLimit = 0;
$totalData = 0;
$pageCount = 0;
$contentsPerPage = 0;


// 1. CREATE PAGINATION FUNCTION WITH CONTENTS PER PAGE
function createPagination($fetchDataPerPage = 10)
{
    global $contentsPerPage;
    $contentsPerPage = $fetchDataPerPage;
}


// 2. FETCH DATA ACCORDING TO PAGE NUMBER
function fetchPagination($columns,$table,$whereOrJoin,$debug=False)
    // USE GLOBAL VARIABLES
    {
    global $mysqli, $catchURL, $page, $startLimit, $totalData, $pageCount, $contentsPerPage;
    
    // CLEANING URL FOR OTHER PAGES - $catchURL
    $catchURL = $_SERVER['REQUEST_URI'];
    $catchURL = preg_replace("/(\?|\&)page=[\d]+/", "", $catchURL);
    $query = parse_url($catchURL, PHP_URL_QUERY);

    if ($query) {
        $catchURL .= '&';
    } else {
        $catchURL .= '?';
    }

    // GET CURRENT PAGE NUMBER
    $page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? secure($_GET['page']) : 1;
    $startLimit = ($page - 1) * $contentsPerPage;

    // GET TOTAL COUNT OF PAGES AND TOTAL CONTENT TO BE DISPLAYED 
    $sql = "SELECT COUNT(*) FROM $table $whereOrJoin";
    if($debug) echo "<h6>Count Query</h6><pre>$sql</pre><br>";
    $countResult = $mysqli->query($sql);
    $rows = $countResult->fetch_row();
    $totalData = $rows[0];
    $pageCount = ceil($totalData / $contentsPerPage);

    // GET DATA
    $sql_main = "SELECT $columns FROM $table $whereOrJoin LIMIT $startLimit,$contentsPerPage";
    if($debug) echo "<h6>Main Query</h6><pre>$sql_main</pre><br>";
    $result = $mysqli->query($sql_main);

    // RETURN ROWS OF NEEDED DATA
    if ($result->num_rows == 0) {
        return 0;
    } else {
        while ($row = $result->fetch_array()) {
            $rows[] = $row;
        }
        //        $remove = array_shift($rows);
        return $rows;
    }
}

// 3. CREATE BOTTOM PAGE NUMBERS
// NOTE: This function should be called after fetchPagination() function
function pagination($i)
{
    // USE GLOBAL VARIABLES
    global $catchURL, $page, $startLimit, $totalData, $pageCount, $show, $page, $contentsPerPage;
    ?>
    <div class="d-flex flex-column flex-md-row align-tiems-center justify-content-md-between p-2">
        <div class=" text-left">
            <?php if($i==-1) { ?>
            <p>Showing 0 out of 0 entries.</p>
            <?php } else { ?>
            <p>Showing <?php echo (($page-1)*$show+1) . " - " . ((($page-1)*$show)+$i); ?> records out of <?php echo $totalData; ?> entries <?= isset($_GET['search'])? "for <b>'".secure($_GET['search'])."'</b>": ""; ?></p>
            <?php } ?>
        </div>
        <div class=" text-right">
    <?php

    if ($pageCount > 1) {
        echo "<ul class='pagination'>";
        $back = $page - 1;
        $next = $page + 1;
        if ($page <= 1)
            echo "<li class='page-item'><a class='page-link' tabindex='-1'><i class='fa fa-angle-left'></i></a></li>";
        else
            echo "<li class='page-item'><a class='page-link' href='" . $catchURL . "page=$back' tabindex='-1'><i class='fa fa-angle-left'></i></a></li>";
        if ($pageCount > 7) {
            if ($page < $pageCount - 3 && $page > 4) {
                echo "<li class='page-item'><a href='#' class='page-link'>...</a></li>";
                for ($i = $page - 2; $i <= $page + 2; $i++) {
                    if ($i == $page)
                        echo "<li class='page-item active'><a href='" . $catchURL . "page=$i' class='page-link'>$i</a></li>";
                    else
                        echo "<li class='page-item'><a href='" . $catchURL . "page=$i' class='page-link'>$i</a></li>";
                }
                echo "<li class='page-item'><a href='#' class='page-link'>...</a></li>";
            } elseif ($page > 4) {
                $start = ($page - 7) + ($pageCount - $page);
                echo "<li class='page-item'><a href='#' class='page-link'>...</a></li>";
                for ($i = $start + 2; $i <= $start + 7; $i++) {
                    if ($i == $page)
                        echo "<li class='page-item active'><a href='" . $catchURL . "page=$i' class='page-link'>$i</a></li>";
                    else
                        echo "<li class='page-item'><a href='" . $catchURL . "page=$i' class='page-link'>$i</a></li>";
                }
            } elseif ($page <= 4) {
                for ($i = 1; $i <= 6; $i++) {
                    if ($i == $page)
                        echo "<li class='page-item active'><a href='" . $catchURL . "page=$i' class='page-link'>$i</a></li>";
                    else
                        echo "<li class='page-item'><a href='" . $catchURL . "page=$i' class='page-link'>$i</a></li>";
                }
                echo "<li class='page-item'><a href='#' class='page-link'>...</a></li>";
            }
        } elseif ($pageCount > 1) {
            for ($i = 1; $i <= $pageCount; $i++) {
                if ($i == $page)
                    echo "<li class='page-item active'><a href='" . $catchURL . "page=$i' class='page-link'>$i</a></li>";
                else
                    echo "<li class='page-item'><a href='" . $catchURL . "page=$i' class='page-link'>$i</a></li>";
            }
        }
        if ($page == ($pageCount))
            echo "<li class='page-item'><a class='page-link' href='#'><i class='fa fa-angle-right'></i></a></li>";
        else
            echo "<li class='page-item'><a href='" . $catchURL . "page=$next' class='page-link'><i class='fa fa-angle-right'></i></a></li>";
        echo "</ul>";
    }
    echo "</div></div>";
}

// Basic Configurations for Custom Data Table
if (isset($_GET['show']) && is_numeric($_GET['show'])) {
    $show = $_GET['show'];
} else {
    $show = RECORDS_PER_PAGE;
}
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}
$search = (isset($_GET['search'])) ? secure($_GET['search']) : "";
createPagination($show);

function getSearchBar()
{
    global $show;
    if(isset($_GET['search'])) {
        $search = $_GET['search'];
    } else {
        $search = "";
    }
    ?>
    <div class="d-flex flex-column flex-md-row align-tiems-center justify-content-md-between p-2">
        <div>
            <form class="form-inline d-flex flex-md-row flex-sm-column align-items-center justify-content-between">
                <div class="mb-0 d-flex align-items-center">
                    <label class="mb-3" for="entries">Show</label>
                    <select class="form-control form-control-sm mx-2 mb-3 records-per-page" id="entries" name="show" onchange="$(this).parents('form').submit();">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <label class="mb-3" for="entries">Entries</label>
                    <script>
                        document.getElementsByClassName('records-per-page')[0].value = "<?= $show; ?>";
                    </script>
                </div>
            </form>
        </div>
        <div>
            <form>
                <div class="form-group d-flex align-items-center mb-3">
                    <label for="search">Search</label>
                    <input type="search" class="form-control form-control-sm ml-2" id="search" name="search" value="<?= $search; ?>">
                </div>
            </form>
        </div>
    </div>
<?php } 
function serial($i) { 
    global $show,$page;
    return (($page-1)*$show+$i);
}
?>