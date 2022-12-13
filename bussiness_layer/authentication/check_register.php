<?php 
/**
 * @author sikul15@stud.fit.vutbr.cz
 * Check of user registration
 */
chdir("../..");
include_once('./data_layer/db_user.php');

if(isset($_POST['email'])){
    if( ! email_ok($_POST['email'])){
        echo 'Neplatný e-mail';
        exit();
    }
    $email = $_POST['email'];

    if(get_user_by_email($email) !== false){
        echo 'E-mail sa už používa!';
        exit();
    }

    if (strlen($_POST['password']) < 8) {
        echo 'Heslo musí mať minimálne 8 znakov';
        exit();
    }

    if ((!preg_match("/^[+0-9 ]*$/",$_POST['phone']) || strlen($_POST['phone']) > 20)) {
        echo 'Neplatné telefónne číslo';
        exit();
    }
    $role = 0;
    $pw_hash = hash('sha256',$_POST['password'],);
    $f_name = isset($_POST['f_name']) ? $_POST['f_name'] : "";
    $l_name = isset($_POST['l_name']) ? $_POST['l_name'] : "";
    $phone = isset($_POST['phone']) ? $_POST['phone'] : "";
    insert_user($f_name,$l_name,$email,$phone,$pw_hash,$role);
    echo 'Uspešne zaregistrovaný';


}else {
    echo 'Something went wrong';
    exit();
}



/***
 * Check if $email is in the format of an email adress.
 */
function email_ok($email)
{ 
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}
?>