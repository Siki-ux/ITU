let all_tickets;

function SearchIcon(){
    const x = document.getElementById("ToSearch");
    if(x.style.width === "40ch"){
        if(x.value !== ""){
            console.log(x.value);
            //x.value = "";
        }
        x.style.animation = "search_in 0.7s";
        x.style.width = "0ch";
    }else {
        x.style.animation = "search_out 0.7s";
        x.style.width = "40ch";
    }
}

function closeHitBar(){
    const x = document.getElementById("hintBar");
    if (x.style.bottom === "0%") {
        x.style.animation = "hintbar_down 0.7s";
        x.style.bottom = "-40%";
    }
}

function hintBar(){
    const x = document.getElementById("hintBar");
    const y = document.getElementById("sidebar");
    if(y.style.left === "0%"){
        closeBurger();
    }
    if (x.style.bottom === "0%") {
        x.style.animation = "hintbar_down 0.7s";
        x.style.bottom = "-40%";
    } else {    
        x.style.animation = "hintbar_up 0.7s";
        x.style.bottom = "0%";  
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
    if (x.style.left === "0%") {
        x.style.animation = "sidebar_down 0.7s";
        x.style.left = "-50%";
    } else {    
        x.style.animation = "sidebar 0.7s";
        x.style.left = "0%";  
      }
}

setInterval(function () {
  $.ajax({
        url:'all_tickets_map_data.php',
        success: function(response){
            all_tickets = JSON.parse(response);
            //console.log(JSON.stringify(all_tickets));      
        }
  });
}, 1000);

function initMap() {
    const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 15,
        center: { lat: 49.2269, lng: 16.59689 },
        disableDefaultUI: true,
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

    map.addListener("click", (pos) => {
        closeBurger();
        closeHitBar();
      });

    setInterval(function () {
        for(let i=0;i<all_tickets.length;i++){
            var marker = new google.maps.Marker({});
            var myLatlng = new google.maps.LatLng(all_tickets[i]["lat"],all_tickets[i]["lng"]);
            marker = new google.maps.Marker({
                position: myLatlng,
                title: all_tickets[i]["category"]
            });
            marker.setMap(map);
        }
    }, 1500);
    
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