<?php 
chdir('../'); // ---> root

include_once('./bussiness_layer/checks.php');
//is_logged(); // Allow only authenticated users

include_once("./bussiness_layer/get_ticket.php");
echo my_tickets_map_json();
?>