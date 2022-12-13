<?php
/**
 * @author xtverd01stud.fit.vutbr.cz
 * Called using AJAX to update state
 */
    chdir('..'); // root

    include_once('./data_layer/db_request.php');

    if(isset($_POST['price']) && isset($_POST['exp_date'])) // 1-2
    {
        $rid = $_POST['rid'];
        $sql_date = date('Y-m-d', strtotime($_POST['exp_date']));
        $price = $_POST['price'];
        $comment = $_POST['comment'];

        state_update_0_1($rid, $sql_date , $price, $comment);
    }
    else // 2-3
    {
        state_update_1_2($_POST['rid']);
    }
    
?>