<?php
/**
 *
 * File documentation
 *
 * @author: Ruben Catshoek <rcatshoek@student.scalda.nl>
 * @since: 16-10-2017
 * @version 0.1 16-10-2017
 */

// Version control
define('INTERACTIEVE_MAP_VERSION', '0.0.1');

// Minimum required Wordpress version for this plugin
define('INTERACTIEVE_MAP_REQUIRED_WP_VERSION', '4.0');

define('INTERACTIEVE_MAP_PLUGIN_BASENAME', plugin_basename(INTERACTIEVE_MAP_PLUGIN));

define('INTERACTIEVE_MAP_PLUGIN_NAME', trim(dirname(INTERACTIEVE_MAP_PLUGIN_BASENAME), '/'));

// Folder structure
define('INTERACTIEVE_MAP_PLUGIN_DIR', untrailingslashit(dirname(INTERACTIEVE_MAP_PLUGIN)));

define('INTERACTIEVE_MAP_PLUGIN_INCLUDES_DIR', INTERACTIEVE_MAP_PLUGIN_DIR . '/includes');

define('INTERACTIEVE_MAP_PLUGIN_INCLUDES_VIEWS_DIR', INTERACTIEVE_MAP_PLUGIN_INCLUDES_DIR . '/views');

define('INTERACTIEVE_MAP_PLUGIN_MODEL_DIR', INTERACTIEVE_MAP_PLUGIN_INCLUDES_DIR . '/model');

define('INTERACTIEVE_MAP_PLUGIN_ADMIN_DIR', INTERACTIEVE_MAP_PLUGIN_DIR . '/admin');

define('INTERACTIEVE_MAP_PLUGIN_ADMIN_VIEWS_DIR', INTERACTIEVE_MAP_PLUGIN_ADMIN_DIR . '/views');

define('INTERACTIEVE_MAP_PLUGIN_IMAGES', INTERACTIEVE_MAP_PLUGIN_DIR . '/images');

