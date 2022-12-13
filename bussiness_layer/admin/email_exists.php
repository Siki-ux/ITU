<?php
    /***
     * This file is called using AJAX in order to figure out if email is already used in the database
     * @author xpavel39@stud.fit.vutbr.cz
     */

    chdir('../..'); // root

    include_once('./data_layer/db_user.php');

    if(isset($_POST['email_to_check']))
    {
        if(get_user_by_email($_POST['email_to_check']))
            echo(true);
        else
            echo(false);
    }
    
?>