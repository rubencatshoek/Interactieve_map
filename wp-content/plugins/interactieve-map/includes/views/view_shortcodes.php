<?php
/**
 * @author: Ruben Catshoek <rcatshoek@student.scalda.nl>
 * @since: 16-10-2017
 * @version 0.1 16-10-2017
 */

// Add the main view shortcode
add_shortcode('interactieve_map_main_view', 'load_main_view');

function load_main_view($atts, $content = NULL)
{
    // Include the main view
    include INTERACTIEVE_MAP_PLUGIN_INCLUDES_VIEWS_DIR . '/interactieve_map_main_view.php';
}