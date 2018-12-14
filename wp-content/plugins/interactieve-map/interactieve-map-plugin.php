<?php

defined('ABSPATH') OR exit;

/**
 *
 * Plugin Name: Interactieve map plugin
 * Plugin URI: localhost
 * Description: With this plugin you can create checkpoints that show hotspots in Sas van Gent.
 * Author: Ruben Catshoek
 * Author URI:http://www.rubencatshoek.nl
 * Version: 0.0.2
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
register_activation_hook( __FILE__, array( 'Interactievemap', 'createDb' ) );

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
    }

    public static function on_deactivation()
    {
        if (!current_user_can('activate_plugins'))
            return;
        $plugin = isset($_REQUEST['plugin']) ? $_REQUEST['plugin'] : '';
        check_admin_referer("deactivate-plugin_{$plugin}");
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

    public static function createDb() {

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        //Calling $wpdb;
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        //Names of the tables that will be added to the db
        $checkpoint                  = $wpdb->prefix . "im_checkpoint";
        $image                       = $wpdb->prefix . "im_image";

        //Create the checkpoint table
        $sql = "CREATE TABLE IF NOT EXISTS $checkpoint (
  checkpoint_id INT NOT NULL AUTO_INCREMENT,
  title TEXT(45) NOT NULL,
  description LONGTEXT NULL,
  icon_path VARCHAR(1024) NOT NULL,
  latitude FLOAT(20,16) NOT NULL,
  longitude FLOAT(20,16) NOT NULL,
  PRIMARY KEY  (checkpoint_id))
ENGINE = InnoDB $charset_collate";
        dbDelta( $sql );

        //Create the image table
        $sql = "CREATE TABLE IF NOT EXISTS $image (
  image_id INT NOT NULL AUTO_INCREMENT,
  fk_checkpoint_id INT NOT NULL,
  image_path VARCHAR(1024) NULL,
  PRIMARY KEY (image_id),
  INDEX fk_wp_im_image_wp_im_checkpoint_idx (fk_checkpoint_id ASC),
  CONSTRAINT fk_wp_im_image_wp_im_checkpoint
    FOREIGN KEY (fk_checkpoint_id)
    REFERENCES $checkpoint (checkpoint_id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB $charset_collate";
        dbDelta( $sql );
    }
}
// Instantiate the class
$interactieve_map = new Interactievemap();