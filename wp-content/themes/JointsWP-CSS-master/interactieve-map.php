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
            window.initMap = function(){
                var map = new google.maps.Map(document.getElementById('map'), {
                    center: {lat: 51.2276878, lng: 3.799993699999959},
                    zoom: 15,
                    disableDefaultUI: true,
                    styles: [{"elementType": "geometry", "stylers": [{"color": "#ebe3cd"}]},{"elementType": "labels.text.fill", "stylers": [{"color": "#523735"}]},{"elementType": "labels.text.stroke", "stylers": [{"color": "#f5f1e6"}]},{"featureType": "administrative", "elementType": "geometry.stroke", "stylers": [{"color": "#c9b2a6"}]},{"featureType": "administrative.land_parcel", "elementType": "geometry.stroke", "stylers": [{"color": "#dcd2be"}]},{"featureType": "administrative.land_parcel", "elementType": "labels", "stylers": [{"visibility": "off"}]},{"featureType": "administrative.land_parcel", "elementType": "labels.text.fill", "stylers": [{"color": "#ae9e90"}]},{"featureType": "landscape.man_made", "elementType": "geometry", "stylers": [{"color": "#8fc796"}]},{"featureType": "landscape.natural", "elementType": "geometry", "stylers": [{"color": "#8dc07e"}]},{"featureType": "poi", "elementType": "geometry", "stylers": [{"color": "#dfd2ae"}]},{"featureType": "poi", "elementType": "labels.text", "stylers": [{"visibility": "off"}]},{"featureType": "poi", "elementType": "labels.text.fill", "stylers": [{"color": "#93817c"}]},{"featureType": "poi.business", "stylers": [{"visibility": "off"}]},{"featureType": "poi.park", "elementType": "geometry.fill", "stylers": [{"color": "#a5b076"}]},{"featureType": "poi.park", "elementType": "labels.text.fill", "stylers": [{"color": "#447530"}]},{"featureType": "road", "elementType": "geometry", "stylers": [{"color": "#f5f1e6"}]},{"featureType": "road", "elementType": "labels.icon", "stylers": [{"visibility": "off"}]},{"featureType": "road.arterial", "elementType": "geometry", "stylers": [{"color": "#fdfcf8"}]},{"featureType": "road.highway.controlled_access", "elementType": "geometry", "stylers": [{"color": "#fdfcf8"}]},{"featureType": "road.local", "elementType": "labels", "stylers": [{"visibility": "off"}]},{"featureType": "road.local", "elementType": "labels.text.fill", "stylers": [{"color": "#806b63"}]},{"featureType": "transit", "stylers": [{"visibility": "off"}]},{"featureType": "transit.line", "elementType": "geometry", "stylers": [{"color": "#dfd2ae"}]},{"featureType": "transit.line", "elementType": "labels.text.fill", "stylers": [{"color": "#8f7d77"}]},{"featureType": "transit.line", "elementType": "labels.text.stroke", "stylers": [{"color": "#ebe3cd"}]},{"featureType": "transit.station", "elementType": "geometry", "stylers": [{"color": "#dfd2ae"}]},{"featureType": "water", "elementType": "geometry.fill", "stylers": [{"color": "#5ac5ec"}]},{"featureType": "water", "elementType": "labels.text.fill", "stylers": [{"color": "#92998d"}]}]
                });


                var dirIcons = '/interactieve_map/wp-content/plugins/interactieve-map/admin/uploaded_images/icons/';

                var dirImages = '/interactieve_map/wp-content/plugins/interactieve-map/admin/uploaded_images/images/';

                var jsonData = <?= $jsonData ?>;

                var markers = [];

                var imageTimeOut;

                var infowindow;

                for (var i = 0; i < jsonData.length; i++) {
                    checkpoint = jsonData[i];
                    var imgUrl = dirImages + checkpoint.images;

                    var myLatLng = new google.maps.LatLng(parseFloat(checkpoint.latitude), parseFloat(checkpoint.longitude));

                    var icon = {
                        url: dirIcons + checkpoint.icon,
                        scaledSize: new google.maps.Size(35, 35)
                    };

                    marker = new google.maps.Marker({
                        position: myLatLng,
                        map: map,
                        icon: icon
                    });

                    markers.push(marker);

                    var contentPopup =
                        '<div class="infoWindow">' +
                        '<h2 id="titel">' + checkpoint.title + '</h2>';

                    for (var j = 0; j < checkpoint.images.length; j++) {
                        contentPopup += '<img class="mySlides" width="100%;" style="max-height: 350px;" src="'+dirImages+checkpoint.images[j]+'">';
                    }

                    contentPopup +=
                        '<p>' + checkpoint.description + '</p>'+
                        '</div>';

                    infowindow = new google.maps.InfoWindow({
                        content: contentPopup
                    });
                    //creates an infowindow 'key' in the marker.
                    marker.infowindow = infowindow;

                    var p = document.getElementsByClassName("mySlides");
                    console.log(p);
                    slider();

                    //finally call the explicit infowindow object
                    marker.addListener('click', function () {
                        hideAllInfoWindows(map);
                        this.infowindow.open(map, this);
                    });

                    function hideAllInfoWindows(map) {
                        markers.forEach(function(marker) {
                            marker.infowindow.close(map, marker);
                        });
                    }

                    function slider () {
                    google.maps.event.addListener(infowindow, 'domready', function () {
                        jQuery(document).ready(function ($) {
                            clearTimeout(imageTimeOut);

                            var x = document.getElementsByClassName("mySlides");

                            var slideIndex = 0;
                            if (x.length > 0) {
                            carousel();
                            }

                            function carousel() {
                                var i;
                                for (i = 0; i < x.length; i++) {
                                    x[i].style.display = "none";
                                }
                                slideIndex++;

                                if (slideIndex > x.length) {slideIndex = 1}
                                if (x.length > 0) {
                                x[slideIndex-1].style.display = "block";
                                imageTimeOut = setTimeout(function() {carousel();}, 3000); // Change image every 3 seconds
                                }
                            }
                        });
                    });
                    }
                }
            };
        </script>
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB16bhSOI96Z6kDudIgGDbhZOyHWF6vrdw&callback=initMap">
    </script>
</div>
<?php get_footer() ?>