<?php
chdir('../');
?>
<html>
    <head>
        <title>Chytni závadu</title>
        <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://kit.fontawesome.com/ea2428928f.js" crossorigin="anonymous"></script>   
        <link rel="stylesheet" type="text/css" href="./map.css" />
        <script src="./map.js"></script>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <button href="javascript:void(0);" class="icon" onclick="myBurger()">
            <i class="fa-solid fa-bars fa-2xl"></i>
        </button>
        <button herf="javascript:void(0);" class="reportIcon" onclick="hintBar()">Nahlásiť</button>
        <button herf="javascript:void(0);" class="myPosition" id="myPosition">
            <i class="fa-solid fa-location-crosshairs fa-2xl"></i>
        </button>
        <button herf="javascript:void(0);" class="SearchIcon" id="SearchIcon" onclick="SearchIcon()">
            <i class="fa-solid fa-magnifying-glass fa-2xl"></i>
        </button>
        <div id="sidebar">
            <h2>Chytni závadu!</h2>
            <ul>
                <li>ahoj</li>
                <li>martin smrdí</li>
            </ul>
        </div>
        <div id="hintBar"  onclick="closeHitBar()">
            <h2>Dvojklikom na mapu zvoľte miesto problému</h2>
        </div>
        <div id="searchBar">

            <form action="">
                <input type="text" placeholder="Zadaj vyhľadávanú adresu" id="ToSearch">
            </form>
        </div>
        <img src="../../img/hands-click-png-icon-5.png" alt="yell at siki cuz something went wrong" class="hand" id="hand" onclick="closeHitBar()">
        <div id="formular">
            <button id="closeFormular">
                <i class="fa-regular fa-circle-xmark fa-3x"></i>
            </button>
        </div>
        <div id="map"></div><script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCJVGL83AulBYsKWzBA0ooSruG4_CVIWqA&callback=initMap"defer></script>
    </body>
</html>