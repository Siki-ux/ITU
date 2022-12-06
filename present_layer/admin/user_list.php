<?php
/***
 * @author xpavel39@stud.fit.vutbr.cz
 */
    chdir('../..'); // ---> root
    include_once('./bussiness_layer/admin/check_admin.php');

    enforce_admin();

?>

<html>
    <head>
        <link rel="stylesheet" type="text/css" href="../../present_layer/admin/admin.css"/>
        <script src="https://kit.fontawesome.com/ea2428928f.js" crossorigin="anonymous"></script>   
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script type="text/javascript" src="../../bussiness_layer/admin/remove_user.js"></script>
        <script type="text/javascript" src="./user_list.js"></script>
    </head>

    <h2 class="main">Správa užívateľov</h2>
    <nav>
        <h3 class="back"><a href = "../../index.php">Späť</a></h2>
    </nav> 

    <table class="admin-table" id="usr-tab"> 
        <thead>
            <tr>
                <th>ID</th>
                <th>Krstné meno</th>
                <th>Priezvisko</th> 
                <th>e-mail</th>
                <th>Telefón</th> 
                <th>Rola</th>
                <th>Odstrániť</th>
            </tr>
        </thead>
        
        <tbody id="tab-of-users"> 

        </tbody>
    </table> 

    <div class="show-tab-but" onclick="usr_tab_but_press()"><i id="show-usr-tab-but" class="fa-sharp fa-solid fa-chevron-up"></i></div>

</html>
