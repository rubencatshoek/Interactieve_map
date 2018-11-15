<?php
// Include model:
include
    INTERACTIEVE_MAP_PLUGIN_MODEL_DIR . "/checkpointClass.php";
// Declare class variable:
$checkpoints = new checkpointClass();
// Set base url to current file  and add page specific vars
$base_url = get_admin_url() . 'admin.php';
$params = array('page' => basename(__FILE__, ".php"));
// Add params to base url
$base_url = add_query_arg($params, $base_url);

// Get the GET data in filtered array
$get_array = $checkpoints->getList();

//// Keep track of current action.
//$action = FALSE;
//if (!empty($get_array)) {
//    // Check actions
//    if (isset($get_array['action'])) {
//        $action = $checkpoints->handleGetAction($get_array);
//    }
//}

// Get the POST data in filtered array
$post_array = $checkpoints->getList();

var_dump($post_array);

// Collect Errors
$error = false;
//Check the POST data
if (!empty($post_array)) {
    // Check the add form:
    $add = false;
    if (isset($post_array['add'])) {
        // Save event type
        $result = $checkpoints->save($post_array);
        if ($result) {
            // Save was succesfull
            $add = true;
        } else {
            // Indicate error
            $error = true;
        }
    }

    // Check the update form:
    if (isset($post_array['update'])) {
        // Save event types
        $checkpoints->update($post_array);
    }
}


?>
<style>
#checkpoint-overzicht {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

#checkpoint-overzicht td, #checkpoint-overzicht th {
    border: 1px solid #ddd;
    padding: 8px;
}

#checkpoint-overzicht tr:nth-child(even){background-color: #f2f2f2;}

#checkpoint-overzicht tr:hover {background-color: #ddd;}

#checkpoint-overzicht th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    background-color: #009d45;
    color: white;
}
</style>
<div class="grid-x">
  <div class="cell">
    <h2>Checkpoint overzicht</h2>

    <table id="checkpoint-overzicht">
  <tr>
    <th>Titel</th>
    <th>Beschrijving</th>
    <th>Icoon</th>
    <th>Acties</th>
  </tr>
  <?php foreach ($get_array as $checkpoints) {
                       echo '<tr>' .
                        '<td>'. $checkpoints->getTitle() . '</td>' .
                        '<td>'. $checkpoints->getDescription() . '</td>' .
                        '<td>'. $checkpoints->getIcon() . '</td>' .
                        '<form method="post">' .
                        '<td><input type="image" src="/images/delete.png" name="delete" /></td>' .
                        '</tr>';
  }?>
</table>
</div>
</div>






<div class="wrap">
    <?php
    echo($add ? "<p>Added a new type</p>" : "");
    // Check if action ==update : then start update form
    echo(($action == 'update') ? '<form action="' . $base_url . '" method="post">' : '');
    ?>

    <table>
        <caption>Catering bedrijven</caption>
        <thead>
        <tr>
            <th width="10">Id</th>
            <th width="150">Naam</th>
            <th width="200">Beschrijving</th>
        </tr>
        </thead>
        <!-- <tr><td colspan="3">Event types rij 1</td></tr> -->
        <?php
        //*
        if ($checkpoints->getNrOfEventTypes() < 1) {
            ?>
            <tr>
                <td colspan="3">Start adding Catering Companies
            </tr>
        <?php } else {
            $type_list = $checkpoints->getEventTypeList();
            //** Show all event types in the table
            foreach ($type_list as $type_list_obj) {

                // Create update link
                $params = array('action' => 'update', 'id' => $type_list_obj->getId());

                // Add params to base url update link
                $upd_link = add_query_arg($params, $base_url);

                // Create delete link
                $params = array( 'action' => 'delete', 'id' => $type_list_obj->getId());

                // Add params to base url delete link
                $del_link = add_query_arg( $params, $base_url );
                ?>

                <tr>
                    <td width="10">
                        <?php echo $type_list_obj->getId(); ?>
                    </td>
                    <?php
                    // If update and id match show update form
                    // Add hidden field id for id transfer
                    if (($action == 'update') && ($type_list_obj->getId() == $get_array['id'])) {
                        ?>
                        <td width="180"><input type="hidden" name="id" value="<?php
                            echo $type_list_obj->getId(); ?>">
                            <input type="text" name="naam" value="<?php
                            echo $type_list_obj->getNaam(); ?>"></td>
                        <td width="200"><input type="text" name="beschrijving" value="<?php
                            echo $type_list_obj->getBeschrijving(); ?>"></td>
                        <td colspan="2"><input type="submit" name="update" value="Updaten"/></td>
                    <?php } else { ?>
                        <td width="180"><?php echo $type_list_obj->getNaam(); ?></td>
                        <td width="200"><?php echo $type_list_obj->getBeschrijving(); ?></td>
                        <?php if ($action !== 'update') {
                            // If action is update don’t show the action button
                            ?>
                            <td><a href="<?php echo $upd_link; ?>">Update</a></td>
                            <td><a href="<?php echo $del_link; ?>">Delete</a></td>
                        <?php } // if action !== update?>
                    <?php } // if acton !== update?>
                </tr>
                <?php
            }
        }
        ?>
    </table>
    <?php
    // Check if action = update : then end update form
    echo(($action == 'update') ? '</form>' : '');
    /** Finally add the new entry line only if no update action **/
    if ($action !== 'update') {
        ?>
        <form action="<?php echo $base_url; ?>" method="post">
            <tr>
                <table>
                    <tr>
                        <td colspan="2"><input type="text" name="naam"></td>
                        <td><input type="text" name="beschrijving"></td>
                    </tr>
                    <tr>
                        <td colspan="2"><input type="submit" name="add" value="Toevoegen"/></td>
                    </tr>
                </table>
        </form>
        <?php
    } // if action !== update
    ?>