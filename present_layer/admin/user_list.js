/// Variables used for ordering table by column
ord_col = 0;
ord_dir_up = true;


setInterval(function () {
    refresh_tables();
}, 30000); // TODO CHANGE!

/***
 * Initial page preparation
 */
window.addEventListener('load', function(){
    refresh_tables();
    fix_order();
});    

/***
 * Make sure that table order buttons are prepared correctly
 */
function fix_order()
{
    res = document.getElementsByClassName('order-but');
    if(res == null)
        return;

    for (let i = 0; i < res.length; i++)
    {
        res[i].classList.remove("fa-chevron-up");
        res[i].classList.remove("fa-chevron-down");
        if(i == ord_col)
        {
            if(ord_dir_up)
                res[i].classList.add("fa-chevron-up");
            else
                res[i].classList.add("fa-chevron-down");
        }
    }
}

/***
 * Handle the change of table ordering
 */
function order_change(col)
{
    if(ord_col != col)
    {
        // Order by different column
        ord_col = col;
        ord_dir_up = true;
    } else
    {
        // Reverse order by same column
        ord_dir_up = !ord_dir_up;
    }
    fix_order();
    refresh_tables();
}

/***
 * Fill the user table with data
 */
function fill_usr_tab(response)
{
    $('#tab-of-users').html(response);
}

/***
 * Refresh all tables
 */
function refresh_tables()
{
    refresh_usr_tab();
}

/***
 * Refresh all tables after 'time' millisecons
 */
function refresh_tables_after(time)
{
    setTimeout(refresh_tables,time);
}

/***
 * Refresh the table of users
 */
function refresh_usr_tab()
{
    $.ajax({
        url:'get_user_table_rows.php',
        data: jQuery.param({ col: ord_col, asc : ord_dir_up?1:0}),
        type: 'GET',
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
        success: function(response){
            fill_usr_tab(response);
        }
    });
}

/***
 * Handle a key press in a table input field
 */
function field_change(event,id,col)
{
    if (event.keyCode == 13) { // Enter pressed
        event.preventDefault();
        elt = document.getElementById(id + "_" + col);
        if(elt != null)
        {
            update_user(id,col,elt.innerHTML);
            refresh_tables_after(20);
            refresh_tables_after(200);
            refresh_tables_after(1000);
    }

    }
}

/***
 * Handle change in role selector
 */
function select_change(id)
{
    elt = document.getElementById("role_" + id);
    if(elt != null)
    {
        update_user(id,"role",elt.value);
        refresh_tables_after(20);
        refresh_tables_after(200);
        refresh_tables_after(1000);
    }
}

