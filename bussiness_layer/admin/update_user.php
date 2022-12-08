<?php
    /*** This file is call using AJAX
     *      Updates a user in the database
     *      @author xpavel39@stud.fit.vutbr.cz
     */

    chdir('../..'); // root

    include_once('./bussiness_layer/admin/check_admin.php');
    enforce_admin();

    include_once('./data_layer/db_user.php');

    
    if(isset($_POST['col']) && isset($_POST['new_val']) && isset($_POST['id']))
    {
        update_user($_POST['id'], $_POST['col'], $_POST['new_val']);
    }
?>