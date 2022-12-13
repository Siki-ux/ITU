<?php
/**
 * @author xtverd01@stud.fit.vutbr.cz
 * Returns the json dictionary of needed data from database
 */
    include_once("./data_layer/db_user.php");
    include_once("./data_layer/db_tickets.php");
    include_once("./bussiness_layer/get_ticket.php");

    $cols = [ 0 => 'id' , 1 => 'description', 2 => 'state_from_manager', 3 => 'state', 4 => 'time_created', 5 => 'time_modified'];

    /***
     * Return data of all users as .json
     */
    function get_tickets_table_data($choice, $ord_col, $asc, $filter = "")
    {
        // if(isset($_SESSION["email"])){
        //     $id =  get_user_by_email($_SESSION["email"])["id"];
        // } else{
        //     echo "fatal error";
        //     exit();
        // }

        global $cols;
        
        $stmt = get_my_tickets_list($cols[$ord_col], $asc, $filter, $choice);

        $json = '[';

        while( $row = $stmt->fetch() )
        {
            $row_ticket = (get_ticket($row[0]))->fetch();
            $ticket = get_ticket_data($row_ticket);

            $address = "<td id='address".$ticket[0]."'><script type='text/javascript'>get_address(".$ticket[0].",".$ticket[2].",".$ticket[3].");</script></td>"; //street

            $json .= '{';
            $json .= ' "id": '.' "'.$row[0].'" , ';
            $json .= ' "category":'.'"'.$ticket[1].'" ,';
            $json .= ' "address":'.'"'.$address.'" ,';
            $json .= ' "status":'.'"'.$row['state_from_manager'].'" ,';
            $json .= ' "req":'.'"'.$row['state'].'" ,';
            $json .= ' "message":'.'"'.$row['msg_from_manager'].'" ,';
            $json .= ' "time_created":'.'"'.$row['time_created'].'" ,';
            $json .= ' "time_modified":'.'"'.$row['time_modified'].'" ,';
            $json .= ' "photo":'.'"'.$row['photo'].'" ,';
            $json .= ' "worker_id":'.'"'.$row['worker_id'].'" ,';
            $json .= ' "task":'.'"'.$row['description_from_manager'].'" ,';
            $json .= ' "expected_date":'.'"'.$row['expected_date'].'" ,';
            $json .= ' "date_fixed":'.'"'.$row['date_fixed'].'" ,';
            $json .= ' "price":'.'"'.$row['price'].'" ,';
            $json .= ' "comment":'.'"'.$row['comment_from_worker'].'"';
            $json .= "},";
        }

        // Remove last ,
        $json = rtrim($json,',');

        $json .= ']';
        return $json;
    }
    

?>
