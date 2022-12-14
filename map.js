/***
 * @author: xsikul@stud.fit.vutbr.cz, Jakub Sikula
 * This file serves as main aplication backend.
 * It contains all the operational and action elements
 */

//Global variable containing up-to-date data obtained from the database
let all_tickets;
//Global variable containing old data obtained from the database
let all_tickets_old
//Global variable representing state of open/closed formular for creating new ticket
let form = false;
//Global variable containing position of new ticket
let form_pos;
//Global variable representing if all tickets are shown
let all = true;
//Global variable representing if registration is shown
let reg = false;
//Global variable representing if worker requests are shown
let worker = false;
//Global variable representing if worker is logged in
let worker_logged = false;
//Global variable representing new marker
let newMarker;
//Global variable representing if image is zoomed;
let zoom = false;

let markers = [];

let infoG;

let map;

function get_category(category){
    let marker_category;
    if (category === "Lampa nesvieti"){
        marker_category = "lamp" ;
    }else if(category === "Odpadky"){
        marker_category = "trash";
    }else if(category === "Poškodený chodník"){
        marker_category = "walk";
    }
    else if(category === "Poškodená cesta"){
        marker_category = "road";
    }else if(category === "Spadnutý strom"){
        marker_category = "tree";
    }
    else if(category === "Problém s kanalizáciou"){
        marker_category = "canal";
    }
    else if(category === "Vrak auta"){
        marker_category = "car";
    }
    else if(category === "Iné"){
        marker_category = "other";
    }
    return marker_category
}

function get_color(status){
    let color;
    if (status === "Zaevidovaný"){
        color = "red";
    }else if (status === "Pracujeme na tom"){
        color = "orange";
    }else {
        color = "green";
    }
    return color;
}

//Function which generate html in case of registration is shown
function register_gen(){
    reg = true;
    document.getElementById("sidebar").innerHTML+= ''+
    '<button id="backRegister" onclick="back_login_gen()">'+
        '<i class="fa-solid fa-arrow-left fa-2xl"></i>'+
    '</button>'+
    '';
    const sidebar = document.getElementById("sidebar-ul");
    sidebar.style.top="10vh";
    sidebar.style.transform="unset";
    sidebar.innerHTML = ''+
    '<div style = "overflow-y:auto; max-height:70vh">'+
    '<form id="register" action="javascript:submit_reg()">'+
        '<label for="f_name">Krstné meno:</label>'+
        '<input type="text" name="f_name" id="f_name">'+
        '<label for="l_name">Priezvisko:</label>'+
        '<input type="text" name="l_name" id="l_name">'+
        '<label for="email"> E-mail: </label>'+
        '<input type="text" name="email" id="email" placeholder="E-mail" required>'+
        '<label for="password"> Heslo: </label>'+
        '<input type="password" name="password" id="password" placeholder="Heslo" required>'+
        '<label for="phone">Telefónne číslo:</label>'+
        '<input type="tel" name="phone" id="phone">'+
        '<input type="hidden" id="role" value="0">'+
        '<input type="submit" id="loginButton" value="Registrovať">'+
    '</form>'+
    '</div>'+
    '';
    document.getElementById("f_name").focus();
}

//Function which generate html in case button back from login is pressed
function back_login_gen(){
    reg = false;
    document.getElementById("sidebar").innerHTML = '<ul id="sidebar-ul"></ul>';
    const sidebar = document.getElementById("sidebar-ul");
    sidebar.innerHTML = ''+
    '<a onclick="login_gen()"><li>Prihlásiť</li></a>'+
    '<a onclick="register_gen()"><li>Registrovať</li></a>';
}

//Function which generate html in case of login is shown
function login_gen(){
    document.getElementById("sidebar").innerHTML+= ''+
    '<button id="backLogin" onclick="back_login_gen()">'+
        '<i class="fa-solid fa-arrow-left fa-2xl"></i>'+
    '</button>'+
    '';
    const sidebar = document.getElementById("sidebar-ul");
    sidebar.innerHTML = ''+
    '<form action="javascript:check_login()" method="post">'+
        '<label for="email"> E-mail: </label>'+
        '<input type="text" name="email" id="email" placeholder="E-mail">'+
        '<label for="password"> Heslo: </label>'+
        '<input type="password" name="password" id="password" placeholder="Heslo">'+
        '<input type="submit" id="loginButton" value="Prihlasiť">'+
    '</form>'+
    '<div id=wrongLogin></div>'+
    '';
    document.getElementById("email").focus();
    
}

//Function which checks login data and changes output message based on result
function check_login(){
    const email = document.getElementById('email');
    const password = document.getElementById('password');
    const out = document.getElementById("wrongLogin")
    if (email.value === ""){
        alert("Zadaj Email!");
    }else if(password.value === ""){
        alert("Zadaj Heslo!");
    }else {
        $.ajax({
            type:"POST",
            url:"./bussiness_layer/authentication/check_login.php",
            data:{email:email.value,password:password.value},
            success: function(res){
                if(res === '0'){
                    sessionStorage.setItem("email",email.value);
                    window.location.replace("index.php");
                }else if (res === '1'){
                    out.innerHTML = "Neznámy email!";
                }else if (res === '2'){
                    out.innerHTML = "Zlé heslo!";
                }else {
                    out.innerHTML = "Something went wrong!";
                }
            }
        });
    }
}

//Function which changes atributes and animation of formular when going up
function formular_up(){
    const formular = document.getElementById("formular");
    if (formular.style.bottom === "-40%" && form === true) {
        formular.style.animation = "formular_up 0.7s";
        formular.style.bottom = "0%";
    }
}
//Function which changes atributes and animation of formular when going down
function formular_down(){
    const formular = document.getElementById("formular");
    if (formular.style.bottom === "0%" && form === true) {
        formular.style.animation = "formular_down 0.7s";
        formular.style.bottom = "-40%";
    }
}

//Function which changes atributes,animation of formular and "removes" new marker when closing
function formular_close(marker){
    const formular = document.getElementById("formular");
    if (formular.style.bottom === "0%" && form === true) {
        formular.style.animation = "formular_close 0.7s";
        formular.style.bottom = "-60%";
        marker.setPosition({lat: 0, lng: 0});
        form = false
    }else if (formular.style.bottom === "-40%" && form === true){
        formular.style.animation = "formular_exit 0.7s";
        formular.style.bottom = "-60%";
        marker.setPosition({lat: 0, lng: 0});
        form = false
    }
}

//Function which changes atributes,animation of formular when opening
function formular_open(){
    closeBurger();
    const formular = document.getElementById("formular");
    if (formular.style.bottom !== "0%" && form === false) {
        formular.style.animation = "formular_open 0.7s";
        formular.style.bottom = "0%";
        form = true;
    }else {
        formular_up();
    }
}

//Function which changes atributes and animation of search bar when search icon is pressed
function SearchIcon(){
    const x = document.getElementById("ToSearch");
    if(x.style.width === "40ch"){
        x.style.animation = "search_in 0.7s";
        x.style.width = "0ch";
        x.blur();
    }else {
        x.focus();
        x.style.animation = "search_out 0.7s";
        x.style.width = "40ch";
    }
    
}

//Function which changes atributes and animation of hint bar on closing
function closeHitBar(){
    const x = document.getElementById("hintBar");
    const hand = document.getElementById("hand");
    if (x.style.bottom === "0%") {
        x.style.animation = "hintbar_down 0.7s";
        x.style.bottom = "-40%";
        hand.style.animation = "hand_out 0.7s";
        hand.style.display = "none";
    }
}

//Function which changes atributes and animation of hint Bar when opening
function hintBar(){
    const x = document.getElementById("hintBar");
    const y = document.getElementById("sidebar");
    const hand = document.getElementById("hand");
    if(y.style.left === "0%"){
        closeBurger();
    }
    if (x.style.bottom === "0%") {
        x.style.animation = "hintbar_down 0.7s";
        x.style.bottom = "-40%";
        hand.style.animation = "hand_out 0.7s";
        hand.style.display = "none";
    } else {    
        x.style.animation = "hintbar_up 0.7s";
        x.style.bottom = "0%";
        hand.style.animation = "hand_in 0.7s";
        hand.style.display = "block";  
      }
}

//Function which changes atributes and animation of sidebar when closeing
function closeBurger(){
    const x = document.getElementById("sidebar");
    if (x.style.left === "0%"){
        if (document.documentElement.clientWidth <= 550){
            x.style.animation = "sidebar_down100 0.7s";
            x.style.left = "-100%";
        }else {
            x.style.animation = "sidebar_down 0.7s";
            x.style.left = "-50%";
        }
        
    }
}

//Function which changes atributes and animation of sidebar when opening
function myBurger() {
    const x = document.getElementById("sidebar");
    closeHitBar();
    formular_down();
    if (document.documentElement.clientWidth <= 550){
        if (x.style.left === "0%") {
            x.style.animation = "sidebar_down100 0.7s";
            x.style.left = "-100%";
        } else {    
            x.style.animation = "sidebar100 0.7s";
            x.style.left = "0%";  
        }
        return;
    }

    if (x.style.left === "0%") {
        x.style.animation = "sidebar_down 0.7s";
        x.style.left = "-50%";
    } else {    
        x.style.animation = "sidebar 0.7s";
        x.style.left = "0%";  
    }
}

function setMapOnAll(map) {
    for (let i = 0; i < markers.length; i++) {
      markers[i].setMap(map);
    }
}

//Function which makes new marker, info window and click event for every ticket in global all_tickets.
//It sets content acording to if user is logged in, if worker is logged in or if aplication
//is showing all tickets.
//Takes: map handle and info Window handle
function makeMarkers(map,infoWindow){
    setMapOnAll(null);
    markers = [];
    for(let i=0;i<all_tickets.length;i++){
        let content =
        '<b>Kategória:</b> '+all_tickets[i]["category"] + '<br>' +
        '<b>Status:</b> '+all_tickets[i]["state"]+'<br>'+
        '<b>Správa:</b> '+all_tickets[i]["msg"]+'<br>'+
        '<b>Posledná úprava:</b> '+all_tickets[i]["time_modified"]+'<br>'+ 
        '<img class=infoImg id= "infoImg" onClick="ZoomImg()" src="'+all_tickets[i]["img"].substring(1)+'" alt="img">';
        if (all_tickets[i]["state"]==="Zaevidovaný" && all === false){
            content += '<button id="removeTicketButton" onclick="handle_remove_button('+ all_tickets[i]["id"] +')">Odstrániť tiket</button>';
        }
        if (worker === true) {
            content = 
            '<b>Kategória:</b> '+all_tickets[i]["category"] + '<br>' +
            '<b>Status:</b> '+all_tickets[i]["state_req"]+'<br>'+
            '<b>Popis:</b> '+all_tickets[i]["description"]+'<br>'+
            '<b>Očakávaná oprava do:</b> '+all_tickets[i]["expected_date"]+'<br>'+
            '<b>Posledná úprava:</b> '+all_tickets[i]["time_modified"]+'<br>'+
            '<img class="infoImg" id= "infoImg" onClick="ZoomImg()" src="'+all_tickets[i]["img"].substring(1)+'" alt="img">'+
            '<form action="present_layer/worker_requests.php" method="GET"><input type=hidden id="requestID" name=requestID value='+all_tickets[i]["id"]+'><input type="submit" id=redirectButton value="Prejsť na žiadosť"></form>';
        }


        let pos =  new google.maps.LatLng(all_tickets[i]["lat"],all_tickets[i]["lng"]);
        const marker = new google.maps.Marker({
            position: pos,
            title: all_tickets[i]["category"],
            optimized:false,
        });
        let icon;
        let status = all_tickets[i]["state"];
        if (status === undefined){
            status = all_tickets[i]["state_req"];
        }
        
        
        icon = {
            url: "./img/markers/"+get_category(all_tickets[i]["category"])+"_"+get_color(status)+".png", // url
            scaledSize: new google.maps.Size(50, 50), // scaled size
            origin: new google.maps.Point(0,0), // origin
            anchor: new google.maps.Point(25, 50) // anchor
        };
        marker.setIcon(icon);
        marker.setMap(map);

        marker.addListener("click",() => {
            infoWindow.close();
            infoWindow.setContent(content);
            infoWindow.open(marker.getMap(), marker);
            map.setCenter(pos);
        })
    }

    
}

//Set of commands which are called in 1 second interval.
//It is used to get up-to-date data from database
setInterval(function () {
    if (all === true){
        $.ajax({
            url:'./bussiness_layer/all_tickets_map_data.php',
            success: function(response){
                all_tickets = JSON.parse(response); 
            }
        });
    }else if(worker === true){
        $.ajax({
            url:'./bussiness_layer/worker_requests_map_data.php',
            success: function(response){
                all_tickets = JSON.parse(response);
            }
        });
    }else {
        $.ajax({
            url:'./bussiness_layer/my_tickets_map_data.php',
            success: function(response){
                all_tickets = JSON.parse(response);
            }
        });
    }
  
}, 1000);

//Function which inicialze whole map with geolocation function and other events
function initMap() {
    map = new google.maps.Map(document.getElementById("map"), {
        zoom: 15,
        /*Location of VUT FIT*/
        center: { lat: 49.2269, lng: 16.59689 },
        disableDefaultUI: true,
        disableDoubleClickZoom: true,
        panControl: false,
        mapId:'c34d843644105241',
    });
    //Try to geolocate user position
    if (navigator.geolocation) {
         navigator.geolocation.getCurrentPosition(
            (position) => {
                const pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude,
                };
                form_pos = pos;
                map.setCenter(pos);
            },
            () => {
                handleLocationError(true, infoWindow, map.getCenter());
            }
        );
    } else {
        //Browser doesntt support Geolocation
        handleLocationError(false, infoWindow, map.getCenter());
    }

    const search = document.getElementById("SearchIcon");
    //Event in case search icon is clicked
    //If something is in search bar it will try to find and center around that location
    search.addEventListener("click",() => {
        const searchBar = document.getElementById("ToSearch");
        if(searchBar.value !== ""){
            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({'address': searchBar.value },function(results,status){
                let lat = results[0].geometry.location.lat();
                let lng = results[0].geometry.location.lng();
                const pos = {
                    lat: lat,
                    lng: lng,
                };
                map.setCenter(pos);
            });
        }
        searchBar.value = "";
    });

    const searchForm = document.getElementById("searchForm");
    searchForm.addEventListener("keypress",(event) => {
        if (event.key === "Enter"){
            event.preventDefault();
            const searchBar = document.getElementById("ToSearch");
            if(searchBar.value !== ""){
                var geocoder = new google.maps.Geocoder();
                geocoder.geocode({'address': searchBar.value },function(results,status){
                    let lat = results[0].geometry.location.lat();
                    let lng = results[0].geometry.location.lng();
                    const pos = {
                        lat: lat,
                        lng: lng,
                    };
                    map.setCenter(pos);
                });
        }
        searchBar.value = "";
        }

    });

    const locationButton = document.getElementById("myPosition");
    //Event in case locationButton is clicked
    //Map will be centered around user location, if its avalible
    locationButton.addEventListener("click", () => {
        // Try HTML5 geolocation.
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(
            (position) => {
              const pos = {
                lat: position.coords.latitude,
                lng: position.coords.longitude,
              };
              map.setCenter(pos);
            },
            () => {
              handleLocationError(true, infoWindow, map.getCenter());
            }
          );
        } else {
          //Browser doesnt support Geolocation
          handleLocationError(false, infoWindow, map.getCenter());
        }
    });
    
    const infoWindow = new google.maps.InfoWindow();
    infoG = infoWindow;
    //Set of commands which are used to initialze data.
    //They get about tickets or requests form database based on who is logged in
    //or if all ticktes are shown
    if(all === true){
        $.ajax({
            url:'./bussiness_layer/all_tickets_map_data.php',
            success: function(response){
                all_tickets = JSON.parse(response);
                makeMarkers(map,infoWindow);
            }
        });
    }else if(worker === true){
        $.ajax({
            url:'./bussiness_layer/worker_requests_map_data.php',
            success: function(response){
                all_tickets = JSON.parse(response);
                makeMarkers(map,infoWindow);
            }
        });
    }else {
        $.ajax({
            url:'./bussiness_layer/my_tickets_map_data.php',
            success: function(response){
                all_tickets = JSON.parse(response);
                makeMarkers(map,infoWindow);
            }
        });
    }
    

    

    const marker = new google.maps.Marker({
        map: map,
    });
    //Event in case of dubble click on map.
    //used to create new ticket 
    map.addListener("dblclick", (click) => {
        if (worker === false && worker_logged === false){
            infoWindow.close();
            closeBurger();
            closeHitBar();
            formular_open();
            form_pos = click.latLng;
            map.setCenter(click.latLng);
            map.setZoom(17.5);
            map.panBy(0, 240);
            marker.setPosition(click.latLng);
            newMarker = marker;
            let json = click.latLng.toJSON();
            document.getElementById("lng").setAttribute('value',json["lng"].toFixed(6));
            document.getElementById("lat").setAttribute('value',json["lat"].toFixed(6));
        }
        
    });

    //Event in case of click on map
    //used to close all pop-ups
    map.addListener("click", (click) => {
        infoWindow.close();
        closeBurger();
        closeHitBar();
        formular_down();
    });

    const formularCloseButton = document.getElementById("closeFormular");
    //Event in case close formular button is presed
    formularCloseButton.addEventListener("click", () => {formular_close(marker);});

    //Interval in which are compared new incoming data whith old shown data
    //in case they are not the same markers on map will be rewriten
    setInterval(function () {
        if(!_.isEqual(all_tickets_old, all_tickets)){
            makeMarkers(map,infoWindow);
            all_tickets_old = all_tickets;
        }

    }, 1000);

    //Event in case side bar have button to create new ticket.
    //Opens new formular on position of map center
    const sideBarNewTicket = document.getElementById("sidebarNewTicket");
    if (sideBarNewTicket !== null) {
        sideBarNewTicket.addEventListener("click",() => {
            infoWindow.close();
            closeBurger();
            closeHitBar();
            formular_open();
            form_pos = map.getCenter();
            map.setCenter(form_pos);
            map.setZoom(17.5);
            map.panBy(0, 240);
            marker.setPosition(form_pos);
            let json = form_pos.toJSON();
            document.getElementById("lng").setAttribute('value',json["lng"].toFixed(6));
            document.getElementById("lat").setAttribute('value',json["lat"].toFixed(6));
        });
    }
    
    
}

//Function which is called when myTickets buttons are pressed
//It changes showing of markers to showing markers submited by user
function myTickets(){
    const burgerButton = document.getElementById("tik");
    let button = document.getElementById("myTickets");
    if (worker === true) {
        button.style.zIndex = 0;
        button = document.getElementById("workerTickets");
    }
    const replace = document.getElementById("allTickets");
    burgerButton.innerHTML = '<li onclick="allTickets()">Všetky tikety</li>';
    closeHitBar();
    formular_down();
    closeBurger();
    all = false;
    button.style.zIndex = 0;
    replace.style.zIndex = 7;
    initMap();
}

//Function which is called when worker requests button is clicked
//It changes showing of markers to showing markers of worker
function workerTickets(){
    worker = true;
    myTickets();
}

//Function which is called when all tickets button is clicked
//It changes showing of markers other to showing all tickets markers
function allTickets(){
    const burgerButton = document.getElementById("tik");
    const button = document.getElementById("allTickets");
    let replace = document.getElementById("myTickets");
    if (worker === true) {
        replace = document.getElementById("workerTickets");
    }
    if (worker === true){
        burgerButton.innerHTML = '<li onclick="workerTickets()">Zobraziť žiadosti</li>';
    }else {
        burgerButton.innerHTML = '<li onclick="myTickets()">Moje tikety</li>';
    }
    
    closeHitBar();
    formular_down();
    closeBurger();
    worker = false;
    all = true;
    button.style.zIndex = 0;
    replace.style.zIndex = 7;
    initMap();
}

//function which is called in case geolocation error
//It handles this problem :)
function handleLocationError(browserHasGeolocation, infoWindow, pos) {
    infoWindow.setPosition(pos);
    infoWindow.setContent(
      browserHasGeolocation
        ? "Error: The Geolocation service failed."
        : "Error: Your browser doesn't support geolocation."
    );
    infoWindow.open(map);
}

//Function which change state if worker is logged
function logged_worker(){
    worker_logged = true;
}
//Function which send data about new ticket
function createTicket(){
    const form = document.querySelector('#form');
    const formData = new FormData(form);

    $.ajax({
        type:"POST",
        url:"./bussiness_layer/create_ticket.php",
        data: formData,
        processData: false,
        contentType: false,
        success: function(res){
           alert(res);
           formular_close(newMarker);
        }
    })
}

function ZoomImg(){
    const image = document.getElementById("infoImg");
    const bigSpot = document.getElementById("bigSpot");
    if (!zoom){
        zoom = true;
        bigSpot.innerHTML = "<img id='bigImage' onClick='ZoomImg()' src='"+image.src+"'>"
        bigSpot.style.zIndex = "40";

    }else {
        zoom = false;
        bigSpot.style.zIndex = "-10";
    }
}

function submit_reg() {
    const form = document.querySelector('#register');
    const formData = new FormData(form);
    $.ajax({
        type:"POST",
        url:"./bussiness_layer/authentication/check_register.php",
        data: formData,
        processData: false,
        contentType: false,
        success: function(res){
            alert(res);
            if (res==="Uspešne zaregistrovaný"){
                $.ajax({
                    type:"POST",
                    url:"./bussiness_layer/authentication/check_login.php",
                    data:formData,
                    processData: false,
                    contentType: false,
                    success: function(result){
                        if(result === '0'){
                            sessionStorage.setItem("email",email.value);
                            window.location.replace("index.php");
                        }else if (result === '1'){
                            alert("Neznámy email!");
                        }else if (result === '2'){
                            alert("Zlé heslo!");
                        }else {
                            alert("Something went wrong!");
                        }
                    }
                });
            }
        }
    })

}
window.initMap = initMap;