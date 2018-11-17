<?php
// Include model:
include INTERACTIEVE_MAP_PLUGIN_MODEL_DIR . "/checkpointClass.php";

// Declare class variable:
$checkpoints = new checkpointClass();

// Get the GET data in filtered array
$get_array = $checkpoints->getList();

if (isset($_POST['delete_x']) && !empty($_POST['delete_x'])) {
//    echo '<script>confirm("Weet je zeker dat je deze checkpoint wilt verwijderen?");</script>';
    $id = $_POST['checkpoint_id'];
    $checkpoints->delete($id);
        echo '<script>location.href=window.location.search;</script>';
        exit;
}
?>
<div class="grid-x">
  <div class="cell">
    <h2>Checkpoint overzicht</h2>

    <table id="checkpoint-overzicht">
  <tr>
    <th width="12%">Titel</th>
    <th width="40%">Beschrijving</th>
    <th width="5%">Icoon</th>
    <th width="8%">Acties</th>
  </tr>
  <?php foreach ($get_array as $checkpoints) {
                       echo '<tr>' .
                        '<td>'. $checkpoints->getTitle() . '</td>' .
                        '<td>'. $checkpoints->getDescription() . '</td>' .
                        '<td><image class="uploaded-icon" src="'. plugins_url() .'/interactieve-map/uploaded_images/' . $checkpoints->getIcon() . '" name="icon"/>' .
                        '<form method="post">' .
                        '<td><a href="?page=im_admin_checkpoint_edit&id=' . $checkpoints->getId() . '"><img class="icon-edit" type="image" src="'. plugins_url() .'/interactieve-map/images/edit.png" name="edit"/></a>' .
                        '<input class="icon-delete" type="image" src="'. plugins_url() .'/interactieve-map/images/delete.png" name="delete"/></td>' .
                        '<input type="hidden" value="' . $checkpoints->getId() . '" name="checkpoint_id"/>' .
                        '</form>' .
                        '</tr>';
  };?>
</table>
</div>
</div>