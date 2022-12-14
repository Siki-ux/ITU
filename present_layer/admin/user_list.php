<?php
/***
 * @author xpavel39@stud.fit.vutbr.cz
 */
    chdir('../..'); // ---> root
    include_once('./bussiness_layer/admin/check_admin.php');

    enforce_admin();

    include_once('./bussiness_layer/constants.php');

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
        <title>Chytni závadu: Správa užívateľov</title>
        <link rel="stylesheet" type="text/css" href="../../present_layer/admin/admin.css"/>
        <script src="https://kit.fontawesome.com/ea2428928f.js" crossorigin="anonymous"></script>   
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script type="text/javascript" src="../../bussiness_layer/admin/alter_user.js"></script>
        <script type="text/javascript" src="./user_list.js"></script>
    </head>

    <div>
        <nav>
            <h2>
                <div class="menu-div">
                    <div class="sett-but ico-hover" id="menu-but" onclick="toggle_dropdown_menu()">
                        <i class="ico fa-xl fa-solid fa-bars"></i>
                    </div>
                    <div id="dropdown-menu" class="dropdown-content content-hidden">
                        <a href="./add_manager.php">Pridať užívateľa</a> 
                        <div onclick="reset();">Reset</div>
                        <div onclick="refresh_tables();">Obnoviť tabuľku</div>
                        <a href="../authentication/logout.php">Odhlásiť</a>
                    </div>
                </div> 
                <div class="headline">Správa užívateľov</div>
            </h2>
        </nav>
    </div>

    <div class="role-filter">
        <label for="role-filter" class="filter-label">Role: </label>
        <select class="filter-input" id="role-select" onChange="role_choice_change()">
            <option selected='selected' value=-1>Všetky</option>
            <option value=0>Obyčajný užívateľ</option>
            <option value=1 class="admin-row"><?php echo $roles[1]; ?></option>
            <option value=2 class="manager-row"><?php echo $roles[2]; ?></option>"
            <option value=3 class="worker-row"><?php echo $roles[3]; ?></option>"
        </select>
    </div>

    <div class="filter">
        <label for="filer" class="filter-label">Hľadať:</label>
        <input name="filter" class="filter-input" id="filter-input" onKeyUp="filter_change();">
        <label onclick="filter_reset();" for="filter" class="filter-ico ico-hover"><i class="fa-lg fa-regular fa-circle-xmark"></i></label>
    </div>

    <table class="admin-table" id="usr-tab"> 
        <thead>
            <tr>
                <th><?php gen_col_head('id',0); ?></th>
                <th><?php gen_col_head('Krstné meno',1); ?></th>
                <th><?php gen_col_head('Priezvisko',2); ?></th> 
                <th><?php gen_col_head('e-mail',3); ?></th>
                <th><?php gen_col_head('Telefón',4); ?></th> 
                <th><?php gen_col_head('Rola',5); ?></th>
                <th>Odstrániť</th>
            </tr>
        </thead>
        
        <tbody id="tab-of-users"> 

        </tbody>
    </table> 

</html>
