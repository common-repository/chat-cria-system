<?php

class Chat_Cria_System {

	protected $loader;

	protected $plugin_name;

	protected $version;

	public function __construct() {

		$this->plugin_name = 'chat-cria-system';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	private function load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-chat-cria-system-loader.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-chat-cria-system-i18n.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-chat-cria-system-admin.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-chat-cria-system-public.php';

		$this->loader = new Chat_Cria_System_Loader();

	}

	private function set_locale() {

		$plugin_i18n = new Chat_Cria_System_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	private function define_admin_hooks() {

		$plugin_admin = new Chat_Cria_System_Admin( $this->get_plugin_name(), $this->get_version() );

		// Hook our settings page
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'register_settings_page' );

		// Hook our settings
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_settings' );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	private function define_public_hooks() {

		$plugin_public = new Chat_Cria_System_Public( $this->get_plugin_name(), $this->get_version() );

		// Append our button
		$this->loader->add_action( 'the_content', $plugin_public, 'append_the_button', 45 );

		// Add our Shortcodes
		$this->loader->add_shortcode( 'chat-cria-system', $plugin_public, 'register_save_unsave_shortcode' );
		$this->loader->add_shortcode( 'chat-cria-systemd', $plugin_public, 'register_saved_shortcode' );

		// Save/unsave AJAX
		$this->loader->add_action( 'wp_ajax_save_unsave_item', $plugin_public, 'save_unsave_item' );
		$this->loader->add_action( 'wp_ajax_nopriv_save_unsave_item', $plugin_public, 'save_unsave_item' );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	public function run() {
		$this->loader->run();
	}


	public function get_plugin_name() {
		return $this->plugin_name;
	}


	public function get_loader() {
		return $this->loader;
	}

	public function get_version() {
		return $this->version;
	}

}
