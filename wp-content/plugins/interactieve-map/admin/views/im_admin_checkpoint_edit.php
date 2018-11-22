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

// Store all image foreseen and unforseen errors here
$imageErrors = '';

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

$emptyFileError = '';

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

            if (! in_array($imageFileExtension,$fileExtensions)) {
                $imageErrors[] = "Dit bestand type is niet mogelijk. Upload een JPEG, JPG of PNG." . '<br>';
            }

            // If no errors are found
            if(empty($imageErrors)) {
                //Upload the file
                $didImageUpload = move_uploaded_file($tmpFilePath, $newFilePath);
            } else {
                $imageErrors = "Kon bestand niet uploaden, probeer het opnieuw. ";
                echo ($imageErrors);
            }
        }
    }
}

// If submit
if (isset($input_array['submit']) && !empty($input_array['submit'])) {
    // If no file is uploaded, don't active checks for uploaded file
    if (empty($fileName) && (empty($imageErrors))) {
        // Refer to different update if no file has been uploaded
        $checkpoints->update($input_array, $fileName, $imageFileName);
        echo '<script>location.href="?page=im_admin_checkpoint_overview";</script>';
        exit;
    }
    else {
        // If uploaded file doesn't match the available extensions
        if (!in_array($fileExtension, $fileExtensions)) {
            $errors[] = "Dit bestand type is niet mogelijk. Upload een JPEG, JPG of PNG.";
        }

        // If no errors are found
        if (empty($errors) &&  empty($imageErrors)) {
            $didUpload = move_uploaded_file($fileTmpName, $uploadPath);

            // If file has been uploaded, start create function and redirect to overview page after that
            if ($didUpload) {
                $checkpoints->update($input_array, $fileName, $imageFileName);
                echo '<script>location.href="?page=im_admin_checkpoint_overview";</script>';
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

// If clicked on delete button
if (isset($_POST['delete']) && !empty($_POST['delete'])) {
    $images->delete($input_array);
    echo '<script>location.href=window.location.search;</script>';
    exit;
}
?>
<form method="post" enctype="multipart/form-data" id="wijzigen">
    <div class="grid-x cell">
        <h2>Checkpoint wijzigen</h2>
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
                '<input type="hidden" name="image_id" value="' . $image->getId() . '">';
        }; ?>
    </div>
    <br>
    <div class="grid-x cell">
        <input type="submit" class="button-style" name="submit" form="wijzigen" value="Wijzigen">
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