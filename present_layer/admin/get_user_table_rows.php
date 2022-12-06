<?php
/***
 * Called using AJAX
 * @author xpavel39@stud.fit.vutbr.cz
 */
    chdir('../..'); // root

    include_once('./bussiness_layer/admin/check_admin.php');
    enforce_admin();

    include_once('./data_layer/db_user.php');
    include_once('./bussiness_layer/admin/user_table_data.php');

    if(session_id() == "")
        session_start();

    // Print table content
    echo get_user_table_rows();

    /***
     * Return HTML of table containing all users 
     */
    function get_user_table_rows()
    {
        global $roles;

        $even = false; // Mark even rows

        $data = get_user_table_data();

        $json = json_decode($data,true);

        $html = "";

        foreach($json as $row) { 
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
                {
                    // Generate delete button
                    $html_row .= '<td> <div class="rem-usr-but" onclick="handle_remove_button('.$row['id'].')">     <i class="fa-regular fa-circle-xmark fa-2xl"></i>     </div></td>';
                }
            }
            else
                $html_row .= "<td> </td>\n";

            $html_row .= "<tr>\n";

            $html .= $html_row;
        }

        return $html;
    }
?>