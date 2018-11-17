<?php
// Include model:
include INTERACTIEVE_MAP_PLUGIN_MODEL_DIR . "/checkpointClass.php";

// Declare class variable:
$checkpoints = new checkpointClass();

$id = $_GET['id'];

$checkpointList = $checkpoints->getList();

$singleCheckpoint = $checkpoints->getById($id);

$input_array = $_POST;

if (isset($input_array['submit']) && !empty($input_array['submit'])) {
    $checkpoints->update($input_array);
}
?>
<form method="post">
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