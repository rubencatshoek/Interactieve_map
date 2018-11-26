<?php
// Include model:
include INTERACTIEVE_MAP_PLUGIN_MODEL_DIR . "/checkpointClass.php";

// Declare class variable:
$checkpoints = new checkpointClass();

// Putting the post values in a variable
$input_array = $_POST;

// Directory for saving images
$upOne = realpath(__DIR__ . '/..');
$uploadDirectory = "/uploaded_images/icons/";

// Store all foreseen and unforseen errors here
$errors = [];

// Get the only useable file extensions
$fileExtensions = ['jpeg','jpg','png'];

// Refers to uploaded file name
$fileName = $_FILES['icon']['name'];
// Refers temporary name of directory in web server
$fileTmpName  = $_FILES['icon']['tmp_name'];
// Refers to file type
$fileType = $_FILES['icon']['type'];
// Refers extension of file
$fileExtension = strtolower(end(explode('.',$fileName)));


// FOR SAVING MULTIPLE IMAGES
// Directory for saving images
$imageUploadDirectory = "/uploaded_images/images/";

// Shows where to place the uploaded file
$uploadPath = $upOne . $uploadDirectory . basename($fileName);

// Refers to uploaded image file names and counts the amount of images uploaded
$imageFileName = ($_FILES['image']['name']);

// Count # of uploaded files in array
$total = count($_FILES['image']['name']);

// Loop through each file
for ($i = 0; $i < $total; $i++) {

    // Refers extension of file
    $imageFileExtension = strtolower(end(explode('.', $imageFileName[$i])));

    //Get the temp file path
    $tmpFilePath = $_FILES['image']['tmp_name'][$i];

    //Make sure we have a file path
    if ($tmpFilePath != "") {

        //Setup our new file path
        $newFilePath = $upOne . $imageUploadDirectory . $_FILES['image']['name'][$i];

        if (!in_array($imageFileExtension, $fileExtensions)) {
            $errors[] = "Dit bestand type is niet mogelijk. Upload een JPEG, JPG of PNG." . '<br>';
        }

        // If no errors are found
        if (empty($errors)) {
            //Upload the file
            $didImageUpload = move_uploaded_file($tmpFilePath, $newFilePath);
        } else {
            echo "Kon bestand niet uploaden, probeer het opnieuw." . '<br>';
        }
    }
}
// If submit
if (isset($input_array['submit']) && !empty($input_array['submit'])) {
    // If uploaded file doesn't match the available extensions
    if (! in_array($fileExtension,$fileExtensions)) {
        $errors[] = "Dit bestand type is niet mogelijk. Upload een JPEG, JPG of PNG.";
    }

    // If no errors are found
    if (empty($errors)) {
        $didUpload = move_uploaded_file($fileTmpName, $uploadPath);

        // If file has been uploaded, start create function and redirect to overview page after that
        if ($didUpload) {
            $checkpoints->create($input_array, $fileName, $imageFileName);
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
?>
<form method="post" enctype="multipart/form-data">
    <div class="grid-x cell">
        <h2>Checkpoint aanmaken</h2>
        <label for="title">Titel:</label><br>
        <input type="text" class="input-style" id="title" name="title" placeholder="Titel" required/>
    </div>
    <div class="grid-x cell">
        <label for="description">Beschrijving:</label><br>
        <textarea type="text" class="input-style"  id="description" name="description" placeholder="Beschrijving"
                  rows="5" cols="43"></textarea>
    </div>
    <div class="grid-x cell">
        <label for="icon">Icoon:</label><br>
        <input type="file" id="icon" name="icon" accept="image/*" required/>
    </div>
    <div class="grid-x cell">
        <label for="image">Uitgelichte afbeelding(en):</label><br>
        <input type="file" id="image" multiple="multiple" accept="image/*"  name="image[]"/>
    </div> <br>
    <div class="grid-x cell">
        <input type="submit" class="button-style" name="submit" value="Aanmaken">
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
    icon.onchange = function() {
        if(this.files[0].size > 2000000){
            alert("De icon die je kiest is groter dan 2mb. Kies een ander bestand");
            this.value = "";
        }
    };
</script>

