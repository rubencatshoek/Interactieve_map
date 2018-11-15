<?php
/*
Template Name: Interactieve map
*/

get_header(); ?>

	<div class="content">

		<div class="inner-content grid-x grid-margin-x grid-padding-x">

		    <main class="main small-12 medium-12 large-12 cell" role="main">

				<head>
					<style>
						/* Set the size of the div element that contains the map */
						#map {
							margin-top: 1%;
							height: 600px;  /* The height is 400 pixels */
							width: 100%;  /* The width is the width of the web page */
						}
					</style>
				</head>
				<body>
				<!--The div element for the map -->
				<div id="map"></div>
				<script>
					function initMap() {
						// Styles a map in night mode.
						var map = new google.maps.Map(document.getElementById('map'), {
							center: {lat: 51.2276878, lng: 3.799993699999959},
							zoom: 15,
							styles: [
								{
									"elementType": "geometry",
									"stylers": [
										{
											"color": "#f5f5f5"
										}
									]
								},
								{
									"elementType": "labels.icon",
									"stylers": [
										{
											"visibility": "off"
										}
									]
								},
								{
									"elementType": "labels.text.fill",
									"stylers": [
										{
											"color": "#616161"
										}
									]
								},
								{
									"elementType": "labels.text.stroke",
									"stylers": [
										{
											"color": "#f5f5f5"
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
											"color": "#bdbdbd"
										}
									]
								},
								{
									"featureType": "poi",
									"elementType": "geometry",
									"stylers": [
										{
											"color": "#eeeeee"
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
											"color": "#757575"
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
									"elementType": "geometry",
									"stylers": [
										{
											"color": "#e5e5e5"
										}
									]
								},
								{
									"featureType": "poi.park",
									"elementType": "labels.text.fill",
									"stylers": [
										{
											"color": "#9e9e9e"
										}
									]
								},
								{
									"featureType": "road",
									"elementType": "geometry",
									"stylers": [
										{
											"color": "#009d45"
										},
										{
											"weight": 1
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
									"elementType": "labels.text.fill",
									"stylers": [
										{
											"color": "#757575"
										}
									]
								},
								{
									"featureType": "road.highway",
									"elementType": "labels.text.fill",
									"stylers": [
										{
											"color": "#616161"
										}
									]
								},
								{
									"featureType": "road.local",
									"elementType": "geometry.fill",
									"stylers": [
										{
											"color": "#009d45"
										}
									]
								},
								{
									"featureType": "road.local",
									"elementType": "geometry.stroke",
									"stylers": [
										{
											"visibility": "off"
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
											"color": "#9e9e9e"
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
											"color": "#e5e5e5"
										}
									]
								},
								{
									"featureType": "transit.station",
									"elementType": "geometry",
									"stylers": [
										{
											"color": "#eeeeee"
										}
									]
								},
								{
									"featureType": "water",
									"elementType": "geometry",
									"stylers": [
										{
											"color": "#c9c9c9"
										}
									]
								},
								{
									"featureType": "water",
									"elementType": "labels.text.fill",
									"stylers": [
										{
											"color": "#9e9e9e"
										}
									]
								}
							]
						});
					}
				</script>
				<!--Load the API from the specified URL
                * The async attribute allows the browser to render the page while the API loads
                * The key parameter will contain your own API key (which is not needed for this tutorial)
                * The callback parameter executes the initMap() function
                -->
				<script async defer
						src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCbDKz8LkRCDtjtv0dWxfWOpiruMpxshIg&callback=initMap">
				</script>
				</body>

			</main> <!-- end #main -->

		</div> <!-- end #inner-content -->

	</div> <!-- end #content -->

<?php get_footer(); ?>
