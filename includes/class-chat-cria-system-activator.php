<?php

class Chat_Cria_System_Activator {

	public static function activate() {

		$saved_page_args = array(
			'post_title'   => __( 'Saved', 'chat-cria-system' ),
			'post_content' => '[chat-cria-systemd]',
			'post_status'  => 'publish',
			'post_type'    => 'page'
		);

		$saved_page_id = wp_insert_post( $saved_page_args );

		add_option( 'Chat_Cria_System_saved_page_id', $saved_page_id );


		$site_url = get_bloginfo( 'url' );
		$site_name = get_bloginfo( 'name' );
		$suffix = '-chat-cria-systemd-items';

		$cookie_name = $site_url . $site_name . $suffix;

		$cookie_name = str_replace( array( '[\', \']' ), '', $cookie_name );
		$cookie_name = preg_replace( '/\[.*\]/U', '', $cookie_name );
		$cookie_name = preg_replace( '/&(amp;)?#?[a-z0-9]+;/i', '-', $cookie_name );
		$cookie_name = htmlentities( $cookie_name, ENT_COMPAT, 'utf-8' );
		$cookie_name = preg_replace( '/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '\\1', $cookie_name );
		$cookie_name = preg_replace( array( '/[^a-z0-9]/i', '/[-]+/' ) , '-', $cookie_name );
		$cookie_name = strtolower( trim( $cookie_name, '-' ) );

		// Save the value to the database.
		add_option( 'Chat_Cria_System_unique_cookie_name', $cookie_name );

	}

}