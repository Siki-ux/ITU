/**
 * @author xtverd01
 * Script for updating manager table of requests.
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
 * Prepare the document to hide menu contend when user clicks away
 */
 function setup_click_away(){
    document.addEventListener('click', function handleClickOutsideBox(event) {
        menu = document.getElementById('dropdown-menu');
        menu_but = document.getElementById("menu-but");
      
        if ( /*(!menu.contains(event.target)) && */(!menu_but.contains(event.target)))
            hide_dropdown_menu(menu);
      });
      
}

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
    $('#tab-of-tickets').html(response);
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
        url:'get_tickets_table_rows.php',
        data: jQuery.param({ col: ord_col, asc : ord_dir_up?1:0, filt : filter, choi : choice}),
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
 function field_change(event,id,col,reload=true)
 {
     event.preventDefault();
     elt = document.getElementById(id + "_" + col);
     if(elt != null)
     {
         update_ticket(id,col,elt.innerHTML);
         if(reload)
         {
             refresh_tables_after(20);
             refresh_tables_after(100);
         }
     }
 }
 
 /***
  * Handle change in status selector
  */
 function select_change(id)
 {
     elt = document.getElementById("role_" + id);
     if(elt != null)
     {
         update_ticket(id,"state_from_manager",elt.value);
         refresh_tables_after(20);
         refresh_tables_after(100);
     }
 }

 menu_hidden = true;
 function toggle_dropdown_menu()
 {
     menu = document.getElementById("dropdown-menu");
     if(menu == null)
         return;
 
     if(menu_hidden)
         show_dropdown_menu(menu);
     else
         hide_dropdown_menu(menu);
 }
 
 function show_dropdown_menu(menu)
 {
     menu.classList.remove("content-hidden");
     menu_hidden = false;
 }
 function hide_dropdown_menu(menu)
 {
     menu.classList.add("content-hidden");
     menu_hidden = true;
 }
