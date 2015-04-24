<?php
/*
Plugin Name: WooCommerce Detailed Category
Plugin URI: 
Description: WooCommerce plugin for detailed product on specified categorie
Author: Samuel Boutron
Author URI: samuel.boutron@gmail.com
Version: 1.2

	Copyright: © 2012 Samuel Boutron (email : samuel.boutron@gmail.com)
	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
	
TO BE DONE :
- find why wp_enqueue_scripts and wp_print_scripts doesn't work the same !!!
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if (!class_exists('WCDetailedCategory')) {

class WCDetailedCategory {

	const VERSION = "2.0.351203";
	
	/**
	 * @var string
	 */
	public $version = '2.0.3';

	/**
	 * @var string
	 */
	public $plugin_url;

	/**
	 * @var string
	 */
	public $plugin_path;

	/**
	 * @var string
	 */
	public $template_url;
	
	/**
	 * @var string
	 */
	public $img_url;
	
	/**
	 * Gets things started by adding an action to initialize this plugin once
	 * WooCommerce is known to be active and initialized
	 */
	public function __construct() {
		add_action( 'woocommerce_init', array(&$this, 'init_detailed_category' ));
		
		// Installation
		if (is_admin() && !defined('DOING_AJAX')) $this->install();
	}
	
	/**
	* Init WooCommerce RUltralight extension once we know WooCommerce is active
	*
	* @access public
	* @return void
	*/
	public function init_detailed_category() {
		global $woocommerce;		
		
		// Define version constant
		define( 'WOOCOMMERCE_DETAILED_CAT_VERSION', $this->version );

		// Include required files
		$this->includes();
		
		// Classes/actions loaded for the admin side

	}
	
	/**
	 * Include required core files used in admin and on the frontend.
	 *
	 * @access public
	 * @return void
	 */
	function includes() {
		if ( is_admin() )
			$this->admin_includes();
		if ( defined('DOING_AJAX') )
			$this->ajax_includes();
		if ( ! is_admin() || defined('DOING_AJAX') )
			$this->frontend_includes();
	}
	
	/**
	 * Include required admin files.
	 *
	 * @access public
	 * @return void
	 */
	public function admin_includes() {
		//include_once( 'wc-detailed-cat-admin-init.php' );			// Admin scripts
		include_once( 'admin/wc-admin-detailed-cat.php' );			// Admin section
	}
	
	/**
	 * Include required ajax files.
	 *
	 * @access public
	 * @return void
	 */
	public function ajax_includes() {
	}
	
	/**
	 * Include required frontend files.
	 *
	 * @access public
	 * @return void
	 */
	public function frontend_includes() {
		// frontend page
		include_once("wc-detailed-cat-frontend-init.php");
	}
		
	/**
	 * Run every time since the activation hook is not executed when updating a plugin
	 */
	private function install() {
		if(get_option('wc_detailed_category') != WCDetailedCategory::VERSION) {
			$this->upgrade();
			
			// new version number
			update_option('wc_detailed_category', WCDetailedCategory::VERSION);
		}
	}
	
	/**
	 * Run when plugin version number changes
	 */
	private function upgrade() {
		}
	
	/**
	 * Runs various functions when the plugin first activates (and every time
	 * its activated after first being deactivated), and verifies that
	 * the WooCommerce plugin is installed and active
	 * 
	 * @see register_activation_hook()
	 * @link http://codex.wordpress.org/Function_Reference/register_activation_hook
	 */
	public static function on_activation() {
		// checks if the woocommerce plugin is running and disables this plugin if it's not (and displays a message)
		if (!is_plugin_active('woocommerce/woocommerce.php')) {
			deactivate_plugins(plugin_basename(__FILE__));
			wp_die(__('The WooCommerce rultralight detailed image product requires <a href="http://www.woothemes.com/woocommerce/" target="_blank">WooCommerce</a> to be activated in order to work. Please install and activate <a href="http://www.woothemes.com/woocommerce/" target="_blank">WooCommerce</a> first. <a href="'.admin_url('plugins.php').'"> <br> &laquo; Go Back</a>'));
		}
		
		// set version number
		update_option('wc_detailed_category', WCDetailedCategory::VERSION);
	}
}

/**
 * instantiate class
 */
$wc_detailed_category = new WCDetailedCategory();

} // class exists check

/**
 * run the plugin activation hook
 */
register_activation_hook(__FILE__, array('WCDetailedCategory', 'on_activation'));