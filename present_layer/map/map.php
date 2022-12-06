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
        <script type="module" src="./map.js"></script>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <a href="javascript:void(0);" class="icon" onclick="myBurger()">penis
                <i class="fa-solid fa-bars fa-2xl"></i>
            </a>
            <h2 class="main">Mapa tiketov</h2>
            <h2 class="misc">BETA</h2>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" id="map"></div><script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCJVGL83AulBYsKWzBA0ooSruG4_CVIWqA&callback=initMap"defer></script>
    </body>
</html>