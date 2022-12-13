<?php
/**
 * @author sikul15@stud.fit.vutbr.cz
 * Check of user login
 */
if (session_id() == "")
    session_start();
include('../../data_layer/db_user.php');
$email = isset($_POST['email']) ? $_POST['email'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$user = get_user_by_email($email);
if($user == null){
    echo '1';
}else if(hash('sha256',$password) == $user['PW_HASH']){
    $_SESSION['email'] = $email;
    echo '0';
}else {
    echo '2';
}
?>