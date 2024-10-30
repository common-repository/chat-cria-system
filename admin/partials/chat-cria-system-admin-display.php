<?php
?>

<div id="wrap">
	<form method="post" action="options.php">
		<?php
			settings_fields( 'chat-cria-system-settings' );
			do_settings_sections( 'chat-cria-system-settings' );
			submit_button();
		?>
	</form>
</div>