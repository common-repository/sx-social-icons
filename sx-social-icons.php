<?php
/**
 * ------------------- SX WORDPRESS ---------------------- 
 *
 * @link              http://www.sabri-elgueder.tn/
 * @since             1.0.0
 * @package           Sx_social_icons
 *
 * @wordpress-plugin
 * Plugin Name:       Sx Social Icons
 * Plugin URI:        http://www.pixelsingenierie.net/
 * Description:       Sx Social Icons provides you with an easy way to display various popular social icons via widgets. You can drag the widget in your sidebars and change the settings from the widget form itself.
 * Version:           1.0.0
 * Author:            Sabri El Gueder
 * Author URI:        http://www.sabri-elgueder.tn/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sx-social-icons
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Sx_Social_Icons' ) ) :

/**
 * Main Sx_Social_Icons Class.
 */
class Sx_Social_Icons {

	/**
	 * Plugin version.
	 * @var string
	 */
	const VERSION = '1.0';

	/**
	 * Instance of this class.
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin.
	 */
	private function __construct() {
		// Load plugin text domain.
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Include classes.
		$this->includes();

		// Register Widget.
		add_action( 'widgets_init', array( $this, 'register_widget' ) );

		// Enqueue Scripts.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		// Social Protocols.
		add_filter( 'kses_allowed_protocols' , array( $this, 'allowed_social_protocols' ) );
	}

	/**
	 * Return an instance of this class.
	 * @return object A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Load Localisation files.
	 *
	 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
	 *
	 * Locales found in:
	 *      - WP_LANG_DIR/sx-social-icons/sx-social-icons-LOCALE.mo
	 *      - WP_LANG_DIR/plugins/sx-social-icons-LOCALE.mo
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'sx-social-icons' );

		load_textdomain( 'sx-social-icons', WP_LANG_DIR . '/sx-social-icons/sx-social-icons-' . $locale . '.mo' );
		load_plugin_textdomain( 'sx-social-icons', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Includes.
	 */
	private function includes() {
		include_once( 'includes/class-widget-sx-social-icons.php' );
	}

	/**
	 * Register Widgets.
	 */
	public function register_widget() {
		register_widget( 'TG_Widget_Sx_Social_Icons' );
	}

	/**
	 * Enqueue styles and scripts.
	 */
	public function enqueue_scripts() {
		if ( apply_filters( 'sx_social_icons_is_active_widget', is_active_widget( false, false, 'redweb_sx_social_icons', true ) ) ) {
			wp_enqueue_style( 'sx-social-icons', plugins_url( 'assets/css/sx-social-icons.css', __FILE__ ), array(), self::VERSION );
		}
	}

	/**
	 * Enqueue admin styles and scripts.
	 */
	public function admin_enqueue_scripts() {
		$screen = get_current_screen();
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		// Register Styles and Scripts.
		wp_register_style( 'sx-social-icons-widgets', plugins_url( '/assets/css/widgets.css', __FILE__ ), array(), self::VERSION );
		wp_register_script( 'sx-social-icons-widgets', plugins_url( '/assets/js/widgets' . $suffix . '.js', __FILE__ ), array( 'jquery' ), self::VERSION );

		if ( $screen && in_array( $screen->id, array( 'widgets', 'customize' ) ) ) {
			wp_enqueue_style( 'sx-social-icons-widgets' );
			wp_enqueue_script( 'sx-social-icons-widgets' );
		}
	}

	/**
	 * List of allowed social protocols in HTML attributes.
	 * @param  array $protocols Array of allowed protocols.
	 * @return array
	 */
	public function allowed_social_protocols( $protocols ) {
		$social_protocols = array(
			'skype'
		);

		return array_merge( $protocols, $social_protocols );
	}
}

add_action( 'plugins_loaded', array( 'Sx_Social_Icons', 'get_instance' ), 0 );

endif;
