<?php
/**
 * Plugin Name: Caniincasa My Dog
 * Plugin URI: https://www.caniincasa.it
 * Description: Sistema completo per gestire i profili dei propri cani: schede dettagliate, calendario vaccinazioni, reminder, esportazione PDF per veterinario, tracker peso e molto altro.
 * Version: 1.0.0
 * Author: Caniincasa
 * Author URI: https://www.caniincasa.it
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: caniincasa-my-dog
 * Domain Path: /languages
 *
 * @package Caniincasa_My_Dog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Plugin constants
define( 'CANIINCASA_MY_DOG_VERSION', '1.0.0' );
define( 'CANIINCASA_MY_DOG_PATH', plugin_dir_path( __FILE__ ) );
define( 'CANIINCASA_MY_DOG_URL', plugin_dir_url( __FILE__ ) );
define( 'CANIINCASA_MY_DOG_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Main Plugin Class
 */
class Caniincasa_My_Dog {

	/**
	 * Instance
	 *
	 * @var Caniincasa_My_Dog
	 */
	private static $instance = null;

	/**
	 * Get instance
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 */
	private function __construct() {
		$this->includes();
		$this->init_hooks();
	}

	/**
	 * Include required files
	 */
	private function includes() {
		// Core
		require_once CANIINCASA_MY_DOG_PATH . 'includes/class-post-type.php';
		require_once CANIINCASA_MY_DOG_PATH . 'includes/class-acf-fields.php';
		require_once CANIINCASA_MY_DOG_PATH . 'includes/class-dashboard.php';
		require_once CANIINCASA_MY_DOG_PATH . 'includes/class-ajax-handlers.php';
		require_once CANIINCASA_MY_DOG_PATH . 'includes/class-calendar.php';
		require_once CANIINCASA_MY_DOG_PATH . 'includes/class-pdf-export.php';
		require_once CANIINCASA_MY_DOG_PATH . 'includes/class-newsletter-block.php';
		require_once CANIINCASA_MY_DOG_PATH . 'includes/class-calculators.php';

		// Admin
		if ( is_admin() ) {
			require_once CANIINCASA_MY_DOG_PATH . 'admin/class-admin.php';
		}
	}

	/**
	 * Initialize hooks
	 */
	private function init_hooks() {
		// Activation/Deactivation
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

		// Init
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );

		// Enqueue assets
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	/**
	 * Plugin activation
	 */
	public function activate() {
		// Create tables if needed
		$this->create_tables();

		// Flush rewrite rules
		Caniincasa_My_Dog_Post_Type::register();
		flush_rewrite_rules();

		// Set default options
		if ( ! get_option( 'caniincasa_my_dog_newsletter_enabled' ) ) {
			update_option( 'caniincasa_my_dog_newsletter_enabled', '1' );
		}
	}

	/**
	 * Plugin deactivation
	 */
	public function deactivate() {
		flush_rewrite_rules();
	}

	/**
	 * Create database tables
	 */
	private function create_tables() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();
		$table_name = $wpdb->prefix . 'dog_vaccinations';

		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			dog_id bigint(20) NOT NULL,
			vaccine_name varchar(255) NOT NULL,
			vaccine_date date NOT NULL,
			next_date date DEFAULT NULL,
			veterinarian varchar(255) DEFAULT NULL,
			notes text DEFAULT NULL,
			reminder_sent tinyint(1) DEFAULT 0,
			created_at datetime DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY  (id),
			KEY dog_id (dog_id),
			KEY next_date (next_date)
		) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );

		// Weight tracker table
		$table_weight = $wpdb->prefix . 'dog_weight_tracker';

		$sql_weight = "CREATE TABLE IF NOT EXISTS $table_weight (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			dog_id bigint(20) NOT NULL,
			weight decimal(5,2) NOT NULL,
			measurement_date date NOT NULL,
			notes text DEFAULT NULL,
			created_at datetime DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY  (id),
			KEY dog_id (dog_id),
			KEY measurement_date (measurement_date)
		) $charset_collate;";

		dbDelta( $sql_weight );

		// Notes/Diary table
		$table_notes = $wpdb->prefix . 'dog_notes';

		$sql_notes = "CREATE TABLE IF NOT EXISTS $table_notes (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			dog_id bigint(20) NOT NULL,
			note_date date NOT NULL,
			note_type varchar(50) DEFAULT 'general',
			note_content text NOT NULL,
			created_at datetime DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY  (id),
			KEY dog_id (dog_id),
			KEY note_date (note_date)
		) $charset_collate;";

		dbDelta( $sql_notes );
	}

	/**
	 * Initialize plugin
	 */
	public function init() {
		// Register post type
		Caniincasa_My_Dog_Post_Type::register();

		// Initialize components
		Caniincasa_My_Dog_ACF_Fields::init();
		Caniincasa_My_Dog_Dashboard::init();
		Caniincasa_My_Dog_AJAX::init();
		Caniincasa_My_Dog_Calendar::init();
		Caniincasa_My_Dog_PDF::init();
		Caniincasa_My_Dog_Newsletter_Block::init();
		Caniincasa_My_Dog_Calculators::init();

		// Admin
		if ( is_admin() ) {
			Caniincasa_My_Dog_Admin::init();
		}
	}

	/**
	 * Load text domain
	 */
	public function load_textdomain() {
		load_plugin_textdomain(
			'caniincasa-my-dog',
			false,
			dirname( CANIINCASA_MY_DOG_BASENAME ) . '/languages'
		);
	}

	/**
	 * Enqueue frontend scripts
	 */
	public function enqueue_scripts() {
		// CSS
		wp_enqueue_style(
			'caniincasa-my-dog',
			CANIINCASA_MY_DOG_URL . 'assets/css/my-dog.css',
			array(),
			CANIINCASA_MY_DOG_VERSION
		);

		// JS
		wp_enqueue_script(
			'caniincasa-my-dog',
			CANIINCASA_MY_DOG_URL . 'assets/js/my-dog.js',
			array( 'jquery' ),
			CANIINCASA_MY_DOG_VERSION,
			true
		);

		// Localize
		wp_localize_script(
			'caniincasa-my-dog',
			'caniincasaMyDog',
			array(
				'ajaxurl'   => admin_url( 'admin-ajax.php' ),
				'nonce'     => wp_create_nonce( 'my_dog_nonce' ),
				'uploading' => __( 'Caricamento...', 'caniincasa-my-dog' ),
				'strings'   => array(
					'confirm_delete' => __( 'Sei sicuro di voler eliminare questo cane?', 'caniincasa-my-dog' ),
					'error'          => __( 'Si è verificato un errore. Riprova.', 'caniincasa-my-dog' ),
				),
			)
		);
	}

	/**
	 * Enqueue admin scripts
	 */
	public function admin_enqueue_scripts( $hook ) {
		// Admin styles
		wp_enqueue_style(
			'caniincasa-my-dog-admin',
			CANIINCASA_MY_DOG_URL . 'assets/css/admin.css',
			array(),
			CANIINCASA_MY_DOG_VERSION
		);
	}
}

/**
 * Initialize plugin
 */
function caniincasa_my_dog() {
	return Caniincasa_My_Dog::instance();
}

// Start the plugin
caniincasa_my_dog();
