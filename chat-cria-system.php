<?php

/**
 *
 * Plugin Name:       Chat Cria System
 * Plugin URI:        https://criasystem.tech
 * Description:       Chat para interação com o usuário do seu site, para funcionar é preciso ter cadastro no site https://criasystem.tech e configurar o token do chat
 * Version:           1.0.4
 * Author:            Maurício Spagnol
 * Author URI:        https://www.linkedin.com/in/maur%C3%ADcio-spagnol-4881b2124/
 * License:           GPL v2 or later
 * Text Domain:       chat-cria-system
 * Domain Path:       /languages
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

function activate_chat_cria_system() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-chat-cria-system-activator.php';
	Chat_Cria_System_Activator::activate();
}

function deactivate_chat_cria_system() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-chat-cria-system-deactivator.php';
	Chat_Cria_System_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_chat_cria_system' );
register_deactivation_hook( __FILE__, 'deactivate_chat_cria_system' );

require plugin_dir_path( __FILE__ ) . 'includes/class-chat-cria-system.php';

function run_Chat_Cria_System() {

	$plugin = new Chat_Cria_System();
	$plugin->run();

}
run_Chat_Cria_System();
