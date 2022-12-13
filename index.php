<?php
/**
 * @author xsikul@stud.fit.vutbr.cz, Jakub Sikula
 * This file serves as main aplication HTML and PHP with login and registration
 */
chdir('.');
if (session_id() == "")
    session_start();

include_once("./bussiness_layer/checks.php");
include_once("./bussiness_layer/admin/check_admin.php");
include_once('./bussiness_layer/print_categories.php');
include_once("./bussiness_layer/user.php");

?>
<html>
    <head>
        <title>Chytni závadu</title>
        <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>
        <script src="https://kit.fontawesome.com/ea2428928f.js" crossorigin="anonymous"></script>  
        <script type="text/javascript" src="./bussiness_layer/remove_ticket.js"></script>
        <link rel="stylesheet" type="text/css" href="./map.css" />
        <script src="./map.js"></script>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <?php
            //if user or worker is logged in show corresponding buttons
            if(is_logged_in()) {
                echo '
                    <button herf="javascript:void(0);" class="tickets" id="allTickets" onclick="allTickets()"><i class="fa-solid fa-globe fa-2xl"></i></button>
                    <button herf="javascript:void(0);" class="tickets" id="myTickets" onclick="myTickets()"><i class="fa-solid fa-user fa-2xl"></i></button>';
                if (is_worker()){
                    echo '<script>logged_worker()</script>';
                    echo '
                    <button herf="javascript:void(0);" class="tickets" id="workerTickets" onclick="workerTickets()"><i class="fa-solid fa-user fa-2xl"></i></button>
                    ';
                }
                //If user is admin or manager he is redirected to thier site
                if( is_admin() )
                    header("Location: ./admin.php");
                if( is_manager() )
                    header("Location: ./manager.php");       
            }
            
        ?>
        <button href="javascript:void(0);" class="icon" onclick="myBurger()">
            <i class="fa-solid fa-bars fa-2xl"></i>
        </button>
        <?php 
            if (!is_worker()){
                echo ' <button herf="javascript:void(0);" class="reportIcon" onclick="hintBar()">Nahlásiť</button>';
            }
        ?>
        <button herf="javascript:void(0);" class="myPosition" id="myPosition">
            <i class="fa-solid fa-location-crosshairs fa-2xl"></i>
        </button>
        <button herf="javascript:void(0);" class="SearchIcon" id="SearchIcon" onclick="SearchIcon()">
            <i class="fa-solid fa-magnifying-glass fa-2xl"></i>
        </button>
        <div id="sidebar">
            <h2>Chytni závadu!</h2>
            <?php 
                if (is_logged_in() || is_worker()){
                    echo '<h3>Prihlásený ako:<br><i>'.get_name().'</i></h3>';
                }
            ?>
            <ul id="sidebar-ul">
                <?php
                //if user or worker is logged in show corresponding buttons
                if( ! is_logged_in()){
                    echo '
                    <a onclick="login_gen()"><li>Prihlásiť</li></a>
                    <a onclick="register_gen()"><li>Registrovať</li></a>';
                }else if(is_worker()){
                    echo '
                    <a id="tik"><li  onclick="workerTickets()">Zobraziť žiadosti</li></a>
                    <a href= "present_layer/worker_requests.php"><li>Opravy</li></a>
                    <a href="present_layer/authentication/logout.php"><li>Odhlásiť</li></a>';
                }else {
                    echo '
                    <a id="tik"><li  onclick="myTickets()">Moje tikety</li></a>
                    <a id="sidebarNewTicket"><li>Nový tiket</li></a>
                    <a href="present_layer/authentication/logout.php"><li>Odhlásiť</li></a>';
                }
                ?>
                
            </ul>
        </div>
        <div id="hintBar"  onclick="closeHitBar()">
            <h2>Dvojklikom na mapu zvoľte miesto problému</h2>
        </div>
        <div id="searchBar">
            <form id= "searchForm" >
                <input type="text" placeholder="Zadaj vyhľadávanú adresu" id="ToSearch">
            </form>
        </div>
        <img src="./img/hands-click-png-icon-5.png" alt="yell at siki cuz something went wrong" class="hand" id="hand" onclick="closeHitBar()">
        <div id="formular" onclick="formular_up()">
            <button id="closeFormular">
                <i class="fa-regular fa-circle-xmark fa-3x"></i>
            </button>
            <form id="form" method="post" action="javascript:createTicket()" enctype="multipart/form-data">
                <input type="hidden" id="lng" name="lng" onKeyDown="return false" readonly required>
                <input type="hidden" id="lat" name="lat" onKeyDown="return false" readonly required>
                <label for="category">Kategória</label>
                <select name="category" id="category">  <?php print_categories(); ?> </select>
                <label for="fileToUpload" id="upload"><i class="fa fa-cloud-upload"></i> Nahraj fotku <i id="vol">(volitelné)</i></label>
                <input type="file" id="fileToUpload" name="fileToUpload">
                <input type = "submit" id="submit" value="Odoslať">
            </form>
        </div>
        <div id="bigSpot"></div>
        <div id="map"></div><script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCJVGL83AulBYsKWzBA0ooSruG4_CVIWqA&callback=initMap"defer></script>
    </body>
</html>

<?php
if( is_admin() )
    header("Location: ./admin.php");
if( is_manager() )
    header("Location: ./manager.php");
?>