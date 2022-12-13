<?php
/**
 * Called using AJAX
 * @author xpavel39@stud.fit.vutbr.cz
 * @Editor xtverd01@stud.fit.vutbr.cz
 * Generating apropiate html code of table row-by-row
 */
    chdir('..'); // root

    //include_once('./data_layer/db_user.php');
    include_once('./bussiness_layer/get_requests_table_data.php');
    include_once('./bussiness_layer/constants.php');

    if(session_id() == "")
        session_start();

    // Print table content
    echo get_requests_table_rows();

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

    /***
     * Return HTML of table containing all users 
     */
    function get_requests_table_rows()
    {
        global $roles;
        global $description_state;
        $counter = 0;

        $even = false; // Mark even rows

        $ord_col = isset($_GET['col']) ? $_GET['col'] : 0;
        $asc = isset($_GET['asc']) ? $_GET['asc'] : 1;
        $filter = isset($_GET['filt']) ? $_GET['filt'] : "";
        $choice = isset($_GET['choi']) ? $_GET['choi'] : "%";

        $data = get_requests_table_data($choice,$ord_col,$asc,$filter);

        $json = json_decode($data,true);

        $html = "";

        foreach($json as $row) { 
            if($row['state'] == 0)
                $html_row = '<tr height="55px" style="background-color: #ff595e;">';
            else if($row['state'] == 1)
                $html_row = '<tr height="55px" style="background-color: #ffca3a;">';
            else if($row['state'] == 2)
                $html_row = '<tr height="55px" style="background-color: #8ac926;">';

                //$html_row = '<tr height="55px"';

            $even = !$even;

            // Add row id and close 'tr' tag

            $html_row .= 'id="row_'.$row['id'].'" >';

            // '.set_editable($row['id'],'first_name').' STACI VLOZIT

            $html_row .= '<td>'.$row['id']." </td>\n";
            $html_row .= '<td>'.$row['category']." </td>\n";
            $html_row .= $row['address']."\n";
            $html_row .= '<td> '.$row['date']." </td>\n";
            $html_row .= '<td> '.$description_state[$row['state']]." </td>\n";

            $html_row .= '<td><div class="expand-but" onclick="Expand('.$counter.')">  <i class="del-ico fa-regular fa fa-arrow-circle-o-down fa-2xl"></i> </div></td>';

            $html_row .= "<tr>";

            $html_row .= 
            "
            <tr class=\"RowNested$counter\" style=\"display:none\">
            <td colspan='6'>
            <table class='expand-table'>
            <colgroup>
            <col width='25%'>
            <col width='6.25%'>
            <col width='6.25%'>
            <col width='12.5%'>
            <col width='25%'>
            <col width='25%'>
            </colgroup>
            <thead>
                <tr>
                    <th colspan='6'>Požiadavek</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class='indescr' colspan='1'>Ticket ID:</td>
                    <td colspan='4'>".$row['for_ticket']."</td>
                    <td colspan='1' rowspan='4' style='padding-bottom:0px;'>
                    <div class='container'>
                        <input type='checkbox' id='zoomCheck$counter'>
                        <label for='zoomCheck$counter'>
                            <img src='".$row['photo']."' alt=\"Chyba\">                       
                        </label>
                    </div>
                    </td>
                </tr>
                <tr>
                    <td class='indescr' colspan='1'>Kategória:</td>
                    <td colspan='4'>".$row['category']."</td>
                </tr>
                <tr>
                    <td class='indescr' colspan='1'>Pozícia:</td>
                    <td colspan='4'>".$row['lat']." : ".$row['lng']."</td>
                </tr>
                <tr>
                    <td class='indescr' colspan='1'>Poloha:</td>
                    ".$row['address_exp']."
                </tr>
                <tr class='expand-lastrow'>
                    <td class='indescr' colspan='1'>Zadanie:</td>
                    <td colspan='5' style='text-align:left;'>".$row['description_from_manager']."</td>
                </tr>
            </tbody>
            </table>
            </td>
            </tr>
            ";

            if($row['state'] == 0)
            {
                $html_row .=
                "
                <tr class=\"RowNested$counter\" style=\"display:none\">
                <td colspan='6'>
                <table class='expand-table'>
                <colgroup>
                <col width='25%'>
                <col width='25%'>
                <col width='25%'>
                <col width='25%'>
                </colgroup>
                <thead>
                    <tr>
                        <th colspan='4'>Služba</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class='indescr' colspan='1'>Očakávaný dátum:</td>
                        <td colspan='1'><input type='date' id='expected_date$counter' name='expected_date'></td>
                        <td class='indescr' colspan='1'>Cena:</td>
                        <td colspan='1'><input type='number' step='0.01' id='price$counter' name='price'><br></td>
                    </tr>
                    <tr>
                        <td class='indescr' colspan='1'>Komentár:</td>
                        <td colspan='3'><input style='width:92%;margin-right: 100px;' type='text' id='comment$counter' name='comment'></td>
                    </tr>
                    <tr>
                        <td colspan='4'><button class='send-button' onclick='handle_button_0_1(".$row['id'].", $counter)'>Odoslať</button></td>
                    </tr>
                </tbody>
                </table>
                </td>
                </tr>
                ";
            }
            else if($row['state'] == 1)
            {
                $html_row .=
                "
                <tr class=\"RowNested$counter\" style=\"display:none\">
                <td colspan='6'>
                <table class='expand-table'>
                <colgroup>
                <col width='25%'>
                <col width='25%'>
                <col width='25%'>
                <col width='25%'>
                </colgroup>
                <thead>
                    <tr>
                        <th colspan='4'>Služba</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class='indescr' colspan='1'>Očakávaný dátum:</td>
                        <td colspan='1'>".$row['expected_date']."</td>
                        <td class='indescr' colspan='1'>Cena:</td>
                        <td colspan='1'>".$row['price']."</td>
                    </tr>
                    <tr>
                        <td class='indescr' colspan='1'>Komentár:</td>
                        <td colspan='3' style='text-align:left;'>".$row['comment_from_worker']."</td>
                    </tr>
                    <tr>
                        <td colspan='4'><button class='finish-button' onclick='handle_button_1_2(".$row['id'].")'>Skončiť</button></td>
                    </tr>
                </tbody>
                </table>
                </td>
                </tr>
                ";
            }
            else if($row['state'] == 2)
            {
                $html_row .=
                "
                <tr class=\"RowNested$counter\" style=\"display:none\">
                <td colspan='6'>
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
                <thead>
                    <tr>
                        <th colspan='7'>Služba</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class='indescr' colspan='1'>Očakávaný dátum:</td>
                        <td colspan='2'>".$row['expected_date']."</td>
                        <td class='indescr' colspan='1'>Dátum opravy:</td>
                        <td colspan='1'>".$row['date_fixed']."</td>
                        <td class='indescr' colspan='1'>Cena:</td>
                        <td colspan='1'>".$row['price']."</td>
                    </tr>
                    <tr>
                        <td class='indescr' colspan='2'>Komentár:</td>
                        <td colspan='5' style='text-align:left;'>".$row['comment_from_worker']."</td>
                    </tr>
                </tbody>
                </table>
                </td>
                </tr>
                ";
            }

            $counter++;

            $html .= $html_row;
        }

        return $html;
    }
?>