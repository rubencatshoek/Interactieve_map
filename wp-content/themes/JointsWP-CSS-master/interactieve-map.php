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

// Declare class variable:
$images = new imageClass();

// Get all Checkpoint information
$getCheckpoints = $checkpoints->getList();

// Convert all Checkpoints to JSON
$jsonData = $checkpoints->convertToJson($getCheckpoints);
?>
<style>
    html {
        border-top: 5px solid #fff;
        background: #58DDAF;
        color: #2a2a2a;
    }

    html, body {
        margin: 0;
        padding: 0;
    }

    h1 {
        color: #fff;
        text-align: center;
        font-weight: 300;
    }

    #slider {
        position: relative;
        overflow: hidden;
        border-radius: 4px;
    }

    #slider ul {
        position: relative;
        margin: 0;
        padding: 0;
        height: 200px;
        list-style: none;
    }

    #slider ul li {
        position: relative;
        display: block;
        float: left;
        margin: 0;
        padding: 0;
        width: 500px;
        height: 300px;
        background: #ccc;
        text-align: center;
        line-height: 300px;
    }

    a.control_prev, a.control_next {
        position: absolute;
        top: 40%;
        z-index: 999;
        display: block;
        padding: 4% 3%;
        width: auto;
        height: auto;
        background: #2a2a2a;
        color: #fff;
        text-decoration: none;
        font-weight: 600;
        font-size: 18px;
        opacity: 0.8;
        cursor: pointer;
    }

    a.control_prev:hover, a.control_next:hover {
        opacity: 1;
        -webkit-transition: all 0.2s ease;
    }

    a.control_prev {
        border-radius: 0 2px 2px 0;
    }

    a.control_next {
        right: 0;
        border-radius: 2px 0 0 2px;
    }
</style>
    <div id="slider">
        <a href="#" class="control_next">></a>
        <a href="#" class="control_prev"><</a>
        <ul>
            <li>SLIDE 1</li>
            <li style="background: #aaa;">SLIDE 2</li>
            <li>SLIDE 3</li>
            <li style="background: #aaa;">SLIDE 4</li>
        </ul>
    </div>
    <div class="inner-content">
    <main class="main small-12 medium-12 large-12 cell" role="main" onload="initMap();">
        <div id="map"></div>
    </main>
    <script>
        function initMap() {
            var map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: 51.2276878, lng: 3.799993699999959},
                zoom: 15,
                disableDefaultUI: true,
                styles: [
                    {
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#ebe3cd"
                            }
                        ]
                    },
                    {
                        "elementType": "labels.text.fill",
                        "stylers": [
                            {
                                "color": "#523735"
                            }
                        ]
                    },
                    {
                        "elementType": "labels.text.stroke",
                        "stylers": [
                            {
                                "color": "#f5f1e6"
                            }
                        ]
                    },
                    {
                        "featureType": "administrative",
                        "elementType": "geometry.stroke",
                        "stylers": [
                            {
                                "color": "#c9b2a6"
                            }
                        ]
                    },
                    {
                        "featureType": "administrative.land_parcel",
                        "elementType": "geometry.stroke",
                        "stylers": [
                            {
                                "color": "#dcd2be"
                            }
                        ]
                    },
                    {
                        "featureType": "administrative.land_parcel",
                        "elementType": "labels",
                        "stylers": [
                            {
                                "visibility": "off"
                            }
                        ]
                    },
                    {
                        "featureType": "administrative.land_parcel",
                        "elementType": "labels.text.fill",
                        "stylers": [
                            {
                                "color": "#ae9e90"
                            }
                        ]
                    },
                    {
                        "featureType": "landscape.man_made",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#8fc796"
                            }
                        ]
                    },
                    {
                        "featureType": "landscape.natural",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#8dc07e"
                            }
                        ]
                    },
                    {
                        "featureType": "poi",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#dfd2ae"
                            }
                        ]
                    },
                    {
                        "featureType": "poi",
                        "elementType": "labels.text",
                        "stylers": [
                            {
                                "visibility": "off"
                            }
                        ]
                    },
                    {
                        "featureType": "poi",
                        "elementType": "labels.text.fill",
                        "stylers": [
                            {
                                "color": "#93817c"
                            }
                        ]
                    },
                    {
                        "featureType": "poi.business",
                        "stylers": [
                            {
                                "visibility": "off"
                            }
                        ]
                    },
                    {
                        "featureType": "poi.park",
                        "elementType": "geometry.fill",
                        "stylers": [
                            {
                                "color": "#a5b076"
                            }
                        ]
                    },
                    {
                        "featureType": "poi.park",
                        "elementType": "labels.text.fill",
                        "stylers": [
                            {
                                "color": "#447530"
                            }
                        ]
                    },
                    {
                        "featureType": "road",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#f5f1e6"
                            }
                        ]
                    },
                    {
                        "featureType": "road",
                        "elementType": "labels.icon",
                        "stylers": [
                            {
                                "visibility": "off"
                            }
                        ]
                    },
                    {
                        "featureType": "road.arterial",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#fdfcf8"
                            }
                        ]
                    },
                    {
                        "featureType": "road.highway.controlled_access",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#fdfcf8"
                            }
                        ]
                    },
                    {
                        "featureType": "road.local",
                        "elementType": "labels",
                        "stylers": [
                            {
                                "visibility": "off"
                            }
                        ]
                    },
                    {
                        "featureType": "road.local",
                        "elementType": "labels.text.fill",
                        "stylers": [
                            {
                                "color": "#806b63"
                            }
                        ]
                    },
                    {
                        "featureType": "transit",
                        "stylers": [
                            {
                                "visibility": "off"
                            }
                        ]
                    },
                    {
                        "featureType": "transit.line",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#dfd2ae"
                            }
                        ]
                    },
                    {
                        "featureType": "transit.line",
                        "elementType": "labels.text.fill",
                        "stylers": [
                            {
                                "color": "#8f7d77"
                            }
                        ]
                    },
                    {
                        "featureType": "transit.line",
                        "elementType": "labels.text.stroke",
                        "stylers": [
                            {
                                "color": "#ebe3cd"
                            }
                        ]
                    },
                    {
                        "featureType": "transit.station",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#dfd2ae"
                            }
                        ]
                    },
                    {
                        "featureType": "water",
                        "elementType": "geometry.fill",
                        "stylers": [
                            {
                                "color": "#5ac5ec"
                            }
                        ]
                    },
                    {
                        "featureType": "water",
                        "elementType": "labels.text.fill",
                        "stylers": [
                            {
                                "color": "#92998d"
                            }
                        ]
                    }
                ]
            });
            var dirIcons = '/interactieve_map/wp-content/plugins/interactieve-map/admin/uploaded_images/icons/';

            var dirImages = '/interactieve_map/wp-content/plugins/interactieve-map/admin/uploaded_images/images/';

            var jsonData = <?= $jsonData ?>;

            for (var i = 0; i < jsonData.length; i++) {
                var checkpoint = jsonData[i];

                var imgUrl = dirImages + checkpoint.images;

                console.log(imgUrl);

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

                var contentPopup =
                    '<div class="">' +
                    '<h2 id="titel" style="text-align: left;">'+checkpoint.title+'</h2>'+
                    '<div id="imageTabs">' +
                    '</div>' +
                    '<input type="hidden" name="id" value="'+checkpoint.id+'">'+
                    '<p style="text-align: left;">'+checkpoint.description+'</p>'+
                    '</div>';

                contentPopup +=
                    '<img width="100%;" style="max-height: 350px;" class="mySlides float-center" src="'+imgUrl+'">' +
                    '<img width="100px;" height="100px;" src="'+dirImages+'test2.jpg"></div>'+
                    '<img width="100px;" height="100px;" src="'+dirImages+'test.jpg">';

                infowindow = new google.maps.InfoWindow({
                    content: contentPopup
                });

                //creates an infowindow 'key' in the marker.
                marker.infowindow = infowindow;

                //finally call the explicit infowindow object
                marker.addListener('click', function() {
                    return this.infowindow.open(map, this);
                });
            }
        }
    </script>
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB16bhSOI96Z6kDudIgGDbhZOyHWF6vrdw&callback=initMap">
    </script>
        <script>
            jQuery(document).ready(function ($) {
                var slideCount = $('#slider ul li').length;
                var slideWidth = $('#slider ul li').width();
                var slideHeight = $('#slider ul li').height();
                var sliderUlWidth = slideCount * slideWidth;

                $('#slider').css({ width: slideWidth, height: slideHeight });

                $('#slider ul').css({ width: sliderUlWidth, marginLeft: - slideWidth });

                $('#slider ul li:last-child').prependTo('#slider ul');

                function moveLeft() {
                    $('#slider ul').animate({
                        left: + slideWidth
                    }, 200, function () {
                        $('#slider ul li:last-child').prependTo('#slider ul');
                        $('#slider ul').css('left', '');
                    });
                }

                function moveRight() {
                    $('#slider ul').animate({
                        left: - slideWidth
                    }, 200, function () {
                        $('#slider ul li:first-child').appendTo('#slider ul');
                        $('#slider ul').css('left', '');
                    });
                };

                $('a.control_prev').click(function () {
                    moveLeft();
                });

                $('a.control_next').click(function () {
                    moveRight();
                });

            });

        </script>
</div>
<?php get_footer() ?>