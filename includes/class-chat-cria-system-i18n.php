<?php

class Chat_Cria_System_i18n {


	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'chat-cria-system',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
