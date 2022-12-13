<?php 
/**
 * @author xsikul15@stud.fit.vutbr.cz
 * Called using AJAX to update ticket
 */
chdir('../'); // ---> root
include_once("./bussiness_layer/get_ticket.php");
echo my_tickets_map_json();
?>