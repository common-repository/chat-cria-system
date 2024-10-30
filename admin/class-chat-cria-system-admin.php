<?php

class Chat_Cria_System_Admin {

	private $plugin_name;

	private $version;

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/chat-cria-system-admin.css', array(), $this->version, 'all' );

	}

	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/chat-cria-system-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function register_settings_page() {

		add_submenu_page(
			'tools.php',
			__( 'Chat Cria System', 'chat-cria-system' ),
			__( 'Chat Cria System', 'chat-cria-system' ),
			'manage_options',
			'chat-cria-system',
			array( $this, 'display_settings_page' )
		);

	}

	public function display_settings_page() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/chat-cria-system-admin-display.php';

	}

	public function register_settings() {

		register_setting(
			$this->plugin_name . '-settings',
			$this->plugin_name . '-settings',
			array( $this, 'sandbox_register_setting' )
		);

		add_settings_section(
			$this->plugin_name . '-settings-section',
			__( 'Settings', 'chat-cria-system' ),
			array( $this, 'sandbox_add_settings_section' ),
			$this->plugin_name . '-settings'
		);

		add_settings_field(
			'text-token',
			__( 'Token', 'chat-cria-system' ),
			array( $this, 'sandbox_add_settings_field_input_text' ),
			$this->plugin_name . '-settings',
			$this->plugin_name . '-settings-section',
			array(
				'label_for' => 'text-token',
				'default'   => __( 'Token', 'chat-cria-system' )
			)
		);
	}

	public function sandbox_register_setting( $input ) {

		$new_input = array();

		if ( isset( $input ) ) {
			foreach ( $input as $key => $value ) {
				if ( $key == 'post-types' ) {
					$new_input[ $key ] = $value;
				} else {
					$new_input[ $key ] = sanitize_text_field( $value );
				}
			}
		}

		return $new_input;

	}

	public function sandbox_add_settings_section() {

		return;

	}

	public function sandbox_add_settings_field_input_text( $args ) {

		$field_id = $args['label_for'];
		$field_default = $args['default'];

		$options = get_option( $this->plugin_name . '-settings' );
		$option = $field_default;

		if ( ! empty( $options[ $field_id ] ) ) {
			$option = $options[ $field_id ];
		}
		?>
			<input type="text" name="<?php echo esc_attr($this->plugin_name . '-settings[' . $field_id . ']'); ?>" id="<?php echo esc_attr($this->plugin_name . '-settings[' . $field_id . ']'); ?>" value="<?php echo esc_attr( $option ); ?>" class="regular-text" />
		<?php

	}

}
