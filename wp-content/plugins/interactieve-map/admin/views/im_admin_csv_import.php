<?php
/**
 * Created by PhpStorm.
 * User: Ruben
 * Date: 8-1-2019
 * Time: 10:39
 */

// Include model:
include INTERACTIEVE_MAP_PLUGIN_MODEL_DIR . "/checkpointClass.php";

// Declare class variable:
$checkpoints = new checkpointClass();

// Putting the post values in a variable
$input_array = $_POST;

// Refers to uploaded file name
$fileName = $_FILES['csv_import']['name'];

// Refers to extention of file
$fileExtension = strtolower(end(explode('.',$fileName)));

// Get the only useable file type
$fileType = ['csv'];

//If the submit button for keuzedeel was clicked and a file was selected:
if (isset($input_array['submit']) && !empty($_FILES['csv_import']['name'])) {
    if (!in_array($fileExtension, $fileType)) {
        echo("Kies een CSV bestand");
    } else {
        //Execute the import
        $result = $checkpoints->importCSV($_FILES['csv_import']);

        echo '<script>location.href="?page=interactieve-map-admin";</script>';
        exit;
    }
}
?>
<form method="post" enctype="multipart/form-data">
    <div class="main small-12 medium-12 large-12 cell">
        <h2>CSV importeren</h2>
        <input type="file" name="csv_import" id="csv_import" accept=".csv">

        <div class="grid-x cell">
            <input type="submit" class="button-style" name="submit" id="submit" value="Importeer">
        </div>
    </div>
</form>