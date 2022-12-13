<?php
/**
 * @author xsikul15@stud.fit.vutbr.cz
 * Called by AJAX
 */
chdir('../'); // ---> root
include_once("./bussiness_layer/get_ticket.php");
echo worker_requests_map_json();
?>