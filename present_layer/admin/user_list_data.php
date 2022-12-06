<?php
/***
 * @author xpavel39@stud.fit.vutbr.cz
 */
    chdir('../..'); // root

    include_once('./bussiness_layer/admin/check_admin.php');
    enforce_admin();

    include_once('./data_layer/db_user.php');
    include_once('./bussiness_layer/admin/user_table_data.php');

    if(session_id() == "")
        session_start();

    // Print table content
    echo get_user_table_rows();
?>