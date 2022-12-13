<?php 
/**
 * @author xsikul15@stud.fit.vutbr.cz
 * Functions used to check users
 */
include_once("./data_layer/db_user.php");
include_once('./bussiness_layer/constants.php');

function is_logged(){
    if(session_id() == "")
        session_start();
        
    if (isset($_SESSION["email"])){
        return 1;
    }else{
        echo "Na túto akciu musíš byť prihlasený! Redirecting to index....";
        header("refresh:1; ../index.php");
        exit();
    }
}

function is_logged_in()
{
    return isset($_SESSION['email']);
}

function is_worker()
{
    if (is_logged_in())
    {
        $role = get_user_by_email($_SESSION["email"])["role"];
        if($role == WORKER)
            return true;
    }
    return false;
}

function is_manager()
{
    if (is_logged_in())
    {
        $role = get_user_by_email($_SESSION["email"])["role"];
        if($role == MANAGER)
            return true;
    }
    return false;
}
?>