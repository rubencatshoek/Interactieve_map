<?php

defined('ABSPATH') OR exit;

/**
 *
 * Plugin Name: Interactieve map plugin
 * Plugin URI: localhost
 * Description: With this plugin you can create checkpoints that show hotspots in Sas van Gent.
 * Author: Ruben Catshoek
 * Author URI:http://www.rubencatshoek.nl
 * Version: 0.0.1
 * Text Domain: interactieve-map-plugin
 * Domain Path: languages
 *
 * This is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with your plugin. If not, see <http://www.gnu.org/licenses/>.
 */

// Define the plugin name:
define('INTERACTIEVE_MAP_PLUGIN', __FILE__);

// Include the general definition file:
require_once plugin_dir_path(__FILE__) . 'includes/defs.php';

/* Register the hooks */
register_activation_hook(__FILE__, array('Interactievemap', 'on_activation'));
register_deactivation_hook(__FILE__, array('Interactievemap', 'on_deactivation'));

class Interactievemap
{
    public function __construct()
    {
        // Fire a hook before the class is setup.
        do_action('interactieve_map_pre_init');
        // Load the plugin.
        add_action('init', array($this, 'init'), 1);
    }

    public static function on_activation()
    {
        if (!current_user_can('activate_plugins'))
            return;
        $plugin = isset($_REQUEST['plugin']) ? $_REQUEST['plugin'] : '';
        check_admin_referer("activate-plugin_{$plugin}");

        // Add the theme capabilities
        Interactievemap::add_plugin_caps();

        # Uncomment the following line to see the function in action
        # exit( var_dump( $_GET ) );
    }

    public static function on_deactivation()
    {
        if (!current_user_can('activate_plugins'))
            return;
        $plugin = isset($_REQUEST['plugin']) ? $_REQUEST['plugin'] : '';
        check_admin_referer("deactivate-plugin_{$plugin}");

        // Remove the theme specific capabilities
        Interactievemap::remove_plugin_caps();

        # Uncomment the following line to see the function in action
        # exit( var_dump( $_GET ) );
    }

    /**
     * Loads the plugin into WordPress.
     *
     * @since 1.0.0
     */
    public function init()
    {
        // Run hook once Plugin has been initialized.
        do_action('interactieve_map_init');
        // Load admin only components.
        if (is_admin()) {
            // Load all admin specific includes
            $this->requireAdmin();
            // Setup admin page
            $this->createAdmin();
        }
        // Load the view shortcodes
        $this->loadViews();
    }

    /**
     * Loads all admin related files into scope.
     *
     * @since 1.0.0
     */
    public function requireAdmin()
    {
        // Admin controller file
        require_once INTERACTIEVE_MAP_PLUGIN_ADMIN_DIR . '/Interactievemap_AdminController.php';
    }

    /**
     * Admin controller functionality
     */
    public function createAdmin()
    {
        Interactievemap_AdminController::prepare();
    }

    /**
     *  Load the view shortcodes:
     */
    public function loadViews()
    {
        include INTERACTIEVE_MAP_PLUGIN_INCLUDES_VIEWS_DIR . '/view_shortcodes.php';
    }
}
// Instantiate the class
$interactieve_map = new Interactievemap();