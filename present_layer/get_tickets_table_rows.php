<?php
/**
 * Called using AJAX
 * @author xpavel39@stud.fit.vutbr.cz
 * @Editor xtverd01@stud.fit.vutbr.cz
 * Generating apropiate html code of table row-by-row
 */
    chdir('..'); // root

    include_once('./data_layer/db_user.php');
    include_once('./bussiness_layer/get_tickets_table_data.php');
    include_once("./data_layer/db_request.php");
    //include_once('./bussiness_layer/constants.php');

    $description_state_manager = [0 => 'Zaevidovaný',1 => 'Pracujeme',2 => 'Vyriešené'];

    if(session_id() == "")
        session_start();

    // Print table content
    echo get_tickets_table_rows();

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

    function gen_select_menu($id, $stat)
    {
        global $description_state_manager;
        $html = '<select class="role-select" id="role_'.$id.'" onchange="select_change('.$id.')">';

        foreach($description_state_manager as $key => $val)
        {
            $html .= '<option value = "'.$key.'" ';

            if($key == $stat)
                $html .= 'selected ';

            // Close option
            $html .= '>'.$val.'</option>';
        }
        
        $html .= '</select>';

        return $html;
    }


    function get_workers_array() {
        $table = get_users_by_role(3);
        $i = 0;
        while($row = $table->fetch())
        {
            $workers[$i][0] = $row['id'];
            $workers[$i][1] = $row['first_name'];
            $workers[$i][2] = $row['last_name'];
            $i++;
        }
        return $workers;
    }

    function worker_select_htmlgenerator($array, $ticket_id) {
        $html = "";
        $html = $html . "
        <select class='worker-select' id='worker_$ticket_id'>
        <option hidden disabled selected value></option>
        ";
    
        for ($i = 0; $i < sizeof($array); $i++) 
        {
            $html = $html . "<option value=".$array[$i][0].">".$array[$i][1]." ".$array[$i][2]."</option>";
        }
    
        $html = $html . "
        </select>
        ";
    
        return $html;
    }

    function get_name_by_id($array, $id) {
        for ($i = 0; $i < sizeof($array); $i++) 
        {
            if($array[$i][0] == $id)
                return $array[$i][1] . " " . $array[$i][2];
        }
    }

    /***
     * Return HTML of table containing all users 
     */
    function get_tickets_table_rows()
    {
        global $roles;
        global $description_state_manager;
        $counter = 0;

        $workers = get_workers_array();

        $ord_col = isset($_GET['col']) ? $_GET['col'] : 0;
        $asc = isset($_GET['asc']) ? $_GET['asc'] : 1;
        $filter = isset($_GET['filt']) ? $_GET['filt'] : "";
        $choice = isset($_GET['choi']) ? $_GET['choi'] : "%";

        $data = get_tickets_table_data($choice,$ord_col,$asc,$filter);

        $json = json_decode($data,true);

        $html = "";

        foreach($json as $row) { 
            $assigned = false;
            if(get_request_by_ticket($row['id']))
            {
                $assigned = true;
            }

            $html_row = '<tr height="68px"';

            // Add row id and close 'tr' tag

            $html_row .= 'id="row_'.$row['id'].'">';

            // '.set_editable($row['id'],'first_name').' STACI VLOZIT

            $html_row .= '<td>'.$row['id']."</td>\n";
            $html_row .= '<td>'.$row['category']."</td>\n";
            $html_row .= $row['address']."\n";

            if($row['status'] == 0)
                $html_row .= '<td style="background-color: #ff595e;"> '.gen_select_menu($row['id'],$row['status'])." </td>\n";
            else if($row['status'] == 1)
                $html_row .= '<td style="background-color: #ffca3a;"> '.gen_select_menu($row['id'],$row['status'])." </td>\n";
            else if($row['status'] == 2)
                $html_row .= '<td style="background-color: #8ac926;"> '.gen_select_menu($row['id'],$row['status'])." </td>\n";

            if($row['req'] == 0)
                $html_row .= '<td style="background-color: #ff595e;">'.$row['req']."</td>\n";
            else if($row['req'] == 1)
                $html_row .= '<td style="background-color: #ffca3a;">'.$row['req']."</td>\n";
            else if($row['req'] == 2)
                $html_row .= '<td style="background-color: #8ac926;">'.$row['req']."</td>\n";
            else
                $html_row .= '<td>'.$row['req']."</td>\n";
                
            $html_row .= '<td '.set_editable($row['id'],'msg_from_manager').'>'.$row['message']."</td>\n";
            $html_row .= '<td>'.$row['time_created']."</td>\n";
            $html_row .= '<td>'.$row['time_modified']."</td>\n";
            $html_row .= "<td>
            <div class='container'>
            <input type='checkbox' id='zoomCheck$counter'>
            <label for='zoomCheck$counter'>
                <img src='".$row['photo']."' alt=\"Chyba\">                       
            </label>
            </div>
            </td\n>";

            $html_row .= '<td><div class="expand-but" onclick="Expand('.$counter.')">  <i class="del-ico fa-regular fa fa-arrow-circle-o-down fa-2xl"></i> </div></td>';

            $html_row .= "<tr>";

            $html_row .=
            "
            <tr class=\"RowNested$counter\" style=\"display:none\">
            <td colspan='10'>
            <table class='expand-table'>
            <colgroup>
            <col width='7%'>
            <col width='17%'>
            <col width='7%'>
            <col width='60.85%'>
            <col width='8.15%'>
            </colgroup>
            <thead>
                <tr>
                    <th colspan='5'>Služba</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan='1' class='indescr' style='text-align:center;'>Technik:</td>";
            
            if (!$assigned)
                $name = worker_select_htmlgenerator($workers, $row['id']);
            else
                $name = get_name_by_id($workers, $row['worker_id']);

            $html_row .=
            "
                    <td colspan='1'>".$name."</td>
                    <td colspan='1' class='indescr' style='text-align:center;'>Zadanie:</td>";

            if (!$assigned)
                $task = "<input style='width:97%;' type='text' id='task_".$row['id']."'>";
            else
                $task = $row['task'];

            $html_row .=
            "
                    <td colspan='1' style='text-align:left;'>".$task."</td>";

            if (!$assigned)
                $button = "<button class='send-button' onclick='insert_req(".$row['id'].")'>Odoslať</button>";
            else
                $button = '';

            $html_row .=
            "
                    <td colspan='1'>$button</td>
                </tr>
            </tbody>
            </table>
            </td>
            </tr>
            ";

            $html_row .=
            "
            <tr class=\"RowNested$counter\" style=\"display:none\">
            <td colspan='10'>
            <table class='expand-table'>
            <colgroup>
            <col width='18.75%'>
            <col width='6.25%'>
            <col width='12.5%'>
            <col width='18.75%'>
            <col width='18.75%'>
            <col width='12.5%'>
            <col width='12.5%'>
            </colgroup>
            <tr>
                <td colspan='1' class='indescr'>Očakávaný dátum</td>
                <td colspan='2'>".$row['expected_date']."</td>
                <td colspan='1' class='indescr'>Dátum opravy</td>
                <td colspan='1'>".$row['date_fixed']."</td>
                <td colspan='1' class='indescr'>Cena</td>
                <td colspan='1'>".$row['price']."</td>
            </tr>
            <tr>
                <td colspan='2' class='indescr'>Komentár</td>
                <td colspan='5' style='text-align:left; padding-bottom:12px;'>".$row['comment']."</td>
            </tr>
            </table>
            </td>
            </tr>
            ";

            $counter++;

            $html .= $html_row;
        }

        return $html;
    }
?>