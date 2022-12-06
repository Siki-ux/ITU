<?php
/***
 * @author xpavel39@stud.fit.vutbr.cz
 */
    include_once('./data_layer/db_user.php');
    include_once('./bussiness_layer/constants.php');

    /***
     * Return data of all users as .json
     */
    function get_user_table_data()
    {
        global $roles;

        $stmt = get_all_users();

        $json = '[';

        while( $row = $stmt->fetch() )
        {
            $json .= '{';
            $json .= ' "id": '.' " '.$row['id'].' " , ';
            $json .= ' "first_name":'.'"'.$row['first_name'].'" ,';
            $json .= ' "last_name":'.'"'.$row['last_name'].'" ,';
            $json .= ' "email":'.'"'.$row['email'].'" ,';
            $json .= ' "phone":'.'"'.$row['phone'].'" ,';
            $json .= ' "role":'.'"'.$roles[ $row['role'] ].'"';
            $json .= "},";
        }

        // Remove last ,
        $json = rtrim($json,',');

        $json .= ']';
        return $json;
    }
    

?>
