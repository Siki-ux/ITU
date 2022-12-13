<?php 
/**
 * @author xsikul12@stud.fit.vutbr.cz
 * Called using AJAX to remove ticket
 */
include_once("../data_layer/db_tickets.php");
if(isset($_POST['remove_ticket'])){
    remove_ticket($_POST['remove_ticket']);
}
?>