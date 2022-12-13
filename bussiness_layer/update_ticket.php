<?php
    chdir('..'); // root

    include_once('./data_layer/db_tickets.php');

    if(isset($_POST['col']) && isset($_POST['new_val']) && isset($_POST['id']))
    {
        update_ticket($_POST['id'], $_POST['col'], $_POST['new_val']);
    }
?>