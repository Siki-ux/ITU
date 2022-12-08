setInterval(function () {
    refresh_tables();
}, 30000); // TODO CHANGE!

window.addEventListener('load', function(){
    refresh_tables();
});    

function fill_usr_tab(response)
{
    $('#tab-of-users').html(response);
}

function refresh_tables()
{
    refresh_usr_tab();
}

function refresh_tables_after(time)
{
    setTimeout(refresh_tables),time;
}

function refresh_usr_tab()
{
    $.ajax({
        url:'get_user_table_rows.php',
        success: function(response){
            fill_usr_tab(response);
        }
    });
}

function usr_tab_but_press()
{
    switch_up_down_icon(document.getElementById("show-usr-tab-but"));
    collapse_usr_tab();
}

usr_but_up = true;

function switch_up_down_icon(elt)
{
    if(usr_but_up)
    {
        elt.classList.remove("fa-chevron-up");
        elt.classList.add("fa-chevron-down");
        usr_but_up = false;
    }
    else
    {
        elt.classList.remove("fa-chevron-down");
        elt.classList.add("fa-chevron-up");
        usr_but_up = true;
    }
}

function collapse_usr_tab()
{
    elt = document.getElementById("usr-tab");
    elt.classList.add("collapsed");
    console.log("A");

}


function field_change(event,id,attr)
{
    if (event.keyCode == 13) { // Enter pressed
        
    }
}

