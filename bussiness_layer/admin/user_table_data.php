<?php
/***
 * @author xpavel39@stud.fit.vutbr.cz
 */
    include_once('./data_layer/db_user.php');
    include_once('./bussiness_layer/constants.php');

    $cols = [ 0 => 'id' , 1 => 'first_name' , 2 => 'last_name', 3 => 'email' , 4 => 'phone' , 5 => 'role'];


    /***
     * Return data of all users as .json
     */
    function get_user_table_data($ord_col, $asc)
    {
        global $cols;

        $stmt = get_all_users($cols[$ord_col], $asc);

        $json = '[';

        while( $row = $stmt->fetch() )
        {
            $json .= '{';
            $json .= ' "id": '.' "'.$row['id'].'" , ';
            $json .= ' "first_name":'.'"'.$row['first_name'].'" ,';
            $json .= ' "last_name":'.'"'.$row['last_name'].'" ,';
            $json .= ' "email":'.'"'.$row['email'].'" ,';
            $json .= ' "phone":'.'"'.$row['phone'].'" ,';
            $json .= ' "role":'.'"'.$row['role'].'"';
            $json .= "},";
        }

        // Remove last ,
        $json = rtrim($json,',');

        $json .= ']';
        return $json;
    }
    

?>
