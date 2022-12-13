<meta name="viewport" content="width=device-width, initial-scale=0.49">
<?php
    /**
     * @author xtverd01
     * Interface for ticket manager
     * Core of table from: user_list.php
     */
    chdir('..'); // ---> root
    include_once('./bussiness_layer/constants.php');
    include_once('./bussiness_layer/checks.php');

    //change_state();

    if(session_id() == "")
        session_start();
    if(! is_manager() )
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
        <title>Tickety</title>
        <link rel="stylesheet" type="text/css" href="./manager_tickets.css"/>
        <script src="https://kit.fontawesome.com/ea2428928f.js" crossorigin="anonymous"></script>   
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script type="text/javascript" src="./onclick.js"></script>
        <script type="text/javascript" src="../bussiness_layer/get_address.js"></script>
        <script type="text/javascript" src="../bussiness_layer/manager_action.js"></script>
        <script type="text/javascript" src="./manager_list.js"></script>
    </head>

<?php
    if(isset($_GET['requestID'])) {
        $ref_id = $_GET['requestID'];
        echo "<script type='text/javascript'>filter_init($ref_id);</script>";
    }    
?>

<div>
    <nav>
        <h2>
            <div class="menu-div">
                <div class="sett-but ico-hover" id="menu-but" onclick="toggle_dropdown_menu()">
                    <i class="ico fa-xl fa-solid fa-bars"></i>
                </div>
                <div id="dropdown-menu" class="dropdown-content content-hidden">
                    <a href="./worker/add_worker.php">Pridať technika</a>
                    <div onclick="filter_reset();">Resetovať filter</div>
                    <a href="./authentication/logout.php">Odhlásiť sa</a>

                </div>
            </div> 
            <div class="headline">Tickety</div>
        </h2>
    </nav>
</div>

    <span>
    <div class="choice">
        <label for="choice" class="choice-label"></label>
        <select class="filter-input" id="choice-select" onChange="choice_change()">
            <option selected='selected' value='%'>Všetky</option>
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
    </span>
    <table class="admin-table" id="usr-tab"> 
        <thead>
            <tr>
                <th style='width:4.5%;'><?php gen_col_head('ID',0); ?></th>
                <th style='width:9%;'><?php gen_col_head('Kategória',1) ?></th>
                <th style='width:14%;'><?php echo "Adresa" ?></th> 
                <th style='width:7.5%;'><?php gen_col_head('Stav',2) ?></th>
                <th style='width:4.5%;'><?php gen_col_head('Req',3) ?></th>
                <th><?php echo "Správa od manažéra" ?></th> 
                <th style='width:7.5%;'><?php gen_col_head('Čas vytvorenia',4) ?></th>
                <th style='width:7.5%;'><?php gen_col_head('Čas úpravy',5) ?></th>
                <th style='width:6%;'><?php echo "Fotka" ?></th> 
                <th style='width:4.5%;'>Akcie</th>
            </tr>
        </thead>
        
        <tbody id="tab-of-tickets"> 

        </tbody>
    </table> 

</html>
