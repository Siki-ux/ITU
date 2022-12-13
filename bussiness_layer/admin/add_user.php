<?php
    /***
     * This file is called using AJAX in order to add a new user
     * @author xpavel39@stud.fit.vutbr.cz
     */

    chdir('../..'); // root 

    include_once('./data_layer/db_user.php');

    if( (! isset($_POST['email'])) || (! isset($_POST['password']))) // Not enough data
        return;

    $f_name = get_post('first_name');
    $l_name = get_post('last_name');
    $email = get_post('email');
    $pwd = get_post('password');
    $phone = get_post('phone');
    $role = get_post('role');

    $pw_hash = hash('sha256',$pwd);

    insert_user($f_name,$l_name,$email,$phone,$pw_hash,$role);

    echo(true);

    function get_post($name)
    {
        return isset($_POST[$name])?$_POST[$name]:"";
    }
    
?>