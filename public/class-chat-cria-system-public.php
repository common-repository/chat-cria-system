<?php

class Chat_Cria_System_Public {

	private $plugin_name;

	private $version;

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/chat-cria-system-public.css', array(), $this->version, 'all' );
	}

	public function enqueue_scripts() {
		wp_enqueue_script( 'socket', plugin_dir_url( __FILE__ ) . 'js/socket.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/chat-cria-system-public.js', $this->version, false );

		$options = get_option( $this->plugin_name . '-settings' );

		$item_save_text = $options['text-token'];

		$jsarray = array(
			'token'               => esc_js($item_save_text),
			'url_back'                 => esc_js("https://backend.criasystem.tech"),
		);
		wp_localize_script( $this->plugin_name, 'vars', $jsarray ); 

	}

	public function get_unique_cookie_name() {

		$cookie_name = get_option( 'Chat_Cria_System_unique_cookie_name' );
		return $cookie_name;

	}

	public function chat_cria_system_set_cookie( $name, $value = array(), $time = null ) {

		$time = $time != null ? $time : time() + apply_filters( 'chat_cria_system_cookie_expiration', 60 * 60 * 24 * 30 );
		$value = base64_encode(json_encode(stripslashes_deep(sanitize_text_field($value)) ) );
		$expiration = apply_filters( 'chat_cria_system_cookie_expiration_time', $time );

		$_COOKIE[ $name ] = $value;
		setcookie( $name, $value, $expiration, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, false );

	}

	public function chat_cria_system_get_cookie( $name ) {

		if ( isset( $_COOKIE[$name] ) ) {
			return json_decode(base64_decode(stripslashes(sanitize_text_field($_COOKIE[$name] )), true ));
		}

		return array();

	}

	public function get_user_status() {

		$options = get_option( $this->plugin_name . '-settings' );
		if ( ! empty( $options['toggle-status-override'] ) ) {
			$status = $options['toggle-status-override'];
		} else {
			$status = 0;
		}

		return $status;

	}

	public function show_save_button( $item_id = '' ) {

		if ( empty( $item_id ) ) {

			$item_id = get_queried_object_id();

		}

		$options = get_option( $this->plugin_name . '-settings' );
		
		$item_save_text = $options['text-token'];

		$status = $this->get_user_status();

		if ( is_user_logged_in() ) {

			$saved_items = get_user_meta( get_current_user_id(), 'Chat_Cria_Systemd_items', true );

			if ( empty( $saved_items ) ) {
				$saved_items = array();
			}

			if ( in_array( $item_id, $saved_items ) ) {
				$is_saved = true;
			} else {
				$is_saved = false;	
			}

		} else {

			$saved_items = $this->chat_cria_system_get_cookie( $this->get_unique_cookie_name() );

			if ( in_array( $item_id, $saved_items ) ) {
				$is_saved = true;
			} else {
				$is_saved = false;
			}

		}

		if ( $status == 1 && is_user_logged_in() && is_singular() || $status == 0 && is_singular() ) {
			if ( $is_saved == false ) {
				return '<a href="#" class="chat-cria-system-button" data-nonce="' . wp_create_nonce( 'Chat_Cria_System_nonce' ) . '" data-item-id="' . esc_attr( $item_id ) . '"><span class="chat-cria-system-icon"></span><span class="chat-cria-system-text">' . esc_html( $item_save_text ) . '</span></a>';
			}

		}

	}

	public function append_the_button( $content ) {

		$item_id = get_queried_object_id();

		$current_post_type = get_post_type( $item_id );

		$saved_page_id = get_option( 'Chat_Cria_System_saved_page_id' );

		$post_types = array();
		$override = 0;

		$options = get_option( $this->plugin_name . '-settings' );
		if ( ! empty( $options['post-types'] ) ) {
			$post_types = $options['post-types'];
		}
		if ( ! empty( $options['toggle-content-override'] ) ) {
			$override = $options['toggle-content-override'];
		}

		if ( $override == 1 && ! empty( $post_types ) && ! is_page( $saved_page_id ) && in_array( $current_post_type, $post_types ) ) {
			$custom_content = '';
			ob_start();
			echo $this->show_save_button();
			$custom_content .= ob_get_contents();
			ob_end_clean();
			$content = $content . $custom_content;
		}

		return $content;

	}

}
