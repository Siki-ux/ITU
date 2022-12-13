<?php 
/**
 * @author xsikul15@stud.fit.vutbr.cz
 * Called by AJAX
 */
chdir('../'); // ---> root
include_once("./bussiness_layer/get_ticket.php");
echo all_tickets_map_json();
?>