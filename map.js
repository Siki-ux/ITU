let all_tickets;
let form = false;
let form_pos;
let all = true;

function formular_up(){
    const formular = document.getElementById("formular");
    if (formular.style.bottom === "-40%" && form === true) {
        formular.style.animation = "formular_up 0.7s";
        formular.style.bottom = "0%";
    }
}

function formular_down(){
    const formular = document.getElementById("formular");
    if (formular.style.bottom === "0%" && form === true) {
        formular.style.animation = "formular_down 0.7s";
        formular.style.bottom = "-40%";
    }
}

function formular_change_position(){
    formular_up();
}

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

function formular_open(){
    const formular = document.getElementById("formular");
    if (formular.style.bottom !== "0%" && form === false) {
        formular.style.animation = "formular_open 0.7s";
        formular.style.bottom = "0%";
        form = true;
    }else {
        formular_change_position();
    }
}

function SearchIcon(){
    const x = document.getElementById("ToSearch");
    if(x.style.width === "40ch"){
        x.style.animation = "search_in 0.7s";
        x.style.width = "0ch";
    }else {
        x.style.animation = "search_out 0.7s";
        x.style.width = "40ch";
    }
}

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

function closeBurger(){
    const x = document.getElementById("sidebar");
    if (x.style.left === "0%"){
        x.style.animation = "sidebar_down 0.7s";
        x.style.left = "-50%";
    }
}

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

function makeMarkers(map,infoWindow){
    for(let i=0;i<all_tickets.length;i++){
        const content =
        '<b>Kategória:</b> '+all_tickets[i]["category"] + '<br>' +
        '<b>Status:</b> '+all_tickets[i]["state"]+'<br>'+
        '<b>Správa:</b> '+all_tickets[i]["msg"]+'<br>'+
        '<b>Posledná úprava:</b> '+all_tickets[i]["time_modified"]+'<br>'+ 
        '<img class=infoImg src="'+all_tickets[i]["img"].substring(1)+'" alt="img">';
        const marker = new google.maps.Marker({
            position: new google.maps.LatLng(all_tickets[i]["lat"],all_tickets[i]["lng"]),
            title: all_tickets[i]["category"],
            optimized:false,
        });
        marker.setMap(map);

        marker.addListener("click",() => {
            infoWindow.close();
            infoWindow.setContent(content);
            infoWindow.open(marker.getMap(), marker);
            map.setCenter(new google.maps.LatLng(all_tickets[i]["lat"],all_tickets[i]["lng"]));
        })
    }

    
}

setInterval(function () {
    if (all === true){
        $.ajax({
            url:'./bussiness_layer/all_tickets_map_data.php',
            success: function(response){
                all_tickets = JSON.parse(response); 
            }
        });
    }else {

    }
  
}, 1000);

function initMap() {
    const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 15,
        center: { lat: 49.2269, lng: 16.59689 },
        disableDefaultUI: true,
        disableDoubleClickZoom: true,
        panControl: false,
    });
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
        // Browser doesn't support Geolocation
        handleLocationError(false, infoWindow, map.getCenter());
    }
    const search = document.getElementById("SearchIcon");

    search.addEventListener("click",() => {
        const x = document.getElementById("ToSearch");
        if(x.value !== ""){
            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({'address': x.value },function(results,status){
                let lat = results[0].geometry.location.lat();
                let lng = results[0].geometry.location.lng();
                const pos = {
                    lat: lat,
                    lng: lng,
                };
                map.setCenter(pos);
            });
        }
        x.value = "";
    });

    const locationButton = document.getElementById("myPosition");

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
          // Browser doesn't support Geolocation
          handleLocationError(false, infoWindow, map.getCenter());
        }
      });
    
    const infoWindow = new google.maps.InfoWindow();
    
    if(all === true){
        $.ajax({
            url:'./bussiness_layer/all_tickets_map_data.php',
            success: function(response){
                all_tickets = JSON.parse(response);
                makeMarkers(map,infoWindow);
            }
        });
    }else {

    }
    

    

    const marker = new google.maps.Marker({
        map: map,
    });

    map.addListener("dblclick", (click) => {
        infoWindow.close();
        closeBurger();
        closeHitBar();
        formular_open();
        form_pos = click.latLng;
        map.setCenter(click.latLng);
        map.setZoom(17.5);
        map.panBy(0, 240);
        marker.setPosition(click.latLng);
        let json = click.latLng.toJSON();
        document.getElementById("lng").setAttribute('value',json["lng"].toFixed(6));
        document.getElementById("lat").setAttribute('value',json["lat"].toFixed(6));
    });

    map.addListener("click", (click) => {
        infoWindow.close();
        closeBurger();
        closeHitBar();
        formular_down();
    });

    const formularCloseButton = document.getElementById("closeFormular");

    formularCloseButton.addEventListener("click", () => {formular_close(marker);});

    setInterval(function () {
        if(all_tickets !== all_tickets){
            makeMarkers(map,infoWindow);
        }

    }, 1500);

    
    
}

function myTickets(){
    const button = document.getElementById("myTickets");
    const replace = document.getElementById("allTickets");
    closeHitBar();
    formular_down();
    closeBurger();
    all = false;
    button.style.zIndex = 0;
    replace.style.zIndex = 7;
    initMap()
}

function allTickets(){
    const button = document.getElementById("allTickets");
    const replace = document.getElementById("myTickets");
    closeHitBar();
    formular_down();
    closeBurger();
    all = true;
    button.style.zIndex = 0;
    replace.style.zIndex = 7;
    initMap()
}


function handleLocationError(browserHasGeolocation, infoWindow, pos) {
    infoWindow.setPosition(pos);
    infoWindow.setContent(
      browserHasGeolocation
        ? "Error: The Geolocation service failed."
        : "Error: Your browser doesn't support geolocation."
    );
    infoWindow.open(map);
}

window.initMap = initMap;