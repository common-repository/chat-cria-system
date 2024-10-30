<?php


class Chat_Cria_System_Deactivator {

	
	public static function deactivate() {

		// Get Saved page id.
		$saved_page_id = get_option( 'Chat_Cria_System_saved_page_id' );

		// Check if the saved page id exists.
		if ( $saved_page_id ) {

			// Delete saved page.
			wp_delete_post( $saved_page_id, true );

			// Delete saved page id record in the database.
			delete_option( 'Chat_Cria_System_saved_page_id' );

		}

		// Get Unique Cookie Name
		$cookie_name = get_option( 'Chat_Cria_System_unique_cookie_name' );

		// Check if the unique cookie name exists.
		if ( $cookie_name ) {

			// Delete unique cookie name from the database.
			delete_option( 'Chat_Cria_System_unique_cookie_name' );

		}

	}

}