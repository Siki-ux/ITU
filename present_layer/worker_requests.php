<meta name="viewport" content="width=device-width, initial-scale=0.49">
<?php
    chdir('..'); // ---> root
    include_once('./bussiness_layer/constants.php');
    include_once("./data_layer/db_request.php");
    include_once('./bussiness_layer/checks.php');
    include_once("./bussiness_layer/worker_ticket_print.php"); //*worker_request_print
    include_once("./bussiness_layer/state_change.php");

    //change_state();

    if(session_id() == "")
        session_start();
    if(! is_worker() )
        header('Location: ../index.php');

    /***
     * Parsing email string and extracting first part
     * @return username
     */
    function print_user_from_email($email){ 
        $pos = strpos($email,"@",0);
        return substr($email,0,$pos);
    }

    function gen_col_head($name,$col)
    {
        echo '
            <div class="order-div" onclick="order_change('.$col.')">
                <div class="col-name" id="col-name-'.$col.'">'.$name.'</div>
                <div class="order-but-div"><i id="order-but-'.$col.'" class="order-but fa-sharp fa-solid fa-xs fa-chevron-up"></i> </div>
            </div>

        ';
    }
?>

<html>
    <head>
        <title>My requests</title>
        <link rel="stylesheet" type="text/css" href="./worker_requests.css"/>
        <script src="https://kit.fontawesome.com/ea2428928f.js" crossorigin="anonymous"></script>   
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script type="text/javascript" src="./onclick.js"></script>
        <script type="text/javascript" src="./worker_list.js"></script>
        <script type="text/javascript" src="../bussiness_layer/get_address.js"></script>
        <script type="text/javascript" src="../bussiness_layer/worker_action.js"></script>
    </head>

<?php
    if(isset($_GET['requestID'])) {
        $ref_id = $_GET['requestID'];
        echo "<script type='text/javascript'>filter_init($ref_id);</script>";
    }    
?>

    <div>
        <h3>
            <div class="back-but">
                <a href = "../index.php">Back</a>
            </div>
            <div class="headline">My Requests</div>
        </h3>
    </div>

    <div class="choice">
        <label for="choice" class="choice-label"></label>
        <select class="filter-input" id="choice-select" onChange="choice_change()">
            <option selected='selected' value='%'>All</option>
            <option value='0'>Zaevidovaný</option>
            <option value='1'>Pracujeme na tom</option>
            <option value='2'>Vyriešené</option>"
        </select>
    </div>

    <div class="filter">
        <label for="filter" class="filter-label"></label>
        <input name="filter" class="filter-input" id="filter-input" onKeyUp="filter_change()">
        <label onclick="filter_reset();" for="filter" class="filter-ico ico-hover"><i class="fa-lg fa-regular fa-circle-xmark"></i></label>
    </div>

    <table class="admin-table" id="usr-tab"> 
        <thead>
            <tr>
                <th><?php gen_col_head('RID',0); ?></th>
                <th><?php gen_col_head('Category',1) ?></th>
                <th><?php echo "Address" ?></th> 
                <th><?php echo "Expected/Fixed date" ?></th>
                <th><?php gen_col_head('State',2); ?></th> 
                <th>Action</th>
            </tr>
        </thead>
        
        <tbody id="tab-of-users"> 

        </tbody>
    </table> 

</html>
