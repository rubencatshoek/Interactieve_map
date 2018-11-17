<?php
// Include model:
include INTERACTIEVE_MAP_PLUGIN_MODEL_DIR . "/checkpointClass.php";

// Declare class variable:
$checkpoints = new checkpointClass();

$input_array = $_POST;

if (isset($input_array['submit']) && !empty($input_array['submit'])) {
    $checkpoints->create($input_array);
}
?>
<form method="post">
    <div class="grid-x cell">
        <h2>Checkpoint aanmaken</h2>
        <label for="title">Titel:</label><br>
        <input type="text" id="title" name="title" placeholder="Titel" required/>
    </div>
    <div class="grid-x cell">
        <label for="description">Beschrijving:</label><br>
        <textarea type="text" id="description" name="description" placeholder="Beschrijving"
                  rows="5" cols="43"></textarea>
    </div>
    <div class="grid-x cell">
        <label for="icon">Icoon:</label><br>
        <input type="file" id="icon" name="icon" required/>
    </div>
    <div class="grid-x cell">
        <label for="image">Uitgelichte afbeelding(en):</label><br>
        <input type="file" id="image" name="image"/>
    </div> <br>
    <div class="grid-x cell">
        <input type="submit" class="button-style" name="submit" value="Aanmaken">
    </div>
</form>