/**
 * @author xtverd01
 * Script for updating worker table of requests.
 */
/// Variables used for ordering table by column
ord_col = 0;
ord_dir_up = true;
filter = "";
choice = '%';
field_value = "";


setInterval(function () {
    refresh_tables();
}, 30000000); // TODO CHANGE!

/***
 * Initial page preparation
 */
window.addEventListener('load', function(){
    document.getElementById("filter-input").value = field_value;
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
        res[i].classList.remove("fa-chevron-down");
        res[i].classList.add("fa-chevron-up");
        res[i].classList.remove("arrow-visible");
        res[i].classList.add("arrow-hidden");
        res[i].classList.remove("fa-xl");
        res[i].classList.add("fa-xs");


        if(i == ord_col)
        {
            res[i].classList.add("arrow-visible");
            res[i].classList.remove("arrow-hidden");

            res[i].classList.remove("fa-xs");
            res[i].classList.add("fa-xl");
            
            if(ord_dir_up)
                res[i].classList.add("fa-chevron-up");
            else
                res[i].classList.add("fa-chevron-down");
        }
    }
}

/***
 * React to the change of choice
 */
function choice_change()
{
    choice_elem = document.getElementById("choice-select");

    choice = choice_elem.value;

    refresh_tables();
}

/***
 * React to the change of table content filter
 */
function filter_change()
{
    filt = document.getElementById("filter-input");
    if(filt == null)
        return;

    filter = filt.value;

    refresh_tables();
}
function filter_reset()
{
    elt = document.getElementById("filter-input");
    if(elt != null)
        elt.value = "";
    
    filter = "";
    refresh_tables();
}

function filter_init($input)
{
    filter = $input;
    field_value = $input;
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
        url:'get_requests_table_rows.php',
        data: jQuery.param({ col: ord_col, asc : ord_dir_up?1:0, filt : filter, choi : choice}),
        type: 'GET',
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
        success: function(response){
            fill_usr_tab(response);
        }
    });
}
