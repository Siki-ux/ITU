
<head><style>
h1 {text-align: center;}
form {text-align: center;}
</style>
<title>Chytni závadu</title>
</head>

<?php
if (session_id() == "")
    session_start();

include_once("./bussiness_layer/checks.php");
include_once("./bussiness_layer/admin/check_admin.php");

if( is_admin() )
    header("Location: ./admin.php");
if( is_manager() )
    header("Location: ./manager.php");
if( is_worker() )
    header("Location: ./worker.php");
?>

<html>

    <h1>Chytni závadu!</h1>

    <?php
    if( ! is_logged_in()) // Allow new user to log in
    {
        echo '
        <form action="present_layer/authentication/login.php" class="inline">
            <button>Prihlásenie</button>
        </form>

        <br>

        <form action="present_layer/authentication/register.php" class="inline">
            <button>Regristrácia</button>
        </form>

        <br>';
    } else
    {
        // Render UI for logged in user
        echo("Logged in as: ".$_SESSION['email']);
        echo '
        <form action="present_layer/all_tickets.php" class="inline">
            <button>Vsetky tikety</button>
        </form>

        <br>

        <form action="present_layer/all_tickets_map.php" class="inline">
            <button>Mapa</button>
        </form>

        <br>

        <form action="present_layer/new_ticket.php" class="inline">
            <button>Nový tiket</button>
        </form>

        <br>


        <form action="present_layer/my_tickets.php" class="inline">
            <button>Moje tikety</button>
        </form>
        <br>
        <form action="present_layer/authentication/logout.php" class="inline">
            <button>Odhlásiť</button>
        </form>
        
        <br>';
    }

    ?>
</html>

