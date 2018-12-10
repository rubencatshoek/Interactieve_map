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
    <main class="main small-12 medium-12 large-12 cell" role="main" onload="initMap();">
        <div id="map" onchange="test();"></div>
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
                var imageTimeOut;

                // Set empty var for later
                var infowindow;

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
                    marker = new google.maps.Marker({
                        position: myLatLng,
                        map: map,
                        icon: icon
                    });

                    // Push the markers into the variable
                    markers.push(marker);

                    // Setup the content for the infowindow that opens when you click on a checkpoint
                    var contentPopup =
                        '<div class="infoWindow">' +
                        '<h2 id="titel">' + checkpoint.title + '</h2>';

                    // Loop through the images
                    for (var j = 0; j < checkpoint.images.length; j++) {
                        contentPopup += '<img class="mySlides" width="100%;" style="max-height: 350px;" src="'+dirImages+checkpoint.images[j]+'">';
                    }

                    // Add the description to the content so it's below the image
                    contentPopup +=
                        '<p>' + checkpoint.description + '</p>'+
                        '</div>';

                    // Call a new infowindow
                    infowindow = new google.maps.InfoWindow({
                        content: contentPopup
                    });

                    // Create an infowindow 'key' in the marker
                    marker.infowindow = infowindow;

                    // Call the slider function
                    slider();

                    // Close the other infowindow when clicked on another and open the new infowindow
                    marker.addListener('click', function () {
                        hideAllInfoWindows(map);
                        this.infowindow.open(map, this);
                    });

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
                            jQuery(document).ready(function ($) {
                                // Clear the timeout so it starts over everytime you open an infowindow
                                clearTimeout(imageTimeOut);

                                // Get the HTML data from the class mySlides
                                var x = document.getElementsByClassName("mySlides");

                                // Set the slideindex to zero for later
                                var slideIndex = 0;

                                // Run the image carousel if there is more then zero
                                if (x.length > 0) {
                                    carousel();
                                }

                                // Carousel function
                                function carousel() {
                                    for (var i = 0; i < x.length; i++) {
                                        // Hide the images
                                        x[i].style.display = "none";
                                    }
                                    slideIndex++;
                                    if (slideIndex > x.length) {
                                        slideIndex = 1
                                    }
                                    // Only do this if there is more then zero images
                                    if (x.length > 0) {
                                        // Set the style to block so it displays
                                        x[slideIndex - 1].style.display = "block";
                                        // Change image every 3 seconds
                                        imageTimeOut = setTimeout(function () {
                                            carousel();
                                        }, 3000);
                                    }
                                }
                            });
                        });
                    }
                }
            };
        </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB16bhSOI96Z6kDudIgGDbhZOyHWF6vrdw&callback=initMap"></script>
</div>
<?php get_footer() ?>