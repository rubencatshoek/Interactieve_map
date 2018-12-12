
//Will contain map object.
var map;

//Has the user plotted their location marker?
var marker = false;

//Function called to initialize / create the map.
function initMap() {
    //Map options.
    var options = {
        center: {lat: 51.2276878, lng: 3.7999936},
        zoom: 15,
        disableDefaultUI: true,
        styles: [{"elementType": "geometry", "stylers": [{"color": "#ebe3cd"}]}, {
            "elementType": "labels.text.fill",
            "stylers": [{"color": "#523735"}]
        }, {"elementType": "labels.text.stroke", "stylers": [{"color": "#f5f1e6"}]}, {
            "featureType": "administrative",
            "elementType": "geometry.stroke",
            "stylers": [{"color": "#c9b2a6"}]
        }, {
            "featureType": "administrative.land_parcel",
            "elementType": "geometry.stroke",
            "stylers": [{"color": "#dcd2be"}]
        }, {
            "featureType": "administrative.land_parcel",
            "elementType": "labels",
            "stylers": [{"visibility": "off"}]
        }, {
            "featureType": "administrative.land_parcel",
            "elementType": "labels.text.fill",
            "stylers": [{"color": "#ae9e90"}]
        }, {
            "featureType": "landscape.man_made",
            "elementType": "geometry",
            "stylers": [{"color": "#8fc796"}]
        }, {
            "featureType": "landscape.natural",
            "elementType": "geometry",
            "stylers": [{"color": "#8dc07e"}]
        }, {"featureType": "poi", "elementType": "geometry", "stylers": [{"color": "#dfd2ae"}]}, {
            "featureType": "poi",
            "elementType": "labels.text",
            "stylers": [{"visibility": "off"}]
        }, {
            "featureType": "poi",
            "elementType": "labels.text.fill",
            "stylers": [{"color": "#93817c"}]
        }, {"featureType": "poi.business", "stylers": [{"visibility": "off"}]}, {
            "featureType": "poi.park",
            "elementType": "geometry.fill",
            "stylers": [{"color": "#a5b076"}]
        }, {
            "featureType": "poi.park",
            "elementType": "labels.text.fill",
            "stylers": [{"color": "#447530"}]
        }, {
            "featureType": "road",
            "elementType": "geometry",
            "stylers": [{"color": "#f5f1e6"}]
        }, {
            "featureType": "road",
            "elementType": "labels.icon",
            "stylers": [{"visibility": "off"}]
        }, {
            "featureType": "road.arterial",
            "elementType": "geometry",
            "stylers": [{"color": "#fdfcf8"}]
        }, {
            "featureType": "road.highway.controlled_access",
            "elementType": "geometry",
            "stylers": [{"color": "#fdfcf8"}]
        }, {
            "featureType": "road.local",
            "elementType": "labels",
            "stylers": [{"visibility": "off"}]
        }, {
            "featureType": "road.local",
            "elementType": "labels.text.fill",
            "stylers": [{"color": "#806b63"}]
        }, {"featureType": "transit", "stylers": [{"visibility": "off"}]}, {
            "featureType": "transit.line",
            "elementType": "geometry",
            "stylers": [{"color": "#dfd2ae"}]
        }, {
            "featureType": "transit.line",
            "elementType": "labels.text.fill",
            "stylers": [{"color": "#8f7d77"}]
        }, {
            "featureType": "transit.line",
            "elementType": "labels.text.stroke",
            "stylers": [{"color": "#ebe3cd"}]
        }, {
            "featureType": "transit.station",
            "elementType": "geometry",
            "stylers": [{"color": "#dfd2ae"}]
        }, {
            "featureType": "water",
            "elementType": "geometry.fill",
            "stylers": [{"color": "#5ac5ec"}]
        }, {"featureType": "water", "elementType": "labels.text.fill", "stylers": [{"color": "#92998d"}]}]
    };

    //Create the map object.
    map = new google.maps.Map(document.getElementById('map'), options);

    // Get default location
    var getLocation = {lat: 51.22762817160363, lng: 3.799993699999959};

    //Set current marker
    marker = new google.maps.Marker({
        position: getLocation,
        map: map
    });

    //Listen for any clicks on the map.
    google.maps.event.addListener(map, 'click', function (event) {
        //Get the location that the user clicked.
        var clickedLocation = event.latLng;
        //If the marker hasn't been added.
        if (marker === false) {
            //Create the marker.
            marker = new google.maps.Marker({
                position: clickedLocation,
                map: map
            });
        } else {
            //Marker has already been added, so just change its location.
            marker.setPosition(clickedLocation);
        }
        //Get the marker's location.
        markerLocation();
    });
}

//This function will get the marker's current location and then add the lat/long
//values to our textfields so that we can save the location.
function markerLocation() {
    //Get location.
    var currentLocation = marker.getPosition();
    //Add lat and lng values to a field that we can save.
    document.getElementById('lat').value = currentLocation.lat(); //latitude
    document.getElementById('lng').value = currentLocation.lng(); //longitude
}