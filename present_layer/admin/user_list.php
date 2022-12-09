<?php
/***
 * @author xpavel39@stud.fit.vutbr.cz
 */
    chdir('../..'); // ---> root
    include_once('./bussiness_layer/admin/check_admin.php');

    enforce_admin();

    function gen_col_head($name,$col)
    {
        echo '
            <div class="order-div" onclick="order_change('.$col.')">
                <div class="col-name" id="col-name-'.$col.'">'.$name.'</div>
                <div class="order-but-div"><i id="order-but-'.$col.'" class="order-but fa-sharp fa-solid fa-xs fa-chevron-up"></i> </div>
            </div>

        ';
        // <div class="show-tab-but" onclick="usr_tab_but_press()"></div>
        
    }

?>

<html>
    <head>
        <link rel="stylesheet" type="text/css" href="../../present_layer/admin/admin.css"/>
        <script src="https://kit.fontawesome.com/ea2428928f.js" crossorigin="anonymous"></script>   
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script type="text/javascript" src="../../bussiness_layer/admin/alter_user.js"></script>
        <script type="text/javascript" src="./user_list.js"></script>
    </head>

    <div>
        <nav>
            <h3>
                <div class="menu-div">
                    <div class="sett-but ico-hover" onclick="toggle_dropdown_menu()">
                        <i class="ico fa-2xl fa-solid fa-bars"></i>
                    </div>
                    <div id="dropdown-menu" class="dropdown-content content-hidden">
                        <a href="../authentication/logout.php" onclick="console.log(1);">Odhlásiť</a>
                        <div onclick="console.log(2);">Priať užívateľa</div>
                        <div onclick="console.log(3);">Resetovať filter</div>

                    </div>
                </div> 
                <div class="headline">Správa užívateľov</div>
            </h3>
        </nav>
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
