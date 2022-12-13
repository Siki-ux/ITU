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
     * Generate html code that will make the field editable, and will assign a callback function for key events
     */
    function set_editable($id, $attr)
    {
        return 'id="'.$id.'_'.$attr.'" contenteditable="true" spellcheck="false" 
            onfocusout="((event)=>{
                field_change(event,'.$id.",'".$attr."'".',false);
              })(event)"
            onKeyDown="((event)=>{
                if (event.keyCode == 13) // Enter
                    field_change(event,'.$id.",'".$attr."'".');
            })(event)"';
    }

    function gen_select_menu($id, $role)
    {
        global $roles;
        $html = '<select class="role-select" id="role_'.$id.'" onchange="select_change('.$id.')">';

        foreach($roles as $key => $val)
        {
            $html .= '<option value = "'.$key.'" ';

            if($key == $role)
                $html .= 'selected ';

            // Close option
            $html .= '>'.$val.'</option>';
        }
        $html .= '</select>';

        return $html;
    }

    function gen_row_role($role, $even)
    {
        global $roles;

        $res = 'class="';

        if($even)
            $res .= 'even-';

        if($role == ADMIN)
            $res .= 'admin-';
        elseif($role == MANAGER)
            $res .= 'manager-';
        elseif($role == WORKER)
            $res .= 'worker-';

        $res .= 'row"';
        return $res;
    }

    /***
     * Return HTML of table containing all users 
     */
    function get_user_table_rows()
    {
        global $roles;

        $even = false; // Mark even rows

        $ord_col = isset($_GET['col']) ? $_GET['col'] : 0;
        $asc = isset($_GET['asc']) ? $_GET['asc'] : 1;
        $filter = isset($_GET['filt']) ? $_GET['filt'] : "";
        $role = isset($_GET['role']) ? $_GET['role'] : -1;

        $data = get_user_table_data($ord_col,$asc,$filter,$role);

        $json = json_decode($data,true);

        $html = "";

        foreach($json as $row) { 
            $html_row = '<tr '.gen_row_role($row['role'],$even).' ';

            $even = !$even;

            // Add row id and close 'tr' tag

            $html_row .= 'id="row_'.$row['id'].'" >';

            // '.set_editable($row['id'],'first_name').' STACI VLOZIT

            $html_row .= '<td> '.$row['id']." </td>\n";
            $html_row .= '<td '.set_editable($row['id'],'first_name').'> '.$row['first_name']." </td>\n";
            $html_row .= '<td '.set_editable($row['id'],'last_name').'> '.$row['last_name']." </td>\n";
            $html_row .= '<td '.set_editable($row['id'],'email').'> '.$row['email']." </td>\n";
            $html_row .= '<td '.set_editable($row['id'],'phone').'> '.$row['phone']." </td>\n";

            $html_row .= '<td class="role-td"> '.gen_select_menu($row['id'],$row['role'])." </td>\n";

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
                    $html_row .= '<td><div class="rem-usr-but" onclick="handle_remove_button('.$row['id'].')">  <i class="del-ico fa-regular fa-circle-xmark fa-2xl"></i> </div></td>';
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