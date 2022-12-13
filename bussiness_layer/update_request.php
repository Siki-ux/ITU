<?php
/**
* @author xtverd01stud.fit.vutbr.cz
 * Called using AJAX to update requests
 */
    chdir('..'); // root

    include_once('./data_layer/db_request.php');

    if(isset($_POST['id']) && isset($_POST['worker']) && isset($_POST['task']))
    {
        insert_request($_POST['worker'], $_POST['id'], $_POST['task']);
    }
?>