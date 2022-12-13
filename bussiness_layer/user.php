<?php
/**
 * @author xsikul15stud.fit.vutbr.cz
 * Used to show name of logged in user
 */
include_once('./data_layer/db_user.php');

function print_user_from_email($email){ 
    $pos = strpos($email,"@",0);
    return substr($email,0,$pos);
}

function get_name(){
    if(isset($_SESSION['email'])) {
        $user = get_user_by_email($_SESSION['email']);
        if (isset($user["first_name"]) && isset($user["last_name"])){
            if ($user["first_name"] != "" && $user["last_name"] != ""){
                return $user["first_name"]. " " . $user["last_name"];
            }
        }
        if(isset($user["first_name"])){
            if ($user["first_name"] != ""){
                return $user["first_name"];
            }
        }
        if(isset($user["last_name"])){
            if ($user["last_name"] != "") {
                return $user["last_name"];
            }
        }
        return print_user_from_email($user["email"]);
    }else {
        return "Anonymný užívateľ";
    }
}
?>