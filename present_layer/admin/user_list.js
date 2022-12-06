setInterval(function () {
    refresh_tables();
}, 3000);

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
