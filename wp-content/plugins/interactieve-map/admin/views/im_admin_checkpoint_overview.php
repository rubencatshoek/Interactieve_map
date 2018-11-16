<?php
// Include model:
include INTERACTIEVE_MAP_PLUGIN_MODEL_DIR . "/checkpointClass.php";
// Declare class variable:
$checkpoints = new checkpointClass();
// Set base url to current file  and add page specific vars
$base_url = get_admin_url() . 'admin.php';
$params = array('page' => basename(__FILE__, ".php"));
// Add params to base url
$base_url = add_query_arg($params, $base_url);

// Get the GET data in filtered array
$get_array = $checkpoints->getList();

// Get the POST data in filtered array
$post_array = $checkpoints->getList();

var_dump($_POST);

if (isset($_POST['edit_x']) && !empty($_POST['edit_x'])) {
    $checkpoints->update();
}

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
                        '<td><image class="uploaded-icon" src="'. plugins_url() .'/interactieve-map/uploaded_images/' . $checkpoints->getIcon() . '" name="edit"/>' .
                        '<form method="post">' .
                        '<td><input class="icon-edit" type="image" src="'. plugins_url() .'/interactieve-map/images/edit.png" name="edit"/>' .
                        '<input class="icon-delete" type="image" src="'. plugins_url() .'/interactieve-map/images/delete.png" name="delete"/></td>' .
                        '<input type="hidden" value="' . $checkpoints->getId() . '" name="checkpoint_id"/>' .
                        '</form>' .
                        '</tr>';
  };?>
</table>
</div>
</div>