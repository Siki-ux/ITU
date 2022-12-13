<?php
/**
 * @author xtverd01@stud.fit.vutbr.cz
 * Returns the json dictionary of needed data from database
 */
    include_once("./data_layer/db_user.php");
    include_once("./data_layer/db_tickets.php");
    include_once("./data_layer/db_request.php");
    include_once("./bussiness_layer/get_ticket.php");

    $cols = [ 0 => 'id' , 1 => 'description', 2 => 'state'];

    /***
     * Return data of all users as .json
     */
    function get_requests_table_data($choice, $ord_col, $asc, $filter = "")
    {
        if(isset($_SESSION["email"])){
            $id =  get_user_by_email($_SESSION["email"])["id"];
        } else{
            echo "fatal error";
            exit();
        }

        global $cols;
        
        $stmt = get_my_requests_list($cols[$ord_col], $asc, $filter, $choice, $id);

        $json = '[';

        while( $row = $stmt->fetch() )
        {
            $row_ticket = (get_ticket($row['for_ticket']))->fetch();
            $ticket = get_ticket_data($row_ticket);

            $address = "<td id='address".$ticket[0]."'><script type='text/javascript'>get_address('".$ticket[0]."',".$ticket[2].",".$ticket[3].");</script></td>";
            $address_exp = "<td colspan='4' id='address".$ticket[0]."exp'><script type='text/javascript'>get_address('".$ticket[0]."exp',".$ticket[2].",".$ticket[3].");</script></td>";

            if($row['state'] != 2)
                $date = $row['expected_date'];
            else
                $date = $row['date_fixed'];

            $json .= '{';
            $json .= ' "id": '.' "'.$row[0].'" , ';
            $json .= ' "category":'.'"'.$ticket[1].'" ,';
            $json .= ' "address":'.'"'.$address.'" ,';
            $json .= ' "address_exp":'.'"'.$address_exp.'" ,';
            $json .= ' "lat":'.'"'.$ticket[2].'" ,';
            $json .= ' "lng":'.'"'.$ticket[3].'" ,';
            $json .= ' "date":'.'"'.$date.'" ,';
            $json .= ' "for_ticket":'.'"'.$row['for_ticket'].'" ,';
            $json .= ' "state":'.'"'.$row['state'].'" ,';
            $json .= ' "description_from_manager":'.'"'.$row['description_from_manager'].'" ,';
            $json .= ' "expected_date":'.'"'.$row['expected_date'].'" ,';
            $json .= ' "price":'.'"'.$row['price'].'" ,';
            $json .= ' "comment_from_worker":'.'"'.$row['comment_from_worker'].'" ,';
            $json .= ' "date_fixed":'.'"'.$row['date_fixed'].'" ,';
            $json .= ' "photo":'.'"'.$row['photo'].'"';
            $json .= "},";
        }

        // Remove last ,
        $json = rtrim($json,',');

        $json .= ']';
        return $json;
    }
    

?>
