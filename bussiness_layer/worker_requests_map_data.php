<?php 
chdir('../'); // ---> root

include_once('./bussiness_layer/checks.php');
//is_logged(); // Allow only authenticated users

include_once("./bussiness_layer/get_ticket.php");
echo worker_requests_map_json();
?>