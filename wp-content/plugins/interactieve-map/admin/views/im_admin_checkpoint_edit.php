<?php
// Include model:
include INTERACTIEVE_MAP_PLUGIN_MODEL_DIR . "/checkpointClass.php";

// Declare class variable:
$checkpoints = new checkpointClass();

// Declare class variable:
$images = new imageClass();

$id = $_GET['id'];

// Retrieve checkpoint list
$checkpointList = $checkpoints->getList();

// Retrieve checkpoint by id
$singleCheckpoint = $checkpoints->getById($id);

// Retrieve image by checkpoint id
$singleImage = $images->getById($id);

// Putting the post values in a variable
$input_array = $_POST;

// Directory for saving images
$upOne = realpath(__DIR__ . '/..');
$uploadDirectory = "/uploaded_images/icons/";

// Store all icon foreseen and unforseen errors here
$errors = [];

// Get the only useable file extensions
$fileExtensions = ['jpeg', 'jpg', 'png'];

// Refers to uploaded file name
$fileName = $_FILES['icon']['name'];
// Refers temporary name of directory in web server
$fileTmpName = $_FILES['icon']['tmp_name'];
// Refers to file type
$fileType = $_FILES['icon']['type'];
// Refers extension of file
$fileExtension = strtolower(end(explode('.', $fileName)));

// Shows where to place the uploaded file
$uploadPath = $upOne . $uploadDirectory . basename($fileName);

// FOR SAVING MULTIPLE IMAGES
// Directory for saving images
$imageUploadDirectory = "/uploaded_images/images/";

// Shows where to place the uploaded file
$uploadPath = $upOne . $uploadDirectory . basename($fileName);

// Refers to uploaded image file names and counts the amount of images uploaded
$imageFileName = ($_FILES['image']['name']);

// Count # of uploaded files in array
$total = count($_FILES['image']['name']);

$fileExists = false;

// Loop through each file
if ($total > 0) {
    for($i=0 ; $i < $total ; $i++) {

        // Refers extension of file
        $imageFileExtension = strtolower(end(explode('.',$imageFileName[$i])));

        //Get the temp file path
        $tmpFilePath = $_FILES['image']['tmp_name'][$i];

        //Make sure we have a file path
        if ($tmpFilePath != ""){

            //Setup our new file path
            $newFilePath = $upOne . $imageUploadDirectory . $_FILES['image']['name'][$i];

            // Check if filename already exists
            if(file_exists($newFilePath)) {
                $fileExists = true;
                $errors[] = "Een of meerdere gekozen afbeeldingen bestaan al." . '<br>';
            }

            // Check if filename already exists
            if(file_exists($uploadPath) && (!empty($fileName))) {
                $fileExists = true;
            }

            if (! in_array($imageFileExtension,$fileExtensions)) {
                $errors[] = "Het bestand type " . $imageFileExtension . " is niet mogelijk. Upload een JPEG, JPG of PNG." . '<br>';
            }

            // If no errors are found
            if (empty($errors) && $fileExists == false) {
                //Upload the file
                $didImageUpload = move_uploaded_file($tmpFilePath, $newFilePath);
            }
        }
    }
}

// If submit
if (isset($input_array['submit']) && !empty($input_array['submit'])) {

    // If no image is uploaded, don't activate checks for uploaded image
    if (empty($fileName) && $fileExists == false) {
        // Refer to different update if no image has been uploaded
        $checkpoints->update($input_array, $fileName, $imageFileName, $id);
        echo '<script>location.href="?page=interactieve-map-admin";</script>';
        exit;
    }
    else {
        // Check if filename already exists
        if(file_exists($uploadPath) && !empty($fileName)) {
            $fileExists = true;
            $errors[] = "De icon " . $fileName . ", bestaat al." . '<br>';
        }

        // If uploaded file doesn't match the available extensions
        if (!in_array($fileExtension, $fileExtensions) && !empty($fileName)) {
            $errors[] = "Dit bestand type is niet mogelijk. Upload een JPEG, JPG of PNG.";
        }

        // If no errors are found
        if (empty($errors) &&  empty($imageErrors) && $fileExists === false) {
            $didUpload = move_uploaded_file($fileTmpName, $uploadPath);

            // If file has been uploaded, start create function and redirect to overview page after that
            if ($didUpload) {
                $checkpoints->update($input_array, $fileName, $imageFileName, $id);
                echo '<script>location.href="?page=interactieve-map-admin";</script>';
                exit;
            } else {
                echo "Kon bestand niet uploaden, probeer het opnieuw.";
            }
        } else {
            // If there are errors, echo them
            foreach ($errors as $error) {
                echo $error;
            }
        }
    }
}

// If clicked on the single image delete button
if (isset($_POST['delete']) && !empty($_POST['delete'])) {
    $images->delete($input_array);
    echo '<script>window.history.back();</script>';
    exit;
}
?>
<script>
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
            styles: [{"elementType": "geometry", "stylers": [{"color": "#ebe3cd"}]},{"elementType": "labels.text.fill", "stylers": [{"color": "#523735"}]},{"elementType": "labels.text.stroke", "stylers": [{"color": "#f5f1e6"}]},{"featureType": "administrative", "elementType": "geometry.stroke", "stylers": [{"color": "#c9b2a6"}]},{"featureType": "administrative.land_parcel", "elementType": "geometry.stroke", "stylers": [{"color": "#dcd2be"}]},{"featureType": "administrative.land_parcel", "elementType": "labels", "stylers": [{"visibility": "off"}]},{"featureType": "administrative.land_parcel", "elementType": "labels.text.fill", "stylers": [{"color": "#ae9e90"}]},{"featureType": "landscape.man_made", "elementType": "geometry", "stylers": [{"color": "#8fc796"}]},{"featureType": "landscape.natural", "elementType": "geometry", "stylers": [{"color": "#8dc07e"}]},{"featureType": "poi", "elementType": "geometry", "stylers": [{"color": "#dfd2ae"}]},{"featureType": "poi", "elementType": "labels.text", "stylers": [{"visibility": "off"}]},{"featureType": "poi", "elementType": "labels.text.fill", "stylers": [{"color": "#93817c"}]},{"featureType": "poi.business", "stylers": [{"visibility": "off"}]},{"featureType": "poi.park", "elementType": "geometry.fill", "stylers": [{"color": "#a5b076"}]},{"featureType": "poi.park", "elementType": "labels.text.fill", "stylers": [{"color": "#447530"}]},{"featureType": "road", "elementType": "geometry", "stylers": [{"color": "#f5f1e6"}]},{"featureType": "road", "elementType": "labels.icon", "stylers": [{"visibility": "off"}]},{"featureType": "road.arterial", "elementType": "geometry", "stylers": [{"color": "#fdfcf8"}]},{"featureType": "road.highway.controlled_access", "elementType": "geometry", "stylers": [{"color": "#fdfcf8"}]},{"featureType": "road.local", "elementType": "labels", "stylers": [{"visibility": "off"}]},{"featureType": "road.local", "elementType": "labels.text.fill", "stylers": [{"color": "#806b63"}]},{"featureType": "transit", "stylers": [{"visibility": "off"}]},{"featureType": "transit.line", "elementType": "geometry", "stylers": [{"color": "#dfd2ae"}]},{"featureType": "transit.line", "elementType": "labels.text.fill", "stylers": [{"color": "#8f7d77"}]},{"featureType": "transit.line", "elementType": "labels.text.stroke", "stylers": [{"color": "#ebe3cd"}]},{"featureType": "transit.station", "elementType": "geometry", "stylers": [{"color": "#dfd2ae"}]},{"featureType": "water", "elementType": "geometry.fill", "stylers": [{"color": "#5ac5ec"}]},{"featureType": "water", "elementType": "labels.text.fill", "stylers": [{"color": "#92998d"}]}]
        };

        //Create the map object.
        map = new google.maps.Map(document.getElementById('map'), options);

        //Get current location from database
        var getLocation = {lat: <?= $singleCheckpoint->getLatitude(); ?>, lng: <?= $singleCheckpoint->getLongitude(); ?>};

        //Set current marker
        marker = new google.maps.Marker({
            position: getLocation,
            map: map
        });

        //Listen for any clicks on the map.
        google.maps.event.addListener(map, 'click', function(event) {
            //Get the location that the user clicked.
            var clickedLocation = event.latLng;
            //If the marker hasn't been added.
            if(marker === false){
                //Create the marker.
                marker = new google.maps.Marker({
                    position: clickedLocation,
                    map: map
                });
            } else{
                //Marker has already been added, so just change its location.
                marker.setPosition(clickedLocation);
            }
            //Get the marker's location.
            markerLocation();
        });
    }

    //This function will get the marker's current location and then add the lat/long
    //values to our textfields so that we can save the location.
    function markerLocation(){
        //Get location.
        var currentLocation = marker.getPosition();
        //Add lat and lng values to a field that we can save.
        document.getElementById('lat').value = currentLocation.lat(); //latitude
        document.getElementById('lng').value = currentLocation.lng(); //longitude
    }
</script>
<form method="post" enctype="multipart/form-data" id="wijzigen">
    <div class="grid-x cell">
        <h2>Checkpoint wijzigen</h2>
        <div class="grid-x cell">
            <div id="map"></div><br>
            <input type="hidden" class="input-style" id="lat" name="latitude" value="<?= $singleCheckpoint->getLatitude(); ?>" readonly/>
        </div>
        <div class="grid-x cell">
            <input type="hidden" class="input-style" id="lng" name="longitude" value="<?= $singleCheckpoint->getLongitude(); ?>" readonly/>
        </div>

        <label for="title">Titel:</label><br>
        <input type="hidden" id="id" name="id" value="<?= $singleCheckpoint->getId(); ?>" required/>
        <input type="text" class="input-style" id="title" name="title" value="<?= $singleCheckpoint->getTitle(); ?>"
               required/>
    </div>
    <div class="grid-x cell">
        <label for="description">Beschrijving:</label><br>
        <textarea type="text" class="input-style" id="description" name="description" rows="5"
                  cols="43"><?= $singleCheckpoint->getDescription(); ?></textarea>
    </div>
    <div class="grid-x cell">
        <label for="icon">Icoon:</label><br>
        <input type="file" id="icon" accept="image/*"  name="icon"/>
    </div>
    <div class="grid-x cell">
        <label for="image">Uitgelichte afbeelding(en):</label><br>
        <input type="file" id="image" multiple="multiple" accept="image/*" name="image[]"/><br>
    </div>
    <div class="grid-x cell space">
        <?php
        foreach ($singleImage as $image) {
            echo '<form method="post">' .
                '<input type="submit" name="delete" value="Verwijderen">' .
                $image->getImage() . '<br>' .
                '<input type="hidden" name="single_image" value="' . $image->getImage() . '">' .
                '<input type="hidden" name="image_id" value="' . $image->getId() . '">' .
                '</form>';
        }; ?>
    </div>
    <br>
    <div class="grid-x cell">
        <input type="submit" class="button-style" name="submit" form="wijzigen" value="Wijzigen">
        <a href="?page=interactieve-map-admin"><input type="button" class="button-style-dark"  value="Terug naar overzicht"></a>
    </div>
</form>
<script>
    var imageField = document.getElementById("image");
    imageField.onchange = function() {
        if(this.files[0].size > 2000000){
            alert("De afbeelding die je kiest is groter dan 2mb. Kies een ander bestand");
            this.value = "";
        }
    };

    var iconField = document.getElementById("icon");
    iconField.onchange = function() {
        if(this.files[0].size > 2000000){
            alert("De icon die je kiest is groter dan 2mb. Kies een ander bestand");
            this.value = "";
        }
    };
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB16bhSOI96Z6kDudIgGDbhZOyHWF6vrdw&callback=initMap"></script>