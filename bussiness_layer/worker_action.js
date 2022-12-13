/**
 * @author xtverd01stud.fit.vutbr.cz
 * Using AJAX updating states
 */
function state_update_xml_0_1(rid, price, exp_date, comment)
{
    var req = new XMLHttpRequest();
    req.open("POST","../bussiness_layer/state_update.php",true);

    var formData = new FormData();
    formData.append('rid', rid);
    formData.append('price', price);
    formData.append('exp_date', exp_date);
    formData.append('comment', comment);

    req.send(formData);
}

function state_update_xml_1_2(rid)
{
    var req = new XMLHttpRequest();
    req.open("POST","../bussiness_layer/state_update.php",true);
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    req.send("rid="+rid);
}

/***
 * Using AJAX update user
 */
// function update_user(id,col,new_val)
// {
//     req = new XMLHttpRequest();
//     req.open("POST","../../bussiness_layer/admin/update_user.php");
//     req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
//     req.send("id=" + id + "&col=" + col + "&new_val=" + new_val);
// }

/***
 * Handle form sending
 */
function handle_button_0_1(rid, counter)
{
    if (confirm('Ste si istý odoslaním formulára?')) {

        
        var price = document.getElementById("price"+counter).value;
        var exp_date = document.getElementById("expected_date"+counter).value;
        var comment = document.getElementById("comment"+counter).value;

        if (price == "" || exp_date == "") {
            alert("Vyplňte všetky polia!");
        }
        else {
            state_update_xml_0_1(rid, price, exp_date, comment);
            refresh_tables_after(100);
        }
    }
}

/***
 * Handle the finish of a request
 */
function handle_button_1_2(rid)
{
    if (confirm('Ste si istý dokončením žiadosti?')) {
        state_update_xml_1_2(rid);
        refresh_tables_after(100);
    }
}


