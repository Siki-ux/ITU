

function remove_user(id)
{
    req = new XMLHttpRequest();
    req.open("POST","../../bussiness_layer/admin/remove_user.php");
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    req.send("remove_user_id=" + id);
}

function update_user(id,col,new_val)
{
    req = new XMLHttpRequest();
    req.open("POST","../../bussiness_layer/admin/update_user.php");
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    req.send("id=" + id + "&col=" + col + "&new_val=" + new_val);
}

function handle_remove_button(id)
{
    if (confirm('Ste si istí, že chcete ostrániť užívaťeľa s id = ' + id + '?')) {
        remove_user(id);
        refresh_tables_after(100);
    }
}


