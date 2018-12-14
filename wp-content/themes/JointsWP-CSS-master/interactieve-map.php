<?php
/*
Template Name: Interactieve map
*/

// Get the header
get_header();

// Include model:
include INTERACTIEVE_MAP_PLUGIN_MODEL_DIR . "/checkpointClass.php";

// Declare class variable:
$checkpoints = new checkpointClass();
$images = new imageClass();

// Get all Checkpoint information
$getCheckpoints = $checkpoints->getList();

// Convert all Checkpoints to JSON
$jsonData = $checkpoints->convertToJson($getCheckpoints);
?>
<div class="inner-content">
    <main class="main small-12 medium-12 large-12 cell" role="main">
        <div id="map"></div>
    </main>
        <script>
            // Map function on all browsers
            window.initMap = function(){
                var map = new google.maps.Map(document.getElementById('map'), {
                    center: {lat: 51.2276878, lng: 3.799993699999959},
                    zoom: 15,
                    disableDefaultUI: true,
                    styles: [{"elementType": "geometry", "stylers": [{"color": "#ebe3cd"}]},{"elementType": "labels.text.fill", "stylers": [{"color": "#523735"}]},{"elementType": "labels.text.stroke", "stylers": [{"color": "#f5f1e6"}]},{"featureType": "administrative", "elementType": "geometry.stroke", "stylers": [{"color": "#c9b2a6"}]},{"featureType": "administrative.land_parcel", "elementType": "geometry.stroke", "stylers": [{"color": "#dcd2be"}]},{"featureType": "administrative.land_parcel", "elementType": "labels", "stylers": [{"visibility": "off"}]},{"featureType": "administrative.land_parcel", "elementType": "labels.text.fill", "stylers": [{"color": "#ae9e90"}]},{"featureType": "landscape.man_made", "elementType": "geometry", "stylers": [{"color": "#8fc796"}]},{"featureType": "landscape.natural", "elementType": "geometry", "stylers": [{"color": "#8dc07e"}]},{"featureType": "poi", "elementType": "geometry", "stylers": [{"color": "#dfd2ae"}]},{"featureType": "poi", "elementType": "labels.text", "stylers": [{"visibility": "off"}]},{"featureType": "poi", "elementType": "labels.text.fill", "stylers": [{"color": "#93817c"}]},{"featureType": "poi.business", "stylers": [{"visibility": "off"}]},{"featureType": "poi.park", "elementType": "geometry.fill", "stylers": [{"color": "#a5b076"}]},{"featureType": "poi.park", "elementType": "labels.text.fill", "stylers": [{"color": "#447530"}]},{"featureType": "road", "elementType": "geometry", "stylers": [{"color": "#f5f1e6"}]},{"featureType": "road", "elementType": "labels.icon", "stylers": [{"visibility": "off"}]},{"featureType": "road.arterial", "elementType": "geometry", "stylers": [{"color": "#fdfcf8"}]},{"featureType": "road.highway.controlled_access", "elementType": "geometry", "stylers": [{"color": "#fdfcf8"}]},{"featureType": "road.local", "elementType": "labels", "stylers": [{"visibility": "off"}]},{"featureType": "road.local", "elementType": "labels.text.fill", "stylers": [{"color": "#806b63"}]},{"featureType": "transit", "stylers": [{"visibility": "off"}]},{"featureType": "transit.line", "elementType": "geometry", "stylers": [{"color": "#dfd2ae"}]},{"featureType": "transit.line", "elementType": "labels.text.fill", "stylers": [{"color": "#8f7d77"}]},{"featureType": "transit.line", "elementType": "labels.text.stroke", "stylers": [{"color": "#ebe3cd"}]},{"featureType": "transit.station", "elementType": "geometry", "stylers": [{"color": "#dfd2ae"}]},{"featureType": "water", "elementType": "geometry.fill", "stylers": [{"color": "#5ac5ec"}]},{"featureType": "water", "elementType": "labels.text.fill", "stylers": [{"color": "#92998d"}]}]
                });

                // Set directory for icons
                var dirIcons = '/interactieve_map/wp-content/plugins/interactieve-map/admin/uploaded_images/icons/';

                // Set directory for images
                var dirImages = '/interactieve_map/wp-content/plugins/interactieve-map/admin/uploaded_images/images/';

                // Get the JSON data from PHP
                var jsonData = <?= $jsonData ?>;

                // Set empty var for later
                var markers = [];

                // Set empty var for later
                var infowindow;

                // Set empty var for later
                var markerCluster;

                // Loop through Checkpoints
                for (var i = 0; i < jsonData.length; i++) {
                    // Set JSON data in variable
                    checkpoint = jsonData[i];

                    // Set the location for the markers
                    var myLatLng = new google.maps.LatLng(parseFloat(checkpoint.latitude), parseFloat(checkpoint.longitude));

                    // Set the icon picture and size
                    var icon = {
                        url: dirIcons + checkpoint.icon,
                        scaledSize: new google.maps.Size(35, 35)
                    };

                    // Set the markers on the map
                    var marker = new google.maps.Marker({
                        position: myLatLng,
                        map: map,
                        icon: icon
                    });

                    // Push the markers into the variable
                    markers.push(marker);

                    // Setup the content for the infowindow that opens when you click on a checkpoint
                    var contentPopup =
                        '<div class="row">'+
                        '<div class="small-12 small-centered text-center columns">'+
                        '<h2 id="titel">' + checkpoint.title + '</h2>';

                    // Loop through the images
                    for (var j = 0; j < checkpoint.images.length; j++) {
                        contentPopup += '<img class="mySlides" style=" max-width: 640px; max-height: 360px; width: 100%; margin: auto; padding: auto;" src="'+dirImages+checkpoint.images[j]+'">';
                    }

                    if (checkpoint.images.length > 1) {
                    contentPopup +=
                        '<a href="#" class="controls" onclick="myShow.previous()"> < </a>&nbsp;'+
                        '<a href="#" class="controls" onclick="myShow.next()"> > </a>';
                    }

                    // Add the description to the content so it's below the image
                    contentPopup +=
                        '<p>' + checkpoint.description + '</p>'+
                        '</div>'+
                        '</div>'+
                        '</div>';

                    // Call a new infowindow
                    infowindow = new google.maps.InfoWindow({
                        content: contentPopup
                    });

                    // Create an infowindow 'key' in the marker
                    marker.infowindow = infowindow;

                    // Call the slider function if there are images
                    if (checkpoint.images.length > 0) {
                        slider();
                    }

                    // Close the other infowindow when clicked on another and open the new infowindow
                    marker.addListener('click', function () {
                        hideAllInfoWindows(map);
                        this.infowindow.open(map, this);
                    });
                }

                // Add a marker clusterer to manage the markers.
                markerCluster = new MarkerClusterer(map, markers, {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});

                // Function to hide the previous infowindow
                function hideAllInfoWindows(map) {
                    // Foreach through the markers
                    markers.forEach(function (marker) {
                        // Close the previous infowindow
                        marker.infowindow.close(map, marker);
                    });
                }

                // Slider function
                function slider() {
                    google.maps.event.addListener(infowindow, 'domready', function () {
                        myShow = w3.slideshow(".mySlides", 0);
                    });
                }

            };
        </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB16bhSOI96Z6kDudIgGDbhZOyHWF6vrdw&callback=initMap"></script>
    <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
</div>
<?php get_footer() ?>