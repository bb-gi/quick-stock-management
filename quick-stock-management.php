
<?php
/**
 * Plugin Name:       Quick Stock Management for WooCommerce
 * Plugin URI:        https://example.com/quick-stock-management
 * Description:       Provides a quick interface to manage WooCommerce product stock status.
 * Version:           1.0.0
 * Author:            Bertrand Bonnet
 * Author URI:        https://www.bertrand-bonnet.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       quick-stock-management
 * Domain Path:       /languages
 * WC requires at least: 6.0
 * WC tested up to: 8.8
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Quick_Stock_Management' ) ) :

/**
 * Main Quick_Stock_Management Class.
 *
 * @class Quick_Stock_Management
 * @version 1.0.0
 */
final class Quick_Stock_Management {

    /**
     * Plugin version.
     *
     * @var string
     */
    public $version = '1.0.0';

    /**
     * The single instance of the class.
     *
     * @var Quick_Stock_Management
     * @since 1.0.0
     */
    protected static $_instance = null;

    /**
     * Main Quick_Stock_Management Instance.
     *
     * Ensures only one instance of Quick_Stock_Management is loaded or can be loaded.
     *
     * @since 1.0.0
     * @static
     * @see QSM()
     * @return Quick_Stock_Management - Main instance.
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Quick_Stock_Management Constructor.
     */
    public function __construct() {
        $this->define_constants();
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Define QSM Constants.
     */
    private function define_constants() {
        define( 'QSM_PLUGIN_FILE', __FILE__ );
        define( 'QSM_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
        define( 'QSM_VERSION', $this->version );
        define( 'QSM_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
        define( 'QSM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
    }

    /**
     * Include required core files used in admin and on the frontend.
     */
    public function includes() {
        // Include admin class for admin-specific functionalities
        if ( is_admin() ) {
            require_once QSM_PLUGIN_PATH . 'includes/class-qsm-admin.php';
        }
    }

    /**
     * Hook into actions and filters.
     */
    private function init_hooks() {
        add_action( 'plugins_loaded', array( $this, 'on_plugins_loaded' ), -1 );
    }

    /**
     * On plugins_loaded action.
     */
    public function on_plugins_loaded() {
        // Init localization
        load_plugin_textdomain( 'quick-stock-management', false, dirname( QSM_PLUGIN_BASENAME ) . '/languages' );
    }

}

endif;

/**
 * Returns the main instance of QSM.
 *
 * @since  1.0.0
 * @return Quick_Stock_Management
 */
function QSM() {
    return Quick_Stock_Management::instance();
}

// Global for backwards compatibility.
$GLOBALS['quick_stock_management'] = QSM();


