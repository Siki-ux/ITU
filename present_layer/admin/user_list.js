/***
 * @author Martin Pavella xpavel39
 */


/// Variables used for formatting table
ord_col = 0;
ord_dir_up = true;
filter = "";
role = -1; // -1   all

/***
 * Initial page preparation
 */
window.addEventListener('load', function(){
    refresh_tables();
    fix_order();
    setup_click_away();
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

    document.addEventListener('keydown',(event) => {
        if(event.key == "Escape")
            document.activeElement.blur();
    });
       
}

/***
 * React to the change of role-select
 */
function role_choice_change()
{
    choice = document.getElementById("role-select");
    if(choice == null)
        return;

    role = choice.value;
    refresh_tables();
}

/***
 * Reset everything to default view
 */
function reset()
{
    reset_role_filter();
    reset_order();
    filter_reset();
}


/***
 * Reset role filter select
 */
function reset_role_filter()
{
    role = -1;

    choice = document.getElementById("role-select");
    if(choice == null)
        return;
    choice.value = -1;

}

/***
 * Reset order to first columnm, ascending.
 */
function reset_order()
{
    ord_col = 0;
    ord_dir_up = true;
    fix_order();
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
        res[i].classList.remove("fa-lg");
        res[i].classList.add("fa-xs");


        if(i == ord_col)
        {
            res[i].classList.add("arrow-visible");
            res[i].classList.remove("arrow-hidden");

            res[i].classList.remove("fa-xs");
            res[i].classList.add("fa-lg");
            
            if(ord_dir_up)
                res[i].classList.add("fa-chevron-up");
            else
                res[i].classList.add("fa-chevron-down");
        }
    }
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

/**
 * Reset the table filter and refresh table
 */
function filter_reset()
{
    elt = document.getElementById("filter-input");
    if(elt != null)
        elt.value = "";
    
    filter = "";
    refresh_tables();
}

/***
 * Refresh the table of users
 */
function refresh_usr_tab()
{
    $.ajax({
        url:'get_user_table_rows.php',
        data: jQuery.param({ col: ord_col, asc : ord_dir_up?1:0, filt : filter, role : role}),
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
        new_val = elt.innerHTML.trim(); // Remove leading and ending spaces that sometimes appeared
        update_user(id,col,new_val);
        if(reload)
        {
            refresh_tables_after(20);
            refresh_tables_after(100);
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
