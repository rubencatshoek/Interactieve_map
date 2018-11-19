<?php
// Include model:
include INTERACTIEVE_MAP_PLUGIN_MODEL_DIR . "/checkpointClass.php";

// Declare class variable:
$checkpoints = new checkpointClass();

$id = $_GET['id'];

$checkpointList = $checkpoints->getList();

$singleCheckpoint = $checkpoints->getById($id);

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
// Refers to file size
$fileSize = $_FILES['icon']['size'];
// Refers temporary name of directory in web server
$fileTmpName  = $_FILES['icon']['tmp_name'];
// Refers to file type
$fileType = $_FILES['icon']['type'];
// Refers extension of file
$fileExtension = strtolower(end(explode('.',$fileName)));

// Shows where to place the uploaded file
$uploadPath = $upOne . $uploadDirectory . basename($fileName);

// If $_POST not empty
if (isset($input_array['submit']) && !empty($input_array['submit'])) {
    // If no file is uploaded, don't active checks for uploaded file
    if (empty($fileName)) {
        $checkpoints->update($input_array, $fileName);
        echo '<script>location.href="?page=im_admin_checkpoint_overview";</script>';
        exit;
    }
    // If a file is uploaded, activate checks
    else {

    // If uploaded file doesn't match the available extensions
    if (! in_array($fileExtension,$fileExtensions)) {
        $errors[] = "Dit bestand type is niet mogelijk. Upload een JPEG, JPG of PNG.";
    }

    // If upload file is too big (2MB)
    if ($fileSize > 2000000) {
        $errors[] = "Het bestand kan niet groten zijn dan 2mb.";
    }

    // If no errors are found
    if (empty($errors)) {
        $didUpload = move_uploaded_file($fileTmpName, $uploadPath);

        // If file has been uploaded, start create function and redirect to overview page after that
        if ($didUpload) {
            $checkpoints->update($input_array, $fileName);
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
?>
<form method="post" enctype="multipart/form-data">
    <div class="grid-x cell">
        <h2>Checkpoint wijzigen</h2>
        <label for="title">Titel:</label><br>
        <input type="text" id="title" name="title" value="<?= $singleCheckpoint->getTitle(); ?>" required/>
    </div>
    <div class="grid-x cell">
        <label for="description">Beschrijving:</label><br>
        <textarea type="text" id="description" name="description" rows="5" cols="43"><?= $singleCheckpoint->getDescription(); ?></textarea>
    </div>
    <div class="grid-x cell">
        <label for="icon">Icoon:</label><br>
        <input type="file" id="icon" name="icon"/>
    </div>
    <div class="grid-x cell">
        <label for="image">Uitgelichte afbeelding(en):</label><br>
        <input type="file" id="image" name="image"/>
    </div> <br>
    <div class="grid-x cell">
        <input type="hidden" id="id" name="id" value="<?= $singleCheckpoint->getId(); ?>" required/>
        <input type="submit" class="button-style" name="submit" value="Wijzigen">
    </div>
</form>