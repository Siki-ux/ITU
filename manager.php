<?php
/**
 * @author xtverd01
 * Intro for ticket manager.
 */
include_once("./bussiness_layer/checks.php");
if(session_id() == "")
    session_start();
if(! is_manager() )
    header('Location: ./index.php');

    header('Location: ./present_layer/manager_tickets.php');
?>