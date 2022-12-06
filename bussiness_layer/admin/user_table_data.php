<?php
/***
 * @author xpavel39@stud.fit.vutbr.cz
 */
    include_once('./data_layer/db_user.php');
    include_once('./bussiness_layer/constants.php');

    /***
     * Return HTML of table containing all users
     */
    function get_user_table_rows()
    {
        global $roles;

        $even = false; // Mark even rows

        $html = "";

        $stmt = get_all_users();

        while( $row = $stmt->fetch() )
        {
            if($even)
                $html_row = '<tr class="even-row">';
            else
                $html_row = '<tr>';

            $even = !$even;

            $html_row .= '<td> '.$row['id']." </td>\n";
            $html_row .= '<td> '.$row['first_name']." </td>\n";
            $html_row .= '<td> '.$row['last_name']." </td>\n";
            $html_row .= '<td> '.$row['email']." </td>\n";
            $html_row .= '<td> '.$row['phone']." </td>\n";
            $html_row .= '<td> '.$roles[ $row['role'] ]." </td>\n";

            // button that removes the user. P.S. Admin cannot be removed
            if($row['role'] != ADMIN)
            {
                if(user_has_tickets($row['id']))
                    // User has active tickets. Cannot remove
                    $html_row .= '<td> Má aktívne tikety </td>'."\n";
                else if(user_has_service_requests($row['id']))
                    // User has active service requests. Cannot remove
                    $html_row .= '<td> Má service requesty </td>'."\n";
                else
                    // Generate delete button
                    $html_row .= '<td> <button onclick="handle_remove_button('. $row['id'] .')"> Odstrániť </button> </td>' . "\n";
            }
            else
                $html_row .= "<td> </td>\n";

            $html_row .= "<tr>\n";

            $html .= $html_row;
        }

        return $html;
    }

?>
