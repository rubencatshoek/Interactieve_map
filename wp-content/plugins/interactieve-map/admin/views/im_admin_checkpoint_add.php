<?php
/**
 *
 * File documentation
 *
 * @author: Ruben Catshoek <rcatshoek@student.scalda.nl>
 * @since: 16-10-2017
 * @version 0.1 16-10-2017
 */

// Include model:
include INTERACTIEVE_MAP_PLUGIN_MODEL_DIR . "/checkpointClass.php";

// Declare class variable:
$checkpoints = new checkpointClass();

// Set base url to current file  and add page specific vars
$base_url = get_admin_url() . 'admin.php';
$params = array('page' => basename(__FILE__, ".php"));

// Add params to base url
$base_url = add_query_arg($params, $base_url);

// Get the POST data in filtered array
$post_array = $checkpoints->getPostValues();

// Get the GET data in filtered array
$get_array = $checkpoints->getGetValues();

// Keep track of current action.
$action = FALSE;
if (!empty($get_array)) {
    // Check actions
    if (isset($get_array['action'])) {
        $action = $checkpoints->handleGetAction($get_array);
    }
}
/*
echo '<pre>';
echo __FILE__.__LINE__.'<br />';
var_dump($post_array);
echo '</pre>';
/*/

// Collect Errors
$error = false;
//Check the POST data
if (!empty($post_array)) {
    // Check the add form:
    $add = false;
    if (isset($post_array['add'])) {
        // Save event categorie
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
// Save event categorie
        $checkpoints->update($post_array);
    }
}

?>
<div class="wrap">
    <?php
    echo($add ? "<p>Added a new category</p>" : "");

    // Check if action == update : then start update form
    echo(($action == 'update') ? '
    <form action="' . $base_url . '"
          method="post">' : '');
    ?>
    <table>
        <caption> Catering categories</caption>
        <thead>
        <tr>
            <th width="10"> Id</th>
            <th width="150"> Categorie</th>
            <th width="200"> Prijs</th>
        </tr>
        </thead>
        <!-- <tr><td colspan = "3" > Event types rij 1 </td ></tr > -->
        <?php
        //*
        if ($catering_categories->getNrOfEventCategories() < 1) {
            ?>
            <tr>
                <td colspan="3">Start adding Categories
            </tr>
        <?php } else {
            $cat_list = $catering_categories->getEventCategoryList();
            //** Show all event categories in the table
            foreach ($cat_list as $event_cat_obj) {
                // Create update link
                $params = array('action' => 'update', 'id' => $event_cat_obj->getId());

                // Add params to base url update link
                $upd_link = add_query_arg($params, $base_url);

                // Create delete link
                $params = array('action' => 'delete', 'id' => $event_cat_obj->getId());

                // Add params to base url delete link
                $del_link = add_query_arg($params, $base_url)
                ?>
                <tr>
                    <td width="10">
                        <?php echo $event_cat_obj->getId(); ?>
                    </td>
                    <?php
                    // If update and id match show update form
                    // Add hidden field id for id transfer
                    if (($action == 'update') && ($event_cat_obj->getId() == $get_array['id'])) {
                        ?>
                        <td width="180"><input type="hidden" name="id" value="<?php
                            echo $event_cat_obj->getId(); ?>">
                            <input type="text" name="naam" value="<?php
                            echo $event_cat_obj->getNaam(); ?>"></td>
                        <td width="200"><input type="text" name="prijs" value="<?php
                            echo $event_cat_obj->getPrijs(); ?>"></td>
                        <td colspan="2"><input type="submit" name="update" value="Updaten"/></td>
                    <?php } else { ?>
                        <td width="180"><?php echo $event_cat_obj->getNaam(); ?></td>
                        <td width="200">€<?php echo $event_cat_obj->getPrijs(); ?></td>
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
                        <td><input type="text" name="prijs"></td>
                    </tr>
                    <tr>
                        <td colspan="2"><input type="submit" name="add" value="Toevoegen"/></td>
                    </tr>
                </table>
        </form>
        <?php
    } // if action !== update
    ?>
</div>