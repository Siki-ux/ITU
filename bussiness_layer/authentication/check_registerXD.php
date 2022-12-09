<?php 
chdir("../../");
include_once('./data_layer/db_user.php');

if(isset($_POST['email'])){
    if( ! email_ok($_POST['email'])){
        echo "<script>alert('Neplatný e-mail')</script>";
        header("refresh:0.1; ../../index.php");
        exit();
    }
    $email = $_POST['email'];

    if(get_user_by_email($email) !== false){
        echo "<script>alert('E-mail sa už používa!')</script>";
        header("refresh:0.1; ../../index.php");
        exit();
    }
    echo "<script>alert('Uspešne zaregistrovaný')</script>";
    header("refresh:0.1; ../../index.php");


}else {
    echo "<script>alert('Something went wrong')</script>";
    header("refresh:0.1; ../../index.php");
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